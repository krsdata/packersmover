<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Company;
use App\Subscription;
use App\Transaction;
use Mail;
use App\Mail\UserWelcome;
use Carbon\Carbon;
use Input, Redirect, Session, Response, DB;
class CompanyController extends Controller
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
        if($user->hasRole('admin') || $user->hasRole('manager')){
            $perPage = 8;
            $s_name=$request->get('search_input');
            $search_input = "";
            $datas = Company::orderBy('id', 'asc');
            if($s_name){
                $datas =  $datas->where('title', 'like', '%' . $s_name . '%');
                $search_input = $s_name;
            }
            $datas = $datas->paginate($perPage);

            if ($request->ajax()){

                $view = view("pages.admin.company.table_view",compact('search_input','datas','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                return response()->json(['html'=>$view]);

            }else{

                return view('pages.admin.company.index',compact('search_input','datas','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            }
        }
        return view('home');
    }

    public function create()
    {
        $user = Auth::user();
        $subscriptions = Subscription::where("status" , "1")->get();
        $default = "default.png";
        return view('pages.admin.company.create',compact('user','default', 'subscriptions'));
    }

    public function update($id)
    {
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $data = Company::findorfail($id);
        $udata = User::findorfail($data->user_id);
        $data->email = $udata->email;
        $subscriptions = Subscription::where("status" , "1")->get();
        $default = "default.png";
        if(isset($data->image) && !empty($data->image)){
            $default = $data->image;
        }
        return view('pages.admin.company.create',compact('data','default','subscriptions'));
    }

    protected function cust_validator(array $data) {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            return Validator::make($data, [
                        'title' => 'required|unique:company,title,'. $id,
                            ], ['title.required' => 'Enter valid company name !!',
                            'title.unique' => 'Company name already exist!!',]);
        } else {

            return Validator::make($data, [
                        'send_to' => 'required|string|'
                      ], ['send_to.required' => 'Company name is required !!',
                            'title.unique' => 'Company name already exist!!',]);
        }
    }

    protected function validator_info(array $data) {
        $cuser = Auth::user();
        $au = [
            'title' => 'required',
            'no_of_emp' => 'required',
            'address' => 'required',
            'description' => 'required',
            'subscription_id' => 'required',
            'back_color' => 'required',
            'text_color' => 'required',
            'status' => 'required',
            'image' => 'required',
            'slug' => 'required',
        ];

        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $userid = en_de_crypt($data['userid'], 'd');
            $au['title'] = 'required|unique:company,title,' . $id;
            //$au['slug'] = 'required|unique:company,slug,' . $id;
            $au['email']  = 'required|unique:users,email,'. $userid;
           

            if(!empty($data['password'])){
                $au['password'] = 'required_with:password_confirmation|same:password_confirmation|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
            }

            return Validator::make($data, $au, ['title.required' => 'Enter valid company name !!',
            'title.unique' => 'Company name already exist!!','slug.required' => 'Enter valid company url !!']);
           // 'slug.unique' => 'Company url already exist!!','email.unique' => 'Email address already exist!!',]

        } else {
            $au['title'] = 'required|string|unique:company';
           // $au['slug'] = 'required|string|unique:company';
            $au[ 'email'] = 'required|string|email|max:255|unique:users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            $au['password'] = 'required_with:password_confirmation|same:password_confirmation|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
            return Validator::make($data, $au, ['title.required' => 'Enter valid company name !!',
            'title.unique' => 'Company name already exist!!','slug.required' => 'Enter valid company url !!',
            //'slug.unique' => 'Company url already exist!!',
            'email.required' => 'Enter valid email address!!',
            'email.unique' => 'Email address already exist!!',
            'email.email' => 'Enter valid email address!!',
            'email.regex' => 'Enter valid email address!!']);
        }
    }

    public function store(Request $request) {
        $delimiter = '-';
        $cuser = Auth::user();
        $cuser_id = $cuser->id;
        $request['slug'] = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $request['slug']))))), $delimiter));

        $validator_info = $this->validator_info($request->all());
        
        if (!empty($_POST['id'])) {
            $id = en_de_crypt($_POST['id'], 'd');
            $userid = en_de_crypt($_POST['userid'], 'd');
            try {
                $user = User::findorfail($userid);
                $user->email = $request['email'];
                $user->update();
            } catch (\Throwable $th) {
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Email address already exist!','redirect_url'=>'admin/company-list']);
            }

            $data = Company::findorfail($id);
            //print_r($data); die();
            $data->title = $request['title'];
            $data->no_of_emp = $request['no_of_emp'];
            $data->address = $request['address'];
            $data->description = $request['description'];
            $data->subscription_id = $request['subscription_id'];
            $data->back_color = $request['back_color'];
            $data->text_color = $request['text_color'];
            if($cuser->hasRole('admin')){
                //echo $request['status']; die();    
                $data->status = $request['status'];
            }
            
            $data->image = $request['image'];
            $data->tac_en = $request['tac_en'];
            $data->tac_ar = $request['tac_ar'];
            if ($data->update()) {
                if($data->slug){

                    $username = env("DB_USERNAME", "root");
                    $password = env("DB_PASSWORD", "password");  
                    $conn = mysqli_connect("localhost", $username, $password);
                    $conn->select_db($data->slug);
                    //$sql = "UPDATE `company` SET `subscription_id` = '".$request['subscription_id']."', status= '".$request['status']."';";
                    $sql = "UPDATE `company` SET `status` = '".$request['status']."';";
                    mysqli_query($conn, $sql);
                    
                }
                return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Company has been updated successfully!','redirect_url'=>'/admin/company-list']);
            }else{
                return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Company Updation failed!','redirect_url'=>'/admin/company-list']);
            }

        }else{

             

            if ($validator_info->fails()) {
                return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_info->getMessageBag()->toArray()]);
            }else{
                $if_exist_slug=Company::where('slug',$request['slug'])->count();
                if($if_exist_slug > 0){
                    
                  return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'company url already exist','redirect_url'=>'/admin/company-list']);
                }

                $type = "company";
                $result = $this->generateCompany($request->all());
                $user = new User();
                $user->name = $request['title'];
                $user->last_name = "";
                $user->email = $request['email'];
                $user->contact = "";
                $user->active = "1";
                $user->type = $type;
                $user->mail_notify = "1";
                $user->password = bcrypt($request['password']);
                $user->save();
                if ($result) {
                    // below line commented bcoz user login restriction by user role
                    // $user_role = Role::where('slug', $type)->first();
                    //$user->roles()->attach($user_role);
                    $user_id = $user->id;
                    $data = new Company();
                    $data->tac_en = $request['tac_en'];
                    $data->tac_ar = $request['tac_ar'];
                    $data->title = $request['title'];
                    $data->no_of_emp = $request['no_of_emp'];
                    $data->address = $request['address'];
                    $data->description = $request['description'];
                    $data->subscription_id = $request['subscription_id'];
                    $data->back_color = $request['back_color'];
                    $data->text_color = $request['text_color'];
                    $data->status = $request['status'];
                    $data->image = $request['image'];
                    $data->user_id = $user_id;
                    $data->created_by = $cuser_id;
                    $data->slug =$request['slug'];
                    $duration='0';
                    $s_typpe=''; $s_price='';
                    if ($data->save()) {
                        //print_r($data); die();
                        $update_company_id=User::where('id','=',$user->id)->update(['company_id' => $data->id]);
                        //transaction code
                        $subscription_details = Subscription::where([["status" ,'=', "1"],["id",'=' ,$data->subscription_id]])->get();
                        if(!empty($subscription_details)){
                          foreach ($subscription_details as $key => $value) {
                             $duration=$value['s_duration'];
                             $s_typpe=$value['s_type'];
                             $s_price=$value['price'];
                          }
                        }

                        $username = env("DB_USERNAME", "root");
                        $password = env("DB_PASSWORD", "password");
                        $conn = mysqli_connect("localhost", $username, $password);
                        $conn->select_db($data->slug);
                        $sql = "UPDATE `subscription` SET `s_type` = '".$s_typpe."';";
                        mysqli_query($conn, $sql);
                        $startDate = date('Y-m-d');
                        if($duration!='0'){
                            $expiry_date=Carbon::createFromFormat('Y-m-d', $startDate)->addMonths($duration)->format('Y-m-d');
                            $transaction = new Transaction();
                            $transaction->company_id = $data->id;
                            $transaction->subscription_type = $data->subscription_id;
                            $transaction->expiry_date = $expiry_date;
                            $transaction->price = $s_price;
                            $transaction->purchase_date =  date('Y-m-d');
                            $transaction->created_by = $user_id;
                            $transaction->save();
                            $update_company=Company::where('id', '=',$data->id)->update(['expiry_date' => $expiry_date]);
                            $sql = "UPDATE `company` SET `expiry_date` = '".$expiry_date."', `status` = '1';";
                            mysqli_query($conn, $sql);
                        }

                        $mdata = new \stdClass();
                        $mdata->message = "<h1>Hi ".$request['title']."!,</h1>";
                        $mdata->message .="<p>Thank you for creating your account at Visitor Point. Your account details are as below</p>";
                        $mdata->message .= "<p>Username : ".$request['email']."</p>";
                        $mdata->message .= "<p>Password : ".$request['password']."</p>";
                        $mdata->message .= "<p>To Sign in to your account, Please click below button.</p>";
                        $mdata->btn_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/".$data->slug;
                        $mdata->btn_color = "primary";
                        $mdata->btn_txt = "Sign In";
                        $mdata->sub = "<p class='sub'>If you have any questions regarding your account. Please contact administrator at Visitor Point.</p>";
                        $mdata->subject ="Welcome to visitor point!!";
                        Mail::to($user->email)->send(new UserWelcome($mdata));
                        return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Company has been added successfully!','redirect_url'=>'/admin/company-list']);
                    }else{
                        return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Company Insertion failed!','redirect_url'=>'/admin/company-list']);
                    }
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Company Insertion failed!','redirect_url'=>'/admin/company-list']);
                }
            }
        }// validate
    }


    /**
     * generate new company folder with database function
     *
     * @param [array] $data
     * @return void
     */
    public function generateCompany($data){
        $url = $data['slug'];
        $result = $this->duplicate('vp_i', $url, $data);
        if ($result)
		{   $root = $_SERVER['DOCUMENT_ROOT'];
            $zip = new \ZipArchive;
            if ($zip->open($root.'/vp/vp.zip') === TRUE) {
                $zip->extractTo($root."/".$url."/");
                $zip->close();
                $sourcePath = public_path().'/upload_image/'.$data['image'];
                $destPath = $root."/".$url."/public/upload_image/".$data['image'];
                copy($sourcePath, $destPath);
            } else {
                return false;
            }
            $file = $root.'/'.$url.'/.env';
            $username = env("DB_USERNAME", "root");
            $password = env("DB_PASSWORD", "password");
            chmod($file,0777);
            $appurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/".$url;
            file_put_contents($file,str_replace('APP_URL=http://localhost/vp','APP_URL='.$appurl,file_get_contents($file)));
            file_put_contents($file,str_replace('DB_DATABASE=vp','DB_DATABASE='.$url,file_get_contents($file)));
            file_put_contents($file,str_replace('DB_USERNAME=root','DB_USERNAME='.$username,file_get_contents($file)));
            file_put_contents($file,str_replace('DB_PASSWORD=root','DB_PASSWORD='.$password,file_get_contents($file)));
            file_put_contents($file,str_replace('DB_PASSWORD=password','DB_PASSWORD='.$password,file_get_contents($file)));
            
            $file1 = $root.'/'.$url.'/bootstrap/cache/config.php';
            if (file_exists($file1)) {
                chmod($file1,0777);
                file_put_contents($file1,str_replace("'database' => 'vp'","'database' => '".$url."'",file_get_contents($file1)));
                file_put_contents($file1,str_replace("'username' => 'root'","'username' => '".$username."'",file_get_contents($file1)));
                file_put_contents($file1,str_replace("'password' => 'root'","'password' => '".$password."'",file_get_contents($file1)));
                file_put_contents($file1,str_replace("'url' => 'http://localhost/vp'","'url' => '".$appurl."'",file_get_contents($file1)));
                file_put_contents($file1,str_replace("'url' => 'http://vp.appristine.in/cpanel'","'url' => '".$appurl."'",file_get_contents($file1)));
            }
            $file2 = $root.'/'.$url.'/public/appointment.js';
            chmod($file2,0777);
            $apiurl = $appurl."/api";
            file_put_contents($file2,str_replace('http://vp.appristine.in/api',$apiurl,file_get_contents($file2)));
            $this->recursiveChmod($root.'/'.$url);
            return true;
        }else{
            return false;
        }
    }

    /**
     * duplicate database function
     *
     * @param [string] $originalDB
     * @param [string] $newDB
     * @param [array] $data
     * @return void
     */
	public function duplicate($originalDB, $newDB, $data)
	{
        
        $username = env("DB_USERNAME", "root");
        $password = env("DB_PASSWORD", "password");
        $conn = mysqli_connect("localhost", $username, $password);
        
		if (!$conn)
		{
			return false;
        }
        
        // Get all table names in originalDB
        $conn->select_db($originalDB);
		$getTables =  mysqli_query($conn,"SHOW TABLES");
		$originalDBs = [];
		while($row = mysqli_fetch_assoc( $getTables ))
		{
			$originalDBs[] = $row['Tables_in_'.$originalDB];
		}
		if (mysqli_query($conn, "CREATE DATABASE `$newDB`") === TRUE)
		{
			$conn->select_db($newDB);
			foreach( $originalDBs as $tab )
			{
				mysqli_query($conn, "CREATE TABLE $tab LIKE ".$originalDB.".".$tab);
				mysqli_query($conn, "INSERT INTO $tab SELECT * FROM ".$originalDB.".".$tab);
			}
            // Create company in new DB
            $user_id = "1";
            $sql1 = "INSERT INTO `company` ( `subscription_id`, `image`, `status`, `title`, `user_id`, `address`, `description`, `no_of_emp`, `tac_en`, `tac_ar`, `text_color`, `back_color`, `created_by`, `created_at`, `updated_at`, `expiry_date`, `slug`) VALUES ('".$data['subscription_id']."', '".$data['image']."', '".$data['status']."', '".$data['title']."', '".$user_id."', '".$data['address']."', '".$data['description']."', '".$data['no_of_emp']."', '".$data['tac_en']."', '".$data['tac_ar']."', '".$data['text_color']."', '".$data['back_color']."', '".$user_id."', NULL, NULL, NULL,'".$data['slug']."');";
            mysqli_query($conn, $sql1);
            $company_id = mysqli_insert_id($conn);
            // Create user in new DB
            $sql2 = "INSERT INTO `users` (`company_id`, `name`, `last_name`, `contact`, `type`, `active`, `mail_notify`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('".$company_id."', '".$data['title']."', '', '', 'company', '1', '1', '".$data['email']."', NULL, '".bcrypt($data['password'])."', NULL, NULL, NULL);";
			mysqli_query($conn, $sql2);
            $user_id = mysqli_insert_id($conn);
			// Create role in new DB
			$sql3 = "INSERT INTO `users_roles` (`user_id`,`role_id`) VALUES ($user_id, 8) ";
			mysqli_query($conn, $sql3);
		} else {
			return false;
		}
		mysqli_close($conn);
		return true;
    }
    
    /**
     * recursiveChmod function
     *
     * @param [type] $path
     * @param integer $filePerm
     * @param integer $dirPerm
     * @return void
     */
    function recursiveChmod($path, $filePerm=0755, $dirPerm=0777) {
        // Check if the path exists
        if (!file_exists($path)) {
            return(false);
        }
 
        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            chmod($path, $filePerm);
 
        // If this is a directory...
        } elseif (is_dir($path)) {
            // Then get an array of the contents
            $foldersAndFiles = scandir($path);
 
            // Remove "." and ".." from the list
            $entries = array_slice($foldersAndFiles, 2);
 
            // Parse every result...
            foreach ($entries as $entry) {
                // And call this function again recursively, with the same permissions
                $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
            }
 
            // When we are done with the contents of the directory, we chmod the directory itself
            chmod($path, $dirPerm);
        }
 
        // Everything seemed to work out well, return true
        return(true);
    }
}
