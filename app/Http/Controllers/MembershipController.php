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
                    use App\membership;
                    use Input, Redirect, Session, Response, DB;
                    
                    
                    
                    
                     class MembershipController extends Controller { 
                    
                        public function __construct(){
                            $this->middleware('auth');
                         }
                                            
                            public function index(Request $request){
                              $ddata=Tables::get();
                              \Session::put(["nav_data" => $ddata]);
                              $columns = Schema::getColumnListing('membership');
                                $user = Auth::user();
                                $user_id = $user->id;
                                if($user->hasRole("admin")){
                                  $perPage = 8;
                                  $search_input=$request->get("search_input");
                                  $datas = Membership::orderBy("id", "asc");    
                                 if($search_input){
                                      $datas =  $datas->where("table_name", "like", "%" . $search_input . "%");
                                  }
                                  $datas = $datas->paginate($perPage);
                                  if ($request->ajax()){
                                      $view = view("pages.admin.membership.table_view",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage)->render();
                                      return response()->json(["html"=>$view]);
                                  }else{
                     
                                      return view("pages.admin.membership.index",compact("search_input","datas","user_id","columns"))->with("no", ($request->input("page", 1) - 1) *  $perPage);
                                  }
                              }
                              return view("home");
                            }
                    
                            public function create(){
                                $user = Auth::user();
                                $default = "default.png";
                                $search_input = "";
                                $columns = Schema::getColumnListing('membership');
                                return view("pages.admin.membership.create",compact("user","default","search_input","columns"));
                              }
                    
                              public function store(Request $request){
                                
                                if (!empty($_POST['id'])) {
                                     if($request->all()){

                                      $datas=$request->all();
                                      array_shift($datas);
                                      $id = en_de_crypt($_POST['id'], 'd');
                                      $details = Membership::findorfail($id);
                                      foreach($datas as $key => $data){
                                        $details->$key=$data;
                                      }
                                     

                                      $details->update();

                                      return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Membership updated successfully!','redirect_url'=>'/admin/table-list']);

                                     }
                                  }else{
                                    $membership=new Membership();
                                    $membership->fill($request->all());
                                    $membership->name=$request['name'];
                                    $membership->price=$request['price'];
                                    $membership->save();
                                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Membership created successfully!','redirect_url'=>'/admin/table-list']);
                                  }
                                  

                              }

                              public function update($id){
                                if($id){
                                   $user = Auth::user();
                                   $id = en_de_crypt($id, 'd');
                                   $datas = Membership::findorfail($id);
                                   //echo "<pre>" ; print_r($datas); die();
                                   $default = "default.png";
                                   $columns = Schema::getColumnListing('membership');
                                   return view('pages.admin.membership.create',compact('datas','default','columns'));
                                }
                              }

                              
                    }
                     ?>