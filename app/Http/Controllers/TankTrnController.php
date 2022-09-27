<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Tanks;
use App\StationLogo;
use App\Stations;
use App\Trn;
use Mail;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use App\Notifications\TankTrnDetail;
use Input, Redirect, Session, Response, DB;
class TankTrnController extends Controller
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
        $currency_code = "Amt"; $decimal_point=0; $station_selected=''; $tank_selected=''; $product = $tank =array(); 
        $user = Auth::user();
        $role_name = $user->type;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $user_id = $user->id;
        $s_datese=$request->get('dates');
        $station_selected =$request->get('station');
        $tank_selected =$request->get('tank');
        if($tank_selected){
            $tank_selected=en_de_crypt($tank_selected, 'd');
        }
        if(!empty($s_datese)){
                $dates = $s_datese;
        }

        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            if($user->hasRole('admin')){
                $Stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
                if($station_selected){
                   $tank = Tanks::select('tank_name','id')->groupBy('tank_name','id')->get();    
                }
            }else{
                   $Stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
                    if($station_selected){
                        $tank = Tanks::select('tank_name','id')->where('station_id',$station_selected)->groupBy('tank_name','id')->get();
                    }  
            }
            
            $perPage = 10;
            
            $product_selected =$request->get('product');
            $tanktrn = Trn::orderBy('created_at', 'desc');

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
                $tanktrn->whereBetween('FDC_DATE_TIME', [$from8, $to8]);
            
            }

            if($station_selected){
                $tanktrn->where('stations_id','=',$station_selected);
                $station_data=Stations::where('id',$station_selected)->first();
                $currency_code = $station_data->currency_code; $decimal_point=$station_data->decimal_point;
                // $product=Trn::where('stations_id',$station_selected)->select('FDC_PROD_NAME')
                // ->groupBy('FDC_PROD_NAME')->get();
                $sname=$station_data->name;
                $stitle=$station_data->title;
            }

            if($product_selected){
                $tanktrn->where([['stations_id','=',@$station_selected],['FDC_PROD_NAME','=',@$product_selected]]);
            }

            if($tank_selected){
                $tanktrn->where('tank_id','=',$tank_selected);
                $tank_data=Tanks::where('id','=',$tank_selected)->first();
                if(!empty($tank_data)){
                    $station_data=Stations::where('id',$tank_data->station_id)->first();
                    if(!empty($station_data)){
                       $currency_code = $station_data->currency_code; $decimal_point=$station_data->decimal_point;}
                       
                }
            }

            $t_amt = $tanktrn->sum('AMO');
            $t_ltr = $tanktrn->sum('VOL');
            $Pump_Start_Volume_Total = $tanktrn->sum('Pump_Start_Volume_Total');
            $Pump_End_Volume_Total = $tanktrn->sum('Pump_End_Volume_Total');
            $t_count = $tanktrn->count();
            $tanktrn = $tanktrn->paginate($perPage)->appends(request()->query());
            $fullUrl = $request->fullUrl();
            $fullUrl = str_replace('/tank-trn', '/tank-trn-download',$fullUrl);
            $txtUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=txt';
            $xmlUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=xml';
            if ($request->ajax()){
                $view = view("pages.admin.tanktrn.table_view",compact('tanktrn','user_id','role_name','dates','fullUrl','txtUrl','xmlUrl','tank','t_amt','t_ltr','Pump_Start_Volume_Total','t_count','Pump_End_Volume_Total','currency_code','decimal_point','Stations','station_selected','tank_selected'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{
                return view('pages.admin.tanktrn.index',compact('tanktrn','user_id','role_name','dates','fullUrl','txtUrl','xmlUrl','tank','t_amt','t_ltr','Pump_End_Volume_Total','t_count','Pump_Start_Volume_Total','currency_code','decimal_point','Stations','station_selected','tank_selected','product_selected'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home'); 
    }

    public function listDowload(Request $request)
    {
        $currency_code = "Amt"; $decimal_point=0; $station_selected=''; $tank_selected=''; $product=array();
        $station_logos=''; $sname=''; $stitle=''; $tank_name=''; $product_selected=''; $vrn=''; $tin=''; $service_station=''; $serial_number=''; $info='';$no=''; 
        $user = Auth::user();
        $role_name = $user->type;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $user_id = $user->id;
        $search_input="";
        $station_selected =$request->get('station');
        $tank_selected =$request->get('tank');
        $product_selected =$request->get('product');
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
                $dates = $s_datese;
        }
        
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $perPage = 10;
            $tank = Tanks::select('tank_name')->groupBy('tank_name')->get();
            $tanktrn = Trn::orderBy('created_at', 'desc');
            
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
                $tanktrn->whereBetween('FDC_DATE_TIME', [$from8, $to8]);
            }

            if($station_selected){
                $tanktrn->where('stations_id','=',$station_selected);
                $station_data=Stations::where('id',$station_selected)->first();
                $currency_code = $station_data->currency_code; $decimal_point=$station_data->decimal_point;
                // $product=Trn::where('stations_id',$station_selected)->select('FDC_PROD_NAME')
                // ->groupBy('FDC_PROD_NAME')->get();
                $sname=$station_data->name;
                $stitle=$station_data->title;
                $vrn=$station_data->vrn;
                $tin=$station_data->tin;
                $no=$station_data->tel;
                $info=$station_data->info;
                $service_station=$station_data->service_station;
                $serial_number=$station_data->serial_number;

                // get station logo 
                $station_logo=StationLogo::where('station_id',$station_selected)->first();
                if(!empty($station_logo)){
                    $station_logos=$station_logo->name;
                }
            }

            if($product_selected){
                $tanktrn->where([['stations_id','=',@$station_selected],['FDC_PROD_NAME','=',@$product_selected]]);
            }

            if($tank_selected){
                $tank_selected=en_de_crypt($tank_selected, 'd');
                $tanktrn->where('tank_id','=',$tank_selected);
                $tank_data=Tanks::find($tank_selected);
                if(!empty($tank_data)){
                    $tank_name=$tank_data->tank_name;
                    $station_data=Stations::where('id',$tank_data->station_id)->first();
                    if(!empty($station_data)){
                       $currency_code = $station_data->currency_code; $decimal_point=$station_data->decimal_point;}
                    // get station logo 
                    $station_logo=StationLogo::where('station_id',$tank_data->station_id)->first();
                    if(!empty($station_logo)){
                        $station_logos=$station_logo->name;
                    }   
                }
                
            }

            $tanktrn = $tanktrn->get();
            $t_amt = $tanktrn->sum('AMO');
            $t_ltr = $tanktrn->sum('VOL');
            $Pump_Start_Volume_Total = $tanktrn->sum('Pump_Start_Volume_Total');
            $Pump_End_Volume_Total = $tanktrn->sum('Pump_End_Volume_Total');
            $t_count = $tanktrn->count();
            $data = array("total_ltr" => $t_ltr, "total_tran" => $t_count, "Pump_Start_Volume_Total" => $Pump_Start_Volume_Total,"Pump_End_Volume_Total" => $Pump_End_Volume_Total);

            $type = $request->get('type');
            if($type == "txt"){
                $txt = "";
                $txttitle = $dates."-".$search_input.".txt";
                
                if(!empty($product_selected)){
                    $txt .= "Product Name:".$product_selected."\n \n";
                }

                if(!empty($tank_name)){
                    $txt .= "Tank: ".$tank_name."\n \n";
                }
		       $txt .= "Station Title : ".@$stitle."\n \n Address: ".@$info."\n \nTelephone No: ".@$no."  \n \nTIN: ".@$tin."  VRN: ".@$vrn."\n \nService Station : ".$service_station."\n \n  Serial Number :".$serial_number."   \n \n \n \n";
                if(!empty($tanktrn)){
                    foreach ($tanktrn as $lkey => $list) {

                        $tanktrn[$lkey]->PRICE = number_format((float)$tanktrn[$lkey]->PRICE,$decimal_point,'.',',');
                        $tanktrn[$lkey]->AMO = number_format((float)$tanktrn[$lkey]->AMO,$decimal_point,'.',',');
                        $tanktrn[$lkey]->VOL = number_format((float)$tanktrn[$lkey]->VOL,2,'.',',');
                        if($list->RFID_CARD_USED == 0){
                            $list->RFID_CARD_USED = "No";
                            $list->CUST_NAME = "";
                        }else{
                            $list->RFID_CARD_USED = "Yes";
                        }

                        $apro[] = array("RDG No" => $list->RDG_ID, "FP No" => $list->FP, "Nozzle No" => $list->NOZ, "Product Name" => $list->FDC_PROD_NAME, "Pump Start Volume Total" => number_format((float)$list->Pump_Start_Volume_Total,2,'.',','), "Pump End Volume Total" => number_format((float)$list->Pump_End_Volume_Total,2,'.',','), "Volume By Total" => $list->VOL);
                        
                    }
                }
                
                $alabel[]= array(
                    "Transactions" =>number_format($data['total_tran'])." Count",
                    "Total Liter" =>number_format((float)$data['total_ltr'],2,'.',',')." Ltr",
                    "TOTAL PUMP START VOLUME" =>number_format($data['Pump_Start_Volume_Total'])." Ltr",
                    "TOTAL PUMP END VOLUME" =>number_format((float)$data['Pump_End_Volume_Total'],2,'.',',')." Ltr",
                );
                $renderer = new ArrayToTextTable($alabel);
                $renderer->showHeaders(true);
                $txt .= $renderer->render(true);
                $txt .= "\n \n \n";
                // }

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
                $xlstitle = $dates."-".$search_input.".xls";
                // $xls .= "Tank Trn List: ".$dates."  \n \n Station Name: ".$sname." \n \n Product Name: ".$product_selected."\n \n Tank: ".$tank_name."\n \n \n \n";

                if(!empty($product_selected)){
                    $xls .= "Product Name:".$product_selected."\n \n";
                }

                if(!empty($tank_name)){
                    $xls .= "Tank: ".$tank_name."\n \n";
                }


		        $xls .= "Station Title : ".@$stitle."\n \n Address: ".@$info."\n \nTelephone No: ".@$no."  \n \nTIN: ".@$tin."  VRN: ".@$vrn."\n \nService Station : ".$service_station."\n \n  Serial Number :".$serial_number."   \n \n \n \n";
                if(!empty($tanktrn)){
                    foreach ($tanktrn as $lkey => $list) {

                       $tanktrn[$lkey]->PRICE = number_format((float)$tanktrn[$lkey]->PRICE,$decimal_point,'.',',');
                        $tanktrn[$lkey]->AMO = number_format((float)$tanktrn[$lkey]->AMO,$decimal_point,'.',',');
                        $tanktrn[$lkey]->VOL = number_format((float)$tanktrn[$lkey]->VOL,2,'.',',');
                        if($list->RFID_CARD_USED == 0){
                            $list->RFID_CARD_USED = "No";
                            $list->CUST_NAME = "";
                        }else{
                            $list->RFID_CARD_USED = "Yes";
                        }

 
                       $apro[] = array("RDG No" => $list->RDG_ID, "FP No" => $list->FP, "Nozzle No" => $list->NOZ, "Product Name" => $list->FDC_PROD_NAME, "Pump Start Volume Total" => number_format((float)$list->Pump_Start_Volume_Total,2,'.',','), "Pump End Volume Total" => number_format((float)$list->Pump_End_Volume_Total,2,'.',','), "Volume By Total" => $list->VOL);                        
                    }
                }
                
                $alabel[]= array(
                    
                    "Transactions" =>number_format($data['total_tran'])." Count",
                    "Total Liter" =>number_format((float)$data['total_ltr'],2,'.',',')." Ltr",
                    "TOTAL PUMP START VOLUME" =>number_format($data['Pump_Start_Volume_Total'])." Ltr",
                    "TOTAL PUMP END VOLUME" =>number_format((float)$data['Pump_End_Volume_Total'],2,'.',',')." Ltr",
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
                
                $view = view("pages.admin.tanktrn.pdf_view",compact('dates','search_input','tanktrn','t_amt','t_ltr','Pump_End_Volume_Total','t_count','Pump_Start_Volume_Total','currency_code','decimal_point','station_logos','sname','stitle','t_count','tank_name','product_selected','tin','vrn','serial_number','service_station','info','no'))->render();
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
                .table_header2 td{
                  text-align: center;
                  vertical-align: middle;
                padding-bottom: 7px!important;
                }
                .table_header2{
                  border: none!important;
                }
                header {
                    position: fixed;
                    top: 0.5cm;
                    left: 0.7cm;
                    right: 0.7cm;
                    height: 3cm;
                    text-align: center;
                    line-height: 1.5cm;
                    font-weight: 600!important;
                }


                tr { page-break-inside: avoid; }

                </style></head><body><div class="modal-body" id="csrdetailbody">';
                $pdfhtml .= $view;
                $pdfhtml .= '</div></body></html>';     
                $pdftitle = $dates."-".$search_input.".pdf";

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

     public function detail($id)
    {
        $tanktrn_data;
        $tanktrn_id = en_de_crypt($id, 'd'); 
        $tanktrn_data = Trn::findorfail($tanktrn_id); 
        if($tanktrn_data){
            // Tank Trn 
            $txt = "Tank Trn Detail \n\n\n\n";
            @$tanktrndata=array(
                                             array( "key" => "ID", "value" => ''),
                                             array( "key" => "FDC Number", "value" => $tanktrn_data->FDC_NUM ),
                                             array( "key" => "RDG NO", "value" => $tanktrn_data->RDG_ID),
                                             array( "key" => "FP No", "value" => $tanktrn_data->FP),
                                             array( "key" => "Nozzle No", "value" => $tanktrn_data->NOZ),
                                             array( "key" => "Product Name", "value" => $tanktrn_data->FDC_PROD_NAME),
                                             array( "key" => "PUMP START VOLUME TOTAL", "value" => number_format((float)$tanktrn_data->Pump_Start_Volume_Total,2,'.',',')),
                                             array( "key" => "PUMP END VOLUME TOTAL", "value" => number_format((float)$tanktrn_data->Pump_End_Volume_Total,2,'.',',')),
                                             array( "key" => "VOLUME BY TOTAL", "value" => $tanktrn_data->VOL),

                                             
                                );
            //} 
            $renderer = new ArrayToTextTable($tanktrndata);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";
        
            
            //xls
            $xls = "\r\n \r\n \r\n";
            $xls .= "Tank Trn Detail \r\n";
            $xls .= "\r\n \r\n \r\n";
            $xls .=  "ID"."\t".""."\r\n";
            $xls .=  "FDC Number"."\t".$tanktrn_data->FDC_NUM."\r\n";
            $xls .=  "RDG NO"."\t".$tanktrn_data->RDG_ID."\r\n";
            $xls .=  "FP No"."\t".$tanktrn_data->FP."\r\n";
            $xls .=  "Nozzle No"."\t".$tanktrn_data->NOZ."\r\n";
            $xls .=  "Product Name"."\t".$tanktrn_data->FDC_PROD_NAME."\r\n";
            $xls .=  "PUMP START VOLUME TOTAL"."\t".number_format((float)$tanktrn_data->Pump_Start_Volume_Total,2,'.',',')."\r\n";
            $xls .=  "PUMP END VOLUME TOTAL"."\t".number_format((float)$tanktrn_data->Pump_Start_Volume_Total,2,'.',',')."\r\n";
            $xls .=  "VOLUME BY TOTAL"."\t".$tanktrn_data->VOL."\r\n";
            
            $file_name = "tanktrn_".$tanktrn_id;
            $file_name = str_replace(".xml","",$file_name);
            $view = view("pages.admin.tanktrn.detail_view",compact('tanktrn_data'))->render();

            
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
           tr.break td {  height: 10px; width:100%  }
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

    public function sendMail($url){
        $user = Auth::user();
        $pdffileurl = url('/')."/tanktrn/".$url;
        $user->notify(new TankTrnDetail($pdffileurl));
        return response()->json(['success' => TRUE,'op'=>'mail','msg_type'=>'success','msg'=>'Mail Send Sucessfully!']);
    }

}    
