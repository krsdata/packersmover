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
use App\Draw_Text;
use App\Monthly_Tickets;
use App\Draw;
use App\Countries;
use App\States;
use App\Cities;
use App\Privacy_Policy;
use App\Terms_Conditions;
use App\Contact_Us;
use App\Winner;
use App\Faq;
use App\Banner;
use App\Role;
use App\Language;
use Carbon\Carbon;
use Input, Redirect, Session, Response, DB;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {

        $user=Auth::user();
         if($user)
         {
          $country = $user->country;
         }
      
        $winner = Winner::groupby('user_id')->get();
        foreach ($winner as $wkey => $data1)
        {
           $winner[$wkey]['image'] = asset('/images/profile_img/'.$data1->image);   
        }
      
       $banner = Banner::where('status','=','active')->get();
       $data = Draw_Text::get();
       if($user)
          {
            $draw = Draw::where('lang',$country)->get();
          }else{
           	$draw = Draw::where('lang','United Kingdom')->get(); 
          }
       //$draw = Draw::get();
       return view('front.home.home',compact('user','draw','winner'));
       
    }

    public function about()
    {

       $user=Auth::user();
       return view('front.home.about',compact('user'));
       
    }

    public function contact()
    {

       $user=Auth::user();
       return view('front.home.contact',compact('user'));
       
    }

    public function faq()
    {

       $user=Auth::user();
       $data = Faq::get();
       return view('front.home.faq',compact('user','data'));
       
    }

    public function terms()
    {

       $user=Auth::user();
       $data = Terms_Conditions::get();
       return view('front.home.terms',compact('user','data'));
       
    }

    public function privacy()
    {

       $user=Auth::user();
       $data = Privacy_Policy::get();
       return view('front.home.privacy',compact('user','data'));
       
    }
  
  	public function month()
    {

       $user=Auth::user();
       $monthly = Monthly_Tickets::where('status','1')->get();
       return view('front.home.month',compact('user','monthly'));
       
    }
  
   //08-03-2022
   public function contact_store(Request $request)
    {
      echo ">>";
      die;
    }

    //draw registers
    public function draw_register(Request $request)
    {
      $user=Auth::user();
      $country = Countries::whereIn('id',array('227','230'))->get();   
      return view('front.home.draw_register',compact('user','country'));
    }

    public function get_states(Request $request)
    {
       $id = $request->id;
       $country = States::where('country_id',$id)->get();
       echo json_encode($country);
    }

    public function get_city(Request $request)
    {
       $id = $request->id;
       $city = Cities::where('state_id',$id)->get();
       echo json_encode($city);
    }

    public function user_profile(Request $request)
    {
      $user = Auth::user();
      $country = Countries::whereIn('id',array('227','230'))->get();
      $states = States::get();
      return view('front.home.account',compact('user','country','states'));
    }

    public function store_profile(Request $request)
    {
      $user = Auth::user();
      if (!empty($request['user_id'])) {
         $validator_user = $this->validator_user($request->all());
         if ($validator_user->fails()) {
             return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Email Address already exist!!']);
         }
         $img_url = '';
         $user_id = en_de_crypt($request['user_id'], 'd');
         $user_data = User::findorfail($user_id);
         $user_data->name = $request['name'];
         $user_data->email = $request['email'];
         $user_data->last_name = $request['last_name'];
         $user_data->contact = $request['contact'];
         return view('front.home.account',compact('user'));
         // if ($user_data->update()) {
         //     return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','img_url'=>$img_url]);
         // }else{
         //     return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updation failed!']);
         // }
     }

      
    }
  
  //06-04-2022
    public function howtoplay(Request $request)
    {
      
       $user=Auth::user();
     
           $draw = Draw_Text::where('type','week_draw')->get();
            foreach ($draw as $tkey => $tvalue) 
            {
                $text_type = $tvalue->text_type;
                if($text_type == 'how_to_play'){
                    $draw['how_to_play'] = $tvalue;  
                }
                
                if($text_type == 'note')
                {
                    $draw['note'] = $tvalue;
                }                
            }
         
            
       return view('front.home.howtoplay',compact('user','draw'));
       
    }

    public function howtowin(Request $request)
    {
      
       $user=Auth::user();
     
           $draw = Draw_Text::where('type','month_draw')->get();
           foreach ($draw as $tkey => $tvalue) 
           {
               $text_type = $tvalue->text_type;
               if($text_type == 'how_to_play'){
                   $draw['how_to_play'] = $tvalue;  
               }
             
                if($text_type == 'how_to_win'){
                   $draw['how_to_win'] = $tvalue;
               }
               
               if($text_type == 'note')
               {
                   $draw['note'] = $tvalue;
               }                
           }
                     
       return view('front.home.howtowin',compact('user','draw'));
       
    }

  


}
