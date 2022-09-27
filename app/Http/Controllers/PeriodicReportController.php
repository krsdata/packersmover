<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Stations;
use App\Trn;
use App\Tanks;
use App\StationLogo;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Input, Redirect, Session, Response, DB;
class PeriodicReportController extends Controller
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
    public function index(Request $request)
    {
        $user = Auth::user(); $currency_code="Amt";  $decimal_point=2; $t_discount=0; $amos=0; $vol=0; $amo_new=0;
        $perPage=10;  
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        $station=$request->get('station');
        $tank_id=$request->get('tank');
        
        
        if(!empty($s_datese)){
            $dates = $s_datese;
        }

        if($user->hasRole('manager')  || $user->hasRole('admin') || $user->hasRole('owner')){

            if($user->hasRole('admin')){
                    $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
                   $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
            }

            $obj = Trn::orderBy('FDC_SAVE_NUM', 'desc');        

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

            if(!empty($station)){
                $obj->whereIn('stations_id',$station);
                $s_details=Stations::whereIn('id',$station)->get();
                if($s_details){
                    // get station 
                    foreach ($s_details as $skey => $svalue) {
                        $currency_code = $svalue['currency_code'];
                        $decimal_point = $svalue['decimal_point'];
                    }   
                }

                if($tank_id){
                    $tank_id=en_de_crypt($tank_id, 'd');
                    $t_data=Tanks::find($tank_id);
                    if(!empty($t_data)){
                        $tank=$t_data->tank_name;
                    }
                    $obj->where('tank_id','=',$tank_id);
                }

                $t_count = $obj->count(); 
                $t_discount = $obj->sum('AMO_DISCOUNT');
                $amos = $obj->sum('AMO');
                $vol = $obj->sum('VOL');
                $amo_discount = $obj->sum('AMO_DISCOUNT');
                if($amo_discount > 0 ){
                   $amo_new= $obj->sum('AMO') - $obj->sum('AMO_DISCOUNT');
                }else{
                    $amo_new= $obj->sum('AMO') + $obj->sum('AMO_DISCOUNT');
                }
            }

            //print_r($obj->all()); die();
             
            $fullUrl = $request->fullUrl();
            $fullUrl = str_replace('/periodic-report', '/periodic-report-download',$fullUrl);
            return view('pages/admin/periodic_report/index',compact('dates','stations','station','fullUrl','t_discount','amos','vol','amo_new','decimal_point','currency_code'));
        }    
    }

    public function list_download(Request $request){

        $user = Auth::user(); $stitle;  $sname; $station_logos=''; $tin;  $vrn; $no; $info;  $station_details = array();
        $currency_code="Amt";  $decimal_point=0; $t_discount=0; $amos=0; $vol=0; $amo_new=0; $tank="";
        $perPage=10;  
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        $station=$request->get('station');
        $tank_id=$request->get('tank');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }  

        if($user->hasRole('manager')  || $user->hasRole('admin') || $user->hasRole('owner')){
           $obj = Trn::orderBy('FDC_SAVE_NUM', 'desc');        

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

            if(!empty($station)){
                $obj->whereIn('stations_id',$station);
                $s_details=Stations::whereIn('id',$station)->get();
                if($s_details){
                    // get station 
                    foreach ($s_details as $skey => $svalue) {
                        $station_details[$skey]['title'] = $svalue['title'];
                        $station_details[$skey]['name'] = $svalue['name'];
                        $station_details[$skey]['info'] = $svalue['info'];
                        $station_details[$skey]['tin'] = $svalue['tin'];
                        $station_details[$skey]['vrn'] = $svalue['vrn'];
                        $station_details[$skey]['service_station'] = $svalue['service_station'];
                        $station_details[$skey]['serial_number'] = $svalue['serial_number'];
                        $station_details[$skey]['tel'] = $svalue['tel'];
                        $currency_code = $svalue['currency_code'];
                        $decimal_point = $svalue['decimal_point'];
                       // get station logo 
                       $station_logo=StationLogo::where('station_id',$station)->first();
                       if(!empty($station_logo)){
                          $station_details[$skey]['logo'] = $station_logo->name;
                       }
                    }   
                }

                $t_count = $obj->count();
                $t_discount = $obj->sum('AMO_DISCOUNT');
                $amos = $obj->sum('AMO');
                $vol = $obj->sum('VOL');
                $amo_discount = $obj->sum('AMO_DISCOUNT');
                if($amo_discount > 0 ){
                   $amo_new= $obj->sum('AMO') - $obj->sum('AMO_DISCOUNT');
                }else{
                    $amo_new= $obj->sum('AMO') + $obj->sum('AMO_DISCOUNT');
                }
            }

            if($tank_id){
                $tank_id=en_de_crypt($tank_id, 'd');
                $t_data=Tanks::find($tank_id);
                if(!empty($t_data)){
                    $tank=$t_data->tank_name;
                }
                $obj->where('tank_id','=',$tank_id);
            }

            //$obj = $obj->get();
            
            
            $view = view("pages.admin.periodic_report.pdf_view",compact('dates','station','t_count','station_logos','station_details','t_discount','tank','amos','vol','amo_new','decimal_point','currency_code'))->render();
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
                $pdftitle = $dates.".pdf";
                $pdf = new Pdf($pdfhtml);
                if (!$pdf->send($pdftitle)) {
                    $error = $pdf->getError();
                    print_r($error);
                }
                exit;
        }
    }
}

?>
