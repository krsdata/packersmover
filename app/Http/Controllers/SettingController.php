<?php 
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
                    use App\setting;
                    use Input, Redirect, Session, Response, DB;
                    
                    
                    
                    
                     class SettingController extends Controller { 
                    
                        public function __construct(){
                            $this->middleware('auth');
                         }
                                            
                            public function index(Request $request){
                              $ddata=Tables::get();
                              \Session::put(["nav_data" => $ddata]);
                              $columns = Schema::getColumnListing('setting');
                                $user = Auth::user();
                                $user_id = $user->id;
                                if($user->hasRole("admin")){
                                  $perPage = 8;
                                  $search_input=$request->get("search_input");
                                  $datas = Setting::orderBy("id", "asc");    
                                 if($search_input){
                                      $datas =  $datas->where("table_name", "like", "%" . $search_input . "%");
                                  }
                                  $datas = $datas->paginate($perPage);
                                  if ($request->ajax()){
                                      $view = view("pages.admin.setting.table_view",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage)->render();
                                      return response()->json(["html"=>$view]);
                                  }else{
                     
                                      return view("pages.admin.setting.index",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage);
                                  }
                              }
                              return view("home");
                            }
                    
                            public function create(){
                                $user = Auth::user();
                                $default = "default.png";
                                $search_input = "";
                                $columns = Schema::getColumnListing('setting');
                                return view("pages.admin.setting.create",compact("user","default","search_input","columns"));
                              }
                    
                              public function store(Request $request){
                                
                                if (!empty($_POST["id"])) {
                                     if($request->all()){
                                      $datas=$request->all();
                                      array_shift($datas);
                                      $columns = Schema::getColumnListing('setting');
                                      array_shift($columns);
                                      $id = en_de_crypt($_POST["id"], "d");
                                      $details = setting::findorfail($id);
                                      foreach($datas as $key => $data){
                                        $details->$key=$data;
                                      }
                                      
                                      $details->update();
                                      return response()->json(["success" => TRUE,"op"=>"update","msg_type"=>"success","msg"=>"setting updated successfully!","redirect_url"=>"/admin/table-list"]);

                                     }
                                  }else{
                                    $m_obj=new setting();
                                    $m_obj->fill($request->all());
                                    
                                    $m_obj->save();
                                    return response()->json(["success" => TRUE,"op"=>"create","msg_type"=>"success","msg"=>"setting created successfully!","redirect_url"=>"/admin/table-list"]);
                                  }
                                  

                              }

                              public function update($id){
                                if($id){
                                   $user = Auth::user();
                                   $id = en_de_crypt($id, "d");
                                   $datas = setting::findorfail($id);
                                   //echo "<pre>" ; print_r($datas); die();
                                   $default = "default.png";
                                   $columns = Schema::getColumnListing("setting");
                                   return view("pages.admin.setting.create",compact("datas","default","columns"));
                                }
                              }
                    }
                     ?>