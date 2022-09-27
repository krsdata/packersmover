@extends('layouts.dashboard')
@section('dashcontent')

 <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                  <div class="col-sm-3">
                      <h4 class="cust-card-title">
                        <i class="mdi  mdi-gas-station cust-box-icon"></i>
                        Station list
                      </h4>
                  </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                          {{ csrf_field() }}
                          <div class="row">
                            <div class="col-md-4">
                            </div> 
                            <div class="col-md-4">
                                <select class="form-control Station_Id" name="station[]" id="station" data-msg-required="stations is required"  multiple="multiple">
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
                            <div class="col-md-4 form-group d-flex custom-search-view-place">
                                <input value="{{$search_input}}" type="text" name="search_input" id="search_input" class="form-control " placeholder="Search Here...." >
                                <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                            </div> 
                          </div>  
                      </form>
                    </div>
                  </div> 
                    <div id="table_filter_view">
                        @include('pages.admin.station.table_view')
                    </div>
                </div>
              </div>
            </div>
          </div>
<script type="text/javascript">
$("#station").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
});
</script>
@endsection



