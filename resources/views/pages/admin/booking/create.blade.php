@extends('layouts.dashboard')
@section('dashcontent')
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>  
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi mdi-library-books cust-box-icon"></i> @if(isset($stock)) {{'Update Stock'}} @else {{'Add New Stock'}} @endif</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "{{ url('/admin/stock/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($stock)){{en_de_crypt($stock->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($stock)) {{'Update Stock info'}} @else {{' Add Stock info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Invoice Number</label>
                <div class="col-sm-9">
                  <input type="number" name="invoice_no" id="invoice_no" class="form-control"
                  data-msg-required="Invoice No is required" value="@if(isset($stock)){{ $stock->invoice_no}}@endif" required/>
                  <label id="invoice_no-error-server" class="server-error" for="invoice_no"></label>
                </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Track Number</label>
                <div class="col-sm-9">
                  <input type="number" name="track_no" id="track_no" class="form-control"
                  data-msg-required="Track Number is required" value="@if(isset($stock)){{ $stock->track_no }}@endif"  required/>
                  <label id="track_no-server" class="server-error" for="amount"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Driver Name</label>
                <div class="col-sm-9">
                  <input type="text" name="driver_name" id="driver_name" class="form-control"
                  data-msg-required="Drive Name is required" value="@if(isset($stock)){{ $stock->driver_name }}@endif"  required/> 
                  <label id="driver_name-server" class="server-error" for="driver_name"></label> 
                </div>  
              </div>  
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Stock Bill</label>
                <div class="col-sm-9">
                  <input type="hidden" name="stock_imgval" id="stock_imgval" 
                  value="@if(!empty($stock->stock_image)){{$stock->image}}@endif">
                  <input type="file" name="stock_image" id="stock_image" class="dropify" data-default-file=" @if(!empty($stock->stock_image)){{ asset('images/stock') }}/{{$stock->stock_image}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Station Id</label>
                <div class="col-sm-9">
                  <select class="form-control dy_Station" name="Station_Id[]" id="Station_Id" multiple="multiple" required>
                      @if( isset($Stations) && !empty($Stations))
                      @foreach($Stations as $station)
                        <option value="{{$station->id}}" @if (isset($stock) && $stock->station_id == $station->id ) selected="selected" @endif> {{$station->title}}</option>
                      @endforeach
                      @endif
                  </select>
                  <label id="Station_Id-error-server" class="server-error" for="Station_Id"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Product Name</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="product_name">
                   @if(isset($stock->product_name))
                     <option value="{{$stock->product_name}}">{{$stock->product_name}}</option>
                   @endif 
                  </select>
                  <label id="product_name-error-server" class="server-error" for="product_name"></label>
                </div>
              </div>
              @if(!isset($stock))
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tank Name</label>
                <div class="col-sm-9">
                  <select class="form-control dy_tank" name="tank_id">
                   @if(isset($stock->tank_id))
                     <option value="{{$stock->tank_id}}">{{$stock->tank_id}}</option>
                   @endif 
                  </select>
                  <label id="tank_id-error-server" class="server-error" for="tank_id"></label>
                </div>
              </div>
              @endif
              @if(!isset($stock))
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Add Liters</label>
                <div class="col-sm-9">
                  <input type="number" name="total_number_liters_ordered" id="total_number_liters_ordered" class="form-control"
                   value="@if(!empty($stock->total_number_liters_ordered)){{$stock->total_number_liters_ordered}}@endif" />
                  <label id="total_number_liters_ordered-error-server" class="server-error" for="total_number_liters_ordered"></label>
                </div>
              </div>
              @endif
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Stock Status</label>
                <div class="col-sm-9">
                  <select class="form-control" name="stock_status" >
                          <option  @if (isset($stock->active) && $stock->active == "0" ) selected="selected" @endif value="0">Inactive</option>
                          <option  @if (isset($stock->active) && $stock->active == "1" ) selected="selected" @endif value="1">Active</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($tank->id)){{'Submit'}} @else {{'Update'}} @endif </button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
  $('.dropify').dropify();
});

$(document).on('change','#stock_image',function(){
  var data = new FormData($("#stock_frm")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    url: APP_URL +"/stock/img_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#stock_imgval').val(res);
        $('#stock_image').val(res);
      }
    }
  });
});

$("#Station_Id").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
});

$(document).on('change', '.dy_product', function(){
    if(this.value){
        var tank = $(".dy_tank").val();
        if(!tank){
            get_dytank_details(this.value,'');
        }else{
             get_dytank_details(this.value,tank);
        }
        
    }
});

function get_dytank_details(product_name,tank){
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });
    $('.dy_tank option').remove();
    $.ajax({
        url: APP_URL + '/get_tank_details',
        method: 'POST',
        data: {"product_name":product_name},
        success: function (data) {
            if (data.tankdata.length > 0) {
                $('.dy_tank').append("<option value =''> All Tank</option>");
                $.each(data.tankdata, function( index, value ) {
                    if(tank==value.eid){
                        $('.dy_tank')
                        .append($("<option value="+value.eid+">"+value.tank_name+"</option>").attr("selected","selected")); 
                    }else{
                        $('.dy_tank')
                        .append($("<option value="+value.eid+">"+value.tank_name+"</option>"));
                    }
                      
                 //});
                });   
            }
        },
    });
}
</script>

@endsection