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
use App\Tanks;
use App\Trn;
use App\TankTrn;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class TankController extends Controller
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
        $t_vol=0;
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 12;
        $station_selected=$request->get('station');
        $product_name=$request->get('PROD_NAMES');
        

        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $tank_arrray=Tanks::orderBy('created_at', 'desc');
            if($user->hasRole('admin')){
                $station = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
               $station = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();    
            }

            if(!empty($station_selected)){
                    $tank_arrray=Tanks::whereIn('station_id',$station_selected);
            }

            if(!empty($product_name)){
                   $tank_arrray=Tanks::where('product_name','=',$product_name);  
            }

            // if(!empty($station_selected)){
            //     $t_data=Trn::whereIn('stations_id',$station_selected)->where([['tank_id','=',0]])->get(['FDC_PROD_NAME','VOL','stations_id']);
                
            // }else if(!empty($product_name)){
            //    $t_data=Trn::whereIn('stations_id',$station_selected)->where([['tank_id','=',0],['FDC_PROD_NAME','=',$product_name]])->get(['FDC_PROD_NAME','VOL','stations_id']);
                
            // }else{
            //     $t_data=Trn::where('tank_id','=','0')->get(['FDC_PROD_NAME','VOL','stations_id']);
                
            // }

            // $tank_arrray = $tank_arrray->paginate($perPage)->appends(request()->query());
            // return view('pages/admin/tank/index',compact('product_name','station','tank_arrray','station_selected','product_name','t_data','t_vol'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            $tank_arrray = $tank_arrray->paginate($perPage)->appends(request()->query());
            return view('pages/admin/tank/index',compact('product_name','station','tank_arrray','station_selected','product_name'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        return view('home');   
    }

    public function tank_create()
    {
        $user = Auth::user();
        if($user->hasRole('admin')){
                $Stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
                   $Stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();  
        }
        //echo "<pre>"; print_r($Stations); die();
        return view('pages/admin/tank/create',compact('Stations'));
    }

    public function tank_update($id)
    {
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $tank_data = Tanks::findorfail($id);
        $tanks = Tanks::where("active",'1') ->orderBy('tank_name', 'asc')->get();
        $Stations = Stations::where("active",'1') ->orderBy('id', 'asc')->get();
        $station_ids=array("0"=>$tank_data->station_id);
        return view('pages/admin/tank/create',compact('tank_data','tanks','Stations','station_ids'));
    }

    public function tank_store(Request $request) {
        $validator_tank_info = $this->validator_tank_info_update($request->all());
        if ($validator_tank_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_tank_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();
            if($request['Station_Id']){
                $data['Station_Id']=@$request['Station_Id'][0];
            }
            $data=strip_tag_function($data);
            
            $if_exist=Tanks::where([['station_id','=',$data['Station_Id']],['product_name','=',$data['product_name']],['tank_color','=',$data['custom_color']]])->count();
            if (!empty($_POST['id'])) {

                if($if_exist > 1){
                   return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Product exist with same color','redirect_url'=>'/admin/tank']);
                }

                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Tanks::findorfail($id);
                $obj_data->station_id = @$data['Station_Id'];
                $obj_data->tank_name = $data['tank_name'];
                $obj_data->product_name = $data['product_name'];
                if(!empty($data['custom_color'])){
                   $obj_data->tank_color = $data['custom_color'];  
                }else{
                   $obj_data->tank_color = "#FFFFFF";  
                }
                
                if(!empty($data['RDG_ID'])){
                   $obj_data->RDG_ID = $data['RDG_ID'];
                }
                if(!empty($data['Fp'])){
                    $obj_data->Fp = $data['Fp']; 
                }
                if(!empty($data['NOZ'])){
                   $obj_data->NOZ = $data['NOZ'];  
                }
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Tank has been updated Sucessfully!','redirect_url'=>'/admin/tank-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Tank Updation failed!','redirect_url'=>'/admin/tank-list']);
                }
            }else{
                
                if($data['filled_capacity'] > $data['capacity']){
                   return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Filled Capacity should be equal or smaller than total capacity','redirect_url'=>'/admin/tank-list']);
                }

                if($if_exist > 0){
                   return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Product exist with same color','redirect_url'=>'/admin/tank-list']);
                } 

                $obj = new Tanks();
                $obj->station_id = @$data['Station_Id'];
                $obj->tank_name = $data['tank_name'];
                $obj->product_name = $data['product_name'];
                $obj->capacity = $data['capacity'];
                $obj->filled_capacity = $data['filled_capacity'];
                if(!empty($data['custom_color'])){
                   $obj->tank_color = $data['custom_color'];  
                }else{
                   $obj->tank_color = "#FFFFFF";  
                }
                if(!empty($data['RDG_ID'])){
                   $obj->RDG_ID = $data['RDG_ID'];
                }
                if(!empty($data['Fp'])){
                    $obj->Fp = $data['Fp']; 
                }
                if(!empty($data['NOZ'])){
                   $obj->NOZ = $data['NOZ'];  
                }
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Tank has been added Sucessfully!','redirect_url'=>'/admin/tank-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Tank Insertion failed!','redirect_url'=>'/admin/tank-list']);
                }
            }
        }
    }

    protected function validator_tank_info_update(array $data) {
        $cuser = Auth::user();
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $au['tank_name'] = 'required|unique:tank,tank_name,'.$id;
        }else{
            $au['tank_name'] = 'required|unique:tank';
            $au['product_name'] = 'required';
            $au['Station_Id'] = 'required';
            $au['filled_capacity'] = 'required';
            $au['capacity'] = 'required';
        }
        return Validator::make($data, $au);
    }

    // public function  tank_delete(Request $request){
    //     $id=$request['id']; 
    //     if($id){
    //        $id = en_de_crypt($id, 'd');
    //        $if_exist=Tanks::where('id',$id)->first();
    //         if(!$if_exist){
    //            return response()->json(["success"=>"False","msg" => "Tank  not found","data"=>""]);
    //         }else{
    //                $if_exist_in_log=Trn::where('tank_id',$id)->count();
    //                if($if_exist_in_log > 0){
    //                   return response()->json(["success"=>"False","msg" => "Please delete related Trn and try again","data"=>""]);  
    //                }else{
    //                       $if_exist->delete(); 
    //                       return response()->json(["success"=>"True","msg" => "Tank deleted successfully"]); 
    //                }
                   
    //         }
    //     }
    // } 
   
}
