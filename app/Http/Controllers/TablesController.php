<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\User;
use App\Role;
use App\Tables;
use App\Membership;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Input, Redirect, Session, Response, DB;
class TablesController extends Controller
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
        $data=Tables::get();
        \Session::put(['nav_data' => $data]);
         $user = Auth::user();
         $user_id = $user->id;
         if($user->hasRole('admin')){
             $perPage = 8;
             $search_input=$request->get('search_input');
             $datas = Tables::orderBy('id', 'asc');    
            if($search_input){
                 $datas =  $datas->where('table_name', 'like', '%' . $search_input . '%');
             }
             $datas = $datas->paginate($perPage);
             if ($request->ajax()){
                 $view = view("pages.admin.table.table_view",compact('search_input','datas','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
                 return response()->json(['html'=>$view]);
             }else{

                 return view('pages.admin.table.index',compact('search_input','datas','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
             }
         }
         return view('home');
     }

     public function create(){
       $user = Auth::user();
       $default = "default.png";
       return view('pages.admin.table.create',compact('user','default'));
     }


     public function update($id){
        if($id){
           $user = Auth::user();
           $id = en_de_crypt($id, 'd');
           $data = Tables::findorfail($id);
           $default = "default.png";
           return view('pages.admin.table.create',compact('data','default'));
        }
      }

      /**
       * store function
       *
       * @param Request $request
       * @return void
       */
     public function store(Request $request)
     {
        $cuser = Auth::user();
        $cuser_id = $cuser->id; 
        $connection = mysqli_connect('localhost','root','','schl');
        $root = $_SERVER['DOCUMENT_ROOT'];
        $v_folder=$root.'/schol/resources/views/pages/admin/'.$request['table_name'];
        //$validator_info = $this->validator_info($request->all());
        $Is_Fillable=""; $size='';
        if($request['Is_Fillable']){
            $Is_Fillable="NOT NULL";
        }else{
                  $Is_Fillable="NULL";
        }
        if($request['column_type']=='varchar'){
          $size="(255)";
        }else{
          $size=" ";
        }
        
        if (!empty($_POST['id'])) {
          $request['id'];
          $request['column_name']; 
          $request['column_type'];
          $u_query="ALTER TABLE ".$request['table_name']." ADD (".$request['column_name']." ".$request['column_type'] . $size . $Is_Fillable .")";

          //echo $u_query; die();

          if(mysqli_query($connection,$u_query)){ 
            $m_file_name=$root.'/schol/app/'.$request['table_name'].'.php';
            $wdata = file_get_contents($m_file_name);
            file_put_contents($m_file_name, ' ');
            $columns = Schema::getColumnListing($request['table_name']);
            $ccolumns = implode(', ', $columns); 
            $n_data='<?php
            namespace App;
            use Illuminate\Database\Eloquent\Model;
            use App\Traits\Encryptable;

              class '.ucfirst($request["table_name"]).' extends Model
              {
                  
                  protected $table = "'.$request['table_name'].'";
                  public $primaryKey = "id";
                  protected $fillable = [
                      "'.$ccolumns.'"
                  ];


              }';
            file_put_contents($m_file_name, $n_data);
            return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Table Updated successfully!','redirect_url'=>'/admin/table-list']);

            


   
          }
        }else{
            $new_table = new Tables();
            $new_table->table_name = $request['table_name'];
            $tab_nme=$request['table_name'];
            // if($request['Is_Fillable']){
            //   $Is_Fillable="NOT NULL";
            // }else{
            //         $Is_Fillable="NULL";
            // }
            
            $datas='';
            if($new_table->save()){
                // $nm = env('DB_USERNAME'); 
                // $psw = env('DB_PASSWORD'); 
                
                $query="CREATE TABLE ".$request['table_name']." (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    updated_at Timestamp  NULL,
                    created_at Timestamp  NULL,
                    ".$request['column_name']." "."".$request['column_type']."(50) ".$Is_Fillable."
                    )";
                if(mysqli_query($connection,$query)){
                    // create view
                    
                    if (!file_exists($v_folder)) {
                        mkdir($v_folder, 0777, true);
                    }
                    
                    $t_file_name=$v_folder."/"."table_view.blade.txt";
                    $thandle = fopen($t_file_name, 'w') or die('Cannot open file:  '.$t_file_name); //implicitly creates file 
                    $tdata='<div class="col-sm-12 px-2" style="display:inline-block;">@if(!$datas->isEmpty())<p class="float-right">  {{ __("a.Displaying") }} {{$datas->firstItem()}}-{{ $datas->lastItem() }}  {{ __("a.of") }} {{$datas->total()}}  {{ __("a.Records") }}</p>@else
                    <p class="float-right">  {{ __("a.Displaying") }} {{" 0 "}}-{{ "0" }}  {{ __("a.of") }}  {{$datas->total()}} {{ __("a.Records") }} </p>
                    @endif
                  </div>
                  <div class="grid-views-sections">
                    <div class="row m-0">
                      @if(!$datas->isEmpty())
                      @foreach($datas as $list)
                      <div class="col-sm-3" id="{{en_de_crypt($list->id,"e")}}">
                        <div class="card-body text-center company-listing-gridview">
                        @if(isset($columns)) 
                        @foreach ($columns as $column)
                        @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                        <h4 style="font-weight: normal;"> {{$column}}: {{$list->$column}}</h4>
                        @endif
                        @endforeach
                        @endif
                          <div class="vp-company-buttonsview">
                            <a href="{{url('."'".'/admin/'.$tab_nme.'/edit/'."'".' . en_de_crypt($list->id,'."'".'e'."'".') ) }}"><button class="btn btn-primary btn-xs"><span class="cust-box-icon-add"> <i class="la la-pencil-alt  cust-box-icon"></i>  </span> </button></a>
                            <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($list->id ,'."'".'e'."'".')}}" data-model="Transaction"><span class="cust-box-icon-add"> <i class="la la-trash-alt  cust-box-icon"></i> </span> </button>
                          </div>
                        </div>
                      </div>
                      @endforeach
                      {!! $datas->onEachSide(1)->links("ajax_pagination") !!}
                      @else
                      <div class="card card-inverse-info col-md-12" id="context-menu-simple">
                        <div class="card-body">
                          <p class="card-text"> {{ __("a.No_Data_Found") }}  </p>
                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                  
                  <div class="table_add_class">
                    @if(!$datas->isEmpty())
                    <div class=" table-responsive">
                      <table class="rwd-tables table table-striped">
                        <thead>
                        <tr>
                        @if(isset($columns)) 
                        @foreach ($columns as $column)
                        @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                          <th> {{$column}}  </th>
                        @endif
                        @endforeach
                        @endif  
                        <th> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($datas as $list)
                          <tr id="{{en_de_crypt($list->id,"e")}}">
                          @if(isset($columns)) 
                          @foreach ($columns as $column)
                          @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                            <td>  <div >{{$list->$column}} </div> </td>
                          @endif
                          @endforeach
                          @endif
                            <td class="actions" data-th="">
                            <a href="{{url("/admin/'.$request['table_name'].'/edit/" . en_de_crypt($list->id,"e") ) }}"><button class="btn btn-primary btn-xs"><span class="cust-box-icon-add"> <i class="la la-pencil-alt  cust-box-icon"></i>  </span> </button></a>
                            <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($list->id ,"e")}}" data-model="Transaction"><span class="cust-box-icon-add"> <i class="la la-trash-alt  cust-box-icon"></i> </span> </button>
                            
                  
                            </td>
                          </tr>  
                          @endforeach
                        </tbody>
                      </table>
                      {!! $datas->onEachSide(1)->links("ajax_pagination") !!}
                    </div>
                  
                    @else
                    <div class="row">
                      <div class="card card-inverse-info col-md-12" id="context-menu-simple">
                        <div class="card-body">
                          <p class="card-text"> {{ __("a.No_Data_Found") }} </p>
                        </div>
                      </div>
                    </div>
                    @endif
                  
                  </div>';
                  fwrite($thandle, $tdata);
                  fclose($thandle);
                  $i_file_name=$v_folder."/"."index.blade.txt";
                  $ihandle = fopen($i_file_name, 'w') or die('Cannot open file:  '.$i_file_name); //implicitly creates file 
                  $idata = '@extends("layouts.dashboard")
                  @section("dashcontent")
                  @php
                    $lang =  \Session::get("lang");
                    if(empty($lang)){
                      $lang = "en";
                    }
                    app()->setLocale($lang);
                    @endphp
                   <div class="row">
                              <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                  <div class="card-body">
                  
                                    <div class="row">
                                          <div class="col-sm-12">
                                              <div class="company-common-header_view col-sm-6">
                                              <span class="company-list-cust-box"> <i class="la la-money-bill-alt cust-box-icon"></i> </span>
                                              <h4 class="cust-card-title">
                                                '.$request['table_name'].'
                                              </h4>
                                              </div>
                                              <div class="mx-2 add-button-view_ffg  float-right">
                                                  <a href="{{route("'.$tab_nme.'_create")}}" ><button class="btn btn-primary border-radius-05"><span class="cust-box-icon-add"> <i class="la la-plus cust-box-icon"></i> </span>  {{ __("a.Add") }} </button></a>
                                              </div>
                                              <div class="add-button-view_greedview float-right">
                                                  <ul>
                                                      <li class="active first-grid-view"> <span> <i class="mdi10 mdi-view-grid  la la-th-large menu-icon"></i></span> </li>
                                                      <li class="second-table-view"> <span> <i class="mdi10 mdi-view-sequential las la-list  menu-icon"></i></span> </li>
                                                  </ul>
                                              </div>
                  
                  
                                          </div>
                                          <div class="col-sm-12 mt-2">
                                              <div class="common-view-header-view-search px-2">
                  
                                                  <div class="m-0 add-button-view_search">
                                                          <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                                                                  {{ csrf_field() }}
                  
                                                      <div class="form-group d-flex custom-search-view-place">
                                                          <input value="{{$search_input}}" type="text" name="search_input" id="search_input" class="form-control" placeholder=" {{ __("a.Search_Subscription") }} " >
                                                          <button   class="btn btn-primary common_search_filter" ><i class="la la-search"></i></button>
                                                      </div>
                                                     </form>
                                                  </div>
                                              </div>
                                          </div>
                                        </div>
                                      <div id="table_filter_view">
                                          @include("pages.admin.'.$tab_nme.'.table_view")
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                  @endsection
                  @section("scripts")
                  <script type="text/javascript">
                  </script>
                  @stop
                  ';
                     
                    fwrite($ihandle, $idata);
                    fclose($ihandle);
                    $cr_file_name=$v_folder."/"."create.blade.txt";
                    $vhandle = fopen($cr_file_name, 'w') or die('Cannot open file:  '.$cr_file_name); //implicitly creates file
                    $vcdata = '@extends("layouts.dashboard")
                    @section("dashcontent")
                    @php
                      $lang =  \Session::get("lang");
                      if(empty($lang)){
                        $lang = "en";
                      }
                      app()->setLocale($lang);
                      @endphp
                    <div class="row">
                      <div class="col-12 grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <div class="transaction-common-header_view">
                              <span class="transaction-list-cust-box"> <i class="la la-money-bill-alt cust-box-icon"></i> </span>
                            <h4 class="cust-card-title"> @if(isset($datas))  Update '.$request['table_name'].'   @else  Add New '.$request['table_name'].'  @endif   </h4>
                            <span class="company-list-cust-back"> <a href=""> <i class="la la-arrow-left cust-box-icon float-right"></i> </a> </span>
                            </div>
                              <form id="addtrn" class="form-sample" method="POST" action = "{{route("'.$tab_nme.'_stores")}}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    
                              <input type="hidden" name="id" id="id" value="@if(isset($datas)){{en_de_crypt($datas->id,"e")}}@endif">
                              <div class="col-sm-12">&nbsp;
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                @if(isset($columns)) 
                                @foreach ($columns as $column)
                                @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"> {{$column}} </label>
                                    <div class="col-sm-8">
                                    <input type="text" name="{{$column}}" id="{{$column}}" class="form-control {{$column}}"
                                    data-msg-required="Required field" value="@if(isset($datas)){{$datas->$column}}@endif"  required />
                                    <label id="{{$column}}-error" class="error" for="{{$column}}"></label>
                                    <label id="{{$column}}-server" class="server-error" for="{{$column}}"></label>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                                </div>
                            </div>
                             <div class="row">
                                    <div class="col-md-12">
                                      <button type="reset" value="reset" id="create_action_reset" style="display:none"></button>
                                      <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='."'".'fa fa-spinner fa-spin '."'".'></i> Processing Order"> @if(!isset($data->id)) {{ __("a.Submit") }} @else  {{ __("a.Update") }} @endif </button>
                                    </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <script type="text/javascript">
                    $(document).ready(function () {
                        
                    })
                    </script>
                    @endsection ';
                    fwrite($vhandle, $vcdata);
                    fclose($vhandle);
                    rename($cr_file_name, $root.'/schol/resources/views/pages/admin/'.$request['table_name']."/"."create.blade.php");
                    rename($i_file_name, $root.'/schol/resources/views/pages/admin/'.$request['table_name']."/"."index.blade.php");
                    rename($t_file_name, $root.'/schol/resources/views/pages/admin/'.$request['table_name']."/"."table_view.blade.php");
                    // create controller
                    $c_file_name=$root.'/schol/app/Http/Controllers/'.ucfirst($request['table_name'].'Controller.txt');
                    $handle = fopen($c_file_name, 'w') or die('Cannot open file:  '.$c_file_name); //implicitly creates file 
                    $data = '<?php 
                    namespace App\Http\Controllers;
                    use Illuminate\Http\Request;
                    use Illuminate\Support\Facades\Schema;
                    use App\Http\Requests;
                    use App\Http\Controllers\Controller;
                    use Illuminate\Support\Facades\Validator;
                    use Illuminate\Support\Facades\Auth;
                    use Mail;
                    use App\User;
                    use App\Role;
                    use App\Tables;
                    use Carbon\Carbon;
                    use App\d_model;
                    use Input, Redirect, Session, Response, DB;
                    
                    
                    
                    
                     class '.ucfirst($request['table_name'].'Controller').' extends Controller { 
                    
                        public function __construct(){
                            $this->middleware('."'auth'".');
                         }
                                            
                            public function index(Request $request){
                              $ddata=Tables::get();
                              \Session::put(["nav_data" => $ddata]);
                              $columns = Schema::getColumnListing('."'".$request["table_name"]."'".');
                                $user = Auth::user();
                                $user_id = $user->id;
                                if($user->hasRole("admin")){
                                  $perPage = 8;
                                  $search_input=$request->get("search_input");
                                  $datas = '.ucfirst($request["table_name"]).'::orderBy("id", "asc");    
                                 if($search_input){
                                      $datas =  $datas->where("table_name", "like", "%" . $search_input . "%");
                                  }
                                  $datas = $datas->paginate($perPage);
                                  if ($request->ajax()){
                                      $view = view("pages.admin.'.$request['table_name'].'.table_view",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage)->render();
                                      return response()->json(["html"=>$view]);
                                  }else{
                     
                                      return view("pages.admin.'.$request['table_name'].'.index",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage);
                                  }
                              }
                              return view("home");
                            }
                    
                            public function create(){
                                $user = Auth::user();
                                $default = "default.png";
                                $search_input = "";
                                $columns = Schema::getColumnListing('."'".$request['table_name']."'".');
                                return view("pages.admin.'.$request['table_name'].'.create",compact("user","default","search_input","columns"));
                              }
                    
                              public function store(Request $request){
                                
                                if (!empty($_POST["id"])) {
                                     if($request->all()){
                                      $datas=$request->all();
                                      array_shift($datas);
                                      $id = en_de_crypt($_POST["id"], "d");
                                      $details = '.$request['table_name'].'::findorfail($id);
                                      foreach($datas as $key => $data){
                                         $details->$key=$data;
                                      }
                                      $details->update();
                                      return response()->json(["success" => TRUE,"op"=>"update","msg_type"=>"success","msg"=>"'.$request['table_name'].' updated successfully!","redirect_url"=>"/admin/table-list"]);

                                     }
                                  }else{
                                    $m_obj=new '.$request['table_name'].'();
                                    $m_obj->fill($request->all());
                                    $m_obj->save();
                                    return response()->json(["success" => TRUE,"op"=>"create","msg_type"=>"success","msg"=>"'.$request['table_name'].' created successfully!","redirect_url"=>"/admin/table-list"]);
                                  }
                                  

                              }

                              public function update($id){
                                if($id){
                                   $user = Auth::user();
                                   $id = en_de_crypt($id, "d");
                                   $datas = '.$request['table_name'].'::findorfail($id);
                                   //echo "<pre>" ; print_r($datas); die();
                                   $default = "default.png";
                                   $columns = Schema::getColumnListing('."'".$request['table_name']."'".');
                                   return view("pages.admin.'.$request['table_name'].'.create",compact("datas","default","columns"));
                                }
                              }
                    }
                     ?>';
                     
                    fwrite($handle, $data);
                    //read the entire string
                     $strm=file_get_contents($c_file_name);

                     //replace something in the file string - this is a VERY simple example
                     $strm=str_replace("d_model",$request['table_name'],$strm);
 
                     //write the entire string
                     file_put_contents($c_file_name, $strm);
                    fclose($handle); 
                    rename($c_file_name, $root.'/schol/app/Http/Controllers/'.ucfirst($request['table_name'].'Controller.php'));
                    // model create 
                    $m_file_name=$root.'/schol/app/'.ucfirst($request['table_name'].'.php');
                    $m_handle = fopen($m_file_name, 'w') or die('Cannot open file:  '.$m_file_name); //implicitly creates file
                    $m_datas='<?php
                        namespace App;
                        use Illuminate\Database\Eloquent\Model;
                        use App\Traits\Encryptable;

                          class '.ucfirst($request["table_name"]).' extends Model
                          {
                              
                              protected $table = "'.$request['table_name'].'";
                              public $primaryKey = "id";
                              protected $fillable = [
                                  "'.$request["column_name"].'"
                              ];

    
                          }';
                    fwrite($m_handle, $m_datas);
                    fclose($m_handle);
                    // web
                    $w_file_name=$root.'/schol/routes/web.php';
                    $wdata = file_get_contents($w_file_name);
                    $wdata .='Route::get("/admin/'.$request['table_name'].'-list", "'.$request['table_name'].'Controller@index")->name("'.$request['table_name'].'");
                            Route::get("/admin/'.$request['table_name'].'-create", "'.$request['table_name'].'Controller@create")->name("'.$request['table_name'].'_create"); 
                            Route::post("/admin/'.$request['table_name'].'/'.$request['table_name'].'_stores", "'.$request['table_name'].'Controller@store")->name("'.$request['table_name'].'_stores");
                            Route::get("/admin/'.$request['table_name'].'/edit/{id}", "'.$request['table_name'].'Controller@update")->name("'.$request['table_name'].'_update");  
                    ';
                    
                    file_put_contents($w_file_name, $wdata);
                    // fwrite($whandle, $wdata);
                    // fclose($whandle);
                    
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Table created successfully!','redirect_url'=>'/admin/table-list']);

                 }//else{
                //     return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Table creation Failed!','redirect_url'=>'/admin/table-list']);
                // }    
            }
        }
    }

    
     /**
      * get_expiry function
      *
      * @return void
      */
     public function get_expiry(){
       $duration='0';
       $sub_id=$_GET['subscription_id'];
       $startDate=$_GET['startDate'];
       $company_id=$_GET['selected_company_id'];
       if(!empty($sub_id) && !empty($startDate)){
         $subscription_details = Subscription::where([["status" ,'=', "1"],["id",'=' ,$sub_id]])->get();
         if(!empty($subscription_details)){
           foreach ($subscription_details as $key => $value) {
               $duration=$value['s_duration'];
           }
         }
         if($duration!='0'){
             $endDate=Carbon::createFromFormat('Y-m-d', $startDate)->addMonths($duration)->format('Y-m-d');
             if($endDate){
                  return response()->json(['success' => TRUE,'end_date'=>$endDate,'msg_type'=>'success']);
             }else{
                  return response()->json(['success' => FALSE,'end_date'=>'','msg_type'=>'error']);
             }
         }
       }
     }

}
