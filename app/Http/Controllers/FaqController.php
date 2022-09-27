<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Faq;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;


class FaqController extends Controller {
    public function __construct() {
    }
    public function index(Request $request) {
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 10;
        $faq=$request->get('faq');        
        $faq = Faq::orderBy('created_at', 'desc');
        $faq = $faq->paginate($perPage);

        if(!empty($faq))
        {
            $faq->where('title','=',$faq);
        }

        $s_count = $faq->count();           
        if ($request->ajax()){
            $view = view("pages.admin.faq.table_view",compact('faq','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.faq.index',compact('faq','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        
        return view('home');  
    }
    
    public function faq_create(Request $request){
        $user = Auth::user();        
        $Faqs = Faq::orderBy('title', 'asc')->get();
        return view('pages/admin/faq/create',compact('Faqs'));
    }

    public function faq_store(Request $request) {
    	$user = Auth::user();
        $validator_info = $this->validator_info($request->all());
        if ($validator_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all(); 

            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');              
                $obj_data = Faq::findorfail($id);
                $obj_data->title = @$data['title'];
                $obj_data->description = $data['description']; 
                $obj_data->status = $data['status'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Faq has been updated Sucessfully!','redirect_url'=>'/admin/faq-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Faq Updation failed!','redirect_url'=>'/admin/faq-list']);
                }
            }else{

                $obj = new Faq();
                $obj->title = @$data['title'];
                $obj->description = $data['description'];
                $obj->status = $data['status'];
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Faq has been added Sucessfully!','redirect_url'=>'/admin/faq-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Faq Insertion failed!','redirect_url'=>'/admin/faq-list']);
                }
            }
        }
    }
    
    
    protected function validator_info(array $data) {
        $au = ['title' => 'required', 'description' => 'required'];
        return Validator::make($data, $au);
    }

    public function faq_update($id){      
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $faq = Faq::findorfail($id);
        return view('pages/admin/faq/create',compact('faq'));
    } 

}
