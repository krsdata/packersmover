<?php
use App\Mail\UserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Notification;
/**
 * en_de_crypt function
 *
 * @param string $string
 * @param string $action
 * @return void
 */
function en_de_crypt($string, $action = 'e')
{

    $secret_key = 'a1s3er191e43f6b7ddsdg2x3q32x';
    $secret_iv =  'a1snsd5nrer17dg4g95ff9llkw22x';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

/**
 * get_data function
 *
 * @param [type] $model
 * @param string $select
 * @param array $wheres
 * @param string $types
 * @param string $order_by
 * @param string $order
 * @return void
 */
function  get_data( 
    $model,
    $select="id",
    $wheres=array(),
    $type="all",
    $order_by="created_at",
    $order= "desc"
){
    $user = Auth::user();
    $valid = "0";
    if($user->hasRole('admin') || $user->hasRole('manager')){
        if($model == "Company" || $model == "Category" || $model == "Stock"){
            $valid = "1";
        }
    }
    if($valid == "0"){
        return array();
    }
    if(!empty($select)){
        $aselect = explode(",",$select);
    }else{
        $aselect = ['id'];
    }
    if ($model) {
        $mod_name = '\\App\\' . $model;
        $data_obj = $mod_name::orderBy($order_by, $order);
        if(!empty($wheres)){
            foreach ($wheres as $key => $value) {
                $wcomp = $value['comp'];
                $wval = $value['val'];
                $wkey = $value['key'];
                if(!empty($wkey) && !empty($wcomp) && !empty($wval)){
                    if($wcomp == "IN"){
                        $data_obj->whereIn($wkey, explode(",",$wval) );
                    }elseif($wcomp == "NOTIN"){
                        $data_obj->whereNotIn($wkey, explode(",",$wval) );
                    }elseif($wcomp == "WILDLIKE"){
                        $data_obj->where($wkey, 'like', '%' . $wval . '%');
                    }else{
                        $data_obj->where($wkey, $wcomp, $wval);
                    }
                }
            }
        }
        
        $data_obj = call_user_func_array(array($data_obj, "select"), $aselect);

        if($type == "count"){
            $data_obj = $data_obj->get()->count();
        }else{
            $data_obj = $data_obj->get();
            if ($data_obj) {
                foreach ($data_obj as $okey => $ovalue) {
                    $id = $ovalue->id;
                    $ovalue->enc_id = en_de_crypt($id, 'e');
                    $data_obj[$okey] = $ovalue;
                }
            } 
        }
    } 
    return $data_obj;
}

/**
 * notification_list
 */

function notification_list($type)
{

    $user = Auth::user();
    $current_user_id = $user->id;
    if($type == "count"){
        return Notification::where('deleted_at','=',NULL)->where('receiver_id','=',$current_user_id)->where('status','=','send')->orderBy('id','desc')->count();
    }else{
        return Notification::where('deleted_at','=',NULL)->where('receiver_id','=',$current_user_id)->where('status','=','send')->orderBy('id','desc')->take(5)->get();
    }
}

/**
 * Undocumented function
 *
 * @param array $ldatas
 * @param string $title
 * @param string $file_name
 * @return void
 */
function downloadCSV($ldatas,$title="",$file_name="")
{
    $csv = "";
    if(!empty($title)){
        $csv .=  $title;
        $csv .= "\n \n \n";
    }
    if(empty($file_name)){
        $file_name = uniqid().".csv";
    }
    
    $flag = false;
    if(!empty($ldatas)){
        foreach ($ldatas as $pkey => $row) {
            if (!$flag) {
                $csv .=  implode(",", array_keys($row)) . "\n";
                $flag = true;
            }
            $csv .=  implode(",", array_values($row)) . "\n";
        }
    }
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-disposition: attachment; filename='.urlencode($file_name));
    header('Content-Length: '.strlen($csv));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    echo $csv;
    exit;
}

/**
 * downloadXLS function
 *
 * @param array $ldatas
 * @param string $title
 * @param string $file_name
 * @return void
 */
function downloadXLS($ldatas,$title="",$file_name="")
{
    $xls = "";
    if(!empty($title)){
        $xls .=  $title;
        $xls .= "\r\n \r\n \r\n";
        $xls .= "\r\n \r\n \r\n";
    }
    if(empty($file_name)){
        $file_name = uniqid().".xls";
    }
    
    $flag = false;
    if(!empty($ldatas)){
        foreach ($ldatas as $pkey => $row) {
            if (!$flag) {
                $xls .=  implode("\t", array_keys($row)) . "\r\n";
                $flag = true;
            }
            $xls .=  implode("\t", array_values($row)) . "\r\n";
        }
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename='.urlencode($file_name));
    header('Content-Length: '.strlen($xls));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    echo $xls;
    exit;
}

/**
 * downloadPDF function
 *
 * @param string $view
 * @param string $title
 * @param string $file_name
 * @return void
 */
function downloadPDF($view,$title="",$file_name="")
{
    $pdfhtml = '<html><head>
    <style>.card{display:block;background-color:#fff;background-clip:border-box;border:1px solid #d2d2dc;border-radius:0;padding:0!important;background:#fefefe}.card-body,.modal-body{padding:5px!important}.mt-1{margin-top:.25rem!important}.w-100{width:100%!important}.justify-content-center{justify-content:center!important}.table-responsive{display:block;width:100%}.pt-3,.py-3{padding-top:1rem!important}.text-left{text-align:left!important}.table-responsive>.table-bordered{border:0}table{border-collapse:collapse;max-width:100%;overflow:hidden}*{box-sizing:border-box}.table-striped th{color:#333333;font-size:13px;}.table thead th{vertical-align:bottom;border-bottom:0 solid #ddd;border-top:0;border-bottom-width:0;font-weight:600;font-size:.875rem;text-transform:uppercase;line-height:1;white-space:nowrap;padding:1.25rem .9375rem;text-align:inherit}.table-striped tbody tr:nth-of-type(odd){background-color:#eee}.table td{font-size:.875rem;padding:.875rem .9375rem;vertical-align:middle;line-height:1;white-space:nowrap;border-top:0 solid #ddd}.table td button{background:0 0;border-color:transparent}.row{display:flex;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.stretch-card{display:-webkit-flex;display:flex;-webkit-align-items:stretch;align-items:stretch;-webkit-justify-content:stretch;justify-content:stretch}.grid-margin{margin-bottom:1.875rem}.col-md-4,.lightGallery .image-tile{width:25%;float:left;margin-right:7.3%}.bg-primary,.settings-panel .color-tiles .tiles.primary{background-color:#331cbf!important}.border-0{border:0!important}.border-radius-2{border-radius:2rem}.card{box-shadow:none;-webkit-box-shadow:none;-moz-box-shadow:none;-ms-box-shadow:none}.stretch-card>.card{width:100%;min-width:100%}.card .card-body{padding:1.25rem 1.75rem}.card-body{flex:1 1 auto;padding:1.25rem;box-shadow:none}.flex-xl-row{flex-direction:row!important}.icon-rounded-inverse-primary{background:#fff;width:1.875rem;height:1.875rem;border-radius:50%;text-align:center;box-shadow:none;float:left;margin:25px 0 0 30px;display:none}.text-white{color:#fff!important;width:90.875rem;margin-left:50px;margin-top:0}.cust-card-dash p{font-size:.77rem}.font-weight-medium{font-weight:500}.text-uppercase{text-transform:uppercase!important}.text-xl-left{text-align:left!important}.mt-xl-0,.my-xl-0{margin-top:0!important}p{margin-bottom:0;line-height:1.5rem}.align-items-xl-baseline{align-items:baseline!important;margin-top:-10px}.flex-xl-row{flex-direction:row!important}.cust-card-dash h3{font-size:1.3rem}.mb-lg-0,.my-lg-0{margin-bottom:0!important}.mr-1,.mx-1{margin-right:.25rem!important}.mb-0,.my-0{margin-bottom:0!important}.small,small{font-size:80%;font-weight:400}
    </style></head><body><div class="modal-body" id="csrdetailbody">';
    $pdfhtml .= $title;
    $pdfhtml .= $view;
    if(empty($file_name)){
        $file_name = uniqid().".pdf";
    }
    $pdfhtml .= '</div></body></html>';
    $pdftitle = $file_name.".pdf";
    $pdf = new \mikehaertl\wkhtmlto\Pdf($pdfhtml);
    if (!$pdf->send($pdftitle)) {
        $error = $pdf->getError();
        print_r($error);
    }
    exit;
}