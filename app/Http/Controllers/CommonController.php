<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;
use  App\Trn;
use  App\Tanks;
use  App\User;
use Input, Redirect, Session, Response, DB;
use Illuminate\Support\Facades\Auth;
class CommonController extends Controller
{

    public function delete(Request $request)
    {
        $model = $request['model'];
        $user = Auth::user();
        $valid = "0";
        if($user->hasRole('admin')){
            if($model == "CompanyUsers" || $model == "User" || $model == "Stations"){
                $valid = "1";
            }
        }

        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            if($model == "Tanks" || $model == "Expenses"){
                $valid = "1";
            }
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $id = en_de_crypt($request['id'], 'd');
        if ($model) {
            $mod_name = '\\App\\' . $model;
            $delete_flag = $mod_name::find($id);
            if ($delete_flag->delete()) {
                return response()->json(['success' => TRUE, 'op' => 'delete', 'msg_type' => 'success', 'msg' => $model . ' deleted Sucessfully!!']);
            } else {
                return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => $model . ' deletion Failed!!']);
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => $model . ' model not found!!']);
        }
    }

    public function listing(Request $request)
    {
        $user = Auth::user();
        $model = $request['model'];
        $valid = "0";
        if($user->hasRole('admin') || $user->hasRole('owner')){
            if($model == "CompanyUsers"){
                $valid = "1";
            }
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $page = $request['page'];
        $size = $request['size'];
        $select = $request['select'];
        $wkey = $request['wkey'];
        $wcomp = $request['wcomp'];
        $wval = $request['wval'];
        $offset = ($page - 1 ) * $size;
        if(!empty($select)){
            $aselect = explode(",",$select);
        }else{
            $aselect = ['id'];
        }
        if ($model) {
            $mod_name = '\\App\\' . $model;
            if($model == "CompanyUsers"){
                $data_obj = DB::table('company_users')
                ->join('users', 'users.id', '=', 'company_users.user_id')
                ->where('company_users.company_id', $wval)
                ->select('users.name', 'company_users.*', 'users.last_name', 'users.email', 'users.contact', 'users.card_number')
                ->offset($offset)
                ->limit($size)
                ->get();
            }else{
                $data_obj = $mod_name::orderBy('created_at', 'desc');
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
                $data_obj = call_user_func_array(array($data_obj, "select"), $aselect);
                $data_obj = $data_obj->paginate($size);
            }
           

            if ($data_obj) {
                foreach ($data_obj as $okey => $ovalue) {
                    $id = $ovalue->id;
                    $ovalue->enc_id = en_de_crypt($id, 'e');
                    $data_obj[$okey] = $ovalue;
                }
                return response()->json(['success' => TRUE, 'op' => 'listing', 'msg_type' => 'success', 'msg' => 'Record found Sucessfully!!', 'data' => $data_obj]);
            } else {
                return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'No record found!!']);
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Something went wrong!!']);
        }
    }

    public function iupdate(Request $request)
    {
        $model = $request['model'];
        $user = Auth::user();
        $valid = "0";
        if($user->hasRole('admin')){
            if($model == "User" ){
                $valid = "1";
            }
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $adata = $request['adata'];
        $id = $request['id'];
        if( $model && $adata ) {
            if (!empty($id)) {
                $id = en_de_crypt($id, 'd');
                $mod_name = '\\App\\' . $model;
                $mod_data = $mod_name::findorfail($id);
                if(!empty($adata)){
                    foreach ($adata as $key => $value) {
                        $value = $this->filter_post($key,$value);
                        $mod_data->$key = $value;
                    }
                }
                if ($mod_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Operation has been updated Sucessfully!','id' => $id, 'adata' => $adata]);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Operation Updation failed!']);
                }
            }else{
                $mod_name = '\\App\\' . $model;
                $mod_data = new $mod_name;
                if(!empty($adata)){
                    foreach ($adata as $key => $value) {
                        $value = $this->filter_post($key,$value);
                        $mod_data->$key = $value;
                    }
                }
                if ($mod_data->save()) {
                    $id = en_de_crypt($mod_data->id, 'e');
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Operation has been added Sucessfully!','id' => $id, 'adata' => $adata]);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Operation Insertion failed!']);
                }
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Something went wrong!!']);
        }
    }

    public function filter_post($key,$value){
        return $value;
    }

    public function uploadpdf(Request $request){
        $user = Auth::user();
        $data = $request['data'];
        $filename = $request['filename'];
        if( $data && $filename ) {
            $filepath = public_path('pdfiles').$filename;
            $file = fopen($filepath,'w');
            fwrite($file,$data);
            fclose($file);
            $url = url('pdfiles').$filename;
            return response()->json(['success' => TRUE, 'op' => 'upload', 'msg_type' => 'success', 'msg' => 'File uploaded successfully!!', 'url' => $url]);
        } else {
            return response()->json(['success' => FALSE, 'op' => 'upload', 'msg_type' => 'error', 'msg' => 'Something went wrong!!']);
        }
    }

    public function get_station_details(Request $request){
      if(!empty($request['station_id'])){
        $station_id=$request['station_id'];
        $productdata =Trn::whereIn('stations_id',$station_id)->select('FDC_PROD_NAME')
                ->groupBy('FDC_PROD_NAME')->get();
        $rdgdata = Trn::whereIn('stations_id', $station_id)->select('RDG_ID')->groupBy('RDG_ID')->get();
        $Fpdata=Trn::whereIn('stations_id', $station_id)->select('FP')->groupBy('FP')->get(); 
        $nozdata = Trn::whereIn('stations_id', $station_id)->select('NOZ')->groupBy('NOZ')->get();
        $tankdata = Tanks::whereIn('station_id', $station_id)->get(['id','tank_name']);
        if($tankdata){
            foreach ($tankdata as $key => $value) {
                $tankdata[$key]['eid']=en_de_crypt($value['id'], 'e');
             } 
        }
        return response()->json(["productdata"=>$productdata,"rdgdata" => $rdgdata,"Fpdata" => $Fpdata,"nozdata" => $nozdata,"tankdata"=>$tankdata]);    
      }else{
             return response()->json(["productdata"=>"","rdgdata" => "","Fpdata" => "","nozdata" => "","tankdata" => ""]);
      } 
    }

    public function get_tank_details(Request $request){
       if(!empty($request['product_name'])){
          $tankdata = Tanks::where('product_name', $request['product_name'])->get(['id','tank_name']);
            if($tankdata){
                foreach ($tankdata as $key => $value) {
                    $tankdata[$key]['eid']=en_de_crypt($value['id'], 'e');
                 } 
                 return response()->json(["tankdata"=>$tankdata]);
            }else{
                return response()->json(["tankdata"=>""]);
            }
       }   
    }

    public function mail_verify($email,$code)
    {

        $email = en_de_crypt($email,'d'); 
        $user = User::where('email','=',$email)->first();
        $msg = 'Invalid url please try to reverify email. Please login to below button';
        if($user){
            Auth::loginUsingId($user->id);
            if($user->mail_notify == $code){
                $user->email_verified_at = date("Y-m-d H:i:s",time());
                $user->mail_notify = "1";
                if($user->update()){
                    $msg = 'User Verfied Successfully';
                }
            }
        }
        return view('mail_verify',compact('msg','user'));
    }
}