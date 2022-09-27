<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Category;
use App\Winner;
use App\Order_master;
use App\Role;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Config;



class PosDashboardController extends Controller
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
       
        //echo $now = date('Y-m-d' ,strtotime('now'));       
        $user = Auth::user();
        $customers = User::where('type','user')->get();
        $total_customers = $customers->count();
        $category = Category::get();     
        $total_category = $category->count();
        $total_sales = Order_master::sum('sub_total');
        
        $date = date("Y-m-d");
        $dates = strtotime($date);
        $custome_data = User::whereDate('created_at','=',$date)->get();
        
        return view('pages/admin/posdashboard',compact('total_customers','custome_data','total_category','total_sales'));

    }
}
