<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Monthly_Tickets;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class TicketController extends Controller
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

    public function index(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $perPage = 12;
        $ticket=$request->get('ticket');       
        
            $ticket = Monthly_Tickets::orderBy('created_at', 'desc')->where('created_by',$user_id);
            $ticket = $ticket->paginate($perPage);          

            if(!empty($ticket)){
                $ticket->where('ticket_name','=',$ticket);
            }
            $s_count = $ticket->count();

            return view('pages/admin/ticket/index',compact('ticket','s_count'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        
        return view('home');   
    } 

    public function ticket_create(Request $request){
        $user = Auth::user();   
        $Ticket = Monthly_Tickets::orderBy('ticket_name', 'asc')->where('created_by',$user->id)->get();        
        
        return view('pages/admin/ticket/create',compact('Ticket'));
    }

    public function ticket_store(Request $request) {
        $user = Auth::user();
        $validator_ticket_info = $this->validator_ticket_info_update($request->all());
        if ($validator_ticket_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_ticket_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();           

            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Monthly_Tickets::findorfail($id);
                $obj_data->ticket_name = @$data['ticket_name'];              
                $obj_data->description = $data['description'];
                $obj_data->fee = $data['fee'];
                $obj_data->status = $data['status'];

                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Ticket has been updated Sucessfully!','redirect_url'=>'/admin/ticket-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Ticket Updation failed!','redirect_url'=>'/admin/ticket-list']);
                }
            }else{

                $obj = new Monthly_Tickets();
                $obj->ticket_name = @$data['ticket_name'];
                $random_number = rand(1000000000, 9999999999);
                $obj_data->ticket_id = $random_number;               
                $obj->description = $data['description'];
                $obj->fee = $data['fee'];
                $obj->status = $data['status'];  
                $obj->created_by = $user->id;
                if ($obj->save()) {
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Ticket has been added Sucessfully!','redirect_url'=>'/admin/ticket-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Ticket Insertion failed!','redirect_url'=>'/admin/ticket-list']);
                }
            }
        }
    }

    protected function validator_ticket_info_update(array $data) {
        $cuser = Auth::user();
        //$id = $data['id'];
        //echo json_encode($data['id']);
        //die;
            $au['ticket_name'] = 'required';
            $au['fee'] = 'required';            
        
        return Validator::make($data, $au);
    } 

    public function ticket_update($id){
        //echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $ticket = Monthly_Tickets::findorfail($id);   
        return view('pages/admin/ticket/create',compact('ticket'));
    } 

    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/stock';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }



    // Tax search
    public function search_data_ticket(request $request)
    {
        $user = Auth::user();
        $ticket_name = $request['ticket_name'];
        $ticket = Monthly_Tickets::where('ticket_name','LIKE','%'.$ticket_name.'%')->where('created_by',$user->id)->get();
        $total_row = $ticket->count();
        $output='';
            if($total_row > 0)
            {
                foreach($ticket as $tkey => $tval)
                {                    
                    $id = $tval->id;
                    $decid = en_de_crypt($id,"e");
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$tval->id.'</td>'.
                    '<td class="text-capitalize">'.$tval->ticket_name.'</td>'.
                    '<td class="text-capitalize">'.$tval->fee .'</td>'.
                    '<td class="text-capitalize">'.$tval->created_at.'</td>'.                    
                    '<td class="actions" data-th="">'.'<a href='.url('admin/ticket/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Ticket Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }   
    }
}

     
   

