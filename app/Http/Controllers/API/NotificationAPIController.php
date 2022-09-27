<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator;
use Mail;
use Helper;
use App\User;
use App\User_Notification;
use Hash;
use App\Template;
use Illuminate\Support\Str;

class NotificationAPIController extends Controller {


    public $successStatus = 200;
    protected $request;
    public function __construct(Request $request) 
    {
        $this->request = $request;
        $this->edata =  new \stdClass();
        $this->ApiF = new \App\library\ApiFunctions;
        $this->FirebaseF = new \App\library\FirebaseFunctions;
        $this->Push = new \App\library\Push;
        
    }
    // device token update
    public function fcm_update(Request $request)
	{
        $user = Auth::user();
        $device_token = $request['device_token'];

        if($user)
        {
            $if_exist = user::where('id',$user->id)->get();
            if(!isset($if_exist[0]['device_token']))
            {
                $device_updated = User::where('id','=',$user->id)->update(["device_token"=> $device_token]);
                return  response()->json(['success' =>true, 'msg' => 'FCM updated successfully'], $this->ApiF->okStatus);  
            }
            else
            {
                $device_updated = User::where('id','=',$user->id)->update(["device_token"=> $device_token]);
                return  response()->json(['success' =>true, 'msg' => 'FCM updated successfully'], $this->ApiF->okStatus);  
            }
        }
        else
        {
            return  response()->json(['success' =>false, 'msg' => 'user failed'], $this->ApiF->okStatus);
        }
	
   	}
    
    // get notifications
    public function get_notification()
    {
        $user = Auth::user();
        if($user)
        {
            $get_data = User_Notification::where('user_id',$user->id)->get();

            if($get_data)
            {
                return  response()->json(['success' =>true, 'msg' => 'Notification list', "data" =>$get_data], $this->ApiF->okStatus);
            }
            else 
            {
                return  response()->json(['success' =>false, 'msg' => 'Notification list', "data" =>$get_data], $this->ApiF->okStatus);
            }
        }
        else
        {
            return  response()->json(['success' =>false, 'msg' => 'user failed', "data" =>''], $this->ApiF->okStatus);
        }
            
    }

    // 
    public function send_fcm_message_api_user($user_id,$msg,$title='',$to_id='',$fname='',$click_action = '')
    {

        $check = user::where('id',$user_id)->get();

        if(isset($check[0]['id']) && !empty($check[0]['device_token']))
        {
            $devicetoken = $check[0]['device_token'];
           // $firebase = new Firebase1();
           // $push = new Push();
            $this->Push->setTitle($title);
            $this->Push->setMessage($msg);
            $this->Push->setoid($to_id);
            $this->Push->setfname($fname);
            $this->Push->setUser($user_id);
            $json = $this->Push->getPush();
            
            $this->Push->setClick_action($click_action);
            
            $notification = $this->Push->getPush1();
            
            // $query = mysqli_query($conn,"SELECT iosToken FROM user_notify WHERE fcm_no = '$fcmno' AND iosToken !='' ");
            if($check[0]['source'] == 'IOS')
            {
                $response = $this->FirebaseF->sendToios($devicetoken, $json);
                return $response;
            }
            else
            {
                //$json = json_encode($json);   
                $response =  $this->FirebaseF->send($devicetoken, $json,$notification);
                return $response;
            }
            
        }
        else{
            return $check;
        }
    }
    // booking draw get notifications
    public function booking_draw_notifications($user_id,$title,$message)
    {
        
        $user = Auth::user();

        if (!empty($message) && !empty($title))
        {
            $user_id    = $user_id; 
            $message    = $message;
            $title      = $title;
            $this->send_fcm_message_api_user($user_id, $title, $message);
        }
    }

    public function booking_draw_notifications1(Request $request)
    {
        $user = Auth::user();

        if (!empty($message) && !empty($title))
        {
            $user_id    = $user_id; 
            $message    = $message;
            $title      = $title;
            $this->send_fcm_message_api_user($user_id, $title, $message);
        }
    }
}
