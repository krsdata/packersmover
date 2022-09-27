@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-10">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-library-books cust-box-icon"></i>
                     Feedback list
                  </h4>
               </div>                           
            </div>
            <br>                   
            <div id="table_filter_view">
               @include('pages.admin.feedback.table_view')
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
