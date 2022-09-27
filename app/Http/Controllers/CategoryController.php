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
use App\Tanks;
use App\Category;
use App\TankStock;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class CategoryController extends Controller
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

        if($request->id)
        {
            $id = en_de_crypt($request->id, 'd');
            $delete_data = Category::findorfail($id);
            $delete_data->delete();
            return redirect::to('admin/category-list');
        }

        $category=$request->get('category');

        $category = Category::orderBy('created_at', 'desc')->where('created_by',$user_id);
        $category = $category->paginate($perPage);

       // dd($request->all());

        if(!empty($category))
        {
            $category->where('name','=',$category)->where('created_by',$user_id);
        }




        $s_count = $category->count();           
        return view('pages/admin/category/index',compact('category','dates','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
         
    } 


    public function category_create(Request $request){
        $user = Auth::user();   
        $Categorys = Category::where('parent_id','=','0')->orderBy('name', 'asc')->where('created_by',$user->id)->get();        
        //echo "<pre>"; print_r($Stations); die();
        return view('pages/admin/category/create',compact('Categorys'));
    }

    public function category_store(Request $request) {
        $user = Auth::user();   
        $validator_category_info = $this->validator_category_info_update($request->all());
        if ($validator_category_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_category_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           

            $data = $data;
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Category::findorfail($id);
                $obj_data->name = _sanitize_text_fields(@$data['name']);
                $obj_data->image = $data['image_val'];
                $obj_data->parent_id = _sanitize_text_fields($data['parent_id']);
               // $obj_data->active = $data['stock_status'];
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Category has been updated Sucessfully!','redirect_url'=>'/admin/category-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Category Updation failed!','redirect_url'=>'/admin/category-list']);
                }
            }else{

                $obj = new Category();
                $obj->name = @$data['name'];
                $obj->image = $data['image_val'];
                // $obj->status = $data['status'];
                $obj->parent_id = $data['parent_id'];
                $obj->flag_id = '';
                $obj->category_type = '';
                $obj->created_by = $user->id;
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Category has been added Sucessfully!','redirect_url'=>'/admin/category-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Category Insertion failed!','redirect_url'=>'/admin/category-list']);
                }
            }
        }
    }

    protected function validator_category_info_update(array $data) {
        $cuser = Auth::user();
       
            $au['name'] = 'required|unique:category,name';
        
        return Validator::make($data, $au);
    } 

    public function category_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $category = Category::findorfail($id);
        return view('pages/admin/category/create',compact('category'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/category';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    // Search category

    public function search_data_category(request $request)
    {
        $user = Auth::user();
        $name = $request['name'];
        $category = Category::where('name','LIKE','%'.$name.'%')->where('created_by',$user->id)->get();
        $total_row = $category->count();
        $output='';
            if($total_row > 0)
            {
                foreach($category as $ckey => $cval)
                {                    
                    $id = $cval->id;
                    $image = asset('public/images/category/'.$cval->image);
                    $decid = en_de_crypt($cval->id,"e");
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$cval->id.'</td>'.
                    '<td class="text-capitalize">'.$cval->name.'</td>'.
                    '<td class="text-capitalize">'.'<img src="'.$image.'">'.'</td>'.
                    '<td class="text-capitalize">'.$cval->created_at.'</td>'.                    
                    '<td class="actions" data-th="">'.'<a href='.url('admin/category/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Category Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }

}

     
   

