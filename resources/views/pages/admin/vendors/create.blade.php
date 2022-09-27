@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi  mdi-account cust-box-icon"></i> @if(isset($user_data)) {{'Update Vendors'}} @else {{'Add New Vendors'}} @endif</h4>
          <form name="pdf_up" id="pdf_up" class="form-sample" method="POST" action = "{{ url('/admin/vendors/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="user_id" id="user_id" value="@if(isset($user_data)){{en_de_crypt($user_data->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($user_data)) {{'Update Vendors info'}} @else {{' Add Vendors info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Business Name</label>
                <div class="col-sm-9">
                  <input type="text" name="bussiness_name" id="bussiness_name" class="form-control"
                  data-msg-required="Business is required" value="@if(isset($user_data)){{ $user_data->bussiness_name}}@endif" required/>
                  <label id="bussiness_name-error-server" class="server-error" for="bussiness_name"></label>
                </div>
              </div>
            </div>
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
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                  <input type="text" name="address" id="address" class="form-control"
                  data-msg-required="Address is required" value="@if(isset($user_data)){{ $user_data->address}}@endif" required/>
                  <label id="address-error-server" class="server-error" for="address"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">City</label>
                <div class="col-sm-9">
                  <input type="text" name="city" id="city" class="form-control"
                  data-msg-required="City is required" value="@if(isset($user_data)){{ $user_data->city}}@endif" required/>
                  <label id="city-error-server" class="server-error" for="city"></label>
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
                        <!-- <option value=""></option> -->
                        <option value="lotto-manager"  @if (isset($user_data->type) && $user_data->type == "lotto-manager" ) selected="selected" @endif>Vendors</option>
                      </select>
                      <label id="type-error-server" class="server-error" for="type"></label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
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
           </div>
          <div class="row"> 
          <div class="col-md-6" style="display:none;">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="active" >
                              <option  @if (isset($user_data->active) && $user_data->active == "0" ) selected="selected" @endif>Deactive</option>
                              <option  @if (isset($user_data->active) && $user_data->active == "1" ) selected="selected" @endif>Active</option>
                          </select>
                        </div>
                      </div>
                    </div>

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

              <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Certificate Password</label>
                <div class="col-sm-9">
                  <input type="text" name="certificate_pass" id="certificate_pass" class="form-control certificate_pass"
                  data-msg-required="certificate password is required" value="@if(isset($user_data)){{ $user_data->certificate_pass }}@endif"  required />
                  <label id="certificate_pass-error-server" class="server-error" for="certificate_pass"></label>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">TIN Number</label>
                <div class="col-sm-9">
                  <input type="text" name="certificate_tin" id="certificate_tin" class="form-control certificate_tin"
                  data-msg-required="tin number is required" value="@if(isset($user_data)){{ $user_data->certificate_tin }}@endif"  required />
                  <label id="certificate_tin-error-server" class="server-error" for="certificate_tin"></label>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Serial Number</label>
                <div class="col-sm-9">
                  <input type="text" name="CERTKEY" id="CERTKEY" class="form-control CERTKEY"
                  data-msg-required="serial nummber is required" value="@if(isset($user_data)){{ $user_data->CERTKEY }}@endif"  required />
                  <label id="CERTKEY-error-server" class="server-error" for="CERTKEY"></label>
                </div>
              </div>
            </div>

              <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Upload Certificate</label>                   
                  <div class="col-sm-9">
                  <div class="form-group">
                      @if(!empty($user_data->image))              
                      <input type="hidden" name="image_val" id="image_val">       
                      <input type="file" name="image" id="image" class="dropify"data-default-file="{{ asset('certificate') }}/{{$user_data->image }}" data-allowed-file-extensions="png jpg jpeg pfx" data-errors-position="outside">  
                      @else      
                      <input type="hidden" name="image_val" id="image_val">                
                      <input type="file" name="image" id="image" class="dropify"data-default-file="{{ asset('images/logo/logo.png') }}" data-allowed-file-extensions="png jpg jpeg pfx" data-errors-position="outside">
                      @endif
                    </div>
                </div>
                </div>
              </div>

          </div>
          <div class="row">             
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

$(document).ready(function(){
         $('.dropify').dropify();
   });

$(document).on('change','#image',function(){
  var data = new FormData($("#pdf_up")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    url: APP_URL +"/admin/certificate_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#image_val').val(res);
        console.log(res);
        //$('#image').val(res);
      }
    }
  });
});

// $("#stations_id").select2({
//     placeholder: "Select a station",
//     allowClear: true,
//     width: '100%',
//     maximumSelectionLength: 1
// });

</script>

@endsection