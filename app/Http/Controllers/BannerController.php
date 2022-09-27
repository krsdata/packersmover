<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Banner;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class BannerController extends Controller
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
        $banner=$request->get('banner');

        $banner = Banner::orderBy('created_at', 'desc');
        $banner = $banner->paginate($perPage);

        if(!empty($banner))
        {
            $banner->where('name','=',$banner);
        }

        $s_count = $banner->count();           
        return view('pages/admin/banner/index',compact('banner','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
        return view('home');   
    } 

    public function banner_create(Request $request){
        $user = Auth::user();   
        $Banners = Banner::orderBy('name', 'asc')->get();        
        //echo "<pre>"; print_r($Stations); die();
        return view('pages/admin/banner/create',compact('Banners'));
    }

    public function banner_store(Request $request) {
        $user = Auth::user();   
        $validator_banner_info = $this->validator_banner_info_update($request->all());
        if ($validator_banner_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_banner_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           

            //$data = strip_tag_function($data);
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Banner::findorfail($id);
                $obj_data->name = $data['name'];
                $obj_data->image = $data['image_val'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Banner has been updated Sucessfully!','redirect_url'=>'/admin/banner-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Banner Updation failed!','redirect_url'=>'/admin/banner-list']);
                }
            }else{

                $obj = new Banner();
                $obj->name = $data['name'];
                $obj->image = $data['image_val'];
                $obj->status = $data['status'];
                $obj->created_by = $user->id;
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Banner has been added Sucessfully!','redirect_url'=>'/admin/banner-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Banner Insertion failed!','redirect_url'=>'/admin/banner-list']);
                }
            }
        }
    }

    protected function validator_banner_info_update(array $data) {
        $cuser = Auth::user();
       
            $au['name'] = 'required';           
        
        return Validator::make($data, $au);
    } 

    public function banner_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $banner = Banner::findorfail($id);
        return view('pages/admin/banner/create',compact('banner'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/banner';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    // Search banner

    public function search_data_banner(request $request)
    {
        $user = Auth::user();
        $name = $request['name'];
        $banner = Banner::where('name','LIKE','%'.$name.'%')->where('created_by',$user->id)->get();
        $total_row = $banner->count();
        $output='';
            if($total_row > 0)
            {
                foreach($banner as $ckey => $cval)
                {                    
                    $id = $cval->id;
                    $decid = en_de_crypt($cval->id,"e");
                    $image = asset('public/images/banner/'.$cval->image);
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$cval->id.'</td>'.
                    '<td class="text-capitalize">'.$cval->name.'</td>'.
                    '<td class="text-capitalize">'.'<img src="'.$image.'">'.'</td>'.
                    '<td class="text-capitalize">'.$cval->status.'</td>'.
                    '<td class="text-capitalize">'.$cval->created_at.'</td>'.    
                    '<td class="actions" data-th="">'.'<a href='.url('admin/banner/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No banner Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }

}