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
use App\Product;
use App\Category;
use App\Tax;
use App\Discount;
use App\StationLogo;
use App\InventoryLogs;
use App\Tanks;
use App\TankStock;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class InventoryController extends Controller
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
        $product=$request->get('product');

        //if($user->hasRole('lotto-manager')){
            $product = Product::orderBy('created_at', 'asc')->where('created_by',$user_id);;
            $product = $product->paginate($perPage);
            foreach($product as $pkey => $pval)
            {
                $catid = $pval->category;
                $cat_name = Category::where('id','=',$catid)->get();
                $product[$pkey]['catname'] = $cat_name[0]->name;
            }

           // $product = $product->paginate($perPage);
            if(!empty($product)){
                $product->where('product_name','=',$product);
            }
    
            $s_count = $product->count();
           // $product = $product->paginate($perPage)->appends(request()->query());  

            return view('pages/admin/inventory/index',compact('product','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
          
        
       // return view('home');   
    } 

    public function inventory_create(Request $request){
        $user = Auth::user();
        if($user->hasRole('lotto-manager')){
                $Products = Product::where("status",'1')->orderBy('product_name', 'asc')->where('created_by',$user->id)->get();
                $category = Category::orderBy('name','asc')->where('created_by',$user->id)->get();
                $tax = Tax::orderBy('tax_name','asc')->where('created_by',$user->id)->get();
                $discount = Discount::orderBy('name','asc')->where('created_by',$user->id)->get();
            } 
            // echo json_encode($category);
            // die;
        return view('pages/admin/inventory/create',compact('Products','category','tax','discount'));
    }

    public function inventory_store(Request $request) {
        $user = Auth::user();
        $validator_product_info = $this->validator_product_info_update($request->all());
        if ($validator_product_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_product_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           

            $data = strip_tag_function($data);
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Product::findorfail($id);
                $obj_data->product_name = @$data['product_name'];
                $obj_data->description = $data['description'];
                $obj_data->category = $data['category'];
                $obj_data->price = $data['price'];
                $obj_data->sale_price = $data['sale_price'];
                $obj_data->quantity = $data['quantity'];
                $obj_data->image = $data['image_val'];
                $obj_data->tax = $data['tax'];
                $obj_data->discount = $data['discount'];
                $obj_data->status = $data['status'];
                
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Product has been updated Sucessfully!','redirect_url'=>'/admin/product-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Product Updation failed!','redirect_url'=>'/admin/product-list']);
                }
            }else{

                $obj = new Product();                
                $obj->product_name = $data['product_name'];
                $obj->description = $data['description'];
                $obj->category = $data['category'];
                $obj->price = $data['price'];
                $obj->sale_price = $data['sale_price'];
                $obj->image = $data['image_val'];
                $obj->status = $data['status']; 
                $obj->quantity = $data['quantity'];
                $obj->discount = $data['discount'];
                $obj->tax = $data['tax'];
                $obj->created_by = $user->id;               
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Product has been added Sucessfully!','redirect_url'=>'/admin/product-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Product Insertion failed!','redirect_url'=>'/admin/product-list']);
                }
            }
        }
    }

    protected function validator_product_info_update(array $data) {
        $cuser = Auth::user();
      
            $au['product_name'] = 'required';
            $au['price'] = 'required';
            $au['sale_price'] = 'required';
            $au['description'] = 'required';
        
        return Validator::make($data, $au);
    } 

    public function inventory_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $product = Product::findorfail($id); 
        $category = Category::orderBy('name','asc')->where('created_by',$user->id)->get();  
        $tax = Tax::orderBy('tax_name','asc')->where('created_by',$user->id)->get();
        $discount = Discount::orderBy('name','asc')->where('created_by',$user->id)->get();    
        return view('pages/admin/inventory/create',compact('product','category','tax','discount'));
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

    public function invoice_Details(request $request){
        //echo "<pre>"; print_r($request->all()); die();
        $id=$request['id']; 
        if($id){
           $id = en_de_crypt($id, 'd');
           $if_exist=Product::where('id',$id)->first();
            if(!$if_exist){
               return response()->json(["success"=>"False","msg" => "Stock not found","data"=>""]);
            }
                   return response()->json(["success"=>"True","msg" => "Stock details","data"=>$if_exist]); 
            
        }
    }

    public function update_Stock(request $request){
        //echo $id ; die();
        $user = Auth::user();
        $id =  $request['id']; 
        $quantity =  $request['quantity']; 
        if($id)
        {
            $id = en_de_crypt($id, 'd');
            $product = Product::findorfail($id);
            $product->quantity = @$quantity;   
            $product->update();

            $logs = new InventoryLogs();
            $logs->productId = $id;
            $logs->qty = @$quantity;   
            $logs->created_by = @$user->id;
            $logs->type = 'update';
            $logs->save();

            return response()->json(["success"=>"True","msg" => "Stock details","data"=>$product]);
       // return view('pages/admin/inventory/create',compact('product','category','tax','discount'));
        }else{
            return response()->json(["success"=>"False","msg" => "Stock not found","data"=>""]);
        }
        
    } 

    public function new_Stock(request $request)
    {
        $user = Auth::user();
        $id =  $request['id']; 
        $quantity =  $request['quantity']; 
        if($id)
        {
            $id = en_de_crypt($id, 'd');
            $product = Product::findorfail($id);
            $product->quantity = @$quantity;   
            $product->update();

            $logs = new InventoryLogs();
            $logs->productId = $id;
            $logs->qty = @$quantity;   
            $logs->created_by = @$user->id;
            $logs->type = 'new';
            $logs->save();

            return response()->json(["success"=>"True","msg" => "Stock details","data"=>$product]);
       // return view('pages/admin/inventory/create',compact('product','category','tax','discount'));
        }else{
            return response()->json(["success"=>"False","msg" => "Stock not found","data"=>""]);
        }
        
    } 

    // search inventory
    public function search_data_inventory(request $request)
    {
        $user = Auth::user();
        $product_name = $request['product_name'];
        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->where('created_by',$user->id)->get();
        $total_row = $product->count();
        $output='';
            if($total_row > 0)
            {
                foreach($product as $pkey => $pval)
                {
                    $decid = en_de_crypt($pval->id,"e");
                    $output.='<tr id="'.$decid.'">'.
                    '<td <button class="btn badge-primary btn-sm">'.$pval->id.'</td>'.
                    '<td class="text-capitalize">'.$pval->product_name.'</td>'.
                    '<td class="text-capitalize">'.$pval->quantity.'</td>'.                    
                    '<td class="actions" data-th="">'.'<a href="#" class="stock_details" data-id="'.$decid.'"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Item Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            } 
    }

}

     
   

