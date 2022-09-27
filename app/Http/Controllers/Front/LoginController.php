<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Schema;



class LoginController extends Controller
{
  
   public function logins() { 
        return view('front.home.logins');
    }
   
    public function frontlogin(Request $request){
        if(Auth::user()){
            //return view('pages.dashboard-ecommerce');
            return response()->json(['success' => TRUE,  'msg_type' => 'success', 'msg' => 'already ', 'redirect_url' => '/']);
        }
        
        if($request->all()){
            $user_data = array(
                'email'=>$request['email'],
                'password'=>$request['password']

            );

            if(Auth::attempt($user_data)){
               
                return redirect('/');

            }else{
                Redirect::to('/')->with('message', 'error');

            }
        }
    }

    public function frontlogout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }



    

    

    
}
