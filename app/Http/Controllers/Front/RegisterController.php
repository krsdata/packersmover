<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\User;
use App\Countries;
use App\Template;
use App\Role;
use Hash;
use Carbon\Carbon;
use Input, Redirect, Session, Response, DB;

class RegisterController extends Controller {

    public function __construct() {
    }

    public function registers() {
        $user=Auth::user();
        $country = Countries::whereIn('id',array('227','230'))->get();   
        return view('front.home.registers',compact('user','country'));
    }
  
    protected function validator_user_info(array $data) {
        
        $au = [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'contact' => 'required',
        ];

    }

    public function store(Request $request) {
      if (!empty($request->all())) {
                if($request['email']){
                   $user_count =User::where('email' ,$request['email'])->count();  
                    if($user_count==0){
                        if($request['password'] ==  $request['password_confirmation'] ){
                            $user=new User;
                            $user->name=$request['name'];
                            $user->last_name=$request['last_name'];
                            $user->contact=$request['contact'];
                            $user->email=$request['email'];
                            $user->gender=$request['gender'];
                            $user->country=$request['country'];
                            $user->state=$request['state'];
                            $user->city=$request['city'];
                            $user->password=Hash::make($request['password']);
                            $user->type='user';
                            if($user->save())
                           {
                                if(Auth::attempt($user)){
                                    return response()->json(['success' => TRUE, 'msg_type' => 'success', 'msg' => 'Registered Successfully', 'redirect_url' => '/']);
                                }else{
                                    return response()->json(['success' => FALSE, 'msg_type' => 'error', 'msg' => 'Failed!']);
                                }
                            }  
                            
                        }else{
                            return response()->json(['success' => FALSE, 'msg_type' => 'error', 'msg' => 'Password and Confirm passwqord must be same!']);
                        }
                    }else{
                        return response()->json(['success' => FALSE, 'msg_type' => 'error', 'msg' => 'Email id already exist!']);
                    }
                }
            }else{
                return response()->json(['success' => FALSE, 'msg_type' => 'error', 'msg' => 'Please fill all the details!']);
            }
    }

}
