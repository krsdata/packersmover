<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Trn;
use App\Role;
use App\Stations;
use App\CompanyUsers;
use App\UsersLoyalty;
use App\StationsProduct;
use App\StationsLoyalty;
use App\ProductPrice;
use Mail;
use Input, Redirect, Session, Response, DB;
use App\Http\Controllers\CronController;
class PriceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->scandir = config('app.default_xml_path');
    }


    public function stations_price(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){


            $s_name=$request->get('search_input');
            //echo "<pre>";print_r($s_name); die();
            $stations_name = "";
            $search_input = "";
            $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            if($user->hasRole('owner')){
                $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
            }
            $role_name = "user";
            if($user->hasRole('admin') || $user->hasRole('owner')){
                if($s_name){
                    $search_input = $s_name;
                }else{
                    $search_input = $stations[0]['id'];
                }
                $role_name = "admin";
            }
            if($user->hasRole('manager')){
                $search_input = $user->stations_id;
            }
            $atrns = array();
            $atrn = Trn::where("stations_id",$search_input)->select('NUM')->groupBy('NUM')->get();
            if($atrn){
                foreach ($atrn as $tkey => $tvalue) {
                    $atrns[] = $tvalue->NUM;
                }
            }
            if(!empty($search_input)){
                foreach ($stations as $skey => $svalue) {
                    if($svalue['id'] == $search_input){
                        $stations_name = $svalue['title'];
                        $station_name =  $svalue['name'];
                        $decimal_point = $svalue['decimal_point'];
                    }
                }
            }
           //$products = StationsLoyalty::where('stations_id', '=', $search_input)->get();
            $products = array();
            if(count($products) == "0"){
                $products = array();
                $atrn = Trn::where("stations_id",$search_input)->select('FDC_PROD_NAME')->groupBy('FDC_PROD_NAME')->get();
                if($atrn){
                    foreach ($atrn as $tkey => $tvalue) {
                        $FDC_PROD_NAME = $tvalue->FDC_PROD_NAME;
                        $stationsl = StationsLoyalty::firstOrCreate(['stations_id' => $search_input, 'fuel' => $FDC_PROD_NAME]);
                        $fprice = Trn::where('stations_id', $search_input)->where('PRICE','!=','')->where('FDC_PROD_NAME', $FDC_PROD_NAME)->orderBy('FDC_DATE_TIME','DESC')->first();
                        if(isset($stationsl->id)){
                            $stationsl->eid = en_de_crypt($stationsl->id, 'e');
                            $stationsl->FDC_PROD = $fprice->FDC_PROD;
                            $stationsl->PRICE = $fprice->PRICE;
                            $products[] = $stationsl;
                        }
                    }
                }
            }else{
                foreach ($products as $pkey => $pvalue) {
                    if(isset($pvalue->id)){
                        $fprice = Trn::where('stations_id', $search_input)->where('PRICE','!=','')->where('FDC_PROD_NAME', $pvalue->fuel)->orderBy('FDC_DATE_TIME','DESC')->first();
                        $pvalue->eid = en_de_crypt($pvalue->id, 'e');
                        $pvalue->FDC_PROD = $fprice->FDC_PROD;
                        $pvalue->PRICE =  $fprice->PRICE;
                        $products[$pkey] = $pvalue;
                    }
                }
            }
            $productprices = array();
            $product_prices = ProductPrice::where("station_id",$search_input)->get();
            if($product_prices){
                foreach ($product_prices as $kdey => $vdalue) {
                    $prid = $vdalue->product_id;
                    $productprices[$prid] = number_format($vdalue->price,$decimal_point,'.',',');
                }
            }
            $destination_path = $this->scandir.DIRECTORY_SEPARATOR.$station_name.DIRECTORY_SEPARATOR.'price';
            $check_file = $destination_path. DIRECTORY_SEPARATOR.'data_encoded.txt';
            $check_file_url = en_de_crypt($check_file, 'e');
            $dataurl = route('stations_price_delete');
            $file_flag = '<span class="text-danger">Price change in progress<span> <button class="btn btn-danger delete-price-file float-right" data-file="'.$check_file_url.'" data-url="'.$dataurl.'" > Delete File </button>';
       
            if (!file_exists($check_file)) {
                $file_flag = '<span class="text-success">No price change activated<span>';
            }
            
            return view('pages.admin.price.fuel_per',compact('productprices','search_input','products','role_name','stations','stations_name','file_flag','user'));
        }
        return view('home');
    }

    public function stations_price_update(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $POST = $request->all();
        $updatepro = array();
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            if (!empty($POST['station_id']) ) {
                $valid = "0";
                $station_id = $POST['station_id'];
                $station_name = "";
                if($user->hasRole('admin')){
                    $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
                    foreach ($stations as $skey => $svalue) {
                        if($svalue['id'] == $station_id){
                            $valid = "1";
                            $station_name = $svalue['name'];
                        }
                    }
                }else{
                    $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
                    foreach ($stations as $skey => $svalue) {
                        if($svalue['id'] == $station_id){
                            $valid = "1";
                            $station_name = $svalue['name'];
                        }
                    }
                }
                if($valid == "1"){
                    $print = 0;
                    $str = "\r\n\r\n";
                    $str1 = $str2 = $str3 = $str4 = "";
                    $aa = array("DIESEL","UNLEADED","V_POWER","LPG","UNLEADED91","UNLEADED95");
                    foreach ($aa as $kaaey => $vaaalue) {
                        if(isset($POST[$vaaalue]) && !empty($POST[$vaaalue])){
                            $idv = "id_".$vaaalue;
                            if(isset($POST[$idv])){
                                $pidv = $POST[$idv];
                                if($pidv > 0){
                                    $updatepro[$pidv] = $POST[$vaaalue];
                                    $pidv = $pidv - 1;
                                }
                                switch ($pidv) {
                                    case '0':
                                        $str1 = "product.new_price= 0/".$POST[$vaaalue]."\r\n";
                                        break;
                                    case '1':
                                        $str2 = "product.new_price= 1/".$POST[$vaaalue]."\r\n";
                                        break;
                                    case '2':
                                        $str3 = "product.new_price= 2/".$POST[$vaaalue]."\r\n";
                                        break;
                                    case '3':
                                        $str4 = "product.new_price= 3/".$POST[$vaaalue]."\r\n";
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                $print = 1;
                            }
                           
                        }
                    }
                    // if(isset($POST['DIESEL']) && !empty($POST['DIESEL'])){
                    //     $str .= "product.new_price=0/".$POST['DIESEL']."\n";
                    //     $print = 1;
                    // }
                    // if(isset($POST['UNLEADED']) && !empty($POST['UNLEADED'])){
                    //     $str .= "product.new_price=1/".$POST['UNLEADED']."\n";
                    //     $print = 1;
                    // }
                    // if(isset($POST['V_POWER']) && !empty($POST['V_POWER'])){
                    //     $str .= "product.new_price=2/".$POST['V_POWER']."\n";
                    //     $print = 1;
                    // }
                    // if(isset($POST['LPG']) && !empty($POST['LPG'])){
                    //     $str .= "product.new_price=3/".$POST['LPG']."\n";
                    //     $print = 1;
                    // }
                    // if(isset($POST['UNLEADED91']) && !empty($POST['UNLEADED91'])){
                    //     $str .= "product.new_price=4/".$POST['UNLEADED91']."\n";
                    //     $print = 1;
                    // }
                    // if(isset($POST['UNLEADED95']) && !empty($POST['UNLEADED95'])){
                    //     $str .= "product.new_price=5/".$POST['UNLEADED95']."\n";
                    //     $print = 1;
                    // }
                    if(!empty($print)){
                        if(empty($str1)){
                            $str1 = "product.new_price= 0/1111\r\n";
                        }
                        if(empty($str2)){
                            $str2 = "product.new_price= 1/2222\r\n";
                        }
                        if(empty($str3)){
                            $str3 = "product.new_price= 2/3333\r\n";
                        }
                        if(empty($str4)){
                            $str4 = "product.new_price= 3/4444\r\n";
                        }
                        $str .= $str1.$str2.$str3.$str4;
                        if(isset($POST['ipc']) && !empty($POST['ipc'])){
                            $str .= "\r\nfdc.cmd_day_close_efd_all= 1\r\n";
                        }else{
                            $str .= "\r\nfdc.cmd_day_close_efd_all= 0\r\n";
                        }
                        $cryptodir = base_path().DIRECTORY_SEPARATOR.'crypto'.DIRECTORY_SEPARATOR;
                        $logfile = $cryptodir.$user_id.'.txt';
                        $handle = fopen($logfile, 'w');
                        fwrite($handle, $str);
                        fclose($handle);
                        putenv("LD_LIBRARY_PATH=/opt/glibc-2.14/lib");
                        $output = shell_exec('cd '.$cryptodir.' && ./cryptoende -e '.$user_id.'.txt');
                        if(strpos($output, "Encode successful")){
                            if($station_name){
                                if($this->scandir){
                                    $destination_path = $this->scandir.DIRECTORY_SEPARATOR.$station_name.DIRECTORY_SEPARATOR.'price';
                                    if (!file_exists($destination_path)) {
                                        mkdir($destination_path, 0777, true);
                                    }
                                    if(!empty($updatepro)){
                                        foreach ($updatepro as $keuy => $valuue) {
                                            $proprice = ProductPrice::firstOrCreate(['product_id' => $keuy,'station_id' => $station_id]);
                                            $proprice->price = $valuue;
                                            $proprice->save();
                                        }
                                    }
                                    
                                    
                                    rename($cryptodir. $user_id.'_encoded.txt', $destination_path.DIRECTORY_SEPARATOR."data_encoded.txt");
                                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Fuel price updated successfully!']);
                                }else{
                                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Please check station dir.']);
                                }

                            }else{
                                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Please check station status.']);
                            }
                        }else{
                            return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.'.$output]);
                        }
                    }else{
                        return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Please enter price!']);
                    }

                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
                }

            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
            }
        }else{
            return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
        }
    }


    public function stations_price_delete(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $POST = $request->all();
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            if (!empty($POST['df']) ) {
                $df = $POST['df'];
                $check_file_url = en_de_crypt($df, 'd');
                if (file_exists($check_file_url)) {
                    unlink($check_file_url);
                }

                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Fuel price deleted successfully!']);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
            }
        }else{
            return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
        }
    }
}
