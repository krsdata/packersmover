@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi  mdi-gas-station cust-box-icon"></i> @if(isset($station_data)) {{'Update Station'}} @else {{'Add New Station'}} @endif</h4>
          <form  name="station_frm" id="station_frm" class="form-sample" method="POST" action = "{{ url('/admin/station/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($station_data)){{en_de_crypt($station_data->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($station_data)) {{'Update User info'}} @else {{' Add User info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Folder Name</label>
                <div class="col-sm-9">
                  <input type="text" name="name" id="name" class="form-control"
                  data-msg-required="folder name is required" value="@if(isset($station_data)){{ $station_data->name}}@endif" required/>
                  <label id="name-error-server" class="server-error" for="name"></label>
                </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Station Title</label>
                <div class="col-sm-9">
                  <input type="text" name="title" id="title" class="form-control"
                  data-msg-required="Station title is required" value="@if(isset($station_data)){{ $station_data->title }}@endif"  required/>
                  <label id="title-error-server" class="server-error" for="title"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Currency Code</label>
                <div class="col-sm-9">
                  <input type="text" name="currency_code" id="currency_code" class="form-control"
                  data-msg-required="Station currency is required" value="@if(isset($station_data)){{ $station_data->currency_code }}@endif"  required/>
                  <label id="currency_code-error-server" class="server-error" for="currency_code"></label>
                </div>
              </div> 
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Service Station </label>
                <div class="col-sm-9">
                  <input type="text" name="service_station" id="service_station" class="form-control"
                  data-msg-required="Service Station is required" value="@if(isset($station_data)){{ $station_data->service_station }}@endif"  required/>
                  <label id="service_station-error-server" class="server-error" for="service_station"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">TIN</label>
                <div class="col-sm-9">
                  <input type="text" name="tin" id="tin" class="form-control"
                  data-msg-required="Tin is required" value="@if(isset($station_data)){{ $station_data->tin }}@endif"  required/>
                  <label id="tin-error-server" class="server-error" for="tin"></label>
                </div>
              </div> 
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tel</label>
                <div class="col-sm-9">
                  <input type="text" name="tel" id="tel" class="form-control"
                  data-msg-required="Telephone No is required" value="@if(isset($station_data)){{ $station_data->tel }}@endif"  required/>
                  <label id="tel-error-server" class="server-error" for="tel"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Decimal Point</label>
                <div class="col-sm-9">
                  <select class="form-control" name="decimal_point" >
                    <option  @if (isset($station_data->decimal_point) && $station_data->decimal_point == "2" ) selected="selected" @endif value="2">2</option>
                    <option  @if (isset($station_data->decimal_point) && $station_data->decimal_point == "1" ) selected="selected" @endif value="1">1</option>
                    <option  @if (isset($station_data->decimal_point) && $station_data->decimal_point == "0" ) selected="selected" @endif value="0">0</option>
                  </select>
                  <label id="decimal_point-error-server" class="server-error" for="decimal_point"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Station Information</label>
                <div class="col-sm-9">
                  <input type="text" name="info" id="info" class="form-control"
                  data-msg-required="Station information is required" value="@if(isset($station_data)){{ $station_data->info }}@endif"  required/>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Active</label>
                <div class="col-sm-9">
                  <select class="form-control" name="active" >
                          <option  @if (isset($station_data->active) && $station_data->active == "0" ) selected="selected" @endif value="0">Inactive</option>
                          <option  @if (isset($station_data->active) && $station_data->active == "1" ) selected="selected" @endif value="1">Active</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Serial Number</label>
                <div class="col-sm-9">
                  <input type="text" name="serial_number" id="serial_number" class="form-control"
                  data-msg-required="Serial Number is required" value="@if(isset($station_data)){{ $station_data->serial_number }}@endif"  required/>
                  <label id="serial_number-error-server" class="server-error" for="serial_number"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">VRN</label>
                <div class="col-sm-9">
                  <input type="text" name="vrn" id="vrn" class="form-control"
                  data-msg-required="Station currency is required" value="@if(isset($station_data)){{ $station_data->vrn }}@endif"  required/>
                  <label id="vrn-error-server" class="server-error" for="vrn"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Station Logo</label>
                <div class="col-sm-9">
                  <input type="hidden" name="station_imgval" id="station_imgval" 
                  value="@if(!empty($logos)){{$logos}}@endif">
                  <input type="file" name="station_image" id="station_image" class="dropify" data-default-file=" @if(!empty($logos)){{ asset('images/station_logo') }}/{{$logos}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside"> 
                </div>   
              </div>
              <div class="form-group ">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary mr-0 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($station_data->id)){{'Submit'}} @else {{'Update'}} @endif </button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function change_craete_user_view(type){
  if(type == "user"){
    $(".usermode").show();
    $(".staffmode").hide();
  }else{
    $(".staffmode").show();
    $(".usermode").hide();
  }
  if(type == "owner"){
    $("#stations_id").attr("multiple","multiple");
    $("#stations_id").attr("name","stations_id[]");
  }else{
    $("#stations_id").removeAttr("multiple");
    $("#stations_id").attr("name","stations_id");
  }
}
$(document).on("change","#type",function(){
  type = $(this).val();
  change_craete_user_view(type);
});
$(document).ready(function(){
  type = $("#type").val();
  change_craete_user_view(type);
  $('.dropify').dropify();

  $('.dropify-clear').on('click', function(event) {
      $('#station_imgval').val(' ');
  });
});
$(document).ready(function() {
  val = $('#stations_id').attr("data-value");
  if(val){
    aval = val.split(",");
    $("#stations_id").val(aval);
  }
});

$(document).on('change','#station_image',function(){
  var data = new FormData($("#station_frm")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    url: APP_URL +"/station/img_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#station_img').val(res);
        $('#station_imgval').val(res);
      }
    }
  });
});


</script>

@endsection
