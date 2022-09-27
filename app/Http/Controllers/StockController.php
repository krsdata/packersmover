<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Stations;
use App\StationLogo;
use App\Tanks;
use App\TankStock;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class StockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 12;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $station=$request->get('station');
        $product=$request->get('PROD_NAMES');
        $tank=$request->get('tank');
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }

        

        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $tankstock=TankStock::orderBy('created_at', 'desc');
            if($user->hasRole('admin')){
                $station_arrray=Stations::where('active','1')->get();
            }else{
                $station_arrray=Stations::where('active','1')->whereIn("id", explode(',', $user->stations_id))->get();
            }

            if(!empty($dates)){
                $adates = explode("-",$dates);
                $af = trim($adates[0]);
                $at = trim($adates[1]);
                $from = date("Y-m-d",strtotime($af));
                $to = date("Y-m-d",strtotime($at));
                $fromtime = date("H:i:s",strtotime($af));
                $totime = date("H:i:s",strtotime($at));
                $from8 = date("Y-m-d H:i:s",strtotime($af));
                $to8 = date("Y-m-d H:i:s",strtotime($at));
                $tankstock->whereBetween('DATE_TIME', [$from8, $to8]);
            
            }

            if(isset($station)){
                $tankstock->whereIn('station_id',$station);
            }

            if(!empty($product)){
                $tankstock->where('product_name','=',$product);
            }

            if(!empty($tank)){
                $t_id=en_de_crypt($tank, 'd');
                $tankstock->where('tank_id','=',$t_id);
            }
            $s_count = $tankstock->count();
            $total_liter = $tankstock->sum('VOL');
            $tankstock = $tankstock->paginate($perPage)->appends(request()->query());
            $fullUrl = $request->fullUrl();
            $fullUrl = str_replace('/stock', '/stock-download',$fullUrl);
            $txtUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=txt';
            $xmlUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=xml';
            return view('pages/admin/stock/index',compact('tankstock','fullUrl','txtUrl','xmlUrl','station_arrray','dates','station','product','tank','s_count','total_liter'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        return view('home');   
    } 

    public function stock_create(Request $request){
        $user = Auth::user();
        if($user->hasRole('admin')){
                $Stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
                   $Stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();  
        }
        //echo "<pre>"; print_r($Stations); die();
        return view('pages/admin/stock/create',compact('Stations'));
    }

    public function stock_store(Request $request) {
        $validator_stock_info = $this->validator_stock_info_update($request->all());
        if ($validator_stock_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_stock_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();
            if($request['Station_Id']){
                $data['Station_Id']=@$request['Station_Id'][0];
            }

            $data=strip_tag_function($data);
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = TankStock::findorfail($id);
                $obj_data->station_id = @$data['Station_Id'];
                $obj_data->product_name = $data['product_name'];
                $obj_data->invoice_no = $data['invoice_no'];
                $obj_data->track_no = $data['track_no'];
                $obj_data->driver_name = $data['driver_name'];
                $obj_data->stock_image = $data['stock_imgval'];
                $obj_data->active = $data['stock_status'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Stock has been updated Sucessfully!','redirect_url'=>'/admin/stock-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Stock Updation failed!','redirect_url'=>'/admin/stock-list']);
                }
            }else{

                $obj = new TankStock();
                $obj->station_id = @$data['Station_Id'];
                $obj->product_name = $data['product_name'];
                $obj->invoice_no = $data['invoice_no'];
                $obj->track_no = $data['track_no'];
                $obj->driver_name = $data['driver_name'];
                $obj->stock_image = $data['stock_imgval'];
                $obj->active = $data['stock_status'];
                if(!empty($data['tank_id'])){
                    $tid = en_de_crypt($data['tank_id'], 'd'); 
                    $obj->tank_id = $tid;
                    $if_exist=tanks::find($tid);
                    if($if_exist){
                        $remaining_capacity=100-$if_exist->capacity;
                        $capacity=$if_exist->capacity+$data['total_number_liters_ordered'];
                        if($capacity <=100){
                            $obj->Pump_Start_Volume_Total = $if_exist->capacity;
                            $obj->Pump_End_Volume_Total = $capacity;
                            $obj->VOL = $data['total_number_liters_ordered'];
                            $update_capacity=tanks::where('id',$tid)->update(["capacity"=>$capacity]);
                        }else{
                                return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Remaining Capacity of tank is '.$remaining_capacity,'redirect_url'=>'/admin/stock-list']);
                            }
                    }
                }    
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Stock has been added Sucessfully!','redirect_url'=>'/admin/stock-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Stock Insertion failed!','redirect_url'=>'/admin/stock-list']);
                }
            }
        }
    }

    protected function validator_stock_info_update(array $data) {
        $cuser = Auth::user();
        if(empty($data['id'])){
             $au['total_number_liters_ordered'] = 'required';
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $au['invoice_no'] = 'required|unique:tank_stock,invoice_no,'.$id;
        }else{
             $au['invoice_no'] = 'required|max:255|unique:tank_stock';
            $au['Station_Id'] = 'required';
            $au['track_no'] = 'required';
            $au['driver_name'] = 'required';
        }
        return Validator::make($data, $au);
    } 

    public function stock_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $stock = TankStock::findorfail($id);
        if($user->hasRole('admin')){
                $Stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
                   $Stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();  
        }
        return view('pages/admin/stock/create',compact('stock','Stations'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('stock_image')) {
            $file = $request->file('stock_image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/stock';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    public function listDowload(Request $request){
        $scurrency='Amt'; $sdecimal=0; $slogo=''; $stitle='';
        $user = Auth::user();
        $role_name = $user->type;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
                $dates = $s_datese;
        }
        $station=$request->get('station');
        $product=$request->get('PROD_NAMES');
        $tank=$request->get('tank');
        $type = $request->get('type');
        
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $tankstock=TankStock::orderBy('created_at', 'desc');
            if(!empty($dates)){
                $adates = explode("-",$dates);
                $af = trim($adates[0]);
                $at = trim($adates[1]);
                $from = date("Y-m-d",strtotime($af));
                $to = date("Y-m-d",strtotime($at));
                $fromtime = date("H:i:s",strtotime($af));
                $totime = date("H:i:s",strtotime($at));
                $from8 = date("Y-m-d H:i:s",strtotime($af));
                $to8 = date("Y-m-d H:i:s",strtotime($at));
                $tankstock->whereBetween('DATE_TIME', [$from8, $to8]);
            
            }

            if(isset($station)){
                $tankstock->whereIn('station_id',$station);
            }

            if(!empty($product)){
                $tankstock->where('product_name','=',$product);
            }

            if(!empty($tank)){
                $t_id=en_de_crypt($tank, 'd');
                $tankstock->where('tank_id','=',$t_id);
            }

            // get_station details
            $station_details=Stations::find(@$station[0]);
            if(!empty($station_details)){
                $scurrency=$station_details->currency_code;
                $sdecimal=$station_details->decimal_point;
                $stitle=$station_details->title;
                // logo 
                $station_logo=StationLogo::where('station_id',$station_details)->first();
                if(!empty($station_logo)){
                      $slogo=$station_logo->name;
                }
                
            }

            
            if($type == "txt"){
                $txt = "";
                $datas = $tankstock->get();
                $s_count = $tankstock->count();
                $total_liter = $tankstock->sum('VOL');
                $txttitle = $dates.".txt";
                $txt .= " Stock List: ".$dates."  \n \n ";

                if(!empty(@$stitle)){
                   $txt .= "Station: ".$stitle."\n \n";
                }

                if(!empty($product)){
                   $txt .= "Product: ".$product."\n \n";
                }

                if(!empty($tank)){
                   $txt .= "tank: ".$tank."\n \n";
                }


                if(!empty($datas)){
                    foreach ($datas as $lkey => $list) {
                        $apro[] = array("INVOICE NUMBER" => $list->invoice_no, "TRACK NUMBER" => $list->track_no,"Pump Start Volume Total" => $list->Pump_Start_Volume_Total,"Pump End Volume Total" => $list->Pump_End_Volume_Total,"VOL" => $list->VOL, "DATE_TIME" => $list->DATE_TIME);
                        
                    }
                }

                $alabel[]= array(
                        "STOCK COUNT" =>$s_count." Count",
                        "TOTAL LITER" =>number_format((float)$total_liter,$sdecimal,'.',',')." Ltr",
                );

                

                $renderer = new ArrayToTextTable($alabel);
                $renderer->showHeaders(true);
                $txt .= $renderer->render(true);
                $txt .= "\n \n \n";
                

                $renderer = new ArrayToTextTable($apro);
                $renderer->showHeaders(true);
                $txt .= $renderer->render(true);
                $txt .= "\n \n \n";



                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-disposition: attachment; filename='.urlencode($txttitle));
                header('Content-Length: '.strlen($txt));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Expires: 0');
                header('Pragma: public');
                echo $txt;

                
                exit;
            }elseif($type == "xml"){
                
                $xls = "";
                $datas = $tankstock->get();
                $s_count = $tankstock->count();
                $total_liter = $tankstock->sum('VOL');
                $xlstitle = $dates.".xls";
                $xls .=  " Stock List: ".$dates."  \n \n";

                if(!empty(@$stitle)){
                   $xls .= "Station: ".$stitle."\n \n";
                }

                if(!empty($product)){
                   $xls .= "Product: ".$product."\n \n";
                }

                if(!empty($tank)){
                   $xls .= "tank: ".$tank."\n \n";
                }
                if(!empty($datas)){
                    foreach ($datas as $lkey => $list) {
                        $apro[] = array("INVOICE NUMBER" => $list->invoice_no, "TRACK NUMBER" => $list->track_no,"Pump Start Volume Total" => $list->Pump_Start_Volume_Total,"Pump End Volume Total" => $list->Pump_End_Volume_Total,"VOL" => $list->VOL, "DATE_TIME" => $list->DATE_TIME);
                        
                    }
                }
                
                $alabel[]= array(
                    "STOCK COUNT" =>$s_count." Count",
                    "TOTAL LITER" =>number_format((float)$total_liter,$sdecimal,'.',',')." Ltr",
                );

                $flag = false;
                    if(!empty($alabel)){
                        foreach ($alabel as $pkey => $row) {
                            if (!$flag) {
                                $xls .=  implode("\t", array_keys($row)) . "\r\n";
                                $flag = true;
                            }
                            $xls .=  implode("\t", array_values($row)) . "\r\n";
                        }
                    }
                    $xls .= "\r\n \r\n \r\n";
                // }

                $flag = false;
                if(!empty($apro)){
                    foreach ($apro as $pkey => $row) {
                        if (!$flag) {
                            $xls .=  implode("\t", array_keys($row)) . "\r\n";
                            $flag = true;
                        }
                        $xls .=  implode("\t", array_values($row)) . "\r\n";
                    }
                }
                $xls .= "\r\n \r\n \r\n";

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-disposition: attachment; filename='.urlencode($xlstitle));
                header('Content-Length: '.strlen($xls));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Expires: 0');
                header('Pragma: public');
                echo $xls;
                exit;
            }else{
                $datas = $tankstock->get();
                $s_count = $tankstock->count();
                $total_liter = $tankstock->sum('VOL');
                $view = view("pages.admin.stock.pdf_view",compact('dates','s_count','total_liter','datas','scurrency','sdecimal','slogo','product','tank'))->render();
                $pdfhtml = '<html><head>
                <style>
                .card {
                    display: block;
                    background-color: #fff;
                    background-clip: border-box;
                    border: 1px solid #d2d2dc;
                    border-radius: 0;
                    padding: 0px!important;
                    background: #fefefe;
                }
                .modal-body, .card-body {
                    padding: 5px!important;
                }
                .mt-1{
                    margin-top: 0.25rem !important;
                }
                .w-100 {
                    width: 100% !important;
                }
                .justify-content-center {
                    justify-content: center !important;
                }
                .table-responsive {
                    display: block;
                    width: 100%;
                }
                .pt-3, .py-3 {
                    padding-top: 1rem !important;
                }
                .text-left {
                    text-align: left !important;
                }
                .table-responsive > .table-bordered {
                    border: 0;
                }
                table {
                    border-collapse: collapse;
                    max-width: 100%;
                    overflow:hidden;
                }
                * {
                    box-sizing: border-box;
                }
                .table-striped th {
                    color: #331CBF;
                }
                .table thead th {
                    vertical-align: bottom;
                    border-bottom: 0px solid #dddddd;
                    border-top: 0;
                    border-bottom-width: 0px;
                    font-weight: 500;
                    font-size: .875rem;
                    text-transform: uppercase;
                    line-height: 1;
                    white-space: nowrap;
                    padding: 1.25rem 0.9375rem;
                    text-align: inherit;
                }
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #eee;
                }
                .table td {
                    font-size: 0.875rem;
                    padding: .875rem 0.9375rem;
                    vertical-align: middle;
                    line-height: 1;
                    white-space: nowrap;
                    border-top: 0px solid #dddddd;
                }
                .table td button{
                    background: transparent;
                    border-color : transparent;
                }

                .row {
                    display: flex;
                    flex-wrap: wrap;
                    margin-right: -15px;
                    margin-left: -15px;
                }
                .stretch-card {
                    display: -webkit-flex;
                    display: flex;
                    -webkit-align-items: stretch;
                    align-items: stretch;
                    -webkit-justify-content: stretch;
                    justify-content: stretch;
                }
                .grid-margin {
                    margin-bottom: 1.875rem;
                }
                .col-md-4, .lightGallery .image-tile {
                    width: 25%;
                    float:left;
                    margin-right:7.3%;
                }
                .bg-primary, .settings-panel .color-tiles .tiles.primary {
                    background-color: #331CBF !important;
                }
                .border-0 {
                    border: 0 !important;
                }
                .border-radius-2 {
                    border-radius: 2rem;
                }
                .card {
                    box-shadow: none;
                    -webkit-box-shadow: none;
                    -moz-box-shadow: none;
                    -ms-box-shadow: none;
                }
                .stretch-card > .card {
                    width: 100%;
                    min-width: 100%;
                }
                .card .card-body {
                    padding: 1.25rem 1.75rem;
                }
                .card-body {
                    flex: 1 1 auto;
                    padding: 1.25rem;
                    box-shadow: none;
                }
                .flex-xl-row {
                    flex-direction: row !important;
                }
                .icon-rounded-inverse-primary {
                    background: white;
                    width: 1.875rem;
                    height: 1.875rem;
                    border-radius: 50%;
                    text-align: center;
                    box-shadow: none;
                    float:left;
                    margin:25px 0px 0px 30px;
                    display:none;
                }
                .text-white {
                    color: #ffffff !important;
                    width: 90.875rem;
                    margin-left:50px;
                    margin-top:0px;
                }
                .cust-card-dash p {
                    font-size: 0.77rem;
                }
                .font-weight-medium {
                    font-weight: 500;
                }
                .text-uppercase {
                    text-transform: uppercase !important;
                }
                .text-xl-left {
                    text-align: left !important;
                }
                .mt-xl-0, .my-xl-0 {
                    margin-top: 0 !important;
                }
                p {
                    margin-bottom: 0px;
                    line-height: 1.5rem;
                }
                .align-items-xl-baseline {
                    align-items: baseline !important;
                    margin-top: -10px;
                }
                .flex-xl-row {
                    flex-direction: row !important;
                }
                .cust-card-dash h3 {
                    font-size: 1.3rem;
                }
                .mb-lg-0, .my-lg-0 {
                    margin-bottom: 0 !important;
                }
                .mr-1, .mx-1 {
                    margin-right: 0.25rem !important;
                }
                .mb-0, .my-0 {
                    margin-bottom: 0 !important;
                }
                small, .small {
                    font-size: 80%;
                    font-weight: 400;
                }
                tr { page-break-inside: avoid; }

                </style></head><body><div class="modal-body" id="stockdetailbody">';
                $pdfhtml .= "<p>Stock List: ".$dates."</p><p>Station: ".$stitle."</p><p>&nbsp;</p>";
                $pdfhtml .= $view;
                $pdfhtml .= '</div></body></html>';     
                $pdftitle = $dates.".pdf";

                /*
                $dompdf = new Dompdf();
                $dompdf->loadHtml($pdfhtml);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $pdf = $dompdf->output();
                print_r($pdf);
                */
                $pdf = new Pdf($pdfhtml);
                if (!$pdf->send($pdftitle)) {
                    $error = $pdf->getError();
                    print_r($error);
                }

                // header('Content-Description: File Transfer');
                // header('Content-Type: application/octet-stream');
                // header('Content-disposition: attachment; filename='.urlencode($pdftitle));
                // header('Content-Length: '.strlen($pdf));
                // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                // header('Expires: 0');
                // header('Pragma: public');
                // echo $pdf;
                exit;
            }
            }    
    }

     public function detail($id){
        $station_name='';$tank_name='';
        $tankstock_id = en_de_crypt($id, 'd'); 
        $tankstock_data = TankStock::findorfail($tankstock_id); 
        if($tankstock_data){
            // Tank Trn 
            $txt = "Tank Stock Detail \n\n\n\n";

            // station details
            $station_details=Stations::find($tankstock_data->station_id);
            if(!empty($station_details)){
               $station_name=$station_details->title;    
            }

            // tank details
             $tank_details=Tanks::find($tankstock_data->tank_id);
            if(!empty($tank_details)){
               $tank_name=$tank_details->tank_name;    
            }

            
            
            @$tankstockdata=array(
                                             
                                             array( "key" => "ID", "value" => ''),
                                             array( "key" => "Station Name", "value" => $station_name),
                                             array( "key" => "Product Name", "value" => $tankstock_data->product_name),
                                             array( "key" => "Tank Name", "value" => $tank_name),
                                             array( "key" => "Invoice Number", "value" => $tankstock_data->invoice_no ),
                                             array( "key" => "Track Number", "value" => $tankstock_data->track_no),
                                             array( "key" => "Driver Name", "value" => $tankstock_data->driver_name),
                                             array( "key" => "Pump Start Volume Total", "value" => $tankstock_data->Pump_Start_Volume_Total),
                                             array( "key" => "Pump End Volume Total", "value" => $tankstock_data->Pump_End_Volume_Total),
                                             array( "key" => "Vol", "value" => $tankstock_data->VOL),
                                             array( "key" => "Date Time", "value" => $tankstock_data->DATE_TIME),
                                             
                                             

                                             
                                );
            //} 
            $renderer = new ArrayToTextTable($tankstockdata);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";
        
            
            //xls
            $xls = "\r\n \r\n \r\n";
            $xls .= "Tank Stock Detail \r\n";
            $xls .= "\r\n \r\n \r\n";
            $xls .=  "ID"."\t".""."\r\n";
            $xls .=  "Station Name"."\t".$station_name."\r\n";
            $xls .=  "Product Name"."\t".$tankstock_data->product_name."\r\n";
            $xls .=  "Tank Name"."\t".$tank_name."\r\n";
            $xls .=  "Invoice Number"."\t".$tankstock_data->invoice_no."\r\n";
            $xls .=  "Track Number"."\t".$tankstock_data->track_no."\r\n";
            $xls .=  "Driver Name"."\t".$tankstock_data->driver_name."\r\n";
            $xls .=  "Pump Start Volume Total"."\t".$tankstock_data->Pump_Start_Volume_Total."\r\n";
            $xls .=  "Pump End Volume Total"."\t".$tankstock_data->Pump_End_Volume_Total."\r\n";
            $xls .=  "VOL"."\t".$tankstock_data->VOL."\r\n";
            $xls .=  "Date Time"."\t".$tankstock_data->DATE_TIME."\r\n";
           
            
            
           
            $file_name = "stock";
            $file_name = str_replace(".xml","",$file_name);
            $view = view("pages.admin.stock.detail_view",compact('tankstock_data','station_name','tank_name'))->render();

            
            // instantiate and use the dompdf class
            $dompdf = new Dompdf();
            $pdfhtml = '<html><head><style>
            .card {
                display: block;
                background-color: #fff;
                background-clip: border-box;
                border: 1px solid #d2d2dc;
                border-radius: 0;
                padding: 0px!important;
                background: #fefefe;
            }
            .modal-body, .card-body {
                padding: 15px!important;
            }
            .mt-1{
                margin-top: 0.25rem !important;
            }
            .w-100 {
                width: 100% !important;
            }
            .justify-content-center {
                justify-content: center !important;
            }
            .table-responsive {
                display: block;
                width: 100%;
            }
            .pt-3, .py-3 {
                padding-top: 1rem !important;
            }
            .text-left {
                text-align: left !important;
            }
            .table-responsive > .table-bordered {
                border: 0;
            }
            h6{
                font-size: .9375rem;
                margin-bottom: 0.5rem;
                font-family: inherit;
                font-weight: 500;
                line-height: 1.2;
                color: inherit;
                font-weight: bold;
            }
            table {
                border-collapse: collapse;
                max-width: 100%;
                overflow:hidden;
            }
            .table-responsive > .table-bordered {
                border: 0;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #dddddd;
            }
            .table-bordered th, .table-bordered td {
                padding: 2px 15px;
                font-size: 12px;
            }
            * {
                box-sizing: border-box;
            }
            .table-bordered th, .table-bordered td {
                padding: 2px 15px;
                font-size: 12px;
            }

           tr { page-break-inside: avoid; }
            </style></head><body><div class="modal-body" id="trndetailbody">';
            $pdfhtml .= $view;
            $pdfhtml .= '</div></body></html>';
            $dompdf->loadHtml($pdfhtml);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $output = $dompdf->output();
            $pdffile = public_path().DIRECTORY_SEPARATOR."rct".DIRECTORY_SEPARATOR.$file_name.".pdf";
            file_put_contents($pdffile, $output);
            $pdffileurl = url('/')."/rct/".$file_name.".pdf";
            return response()->json(['success' => TRUE,'op'=>'detail','msg_type'=>'success','msg'=>'Record found Sucessfully!','html'=>$view,'txt'=>$txt, 'xls' => $xls, 'file_name' => $file_name, "pdffileurl" => $pdffileurl]);
        }else{
            return response()->json(['success' => FALSE,'op'=>'detail','msg_type'=>'error','msg'=>'No record found']);
        }
    }

    public function invoice_Details(request $request){
        //echo "<pre>"; print_r($request->all()); die();
        $id=$request['id']; 
        if($id){
           $id = en_de_crypt($id, 'd');
           $if_exist=TankStock::where('id',$id)->first();
            if(!$if_exist){
               return response()->json(["success"=>"False","msg" => "Stock not found","data"=>""]);
            }else{
                   if($if_exist->station_id){
                      $station_logo=StationLogo::where('station_id',$if_exist->station_id)->first();
                      if(!empty($station_logo)){
                        $slogo=$station_logo->name;
                      }
                   }
                   return response()->json(["success"=>"True","msg" => "Stock details","data"=>$if_exist,'station_logo'=>$slogo]); 
            }
        }
    }

}

     
   

