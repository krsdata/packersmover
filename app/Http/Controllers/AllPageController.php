<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\User;
use App\Role;
use App\Tables;
use Carbon\Carbon;
use App\Feedback;
use App\Terms_Conditions;
use App\Privacy_Policy;
use App\Draw_Text;
use App\Faq;

use Input, Redirect, Session, Response, DB;


class AllPageController extends Controller {
    public function __construct() {
    }
    public function index() {
        $lang="";
        $langs=Language::where('name','=','english')->get();
        foreach($langs as $lag){
            $lang=$lag['id']; 
        }

        $datas = Pages::where([['base_lang','=',$lang],['lang','=',1]])->get();
        return view('pages/admin/pages/index',compact('datas'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
        return view('home');   
    }
    
    public function create(Request $request) {
        //print_r($request->all());die();
        $datas = "";
        $langs = Language::get();
        if (!empty($request['id'])) {
            $id = en_de_crypt($request['id'], "d");
            $datas = pages::findorfail($id);
        }
        return view('pages.allpage.app-allpage-add', ["datas" => $datas, "langs" => $langs]);
    }

    public function store(Request $request) {
       //echo "<pre>" ;print_r($request->all()); die(); 
       $lang=""; $user = Auth::user();
        $langs=Language::where('name','=','english')->get();
        foreach($langs as $lag){
            $lang=$lag['id']; 
        }

        
        if (!empty($request['id'])) {

            if(empty($request['l_id']) &&  !empty($request['id'])){
                $this->update($request,en_de_crypt($request['id'], "d"));
            }

            if(!empty($request['l_id']) &&  !empty($request['id'])){
                $if_exist = Pages::where([['lang','=',en_de_crypt($request['lang'], "d")],['id','=',en_de_crypt($request['l_id'], "d")]])->count();
                if($if_exist > 0){
                    $this->update($request,en_de_crypt($request['l_id'], "d"));  
                }else{
                    $m_obj = new Pages();
                    $m_obj->title = $request['title'];
                    $m_obj->slug = $request['slug'];
                    $m_obj->contents = $request['contents'];
                    $m_obj->org_id = $user->id;
                    $m_obj->base_lang = en_de_crypt($request['id'], "d");
                    $m_obj->lang =en_de_crypt($request['lang'], "d");
                    $m_obj->save();
                }
                
            }
            return redirect()->back()->withSuccess('Page updated Successfully');

            //return response()->json(['success' => TRUE, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Page updated Successfully', 'redirect_url' => 'app-allpage-list']); 

        }else{
                $validator_info = $this->validator_info($request->all());
                if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
                }

                if(!empty($lang)){
                    $m_obj = new Pages();
                    $new_lang = new Pages();
                    $m_obj->fill($request->all());
                    $m_obj->contents = $request['contents'];
                    $m_obj->org_id = $user->id;
                    $m_obj->base_lang = $lang;
                    $m_obj->save();

                    $newid = $m_obj->id;
                    $new_lang->contents = $request['contents'];
                    $new_lang->org_id = $user->id;
                    $new_lang->base_lang = $newid;
                    $new_lang->lang = '2';
                    $new_lang->title = $request['title'];
                    $new_lang->slug = $request['slug'];
                    $new_lang->save();


                }
                //die;
                return redirect()->back()->withSuccess('Page updated Successfully');

               // return response()->json(['success' => TRUE, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Page created Successfully', 'redirect_url' => 'app-allpage-list']);
            }
        }
    
    
    protected function validator_info(array $data) {
        $au = ['title' => 'required', 'slug' => 'required','contents' => 'required'];
        return Validator::make($data, $au);
    }

    public function update($data,$id){
        //print_r($data); die();
        $page=pages::findorfail($id);
        $page->title = $data['title'];
        $page->slug = $data['slug'];
        $page->contents = $data['contents'];
        $page->update();
    }

    public function lang_data(Request $request){
        if (!empty($request['lang_id'])) {
            $lang = en_de_crypt($request['lang_id'], "d");
            $base_lang = en_de_crypt($request['id'], "d");
            $lang_name = $request['lang_name'];
            if($lang_name == 'English'){
                $datas = pages::where([['id','=',$base_lang],['lang','=','1']])->get();  

                foreach($datas as $data){
                    $data['ids']=en_de_crypt($data['id'], "e");                    
                    $data['content']=strip_tags($data['content']);
                     $data['title'] = $data['title'];
                    $data['lang']=$data['lang'];
                    
                } 
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'','data'=>$datas]); 
            }
            $datas = pages::where([['base_lang','=',$base_lang],['lang','=',$lang]])->get();
            if($datas->isEmpty()){
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'No '.$lang_name.' content  available','datas'=>'']);
            }else{
                foreach($datas as $data){
                    $data['ids']=en_de_crypt($data['id'], "e");
                    $data['content']=strip_tags($data['contents']);
                    $data['title']=strip_tags($data['title']);
                }
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'','data'=>$datas]);
            }
        }
    }

}
