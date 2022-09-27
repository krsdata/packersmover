<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CsrProduct;
use App\CsrTank;
use App\CsrNozzle;
use App\Csr;
use App\Role;
use App\StationsScan;
use App\Stations;
use App\Trn;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Config;
use App\StationsLoyalty;
use Illuminate\Support\Facades\Redis;



class DashboardController extends Controller
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
        //$l_img='';
        $user = Auth::user();
        $s_name=$request->get('search_input');
        $search_input = "";
 
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager') || $user->hasRole('company')){
            return view('pages/admin/dashboard',compact('singledate','search_input','data','stations','role_name','stran','wtpd','slpd','currency_code','decimal_point','aproducts','slpdkey','slpdlable','slpdcolor','prodtla'));
        }
        if($user->hasRole('user') ){
            return view('pages/admin/dashboard',compact('singledate','search_input','data','stations','role_name','stran','wtpd','slpd','currency_code','decimal_point','aproducts','slpdkey','slpdlable','slpdcolor','prodtla'));
        }
        return view('home');
    }
}
