@extends('layouts.dashboard')
@section('dashcontent')
<style>
</style>  
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="col-sm-4">
                  <h4 class="cust-card-title">
                    <i class="mdi mdi-calendar-clock cust-box-icon"></i>
                    Periodic Report List
                  </h4>
              </div>
          </div>
          <div class="row mt-3">
            <div class="col-sm-12">
                <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="row">
                
                <div class="col-sm-4 add-select-muletile12 " style="border-radius: 0px 5px 5px 0px;">
                   <select class="form-control Station_Id dy_Station" name="station[]" id="station" data-msg-required="stations is required"  multiple="multiple" required>
                      @php
                          $selected =" ";
                          if(isset($stations) && !empty($stations)){
                            foreach($stations as $stationss){
                              if(!empty($station)){
                                if(in_array($stationss->id,$station)){

                                  $selected ="selected";
                                }else{
                                      $selected =" ";
                                }
                              }
                              echo '<option value="'.$stationss->id.'" '.$selected.'>'.$stationss->title.'</option>';
                            }  
                          }   
                      @endphp
                     </select> 
                   </div>
                   <div class="col-sm-4">
                      <select class="form-control tank" placeholder="Tank" name="tank" >
                        <option value="">All Tank</option>
                      </select>
                    </div>
                  <div class="col-sm-4 d-flex">
                    <input type="text" class="form-control common_date" placeholder="Date" name="dates"value="{{$dates}}" />
                    <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                  </div>
              </div>
               </form>
            </div>
          </div>  
         
          <div class="mt-4" id="table_filter_view">
              @include('pages.admin.periodic_report.table_view')
          </div>
      </div>
    </div>
  </div>
</div>
@if($amo_new!=0)
<div class="row">
  <div class="col-sm-12">
    <div class="clear-fix">
        <div style="float:right;width:auto;">
            <a href="{{ $fullUrl }}"  class="btn btn-primary ml-2 " data-type="pdf" >Export PDF</a>
        </div>
    </div>
  </div>
</div>
@endif
<script>
$("#station").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
});
</script>  
@endsection
