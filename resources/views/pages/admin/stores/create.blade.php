@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi  mdi-account cust-box-icon"></i> @if(isset($user_data)) {{'Update User'}} @else {{'Add New User'}} @endif</h4>
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
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-9">
                  <input type="text" name="first_name" id="first_name" class="form-control"
                  data-msg-required="first name is required" value="@if(isset($user_data)){{ $user_data->name}}@endif" required/>
                  <label id="first_name-error-server" class="server-error" for="first_name"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-9">
                  <input type="text" name="last_name" id="last_name" class="form-control"
                  data-msg-required="last name is required" value="@if(isset($user_data)){{ $user_data->last_name }}@endif"  required/>
                  <label id="last_name-error-server" class="server-error" for="last_name"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
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
              <div class="form-group row">
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
            <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Account Type</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="type" id="type" data-msg-required="account type is required" required >
                        <option value=""></option>
                        {{-- <option value="admin" @if (isset($user_data->type) && $user_data->type == "admin" ) selected="selected" @endif>Admin</option> --}}
                        <option value="owner"  @if (isset($user_data->type) && $user_data->type == "owner" ) selected="selected" @endif>Owner</option>
                        <option value="manager"  @if (isset($user_data->type) && $user_data->type == "manager" ) selected="selected" @endif>Manager</option>
                        <option value="company"  @if (isset($user_data->type) && $user_data->type == "company" ) selected="selected" @endif>Company</option>
                        <option value="user"  @if (isset($user_data->type) && $user_data->type == "user" ) selected="selected" @endif>User</option>
                        <option value="accountant"  @if (isset($user_data->type) && $user_data->type == "accountant" ) selected="selected" @endif>Accountant</option>
                        <option value="account-manager"  @if (isset($user_data->type) && $user_data->type == "account-manager" ) selected="selected" @endif>Account Manager</option>
                      </select>
                      <label id="type-error-server" class="server-error" for="type"></label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6" style="display:none">
                      <div class="form-group row">
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
              <div class="col-md-6" style="display:none">
                <div class="form-group row date-picker-custom-chage">
                  <label class="col-sm-3 col-form-label">Date of Birth</label>
                  <div class="col-sm-9">
                      <div id="datepicker-popup" class="input-group date datepicker">
                <input type="text" name="dob" id="dob" class="date form-control" @if(isset($user_data->dob)) value="<?php echo date('m/d/Y',strtotime($user_data->dob)); ?>"@endif>
                <span class="input-group-addon input-group-append border-left">
                  <span class="mdi mdi-calendar input-group-text"></span>
                </span>
              </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Password
                        <button type="button" class=" btn-rounded p-1 btn btn-danger" data-toggle="tooltip" title="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." data-content="Sed posuere consectetur est at lobortis. Aenean eu leo quam."><i class="mdi mdi-alert btn-icon-prepend m-0"></i></button></label>
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
              <div class="col-md-6 staffmode "  style="@role('manager') opacity:0!important @endrole"  >
                  <div class="form-group row ">
                    <label class="col-sm-3 col-form-label">Stations</label>
                    <div class="col-sm-9">
                      <!-- <select class="form-control" name="stations_id" id="stations_id" data-msg-required="stations is required"  data-value="@if(isset($user_data)){{ $user_data->stations_id }}@endif" required >
                        @if( isset($stations) && !empty($stations))
                          @foreach($stations as $station)
                            <option value="{{$station->id}}" @if( isset($user_data->stations_id) && !empty($user_data->stations_id) && in_array($station->id, explode(",",$user_data->stations_id )) ) selected @endif> {{$station->title}}</option>
                          @endforeach
                        @endif
                       </select> -->
                       <select class="form-control" name="stations_id" id="stations_id"  data-msg-required="stations is required"  multiple="multiple" required>
                      @php
                          $selected =" ";
                          if(isset($stations) && !empty($stations)){
                            foreach($stations as $station){
                              if(!empty($station_id)){
                                if(in_array($station->id,$station_id)){

                                  $selected ="selected";
                                }else{
                                      $selected =" ";
                                }
                              }
                              echo '<option value="'.$station->id.'" '.$selected.'>'.$station->title.'</option>';
                            }  
                          }   
                      @endphp
                  </select>
                      <label id="type-error-server" class="server-error" for="type"></label>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6 usermode" style="display:none">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="card_number">Card Number</label>
                    <div class="col-sm-9">
                        <input type="text" name="card_number" id="card_number" class="form-control" data-msg-required="card number is required." value="@if(isset($user_data)){{ $user_data->card_number }}@endif"  required/>
                        <label id="card_number-error-server" class="server-error" for="card_number"></label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Active</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="active" >
                              <option  @if (isset($user_data->active) && $user_data->active == "0" ) selected="selected" @endif value="0">Inactive</option>
                              <option  @if (isset($user_data->active) && $user_data->active == "1" ) selected="selected" @endif value="1">Active</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Email Notification</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="mail_notify" >
                              <option  @if (isset($user_data->mail_notify) && $user_data->mail_notify == "0" ) selected="selected" @endif value="0">Inactive</option>
                              <option  @if (isset($user_data->mail_notify) && $user_data->mail_notify == "1" ) selected="selected" @endif value="1">Active</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 usermode" style="display:none">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Card Description</label>
                    <div class="col-sm-9">
                      <input type="text" name="card_desc" id="card_desc" class="form-control" data-msg-required="Card Description is required." value="@if(isset($user_data)) {{ $user_data->card_desc }} @endif"  required/>
                        <label id="card_desc-error-server" class="server-error" for="card_desc"></label>
                    </div>
                  </div>
                </div>
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

// $("#stations_id").select2({
//     placeholder: "Select a station",
//     allowClear: true,
//     width: '100%',
//     maximumSelectionLength: 1
// });

</script>

@endsection