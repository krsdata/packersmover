<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\UsersLoyalty;
use Mail;
use File;
use App\Imglogo;
use Input, Redirect, Session, Response, DB;
class UsersController extends Controller
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
        $user = Auth::user();
        $user_id = $user->id;
        $role_name = $user->type;


        if($request->id)
        {
            $id = en_de_crypt($request->id, 'd');
            $delete_data = User::findorfail($id);
            $delete_data->delete();
            return redirect::to('admin/user-list');
        }

        if($user->hasRole('sai-manager')){
            $perPage = 10;
            $s_name=$request->get('search_input');
            $search_input = "";
            $users = User::where('type','user')->orderBy('created_at', 'desc');
            
            if($s_name){
                $users =  $users->where('name', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }
           
            $users = $users->paginate($perPage);
           // dd(Auth::user()->type);
            if ($request->ajax()){

                $view = view("pages.admin.user.table_view",compact('search_input','users','user_id','role_name'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{

                return view('pages.admin.user.index',compact('search_input','users','user_id','role_name'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home');
    }

    public function user_create()
    {
        $user = Auth::user();
        $role_name = $user->type;

        return view('pages/admin/user/create',compact('user','role_name'));
    }

    public function user_update($id)
    {
        $user = Auth::user();
        $role_name = $user->type;
        $user_id = en_de_crypt($id, 'd');
        $user_data = User::findorfail($user_id);
        //echo "<pre>";print_r($station_selected); die();
        return view('pages/admin/user/create',compact('user_data'));
    }

    protected function validator_user(array $data) {

            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user_id = en_de_crypt($data['user_id'], 'd');
                return Validator::make($data, [
                            'email' => 'required|unique:users,email,'. $user_id,
                                ], ['email.unique' => 'Email address already exist!!',]);
            } else {

                return Validator::make($data, [
                            'email' => 'required|string|email|max:255|unique:users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
                                ], ['email.required' => 'Enter valid email address!!','email.unique' => 'Email address already exist!!',
                            'email.email' => 'Enter valid email address!!','email.regex' => 'Enter valid email address!!',]);
            }
    }

    public function validate_user(Request $request) {

        $validator_user = $this->validator_user($request->all());
        if ($validator_user->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_user->getMessageBag()->toArray()]);
        }
        return response()->json(['success' => TRUE]);
    }

    protected function validator_user_info(array $data) {
        $cuser = Auth::user();
        $au = [
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
            'contact' => 'required'
        ];
        
        if($cuser->hasRole('admin')){
            if (isset($data['type']) && $data['type'] != 'user' ) {
                $au['stations_id'] = 'required';
            }
        }
        
        if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user_id = en_de_crypt($data['user_id'], 'd');
               
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

    public function user_store(Request $request) {

        $cuser = Auth::user();
        $validator_user_info = $this->validator_user_info($request->all());
        if ($validator_user_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_user_info->getMessageBag()->toArray()]);
        }else{
            
            //echo "<pre>"; print_r($request['stations_id']); die();
            if (!empty($_POST['user_id'])) {
                //print_r($request->all());  die();
                $user_id = en_de_crypt($_POST['user_id'], 'd');
                //$dob = date('Y-m-d', strtotime($request["dob"]));
                $user_data = User::findorfail($user_id);
                $user_data->name = $request['first_name'];
                $user_data->last_name = $request['last_name'];
                $user_data->email = $request['email'];
                $user_data->contact = $request['contact'];
               
                $user_data->type = $request['type'];
               
                //$user_data->dob = $dob;
                if ($user_data->update()) {
                    DB::table('users_roles')->where('user_id', '=', $user_data->id)->delete();
                    $type = $request['type'];
                    $user_role = Role::where('slug', $type)->first();
                    $user_data->roles()->attach($user_role);
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','redirect_url'=>'/admin/user-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updation failed!','redirect_url'=>'/admin/user-list']);
                }

            }else{

                $sr_number='';
                
               // $dob = date('Y-m-d', strtotime($request["dob"]));
                $user = new User();
                $user->name = $request['first_name'];
                $user->last_name = $request['last_name'];
                $user->email = $request['email'];
                $user->contact = $request['contact'];
                $user->password = bcrypt($request['password']);
                $user->type = $request['type'];

                if ($user->save()) {
                    $type = $request['type'];
                    $user_role = Role::where('slug', $type)->first();
                    $user->roles()->attach($user_role);
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'User has been added Sucessfully!','redirect_url'=>'/admin/user-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'User Insertion failed!','redirect_url'=>'/admin/user-list']);
                }
            }
        }
    }

 


    
    public function edit_user(Request $request)
    {
        //echo ">>";die;
        $user = Auth::user(); 
        $lp = 0;
        $role_name = "admin";
        if($user->hasRole('user')){
            $lp = UsersLoyalty::where('user_id','=',$user->id)->sum('loyalty_points');
            if(empty($lp)) $lp = 0;
            $role_name = "user";
        }
        return view('pages/admin/user/edit_user',compact('user','lp','role_name'));
    }

    public function edit_posadmin(Request $request)
    {
        //echo ">>";die;
        $user = Auth::user(); 
        $lp = 0;
        $role_name = "admin";
        if($user->hasRole('user')){
            $lp = UsersLoyalty::where('user_id','=',$user->id)->sum('loyalty_points');
            if(empty($lp)) $lp = 0;
            $role_name = "user";
        }
        return view('pages/admin/user/edit_user',compact('user','lp','role_name'));
    }

    public function store_edit_posadmin(Request $request)
    {

        if (!empty($request['user_id'])) {
            $validator_user = $this->validator_user($request->all());
            if ($validator_user->fails()) {
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Email Address already exist!!']);
            }
            $img_url = '';
            $user_id = en_de_crypt($request['user_id'], 'd');
            $user_data = User::findorfail($user_id);
/*
            if ($file = $request->hasFile('file')) {

              $file = $request->file('file');
              $fileName = $file->getClientOriginalName();
              $destinationPath = public_path() . '/user_profile/';
              $file->move($destinationPath, $fileName);
              $user_data->profile_img = $fileName;
              $img_url = asset('/user_profile/'.$fileName);
            }
*/
            $user_data->name = $request['name'];
            $user_data->email = $request['email'];
            $user_data->last_name = $request['last_name'];
            $user_data->contact = $request['contact'];

            if ($user_data->update()) {
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','img_url'=>$img_url]);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updation failed!']);
            }
        }

        return view('pages/admin/user/edit_posadmin');
    }

    public function change_posadmin_pass(Request $request)
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
      return view('pages/admin/user/edit_posadmin');
    }

    public function store_edit_user(Request $request)
    {

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
            $user_data->city = $request['city'];

            if ($user_data->update()) {
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','img_url'=>$img_url]);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updation failed!']);
            }
        }

        return view('pages/admin/user/edit_user');
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
