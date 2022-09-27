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
        <h4 class="cust-card-title"><i class="mdi mdi-deskphone cust-box-icon"></i> @if(isset($product)) {{'Update Product'}} @else {{'Add New Product'}} @endif</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "{{ url('/admin/product/store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
          <input type="hidden" name="id" id="id" value="@if(isset($product)){{en_de_crypt($product->id,'e')}}@endif">
          {{-- <p class="card-description">
              @if(isset($product)) {{'Update Product info'}} @else {{' Add Product info'}} @endif
          </p> --}}
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Product Name</label>
                <div class="col-sm-9">
                  <input type="text" name="product_name" id="product_name" class="form-control"
                  data-msg-required="Product Name is required" value="@if(isset($product)){{ $product->product_name}}@endif" required/>
                  <label id="product_name-error-server" class="server-error" for="product_name"></label>
                </div>
              </div>
          
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                  <input type="text" name="description" id="description" class="form-control"
                  data-msg-required="Track Number is required" value="@if(isset($product)){{ $product->description }}@endif"  required/>
                  <label id="description-server" class="server-error" for="amount"></label>
                </div>
              </div>  

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Sale Price</label>
                <div class="col-sm-9">
                  <input type="number" name="sale_price" id="sale_price" class="form-control"
                  data-msg-required="Sale Price is required" value="@if(isset($product)){{ $product->sale_price}}@endif" required/>
                  <label id="sale_price-error-server" class="server-error" for="sale_price"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tax</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="tax">
                  <option value="No tax">No Tax</option>
                   @if(isset($tax))
                   @foreach($tax as $tname)
                     <option value="{{$tname->tax_name}}">{{$tname->tax_name}}</option>
                     @endforeach
                   @endif 
                  </select>
                  <label id="tax-error-server" class="server-error" for="tax"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Product Image</label>
                <div class="col-sm-9">
                  <input type="hidden" name="image_val" id="image_val" 
                  value="@if(!empty($product->image)){{$product->image}}@endif">
                  <input type="file" name="image" id="image" class="dropify" data-default-file=" @if(!empty($product->image)){{ asset('images/stock') }}/{{$product->image}}@endif" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>
              
            </div>
            <div class="col-md-6">              
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Category Name</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="category">
                   @if(isset($category))
                   @foreach($category as $cat)
                     <option value="{{$cat->id}}">{{$cat->name}}</option>
                     @endforeach
                   @endif 
                  </select>
                  <label id="category-error-server" class="server-error" for="category"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Price</label>
                <div class="col-sm-9">
                  <input type="number" name="price" id="price" class="form-control"
                  data-msg-required="Price is required" value="@if(isset($product)){{ $product->price}}@endif" required/>
                  <label id="price-error-server" class="server-error" for="price"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Quantity</label>
                <div class="col-sm-9">
                  <input type="number" name="quantity" id="quantity" class="form-control"
                  data-msg-required="Invoice No is required" value="@if(isset($product)){{ $product->quantity}}@endif" required/>
                  <label id="quantity-error-server" class="server-error" for="quantity"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Discount</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="discount">
                  <option value="0">No Discount</option>
                   @if(isset($discount))
                   @foreach($discount as $dname)
                     <option value="{{$dname->name}}">{{$dname->name}}</option>
                     @endforeach
                   @endif 
                  </select>
                  <label id="discount-error-server" class="server-error" for="discount"></label>
                </div>
              </div>
             
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Product Status</label>
                <div class="col-sm-9">
                  <select class="form-control" name="status" >
                          <option  @if (isset($product->status) && $product->status == "0" ) selected="selected" @endif value="0">Inactive</option>
                          <option  @if (isset($product->status) && $product->status == "1" ) selected="selected" @endif value="1">active</option>
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