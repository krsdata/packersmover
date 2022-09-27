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
        <h4 class="cust-card-title"><i class="mdi mdi-codepen cust-box-icon"></i> @if(isset($draw)) {{'Update draw'}} @else {{'Add New draw'}} @endif</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "{{ url('/admin/draw/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($draw)){{en_de_crypt($draw->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($draw)) {{'Update draw info'}} @else {{' Add draw info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Product Name</label>
                <div class="col-sm-9">
                  <input type="text" name="draw_name" id="draw_name" class="form-control"
                  data-msg-required="Product Name is required" value="@if(isset($draw)){{ $draw->draw_name}}@endif" required/>
                  <label id="draw_name-error-server" class="server-error" for="draw_name"></label>
                </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Text</label>
                <div class="col-sm-9">
                  <input type="text" name="text" id="text" class="form-control"
                  data-msg-required="text is required" value="@if(isset($draw)){{ $draw->text }}@endif"  required/>
                  <label id="text-server" class="server-error" for="amount"></label>
                </div>
              </div>  

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">prize</label>
                <div class="col-sm-9">
                  <input type="number" name="prize" id="prize" class="form-control"
                  data-msg-required="prize is required" value="@if(isset($draw)){{ $draw->prize}}@endif" required/>
                  <label id="prize-error-server" class="server-error" for="prize"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Type</label>
                <div class="col-sm-9">
                  <select class="form-control" name="type">
                  <option value="0">No type</option>
                     <option value="week_draw">Week Draw</option>
                     <option value="month_draw">Month Draw</option>
                  </select>
                  <label id="tax-error-server" class="server-error" for="type"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Draw Image</label>
                <div class="col-sm-9">
                  <input type="hidden" name="image_val" id="image_val" 
                  value="@if(!empty($draw->image)){{$draw->image}}@endif">
                  <input type="file" name="image" id="image" class="dropify" data-default-file=" @if(!empty($draw->image)){{ asset('images/draw') }}/{{$draw->image}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Entry Fee</label>
                <div class="col-sm-9">
                  <input type="number" name="entry_fee" id="entry_fee" class="form-control"
                  data-msg-required="entry_fee is required" value="@if(isset($draw)){{ $draw->entry_fee}}@endif" required/>
                  <label id="entry_fee-error-server" class="server-error" for="entry_fee"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                  <select class="form-control" name="status" >
                          <option  @if (isset($draw->status) && $draw->status == "1" ) selected="selected" @endif value="1">active</option>
                          <option  @if (isset($draw->status) && $draw->status == "0" ) selected="selected" @endif value="0">Inactive</option>
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

$(document).on('change','#image',function(){
  var data = new FormData($("#stock_frm")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    url: APP_URL +"/product/img_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#image_val').val(res);
        $('#image').val(res);
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