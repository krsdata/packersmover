<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;;
use App\Draw;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use File;
use Helper;
use Input, Redirect, Session, Response, DB;
class DrawController extends Controller
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
        
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 10;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";        
        $draw=$request->get('draw');
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }

        //if($user->hasRole('lotto-manager')){
            $draw = Draw::orderBy('created_at', 'desc')->where('created_by',$user_id);
            $draw = $draw->paginate($perPage);            
         
            $s_count = $draw->count();
            return view('pages/admin/draw/index',compact('draw','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            //return view('pages.admin.customer.index',compact('search_input','customers','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
       // return view('home');   
    } 

    public function draw_create(Request $request){
        $user = Auth::user();
        if($user->hasRole('lotto-manager')){
                $Draws = Draw::where("status",'1')->orderBy('draw_name', 'asc')->where('created_by',$user->id)->get();
                               
            } 
        
        return view('pages/admin/draw/create',compact('Draws'));
    }

    public function draw_store(Request $request) {
        $user = Auth::user();
        $validator_draw_info = $this->validator_draw_info_update($request->all());
        if ($validator_draw_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_draw_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           
           
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Draw::findorfail($id);
                $obj_data->draw_name = $data['draw_name'];
                $obj_data->text = $data['text'];
                $obj_data->type = $data['type'];
                $obj_data->prize = $data['prize'];
                $obj_data->entry_fee = $data['entry_fee'];
                $obj_data->status = $data['status'];
                $obj_data->image = $data['image_val'];
                
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'draw has been updated Sucessfully!','redirect_url'=>'/admin/draw-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'draw Updation failed!','redirect_url'=>'/admin/draw-list']);
                }
            }else{

                $obj = new Draw();                
                $obj->draw_name = $data['draw_name'];
                $obj->text = $data['text'];
                $obj->type = $data['type'];
                $obj->prize = $data['prize'];
                $obj->entry_fee = $data['entry_fee'];
                $obj->image = $data['image_val'];
                $obj->status = $data['status'];                 
                $obj->created_by = $user->id;              
                if ($obj->save()) {
                    //$id = $obj->id;die;    
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'draw has been added Sucessfully!','redirect_url'=>'/admin/draw-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'draw Insertion failed!','redirect_url'=>'/admin/draw-list']);
                }
            }
        }
    }

    protected function validator_draw_info_update(array $data) {
        $cuser = Auth::user();
      
            $au['draw_name'] = 'required';
            $au['prize'] = 'required';
            $au['entry_fee'] = 'required';
            $au['type'] = 'required';
        
        return Validator::make($data, $au);
    } 

    public function draw_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $draw = Draw::findorfail($id);         
        return view('pages/admin/draw/create',compact('draw'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/draw';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    // Search bar product

    public function search_data_draw(request $request)
    {
        $user = Auth::user();
        $draw_name = $request['draw_name'];
        $draw = Draw::where('draw_name','LIKE','%'.$draw_name.'%')->where('created_by',$user->id)->get();
        $total_row = $draw->count();
        $output='';
            if($total_row > 0)
            {
                foreach($draw as $dkey => $dval)
                {                      
                    $decid = en_de_crypt($dval->id,"e");
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$dval->id.'</td>'.
                    '<td class="text-capitalize">'.$dval->draw_name.'</td>'.
                    '<td class="text-capitalize">'.$dval->text.'</td>'.
                    '<td class="text-capitalize">'.$dval->prize.'</td>'.
                    '<td class="text-capitalize">'.$dval->entry_fee.'</td>'.
                    '<td class="text-capitalize">'.$dval->type.'</td>'.                    
                    '<td class="actions" data-th="">'.'<a href='.url('admin/draw/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Draw Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }

}

     
   

