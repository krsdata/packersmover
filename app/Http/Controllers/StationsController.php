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
use App\StationLogo;
use Mail;
use File;
use App\Tanks;
use Input, Redirect, Session, Response, DB;
class StationsController extends Controller
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
        if($user->hasRole('admin')){

            if($user->hasRole('admin')){
                    $stations = Stations::where("active",'1')->orderBy('name', 'asc')->get();
            }else{
                   $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
            }
            $perPage = 10;
            $s_name=$request->get('search_input');
            $station=$request->get('station');
            $search_input = "";
            $obj = Stations::orderBy('created_at', 'desc');
            if($s_name){
                $obj =  $obj->where('title', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }

            if(!empty($station)){
                $obj =  $obj->whereIn('id',$station);
            }

            $obj = $obj->paginate($perPage);
            if ($request->ajax()){
                $view = view("pages.admin.station.table_view",compact('search_input','obj','user_id','station','stations'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{
                return view('pages.admin.station.index',compact('search_input','obj','user_id','station','stations'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home');
    }

    public function station_create()
    {
        $user = Auth::user();
        $stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        return view('pages/admin/station/create',compact('stations'));
    }

    public function station_update($id)
    {
        $logos="";
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $station_data = Stations::findorfail($id);
        if($id){
            $StationLogo=StationLogo::where('station_id',$id)->first();
            if($StationLogo){
               $logos=$StationLogo->name;
            }

        }
        $stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        return view('pages/admin/station/create',compact('station_data','stations','logos'));
    }

    protected function validator_station_info(array $data) {
        $cuser = Auth::user();
        $au = [
            'title' => 'required',
            'active' => 'required',
            'currency_code' => 'required'
        ];
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $au['name'] = 'required|unique:stations,name,'.$id;
            return Validator::make($data, $au, ['name.unique' => 'Folder name is already exists!!',]);
        } else {
            $au['name'] = 'required|max:255|unique:stations';
            return Validator::make($data, $au, ['name.unique' => 'Folder name is already exists!!',]);
        }
    }

    public function station_store(Request $request) {
        $validator_station_info = $this->validator_station_info($request->all());
        if ($validator_station_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_station_info->getMessageBag()->toArray()]);
        }else{
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Stations::findorfail($id);
                $obj_data->name = $request['name'];
                $obj_data->title = $request['title'];
                $obj_data->info = $request['info'];
                $obj_data->decimal_point = $request['decimal_point'];
                $obj_data->active = $request['active'];
                $obj_data->currency_code = $request['currency_code'];
                $obj_data->tin = $request['tin'];
                $obj_data->vrn = $request['vrn'];
                $obj_data->service_station = $request['service_station'];
                $obj_data->serial_number = $request['serial_number'];
                $obj_data->tel = $request['tel'];
                if ($obj_data->update()) {
                    if(!empty($request['station_imgval'])){
                        $StationLogo=StationLogo::where('station_id',$id)->first();
                        if(!empty($StationLogo)){
                            $StationLogo=StationLogo::where('station_id',$id)->update(["name"=>$request['station_imgval']]);
                        }else{
                                $StationLogo= new StationLogo;
                                $StationLogo->station_id=$id; 
                                $StationLogo->name=$request['station_imgval']; 
                                $StationLogo->save();
                        }
                    }
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Station has been updated Sucessfully!','redirect_url'=>'/admin/station-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Station Updation failed!','redirect_url'=>'/admin/station-list']);
                }
            }else{
                $obj = new Stations();
                $obj->name = $request['name'];
                $obj->title = $request['title'];
                $obj->info = $request['info'];
                $obj->decimal_point = $request['decimal_point'];
                $obj->active = $request['active'];
                $obj->currency_code = $request['currency_code'];
                $obj->tin = $request['tin'];
                $obj->vrn = $request['vrn'];
                $obj->service_station = $request['service_station'];
                $obj->serial_number = $request['serial_number'];
                $obj->tel = $request['tel'];
                if ($obj->save()) {
                    if(!empty($request['station_imgval'])){
                        $StationLogo= new StationLogo;
                        $StationLogo->station_id=$obj->id; 
                        $StationLogo->name=$request['station_imgval']; 
                        $StationLogo->save(); 
                    }
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Station has been added Sucessfully!','redirect_url'=>'/admin/station-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Station Insertion failed!','redirect_url'=>'/admin/station-list']);
                }
            }
        }
    }

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('station_image')) {
            $file = $request->file('station_image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/station_logo';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    public function  station_delete(Request $request){
        $id=$request['id']; 
        if($id){
           $id = en_de_crypt($id, 'd');
           $if_exist=Stations::where('id',$id)->first();
            if(!$if_exist){
               return response()->json(["success"=>"False","msg" => "Station not found","data"=>""]);
            }else{
                   $if_exist_in_tank=Tanks::where('station_id',$id)->count();
                   if($if_exist_in_tank > 0){
                      return response()->json(["success"=>"False","msg" => "Please delete related Station data and try again","data"=>""]);  
                   }else{
                          $if_exist->delete(); 
                          return response()->json(["success"=>"True","msg" => "Station deleted successfully"]); 
                   }
                   
            }
        }
    }
}
