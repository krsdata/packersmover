<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\Exports\ExportUser;
use App\Role;
use App\Customer;
use App\Stations;
use App\CompanyUsers;
use App\UsersLoyalty;
use Mail;
use File;

//use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use App\Imglogo;
use Input, Redirect, Session, Response, DB;
class CustomersController extends Controller
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


    public function generate_pdf()
    {
        $pdf = new Pdf();
    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportUser, 'users.xlsx');
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
            $perPage = 10;
            $s_name=$request->get('search_input');
            $search_input = "";
            $customers = User::where('type','=','user')->orderBy('created_at', 'desc');
            if($s_name){
                $customers =  $customers->where('name', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }
            
            $customers = $customers->paginate($perPage);
            
            if ($request->ajax()){

                $view = view("pages.admin.customer.table_view",compact('search_input','customers','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{

                return view('pages.admin.customer.index',compact('search_input','customers','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        
        return view('home');
    }

    public function customer_create()
    {
        $user = Auth::user();
        return view('pages/admin/customer/create',compact('user'));
    }

    public function customer_update($id)
    {
        $user = Auth::user();
        $user_id = en_de_crypt($id, 'd');
        $customer_data = Customer::findorfail($user_id);
        return view('pages/admin/customer/create',compact('customer_data'));
    }


    public function customer_store(Request $request) {        
        $cuser = Auth::user();        
        $validator_user_info = $this->validator_user_info($request->all());
        if ($validator_user_info->fails()) {
           // echo json_encode($validator_user_info->getMessageBag()->toArray());
           // die;
            return redirect()->back()->withErrors(['email' => $validator_user_info->getMessageBag()->toArray()]);
           // return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_user_info->getMessageBag()->toArray()]);
        }else{
           
            if (!empty($_POST['id'])) {

                $user_id = en_de_crypt($_POST['id'], 'd');
                $user_data = Customer::findorfail($user_id);
                $user_data->name = $request['name'];
                $user_data->email = $request['email'];
                $user_data->mobile_no = $request['mobile_no'];
                //$user_data->dob = $dob;
                if ($user_data->update()) {  
                    return redirect()->back()->withSuccess('User has been updated Sucessfully!');                 
                   // return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','redirect_url'=>'/admin/customer-list']);
                }else{
                    return redirect()->back()->withErrors('Something went wrong!');
                }

            }else{
                
                $customer = new Customer();
                $customer->name = $request['name'];
                $customer->email = $request['email'];
                $customer->mobile_no = $request['mobile_no'];
                $customer->created_by = $cuser->id;
                if ($customer->save()) {
                    return redirect()->back()->withSuccess('User has been added Sucessfully!');
                   // return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'User has been added Sucessfully!','redirect_url'=>'/admin/customer-list']);
                }else{
                    return redirect()->back()->withErrors('Something went wrong!');
                }
            }
        }
    }

    protected function validator_user_info(array $data) {
        $cuser = Auth::user();        
        $au = [
            'name' => 'required',
            'mobile_no' => 'required',
            'email' => 'required|unique:customer,email'
        ];

        return Validator::make($data, $au);

    }
    
    public function edit_user(Request $request)
    {
        $user = Auth::user(); 
        $lp = 0;
        $role_name = "admin";
        if($user->hasRole('user')){
            $lp = UsersLoyalty::where('user_id','=',$user->id)->sum('loyalty_points');
            if(empty($lp)) $lp = 0;
            $role_name = "user";
        }
        return view('pages/admin/customer/edit_user',compact('user','lp','role_name'));
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

            if ($user_data->update()) {
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'User has been updated Sucessfully!','img_url'=>$img_url]);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'User Updation failed!']);
            }
        }

        return view('pages/admin/customer/edit_user');
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
