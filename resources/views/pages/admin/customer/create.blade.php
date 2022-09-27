@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi  mdi-account cust-box-icon"></i> @if(isset($customer_data)) {{'Update User'}} @else {{'Add New User'}} @endif</h4>
          <form  class="form-sample" method="POST" action = "{{ url('/admin/customer/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="user_id" id="user_id" value="@if(isset($customer_data)){{en_de_crypt($customer_data->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($customer_data)) {{'Update User info'}} @else {{' Add User info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                  <input type="text" name="name" id="name" class="form-control"
                  data-msg-required="Name is required" value="@if(isset($customer_data)){{ $customer_data->name}}@endif" required/>
                  <label id="name-error-server" class="server-error" for="name"></label>
                </div>
              </div>
            </div>
           
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-9">
                  <input type="text" name="email" id="email" class="form-control"
                  data-msg-required="email is required" value="@if(isset($customer_data)){{ $customer_data->email }}@endif"  required />
                  <label id="email-error" class="error" for="email"></label>
                  <label id="email-error-server" class="server-error" for="email"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Contact Number</label>
                <div class="col-sm-9">
                  <input type="text" name="mobile_no" id="mobile_no" class="form-control mobile_no"
                  data-msg-required="mobile_no is required" value="@if(isset($customer_data)){{ $customer_data->mobile_no }}@endif"  required />
                  <label id="mobile_no-error-server" class="server-error" for="mobile_no"></label>
                </div>
              </div>
            </div>
          </div>                
        </div>
        <div class="col-md-12 mb-4">
          <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
          <button type="submit" class="btn btn-primary mr-3 float-right " data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($customer_data->id)){{'Submit'}} @else {{'Update'}} @endif </button>
        </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection