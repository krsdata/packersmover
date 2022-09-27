<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Terms_Conditions;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;


class TermsController extends Controller {
    public function __construct() {
    }
    public function index(Request $request) {
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 10;
        $terms=$request->get('faq');        
        $terms = Terms_Conditions::orderBy('created_at', 'desc');
        $terms = $terms->paginate($perPage);

        if(!empty($terms))
        {
            $terms->where('name','=',$terms);
        }

        $s_count = $terms->count();           
        if ($request->ajax()){
            $view = view("pages.admin.terms.table_view",compact('terms','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.terms.index',compact('terms','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        
        return view('home');  
    }
    
    public function terms_create(Request $request){
        $user = Auth::user();        
        $Terms = Terms_Conditions::orderBy('name', 'asc')->get();
        return view('pages/admin/terms/create',compact('Terms'));
    }

    public function terms_store(Request $request) {
    	$user = Auth::user();
        $validator_info = $this->validator_info($request->all());
        if ($validator_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all(); 

            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');              
                $obj_data = Terms_Conditions::findorfail($id);
                $obj_data->name = @$data['name'];
                $obj_data->description = $data['description']; 
                $obj_data->status = $data['status'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'terms has been updated Sucessfully!','redirect_url'=>'/admin/terms-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'terms Updation failed!','redirect_url'=>'/admin/terms-list']);
                }
            }else{

                $obj = new Terms_Conditions();
                $obj->name = @$data['name'];
                $obj->description = $data['description'];
                $obj->status = $data['status'];
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'terms has been added Sucessfully!','redirect_url'=>'/admin/terms-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'terms Insertion failed!','redirect_url'=>'/admin/terms-list']);
                }
            }
        }
    }
    
    
    protected function validator_info(array $data) {
        $au = ['name' => 'required', 'description' => 'required'];
        return Validator::make($data, $au);
    }

    public function terms_update($id){
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $terms = Terms_Conditions::findorfail($id);
        return view('pages/admin/terms/create',compact('terms'));
    } 

}
