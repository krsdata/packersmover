@extends('layouts.dashboard')
@section('dashcontent')

 <div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
            <h4 class="cust-card-title">
            <i class="mdi  mdi-coin cust-box-icon" id="user_loyalty_update" data-url="{{route('user_loyalty_update')}}"></i>
              Station Fuel List
            </h4>
        </div>
        <div class="col-sm-6">
        <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
          {{ csrf_field() }}
          <div class="form-group d-flex custom-search-view-place">
              @if($role_name == "admin")
              <input type="hidden" id="selected_value" value="{{ @$search_input }}">
              <select class="form-control Station_Id" name="search_input" id="search_input" data-msg-required="stations is required"  multiple="multiple">
                  @if( isset($stations) && !empty($stations))
                  @foreach($stations as $station)
                      <option value="{{$station->id}}"> {{$station->title}}</option>
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
            <div class="col-sm-3">
              Product Name
            </div>
            <div class="col-sm-3">
              Fuel Price
            </div>
            <div class="col-sm-2">
              New Price
            </div>
        </div>
        <hr/>
        <form method="post"  action = "{{route('stations_price_update')}}">

                    {{ csrf_field() }}
                    <input type="hidden" name="station_id" value="@if($user->hasRole('admin')){{@$search_input[0]}}@else {{@$search_input}} @endif">
              @if( isset($products) && !empty($products))
              @foreach($products as $product)
              <div class="row">
                <div class="col-sm-4">
                  {{$stations_name}}
                </div>
                <div class="col-sm-3">
                  {{$product->fuel}}
                </div>
                <div class="col-sm-3">
                    <input type="number" name="{{trim($product->fuel)}}" value="{{trim($product->PRICE)}}" class="form-control">
                    <input type="hidden" name="id_{{trim($product->fuel)}}" value="{{$product->FDC_PROD}}" class="form-control">
                </div>
                <div class="col-sm-2">
                 @if(isset($productprices[$product->FDC_PROD]))
                  <span class="btn btn-outline-primary disabled" style="cursor:none">
                        {{trim($productprices[$product->FDC_PROD])}}
                  </span>
                @endif
                </div>
              </div>
              <hr/>
              @endforeach
              @endif

              <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <label class="form-check-label text-muted"> <input type="checkbox" class="form-check-input" name="ipc" id="ipc">Immediate price change <i class="input-helper"></i></label>
                </div>
                <button type="button" class="btn btn-primary  border-radius-05 float-right " callback="after_update_price" onclick="ajaxCommonSumitForm(this)" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing " >  Update  Price</button>
              </div>
              

        </form>
        <div class="clearfix">

        </div>

        <hr/>
        <div class="header-title"> File Status :  {!! $file_flag !!} </div>
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

