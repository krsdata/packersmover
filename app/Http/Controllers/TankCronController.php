<?php

namespace App\Http\Controllers;

use App\Trn;
use App\Classes\ArrayToTextTable;
use Dompdf\Dompdf;
use Exception;
use App\Tanks;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class TankCronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function tankcron(){
        $tankarray=$tank=array();
        $date=date('Y-m-d',strtotime("-1 days"));
        $date= $date." 00:00 - ".$date." 23:59";
        $obj=Trn::where('FDC_DATE',$date)->get();
        if($obj){
            foreach ($obj as $key => $value) {
               if($value['tank_id'] > 0){
                  array_push($tankarray,$value['tank_id']);
               } 
               
            }
        }
        //echo "<pre>";print_r($tankarray);
        if(!empty($tankarray)){
            foreach ($tankarray as $key => $value) {
                $tank[]=Tanks::find($value);
                
            } 
        }
        $view = view("pages.admin.periodic_table",compact('obj','tank'))->render();
            $pdfhtml = '<html><head>
            <style>
            .card {
                display: block;
                background-color: #fff;
                background-clip: border-box;
                border: 1px solid #d2d2dc;
                border-radius: 0;
                padding: 0px!important;
                background: #fefefe;
            }
            .modal-body, .card-body {
                padding: 5px!important;
            }
            .mt-1{
                margin-top: 0.25rem !important;
            }
            .w-100 {
                width: 100% !important;
            }
            .justify-content-center {
                justify-content: center !important;
            }
            .table-responsive {
                display: block;
                width: 100%;
            }
            .pt-3, .py-3 {
                padding-top: 1rem !important;
            }
            .text-left {
                text-align: left !important;
            }
            .table-responsive > .table-bordered {
                border: 0;
            }
            table {
                border-collapse: collapse;
                max-width: 100%;
                overflow:hidden;
            }
            * {
                box-sizing: border-box;
            }
            .table-striped th {
                color: #331CBF;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: 0px solid #dddddd;
                border-top: 0;
                border-bottom-width: 0px;
                font-weight: 500;
                font-size: .875rem;
                text-transform: uppercase;
                line-height: 1;
                white-space: nowrap;
                padding: 1.25rem 0.9375rem;
                text-align: inherit;
            }
            .table-striped tbody tr:nth-of-type(odd) {
                background-color: #eee;
            }
            .table td {
                font-size: 0.875rem;
                padding: .875rem 0.9375rem;
                vertical-align: middle;
                line-height: 1;
                white-space: nowrap;
                border-top: 0px solid #dddddd;
            }
            .table td button{
                background: transparent;
                border-color : transparent;
            }

            .row {
                display: flex;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
            }
            .stretch-card {
                display: -webkit-flex;
                display: flex;
                -webkit-align-items: stretch;
                align-items: stretch;
                -webkit-justify-content: stretch;
                justify-content: stretch;
            }
            .grid-margin {
                margin-bottom: 1.875rem;
            }
            .col-md-4, .lightGallery .image-tile {
                width: 25%;
                float:left;
                margin-right:7.3%;
            }
            .bg-primary, .settings-panel .color-tiles .tiles.primary {
                background-color: #331CBF !important;
            }
            .border-0 {
                border: 0 !important;
            }
            .border-radius-2 {
                border-radius: 2rem;
            }
            .card {
                box-shadow: none;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                -ms-box-shadow: none;
            }
            .stretch-card > .card {
                width: 100%;
                min-width: 100%;
            }
            .card .card-body {
                padding: 1.25rem 1.75rem;
            }
            .card-body {
                flex: 1 1 auto;
                padding: 1.25rem;
                box-shadow: none;
            }
            .flex-xl-row {
                flex-direction: row !important;
            }
            .icon-rounded-inverse-primary {
                background: white;
                width: 1.875rem;
                height: 1.875rem;
                border-radius: 50%;
                text-align: center;
                box-shadow: none;
                float:left;
                margin:25px 0px 0px 30px;
                display:none;
            }
            .text-white {
                color: #ffffff !important;
                width: 90.875rem;
                margin-left:50px;
                margin-top:0px;
            }
            .cust-card-dash p {
                font-size: 0.77rem;
            }
            .font-weight-medium {
                font-weight: 500;
            }
            .text-uppercase {
                text-transform: uppercase !important;
            }
            .text-xl-left {
                text-align: left !important;
            }
            .mt-xl-0, .my-xl-0 {
                margin-top: 0 !important;
            }
            p {
                margin-bottom: 0px;
                line-height: 1.5rem;
            }
            .align-items-xl-baseline {
                align-items: baseline !important;
                margin-top: -10px;
            }
            .flex-xl-row {
                flex-direction: row !important;
            }
            .cust-card-dash h3 {
                font-size: 1.3rem;
            }
            .mb-lg-0, .my-lg-0 {
                margin-bottom: 0 !important;
            }
            .mr-1, .mx-1 {
                margin-right: 0.25rem !important;
            }
            .mb-0, .my-0 {
                margin-bottom: 0 !important;
            }
            small, .small {
                font-size: 80%;
                font-weight: 400;
            }
            tr { page-break-inside: avoid; }

            </style></head><body><div class="modal-body" id="trndetailbody">';
            $pdfhtml .= "<p>Transaction List: ".$date."</p><p>&nbsp;</p>";
            $pdfhtml .= $view;
            $pdfhtml .= '</div></body></html>';     
            $pdftitle = $date."periodic_report.pdf";
            $pdf = new Pdf($pdfhtml);
            if (!$pdf->send($pdftitle)) {
                $error = $pdf->getError();
                print_r($error);
            }
            exit;
            //print_r($obj); die();
        }

}
