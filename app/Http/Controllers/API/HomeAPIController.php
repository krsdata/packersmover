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
use App\Banner;
use App\Winner;
use App\Contact_Us;
use App\Feedback;
use App\Terms_Conditions;
use App\Privacy_Policy;
use App\Countries;
use App\States;
use App\Cities;
use App\Request_Money;
use App\Draw;
use App\Booking_Draw;
use App\Withdraw_Balance;
use App\Draw_Text;
use App\User_Notification;
use App\Monthly_Tickets;
use App\Faq;
use Hash;
use App\Template;
use Illuminate\Support\Str;

class HomeAPIController extends Controller {


    public $successStatus = 200;
    protected $request;
    public function __construct(Request $request) 
    {
        $this->request = $request;
        $this->edata =  new \stdClass();
        $this->ApiF = new \App\library\ApiFunctions;        
    }


    // Home API
   
    public function home(Request $request)
    {
        $user = Auth::user();
       if($user)
       {
      	$country = $user->country;
       }
        
        //customer       
        $banner=Banner::orderBy('id','desc')->get();
        if($user)
        {
            $wallet = $user->wallet;
        }
        
        //$banner = $banner->get();
        if($banner){

        foreach ($banner as $bkey => $data) 
        {
           // echo json_encode($data);
            $banner[$bkey]['image'] = asset('/images/banner/'.$data->image);
            $winner=Winner::groupby('user_id')->get();
            
          
            foreach ($winner as $wkey => $data1)
            {
                $winner[$wkey]['image'] = asset('/images/profile_img/'.$data1->image);
                
            }
        }
          if($user)
          {
            $draw = Draw::where('lang',$country)->get();
          }else{
           	$draw = Draw::where('lang','United Kingdom')->get(); 
          }
        
        if($draw)
        {
            foreach ($draw as $dkey => $ddata)
            {
                $draw[$dkey]['image'] = asset('/images/draw/'.$ddata->image);
                
            }
        }
        
        return  response()->json(['success' =>true, 'msg' => 'Banner list', "wallet" => @$wallet,"banners" =>$banner,"draw" =>$draw,"winners" =>$winner], $this->ApiF->okStatus);            

        }else{
                return  response()->json(['success' =>false, 'msg' => 'No banner found', "data" =>""], $this->ApiF->okStatus);
        }

    }

