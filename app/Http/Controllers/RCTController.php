<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Rct;
use App\Items;
use App\Totals;
use App\Payments;
use App\Vattotals;
use App\Stations;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use App\StationsScan;
use App\Notifications\RctDetail;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Config;
use App\StationsLoyalty;


class RCTController extends Controller
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

    public function index(Request $request){
        //echo "<pre>"; print_r($request->all());  die();
        $user = Auth::user();
        $role_name = $user->type;
        $perPage = 10;
        $s_name=$request->get('item');
        $stations=$request->get('station');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        $p_count=0; $i_count=0; $rct_count=0; $item_id;
        $currency_code=''; $decimal_point=0;
        if(!empty($s_datese)){
            $dates = $s_datese;
        }
        $obj=Rct::orderBy('id', 'desc');
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
            $obj->whereBetween('DATE_TIME', [$from8, $to8]);
        }

       
        $item=Rct::select('CUSTNAME')->groupBy('CUSTNAME')->get();
        // station  
        
        if($user->hasRole('company') || $user->hasRole('user') || $user->hasRole('manager') || $user->hasRole('owner')){
            //$s_data=explode(" ",$user->stations_id); print_r($s_data);  die();
             $stations_data= Stations::orderBy('id', 'desc')->whereIn("id", explode(',', $user->stations_id))->get();
        }else{
            $stations_data= Stations::orderBy('id', 'desc')->get();
        }

        if($request->get('station')){
               $obj->whereIn('station_id',@$request->get('station'));
        }


        if($request->get('item')){
               $obj->where('CUSTNAME',$request->get('item'));
        }
        $rct_count = $obj->count();
        
        if(!empty($request['station'])){
            foreach ($stations_data as $skey => $svalue) {
                for ($i=0; $i <=sizeof($request['station']) ; $i++) { 
                        if($svalue['id'] ==  @$request['station'][$i]){
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

        
        if($obj){
            //echo $ssss=$obj->count(); die();
            $ssss=$obj->get();
            $ii_count =array();
            $rcts_count =array();
            foreach ($ssss as $key => $value) {
               array_push($rcts_count,$value['id']);
            }
                $pay_count = Payments::whereIn('rct_id', $rcts_count)->get();
                if($pay_count){
                    $p_count = $pay_count->sum('PMTAMOUNT');
                    
                } 
                $itm= Items::whereIn('rct_id', $rcts_count)->get();
                if($itm){
                    foreach ($itm as $key => $valuess) {
                        array_push($ii_count,$valuess['ID']);
                    } 
                }

            $i_count = count($ii_count);    
        }
        //echo count($rcts_count); die();    
            
        $obj = $obj->orderBy('id','DESC')->paginate($perPage)->appends(request()->query());
        //echo "<pre>";print_r($obj); die();
        
        $fullUrl = $request->fullUrl();
        $fullUrl = str_replace('/rct-list', '/rct-list-download',$fullUrl);
        $txtUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=txt';
        $xmlUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=xml';
        //echo "<pre>"; print_r($obj); 
        //die();
        if ($request->ajax()){
            $view = view("pages.admin.rct.table_view",compact('dates','obj','role_name','item','s_name','stations_data','stations','rct_count','p_count','i_count','decimal_point','currency_code'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);
        }else{
            return view('pages.admin.rct.index',compact('dates','obj','role_name','fullUrl','txtUrl','xmlUrl','item','s_name','stations_data','stations','rct_count','p_count','i_count','decimal_point','currency_code'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
    }

    public function listDowload(Request $request){
        $user = Auth::user();
        $role_name = $user->type;
        $perPage = 10;
        $s_name=$request->get('item');
        $page=1;
        $stations=$request->get('station');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }
        $from8='';$to8='';
        $p_count=0; $i_count=0;   
        $obj=Rct::orderBy('id', 'desc');
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
            $obj->whereBetween('DATE_TIME', [$from8, $to8]);
        }

        $item_id;
        $item=Items::select('DESC')->groupBy('DESC')->get();
        if($request->get('item')!=0){
               $data=Items::where('DESC',$request->get('item'));
               if($data){
                 foreach ($data as $key => $value) {
                     $item_id=$value->rct_id;
                 }
                 $obj->where('id',$item_id);
               }
                
        } 

        if($request->get('station')){
               $obj->whereIn('station_id',@$request->get('station'));
        } 

         if($request->get('item')){
               $obj->where('CUSTNAME',$request->get('item'));
        }
        
        
        $rct_count = $obj->count();
        

        

        $currency_code=''; $decimal_point=0;
        if(!empty($request['station'])){
            $stations_data= Stations::orderBy('id', 'desc')->get();
            foreach ($stations_data as $skey => $svalue) {
                for ($i=0; $i <=sizeof($request['station']) ; $i++) { 
                        if($svalue['id'] ==  @$request['station'][$i]){
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

        //$obj = $obj->orderBy('id','DESC')->paginate($perPage)->appends(request()->query());
       //$data = array("Total_RCt" => $rct_count, "Total_item" => $p_count, "total_paymnet" => $i_count);
        if($obj->get()){
            $ongss = $obj->get();
            $ii_count =array();
            foreach ($ongss as $key => $value) {
                $pay_count = Payments::where('rct_id', $value['id'])->get();
                if($pay_count){
                    foreach ($pay_count as $key => $values) {
                        $p_count = $p_count+$values['PMTAMOUNT'];
                    } 
                } 
                $itm= Items::where('rct_id', $value['id'])->get();
                if($itm){
                    foreach ($itm as $key => $valuess) {
                        array_push($ii_count,$valuess['ID']);
                    } 
                }
            }
            
            $i_count = count($ii_count);
        }
        $type = $request->get('type');
        if($type == "txt"){
            $txt = "";
            $datas = $obj->get();
            $txt .= "RCT List: ".date("Y.m.d")."    \n \n \n \n";
            $txttitle ="Rct-list-".date("Y-m-d").".txt";
            if(!empty($datas)){
                foreach ($datas as $lkey => $list) {
                   
                    $apro[] = array("DATE" => $list->DATE, "TIME" => $list->TIME, "TIN" => $list->TIN, "REGID" => $list->REGID, "EFDSERIAL" => $list->EFDSERIAL, "CUSTIDTYPE" => $list->CUSTIDTYPE, "CUSTID" => $list->CUSTID, "CUSTNAME" => $list->CUSTNAME, "MOBILENUM" => $list->MOBILENUM, "RCTNUM" => $list->RCTNUM, "DC" => $list->DC, "GC" => $list->GC,"ZNUM" => $list->ZNUM,"RCTVNUM" => $list->RCTVNUM);
                    
                }
            }
            
            $alabel[]= array("Total_RCt" => number_format((float)$rct_count), "Total_item" =>number_format((float)$i_count),"total_paymnet" => number_format((float)$p_count,$decimal_point,'.',','));
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
            $datas = $obj->get();
            $xlstitle = "Rct-list-".date("Y-m-d").".xls";
            $xls .= "RCT List: ".date("Y.m.d")."    \n \n \n \n";
            if(!empty($datas)){
                foreach ($datas as $lkey => $list) {
                    $apro[] = array("DATE" => $list->DATE, "TIME" => $list->TIME, "TIN" => $list->TIN, "REGID" => $list->REGID, "EFDSERIAL" => $list->EFDSERIAL, "CUSTIDTYPE" => $list->CUSTIDTYPE, "CUSTID" => $list->CUSTID, "CUSTNAME" => $list->CUSTNAME, "MOBILENUM" => $list->MOBILENUM, "RCTNUM" => $list->RCTNUM, "DC" => $list->DC, "GC" => $list->GC,"ZNUM" => $list->ZNUM,"RCTVNUM" => $list->RCTVNUM);
                    
                }
            }

            $alabel[]= array("Total_RCt" => number_format((float)$rct_count), "Total_item" =>number_format((float)$i_count),"total_paymnet" => number_format((float)$p_count,$decimal_point,'.',','));
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
            $view = view("pages.admin.rct.pdf_view",compact('obj','stations','role_name','p_count','rct_count','i_count','currency_code','decimal_point'))->render();
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
            </style></head><body><div class="modal-body" id="rctdetailbody">';
            $pdfhtml .= "<p>RCT List:</p><p>&nbsp;</p>";
            $pdfhtml .= $view;
            $pdfhtml .= '</div></body></html>';
            $pdftitle =" Rct-list-".date("Y-m-d").".pdf";

            $pdf = new Pdf($pdfhtml);
            if (!$pdf->send($pdftitle)) {
                $error = $pdf->getError();
                print_r($error);
            }
            exit;
        } 
    }

     public function detail($id)
    {
        $item_datas;  $totals_datas; $payments_datas;$vattotal_datas;$itemsdatass;$rctdata;
        $rct_id = en_de_crypt($id, 'd'); 
        $rct_data = Rct::findorfail($rct_id); 
        if($rct_data){
            $item_datas = Items::where('rct_id',$rct_data->id)->get();
            $item_datas = $item_datas->toArray();  
            

             // RCT 
            $txt = "RCT  Detail \n\n\n\n";
            @$rctdata=array(
                                             array( "key" => "DATE", "value" => $rct_data->DATE),
                                             array( "key" => "TIME", "value" => $rct_data->TIME),
                                             array( "key" => "TIN", "value" => $rct_data->TIN),
                                             array( "key" => "REGID", "value" => $rct_data->REGID),
                                             array( "key" => "EFDSERIAL", "value" => $rct_data->EFDSERIAL),
                                             array( "key" => "CUSTIDTYPE", "value" => $rct_data->CUSTIDTYPE),
                                             array( "key" => "CUSTNAME", "value" => $rct_data->CUSTNAME),
                                             array( "key" => "MOBILENUM", "value" => $rct_data->MOBILENUM),
                                             array( "key" => "RCTNUM", "value" => $rct_data->RCTNUM),
                                             array( "key" => "DC", "value" => $rct_data->DC),
                                             array( "key" => "GC", "value" => $rct_data->GC),
                                             array( "key" => "ZNUM", "value" => $rct_data->ZNUM),
                                             array( "key" => "RCTVNUM", "value" =>$rct_data->RCTVNUM),
                                             array( "key" => "station_id", "value" => $rct_data->station_id),
                                    );
            //} 
            $renderer = new ArrayToTextTable($rctdata);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";


            // Totals 
            $txt .= "Totals  Detail \n";
            $totals_datas = Totals::where('rct_id',$rct_data->id)->get();
            $totals_datas = $totals_datas->toArray(); 
            foreach ($totals_datas as $key => $aval) {
                    $totalsdatas=array(
                                             array( "key" => "TOTALTAXEXCL", "value" => $aval['TOTALTAXEXCL']),
                                             array( "key" => "TOTALTAXINCL", "value" => $aval['TOTALTAXINCL']),
                                             array( "key" => "DISCOUNT", "value" => $aval['DISCOUNT']),
                                    );
            } 
            $renderer = new ArrayToTextTable($totalsdatas);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";

            //Items 
            $txt .= "Items Detail \n";
            //echo ""; print_r($itemsdatass); die();
            for ($i=0; $i<sizeof($item_datas);$i++) {
                    $itemsdatass=array(
                                        array( "key" => "Station id", "value" => @$item_datas[$i]['rct_id']),
                                        array( "key" => "DESC", "value" => @$item_datas[$i]['DESC']),
                                        array( "key" => "QTY", "value" => @$item_datas[$i]['QTY']),
                                        array( "key" => "TAXCODE", "value" => @$item_datas[$i]['TAXCODE']),
                                        array( "key" => "AMT", "value" => @$item_datas[$i]['AMT']),
                                    );
                    $renderer = new ArrayToTextTable($itemsdatass);
                    $renderer->showHeaders(false);
                    $txt .= $renderer->render(true);
                    $txt .= "\n \n \n";
            }
            

            // Payments
            $txt .= "Payments Detail \n";
            $payments_datas = Payments::where('rct_id',$rct_data->id)->get();
            $payments_datas = $payments_datas->toArray();
            foreach ($payments_datas as $key => $aval) {
                    $paymnetsdatas=array(
                                              array( "key" => "PMTTYPE", "value" => $aval['PMTTYPE']),
                                              array( "key" => "PMTTYPE", "value" => $aval['PMTAMOUNT']),
                                    );
            } 
            $renderer = new ArrayToTextTable($paymnetsdatas);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";
            
            // Vattotal
            $txt .= "Vattotal Detail \r\n";
            $vattotal_datas = Vattotals::where('rct_id',$rct_data->id)->get();
            $vattotal_datas = $vattotal_datas->toArray();
            foreach ($vattotal_datas as $key => $aval) {
                    $vattotalsdatas=array(
                                            array( "key" => "VATRATE", "value" => $aval['VATRATE']), 
                                            array( "key" => "NETTAMOUNT", "value" => $aval['NETTAMOUNT']), 
                                            array( "key" => "TAXAMOUNT", "value" => $aval['TAXAMOUNT']), 
                                            
                                    );
            }
            $renderer = new ArrayToTextTable($vattotalsdatas);
            $renderer->showHeaders(false);
            $txt .= $renderer->render(true);
            $txt .= "\n \n \n";
            
            //xls
            $xls = "\r\n \r\n \r\n";
            $xls .= "RCT Detail \r\n";
            $xls .= "\r\n \r\n \r\n";
            $xls .=  "DATE"."\t".$rct_data->DATE."\r\n";
            $xls .=  "TIME"."\t".$rct_data->TIME."\r\n";
            $xls .=  "TIN"."\t".$rct_data->TIN."\r\n";
            $xls .=  "REGID"."\t".$rct_data->REGID."\r\n";
            $xls .=  "EFDSERIAL"."\t".$rct_data->EFDSERIAL."\r\n";
            $xls .=  "CUSTIDTYPE"."\t".$rct_data->CUSTIDTYPE."\r\n";
            $xls .=  "CUSTID"."\t".$rct_data->CUSTID."\r\n";
            $xls .=  "CUSTNAME"."\t".$rct_data->CUSTNAME."\r\n";
            $xls .=  "MOBILENUM"."\t".$rct_data->MOBILENUM."\r\n";
            $xls .=  "RCTNUM"."\t".$rct_data->RCTNUM."\r\n";
            $xls .=  "DC"."\t".$rct_data->DC."\r\n";
            $xls .=  "GC"."\t".$rct_data->GC."\r\n";
            $xls .=  "ZNUM"."\t".$rct_data->ZNUM."\r\n";
            $xls .=  "RCTVNUM"."\t".$rct_data->RCTVNUM."\r\n";
            $xls .=  "station_id"."\t".$rct_data->station_id."\r\n";
           
            

            $xls = "\r\n \r\n \r\n";
            $xls .= "Totals Detail \r\n";
            $xls .= "\r\n \r\n \r\n";
            foreach ($totals_datas as $key => $aval) {
              $xls .=  "TOTALTAXEXCL"."\t".$aval['TOTALTAXEXCL']."\r\n";
              $xls .=  "TOTALTAXINCL"."\t".$aval['TOTALTAXINCL']."\r\n";
              $xls .=  "DISCOUNT"."\t".$aval['DISCOUNT']."\r\n";
            }

            $xls .= "Items Detail  \r\n";
            $xls .= "\r\n";
            foreach ($item_datas as $key => $aval) {
              $xls .=  "Station id"."\t".$aval['rct_id']."\r\n";
              $xls .=  "DESC"."\t".$aval['DESC']."\r\n";
              $xls .=  "QTY"."\t".$aval['QTY']."\r\n";
              $xls .=  "TAXCODE"."\t".$aval['TAXCODE']."\r\n";
              $xls .=  "AMT"."\t".$aval['AMT']."\r\n";
              $xls .= "\r\n";
            }
             

            $xls .= "\r\n \r\n \r\n";
            $xls .= "Payments Detail \r\n";
            $xls .= "\r\n";
            foreach ($payments_datas as $key => $aval) {
              $xls .=  "PMTTYPE"."\t".$aval['PMTTYPE']."\r\n";
              $xls .=  "PMTAMOUNT"."\t".$aval['PMTAMOUNT']."\r\n";
            }
             

            $xls .= "\r\n \r\n \r\n";
            $xls .= "Vattotals Detail \r\n";
            $xls .= "\r\n";
            foreach ($vattotal_datas as $key => $aval) {
              $xls .=  "VATRATE"."\t".$aval['VATRATE']."\r\n";
              $xls .=  "NETTAMOUNT"."\t".$aval['NETTAMOUNT']."\r\n";
              $xls .=  "TAXAMOUNT"."\t".$aval['TAXAMOUNT']."\r\n";
            }
            
            
            
            $ss_data = StationsScan::where('records', $rct_data->station_id)->where( "type", "rct")->first();
            $file_name = "test";
            $file_name = str_replace(".xml","",$file_name);
            $view = view("pages.admin.rct.detail_view",compact('item_datas','totals_datas','payments_datas','vattotal_datas','rct_data'))->render();

            
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

    public function sendMail($url){
        $user = Auth::user();
        $pdffileurl = url('/')."/rct/".$url;
        $user->notify(new RctDetail($pdffileurl));
        return response()->json(['success' => TRUE,'op'=>'mail','msg_type'=>'success','msg'=>'Mail Send Sucessfully!']);
    }

    public function dashboard(Request $request){
        $user = Auth::user();
        $s_name=$request->get('search_input');
        $search_input = "";
        $singledate = date("m/d/Y",time());
        $s_datese=$request->get('singledate');
        if(!empty($s_datese)){
            $singledate = $s_datese;
        }
       
        if(!empty($s_name)){
            $search_input = trim(urldecode($s_name));
        }
        $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
        if($user->hasRole('owner')){
            $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
        }
        $obj = \DB::table('rct')->orderBy('DATE_TIME', 'desc');
        $obj1 = \DB::table('rct')->orderBy('DATE_TIME', 'desc');
        if(!empty($singledate)){
            $from = date("Y/m/d",strtotime("-14 days", strtotime($singledate))); 
            $to = date("Y/m/d",strtotime($singledate));
            $to11 = date("Y/m/d",strtotime("+1 days", strtotime($singledate)));
            $obj->whereBetween('DATE', [$from,$to]);
            $obj1->whereBetween('DATE', [$to,$to11]);
        }
        
        $role_name = "user";
        $currency_code = "Amt";
        $decimal_point = 2;
        if($user->hasRole('admin') || $user->hasRole('owner')){
            if($s_name){
                $obj->where('station_id', '=', $s_name );
                $obj1->where('station_id', '=', $s_name );
                $search_input = $s_name;
                foreach ($stations as $skey => $svalue) {
                    if($svalue['id'] == $s_name){
                        $currency_code = $svalue['currency_code'];
                        $decimal_point = $svalue['decimal_point'];
                    }
                }
            }else{
                $obj->where('station_id', '=', $stations[0]['id'] );
                $obj1->where('station_id', '=', $stations[0]['id'] );
                $search_input = $stations[0]['id'];
                $currency_code = $stations[0]['currency_code'];
                $decimal_point =  $stations[0]['decimal_point'];
            }
            $role_name = "admin";
        }
        if($user->hasRole('manager') ){
            $s_name = $user->stations_id;
            $obj->where('station_id', '=', $s_name );
            $obj1->where('station_id', '=', $s_name );
            $search_input = $s_name;
            foreach ($stations as $skey => $svalue) {
                if($svalue['id'] == $s_name){
                    $currency_code = $svalue['currency_code'];
                    $decimal_point = $svalue['decimal_point'];
                }
            }
            $role_name = "staff";
        }
        $slpdkey1 = Config::get('constants.product_code');
        $slpdcolor1 = Config::get('constants.product_color');
        $slpdlable1 = Config::get('constants.product_name');
        if($user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('owner') ){
            $aproducts = $slpdlable = $slpdkey = $slpdcolor = array();
            $products = StationsLoyalty::where('stations_id', '=', $search_input)->get();
            if(!empty($products)){
                foreach ($products as $kesy => $valuse) {
                    $afuel = $valuse->fuel;
                    foreach ($slpdlable1 as $kepy => $valupe) {
                        if($valupe == $afuel){
                            $slpdlable[] = $afuel;
                            $aproducts[] = $afuel;
                            $slpdkey[] = $slpdkey1[$kepy];
                            $slpdcolor[] = $slpdcolor1[$kepy];
                        }
                    }
                }
            }
        }
        $total_amount = $total_tran = 0;
        $atran = $lpd = array();
        for ($i=0; $i <= 24; $i++) {
            $si = $i;
            if($i < 10){
                $si = "0".$i;
            }

            $atran[$i]['y'] = str_replace("/","-",$to)." ".$si.":00";
            foreach ($aproducts as $kpey => $valpue) {
                foreach ($slpdlable1 as $kepy => $valupe) {
                    if($valupe == $valpue){
                        $skey = $slpdkey1[$kepy];
                        $atran[$i][$skey] = 0;
                    }
                }
            }
        }
        $d = array();
        $fromdate = strtotime($from);
        $todate = strtotime($to);
        while ($fromdate <= $todate) {
            $lpd[$fromdate]['y'] = date("Y-m-d",$fromdate);
            foreach ($aproducts as $kpey => $valpue) {
                foreach ($slpdlable1 as $kepy => $valupe) {
                    if($valupe == $valpue){
                        $skey = $slpdkey1[$kepy];
                        $d[$skey][$fromdate] = 0;
                        $lpd[$fromdate][$skey] = 0;
                    }
                }
            }
            $fromdate = strtotime("+1 day", $fromdate);
        }
        try {
            $aobj = $obj->get();
            $aobj1 = $obj1->get();
        } catch (\Exception $e) {
            echo $e->getMessage(); die;
        }
        if($aobj){
            $prodtl = array(); $j=0; $k=0;//$amt=0; $total_amount=0;
            foreach ($aobj as $okey => $ovalue) {
                $FDC_DATE = $ovalue->DATE;
                $dtime = strtotime($FDC_DATE);
                $items=Items::where('rct_id',$ovalue->id)->get();
                if($items){
                    foreach ($items as $key => $value) {
                        $type = $value->DESC;
                        $quantity= $value->QTY;
                        $amts= $value->AMT;
                
                        //echo "<pre>"; print_r($quantity);
                        //echo "<pre>"; print_r($type);
                        if($FDC_DATE == $to){
                            $FDC_TIME = $ovalue->TIME;
                            $astr = explode(":",$FDC_TIME);
                            $i = ltrim($astr[0], '0');
                            if(empty($i)){
                                $i = 0;
                            }
                            // foreach ($amts as $amtkey => $amtval) {
                            //     $amt=$amt+$amtval;
                            // }
                            $total_amount = $total_amount + $amts;
                            $total_tran = $total_tran + 1;
                            if( is_numeric($i) ){
                                //while ($j <=count($type)){
                                    foreach ($aproducts as $kpey => $valpue) { 
                                        foreach ($slpdlable1 as $kepy => $valupe) {
                                            if($valupe == $valpue && @$type == $valupe){
                                                //echo $type[$j]; echo "<br>"; 
                                                $skey = $slpdkey1[$kepy];
                                                $atran[$i][$skey] = $atran[$i][$skey] + 1;
                                                if(isset($prodtl[$valpue]['qty'])){
                                                    $prodtl[$valpue]['qty'] = @$prodtl[$valpue]['qty'] + @$quantity;
                                                }else{
                                                    $prodtl[$valpue]['qty'] = @$quantity;
                                                }
                                                if(isset($prodtl[$valpue]['amt'])){
                                                    $prodtl[$valpue]['amt'] = @$prodtl[$valpue]['amt'] + @$amts;
                                                }else{
                                                    $prodtl[$valpue]['amt'] = @$amts;
                                                }
                                                $prodtl[$valpue]['color'] = $slpdcolor1[$kepy];
                                            }
                                        }
                                        
                                    }
                                    
                                //}
                            } 
                            
                        }

                        foreach ($aproducts as $kpey => $valpue) {
                            foreach ($slpdlable1 as $kepy => $valupe) {
                                //foreach ($type as $typekey => $typevalue) {
                                    if($valupe == $valpue && $type == $valupe){
                                        $skey = $slpdkey1[$kepy];
                                        $d[$skey][$dtime] =  $d[$skey][$dtime] + 1;
                                        $lpd[$dtime][$skey] = number_format((float)($lpd[$dtime][$skey] + @$quantity), 3, '.', '');
                                    }
                                //}    
                            }
                        }
                    } 
                   
                }    
                
                //echo "<pre>"; print_r($prodtl); 
            }
        }
        //echo "<pre>"; print_r($type);
        // echo "<pre>"; print_r($slpdlable1); 
        // echo "<pre>"; print_r($aproducts);die();    
        // echo "<pre>"; print_r($prodtl); 
        if($aobj1){
            //echo "<pre>"; print_r($aproducts); die();
            foreach ($aobj1 as $okey1 => $ovalue1) {
                $FDC_DATE1 = $ovalue1->DATE;
                //$typtype1e = $ovalue1->RCTNUM;
                //echo  $FDC_DATE1; echo "<br>"; echo date("Y/m/d",strtotime($singledate) + 86400); echo "<br>";
                if($FDC_DATE1 ==  date("Y/m/d",strtotime($singledate) + 86400) ){
                    $FDC_TIME1 = $ovalue1->TIME;
                    $astr1 = explode(":",$FDC_TIME1);
                    $i1 = ltrim($astr1[0], '0');
                    if(empty($i1)){
                        $i1 = 0;
                    }
                    if( is_numeric($i1) && $i1==0){
                        //echo "1";
                        $i2 = 24;
                        foreach ($aproducts as $kpey => $valpue) {
                            foreach ($slpdlable1 as $kepy => $valupe) {
                                if($valupe == $valpue){
                                    $skey = $slpdkey1[$kepy];
                                    $atran[$i2][$skey] = $atran[$i2][$skey] + 1;
                                }
                            }
                        }
                    }
                }
            }
            //echo "<pre>";print_r($atran); die();
        }
        $wtpd  = array();
        foreach ($d as $dkey => $dvalue) {
            $fd = array();
            foreach ($dvalue as $d1key => $d1value) {
                $fd[] = array($d1key."000",$d1value);
            }
            foreach ($slpdkey1 as $kepy => $valupe) {
                if($valupe == $dkey){
                    $labelss = $slpdlable1[$kepy];
                    $colorss = $slpdcolor1[$kepy];
                    $object = new \stdClass();
                    $object->data = $fd;
                    $object->label = $labelss;
                    $object->color = $colorss;
                    $wtpd[] = $object;
                }
            }
        }
        //echo  "<pre>";print_r($atran); die();
        $stran = json_encode($atran);
        //echo $stran; die();
        $alpd = array();
        foreach ($lpd as $lkey => $lvalue) {
           $alpd[] = $lvalue;
        }
        $prodtla = array();
        //echo "<pre>";  print_r($prodtl); die();
        foreach ($prodtl as $dkey => $dvalue) {
            foreach ($slpdlable1 as $kepy => $valupe) {
                if($valupe == $dkey){
                    $labelss = $slpdlable1[$kepy];
                    $colorss = $slpdcolor1[$kepy];
                    $object = new \stdClass();
                    $object->data = $dvalue['qty'];
                    $object->label = $labelss;
                    $object->color = $colorss;
                    $prodtla[] = $object;
                }
            }
        }
        $slpd = json_encode($alpd);
        $slpdkey = json_encode($slpdkey);
        $slpdlable = json_encode($slpdlable);
        $slpdcolor = json_encode($slpdcolor);
        $prodtla = json_encode($prodtla);
        $data = array(   "total_amount" => $total_amount,
                         "total_tran" => $total_tran,
                         "fuel" => $prodtl
                         );
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager') || $user->hasRole('company')){
            return view('pages/admin/rct/rct-dashboard',compact('singledate','search_input','data','stations','role_name','stran','wtpd','slpd','currency_code','decimal_point','aproducts','slpdkey','slpdlable','slpdcolor','prodtla'));
        }
        if($user->hasRole('user') ){
            return view('pages/admin/rct/rct-dashboard',compact('singledate','search_input','data','stations','role_name','stran','wtpd','slpd','currency_code','decimal_point','aproducts','slpdkey','slpdlable','slpdcolor','prodtla'));
        }
        
    }

    
}
