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
use Mail;
use Input, Redirect, Session, Response, DB;
class LoyaltyController extends Controller
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
        $user = Auth::user();
        $user_id = $user->id;
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $perPage = 10;
            $s_name=$request->get('search_input');
            $name_search = $request->get('name_search');
            $search_input = "";
            $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            if($user->hasRole('owner')){
                $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
            }
            $role_name = "user";
            $users = User::orderBy('created_at', 'desc');
            if($name_search){
                $users =  $users->where('name', 'like', '%' . $name_search . '%');
            }
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
            $users->whereIn('card_number', $atrns);
            $users->where('type', '=', 'user');
            $users = $users->paginate($perPage);
            if($users){
                foreach ($users as $ukey => $uvalue) {
                    $users[$ukey]->loyalty_points = "0";
                    $lp = UsersLoyalty::where( "user_id", $uvalue->id )->select('loyalty_points')->where("stations_id", $search_input)->first();
                    if($lp){
                        $users[$ukey]->loyalty_points = $lp->loyalty_points;
                    }
                }
            }
            if ($request->ajax()){

                $view = view("pages.admin.loyalty.table_view",compact('search_input','users','user_id','role_name','stations','name_search'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{

                return view('pages.admin.loyalty.index',compact('search_input','users','user_id','role_name','stations','name_search'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home');
    }

    function user_loyalty_update(Request $request){
        $cuser = Auth::user();
        if (!empty($_POST['id']) && !empty($_POST['sid'])) {
            $sid = $_POST['sid'];
            $id = $_POST['id'];
            $user_id = en_de_crypt($id, 'd');
            $UsersLoyalty = UsersLoyalty::firstOrCreate(['stations_id' => $sid,'user_id' => $user_id]);
            if ($UsersLoyalty) {
                $UsersLoyalty->loyalty_points = $_POST['val'];
                if ($UsersLoyalty->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Loyalty point updated successfully!']);
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

    public function stations_loyalty(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $s_name=$request->get('search_input');
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
                    }
                }
            }
            $products = StationsLoyalty::where('stations_id', '=', $search_input)->get();
            if(count($products) == "0"){
                $products = array();
                $atrn = Trn::where("stations_id",$search_input)->select('FDC_PROD_NAME')->groupBy('FDC_PROD_NAME')->get();
                if($atrn){
                    foreach ($atrn as $tkey => $tvalue) {
                        $FDC_PROD_NAME = $tvalue->FDC_PROD_NAME;
                        $stationsl = StationsLoyalty::firstOrCreate(['stations_id' => $search_input, 'fuel' => $FDC_PROD_NAME]);
                        if(isset($stationsl->id)){
                            $stationsl->eid = en_de_crypt($stationsl->id, 'e');
                            $products[] = $stationsl;
                        }
                    }
                }
            }else{
                foreach ($products as $pkey => $pvalue) {
                    if(isset($pvalue->id)){
                        $pvalue->eid = en_de_crypt($pvalue->id, 'e');
                        $products[$pkey] = $pvalue;
                    }
                }
            }
            return view('pages.admin.loyalty.fuel_per',compact('search_input','products','role_name','stations','stations_name'));
        }
        return view('home');
    }

    public function stations_loyalty_update(Request $request){
        $user = Auth::user();
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            if (!empty($_POST['eid']) && isset($_POST['per_ltr'])) {
                $per_ltr = $_POST['per_ltr'];
                $eid = $_POST['eid'];
                $id = en_de_crypt($eid, 'd');
                $sl = StationsLoyalty::findorfail( $id);
                if ($sl) {
                    $sl->per_ltr = $per_ltr;
                    if ($sl->update()) {
                        return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Loyalty point updated successfully!']);
                    }else{
                        return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Something went wrong.... Please try again!']);
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
}
