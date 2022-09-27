@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-deskphone cust-box-icon"></i>
                     Zreports
                  </h4>
               </div>
            </div>
            <br>       
            <div id="table_filter_view">
               @include('pages.admin.zreports.table_view')
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
