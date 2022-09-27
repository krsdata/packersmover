<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Booking_Draw;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class BookingDrawController extends Controller
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
        
        $bookingdraw = Booking_Draw::orderBy('created_at', 'desc')->where('created_by',$user_id);
        $bookingdraw = $bookingdraw->paginate($perPage);

        if(!empty($bookingdraw)){
            $bookingdraw->where('first_name','=',$bookingdraw);
        }

        if ($request->ajax()){
            $view = view("pages.admin.booking.table_view",compact('bookingdraw'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.booking.index',compact('bookingdraw'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
        }
        
        return view('home');   
    } 

    public function view_cart(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $customer = Customer::where("created_by","=",$user_id)->get();

        return view('pages/admin/order/view_cart',compact('user','customer'));
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


    public function img_upload(Request $request){
        $fileName = "false";
        if ($request->file('stock_image')) {
            $file = $request->file('stock_image');
            $fileName =uniqid("image_").'.png';
            $destinationPath = public_path() . '/images/stock';
            $file->move($destinationPath, $fileName);
        }
        return $fileName;
    }

    public function search_data(request $request)
    {
        $user = Auth::user();
        $product_name = $request['product_name'];
        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->where('created_by',$user->id)->get();
        $total_row = $product->count();
        $output='';
            if($total_row > 0)
            {
                foreach($product as $pkey => $pval)
                {
                    $catid = $pval->category;
                    $cat_name = Category::where('id','=',$catid)->get();
                    $product[$pkey]['catname'] = $cat_name[0]->name;
                    $decid = en_de_crypt($pval->id,"e");
                    $output.='<tr>'.
                    '<td <button class="btn badge-primary btn-sm">'.$pval->id.'</td>'.
                    '<td class="text-capitalize">'.$pval->product_name.'</td>'.
                    '<td class="text-capitalize">'.$pval->catname.'</td>'.
                    '<td class="text-capitalize">'.$pval->quantity.'</td>'.
                    '<td class="actions" data-th="">'.'<a href='.url('admin/order/add_to_Cart/').'/'.$decid.'><button class="btn badge-primary btn-xs">Add</button></a>'.'</td>'.
                    '</tr>';

                }
                return response()->json(["success"=>"True","msg" => "","data"=>$output]);
            }
            else
            {
                $output = '<tr><td class="server-error" colspan="6">No Product Found..</td></tr>';
                return response()->json(["success"=>"false","msg" => "","data"=>$output]);
            }

        
        
    }

}
