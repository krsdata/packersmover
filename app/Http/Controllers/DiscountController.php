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
use App\Tax;
use App\Category;
use App\Discount;
use App\TankStock;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class DiscountController extends Controller
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
        $perPage = 12;
        $discount=$request->get('discount');
        
            $discount = Discount::orderBy('created_at', 'desc')->where('created_by',$user_id);
            $discount = $discount->paginate($perPage);
            
            if(!empty($discount)){
                $discount->where('name','=',$discount);
            }
            $s_count = $discount->count();

            return view('pages/admin/discount/index',compact('discount','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
        return view('home');   
    } 

    public function discount_create(Request $request){
        $user = Auth::user();   
        $Discounts = Discount::orderBy('name', 'asc')->where('created_by',$user->id)->get();
        //echo "<pre>"; print_r($Stations); die();
        return view('pages/admin/discount/create',compact('Discounts'));
    }

    public function discount_store(Request $request) {
    	$user = Auth::user();
        $validator_stock_info = $this->validator_stock_info_update($request->all());
        if ($validator_stock_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_stock_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           

            $data = strip_tag_function($data);
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Discount::findorfail($id);
                $obj_data->name = _sanitize_text_fields(@$data['name']);
                $obj_data->discount_price = _sanitize_text_fields($data['discount_price']); 
                $obj_data->status = _sanitize_text_fields($data['status']);
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Discount has been updated Sucessfully!','redirect_url'=>'/admin/discount-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Discount Updation failed!','redirect_url'=>'/admin/discount-list']);
                }
            }else{

                $obj = new Discount();
                $obj->name = _sanitize_text_fields(@$data['name']);
                $obj->discount_price = _sanitize_text_fields($data['discount_price']);
                $obj->status = _sanitize_text_fields($data['status']);   
                $obj->created_by = $user->id;      
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Discount has been added Sucessfully!','redirect_url'=>'/admin/discount-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Discount Insertion failed!','redirect_url'=>'/admin/discount-list']);
                }
            }
        }
    }

    protected function validator_stock_info_update(array $data) {
        $cuser = Auth::user();
      
            $au['name'] = 'required|unique:discount,name';
            $au['discount_price'] = 'required';            
        
        return Validator::make($data, $au);
    } 

    public function discount_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $discount = Discount::findorfail($id);
        return view('pages/admin/discount/create',compact('discount'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/stock';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

     // Search category
     public function search_data_discount(request $request)
     {
         $user = Auth::user();
         $name = _sanitize_text_fields($request['name']);
         $discount = Discount::where('name','LIKE','%'.$name.'%')->where('created_by',$user->id)->get();
         $total_row = $discount->count();
         $output='';
             if($total_row > 0)
             {
                 foreach($discount as $dkey => $dval)
                 {                    
                     $id = $dval->id;
                     $decid = en_de_crypt($id,"e");
                     $output.='<tr>'.
                     '<td <button class="btn badge-primary btn-sm">'.$dval->id.'</td>'.
                     '<td class="text-capitalize">'.$dval->name.'</td>'.
                     '<td class="text-capitalize">'.$dval->discount_price.'</td>'.                 
                     '<td class="actions" data-th="">'.'<a href='.url('admin/discount/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
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

     
   

