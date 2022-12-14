@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi  mdi-account cust-box-icon"></i> @if(isset($user_data)) {{'Update Supervisor'}} @else {{'Add New Supervisor'}} @endif</h4>
          <form  class="form-sample" method="POST" action = "{{ url('/admin/user/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="user_id" id="user_id" value="@if(isset($user_data)){{en_de_crypt($user_data->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($user_data)) {{'Update User info'}} @else {{' Add User info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-0">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-9">
                  <input type="text" name="first_name" id="first_name" class="form-control"
                  data-msg-required="first name is required" value="@if(isset($user_data)){{ $user_data->name}}@endif" required/>
                  <label id="first_name-error-server" class="server-error" for="first_name"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-0">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-9">
                  <input type="text" name="last_name" id="last_name" class="form-control"
                  data-msg-required="last name is required" value="@if(isset($user_data)){{ $user_data->last_name }}@endif"  required/>
                  <label id="last_name-error-server" class="server-error" for="last_name"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-0">
                <label class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-9">
                  <input type="text" name="email" id="email" class="form-control   user_validate"
                  data-msg-required="email is required" value="@if(isset($user_data)){{ $user_data->email }}@endif"  required />
                  <label id="email-error" class="error" for="email"></label>
                  <label id="email-error-server" class="server-error" for="email"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-0">
                <label class="col-sm-3 col-form-label">Contact Number</label>
                <div class="col-sm-9">
                  <input type="text" name="contact" id="contact" class="form-control contact"
                  data-msg-required="contact is required" value="@if(isset($user_data)){{ $user_data->contact }}@endif"  required />
                  <label id="contact-error-server" class="server-error" for="contact"></label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
                <div class="col-md-6" style="display:none;">
                      <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label">Gender</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="gender" >
                                  <option  @if (isset($user_data->gender) && $user_data->gender == "Male" ) selected="selected" @endif>Male</option>
                                  <option  @if (isset($user_data->gender) && $user_data->gender == "Female" ) selected="selected" @endif>Female</option>
                          </select>
                        </div>
                      </div>
                    </div>
          {{-- </div>
          <div class="row"> --}}
              <div class="col-md-6">
                <div class="form-group row mb-0">
                    <label class="col-sm-3 col-form-label">Password
                        <button type="button" class=" btn-rounded p-1 btn btn-danger" data-toggle="tooltip" title="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." ></button></label>
                  <div class="col-sm-9">
                    <input type="Password" name="password" id="pass" @if(!isset($user_data->id)) class="form-control pass" @else class="form-control "  @endif
                    data-msg-required="password is required."
                    data-msg-pattern="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." @if(!isset($user_data->id)) required  @endif @if(isset($user_data->id)) placeholder="Change Password"  @endif/>
                    <label id="password-error-server" class="server-error" for="password"></label>
                </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group row mb-0">
                    <label class="col-sm-3 col-form-label">Confirm Password
                        <button type="button" class=" btn-rounded p-1 btn btn-danger" data-toggle="tooltip" title="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." ></button></label>
                  <div class="col-sm-9">
                    <input type="Password" name="password" id="pass" @if(!isset($user_data->id)) class="form-control pass" @else class="form-control "  @endif
                    data-msg-required="password is required."
                    data-msg-pattern="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." @if(!isset($user_data->id)) required  @endif @if(isset($user_data->id)) placeholder="Change Password"  @endif/>
                    <label id="password-error-server" class="server-error" for="password"></label>
                </div>
                </div>
              </div>
          </div>

          <div class="row">
                    <input type="hidden" name="type" value="user">
                <div class="col-md-12">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($user_data->id)){{'Submit'}} @else {{'Update'}} @endif </button>
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
  $("#stations_id").val('').trigger('change');
  change_craete_user_view(type);
});
$(document).ready(function(){
  type = $("#type").val();
  change_craete_user_view(type);
});
$(document).ready(function() {
  val = $('#stations_id').attr("data-value");
  if(val){
    aval = val.split(",");
    $("#stations_id").val(aval);
  }
});

$("#stations_id").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    //maximumSelectionLength: 1
});
$(document).ready(function() {
    $("#card_number").select2({
      placeholder: "Select a Card Number",
//     allowClear: true,
       width: '100%',
       //maximumSelectionLength: 1
    });
      
    $("#btn-add-state").on("click", function(){
      var newStateVal = $("#new_card_number").val();
      // Set the value, creating a new option if necessary
      if ($("#card_number").find("option[value='" + newStateVal + "']").length) {
        $("#card_number").val(newStateVal).trigger("change");
      } else { 
        // Create the DOM option that is pre-selected by default
        var newState = new Option(newStateVal, newStateVal, true, true);
        // Append it to the select
        $("#card_number").append(newState).trigger('change');
      } 
    });  
});


if(!$('#user_id').val()){
     //alert("not")
}else{
   //alert("yes")
    var exist = {};
    $('#card_number > option').each(function() {
        if (exist[$(this).val()]){
            $(this).remove();
        }else{
            exist[$(this).val()] = true;
        }
    });
}



</script>

@endsection