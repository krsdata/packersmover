<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Trn;
use Mail;
use Input, Redirect, Session, Response, DB;
use App\StationsScan;
use App\Stations;
use App\StationLogo;
use App\User;
use App\StationsProduct;
use App\CompanyUsers;
use App\Classes\ArrayToTextTable;
use Illuminate\Support\Facades\Config;
use Dompdf\Dompdf;
use App\Notifications\TrnDetail;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\Redis;

class TrnController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         ini_set('max_execution_time', 1800); //30 minutes
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $pdata;
        $user = Auth::user();
        $role_name = $user->type;
        $perPage = 10;
        $s_name=''; 
        $search_input = $request->get('search_input');
        $user_name = $request->get('user_name');
        $card_number = $request->get('card_number');
        $PROD_NAME = $request->get('PROD_NAMES');
        $CUST_NAME = $request->get('company_name');
        $rdg_id = $request->get('rdg_id');
        $noz = $request->get('noz'); 
        $fp = $request->get('fp');
        $card_desc = $request->get('card_desc');
        $rdgdata = $nozdata = $Fpdata = $companydata = $card_desc_data=array();  
        $obj = Trn::orderBy('FDC_SAVE_NUM', 'desc');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
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
            @$obj->whereBetween('FDC_DATE_TIME', [$from8, $to8]);
        }
        
        if($search_input){
               //print_r($request->get('search_input')); die();
               $obj->whereIn('stations_id', @$search_input);
               $companydata =Trn::whereIn('stations_id',@$search_input)->select('CUST_NAME')
                ->groupBy('CUST_NAME')->get();

                $card_desc_data=User::whereNotNull('card_desc')->select('card_desc')
                ->groupBy('card_desc')->get();
                //echo "<pre>";print_r($card_desc_data);die();
        }
        $currency_code = "Amt";
        $stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        if($user->hasRole('company') || $user->hasRole('user') || $user->hasRole('manager') || $user->hasRole('owner')){
            $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
        }
        $aComUsers = $aCardNum = $astnumber = $users = array();
        if($user->hasRole('company') || $user->hasRole('user')){
            if( $user->hasRole('company') ){
                $company_data = CompanyUsers::orderBy('id', 'desc')->where('company_id', '=',   $user->id )->get();
            
                if(!empty($company_data)){
                    foreach ($company_data as $cuk => $cuv) {
                        $aComUsers[]  = $cuv->user_id;
                    }
                }
                $users = User::orderBy('name', 'asc')->whereIn('id',$aComUsers)->select("id",'name','last_name','card_number','card_desc')->get();
                if(!empty($users)){
                    foreach ($users as $acuk => $cuacuvv) {
                        $aCardNum[]  = $cuacuvv->card_number;

                    }
                }
                $astids = Trn::whereIn("NUM",$aCardNum)->select('stations_id')->groupBy('stations_id')->get();
            }
            if( $user->hasRole('user') ){
                $card_number =  $user->card_number;
                $aCardNum[] = $card_number;
                $users[] = $user;
                $astids = Trn::where("NUM",$card_number)->select('stations_id')->groupBy('stations_id')->get();
            }
            if($astids){
                foreach ($astids as $snkey => $snvalue) {
                    $astnumber[] = $snvalue->stations_id;
                }
            }
            if($astnumber){
                $stations = Stations::where("active",'1')->whereIn("id", $astnumber)->orderBy('name', 'asc')->get();
            }else{
                return view('pages.admin.trn.nostation');
            }
        }
        
        //if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('company') || $user->hasRole('user') || $user->hasRole('manager')){
            //if($request->get('search_input')){
                if(!empty($PROD_NAME)){
                    $obj->where('FDC_PROD_NAME',$PROD_NAME);
                }
                
                if($CUST_NAME!='0'){
                    $obj->where('CUST_NAME',$CUST_NAME);
                            
                }
                
                if(!empty($rdg_id)){
                    $obj->where('RDG_ID',@$rdg_id);
                }
                if(!empty($fp)){
                    $obj->where('FP',@$fp);
                }
                if(!empty($noz)){
                    $obj->where('NOZ',@$noz);
                }
                   
                if($card_desc){
                    $user_details=User::whereIn('card_desc',$card_desc)->get(['stations_id']);
                    if($user_details){
                        foreach ($user_details as $key => $value) {
                           $stations_id = explode (",", $value->stations_id);
                           $obj->whereIn('stations_id',$stations_id); 
                        }
                    }
                }
               

            $search_input= 1; 
          //}
        //}
         
        // if($user->hasRole('manager')){
        //     $s_name = $user->stations_id;
        //     $obj->where('stations_id', '=',   $s_name );
        //     $search_input = $s_name;
        // }
        
        $currency_code=''; $decimal_point=0;
        if(!empty($request['search_input'])){
            $obj->whereIn('stations_id', @$request->get('search_input'));
            foreach ($stations as $skey => $svalue) {
                for ($i=0; $i <=sizeof($request['search_input']) ; $i++) { 
                        if($svalue['id'] ==  @$request['search_input'][$i]){
                            if(empty($currency_code)){
                                  $currency_code = $svalue['currency_code'];
                            }elseif($currency_code!=$svalue['currency_code']){

                                  $currency_code = 'AMT';
                            }
                            if(empty($decimal_point)){
                                  $decimal_point = $svalue['decimal_point'];
                            }elseif($decimal_point <$svalue['decimal_point']){
                                  $decimal_point = $svalue['decimal_point'];
                            }
                        } 
                }    
            }
        }
        
        $atrnumber = array();
        if(!empty($request['search_input'])){
            $atrnum = Trn::whereIn('stations_id', @$request->get('search_input'))->select('NUM')->groupBy('NUM')->get();
            if($atrnum){
                foreach ($atrnum as $tnkey => $tnvalue) {
                    $atrnumber[] = $tnvalue->NUM;
                }
            }
        }
        //echo "<pre>";print_r($obj->get());die();
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $users = User::orderBy('name', 'asc')->whereIn('card_number', $atrnumber)->where('type', '=', 'user')->select('id','card_number','name','last_name')->get();
        }
        //$PROD_NAME = "";
        //echo "<pre>";print_r($obj->get());die();
        $s_PROD_NAME=$request->get('PROD_NAME');
        $data = array("total_ltr" => "0", "total_tran" => "0", "total_amount" => "0","amount_new" => "0","amount_discount" => "0");
        if(!empty($PROD_NAME)){

            $pdata=Trn::where('FDC_PROD', '=', $PROD_NAME )->first();
            //$PROD_NAME = $s_PROD_NAME;
        }
        //echo "<pre>";print_r($card_number);die();
        if(!empty($card_number)){
            $obj->where('NUM',$card_number);
            //echo "<pre>";print_r($obj->get());die();
        }else{
            if($user->hasRole('company') || $user->hasRole('user') ){
                $obj->whereIn('NUM', $aCardNum );
            }
        }
        
        if(!empty($user_name)){
            //echo $user_name; die();
            $obj->where('CUST_NAME', '=',$user_name);
        }

        if(!empty($CUST_NAME)){
            //echo $user_name; die();
            $obj->where('CUST_NAME', '=',$CUST_NAME);
        }
        
        $amos = $obj->sum('AMO');
        $vol = $obj->sum('VOL');
        $count = $obj->count();
        $amo_discount = $obj->sum('AMO_DISCOUNT');
        if($amo_discount > 0 ){
           $amo_new= $obj->sum('AMO') - $obj->sum('AMO_DISCOUNT');
        }else{
            $amo_new= $obj->sum('AMO') + $obj->sum('AMO_DISCOUNT');
        }
        

        $data = array("total_ltr" => $vol, "total_tran" => $count, "total_amount" => $amos,"amount_new" => $amo_new,"amount_discount" => $amo_discount);

        $FDC_PROD_NAME = Config::get('constants.product_name');
        $obj = $obj->orderBy('FDC_DATE_TIME','DESC')->paginate($perPage)->appends(request()->query());
        $fullUrl = $request->fullUrl();
        $fullUrl = str_replace('/trn-list', '/trn-list-download',$fullUrl);
        $txtUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=txt';
        $xmlUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=xml';
        //echo "<pre>"; print_r($obj); die();
        
                     
        $s_name=$request['search_input'];
        if ($request->ajax()){
            $view = view("pages.admin.trn.table_view",compact('dates','s_name','obj','stations','role_name','FDC_PROD_NAME','PROD_NAME','data','currency_code','users','card_number','decimal_point','rdg_id','noz','fp','user_name','companydata','CUST_NAME','card_desc_data','card_desc'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);
        }else{
            return view('pages.admin.trn.index',compact('dates','s_name','obj','stations','role_name','FDC_PROD_NAME','PROD_NAME','data','currency_code','users','card_number','decimal_point','fullUrl','txtUrl','xmlUrl','rdg_id','noz','fp','user_name','companydata','CUST_NAME','card_desc_data','card_desc'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
    }

    public function detail($id)
    {
        $trn_id = en_de_crypt($id, 'd');
        $trn_data = Trn::findorfail($trn_id);
        if($trn_data){
            $stations =  Stations::findorfail($trn_data->stations_id);
            $decimal_point =  $stations->decimal_point;
            $amt=
            $trn_data->AMO = number_format((float)$trn_data->AMO,$decimal_point,'.',',');
            $trn_data->PRICE = number_format((float)$trn_data->PRICE,$decimal_point,'.',',');
            $trn_data->VOL = number_format((float)$trn_data->VOL,2,'.',',');
            


            $data = array(
                        array( "key" => "ID", "value" => $trn_data->EFD_ID),
                        array( "key" => "FDC Number", "value" => $trn_data->FDC_SAVE_NUM),
                        array( "key" => "RPR Print", "value" => $trn_data->REG_ID),
                        array( "key" => "FDC Date", "value" => $trn_data->FDC_DATE),
                        array( "key" => "FDC Time", "value" => $trn_data->FDC_TIME),
                        array( "key" => "RDG", "value" => $trn_data->RDG_ID),
                        array( "key" => "RDG Date", "value" => $trn_data->RDG_DATE),
                        array( "key" => "RDG Time", "value" => $trn_data->RDG_TIME),
                        array( "key" => "FP", "value" => $trn_data->FP),
                        array( "key" => "Nozzle", "value" => $trn_data->NOZ),
                        array( "key" => "Fuel", "value" => $trn_data->FDC_PROD_NAME),
                        array( "key" => "Price", "value" => $trn_data->PRICE),
                        array( "key" => "Volume", "value" => $trn_data->VOL),
                        array( "key" => "Amount", "value" => $trn_data->AMO),
                        array( "key" => "Round Type", "value" => $trn_data->ROUND_TYPE),
                        array( "key" => "Card Used", "value" => $trn_data->RFID_CARD_USED),
                );
            
            $txt = "Report Detail \n";
            $renderer = new ArrayToTextTable($data);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            if($trn_data->RFID_CARD_USED == "1"){
                $txt .= "\n \n \n";
                $txt .= "Card Detail \n";
                $trn_data->DISCOUNT = number_format((float)$trn_data->DISCOUNT,$decimal_point,'.',',');

                $carddata = array(
                                    array( "key" => "RFID Number (hex)", "value" => $trn_data->NUM),
                                    array( "key" => "RFID Number (dec)", "value" => $trn_data->NUM_10),
                                    array( "key" => "Card Type", "value" => $trn_data->CARD_TYPE),
                                    array( "key" => "Description", "value" => $trn_data->CUST_NAME),
                                    array( "key" => "Pay Method", "value" => $trn_data->PAY_METHOD),
                                    array( "key" => "Discount Type", "value" => $trn_data->RFID_CARD_DISCOUNT_TYPE),
                                    array( "key" => "Discount Value", "value" => $trn_data->DISCOUNT),
                                    array( "key" => "En. Product", "value" => $trn_data->PRODUCT_ENABLED),
                                );

                $renderer = new ArrayToTextTable($carddata);
                $renderer->showHeaders(false);
                $txt .= $renderer->render(true);
                $txt .= "\n \n \n";
                $txt .= "Discount Detail \n";
                $trn_data->PRICE_ORIGIN = number_format((float)$trn_data->PRICE_ORIGIN,$decimal_point,'.',',');
                $trn_data->PRICE_NEW = number_format((float)$trn_data->PRICE_NEW,$decimal_point,'.',',');
                $trn_data->PRICE_DISCOUNT = number_format((float)$trn_data->PRICE_DISCOUNT,$decimal_point,'.',',');
                $trn_data->VOL_ORIGIN = number_format((float)$trn_data->VOL_ORIGIN,2,'.',',');
                $trn_data->AMO_ORIGIN = number_format((float)$trn_data->AMO_ORIGIN,$decimal_point,'.',',');
                $trn_data->AMO_NEW = number_format((float)$trn_data->AMO_NEW,$decimal_point,'.',',');
                $trn_data->AMO_DISCOUNT = number_format((float)$trn_data->AMO_DISCOUNT,$decimal_point,'.',',');

                $discountdata = array(
                                        array( "key" => "Price Original", "value" => $trn_data->PRICE_ORIGIN),
                                        array( "key" => "Price New", "value" => $trn_data->PRICE_NEW),
                                        array( "key" => "Price Discount", "value" => $trn_data->PRICE_DISCOUNT),
                                        array( "key" => "Volume", "value" => $trn_data->VOL_ORIGIN),
                                        array( "key" => "Amount Original", "value" => $trn_data->AMO_ORIGIN),
                                        array( "key" => "Amount New", "value" => $trn_data->AMO_NEW),
                                        array( "key" => "Amount Discount", "value" => $trn_data->AMO_DISCOUNT),
                                    );
            
                $renderer = new ArrayToTextTable($discountdata);
                $renderer->showHeaders(false);
                $txt .= $renderer->render(true);
                $txt .= "\n \n \n";
            }

            $xls = "Transactions Detail \r\n";
            if(!empty($data)){
                foreach ($data as $akey => $aval) {
                    $xls .=  $aval['key']."\t".$aval['value']."\r\n";
                }
            }

            if($trn_data->RFID_CARD_USED == "1"){
                $xls .= "\r\n \r\n \r\n";
                $xls .= "Card Detail \r\n";
                if(!empty($carddata)){
                    foreach ($carddata as $akey => $aval) {
                        $xls .=  $aval['key']."\t".$aval['value']."\r\n";
                    }
                }
                $xls .= "\r\n \r\n \r\n";
                $xls .= "Discount Detail \r\n";
                if(!empty($discountdata)){
                    foreach ($discountdata as $akey => $aval) {
                        $xls .=  $aval['key']."\t".$aval['value']."\r\n";
                    }
                }
            }
            
            
            $ss_data = StationsScan::where('records', $trn_id)->where( "type", "trn")->first();
            $file_name = $ss_data->file_name;
            $file_name = str_replace(".xml","",$file_name);
            $view = view("pages.admin.trn.detail_view",compact('trn_data'))->render();

            
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
                padding: 20px!important;
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

            .transactiontr{ width:50px !important; }
            </style></head><body><div class="modal-body" id="trndetailbody">';
            $pdfhtml .= $view;
            $pdfhtml .= '</div></body></html>';
            $dompdf->loadHtml($pdfhtml);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $output = $dompdf->output();
            $pdffile = public_path().DIRECTORY_SEPARATOR."trn".DIRECTORY_SEPARATOR.$file_name.".pdf";
            file_put_contents($pdffile, $output);
            $pdffileurl = url('/')."/trn/".$file_name.".pdf";
            return response()->json(['success' => TRUE,'op'=>'detail','msg_type'=>'success','msg'=>'Record found Sucessfully!','html'=>$view,'txt'=>$txt, 'xls' => $xls, 'file_name' => $file_name, "pdffileurl" => $pdffileurl]);
        }else{
            return response()->json(['success' => FALSE,'op'=>'detail','msg_type'=>'error','msg'=>'No record found']);
        }
    }
    public function sendMail($url)
    {
        $user = Auth::user();
        $pdffileurl = url('/')."/trn/".$url;
        $user->notify(new TrnDetail($pdffileurl));
        return response()->json(['success' => TRUE,'op'=>'mail','msg_type'=>'success','msg'=>'Mail Send Sucessfully!']);
    }

    public function listDowload(Request $request)
    {
        $user = Auth::user();  $station_details = array();
        $role_name = $user->type;
        $s_name=$request->get('search_input');
        $card_number = $request->get('card_number');
        $PROD_NAME = $request->get('PROD_NAMES');
        $user_name = $request->get('user_name');
        $rdg_id = $request->get('rdg_id');
        $noz = $request->get('noz');
        $fp = $request->get('fp');
        $CUST_NAME = $request->get('company_name');
        $search_input = "";
        $card_desc = $request->get('card_desc');
        $obj = Trn::orderBy('FDC_SAVE_NUM', 'desc');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
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
            $obj->whereBetween('FDC_DATE_TIME', [$from8, $to8]);
        }
        //$currency_code = "Amt";
        $stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        if($user->hasRole('company') || $user->hasRole('user') || $user->hasRole('manager') || $user->hasRole('owner')){
            $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
        }
        $aComUsers = $aCardNum = $astnumber = $users = array();
        if($user->hasRole('company') || $user->hasRole('user')){
            if( $user->hasRole('company') ){
                $company_data = CompanyUsers::orderBy('id', 'desc')->where('company_id', '=',   $user->id )->get();
            
                if(!empty($company_data)){
                    foreach ($company_data as $cuk => $cuv) {
                        $aComUsers[]  = $cuv->user_id;
                    }
                }
                $users = User::orderBy('name', 'asc')->whereIn('id',$aComUsers)->select("id",'name','last_name','card_number')->get();
                if(!empty($users)){
                    foreach ($users as $acuk => $cuacuvv) {
                        $aCardNum[]  = $cuacuvv->card_number;
                    }
                }
                $astids = Trn::whereIn("NUM",$aCardNum)->select('stations_id')->groupBy('stations_id')->get();
            }
            if( $user->hasRole('user') ){
                $card_number =  $user->card_number;
                $aCardNum[] = $card_number;
                $users[] = $user;
                $astids = Trn::where("NUM",$card_number)->select('stations_id')->groupBy('stations_id')->get();
            }
            if($astids){
                foreach ($astids as $snkey => $snvalue) {
                    $astnumber[] = $snvalue->stations_id;
                }
            }
            if($astnumber){
                $stations = Stations::where("active",'1')->whereIn("id", $astnumber)->orderBy('name', 'asc')->get();
            }else{
                return view('pages.admin.trn.nostation');
            }
        }
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('company') || $user->hasRole('user') ){
            //if($request->get('search_input')){
                if(!empty($PROD_NAME)){
                    $obj->where('FDC_PROD_NAME',$PROD_NAME);
                }
                
                if($CUST_NAME!='0'){
                    $obj->where('CUST_NAME',$CUST_NAME);
                            
                }
                
                if(!empty($rdg_id)){
                    $obj->where('RDG_ID',@$rdg_id);
                }
                if(!empty($fp)){
                    $obj->where('FP',@$fp);
                }
                if(!empty($noz)){
                    $obj->where('NOZ',@$noz);
                }

                if($card_desc){
                    $user_details=User::whereIn('card_desc',$card_desc)->get(['stations_id']);
                    if($user_details){
                        foreach ($user_details as $key => $value) {
                           $stations_id = explode (",", $value->stations_id);
                           $obj->whereIn('stations_id',$stations_id); 
                        }
                    }
                }

            $search_input= 1; 
          //}
        }
        if($user->hasRole('manager')){
            $s_name = $user->stations_id;
            $obj->where('stations_id', '=',   $s_name );
            $search_input = $s_name;
        }
        
        $currency_code=''; $decimal_point=0;
        if(!empty($request['search_input'])){
            $obj->whereIn('stations_id', @$request->get('search_input'));
            foreach ($stations as $skey => $svalue) {
                            
                            for ($i=0; $i <=sizeof($request['search_input']) ; $i++) { 
                                if($svalue['id'] ==  @$request['search_input'][$i]){
                                
                                    if(empty($currency_code)){
                                      $currency_code = $svalue['currency_code'];
                                    }elseif($currency_code!=$svalue['currency_code']){

                                      $currency_code = 'AMT';
                                    }
                                    if(empty($decimal_point)){
                                      $decimal_point = $svalue['decimal_point'];
                                    }elseif($decimal_point <$svalue['decimal_point']){
                                      $decimal_point = $svalue['decimal_point'];
                                    }
                                } 
                            }    
            }

            // get station details
            $station_data=Stations::whereIn('id',$request['search_input'])->get();
            foreach ($station_data as $skeys => $svalue) {
                $station_details[$skeys]['title'] = $svalue['title'];
                $station_details[$skeys]['name'] = $svalue['name'];
                $station_details[$skeys]['vrn'] = $svalue['vrn'];
                $station_details[$skeys]['tin'] = $svalue['tin'];
                $station_details[$skeys]['info'] = $svalue['info'];
                $station_details[$skeys]['tel'] = $svalue['tel'];
                $station_details[$skeys]['service_station'] = $svalue['service_station'];
                $station_details[$skeys]['serial_number'] = $svalue['serial_number'];
                // get station logo 
                $station_logo=StationLogo::where('station_id',$svalue['id'])->first();
                if(!empty($station_logo)){
                    $station_details[$skeys]['logo']=$station_logo->name;
                }    
            }
            
        }
        
        $atrnumber = array();
        $atrnum = Trn::whereIn("stations_id",$s_name)->select('NUM')->groupBy('NUM')->get();
        if($atrnum){
            foreach ($atrnum as $tnkey => $tnvalue) {
                $atrnumber[] = $tnvalue->NUM;
            }
        }
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $users = User::orderBy('name', 'asc')->whereIn('card_number', $atrnumber)->where('type', '=', 'user')->select('id','card_number','name','last_name')->get();
        }
        $PROD_NAME = "";
        $s_PROD_NAME=$request->get('PROD_NAMES');
        $data = array("total_ltr" => "0", "total_tran" => "0", "total_amount" => "0","amount_new" => "0","amount_discount" => "0");
        if(!empty($s_PROD_NAME)){
            $obj->where('FDC_PROD_NAME', '=',   $s_PROD_NAME );
            $PROD_NAME = $s_PROD_NAME;
        }
        if(!empty($CUST_NAME)){
            $obj->where('CUST_NAME', '=',   $CUST_NAME );
        }
        if(!empty($card_number)){
            $obj->where('NUM',$card_number);
        }else{
            if($user->hasRole('company') || $user->hasRole('user') ){
                $obj->whereIn('NUM', $aCardNum );
            }
        }

        if(!empty($user_name)){
            $obj->where('CUST_NAME', '=',$user_name );
        }
        $vol = $obj->sum('VOL');
        $amo = $obj->sum('AMO');
        $count = $obj->count();
        $amo_discount = $obj->sum('AMO_DISCOUNT');
        $amo_new =  $obj->sum('AMO') + $obj->sum('AMO_DISCOUNT');
        $data = array("total_ltr" => $vol, "total_tran" => $count, "total_amount" => $amo,"amount_new" => $amo_new,"amount_discount" => $amo_discount);
        
        $FDC_PROD_NAME = Config::get('constants.product_name');
       
        if(empty($card_number)){
            $card_number= "";
        }
        if(empty($PROD_NAME)){
            $s_PROD_NAME = "";
        }else{
           $s_PROD_NAME =  $PROD_NAME;
        }

        if(empty($rdg_id)){
            $rdg_id = "";
        }else{
           $rdg_id =  $rdg_id;
        }

        if(empty($noz)){
            $noz = "";
        }else{
           $noz =  $noz;
        }

        if(empty($fp)){
            $fp = "";
        }else{
           $fp =  $fp;
        }

        if(empty($CUST_NAME)){
            $CUST_NAME = " ";
        }else{
           $CUST_NAME =  $CUST_NAME;
        }

        if(empty($user_name)){
            $user_name = " ";
        }else{
           $user_name =  $user_name;
        }
        $type = $request->get('type');
        if($type == "txt"){
            $txt = "";
            $datas = $obj->get();

            $txttitle = $dates.".txt";
            

            $txt .= "Transaction List: ".$dates."\n \n";
            if(!empty($card_number)){
                 $txt .= "Card Number: ".$card_number."\n \n";
            }
            
            if(!empty($s_PROD_NAME)){
                 $txt .= "Product Name: ".$s_PROD_NAME."\n \n";
            }
            if(!empty($rdg_id)){
                 $txt .= "RGD ID: ".$rdg_id."\n \n";
            }
            if(!empty($fp)){
                 $txt .= "FP ID: ".$fp."\n \n";
            }
            if(!empty($noz)){
                 $txt .= "NOZ ID: ".$noz."\n \n";
            }
            if($user_name!=0){
                 $txt .= "User: ".$user_name."\n \n";
            }

            if($CUST_NAME!=0){
                 $txt .= "Company Name: ".$noz."\n \n";
            }
            if(!empty(@$card_desc[0])){
                 $txt .= "Card Descripion: ".@$card_desc[0];
            }

            if(!empty($station_details)){
                foreach($station_details as $list){
                        $txt .= "Station Title : ".$list['title']."\n \nAddress: ".$list['info']."\n \ntelephone: ".$list['tel']."\n \nTIN: ".$list['tin']."  VRN: ".$list['vrn']."\n \nService Station : ".$list['service_station']." \n \nSerial Number :".$list['serial_number']." \n \n \n \n";
                }
            }
            
            if(!empty($datas)){
                foreach ($datas as $lkey => $list) {
                    $datas[$lkey]->PRICE = number_format((float)$datas[$lkey]->PRICE,$decimal_point,'.',',');
                    $datas[$lkey]->AMO = number_format((float)$datas[$lkey]->AMO,$decimal_point,'.',',');
                    $datas[$lkey]->VOL = number_format((float)$datas[$lkey]->VOL,2,'.',',');
                    if($list->RFID_CARD_USED == 0){
                        $list->RFID_CARD_USED = "No";
                        $list->CUST_NAME = "";
                    }else{
                        $list->RFID_CARD_USED = "Yes";
                    }
                    $apro[] = array("No" => $list->FDC_SAVE_NUM,"Verification Code" => $list->EFD_ID, "FDC Date" => $list->FDC_DATE, "FDC Time" => $list->FDC_TIME, "RDG No" => $list->RDG_ID, "FP No" => $list->FP, "Nozzle No" => $list->NOZ, "Fuel" => $list->FDC_PROD_NAME, "Unit Price" => $list->PRICE, "Volume" => $list->VOL, "Amount" => $list->AMO, "Card Used" => $list->RFID_CARD_USED, "Company Name" => $list->CUST_NAME,"Amount New" => $list->AMO_NEW,"Amount Discount" => $list->AMO_DISCOUNT);
                    
                }
            }
            // if ( (isset($PROD_NAME) && !empty($PROD_NAME) ) || (isset($card_number) && !empty($card_number) )){
                if($data['amount_discount'] > 0){
                   @$new_Amount=$data['total_amount'] - $data['amount_discount'];
                }else{
                    @$new_Amount=$data['total_amount'] +  $data['amount_discount'];
                }
                
                $alabel[]= array(
                    "Total Amount" =>number_format((float)$data['total_amount'],$decimal_point,'.',',')." ".$currency_code,
                    "Transactions" =>number_format($data['total_tran'])." Count",
                    "Total Liter" =>number_format((float)$data['total_ltr'],2,'.',',')." Ltr",
                    "AMOUNT NEW" =>number_format((float)$new_Amount,$decimal_point,'.',',')." ".$currency_code,
                    "AMOUNT DISCOUNT" =>number_format((float)$data['amount_discount'],$decimal_point,'.',',')." ".$currency_code,
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
            $datas = $obj->get();
            $xlstitle = $dates."-".@$card_number."-".$s_PROD_NAME.".xls";
            
            if(!empty($card_number)){
                 $xls .= "Card Number: ".$card_number."\n \n";
            }
            if(!empty($s_PROD_NAME)){
                 $xls .= "Product Name: ".$s_PROD_NAME."\n \n";
            }
            if(!empty($rdg_id)){
                 $xls .= "RGD ID: ".$rdg_id."\n \n";
            }
            if(!empty($fp)){
                 $xls .= "FP ID: ".$fp."\n \n";
            }
            if(!empty($noz)){
                 $xls .= "NOZ ID: ".$noz."\n \n";
            }
            if($user_name!=0){
                 $xls .= "User: ".$user_name."\n \n";
            }

            if($CUST_NAME!=0){
                 $xls .= "Company Name: ".$noz."\n \n";
            }
            if(!empty(@$card_desc[0])){
                 $xls .= "Card Descripion: ".@$card_desc[0]."\n \n";
            }


            if(!empty($station_details)){
                foreach($station_details as $list){
                        $xls .= "Station Title : ".$list['title']."\n \nAddress: ".$list['info']."\n \ntelephone: ".$list['tel']."\n \nTIN: ".$list['tin']."  VRN: ".$list['vrn']."\n \nService Station : ".$list['service_station']." \n \n Serial Number :".$list['serial_number']." \n \n \n \n";
                }
            }
            if(!empty($datas)){
                foreach ($datas as $lkey => $list) {
                    $datas[$lkey]->PRICE = number_format((float)$datas[$lkey]->PRICE,$decimal_point,'.',',');
                    $datas[$lkey]->AMO = number_format((float)$datas[$lkey]->AMO,$decimal_point,'.',',');
                    $datas[$lkey]->VOL = number_format((float)$datas[$lkey]->VOL,2,'.',',');
                    if($list->RFID_CARD_USED == 0){
                        $list->RFID_CARD_USED = "No";
                        $list->CUST_NAME = "";
                    }else{
                        $list->RFID_CARD_USED = "Yes";
                    }
                    $apro[] = array("No" => $list->FDC_SAVE_NUM,"Verification Code" => $list->EFD_ID, "FDC Date" => $list->FDC_DATE, "FDC Time" => $list->FDC_TIME, "RDG No" => $list->RDG_ID, "FP No" => $list->FP, "Nozzle No" => $list->NOZ, "Fuel" => $list->FDC_PROD_NAME, "Unit Price" => $list->PRICE, "Volume" => $list->VOL, "Amount" => $list->AMO, "Card Used" => $list->RFID_CARD_USED, "Company Name" => $list->CUST_NAME,"Amount New" => $list->AMO_NEW,"Amount Discount" => $list->AMO_DISCOUNT);
                    
                }
            }
            // if ( (isset($PROD_NAME) && !empty($PROD_NAME) ) || (isset($card_number) && !empty($card_number) )){
                if($data['amount_discount'] > 0){
                   @$new_Amount=$data['total_amount'] - $data['amount_discount'];
                }else{
                    @$new_Amount=$data['total_amount'] +  $data['amount_discount'];
                }
                //@$new_Amount=$data['total_amount']+$data['amount_discount'];
                $alabel[]= array(
                    "Total Amount" =>number_format((float)$data['total_amount'],$decimal_point,'.',',')." ".$currency_code,
                    "Transactions" =>number_format($data['total_tran'])." Count",
                    "Total Liter" =>number_format((float)$data['total_ltr'],2,'.',',')." Ltr",
                    "AMOUNT NEW" =>number_format((float)@$new_Amount,$decimal_point,'.',',')." ".$currency_code,
                    "AMOUNT DISCOUNT" =>number_format((float)$data['amount_discount'],$decimal_point,'.',',')." ".$currency_code,
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
            $obj=$obj->get();
            //echo "<pre>"; echo $noz; die();
            $view = view("pages.admin.trn.pdf_view",compact('dates','search_input','obj','stations','role_name','FDC_PROD_NAME','PROD_NAME','data','currency_code','users','card_number','decimal_point','station_details','card_number','s_PROD_NAME','rdg_id','fp','user_name','CUST_NAME','noz','card_number'))->render();
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
            tr.break td {  height: 10px; width:100%  }
            


            </style></head><body><div class="modal-body" id="csrdetailbody">';
            $pdfhtml .= $view;
            $pdfhtml .= '</div></body></html>';     
            $pdftitle = $dates."-".@$card_number."-".$s_PROD_NAME.".pdf";

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

    public function get_product_details(Request $request){
        //echo "<pre>"; print_r($request->all()); die();
        $details=array();
        if($request['id']!=0){
            foreach ($request['id'] as $key => $value) {
               $product = Trn::where('stations_id', '=', $value )->select('FDC_PROD_NAME','FDC_PROD')
                ->groupBy('FDC_PROD_NAME','FDC_PROD')->get();
                    foreach ($product as $key => $value) {
                        if($value['FDC_PROD_NAME']){
                            $details[]= $product; 
                            return response()->json(['success' => TRUE,'data'=>$details,'product'=>'']);  
                        }
                    }
            }
           
            //echo "<pre>"; print_r($details); die();
        }
    }

    public function get_type_details(Request $request){
        //echo "<pre>"; print_r($request->all()); die();
        $details=array();
        if($request['product']=='0' && $request['station']){

            $details['RDG_ID'] = Trn::whereIn('stations_id', @$request['station'])->select('RDG_ID')->groupBy('RDG_ID')->get();
            $details['NOZ'] = Trn::whereIn('stations_id', @$request['station'])->select('NOZ')->groupBy('NOZ')->get();
            $details[ 'FP'] =Trn::whereIn('stations_id', @$request['station'])->select('FP')->groupBy('FP')->get();
                   
        }
        if($request['product']!='0' && $request['station']){
             $details['RDG_ID'] = Trn::where('FDC_PROD', '=', $request['product'])->whereIn('stations_id', @$request['station'])->select('RDG_ID')->groupBy('RDG_ID')->get();
            $details['NOZ'] = Trn::where('FDC_PROD', '=', $request['product'])->whereIn('stations_id', @$request['station'])->select('NOZ')->groupBy('NOZ')->get();
            $details[ 'FP'] =Trn::where('FDC_PROD', '=', $request['product'])->whereIn('stations_id', @$request['station'])->select('FP')->groupBy('FP')->get();
        }
        return response()->json(['success' => TRUE,'data'=>$details]);
    }

     
}
