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
        <h4 class="cust-card-title"><i class="mdi mdi-percent cust-box-icon"></i> @if(isset($faq)) {{'Update faq'}} @else {{'Add New faq'}} @endif</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "{{ url('/admin/faq/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($faq)){{en_de_crypt($faq->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($faq)) {{'Update faq info'}} @else {{' Add faq info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                  <input type="text" name="title" id="title" class="form-control"
                  data-msg-required="title is required" value="@if(isset($faq)){{ $faq->title}}@endif" required/>
                  <label id="title-error-server" class="server-error" for="title"></label>
                </div>
              </div>

            </div>
            <div class="col-md-8">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Decription</label>
                <div class="col-sm-9">
                  <input type="hidden" name="description" value="" id="description">
                <textarea cols="80" data-msg-required="description is required" id="editor1" rows="10">@if(isset($faq->description)){{$faq->description}}@endif  </textarea> 
                  <label id="description-error-server" class="server-error" for="amount"></label>
                </div>                
              </div>
              
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                  <select class="form-control" name="status" >
                          <option  @if (isset($faq->status) && $faq->status == "1" ) selected="selected" @endif value="1">Active</option>
                          <option  @if (isset($faq->status) && $faq->status == "0" ) selected="selected" @endif value="0">Inactive</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary  float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($faq->id)){{'Submit'}} @else {{'Update'}} @endif </button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  CKEDITOR.replace('editor1', {
    fullPage: true,
    extraPlugins: 'docprops',                  
    allowedContent: true,
    height: 320
  });
  
    CKEDITOR.instances.editor1.on('change', function() { 
    //var desc = $("#description").val();
    var desc = CKEDITOR.instances.editor1.getData();    
    $("#description").val(desc);
    //$("#editor3").val(desc);

});
  
  </script>

@endsection