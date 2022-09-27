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
        <h4 class="cust-card-title"><i class="mdi  mdi-gas-station cust-box-icon"></i> @if(isset($expense_data)) {{'Update Expense'}} @else {{'Add New Expense'}} @endif</h4>
          <form  name="expense_frm" id="expense_frm" class="form-sample" method="POST" action = "{{ url('/admin/expense/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($expense_data)){{en_de_crypt($expense_data->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($expense_data)) {{'Update User info'}} @else {{' Add User info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                  <input type="text" name="title" id="title" class="form-control"
                  data-msg-required="Title is required" value="@if(isset($expense_data)){{ $expense_data->title}}@endif" required/>
                  <label id="title-error-server" class="server-error" for="name"></label>
                </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Amount</label>
                <div class="col-sm-9">
                  <input type="number" name="amount" id="amount" class="form-control"
                  data-msg-required="Amount is required" value="@if(isset($expense_data)){{ $expense_data->amount }}@endif"  required/>
                  <label id="amount-error-server" class="server-error" for="amount"></label>
                </div>
              </div>  
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Expense Bill</label>
                <div class="col-sm-9">
                  <input type="hidden" name="expense_imgval" id="expense_imgval" 
                  value="@if(!empty($expense_data->image)){{$expense_data->image}}@endif">
                  <input type="file" name="expense_image" id="expense_image" class="dropify" data-default-file=" @if(!empty($expense_data->image)){{ asset('images/expense_img') }}/{{$expense_data->image}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>            
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Station Id</label>
                <div class="col-sm-9 add-select-muletile12">
                  <select class="form-control dy_Station" name="Station_Id[]" id="Station_Id" data-msg-required="stations is required"  multiple="multiple" required>
                      @php
                          $selected =" ";
                          if(isset($Stations) && !empty($Stations)){
                            foreach($Stations as $items){
                              if(!empty($station_id)){
                                if(in_array($items->id,$station_id)){

                                  $selected ="selected";
                                }else{
                                      $selected =" ";
                                }
                              }
                              echo '<option value="'.$items->id.'" '.$selected.'>'.$items->title.'</option>';
                            }  
                          }   
                      @endphp
                  </select>
                  <label id="Station_Id-error-server" class="server-error" for="Station_Id"></label>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                  <!-- <input type="text" name="description" id="description" class="form-control"
                  data-msg-required="Description is required" value=""  required/> -->
                  <textarea rows="4" class="form-control" cols="50" name="description" id="description">@if(isset($expense_data)){{ $expense_data->title }}@endif</textarea>
                  <label id="description-error-server" class="server-error" for="title"></label>
                </div>
              </div>
              <div class="form-group ">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($expense_data->id)){{'Submit'}} @else {{'Update'}} @endif </button>
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

$(document).on('change','#expense_image',function(){
  var data = new FormData($("#expense_frm")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    url: APP_URL +"/expense/img_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#expense_imgval').val(res);
        $('#expense_image').val(res);
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
</script>

@endsection