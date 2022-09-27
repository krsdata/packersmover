@extends('layouts.dashboard')
@section('dashcontent')

 <div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
            <h4 class="cust-card-title">
            <i class="mdi  mdi-fuel mdi-rotate-315 cust-box-icon" id="user_loyalty_update" data-url="{{route('user_loyalty_update')}}"></i>
              Station Loyalty List
            </h4>
        </div>
        <div class="col-sm-6">
        <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
          {{ csrf_field() }}
          <div class="form-group d-flex custom-search-view-place">
              @if($role_name == "admin")
              <input type="hidden" id="selected_value" value="{{ @$search_input }}">
              <select class="form-control Station_Id " name="search_input" id="search_input" data-msg-required="stations is required"  multiple="multiple">
                @if(isset($stations) && !empty($stations))
                @foreach($stations as $stationss)
                            <option value="{{$stationss->id}}"> {{$stationss->title}}</option>
                @endforeach
                @endif
              </select>
              <button   class="btn btn-primary common_search_filter ml-2" ><i class="mdi mdi-pencil"></i></button>
              @endif
          
          </div>
          </form>
        </div>
        </div>
        <hr/>
        <div class="row" id="change_slp" data-url="{{ route('stations_loyalty_update') }}">
            <div class="col-sm-4">
              Station Name
            </div>
            <div class="col-sm-4">
              Product Name
            </div>
            <div class="col-sm-4">
              Loyalty Point
            </div>
        </div>
        <hr/>
              @if( isset($products) && !empty($products))
              @foreach($products as $product)
              <div class="row">
                <div class="col-sm-4">
                  {{$stations_name}}
                </div>
                <div class="col-sm-4">
                  {{$product->fuel}}
                </div>
                <div class="col-sm-4">
                  <input type="number" class="change_slp form-control" value="{{$product->per_ltr}}" data-eid="{{$product->eid}}" />
                </div>
              </div>
              <hr/>
              @endforeach
              @endif
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

