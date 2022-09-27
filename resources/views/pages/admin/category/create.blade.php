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
        <h4 class="cust-card-title"><i class="mdi mdi-format-list-bulleted cust-box-icon"></i> @if(isset($category)) {{'Update Category'}} @else {{'Add New Category'}} @endif</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "{{ url('/admin/category/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($category)){{en_de_crypt($category->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($stock)) {{'Update Category info'}} @else {{' Add Category info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Category Name</label>
                <div class="col-sm-9">
                  <input type="text" name="name" id="name" class="form-control"
                  data-msg-required="Name is required" value="@if(isset($category)){{ $category->name}}@endif" required/>
                  <label id="name-error-server" class="server-error" for="name"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Main Category</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="parent_id">
                  <option value="0">Select Category</option>
                   @if(isset($Categorys))
                   @foreach($Categorys as $cat)
                     <option value="{{$cat->id}}" @if(isset($category) && $category->parent_id==$cat->id) selected @endif >{{$cat->name}}</option>
                     @endforeach
                   @endif 
                  </select>
                  <label id="category-error-server" class="server-error" for="category"></label>
                </div>
              </div>

             
        
            </div>


            <div class="col-md-6">

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Category Image</label>
                <div class="col-sm-9">
                  <input type="hidden" name="image_val" id="image_val" 
                  value="@if(!empty($category->image)){{$category->image}}@endif">
                  <input type="file" name="image" id="image" class="dropify" data-default-file=" @if(!empty($category->image)){{ asset('images/category') }}/{{$category->image}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>
              
            

              <div class="form-group">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary  float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($tank->id)){{'Submit'}} @else {{'Update'}} @endif </button>
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
    url: APP_URL +"/category/img_upload",
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





</script>

@endsection