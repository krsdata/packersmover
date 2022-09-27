<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Expenses;
use App\Stations;
use App\StationLogo;
use Mail;
use File;
use App\Expenselog;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Input, Redirect, Session, Response, DB;

class ExpenseController extends Controller
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
        $status=''; $station='';
        $user = Auth::user();
        $perPage = 10;
        $station=$request->get('station');
        $status=$request->get('status');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }

        if($user->hasRole('manager') || $user->hasRole('accountant') || $user->hasRole('account-manager') || $user->hasRole('admin') || $user->hasRole('owner')){

            $expenses=Expenses::orderBy('id', 'desc');
            if($user->hasRole('admin')){
                $station_arrray = Stations::where("active",'1')->orderBy('name', 'asc')->get();
                $expenses=Expenses::orderBy('id', 'desc');
            }else{
               $station_arrray = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get(); 
               $expenses=Expenses::whereIn("station_id", explode(',', $user->stations_id))->orderBy('id', 'desc');   
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
                    $expenses->whereBetween('DATE_TIME', [$from8, $to8]);
            }

            if(!empty($station)){
                $expenses->whereIn('station_id',$station);

            }    

            //echo "<pre>"; print_r($expenses->get()); die();
            

            if (!empty($status) && $status!='pending') {
               $statusarray = explode(' ', $status, 2);
               $expenses->where($statusarray[0]."_status",'=',$status);
            }

            if ($status=='pending'){
                $expenses->where("accountant_status",'=',$status);
            }

            

            if($user->hasRole('accountant')){
                if ($status=='pending'){
                   $expenses->where("accountant_status",'=',$status);
                }

                if(!empty($status) && $status!='pending'){
                    $statusarray = explode(' ', $status, 2);
                    $expenses->where($statusarray[0]."_status",'=',$status);
                }

                
            }
            
            if($user->hasRole('account-manager')){
                if(!empty($status)){
                   $statusarray = explode(' ', $status, 2);
                   $expenses->where($statusarray[0]."_status",'=',$status);  
                }else{
                   $expenses->Where('accountant_status','=','accountant accept');
                } 
            }


            //echo "<pre>"; print_r($expenses->get()); die();
            $e_count = $expenses->count();
            $e_amt = $expenses->sum('amount');
            $expenses = $expenses->paginate($perPage)->appends(request()->query());
            $fullUrl = $request->fullUrl();
            $fullUrl = str_replace('/expense', '/expense-download',$fullUrl);
            $txtUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=txt';
            $xmlUrl = $fullUrl.(parse_url($fullUrl, PHP_URL_QUERY) ? '&' : '?') . 'type=xml';
            if ($request->ajax()){
                $view = view("pages.admin.expense.table_view",compact('station','expenses','status','dates','e_count','fullUrl','txtUrl','xmlUrl','e_amt','station_arrray','user'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);
            }else{
                    return view('pages.admin.expense.index',compact('station','expenses','status','dates','e_count','fullUrl','txtUrl','xmlUrl','e_amt','station_arrray','user'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
            return view('home'); 
        }

        
    }

    public function expense_create()
    {
        $user = Auth::user();
        if($user->hasRole('admin')){
            $Stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
        }else{
            $Stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();    
        }
        return view('pages/admin/expense/create',compact('Stations'));
    }

    public function expense_update($id)
    {
        $logo="";
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $expense_data = Expenses::findorfail($id);
        $logo=$expense_data->image;
        $station_id=array("0"=>$expense_data->station_id);
        $Stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        return view('pages/admin/expense/create',compact('expense_data','Stations','logo','station_id'));
    }

    protected function validator_expense_info(array $data) {
        $cuser = Auth::user();
        $au = [
            'title' => 'required',
            'description' => 'required',
            'amount' => 'required',

        ];
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $au['title'] = 'required|unique:expenses,title,'.$id;
            return Validator::make($data, $au, ['title.unique' => 'Title is already exists!!',]);
        } else {
            $au['title'] = 'required|max:255|unique:expenses';
            return Validator::make($data, $au, ['title.unique' => 'Title  is already exists!!',]);
        }
    }

    public function expense_store(Request $request) {
        $user = Auth::user();
        $validator_expense_info = $this->validator_expense_info($request->all());
        if ($validator_expense_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_expense_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();
            if($request['Station_Id']){
                $data['Station_Id']=@$request['Station_Id'][0];
            }
            $data=strip_tag_function($data);
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Expenses::findorfail($id);
                $obj_data->description = $data['description'];
                $obj_data->title = $data['title'];
                $obj_data->amount = $data['amount'];
                $obj_data->station_id = $data['Station_Id'];
                if(!empty($data['expense_imgval'])){
                       $obj_data->image=$data['expense_imgval']; 
                }
                if ($obj_data->update()) {
                    $this->write_expense_log($data['Station_Id'],$data['title'],"Expense Updated Successfully",$obj_data->id,$user->id,'update','');
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Expense has been updated Sucessfully!','redirect_url'=>'/admin/expense']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Expense Updation failed!','redirect_url'=>'/admin/expense']);
                }
            }else{
                $obj = new Expenses();
                $obj->title = $data['title'];
                $obj->description = $data['description'];
                $obj->amount = $data['amount'];
                $obj->station_id = $data['Station_Id'];
                if(!empty($data['expense_imgval'])){
                        $obj->image=$data['expense_imgval']; 
                }
                if ($obj->save()) {
                    // Track the expense
                    $this->write_expense_log($data['Station_Id'],$data['title'],"Expense Created Successfully",$obj->id,$user->id,'create','');
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Expense has been added Sucessfully!','redirect_url'=>'/admin/expense']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Expense Insertion failed!','redirect_url'=>'/admin/expense']);
                }
            }
        }
    }

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('expense_image')) {
            $file = $request->file('expense_image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/expense_img';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    public function update_pending_status(Request $request){
        //echo "<pre>"; print_r($request->all());die();
        $last_expense_id=0;
        $user = Auth::user();
        $id=$request['id'];
        $role=$request['role'];
        $status=$request['status'];
        if($id && $role && $status){
           $id = en_de_crypt($id, 'd');
           $if_exist=Expenselog::where('expense_id',$id)->first();
           if(!$if_exist){
               return response()->json(["success"=>"False","msg" => "Expense log not found for this expense"]);
            }else{
                    $xpenses_data=Expenses::where('id',$id)->first();
                    if($status=='reject'){
                        $reject_reason=$request['reject_reason'];
                        $this->write_expense_log($xpenses_data->Station_Id,$xpenses_data->title,"Expense ".$status." by ". $user->name." ".$user->last_name,$id,$user->id,$status,$reject_reason);
                    }
                    $pa_status=$xpenses_data->accountant_status;
                    $pm_status=$xpenses_data->account_manager_status;
                    if($pa_status=='pending' && $role== 'accountant'){
                       $update_status_expense=Expenses::where('id',$id)->update(["accountant_status"=>$role." ".$status]);
                       $this->write_expense_log($xpenses_data->Station_Id,$xpenses_data->title,"Expense ".$status." by ". $user->name." ".$user->last_name,$id,$user->id,$status,'');
                       return response()->json(["success"=>"True","msg" => "Expense log updated Successfully"]);
                    }else if ($pa_status!='pending' && $pa_status=='accountant accept' && $role== 'account_manager' && $pm_status=='pending'){
                        $update_status_expense=Expenses::where('id',$id)->update(["account_manager_status"=>$role." ".$status]);
                        $this->write_expense_log($xpenses_data->Station_Id,$xpenses_data->title,"Expense ".$status." by ". $user->name." ".$user->last_name,$id,$user->id,$status,'');
                       return response()->json(["success"=>"True","msg" => "Expense log updated Successfully"]);
                    }else{
                         return response()->json(["success"=>"False","msg" => "Status for the expense is already saved"]);
                    }
                    
                    
                   
                     
            }
        }
    }

    public function write_expense_log($station,$title,$msg,$expense_id,$user_id,$status,$reject_reason){
       $contents = "Time : ".date("Y-m-d h:i:s a",time())." // Status : ".$msg."\n";
       $expenselog=new Expenselog();
       $expenselog->expense_id=$expense_id;
       $expenselog->user_id=$user_id;
       $expenselog->note=$contents;
       $expenselog->status=$status;
       if(!empty($reject_reason)){
          $expenselog->reject_reason=$reject_reason;
       }
       $expenselog->save();
    }

    public function  get_expense_details(Request $request){
       $id=$request['id']; 
       if($id){
           $id = en_de_crypt($id, 'd');
           $if_exist=Expenses::where('id',$id)->first();
            if(!$if_exist){
               return response()->json(["success"=>"False","msg" => "Expense not found","data"=>""]);
            }else{
                   $Expenselog=Expenselog::where('expense_id','=',$id)->get(); 
                   $data=array('expense'=>$if_exist,"log"=>$Expenselog);
                   return response()->json(["success"=>"True","msg" => "Expense details","data"=>$data]); 
            }
        }
    }

    // public function  expense_delete(Request $request){
    //     $id=$request['id']; 
    //     if($id){
    //        $id = en_de_crypt($id, 'd');
    //        $if_exist=Expenses::where('id',$id)->first();
    //         if(!$if_exist){
    //            return response()->json(["success"=>"False","msg" => "Expense not found","data"=>""]);
    //         }else{
    //                $if_exist_in_log=Expenselog::where('id',$id)->count();
    //                if($if_exist_in_log > 0){
    //                   return response()->json(["success"=>"False","msg" => "Please delete related expense log and try again","data"=>""]);  
    //                }else{
    //                       $if_exist->delete(); 
    //                       return response()->json(["success"=>"True","msg" => "Expense deleted successfully"]); 
    //                }
                   
    //         }
    //     }
    // }

    public function listDowload(Request $request)
    {
        $status=''; $s_name=''; $slogo=''; $stitle='';
        $user = Auth::user();
        $perPage = 10;
        $station=$request->get('station');
        $status=$request->get('status');
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }
        
        if($user->hasRole('manager') || $user->hasRole('accountant') || $user->hasRole('account-manager') || $user->hasRole('admin') || $user->hasRole('owner')){
            $perPage = 10;
            $station_selected =$request->get('station');
            $tank_selected =$request->get('tank');
            $product_selected =$request->get('product');

            $expenses = Expenses::orderBy('id', 'desc');
            
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
                    $expenses->whereBetween('DATE_TIME', [$from8, $to8]);
            }

            if (!empty($status) && $status!='pending') {
               $statusarray = explode(' ', $status, 2);
               $expenses->where($statusarray[0]."_status",'=',$status);
            }

            if ($status=='pending'){
                $expenses->where("accountant_status",'=',$status);
            }

            

            if($user->hasRole('accountant')){
                if ($status=='pending'){
                   $expenses->where("accountant_status",'=',$status);
                }

                if(!empty($status) && $status!='pending'){
                    $statusarray = explode(' ', $status, 2);
                    $expenses->where($statusarray[0]."_status",'=',$status);
                }

                
            }
            
            if($user->hasRole('account-manager')){
                if(!empty($status)){
                   $statusarray = explode(' ', $status, 2);
                   $expenses->where($statusarray[0]."_status",'=',$status);  
                }else{
                   $expenses->Where('accountant_status','=','accountant accept');
                } 
            }

            // get_station details
            $station_details=Stations::find(@$station_selected[0]);
            if(!empty($station_details)){
                $stitle=$station_details->title;
                // logo 
                $station_logo=StationLogo::where('station_id',$station_details)->first();
                if(!empty($station_logo)){
                      $slogo=$station_logo->name;
                }
                
            }

            $expenses = $expenses->get();
            $e_count = $expenses->count();
            $e_amt = $expenses->sum('amount');
            $data = array("total_expense" => $e_count,"total_amount" => $e_amt);

            $type = $request->get('type');
            if($type == "txt"){
                $txt = "";
                $txttitle = $dates."-".$status.".txt";
                $txt .= "Expense List: ".$dates."\n \n";
                

                if(!empty($stitle)){
                    $txt .= "Station: ".$stitle."\n \n";
                }

                if(!empty($status)){
                    $txt .= "Status: ".$status."\n \n";
                }
                if(!empty($expenses)){
                    foreach ($expenses as $lkey => $list) {
                        if(!empty($list->accountant_status) && $list->accountant_status!='pending' ){
                            $sttauss=substr($list->accountant_status, strpos($list->accountant_status, " ") + 1);
                       }else{
                            $sttauss=$list->accountant_status;
                       }

                       if(!empty($list->account_manager_status) && $list->account_manager_status!='pending' ){
                                 $msttaus= substr($list->account_manager_status, strpos($list->account_manager_status, " ") + 1);
                          }else{
                                 $msttaus= $list->account_manager_status;  
                        }

                        $apro[] = array("TITLE" => $list->title, "DESCRIPTION" => $list->description, "AMOUNT" => $list->amount, "ACCOUNTANT STATUS" => $sttauss, "ACCOUNT MANAGER STATUS" => $msttaus);
                        
                    }
                }
                
                $alabel[]= array(
                    "Expense Total" =>number_format($data['total_expense'])." Count","Amount Total"=>$data['total_amount']);
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
                $xlstitle = $dates."-".$status.".xls";
                $xls .= "Expense List: ".$dates."\n \n";
                
                if(!empty($stitle)){
                    $xls .= "Station: ".$stitle."\n \n";
                }

                if(!empty($status)){
                    $xls .= "Status: ".$status."\n \n";
                }
                if(!empty($expenses)){
                    foreach ($expenses as $lkey => $list) {

                       if(!empty($list->accountant_status) && $list->accountant_status!='pending' ){
                            $sttauss=substr($list->accountant_status, strpos($list->accountant_status, " ") + 1);
                       }else{
                            $sttauss=$list->accountant_status;
                       }

                       if(!empty($list->account_manager_status) && $list->account_manager_status!='pending' ){
                                 $msttaus= substr($list->account_manager_status, strpos($list->account_manager_status, " ") + 1);
                          }else{
                                 $msttaus= $list->account_manager_status;  
                        }
                       $apro[] = array("TITLE" => $list->title, "DESCRIPTION" => $list->description, "AMOUNT" => $list->amount, "ACCOUNTANT STATUS" => $sttauss, "ACCOUNT MANAGER STATUS" => $msttaus);                       
                    }
                }
                
                $alabel[]= array(
                    "Expense Total" =>number_format($data['total_expense'])." Count","Amount Total"=>$data['total_amount']);
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
                
                $view = view("pages.admin.expense.pdf_view",compact('dates','station','expenses','e_count','e_amt','status'))->render();
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
                $pdfhtml .= "<p>Expense: ".$dates."</p><p>Station: ".@$stitle."</p><p>&nbsp;</p>";
                $pdfhtml .= $view;
                $pdfhtml .= '</div></body></html>';     
                $pdftitle = $dates."-".$s_name.".pdf";

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
}    
