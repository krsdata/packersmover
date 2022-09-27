<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Feedback;
use App\Request_Money;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class WithdrawController extends Controller
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
        $withdraw = Request_Money::orderBy('created_at', 'desc');       
        $withdraw = $withdraw->paginate($perPage);
        
        if ($request->ajax()){
            $view = view("pages.admin.withdraw.table_view",compact('withdraw'))->with('no', ($request->input('page', 1) - 1) *  $perPage)->render();
            return response()->json(['html'=>$view]);

        }else{
            return view('pages.admin.withdraw.index',compact('withdraw'))->with('no', ($request->input('page', 1) - 1) *  $perPage);
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


}
