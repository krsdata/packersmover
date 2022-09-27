<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        
        $user = Auth::User(); 
        
        if($user->type =='user')
        {
            return redirect('/user_profile');
        }
      
        if($user->hasRole('company')){
            
            $r_url = url('/login/');
            if($user->active == "1"){
                
                $company_details = Company::findorfail($user->company_id);
                if($company_details->status == "1"){
                    if (strtotime($company_details->expiry_date) < time()) {
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        return redirect()->intended($r_url)->with('failure', 'Your account has expired. Please contact your system administrator.');
                    }
                }else{
                   
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return redirect()->intended($r_url)->with('failure', 'Your company account has been inactive. Please contact your system administrator.');
                }
            }else{
                
                $this->guard()->logout();
                $request->session()->invalidate();
                return redirect()->intended($r_url)->with('failure', 'Your account has been inactive. Please contact your system administrator.');
            }
        }else{
                
        }
    }
}
