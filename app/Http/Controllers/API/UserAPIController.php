<?php

namespace App\Http\Controllers\API;
//use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Str;
use Mail;
use Helper;
use App\User;
use App\OauthAccessToken;
use App\Otp_Verify;
use App\Dating_users;
use Illuminate\Database\Eloquent\SoftDeletes;



class UserAPIController extends Controller {


    public $successStatus = 200;
    protected $request;
    use SoftDeletes;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->edata =  new \stdClass();
        $this->ApiF = new \App\library\ApiFunctions;
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login() {
        $device_id = request('device_id');
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();   
            $success['token'] = $user->createToken('VistorPoint')->accessToken;
            $success['name'] = $user->name;
            $success['id'] = $user->id;
            $success['img_url'] = url("/upload_image")."/".$user->profile_img;
            $success=json_encode($success);
            $success=json_decode($success);
            $success=$this->ApiF->esd($success,['id'],'object');            
            return response()->json(['success' =>true, 'msg' => trans('a.user_sign_in'),"data" => $success], $this->ApiF->okStatus);
        } else {
            return response()->json(['success' => false, 'error' => trans('a.Unauthorised'), 'msg' => trans('a.incorrect_email'),"data" =>$this->edata], $this->ApiF->authStatus);
        }
    }
    
    // social login api
    public function social_login(Request $request)
    {
        if($request)
        {
            $validator_info = $this->validator_info($request->all(),$au = ['email' => '', 'username' => 'required','contact' => '','social_type' => 'required']);
            if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            }

            if(!empty($request['username']))
            {
                $if_exist = user::where('username',$request['username'])->orwhere('social_type',$request['social_type'])->get();
                //echo json_encode($if_exist);die;
                if(!isset($if_exist[0]['username']) == $request['username'])
                {                    
                    $users= new User();
                    $users->name=$request['name'];
                    $users->email=$request['email'];
                    $users->username=$request['username'];
                    $users->social_type=$request['social_type'];
                    $users->contact=$request['contact'];
                    $users->type='user';
                    $users->active='1';
                  
                    $users->save();
                    return response()->json(['success' =>true, 'msg' => 'User registered successfully', "data" => $users], $this->ApiF->okStatus);                   

                }else{
                    $user = $if_exist[0];
                   // echo json_encode($user);die;
                    //$user = Auth::user();
                    $success['token'] = $user->createToken('VistorPoint')->accessToken;
                    $success['name'] = $user->name;
                    $success['id'] = $user->id;
                    $success=json_encode($success);
                    $success=json_decode($success);
                    $success=$this->ApiF->esd($success,['id'],'object');
                    return response()->json(['success' =>true, 'msg' => trans('a.user_sign_in'),"data" => $success], $this->ApiF->okStatus);
                }
            }
        }
    }

    public function store(request $request) {
        if($request){
             if(!empty($request['email']) && !empty($request['contact'])){
                $if_exist = user::where('email',$request['email'])->get();
                if(!isset($if_exist[0]['email'])){
                    $users= new User();
                    $users->name=$request['name'];
                    $users->last_name=$request['last_name'];
                    $users->gender=$request['gender'];
                    $users->country=$request['country'];
                    $users->state=$request['state'];
                    $users->city=$request['city'];
                    $users->email=$request['email'];
                    $users->contact=$request['contact'];
                    $users->type='user';
                    $users->active='1';
                    $users->password=bcrypt($request['password']);
                    $otp = $request['otp'];
                    // if($otp)
                    // {
                    //     $verify_otp = Otp_Verify::where('contact',$request['contact'])->get();
                    //     $dbotp = $verify_otp[0]['otp'];    
                    // }
                    // if($dbotp == $otp)
                    // {
                    //     return response()->json(["success"=>"True","msg" => "otp match successfully","data"=>$verify_otp]);
                        
                    // }else{
                    //     return response()->json(["success"=>"false","msg" => "Otp not match"]);
                    // }                    
                    $users->save();                 
                    return response()->json(['success' =>true, 'msg' => 'User registered successfully', "data" => $users], $this->ApiF->okStatus);
                }else{
                    return response()->json(['success' =>true, 'msg' => 'User is already registered ', "data" => ""], $this->ApiF->okStatus);         
                }
             }else{
                return response()->json(['success' =>true, 'msg' => 'Please fill all the details', "data" => ""], $this->ApiF->okStatus);
             }
        }else{ 
            return response()->json(['success' =>true, 'msg' => 'Please fill all the details', "data" => ""], $this->ApiF->okStatus);
        }
        
    }

    public function send_otp(request $request)
    {
        $user = Auth::user();        
        if($request['contact'] !='')
        {
            $contact = $request['contact'];
            @$update_data = Otp_Verify::where('contact',$contact)->get();
            
            if($update_data != '[]')
            {
                
                @$dbcontacts = $update_data[0]['contact'];                
                $rand_no = mt_rand(1000, 9999);                
                $updates = DB::table('otp_verify')->where('contact', $dbcontacts)->update(array('otp' => $rand_no));
                //echo json_encode(@$dbcontacts);die;   
                @$send_data = Otp_Verify::where('id',$update_data[0]['id'])->get(['contact','id','otp']);
                if($updates)
                {
                
                    return response()->json(["success"=>"True","msg" => "otp send successfully","data"=>$send_data]);
                }else{
                    return response()->json(["success"=>"false","msg" => "data not inserteddd"]);
                }
            }
            else
            {
                $rand_no = mt_rand(1000, 9999);              
                $otp_verify = new Otp_Verify();
                $otp_verify->contact = $contact;
                $otp_verify->otp = $rand_no;
                if($otp_verify->save())
                {                   
                    return response()->json(["success"=>"True","msg" => "otp send successfully","data"=>$otp_verify]);
                }else{
                    return response()->json(["success"=>"false","msg" => "data not inserted"]);
                }
            } 
        }else{
            return response()->json(["success"=>"false","msg" => "plz insert contact number"]);
        }
       
    }

    public function get_membership(){
        $membership=Membership::get();
        if($membership){

        }else{
            return response()->json(['success' =>true, 'msg' => 'Please fill all the details', "data" => ""], $this->ApiF->okStatus);

        }

    }

    public function edit_profile(Request $request){
        $user = Auth::user();
        if($request){

            $validator_info = $this->validator_info($request->all(),$au = ['name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'last_name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/','phone_no' => 'required','address' => 'required']);
            if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            }

            $edit_user=User::where('id','=',$user->id)->update(["name"=>$request['name'],"last_name"=>$request['last_name']]);  
            $edit_user_detais=UserDetails::where('user_id','=',$user->id)->update(["phone_no"=>$request['phone_no'],"address"=>$request['address']]);
            $users=User::find($user->id);  
            $m_obj=UserDetails::where('user_id','=',$user->id)->get();
            $users=$this->ApiF->esd($users,['id'],'object');
            $m_obj=$this->ApiF->esd($m_obj,['id','org_id','user_id'],'aobject');
            $success['user'] = $users;
            $success['user_details'] = $m_obj;
            
            if($edit_user && $edit_user_detais){
                return  response()->json(['success' =>true, 'msg' => 'user updated successfully',"data"=>$success], $this->ApiF->okStatus);  
            }else{
                return  response()->json(['success' =>false, 'msg' => 'user update failed'], $this->ApiF->okStatus);
            }
        }
        
    }

    public function update_users(Request $request)
    {
        $user = Auth::user();
        if($request){

            $validator_info = $this->validator_info($request->all(),$au = ['name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'last_name' => 'required','email' => 'required','country' => 'required','state' => 'required','city' => 'required']);
            if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            }

            $edit_user=User::where('id','=',$user->id)->update(["name"=>$request['name'],"last_name"=>$request['last_name'],"email"=>$request['email'],"country"=>$request['country'],"state"=>$request['state'],"city"=>$request['city']]);            
            $users = User::find($user->id);            
            $users->profile_img = url('/upload_image').'/'.$users->profile_img;
            $users=$this->ApiF->esd($users,['id'],'object');
            $success['user'] = $users;            
            
            if($edit_user){
                return  response()->json(['success' =>true, 'msg' => 'user updated successfully',"data"=>$success], $this->ApiF->okStatus);  
            }else{
                return  response()->json(['success' =>false, 'msg' => 'user update failed'], $this->ApiF->okStatus);
            }
        }
    }

    public function get_profile(Request $request)
    {
        $user = Auth::user();
        $profile = User::where('id','=',$user->id)->get();

       // $path = public_path() . '/upload_image';
        //$profile['profile_img'] = url("/upload_image")."/".$user->profile_img;        
        if($profile)
        {
            foreach ($profile as $pkey => $data)
            {
                //currency data
                $country = $data['country'];
                if($country == 'United Kingdom')
                {
                    $profile[$pkey]['currency'] = '$';
                  	$profile[$pkey]['currency_type'] = 'USD';
                }else if($country == 'Uganda')
                {
                    $profile[$pkey]['currency'] = '€';
                  	$profile[$pkey]['currency_type'] = 'UGX';
                }else{
                    $profile[$pkey]['currency'] = '$';
                  	$profile[$pkey]['currency_type'] = 'USD';
                }
                $profile[$pkey]['profile_img'] =  url("/upload_image")."/".$data->profile_img;
                return  response()->json(['success' =>true, 'msg' => 'profile', 'data' =>$profile], $this->ApiF->okStatus);    
            }
            
        }else
        {
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
        }
    }

    public function upload_image(Request $request){
        $user = Auth::user();
        //echo json_encode($user->id);
        $id = $user->id; 
        $fileName = ""; $path="";$data=array();
        
        $path = public_path() . '/upload_image';
        
        // if ($request->hasFile('file') && !empty($path)) {
        //     $file = $request->file('file');
        //     $fileName =uniqid("image_").'.png';
        //     $destinationPath = $path;
        //     $file->move($destinationPath, $fileName);
        // }
        
        if ($request['file'] && !empty($path)) {
            $image1 = $request['file'];
            $image_parts = explode("data:image/png;base64,", $image1);  
            $image_parts = str_replace(' ', '+', $image1);          
            $fileName = uniqid("image_").'.png';
            $image_base64 = base64_decode($image_parts);
            $image1 = file_put_contents(public_path().'/upload_image/'.$fileName, $image_base64);
        }
        //echo json_encode($fileName);die;
        if($fileName){
           
            $updateUser = User::find($id);            
            $updateUser->profile_img = $fileName;
            $updateUser->save();
            
            $data['image'] = $fileName;
            $data['file_image'] = url('/upload_image/').'/'.$fileName;
            $data['id'] = en_de_crypt($id,'e');
            return response()->json(['success' =>true,"data" => $data],$this->ApiF->okStatus);
        }else{
            return response()->json(['success' => false, 'error' => 'error', 'msg' => 'Image not found',"data" =>''], $this->ApiF->okStatus);
        }
    }

    public function change_password(request $request)
    {
        $data = $request->all();
        $user = Auth::user();
       //Changing the password only if is different of null
        if( isset($data['oldPassword']) && !empty($data['oldPassword']) && $data['oldPassword'] !== "" && $data['oldPassword'] !=='undefined') {
            //checking the old password first
            $check  = Auth::guard('web')->attempt([
                'email' => $user->email,
                'password' => $data['oldPassword']
            ]);
            if($check && isset($data['newPassword']) && !empty($data['newPassword']) && $data['newPassword'] !== "" && $data['newPassword'] !=='undefined') {
                $user->password = bcrypt($data['newPassword']);
                $user->token()->revoke();
                $token = $user->createToken('newToken')->accessToken;

                //Changing the type
                $user->save();

                return json_encode(array('success' => true,'msg' => 'Password change Successfully', 'token' => $token,'data'=>$user)); //sending the new token
            }
            else {
                return "Wrong password information";
            }
        }
        return "Wrong password information1";
    }

    public function forget_password(Request $request)
    {
            if($request){
                
                $validator_info = $this->validator_info($request->all(),$au = ['email' => 'required|string|email|max:255|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/']);
                if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
                }
                $user = User::whereEmail($request['email'])->first();
                if ($user == null) {
                    return  response()->json(['success' =>false, 'msg' => 'Email not Exist!'], $this->ApiF->okStatus);  
                } else {
                    $user = User::findorfail($user->id);
                    $this->sendEmail($user);
                    return  response()->json(['success' =>true, 'msg' => 'mail send to registered mail id'], $this->ApiF->okStatus);  
    
                } 
            }
    }
    
    public function sendEmail($user) 
    {
            $tem_datas=Template::where('name','=','forget-password')->get();
            if($tem_datas){
                foreach($tem_datas as $tem_data){
                    $description=$tem_data['body'];
                    $name=$user->name;
                    Mail::send('email.forget', ['user' => $user,'description'=>$description,'name'=>$user->name], function ($message) use ($user,$description,$name) {
                        $message->to($user->email);
                        $message->subject("$user->name, reset your password");
                    });    
                }
                 
            }
    }

    protected function validator_info(array $data,array $au) 
    {
        return Validator::make($data, $au);
    }

    public function notifyUser(Request $request)
    {
 
            $user = User::where('id', $request->id)->first();
          
            $notification_id = $user->notification_id;
            $title = "Greeting Notification";
            $message = "Have good day!";
            $id = $user->id;
            $type = "basic";
          
            $res = send_notification_FCM($notification_id, $title, $message, $id,$type);
          
            if($res == 1){
          
               // success code
          
            }else{
          
              // fail code
            }
             
          
    }

    // social login for google
    // public function redirect($provider)
    // {
    //     return Socialite::driver($provider)->redirect();
    // }

    // public function Callback($provider)
    // {
    //     $userSocial  =   Socialite::driver($provider)->stateless()->user();
    //     $users       =   User::where(['email' => $userSocial->getEmail()])->first();

    //     if($users)
    //     {
    //         Auth::login($users);
    //         return redirect('/');
    //     }
    //     else
    //     {
    //     $user = User::create([
    //             'name'          => $userSocial->getName(),
    //             'email'         => $userSocial->getEmail(),
    //             'image'         => $userSocial->getAvatar(),
    //             'provider_id'   => $userSocial->getId(),
    //             'provider'      => $provider,
    //         ]);
    //      return redirect()->route('home');
    //     }
    // }


}