    public function contact(Request $request)
    {
        $user = Auth::user();
        $validator_info = $this->validator_info($request->all(),$au = ['name' => 'required','email_id' => 'required|email','mobile_no' => 'required',
        'type'=>'required','message'=>'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 

            $contact =new Contact_Us();
            $contact->name =$request['name'];
            $contact->email_id =$request['email_id'];
            $contact->mobile_no =$request['mobile_no'];
            $contact->message = $request['message'];
            $contact->type = $request['type'];
            if($contact->save()){

                return  response()->json(['success' =>true, 'msg' => 'contact saved successfully', "data" =>$contact], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function feedback(Request $request)
    {
        $user = Auth::user();
        $validator_info = $this->validator_info($request->all(),$au = ['review'=>'required','rating'=>'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 

            $feedback =new Feedback();
            $feedback->review =$request['review'];
            $feedback->rating =$request['rating'];
            $feedback->user_id =$user->id;
            
            if($feedback->save()){

                return  response()->json(['success' =>true, 'msg' => 'feedback saved successfully', "data" =>$feedback], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function terms_conditions(Request $request)
    {
        $user = Auth::user();
        $validator_info = $this->validator_info($request->all(),$au = ['lang'=>'required']);
        if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
        } 
            $lang = $request['lang'];
            $terms_cond=Terms_Conditions::where('lang',$lang)->where('status','1')->get();   
       		$content = str_replace(array("\r","\n","\t","&nbsp;"), "", $terms_cond[0]->description);
            $terms_cond[0]->description = strip_tags($content);  
            if($terms_cond){

                return  response()->json(['success' =>true, 'msg' => 'list', "data" =>$terms_cond], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function privacy_policy(Request $request)
    {
        $user = Auth::user();

            $validator_info = $this->validator_info($request->all(),$au = ['lang'=>'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 
            $lang = $request['lang'];
            $privacy_poly = Privacy_Policy::where('lang',$lang)->where('status','1')->get();           
            $content = str_replace(array("\r","\n","\t","&nbsp;"), "", $privacy_poly[0]->description);
            $privacy_poly[0]->description = strip_tags($content);           
            if($privacy_poly){

                return  response()->json(['success' =>true, 'msg' => 'list', "data" =>$privacy_poly], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function countries(Request $request)
    {
            $country=Countries::orderBy('id','desc')->whereIn('id',array('227','230','50','113','216','204','83','160'))->get();                       
            if($country){

                return  response()->json(['success' =>true, 'msg' => 'Countries List', "data" =>$country], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function states(Request $request)
    {
        $validator_info = $this->validator_info($request->all(),$au = ['id' => 'required','search_input' => '']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 
            $id = $request['id'];
            $state = States::where('country_id','=',$id)->get();                        
            if($state){

                return  response()->json(['success' =>true, 'msg' => 'State List', "data" =>$state], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function city(Request $request)
    {
        $validator_info = $this->validator_info($request->all(),$au = ['id' => 'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 
            $id = $request['id'];
            $city = Cities::where('state_id','=',$id)->get();           
            if($city){

                return  response()->json(['success' =>true, 'msg' => 'City List', "data" =>$city], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function request_money(Request $request)
    {
        $user = Auth::user();
        //echo json_encode($user);die;
        $validator_info = $this->validator_info($request->all(),$au = ['bank_name' => 'required','account_name' => 'required','account_number' => 'required','swift_code' => 'required','amount' => 'required','contact' => 'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 
            $request_money = new Request_Money();
            $request_money->bank_name = $request['bank_name'];
            $request_money->account_number = $request['account_number'];
            $request_money->account_name = $request['account_name'];
            $request_money->swift_code = $request['swift_code'];
            // check amount to user table in wallet column
            $wallet = $user->wallet;
            if($wallet > 0)
            {
                $check_amt = $request['amount'] <= $wallet;
                $wallet_balance = $wallet - $request['amount'];
                User::where('id', $user->id)
                ->update([
                    'wallet' => $wallet_balance
                    ]);
                if(!$check_amt)
                {
                   
                    return  response()->json(['success' =>false, 'msg' => 'amount should be less than', "data" =>""], $this->ApiF->okStatus);

                }
                
                
            }
            
            $request_money->contact = $request['contact'];
            $request_money->amount = $request['amount'];
            $request_money->user_request = 'success';
            $request_money->user_id = $user->id;
            if($request_money->save()){

                return  response()->json(['success' =>true, 'msg' => 'request send successfully', "data" =>$request_money], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }

    public function booking_draw(Request $request)
    {
        $user = Auth::user();
        $validator_info = $this->validator_info($request->all(),$au = ['first_name' => 'required','last_name' => 'required','gender' => 'required','city' => 'required','state' => 'required',
        'country' => 'required','mobile' => 'required','otp' => 'required','draw_name' => 'required','fees' => 'required',]);

        if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 
			$countsall = Booking_Draw::where('user_id',$user->id)->get();
            $checkorder = $countsall->count();
            if($checkorder >= 3)
            {
              return  response()->json(['success' =>true, 'msg' => 'maximum 3 draw', "data" =>''], $this->ApiF->okStatus);
            }
      	
            $booking_draw = new Booking_Draw();
            $booking_draw->first_name = $request['first_name'];
            $booking_draw->last_name = $request['last_name'];
            $booking_draw->gender = $request['gender'];
            $booking_draw->city = $request['city'];
            $booking_draw->state = $request['state'];
            $booking_draw->country = $request['country'];
            $booking_draw->mobile = $request['mobile'];
            $booking_draw->otp = $request['otp'];
            $booking_draw->draw_name = $request['draw_name'];
            $booking_draw->fees = $request['fees'];           
            $booking_draw->type = 'week_draw';
            $booking_draw->order_status = 'pending';
      		$booking_draw->date = date("Y-m-d");
            $booking_draw->user_id = $user->id; 
        
        if($booking_draw->save()){
            $order_id = $booking_draw->id;            
            $title = 'Hii';
            $message = 'its working';
            $user_notificatins = new User_Notification();
            $user_notificatins->title = $title;
            $user_notificatins->message = $message;
            $user_notificatins->order_id = $order_id;
            $user_notificatins->user_id = $user->id;

            if($user_notificatins->save())
            {
                $result = app('App\Http\Controllers\API\NotificationAPIController')->booking_draw_notifications($user->id,$title,$message);                
            }
            
            return  response()->json(['success' =>true, 'msg' => 'booking draw successfully', "data" =>$booking_draw], $this->ApiF->okStatus);
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
     }

    }
    // booking monthly Draw
    public function booking_Monthdraw(Request $request)
    {
        $user = Auth::user();
        $validator_info = $this->validator_info($request->all(),$au = ['first_name' => 'required','last_name' => 'required','gender' => 'required','city' => 'required','state' => 'required',
        'country' => 'required','mobile' => 'required','otp' => 'required','draw_name' => 'required','fees' => 'required','ticket_id' => 'required']);

        if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            } 

            $booking_draw = new Booking_Draw();
            $booking_draw->ticket_id = $request['ticket_id'];
            $booking_draw->first_name = $request['first_name'];
            $booking_draw->last_name = $request['last_name'];
            $booking_draw->gender = $request['gender'];
            $booking_draw->city = $request['city'];
            $booking_draw->state = $request['state'];
            $booking_draw->country = $request['country'];
            $booking_draw->mobile = $request['mobile'];
            $booking_draw->otp = $request['otp'];
            $booking_draw->draw_name = $request['draw_name'];
            $booking_draw->fees = $request['fees'];           
            $booking_draw->type = 'month_draw';
            $booking_draw->order_status = 'pending';
      		$booking_draw->date = date("Y-m-d");
            $booking_draw->user_id = $user->id; 
            //update month draw ticker for status 0
            $updateMonth = Monthly_Tickets::where('ticket_id','=',$request['ticket_id'])->update(['status'=>'0']);
        if($booking_draw->save()){

            return  response()->json(['success' =>true, 'msg' => 'booking draw successfully', "data" =>$booking_draw], $this->ApiF->okStatus);
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
     }

    }

    // get month draw
    public function Month_Draw(Request $request)
    {
        $user = Auth::user();
        $perPage = 60;
        $get_month = array();$p_data="";
        $search_input = $request['search_input'];
        if($search_input)
        {
           $p_data = DB::table('monthly_tickets')->orderBy('id', 'desc')->where('id', '=', $search_input)->orwhere('id', $search_input);     
           if($p_data) 
           {
                $get_month = $p_data->paginate($perPage);
           }
       }
       else
       {
             $get_month = DB::table('monthly_tickets');
             $get_month = $get_month->paginate($perPage);
       }

        if($get_month){

                return  response()->json(['success' =>true, 'msg' => 'list', "data" =>$get_month], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }
    }

    // set settings lang/sounds/notifications
    public function Set_Settings(Request $request)
    {
        $user = Auth::user();
        if($request)
        {
            $validator_info = $this->validator_info($request->all(),$au = ['type' => 'required']);

            if ($validator_info->fails()) {
                        return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
                } 
            $type = $request['type'];
            
            if($type)
            {
                if($type == 'en' || $type == 'ar')
                {
                    $update_settings = User::where('id','=',$user->id)->update(["lang"=>$request['type']]);
                }else if($type == 'sounds')
                {
                    $if_exist = user::where('id',$user->id)->get();
                    if($if_exist[0]['sounds'] ==  0){
                    $update_settings = User::where('id','=',$user->id)->update(["sounds"=> "1"]);
                    }else{
                        $update_settings = User::where('id','=',$user->id)->update(["sounds"=> "0"]);
                    }
                }
                else if($type == 'vibration')
                {
                    $if_exist = user::where('id',$user->id)->get();
                    if($if_exist[0]['vibration'] ==  0){
                    $update_settings = User::where('id','=',$user->id)->update(["vibration"=> "1"]);
                    }else{
                        $update_settings = User::where('id','=',$user->id)->update(["vibration"=> "0"]);
                    }
                }
                else if($type == 'push')
                {
                    $if_exist = user::where('id',$user->id)->get();
                    if($if_exist[0]['push'] ==  0){
                    $update_settings = User::where('id','=',$user->id)->update(["push"=> "1"]);
                    }else{
                        $update_settings = User::where('id','=',$user->id)->update(["push"=> "0"]);
                    }
                }else if($type == 'email_notifications')
                {
                    $if_exist = user::where('id',$user->id)->get();
                    if($if_exist[0]['email_notifications'] ==  0){
                    $update_settings = User::where('id','=',$user->id)->update(["email_notifications"=> "1"]);
                    }else{
                        $update_settings = User::where('id','=',$user->id)->update(["email_notifications"=> "0"]);
                    }
                }                
            }                 
    
            if($update_settings){
                return  response()->json(['success' =>true, 'msg' => 'settings updated successfully',"data"=>$update_settings], $this->ApiF->okStatus);  
            }else{
                return  response()->json(['success' =>false, 'msg' => 'settings update failed'], $this->ApiF->okStatus);
            }
        }
    }

    public function my_wallet(Request $request)
    {
        $user = Auth::user();
        $wallet_balance = User::where('id',$user->id)->get('wallet');
       //echo json_encode($wallet_balance['wallet']);die;
        $last_withdraw = Withdraw_Balance::where('user_id','=',$user->id)->orderby('created_at','desc')->groupBy('id')->limit(1)->get('withdraw_amount');        
    
        if($user){

            return  response()->json(['success' =>true, 'msg' => 'amounts', 'wallet_balance' =>$wallet_balance,'last_withdraw' => $last_withdraw], $this->ApiF->okStatus);
            
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
     }
    }

    public function withdraw_history(Request $request)
    {
        $user = Auth::user();
        $withdraw = Withdraw_Balance::where('user_id','=',$user->id)->orderby('created_at','desc')->get();        

    
        if($user){
            return  response()->json(['success' =>true, 'msg' => 'All transactions history', 'data' =>$withdraw], $this->ApiF->okStatus);
            
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
     }
    }

    public function my_plays(Request $request)
    {
        $user = Auth::user();
      	$type = $request['type'];
      
        $plays = Booking_Draw::where('user_id','=',$user->id)->where('order_status','pending')->where('type',$type)->get();
        
        if($plays){
            return  response()->json(['success' =>true, 'msg' => 'List', 'data' =>$plays], $this->ApiF->okStatus);        
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
     }
    }

    public function faq(Request $request)
    {
        $validator_info = $this->validator_info($request->all(),$au = ['lang' => 'required']);
            if ($validator_info->fails()) {
                    return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
            }
            $lang = $request['lang'];
            $faq = Faq::where('lang',$lang)->get();
            foreach($faq as $fkey => $fvalue)
            {
                $description = $fvalue->description;
                $title = $fvalue->title;
                $content = str_replace(array("\r","\n","\t"), "", $description);
                $content1 = str_replace(array("\r","\n","\t"), "", $title);
                $faq[$fkey]['description'] = strip_tags($content);
                $faq[$fkey]['title'] = strip_tags($content1);
            }
            if($faq){

                return  response()->json(['success' =>true, 'msg' => 'list', "data" =>$faq], $this->ApiF->okStatus);
            }
            else{
                return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
         }

    }
    // get how to play data type wise
    public function get_playData(Request $request)
    {
        $validator_info = $this->validator_info($request->all(),$au = ['type' => 'required']);
        if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray() ]);
        }
        $type = $request['type'];
        $all_data = array();
       
        $all_data1 = array();
        if($type == 'week_draw')
        {
           $type = Draw_Text::where('type',$type)->get();
            foreach ($type as $tkey => $tvalue) 
            {
                $text_type = $tvalue->text_type;
                if($text_type == 'how_to_play'){
                    $all_data['how_to_play'][] = $tvalue;  
                }
                
                if($text_type == 'note')
                {
                    $all_data['note'][] = $tvalue;
                }                
            }
            return  response()->json(['success' =>true, 'msg' => 'data', "data" =>$all_data], $this->ApiF->okStatus);
        }else if($type == 'month_draw'){

            $type = Draw_Text::where('type',$type)->get();
            foreach ($type as $tkey => $tvalue) 
            {
                $text_type = $tvalue->text_type;
                if($text_type == 'how_to_play'){
                    $all_data['how_to_play'][] = $tvalue;  
                }
              
              	if($text_type == 'how_to_win'){
                    $all_data['how_to_win'][] = $tvalue;
                }
                
                if($text_type == 'note')
                {
                    $all_data['note'][] = $tvalue;
                }                
            }
            return  response()->json(['success' =>true, 'msg' => 'data', "data" =>$all_data], $this->ApiF->okStatus);
        }else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>"type is not proper"], $this->ApiF->okStatus);
        }
    }

    // monthly tickets add script
   public function monthly_tickets(Request $request)
   {
        $validator_info = $this->validator_info($request->all(),$au = ['ticket_name' => 'required','description' => 'required','fee' => 'required','status' => 'required']);
        
        for($i= 1;$i<=1000;$i++){
        $monthly_tickets = new Monthly_Tickets();
        $monthly_tickets->ticket_name = 'Monthly Draw';
        $monthly_tickets->description = 'Get a chance win upto';
        $monthly_tickets->fee = '100';
        $monthly_tickets->status = '1';
        $monthly_tickets->created_by = '1275';
        $randome_number = rand(1000000000, 9999999999);
        $monthly_tickets->ticket_id = $randome_number;
        $monthly_tickets->save();
     
        }
        // if($monthly_tickets->save()){

        //     return  response()->json(['success' =>true, 'msg' => 'booking draw successfully', "data" =>$monthly_tickets], $this->ApiF->okStatus);
        // }
        // else{
        //     return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
        // }
    
   }

  /* public function monthly_tickets(Request $request)
   {
        $validator_info = $this->validator_info($request->all(),$au = ['type' => 'required','type_title' => 'required','title' => 'required','description' => 'required','text_type' => 'required']);        

        $monthly_tickets = new Draw_Text();
        $monthly_tickets->type = $request['type'];
        $monthly_tickets->type_title = $request['type_title'];
        $monthly_tickets->title = $request['title'];
        $monthly_tickets->description = $request['description'];
        $monthly_tickets->text_type = $request['text_type'];

        if($monthly_tickets->save()){

            return  response()->json(['success' =>true, 'msg' => 'booking draw successfully', "data" =>$monthly_tickets], $this->ApiF->okStatus);
        }
        else{
            return  response()->json(['success' =>false, 'msg' => 'Failed', "data" =>""], $this->ApiF->okStatus);
        }

   }*/
  
  public function winners_script(Request $request)
   {
        $monday = strtotime("this monday");
        $sunday = strtotime(date("Y-m-d",$monday)." +5 days");
        $from = date("Y-m-d",$monday);
        $to = date("Y-m-d",$sunday);

        $data = Booking_Draw::whereBetween('created_at',[$from, $to])->get();
        
        $user_id = array();
        foreach($data as $dkey => $dval)
        {
            @$total_fees += $dval->fees;
            $user_id[] = $dval->user_id;
            
            
        }
       
        //echo json_encode($users);
        //die;
        
        for($i=0;$i<4;$i++){
            $random_winner[] = array_rand(array_flip($user_id));
        }
       //$im = explode(",",$random_winner);
       $ex = implode(",",$random_winner);
      

        $first_winner = ($total_fees * 50 / 100);
        $second_winner = ($total_fees * 20 / 100);
        $third_winner = ($total_fees * 10 / 100);

        $users = User::whereIn('id',($random_winner))->get();
        $data = $users->count();
        
        foreach($users as $ukey => $uval)
        {
            $exist_wallet = $uval->wallet;
            $wiiner_id[] = $uval->id;
            $id = $uval->id;
            $name = $uval->name;

            $winner_insert = new Winner();
            $winner_insert->user_id = $id;
            $winner_insert->draw_name = 'week_draw';
            $winner_insert->name = $name;
            $winner_insert->win_price = "100";
            $winner_insert->save();
            //$sk = explode(",",$wiiner_id);
           // print_r($sk);
            $update_firstwallet = $exist_wallet + $first_winner;
            $update_secondwallet = $exist_wallet + $second_winner;
            $update_thirdwallet = $exist_wallet + $third_winner;

            //$winners_wallet = User::whereIn('id', ($wiiner_id))->update(['wallet' => $update_firstwallet,'wallet' => $update_secondwallet,
                   // 'wallet' => $update_thirdwallet]);
            //$winners_wallet = User::where('id', $wiiner_id)->update(['wallet' => $update_secondwallet]);
            //$winners_wallet = User::where('id', $wiiner_id)->update(['wallet' => $update_thirdwallet]);
        }
        
        
       

   }



   protected function validator_info(array $data,array $au) {
    return Validator::make($data, $au);  
   }

}