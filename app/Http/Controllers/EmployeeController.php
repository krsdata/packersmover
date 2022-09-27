<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\Role;
use App\Customer;
use Mail;
use File;
//use pdf;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Imglogo;
use Input, Redirect, Session, Response, DB;
class EmployeeController extends Controller
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

    public function showEmployees(){
        $employee = User::all();
        return view('pages.admin.employee.index', compact('employee'));
      }

      // Generate PDF
    public function createPDF() {
        // retreive all records from db
        $employee = User::all();
        
        // share data to view
       
        $pdf = PDF::loadView('pages.admin.employee.invoice',compact('employee'));
        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');
      }

      public function invoice(){
        $employee = User::all();
        return view('pages.admin.employee.invoice', compact('employee'));
      }

}
