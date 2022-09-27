<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Privacy_Policy;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;


class PrivacyController extends Controller {
    public function __construct() {
    }
    public function index(Request $request) {
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 10;
        $privacy=$request->get('faq');        
        $privacy = Privacy_Policy::orderBy('created_at', 'desc');
        $privacy = $privacy->paginate($perPage);

        if(!empty($privacy))
        {
            $privacy->where('name','=',$privacy);
        }

        $s_count = $privacy->count();           
        if ($request->ajax()){
            $view = view("pages.admin.privacy.table_view",compact('privacy','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.privacy.index',compact('privacy','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        
        return view('home');  
    }
    
    public function privacy_create(Request $request){
        $user = Auth::user();        
        $Privacys = Privacy_Policy::orderBy('name', 'asc')->get();
        return view('pages/admin/privacy/create',compact('Privacys'));
    }

    public function privacy_store(Request $request) {
    	$user = Auth::user();
        $validator_info = $this->validator_info($request->all());
        if ($validator_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all(); 

            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');              
                $obj_data = Privacy_Policy::findorfail($id);
                $obj_data->name = @$data['name'];
                $obj_data->description = $data['description']; 
                $obj_data->status = $data['status'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'privacy has been updated Sucessfully!','redirect_url'=>'/admin/privacy-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'privacy Updation failed!','redirect_url'=>'/admin/privacy-list']);
                }
            }else{

                $obj = new Privacy_Policy();
                $obj->name = @$data['name'];
                $obj->description = $data['description'];
                $obj->status = $data['status'];
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'privacy has been added Sucessfully!','redirect_url'=>'/admin/privacy-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'privacy Insertion failed!','redirect_url'=>'/admin/privacy-list']);
                }
            }
        }
    }
    
    
    protected function validator_info(array $data) {
        $au = ['name' => 'required', 'description' => 'required'];
        return Validator::make($data, $au);
    }

    public function privacy_update($id){
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $privacy = Privacy_Policy::findorfail($id);
        return view('pages/admin/privacy/create',compact('privacy'));
    } 

}
