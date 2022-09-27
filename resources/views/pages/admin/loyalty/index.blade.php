@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
            <h4 class="cust-card-title">
            <i class="mdi  mdi-account cust-box-icon" id="user_loyalty_update" data-url="{{route('user_loyalty_update')}}"></i>
              Loyalty User List
            </h4>
        </div>
        <div class="col-sm-6">
        <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                      {{ csrf_field() }}
                    
          <div class="form-group d-flex custom-search-view-place">
              @if($role_name == "admin")
              <input type="hidden" id="selected_value" value="{{ @$search_input }}">
              <select class="form-control Station_Id" name="search_input" id="search_input" data-msg-required="stations is required"  multiple="multiple">
                @if(isset($stations) && !empty($stations))
                        @foreach($stations as $stationss)
                            <option value="{{$stationss->id}}"> {{$stationss->title}}</option>
                @endforeach
                @endif
              </select>
              @endif
              &nbsp;  &nbsp; <input value="{{$name_search}}" type="text" name="name_search" id="name_search" class="form-control" placeholder="Search Here...." >
              <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify"></i></button>
          </div>
          </form>
        </div>
        </div>
          <div id="table_filter_view">
              @include('pages.admin.loyalty.table_view')
          </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $("#search_input").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
  });

  // get selected value 
  var value=$('#selected_value').val();
  if(value){
    $("#search_input").val(value).trigger('change');
  }
</script>
@endsection


