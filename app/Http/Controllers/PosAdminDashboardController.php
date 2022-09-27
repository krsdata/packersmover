<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CsrProduct;
use App\CsrTank;
use App\CsrNozzle;
use App\Csr;
use App\Order;
use App\Order_Items;
use App\Product;
use App\Customer;
use App\User;
use App\Role;
use App\StationsScan;
use App\Stations;
use App\Trn;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Config;
use App\StationsLoyalty;



class PosAdminDashboardController extends Controller
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
    public function index(Request $request)
    {
        
        $user = Auth::user();
        $total_sales = Order::sum('total_amount');
        $product = Product::where('status','1')->get();   
        $order = Order::get(); 
        $customers = Customer::get();
        $total_items = $product->count();       
        $total_orders = $order->count();       
        $total_customers = $customers->count();

        // Customers
        $date = date("Y-m-d");
        $dates = strtotime($date);
        $custome_data = Customer::whereDate('created_at','=',$date)->get();        
        $order_data = Order::whereDate('created_at','=',$date)->get();
        
        foreach($custome_data as $ckey => $cval)
        {
            $created_by = $cval['created_by'];
            $users_store = User::get();
            $custome_data[$ckey]['store'] = $users_store[0]['name'];
        }
          //echo ">>";die; 
        foreach($order_data as $okey =>$oval)
        {
            $user_id = $oval['user_id'];
            $created_by = $oval['created_by'];
            $users_store = User::where('id','=',$created_by)->get();
            $users = Customer::where('id','=',$user_id)->get();
            $order_data[$okey]['store'] = $users[0]['name'];
            $order_data[$okey]['name'] = $users[0]['name'];
            $order_data[$okey]['email'] = $users[0]['email'];
        }     

        return view('pages/admin/posadmindashboard',compact('total_sales','total_items','total_orders','total_customers','custome_data','order_data'));

    }
}
