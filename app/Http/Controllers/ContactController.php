<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Contact_Us;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class ContactController extends Controller
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
        $perPage = 12;
        $contact = array();
        $get_data = array();
        $contact = Contact_Us::orderBy('created_at', 'desc');
        $contact = $contact->paginate($perPage);
        foreach($contact as $ckey => $cval)
        {
            $id = $cval->user_id;
            $get_data = User::where('id','=',$id)->get();
            foreach($get_data as $gkey => $gval)
            {
                $contact[$ckey]['name'] = $gval['name'];
                $contact[$ckey]['email'] = $gval['email'];
               
            }
        }      
       
        // if(!empty($feedback)){
        //     $feedback->where('name','=',$feedback['name']);
        // }   
        
       
        if ($request->ajax()){
            $view = view("pages.admin.contact.table_view",compact('contact'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.contact.index',compact('contact'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        
        return view('home');   
    } 




    protected function validator_stock_info_update(array $data) {
        $cuser = Auth::user();
        if(empty($data['id'])){
             $au['total_number_liters_ordered'] = 'required';
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $id = en_de_crypt($data['id'], 'd');
            $au['invoice_no'] = 'required|unique:tank_stock,invoice_no,'.$id;
        }else{
             $au['invoice_no'] = 'required|max:255|unique:tank_stock';
            $au['Station_Id'] = 'required';
            $au['track_no'] = 'required';
            $au['driver_name'] = 'required';
        }
        return Validator::make($data, $au);
    } 

    public function search_data(request $request)
    {
        $user = Auth::user();
        $name = $request['name'];
        $contact = Contact_Us::where('name','LIKE','%'.$name.'%')->where('created_by',$user->id)->get();
        $total_row = $contact->count();
        $output='';
            if($total_row > 0)
            {
                foreach($contact as $ckey => $cval)
                {                    
                    $id = $cval->id;          
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$cval->id.'</td>'.
                    '<td class="text-capitalize">'.$cval->name.'</td>'.                  
                    '<td class="text-capitalize">'.$cval->email_id.'</td>'.
                    '<td class="text-capitalize">'.$cval->mobile_no.'</td>'.
                    '<td class="text-capitalize">'.$cval->message.'</td>'.
                    '<td class="text-capitalize">'.$cval->type.'</td>'.
                    '<td class="text-capitalize">'.$cval->created_at.'</td>'.               
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Contact Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }


}
