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
use App\Order;
use App\Customer;
use App\Order_Items;
use App\Tax;
use App\Zreports;
use App\Discount;
use App\StationLogo;
use App\Tanks;
use App\TankStock;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class ZreportsController extends Controller
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
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";        
        $order=$request->get('order');
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }

        
            $order = Order::orderBy('id', 'asc')->where('created_by',$user_id);
            $order = $order->paginate($perPage);
            $s_count = $order->count();
            
            return view('pages/admin/zreports/index',compact('order','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
       // return view('home');   
    } 

    public function product_create(Request $request){
        $user = Auth::user();
        if($user->hasRole('lotto-manager')){
                $Products = Product::where("status",'1')->orderBy('product_name', 'asc')->get();
                $category = Category::orderBy('name','asc')->where('parent_id','=','0')->get();
                $tax = Tax::orderBy('tax_name','asc')->get();
                $discount = Discount::orderBy('name','asc')->get();
            } 
            // echo json_encode($category);
            // die;
        return view('pages/admin/product/create',compact('Products','category','tax','discount'));
    }

    public function order_Items(request $request)
    {
        $user = Auth::user();
        $id = en_de_crypt($request['id'], 'd');        
        if($id)
        {
            $order = Order::where('id','=',$id)->get();
            $order_items = Order_Items::where('order_id','=',$id)->get();
            return response()->json(["success"=>"True","msg" => "","data"=>$order,"items"=>$order_items]);
            //return response()->json(["success"=>"True","msg" => "List","order"=>$order,"order_items"=>$order_items]);
        }

    }

    public function zreports_detail($id)
    {
        $id = en_de_crypt($id, 'd');        
        if($id)
        {
            $order = Order::where('id','=',$id)->get();
            foreach($order as $ord => $oval)
            {
                $usersid = $oval['user_id'];
                @$customer = Customer::where('id','=',$usersid)->get();
                $order[$ord]['user_name'] = @$customer[0]['name'];
                $order[$ord]['user_email'] = @$customer[0]['email'];
                $order[$ord]['user_mobile'] = @$customer[0]['mobile_no'];
            }            
            $order_items = Order_Items::where('order_id','=',$id)->get();           
            //echo json_encode($order_items);
            //die;
            return view('pages/admin/zreports/detail_view',compact('order','order_items'));    
        }
        
    }

}

     
   

