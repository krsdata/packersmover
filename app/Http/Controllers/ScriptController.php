<?php

namespace App\Http\Controllers;

use App\Trn;
use App\Csr;
use App\CsrProduct;
use App\CsrTank;
use App\CsrNozzle;
use App\User;
use App\StationsScan;
use App\Stations;
use App\Options;
use App\StationsLoyalty;
use App\UsersLoyalty;
use App\AutoLoyaltyPoint;
use App\Tanks;
use App\TankTrn;
use App\Notifications\TrnDetail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        ini_set('max_execution_time', 1800); //30 minutes
        //$this->scandir = base_path().DIRECTORY_SEPARATOR.'stations';
        //$this->scandir = '/home/rohe/public_html/sjv_test';
        $this->scandir = config('app.default_xml_path');
        //print_r( $this->scandir );die;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        echo "index";
    }

    public function cronScript($pass){
        if($pass=="Ifg6m0pyt34g68i"){
            $scandir =  $this->scandir;
            $files = scandir($scandir);
            if(!empty($files)){
                //echo "1"; die();
                foreach ($files as $dir) {
                    if(is_dir($scandir.DIRECTORY_SEPARATOR.$dir))
                    {
                        if( $dir != ".." && $dir != "." ){
                            echo "*/5 * * * * curl ".url("/")."/cron_script/".$dir."/move";
                            echo "<br>";
                        }
                    }
                }
            }
        }
    }
    public function scanStations($dir,$action){
        $opdata = Options::orderBy('created_at', 'desc');
        $opdata->where('okey', '=',  $dir.'_scans_status' );
        $data_options = $opdata->first();
        if(!$data_options){
            $data_options = Options::firstOrCreate(['okey' => $dir.'_scans_status','ovalue' => 'sleep']);
        }
        if($data_options->ovalue == "sleep"){
            $data_options->ovalue = 'wait';
            $data_options->update();
            $scandir =  $this->scandir;
            $i = 0;
            if( $dir != ".." && $dir != "." ){
                $dfiles = scandir($scandir.DIRECTORY_SEPARATOR.$dir);
                if($dfiles){
                    foreach ($dfiles as $filename) {
                        if(!is_dir($scandir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$filename))
                        {
                            if($filename != "index.php" && $filename != "index.html" && $filename != "." && $filename != ".." && $filename != "log.txt"){
                                $newstring = substr($filename, -4);
                                if($newstring == ".xml"){
                                    $type = substr($filename, 0, 3);
                                    if( $type == "csr" || $type == "trn" ){
                                        $result = $this->checkAlreadyExist($scandir, $dir, $filename);
                                        if($result['status'] == "success"){
                                            $this->processStationFile($scandir, $dir, $filename, $action);
                                            $i ++;
                                        }else{
                                            $this->write_log( $dir, $filename, $result['status']);
                                            if($action == "move"){
                                                $this->move_file($dir,$filename);
                                            }
                                            if($action == "delete"){
                                                $this->delete_file($dir,$filename);
                                            }
                                        }
                                    }else{
                                        $this->write_log( $dir, $filename, "File type not match.");
                                        if($action == "move"){
                                            $this->move_file($dir,$filename);
                                        }
                                        if($action == "delete"){
                                            $this->delete_file($dir,$filename);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $data_options->ovalue = 'sleep';
            $data_options->update();
        }else{
            if( strtotime($data_options->updated_at) < ( time() - 300 ) ){
                $data_options->ovalue = 'sleep';
                $data_options->update();
            }
        }
    }

    function checkAlreadyExist($scandir, $dir, $filename){
        $scdata = StationsScan::orderBy('created_at', 'desc');
        $scdata->where('folder_name', '=',  $dir );
        $scdata->where('active', '=',  '1' );
        $scdata->where('file_name', '=',  $filename );
        $data_sc = $scdata->count();
        $result = array();
        if(empty($data_sc)){ $data_sc = 0; }
        if($data_sc > 0){
            $scandata = $scdata->first();
            $scanid = $scandata->id;
            $strndata = Trn::orderBy('created_at', 'desc')->where('scans_id', '=',  $scanid )->count();
            if($strndata > 0){
                $result['status'] = "File already exist. Please delete the file.";
            }else{
                $scsrdata = Csr::orderBy('created_at', 'desc')->where('scans_id', '=',  $scanid )->count();
                if($scsrdata > 0){
                    $result['status'] = "File already exist. Please delete the file.";
                }else{
                    $scandata->delete();
                    $result['status'] = "success";
                }
            }
        }else{
            $result['status'] = "success";
        }
        return $result;
    }

    function processStationFile($scandir, $dir, $filename, $action){
        $type = substr($filename, 0, 3);
        if( $type != "csr" && $type != "trn" ){
            // $this->write_log( $dir, $filename, "file type not found.");
        }else{
            $ext = substr($filename, -4);
            if( $ext != ".xml" ){
               // $this->write_log( $dir,  $filename, "file extension not found.");
            }else{
                $stations = Stations::firstOrCreate(['name' => $dir]);
                if(!isset($stations->id)){
                    $this->write_log( $dir,  $filename, "stations not found.");
                }else{
                    $stations_id = $stations->id;
                    if(empty($stations->title)){
                        $stations->title = $stations->name;
                        $stations->currency_code = "Amt";
                        $stations->update();
                    }
                    $ss = new StationsScan();
                    $ss->active = '1';
                    $ss->file_name = $filename;
                    $ss->folder_name = $dir;
                    $ss->type = $type;
                    $ss->records = 'NA';
                    $ss->error = 'NA';
                    if ($ss->save()) {
                        $stations->save();
                        $scans_id = $ss->id;
                        $tmp_name = $scandir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$filename;
                        $xmlstring = file_get_contents($tmp_name);
                        libxml_use_internal_errors(true);
                        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
                        if (!$xml) {
                            if($action == "move"){
                                $this->move_file($dir,$filename);
                            }
                            if($action == "delete"){
                                $this->delete_file($dir,$filename);
                            }
                            $this->write_log( $dir, $filename, "Invalid xml format.");
                            return;
                        }
                        $json = json_encode($xml);
                        $array = json_decode($json,TRUE);
                        //print_r($array);die;
                        $csr = array("stations_id" => $stations_id, "scans_id" => $scans_id);
                        if($type == "csr"){
                            $CSR_DETAILS = $array['CSR_DETAILS']['@attributes'];
                            $SITE_DATA = $array['SITE_DATA']['@attributes'];
                            if(is_array($CSR_DETAILS)){
                                $csr_data = array_merge($CSR_DETAILS, $SITE_DATA, $csr);
                                $csr_data['FDC_START_DATE_TIME'] =  $csr_data['FDC_START_DATE']." ".$csr_data['FDC_START_TIME'];
                                $csr_id = $this->save_data('Csr',$csr_data);
                                if($csr_id > 0){
                                    $ss->records = $csr_id;
                                    $ss->update();
                                    $PROD_DATA = $array['PROD_DATA']['PROD'];
                                    if(!empty($PROD_DATA)){
                                        foreach ($PROD_DATA as $pkey => $pvalue) {
                                            $PROD = $pvalue['@attributes'];
                                            $PROD['csr_id'] = $csr_id;
                                            $this->save_data('CsrProduct',$PROD);
                                        }
                                    }
                                    if(isset($array['TANK_DATA']['TANK'])){
                                        $TANK_DATA = $array['TANK_DATA']['TANK'];
                                        if(!empty($TANK_DATA)){
                                            foreach ($TANK_DATA as $tkey => $tvalue) {
                                                $TANK = $tvalue['@attributes'];
                                                $TANK['csr_id'] = $csr_id;
                                                $this->save_data('CsrTank',$TANK);
                                            }
                                        }
                                    }
                                    if(isset($array['RDG_DATA']['RDG_INFO'])){
                                        $RDG_INFO = $array['RDG_DATA']['RDG_INFO'];
                                        if(!empty($RDG_INFO)){
                                            foreach ($RDG_INFO as $nkey => $nvalue) {
                                                $RDG_NUM = $nvalue['RDG']['@attributes']['RDG_NUM'];
                                                $FP_INFO = $nvalue['FP_INFO'];
                                                if(!empty($FP_INFO)){
                                                    foreach ($FP_INFO as $fkey => $fvalue) {
                                                        $FP_NUM = $fvalue['FP']['@attributes']['FP_NUM'];
                                                        if(isset($fvalue['NOZ'])){
                                                            $NOZ = $fvalue['NOZ'];
                                                            if(!empty($NOZ)){
                                                                foreach ($NOZ as $fnkey => $fnvalue) {
                                                                    $NOZ_DATA = $fnvalue['@attributes'];
                                                                    $NOZ_DATA['RDG_NUM'] = $RDG_NUM;
                                                                    $NOZ_DATA['FP_NUM'] = $FP_NUM;
                                                                    $NOZ_DATA['csr_id'] = $csr_id;
                                                                    //print_r($NOZ_DATA);
                                                                    $this->save_data('CsrNozzle',$NOZ_DATA);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $this->write_log( $dir, $filename, "done.");
                                    if($action == "move"){
                                        $this->move_file($dir,$filename);
                                    }
                                    if($action == "delete"){
                                        $this->delete_file($dir,$filename);
                                    }
                                    return;
                                }else{
                                    $ss->active = '0';
                                    $ss->error = 'csr not added.';
                                    $ss->update();
                                    $this->write_log( $dir, $filename, "csr not added.");
                                    return;
                                }
                            }else{
                                if($action == "move"){
                                    $this->move_file($dir,$filename);
                                }
                                if($action == "delete"){
                                    $this->delete_file($dir,$filename);
                                }
                                $this->write_log( $dir, $filename, "Invalid xml format.");
                                return;
                            }
                        }
                        if($type == "trn"){
                            $TRN = $array['TRN']['@attributes'];
                            if(is_array($TRN)){
                                $TRN['RFID_CARD_USED'] = $array['RFID_CARD']['@attributes']['USED'];
                                unset($array['RFID_CARD']['@attributes']['USED']);
                                if(isset($array['RFID_CARD']['@attributes']['DISCOUNT_TYPE'])){
                                    $TRN['RFID_CARD_DISCOUNT_TYPE'] = $array['RFID_CARD']['@attributes']['DISCOUNT_TYPE'];
                                    unset($array['RFID_CARD']['@attributes']['DISCOUNT_TYPE']);
                                }
                                if(!empty($array['RFID_CARD']['@attributes'])){
                                    $trn_data = array_merge($TRN, $array['RFID_CARD']['@attributes']);
                                }else{
                                    $trn_data = $TRN;
                                }
                                if(isset($array['DISCOUNT']['@attributes'])){
                                    $trn_data1 = array_merge($trn_data, $array['DISCOUNT']['@attributes']);
                                }else{
                                    $trn_data1 = $trn_data;
                                }
                                $trn_data1['stations_id'] = $stations_id;
                                $trn_data1['scans_id'] = $scans_id;
                                $trn_data1['FDC_DATE_TIME'] =  $trn_data1['FDC_DATE']." ". $trn_data1['FDC_TIME'];
                                $trn_id = $this->save_data('Trn',$trn_data1);
                                if($trn_id > 0){
                                    $ss->records = $trn_id;
                                    $ss->update();
                                    $VOL = $trn_data1['VOL'];
                                    $FDC_PROD_NAME = $trn_data1['FDC_PROD_NAME'];
                                    if($trn_data1['RFID_CARD_USED'] == "1"){
                                        $NUM = $trn_data1['NUM'];
                                        $user = User::where('card_number', '=', $NUM)->select('id','card_number','name','last_name')->first();
                                        if($user){
                                            $user_id = $user->id;
                                            $sl =  StationsLoyalty::firstOrCreate(['stations_id' => $stations_id, 'fuel' => $FDC_PROD_NAME]);
                                            if($sl){
                                                $per_ltr = $sl->per_ltr;
                                                if(!empty($per_ltr)){
                                                    $lp = $VOL * $per_ltr;
                                                    if($lp > 0){
                                                        $UsersLoyalty = UsersLoyalty::firstOrCreate(['stations_id' => $stations_id,'user_id' => $user_id]);
                                                        if ($UsersLoyalty) {
                                                            $loyalty_points = $UsersLoyalty->loyalty_points;
                                                            if(empty($loyalty_points)){
                                                                $loyalty_points = $lp;
                                                            }else{
                                                                $loyalty_points = $loyalty_points + $lp;
                                                            }
                                                            $UsersLoyalty->loyalty_points = $loyalty_points;
                                                            if ($UsersLoyalty->update()) {
                                                                $alp = new AutoLoyaltyPoint();
                                                                $alp->trn_id = $trn_id;
                                                                $alp->user_id = $user_id;
                                                                $alp->vol = $VOL;
                                                                $alp->per_ltr = $per_ltr;
                                                                $alp->points = $lp;
                                                                $alp->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }else{
                                                $sl =  StationsLoyalty::firstOrCreate(['stations_id' => $stations_id, 'fuel' => $FDC_PROD_NAME]);
                                            }
                                        }else{
                                            $sl =  StationsLoyalty::firstOrCreate(['stations_id' => $stations_id, 'fuel' => $FDC_PROD_NAME]);
                                        }
                                    }else{
                                        $sl =  StationsLoyalty::firstOrCreate(['stations_id' => $stations_id, 'fuel' => $FDC_PROD_NAME]);
                                    }
                                    $this->write_log( $dir,  $filename, "done.");
                                    if($action == "move"){
                                        $this->move_file($dir,$filename);
                                    }
                                    if($action == "delete"){
                                        $this->delete_file($dir,$filename);
                                    }
                                }else{
                                    $ss->active = '0';
                                    $ss->error = 'trn not added.';
                                    $ss->update();
                                    $this->write_log( $dir, $filename, "trn not added.");
                                }
                            }else{
                                if($action == "move"){
                                    $this->move_file($dir,$filename);
                                }
                                if($action == "delete"){
                                    $this->delete_file($dir,$filename);
                                }
                                $this->write_log( $dir, $filename, "Invalid xml format.");
                                return;
                            }
                        }
                    }else{
                        $this->write_log( $dir,  $filename, "scan record not created.");
                    }
                }
            }
        }
    }

    function save_data($model,$adata){
        $mod_name = '\\App\\' . $model;
        $mod_data = new $mod_name;
        if(!empty($adata)){
            if($model=="Trn"){
                if(isset($adata["AMO_DISCOUNT"])){
                    if($adata["AMO_DISCOUNT"] > 0){
                        $adata["AMO_DISCOUNT"] ="-".$adata["AMO_DISCOUNT"];
                    }
                }
            }
            foreach ($adata as $key => $value) {
                $mod_data->$key = $value;
            }
        }
        if ($mod_data->save()) {
            if($model=="Trn"){
                $product=$mod_data->FDC_PROD_NAME;
                $stations_id=$mod_data->stations_id;
                $check_if_product_exist=Tanks::where([['product_name','=',$product],['station_id','=',$stations_id]])->get();
                if($check_if_product_exist->count() > 0){
                    if($check_if_product_exist->count()==1){
                        $tank_trn_id=$this->save_tank_trn($mod_data,@$check_if_product_exist[0]['id']);
                    }else{
                        $check_all_filters=Tanks::where([['product_name','=',$product],['station_id','=',$stations_id],['RDG_ID','=',$mod_data->RDG_ID],['FP','=',$mod_data->FP],['NOZ','=',$mod_data->NOZ]])->get(); 
                        if($check_all_filters->count()==1){
                            $tank_trn_id=$this->save_tank_trn($mod_data,@$check_all_filters[0]['id']);
                        }
                    }
                }
            }
            return $mod_data->id;
        }else{
            return '0';
        }
    }

    function move_file( $dir, $filename ){
        $scandir =  $this->scandir;
        $destination_path = $scandir.'_done';
        $destination_path = $destination_path.DIRECTORY_SEPARATOR.$dir;
        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0777, true);
        }
        $source_file = $scandir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$filename;
        if( rename($source_file, $destination_path .DIRECTORY_SEPARATOR. pathinfo($source_file, PATHINFO_BASENAME)) ){
            $this->write_log( $dir, $filename, "file moved successfully.");
        }
    }

    function delete_file( $dir, $filename ){
        $scandir =  $this->scandir;
        $source_file = $scandir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$filename;
        if (file_exists($source_file)) {
            unlink($source_file);
            $this->write_log( $dir, $filename, "file deleted successfully.");
        }
    }

    function write_log( $dir, $filename, $status ){
        $contents = "Time : ".date("Y-m-d h:i:s a",time())." // File Name : ".$filename." // Status : ".$status."\n";
        $scandir =  $this->scandir;
        $scanstationdir = $scandir.DIRECTORY_SEPARATOR.$dir;
        if (!file_exists($scanstationdir)) {
            mkdir($scanstationdir, 0777, true);
        }
        $logstationdir = $scandir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR."logs";
        if (!file_exists($logstationdir)) {
            mkdir($logstationdir, 0777, true);
        }
        $logfile = $logstationdir.DIRECTORY_SEPARATOR.'log.txt';
        if(!is_file($logfile)){
            $handle = fopen($logfile, 'w');
            fwrite($handle, $contents);
            fclose($handle);
        }else{
            $handle = fopen($logfile, 'a');
            fwrite($handle, $contents);
            fclose($handle);
            $bytes = filesize($logfile);
            if($bytes >= 1048576){
                $mb =  number_format($bytes / 1048576, 0);
                if($mb > 5){
                    $logfile1 = $logstationdir.DIRECTORY_SEPARATOR.'log_'.date('Y-m-d-H-i-s',time()).'.txt';
                    rename($logfile, $logfile1);
                }
            }
        }
    }

    public function save_tank_trn($mod_data,$tank_id){
        if($mod_data){
            $filled_capacity=''; $update_filled_capacity='';
            if(!empty($tank_id)){
                $tank_d=Tanks::where('id',$tank_id)->first();
                if(!empty($tank_d)){
                    $filled_capacity=$tank_d->filled_capacity;
                    if($tank_d->filled_capacity >= $mod_data->VOL){
                        $update_filled_capacity=$tank_d->filled_capacity - $mod_data->VOL;
                        $update_tank=Tanks::where('id',$tank_id)->update(['filled_capacity'=>$update_filled_capacity]); 
                        // update  tank trn detais   
                        $update_trn=Trn::where('id','=',$mod_data->id)->update([ 'tank_id' => $tank_id,'Pump_Start_Volume_Total'=> $filled_capacity,'Pump_End_Volume_Total' => $update_filled_capacity
                        ]); 
                        $update_trn;               
                        return $update_trn;
                    }
                }
            }
        }
        return 0;
    }

}
