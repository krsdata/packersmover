<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Customer;
use App\Role;
use App\Stations;
use App\Trn;
use App\CompanyUsers;
use App\UsersLoyalty;
use App\Helpers\Helper;
use Mail;
use DateTime;
use DateTimeZone;
use File;
use App\Imglogo;
//use Twilio\Rest\Client;

//use Laravel\Passport\Client as OClient; 
//use GuzzleHttp\Client;
use Twilio\Rest\Client;


use Input, Redirect, Session, Response, DB;
class VendorsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->ApiF = new \App\library\ApiFunctions;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        //return Helper::sendMessage('User registration successful!!', '+918551995731');
        //echo $data;die;
        $user_id = $user->id;
        $role_name = $user->type;
        if($user->hasRole('pos-admin')){
            $perPage = 10;
            $s_name=$request->get('search_input');
            $search_input = "";
            $users = User::orderBy('created_at', 'desc')->where('type','lotto-manager');
            if($s_name){
                $users =  $users->where('name', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }
           
            $users = $users->paginate($perPage);
            
            if ($request->ajax()){

                $view = view("pages.admin.vendors.table_view",compact('search_input','users','user_id','role_name'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{

                return view('pages.admin.vendors.index',compact('search_input','users','user_id','role_name'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home');
    }



    public function vendors_create()
    {
        $user = Auth::user();       
        $role_name = $user->type;
        return view('pages/admin/vendors/create',compact('role_name'));
    }

    public function vendors_update($id)
    {
        $user = Auth::user();
        $role_name = $user->type;
        $user_id = en_de_crypt($id, 'd');
        $user_data = User::findorfail($user_id);
        $station_id=array("0"=>$user_data->stations_id);
        $stations = Stations::where("active",'1') ->orderBy('name', 'asc')->get();
        if($user->hasRole('admin')){
            $trn_data = Trn::select('NUM','CUST_NAME')
                ->groupBy('NUM','CUST_NAME')->get();
        }else{
             $trn_data = Trn::whereIn("stations_id", explode(',', $user->stations_id))->select('NUM','CUST_NAME')
                ->groupBy('NUM','CUST_NAME')->get();
        }
        if($user->hasRole('owner')){
            $stations = Stations::where("active",'1')->whereIn("id", explode(',', $user->stations_id))->orderBy('name', 'asc')->get();
        }
        //echo "<pre>";print_r($user_data); die();
        @$station_selected=explode(',', @$user_data->stations_id);
        //echo "<pre>";print_r($station_selected); die();
        return view('pages/admin/vendors/create',compact('user_data','stations','station_id','role_name','trn_data','station_selected'));
    }

    public function validate_vendors(Request $request) {

        $validator_vendors = $this->validator_vendors($request->all());
        if ($validator_vendors->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_vendors->getMessageBag()->toArray()]);
        }
        return response()->json(['success' => TRUE]);
    }

    protected function validator_vendors_info(array $data) {
        $cuser = Auth::user();
        $au = [
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
            'contact' => 'required',
            'mail_notify' => '',
            'active' => ''
        ];
        
        if($cuser->hasRole('admin')){
            if (isset($data['type']) && $data['type'] != 'user' ) {
                $au['stations_id'] = 'required';
            }
        }
        if (isset($data['type']) && $data['type'] == 'user' ) {
            $au['card_number'] = 'required|unique:users';
            $au['card_desc'] = 'required';
        }
        if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user_id = en_de_crypt($data['user_id'], 'd');
                if (isset($data['type']) && $data['type'] == 'user' ) {
                    $au['card_number'] = 'required|unique:users,card_number,'.$user_id;
                }
                $au['email'] = 'required|email|unique:users,email,' .  $user_id;
                if(!empty($data['password'])){
                    $au['password'] = 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
                }
                return Validator::make($data, $au, ['email.required' => 'User is already exists!!',]);
             
            } else {
                $au['email'] = 'required|string|email|max:255|unique:users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                $au['password'] = 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
                return Validator::make($data, $au, ['email.required' => 'Enter the email address in the format someone@example.com',
                            'email.email' => 'Enter the email address in the format someone@example.com','email.regex' => 'Enter the email address in the format someone@example.com',]);
            }

    }

    public function vendors_store(Request $request) {     
        $cuser = Auth::user();
        $validator_vendors_info = $this->validator_vendors_info($request->all());      
        if ($validator_vendors_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_vendors_info->getMessageBag()->toArray()]);
        }else{
            
            if (!empty($_POST['user_id'])) {
                //print_r($request->all());  die();
                $user_id = en_de_crypt($_POST['user_id'], 'd');
                //$dob = date('Y-m-d', strtotime($request["dob"]));
                $user_data = User::findorfail($user_id);
                $user_data->name = $request['first_name'];
                $user_data->last_name = $request['last_name'];
                $user_data->address = $request['address'];
                $user_data->city = $request['city'];
                $user_data->email = $request['email'];
                $user_data->contact = $request['contact'];
                $user_data->active = '1';
                $user_data->certificate_pass = $request['certificate_pass'];
                $user_data->certificate_tin = $request['certificate_tin'];
                $user_data->CERTKEY = $request['CERTKEY'];
                $user_data->image = $request['image_val'];
                $user_data->mail_notify = $request['mail_notify'];
                $user_data->business_name = $request['business_name'];
                if(!empty($request['mail_notify'])){
                     $user_data->mail_notify = $request['mail_notify'];
                }
                //$user_data->REGID= @$request['REGID'];
                $user_data->type = $request['type'];
                if(!empty($request['card_desc'])){
                    $user_data->card_desc = $request['card_desc'];
                }
                //$user_data->dob = $dob;
                if ($user_data->update()) {
                    DB::table('users_roles')->where('user_id', '=', $user_data->id)->delete();
                    $type = $request['type'];
                    $user_role = Role::where('slug', $type)->first();
                    $user_data->roles()->attach($user_role);                    
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Vendor has been updated Sucessfully!','redirect_url'=>'/admin/vendors-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Vendor Updation failed!','redirect_url'=>'/admin/vendors-list']);
                }

            }else{
               
                $sr_number='';

                // $data = $request->all();
                // echo json_encode($data);
                // die;
                //$imagepath = file_get_contents(public_path() . '/certificate/'.$data['image_val']);
                //$certPassword = 'R@dDix1';
                //openssl_pkcs12_read($imagepath, $certs, $certPassword);
                //var_dump($certs);
                
                $user = new User();
                $user->name = $request['first_name'];
                $user->last_name = $request['last_name'];
                $user->address = $request['address'];
                $user->city = $request['city'];
                $user->email = $request['email'];
                $user->contact = $request['contact'];
                $user->business_name = $request['business_name'];
                $user->active = '1';
                $user->type = $request['type'];
                $user->mail_notify = '1';
                $user->unique_code  = $sr_number;
                $user->password = bcrypt($request['password']);
                $user->certificate_pass = $request['certificate_pass'];
                $user->certificate_tin = $request['certificate_tin'];
                $user->CERTKEY = $request['CERTKEY'];
                $user->image = $request['image_val'];
                if(!empty($request['card_desc'])){
                    $user->card_desc = $request['card_desc'];
                }
                if ($user->save()) {
                    $oauths = $user->id;
                    $emails = $user->email;
                    $passwordss = $user->password;
                    $oClient = OClient::where([['user_id','=',$oauths],['name','=',$emails],['password_client','=',1]])->first();

                    if(!$oClient){
                        $oClient=new OClient;
                        $oClient->user_id=$oauths;
                        $oClient->name=$emails;
                        $oClient->secret=bin2hex(random_bytes(40));
                        $oClient->redirect='http://localhost';
                        $oClient->personal_access_client=0;
                        $oClient->password_client=1;
                        $oClient->revoked=0;
                        $oClient->save();
                    }
                    $http = new \GuzzleHttp\Client;
                    try{
                            $response = $http->post(url('/oauth/token'), [
                                'form_params' => [  
                                'grant_type' => 'password',
                                'client_id' =>$oClient->id,
                                'client_secret' =>$oClient->secret,
                                'username' => $emails,
                                'password' => $passwordss,
                                'scope' => '',
                            ],
                            ]);
                    }catch(Exception $e) {
                            echo 'Message: ' .$e->getMessage();
                    } 
                    

                    $type = $request['type'];
                    $user_role = Role::where('slug', $type)->first();
                    $user->roles()->attach($user_role); 
                    $customer = new Customer();
                    $customer->name = 'demo-customer';
                    $customer->email = 'demo@appristine.in';
                    $customer->mobile_no = '0000000000';
                    $customer->created_by = $user->id;
                    $customer->save();
                   // echo ;  die;
                   //$this->sendMessage('Vendor registration successfully!!', '+255'.$request['contact'] );
                   return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'User has been added Sucessfully!','redirect_url'=>'/admin/vendors-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'User Insertion failed!','redirect_url'=>'/admin/vendors-list']);
                }
            }
        }
    }

  // public function sendMessage($message, $recipients)
  // {
  	 
  //     $account_sid = getenv("TWILIO_SID");
  //     $auth_token = getenv("TWILIO_AUTH_TOKEN");
  //     $twilio_number = getenv("TWILIO_NUMBER");
  //     $client = new Client($account_sid, $auth_token);
  //     $client->messages->create($recipients, 
  //             ['from' => $twilio_number, 'body' => $message] );
  // }

    public function company_user($id,Request $request){
        $user = Auth::user();
        $user_id = en_de_crypt($id, 'd');
        $company_id = $user_id;
        $user_data = User::findorfail($company_id);
        $company_data = CompanyUsers::orderBy('id', 'desc')->where('company_id', '=',   $company_id )->get();
        $aComUsers = array();
        if(!empty($company_data)){
            foreach ($company_data as $cuk => $cuv) {
                $uid = $cuv->user_id;
                $aComUsers[]  = $uid;
            }
        }
        if($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('manager')){
            $perPage = 10;
            $s_name=$request->get('search_input');
            $search_input = "";
            $users = User::orderBy('created_at', 'desc');
            if($s_name){
                $users =  $users->where('name', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }
            if($user->hasRole('owner')){
                $stations_id = $user->stations_id;
                $users->whereIn('stations_id', explode(",",$stations_id));
            }
            if($user->hasRole('manager')){
                $stations_id = $user->stations_id;
                $users->where('stations_id', '=', $stations_id);
            }
            $users->where('type', '=', 'user');
            $users->whereNotIn( "id", $aComUsers );
            $users = $users->paginate($perPage);
            if ($request->ajax()){
                $view = view("pages.admin.vendors.table_view_add",compact('search_input','users','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view,'success'=>TRUE,'msg_type' => 'success']);
            }else{
                return view('pages/admin/vendors/company',compact('user_data','company_id','search_input'));
            }
        }
    }

    public function add_company_vendors(Request $request) {
        $cuser = Auth::user();
        if (!empty($_POST['id']) && !empty($_POST['cid'])) {
            $cid = $_POST['cid'];
            $id = $_POST['id'];
            $user_id = en_de_crypt($id, 'd');
            $staCompanyUserstions = CompanyUsers::firstOrCreate(['company_id' => $cid,'user_id' => $user_id]);
            if ($staCompanyUserstions) {
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'The company added user successfully!']);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'The company add user failed!']);
            }

        }else{
            return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'The company add user failed!']);
        }
    }
    



    public function password_verify($password, $hash)
    {
      if (strlen($hash) !== 60 OR strlen($password = crypt($password, $hash)) !== 60)
      {
        return FALSE;
      }
      $compare = 0;
      for ($i = 0; $i < 60; $i++)
      {
        $compare |= (ord($password[$i]) ^ ord($hash[$i]));
      }
      return ($compare === 0);
    }

    public function send_otp(Request $request)
    {
        $user = Auth::user();
        $contact = $request['contact'];
        $rand_no = mt_rand(1000, 9999);
        $message = 'Your one-time password for pos is '.$rand_no.' For security reasons, please do not share this. This code will expire shortly.';
        $data =  Helper::sendMessage($message, '+91'.$contact);
        if($data)
        {
            return response()->json(["success"=>"True","msg" => "","data"=>$data]);
        }else{
            return response()->json(["success"=>"false","msg" => "","data"=>$data]);
        }
    }

    public function certificate_upload(Request $request){        
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("certificate").'.pfx';
            $destinationPath = public_path() . '/certificate';
            $file->move($destinationPath, $fileName);
        }        
        return $fileName;
    }


    public function change_user_pass(Request $request)
    {
        
        if (!empty($request['user_id_pass'])) {

            $user_id = en_de_crypt($request['user_id_pass'], 'd');

            $u_data=User::where('id','=',$user_id)->get();

            $current_pass =  $request['current_pass'];
            $new_pass =  $request['password'];
            $confirm_pass =  $request['confirm_password'];

            if (password_verify($current_pass, @$u_data[0]->password)) {

              if (!empty($new_pass) && $new_pass == $confirm_pass) {

                $u_data = User::findorfail($user_id);
                $u_data->password = bcrypt($new_pass);

                if ($u_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'New password has been updated Sucessfully!','reset'=>'true']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updateion failed!','reset'=>'true']);
                }
              }else{

                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'New password and confirm password does not match.','reset'=>'true']);
              }
            }else{

              return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Current password does not match.','reset'=>'true']);
            }
        }
      return view('pages/admin/user/edit_user');
    }

    public function img_upload(Request $request){
        $p_fileName = "false";
        // if ($request->file('image')) {
        //     $filename=public_path().'/images/logo.png';
        //     if(File::exists($filename)) {
        //         if(File::delete($filename)){
        //             $p_file = $request->file('image');
        //             $p_fileName = "logo".'.png';
        //             $destinationPath = public_path().'/images/';  
        //             shell_exec('chmod -R 777 public/images');
        //             $data=\Image::make($p_file)->save(public_path('images/'.$p_fileName ));
        //         }
        //     }else{
        //         //if(File::delete($filename)){
        //             $p_file = $request->file('image');
        //             $p_fileName = "logo".'.png';
        //             $destinationPath = public_path().'/images/';  
        //             shell_exec('chmod -R 777 public/images');
        //             $data=\Image::make($p_file)->save(public_path('images/'.$p_fileName ));
        //         //}
        //     }
        // }
        // if ($request->hasFile('file')) {
        //     $update=ImgLogo::update(["active"=>1]);
        //     //if($update){
        //       $file = $request->file('file');
        //       $fileName =uniqid("image_").'.png';
        //       $destinationPath = public_path() . '/images/';
        //       $file->move($destinationPath, $fileName);
        //       $insert_img=new ImgLogo;
        //       $insert_img->img=$fileName;
        //       $insert_img->save();     
        //     //}            

        // }

        $user = Auth::user();
        $fileName = "false";
        if ($request->file('image')) {
            $update=Imglogo::where('id', '>', 0)->update(["active"=>1]);
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/logo';
            $file->move($destinationPath, $fileName);
            $insert_img=new Imglogo;
            $insert_img->img=$fileName;
            $insert_img->active=0;
            $insert_img->save();
          }
        return $fileName;
        
    }


}
