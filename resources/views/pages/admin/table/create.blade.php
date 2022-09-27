@extends('layouts.dashboard')
@section('dashcontent')
@php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
  @endphp
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="transaction-common-header_view">
          <span class="transaction-list-cust-box"> <i class="la la-money-bill-alt cust-box-icon"></i> </span>
        <h4 class="cust-card-title"> @if(isset($data))  Update Table   @else  Add New Table  @endif   </h4>
        <span class="company-list-cust-back"> <a href=""> <i class="la la-arrow-left cust-box-icon float-right"></i> </a> </span>
        </div>
          <form id="addtrn" class="form-sample" method="POST" action = "{{route('tables_stores')}}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

          <input type="hidden" name="id" id="id" value="@if(isset($data)){{en_de_crypt($data->id,'e')}}@endif">
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label"> Table Name </label>
                <div class="col-sm-8">
                <input type="text" name="table_name" id="table_name" class="form-control table_name"
                  data-msg-required="Enter valid table name" value="@if(isset($data)){{ $data->table_name }}@endif"  @if(isset($data->id)) readonly="true"  @endif required />
                  <label id="table_name-error" class="error" for="table_name"></label>
                  <label id="table_name-server" class="server-error" for="table_name"></label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Column  Name </label>
                <div class="col-sm-8">
                <input type="text" name="column_name" id="column_name" class="form-control column_name"
                  data-msg-required="Enter valid column name" value=""  required />
                  <label id="column_name-error" class="error" for="column_name"></label>
                  <label id="column_name-error-server" class="server-error" for="column_name"></label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label"> Column  Type </label>
                <div class="col-sm-8">
                <input type="text" name="column_type" id="column_type" class="form-control column_type"
                  data-msg-required="Enter valid  column type" value=""   required />
                  <label id="column_type-error" class="error" for="column_type"></label>
                  <label id="column_type-error-server" class="server-error" for="column_type"></label>
              </div>
            </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Is Fillable  </label>
                <div class="col-sm-8">
                  <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="Is_Fillable" name="Is_Fillable">
                      <label class="custom-control-label" for="Is_Fillable">      </label>
                  </div>
              </div>
            </div>
            </div>
          </div>
        
           
          <div class="row">
                <div class="col-md-12 custom-control custom-radio">
                  <button type="reset" value="reset" id="create_action_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($data->id)) {{ __('a.Submit') }} @else  {{ __('a.Update') }} @endif </button>
                </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  subprice = {};
  @php
    if(isset($subprice)){
      @endphp
      subprice = @php echo $subprice @endphp
      @php
    }
  @endphp

  $(document).ready(function () {
    $('#datepicker1').datepicker({
           startDate: new Date(),
           autoclose: true,
           format: 'yyyy-mm-dd',

     }).on("change", function(e) {
      get_expiry_date($("#subscription_type").val(),$("#datepicker1").val(),$("#company_id").val());
    });
    $("#subscription_type").trigger("change");
  })

  $(document).on("change","#subscription_type",function(){
    get_expiry_date($("#subscription_type").val(),$("#datepicker1").val(),$("#company_id").val());
    suid = $("#subscription_type").val();
    price = subprice[suid]
    $("#price").val(price);
  });

  $("#addtrn").validate({
  focusInvalid: true,
  invalidHandler: function(form, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
          var firstInvalidElement = $(validator.errorList[0].element);
          $('html,body').scrollTop(firstInvalidElement.offset().top + 50 );
          firstInvalidElement.focus();
      }
  }
});

</script>
@endsection
