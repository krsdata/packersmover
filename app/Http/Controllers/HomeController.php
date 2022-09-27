<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
    public function index()
    {        
        $user = Auth::user();    
        //echo $user->hasRole('admin');die;
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            return redirect()->route('dashboard');
        }
        if($user->hasRole('company') || $user->hasRole('user')){
            return redirect()->route('trn');
        }
        if($user->hasRole('accountant') || $user->hasRole('account-manager')){
          return redirect()->route('expense');
        }  
        if($user->hasRole('sai-manager')){
            return redirect()->route('posdashboard');
          }    
          if($user->hasRole('pos-admin')){             
            return redirect()->route('posadmindashboard');
          }                
        return view('home');
    }
}
