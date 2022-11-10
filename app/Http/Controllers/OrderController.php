<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Order_master;
use App\Category;
use App\Classes\ArrayToTextTable;

use PDF;
use Mail;
use File;
use Helper;
use Input, Redirect, Session, Response, DB;
class OrderController extends Controller
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
        $perPage = 10;
        $date = date("M j, Y,",time());
        $dates= $date." 00:00 - ".$date." 23:59";        
        $order=$request->get('order');
        $s_datese=$request->get('dates');
        if(!empty($s_datese)){
            $dates = $s_datese;
        }
        
        $order_name = $request->get('order_name');

        //if($user->hasRole('sai-manager')){
            if($order_name)
            {
                $order = Order_master::orderBy('created_at', 'desc')->where(function($query) use($order_name) {
                    if (!empty($order_name)) {
                        $query->Where('email', 'LIKE', "%$order_name%")
                                ->OrWhere('contact', 'LIKE', "%$order_name%");
                    }
                });
            }else{
                $order = Order_master::orderBy('created_at', 'desc');
            }
            


            $order = $order->paginate($perPage);            
         
            $s_count = $order->count();
            return view('pages/admin/order/index',compact('order','s_count','request'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
            //return view('pages.admin.customer.index',compact('search_input','customers','user_id'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
       // return view('home');   
    } 

    public function order_create(Request $request){
        $user = Auth::user();
        $sub_cat = array();
        
                $Orders = Order_master::orderBy('name', 'asc')->get();
                $category = Category::where("parent_id","0")->get();
               
                foreach($category as $ckey => $cval)
                {
                    
                    $category[$ckey]['name'] = $cval->name;
                    $cat_id = $cval->id;
                    $category[$ckey]['sub_cat'] = Category::where("parent_id",$cat_id)->get();
                   
                }
                             
        return view('pages/admin/order/create',compact('Orders','category','sub_cat'));
    }

    public function order_store(Request $request) {

    //    echo json_encode($request->all());
    //    die;
        $user = Auth::user();
        $validator_order_info = $this->validator_order_info_update($request->all());
        if ($validator_order_info->fails()) {
            return response()->json(['success' => FALSE, 'msg_type' => 'errors', 'errors' => $validator_order_info->getMessageBag()->toArray()]);
        }else{
            $data=$request->all();   
            
            if (!empty($_POST['id'])) {
                $id = en_de_crypt($_POST['id'], 'd');
                $obj_data = Order_master::findorfail($id);
                $obj_data->name = $data['name'];
                
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'order has been updated Sucessfully!','redirect_url'=>'/admin/order-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'order Updation failed!','redirect_url'=>'/admin/order-list']);
                }
            }else{
                
                $order_obj = new Order_master();
               // $alljson = json_encode($data);
                $alljson = array();
                $allwrap = array();

                $datas = '';
                $string = '';
                $tr = '';
                foreach($data as $dkey => $dval)
                {
                    
                    if($dval != '0' && $dkey && $dkey !='_token' && $dkey !='contact' && $dkey != 'date' && $dkey != 'origin_floor' && $dkey != 'destination_floor'
                    && $dkey !='origin' && $dkey !='origin_lift_availability' &&  $dkey !='destination'
                    && $dkey !='destination_lift_availability' && $dkey !='name' && $dkey !='email' && $dkey !='id'
                    && $dkey != 'packing' && $dkey != 'transport' && $dkey != 'loading' && $dkey != 'unloading' && $dkey != 'unpacking'
                    && $dkey != 'ac' && $dkey != 'local' && $dkey != 'car_transport' && $dkey != 'insurance' 
                    && $dkey != 'gst' && $dkey != 'sub_total' && $dkey != 'transport_gst' && $dkey != 'discount' && $dkey != 'advance_payment'
                    && $dkey != 'gst_amt' && $dkey != 'transport_gst_amt' && $dkey != 'total' && $dval != '' &&
                     $dkey != 'gross_total' && $dkey != 'pending_amt' )
                    {
                        $string = preg_replace('/[^a-z]/i', '', $dval);

                        if($string)
                        {   
                            $tr = trim($dkey,"wrap");
                            //$allwrap[$tr] = $dval;
                            if($tr)
                            {
                                $datas = $dval;
                                
                            }
                        }
                         else
                         {
                           
                           // $alljson[$dkey] = $dval;
                            $alljson[] = array(
                                'name' => $dkey,
                                'qty' => $dval,
                                'wraps' => $datas
                            );
                         }
                       
                       // $decode[] = $extra;
                        //echo json_encode($decode);
                        //die;

                        //$alljson[$dkey] = $dval;
                    }

                    // if($dval == 'Bubble' || $dval == 'Corrugated' || $dval == 'Foam' || $dval == 'Wrapping')
                    // {
                    //     $short = trim($dkey,'wrap');
                    //     if($short)
                    //     {
                    //         $allwrap[$short] = $dval;
                    //     }
                        
                    // }
                }
              
                    // echo json_encode($alljson);
                    // die;
                

                $data_array = array();
                $order_obj->name = $data['name'];
                $order_obj->email = $data['email'];
                $order_obj->contact = $data['contact'];
                $order_obj->date = date("Y-m-d H:i:s");
                $order_obj->item_name = json_encode($alljson);
                
                
              
                    $order_obj->origin = $data['origin'];
                    $order_obj->origin_lift_availability = $data['origin_lift_availability']??null;
                    $order_obj->origin_floor = $data['origin_floor']??0;
                    $order_obj->destination = $data['destination']??null;
                    $order_obj->destination_lift_availability = $data['destination_lift_availability']??null;
                    $order_obj->destination_floor = $data['destination_floor']??null;
                
                
                $order_obj->date = $data['date'];
               
                $order_obj->packing = $data['packing'];
                $order_obj->transport = $data['transport'];

                $order_obj->loading = $data['loading'];
                $order_obj->unloading = $data['unloading'];
                $order_obj->unpacking = $data['unpacking'];
                $order_obj->ac = $data['ac'];
                $order_obj->local = $data['local'];
                $order_obj->car_transport = $data['car_transport'];
                $order_obj->insurance = $data['insurance'];
                $order_obj->gst = $data['gst'];
                $order_obj->gst_amt = $data['gst_amt'];
                $order_obj->transport_gst = $data['transport_gst'];
                $order_obj->transport_gst_amt = $data['transport_gst_amt'];
                $order_obj->discount = $data['discount'];
                $order_obj->total = $data['total'];
                $order_obj->sub_total = $data['sub_total'];
                $order_obj->advance_payment = $data['advance_payment'];
                $order_obj->gross_total = $data['gross_total'];
                $order_obj->pending_amt = $data['pending_amt'];
                
                // If advance payment so lets send sms for customer
                if($data['advance_payment'])
                {
                    // call function api for sms
                    $this->send_sms_confirmation_msg($data['contact']);
                }


                $order_obj->save();  
                
                if ($order_obj->save()) {
                    // generate pdf order

                        // $order = Order_master::findorfail($order_obj->id);
                        // view()->share('employee',$order);
                        // $items = json_decode($order->item_name);
                        // $pdf = PDF::loadView('pages.admin.employee.invoice', compact('order','items'));
                        // return $pdf->download('pdf_file.pdf');

                    // end
                    
                    $this->send_sms_quotation_msg($data['contact']);
                    
                  //redirect to costing page
                  return redirect('/admin/order/generate_invoicepdf/'.en_de_crypt($order_obj->id,'e'))->with('status', 'Order has been added Sucessfully!');

                  //  return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'order has been added Sucessfully!','redirect_url'=>'/admin/order-list']);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'order Insertion failed!','redirect_url'=>'/admin/order-list']);
                }
            }
        }
    }

    public function get_serializedata(Request $request)
    {
        $data = $request->all();
        // echo json_encode($data);
        // die;
        $output='';
        $extra_key='';
        $alljson = array();
        $allwrap = array();
        $datas;
        foreach($data as $okey => $oval)
        {
           
            if($oval != '0' && $okey !='contact' && $okey !='origin' && $okey !='destination' && $okey !='name' && $okey !='email'
            && $okey != 'date' && $okey != 'origin_floor' && $okey != 'destination_floor'
            && $okey !='packing' && $okey !='gst' && $okey !='transport_gst' && $okey !='sub_total' && $okey !='gross_total'
            && $okey !='pending_amt' && $okey !='gst_amt' && $okey !='transport_gst_amt' && $okey != 'total' && $okey != '_token'
            && $okey !='transport' && $okey !='unpacking' && $okey !='car_transport' && $okey !='loading' && $okey !='unloading'
            && $okey !='local' && $okey !='insurance' && $oval !='' && $oval !='ac' && $oval !='discount'
            && $oval !='advance_payment')
            {

                $string = preg_replace('/[^a-z]/i', '', $oval);
                if($string)
                { 
                    $tr = trim($okey,"wrap");
                    $allwrap[$oval] = $tr;
                    if($tr)
                    {
                        @$datas = $oval;
                    }

                }
                else
                {

                    $alljson[] = array(
                        'name' => $okey,
                        'qty' => $oval,
                        'wraps' => '['.$datas.']'
                    );

                  // $alljson[$okey] = $oval;
                   
                }
                
                //echo json_encode($alljson);
               // $output .= '<p>'.$okey.' <span>'.@$datas.'</span>'.' <span> :'.$oval.'</span>'.'</p>';
                  //$output .= $alljson;              
            }
        }

        foreach($alljson as $akey => $aval)
        {
            $output .= '<p>'.$aval['name'].' <span>'.@$aval['wraps'].'</span>'.' <span> :'.$aval['qty'].'</span>'.'</p>';
        }

        //print_r($alljson[0]['name']);
       // echo json_encode($alljson);
           
            
        return response()->json(["msg" => "","data"=>$output]);
    }

     // order delete
     public function order_delete($id)
     {
         $id = en_de_crypt($id, 'd');
        
         $order_data = Order_master::findorfail($id);
         $order_data->delete();
         
       
         return redirect('/admin/order-list')->with('status', 'Order has been deleted Sucessfully!');

     }
    // order detail
    public function order_detail($id)
    {
        $id = en_de_crypt($id, 'd');
        $order_data = Order_master::findorfail($id);
        $items = json_decode($order_data->item_name,true);
        
        return view('pages/admin/order/order_detail',compact('order_data','items'));
    }

    // costing function
    public function costing($cost_id)
    {
        $user = Auth::user();
        $id = $cost_id;
        $data = Order_master::where('id',$id)->get();
        //echo json_encode($data);
        //die;
        return view('pages/admin/order/costing',compact('data'));
    }

    public function update_order(Request $request)
    {
        $user = Auth::user();
        echo $id = $cost_id;
        die;
    }

    protected function validator_order_info_update(array $data) {
        $cuser = Auth::user();
      
            $au['name'] = 'required';
            $au['email'] = 'required';
            $au['contact'] = 'required';
        
        return Validator::make($data, $au);
    } 

    public function order_update($id){
        echo $id ; die();
        $user = Auth::user();
        $id = en_de_crypt($id, 'd');
        $order = Order_master::findorfail($id);         
        return view('pages/admin/order/create',compact('order'));
    } 
    
    // generate invoice bill
    public function generate_invoicepdf($id)
    {
        // retrive all records from db
        $data = array();
        $id = en_de_crypt($id, 'd');
       
       // $perPage = 10;
        $order = Order_master::findorfail($id);
        //$order = $order->paginate($perPage);
        // share data to view
        //view()->share('employee',$order);
        $items = json_decode($order->item_name,true);
        $total_count = count($items);
        
       
      
       return view('pages/admin/employee/invoice',compact('order','items','total_count'));
       // $pdf = PDF::loadView('pages.admin.employee.invoice', compact('order','items','total_count'));
        
      //  return $pdf->download('pdf_file.pdf');
    }


    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/draw';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    // Search bar product

    public function search_data_order(request $request)
    {
        $user = Auth::user();
        $name = $request['name'];
        $order = Order_master::where('name','LIKE','%'.$name.'%')->get();
        $total_row = $order->count();
        $output='';
            if($total_row > 0)
            {
                foreach($order as $dkey => $dval)
                {                      
                    $decid = en_de_crypt($dval->id,"e");
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$dval->id.'</td>'.
                    '<td class="text-capitalize">'.$dval->name.'</td>'.
                    '<td class="text-capitalize">'.$dval->email.'</td>'.
                    '<td class="text-capitalize">'.$dval->contact.'</td>'.
                    '<td class="text-capitalize">'.$dval->address.'</td>'.                
                    '<td class="actions" data-th="">'.'<a href='.url('admin/order/edit/').'/'.$decid.'><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Order Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }
    
    
    // Third-party api
    // quotation msg - pdf generate send 
    // reminder msg - booking udyache asel trr (tommrow asel tr) adich send krayache
    // confirmation msg - advance payment asel tr send karayache
    // thank you msg - booking date -> after thabk 
    
    public function send_sms_quotation_msg($mobiles)
    {
        //$mobiles = '918551995731';
        //1201159438906763690
        // pdf generated and after send messages
        $flow_id = "6040bfedd6fc05609509b496";
        $senderId = "SAIPKR";
        
        //Prepare you post parameters
        $postData = array(
            "flow_id" => $flow_id,
            "sender" => $senderId,
            "mobiles" => '91'.$mobiles
        );
       $postDataJson = json_encode($postData);
       
        $url="http://api.msg91.com/api/v5/flow/";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postDataJson,
            CURLOPT_HTTPHEADER => array(
                "authkey: 238708A7BIRMsept5ba3c275",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    //reminder msg for before booking date - cron created
    public function send_sms_reminder_msg()
    {
         //1201159438906763690
        // Booking date tommorrow so send before msg
        $prev_day = date('Y-m-d h:i:s', strtotime(' -1 day'));
        $nxt_day = date('Y-m-d', strtotime(' +1 day'));
  
        $booking_reminder = Order_master::where('date',$nxt_day)->get();
        $counts = array();
       foreach($booking_reminder as $bkey => $bval)
       {
          $counts[$bkey]['mobiles'] = '91'.$bval->contact;
       }
       
        $flow_id = "6040ca63367591043f1a4183";
        $senderId = "SAIPKR";
       
        //Prepare you post parameters
        $postData = array(
            "flow_id" => $flow_id,
            "sender" => $senderId,
            "recipients" => $counts
        );

        $postDataJson = json_encode($postData);
        $url="http://api.msg91.com/api/v5/flow/";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postDataJson,
            CURLOPT_HTTPHEADER => array(
                "authkey: 238708A7BIRMsept5ba3c275",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
       

    }
   
     // advance payment
    public function send_sms_confirmation_msg($mobiles)
    {
         //1201159438906763690
        // IF payment advance - so send confirmation msgs
        $senderId = "SAIPKR";
        $flow_id = "6040ca8e0fdf72715d773ec8";
      
        
        //Prepare you post parameters
        $postData = array(
            "sender" => $senderId,
            "flow_id" => $flow_id,
            "mobiles" => '91'.$mobiles
        );
        $postDataJson = json_encode($postData);
        
        $url="http://api.msg91.com/api/v5/flow/";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postDataJson,
            CURLOPT_HTTPHEADER => array(
                "authkey: 238708A7BIRMsept5ba3c275",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
       

    }

    // after all moving done then send msg afgter two days
    public function send_sms_thanku_msg()
    {
         //1201159438906763690
        // booking date - shifitng done for customer after two days send thank u msgs
        $prv_day = date('Y-m-d', strtotime(' -1 day'));
        $today = date('Y-m-d');
        
        $booking_reminder = Order_master::where('date','=',$prv_day)->get();
        $thanku = array();
        foreach($booking_reminder as $bkey => $bval)
        {
            // if($bval->date < $today)
            // {
                $thanku[$bkey]['mobiles'] = '91'.$bval->contact;
            // }
        }
       
        $senderId = "SAIPKR";
        $flow_id = "6040cb0ce9550e4c262b10ab";
        
        //Prepare you post parameters
        $postData = array(
            "flow_id" => $flow_id,
            "sender" => $senderId,
            "recipients" => $thanku
        );
        $postDataJson = json_encode($postData);
        
        $url="http://api.msg91.com/api/v5/flow/";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postDataJson,
            CURLOPT_HTTPHEADER => array(
                "authkey: 238708A7BIRMsept5ba3c275",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
       

    }

    // status complete after date 
    public function order_complte_success()
    {
        $prev_day = date('Y-m-d', strtotime(' -1 day'));
        $today = date('Y-m-d');

        $status = Order_master::get();
        $thanku = array();
        foreach($status as $bkey => $bval)
        {
            if($today > $bval->date)
            {
                $id = $bval->id;
                $obj_data = Order_master::findorfail($id);
                $obj_data->quotation_status = 'complete';
                $obj_data->update();
                
            }
        }
       

    }

}

     
   

