<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Banner;
use App\Order_master;
use App\CrudEvents;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Pdf;
use Mail;
use Helper;
use Input, Redirect, Session, Response, DB;
class BookCalenderController extends Controller
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
        $perPage=10;
        $date = date("Y-m-d");
       // $events = array();
        //$events = Order_master::where('date', '>=', $date)->get(['name', 'date']);
        $events = DB::table('order_master AS o')
                            ->select('o.id AS id','o.name AS title','o.date AS start')
                            ->get();
        if ($request->ajax()) {

            $data = CrudEvents::whereDate('event_start', '>=', $request->start)
                ->whereDate('event_end',   '<=', $request->end)
                ->get(['id', 'event_name', 'event_start', 'event_end']);

            return response()->json($data);
        }

       
          // $event = response()->json($events);
        //   echo json_encode($events);
        //   die;
           return view('pages/admin/calender/book_calender',compact('events'));
           //return view('home');   
    } 

    public function calendarEvents(Request $request)
    {
        
        $date = date("Y-m-d");
        // $event = CrudEvents::where('event_start', '>=', $date)->get(['id', 'event_name', 'event_start']);
        // return response()->json($event);
        switch ($request->type) {
           case 'create':
              $event = CrudEvents::create([
                  'event_name' => $request->event_name,
                  'event_start' => $request->event_start,
                  'event_end' => $request->event_end,
              ]);
              
              return response()->json($event);
             break;
  
           case 'edit':
              $event = CrudEvents::find($request->id)->update([
                  'event_name' => $request->event_name,
                  'event_start' => $request->event_start,
                  'event_end' => $request->event_end,
              ]);
 
              return response()->json($event);
             break;
  
            case 'delete':
            $id = en_de_crypt($request->id, 'd');
         
  
              return response()->json($id);
             break;

            
                
            default:
             break;
        }
    }



 

}