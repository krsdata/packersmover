@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-file-document cust-box-icon"></i>
                     Item Inventory
                  </h4>
               </div>
               <div class="col-md-1">
                  <a href="#" class="all_items"><button class="btn badge-primary btn-xs"><i class="mdi mdi-plus-circle"></i></button></a>                  
                </div>
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">          
                    <div class="col-md-5">
                        <input type="text" autoComplete="off" placeholder="Search Product" name="product_name" id="search_product" class="form-control">
                      </div> 
                      <div class="col-md-4 d-flex">
                        <button class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div>                       
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                       
            <div id="table_filter_view">
               @include('pages.admin.inventory.table_view')
            </div>
         </div>
      </div>
   </div>
</div>


<!-- All dropdwn list and update qty -->
<div id="QtyUpdat" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wizard-title">Add Stock</h5>
      </div>
      <div class="modal-body" style="padding: 10px 10px;">
      <input type="hidden" id="edit_id" vallue="">
        <!-- <div class="tab-content mt-2"> -->
        
          <div class="tab-pane fade show active" id="details" role="tabpanel">            
            <!-- <p id="e_title"></p> -->
            <div class="form-group row">
               <label class="col-sm-2 col-form-label">Item</label>
               <div class="col-sm-9">
               <select class="form-control dy_product" id="product_id">                  
                   @if(isset($product))
                   @foreach($product as $ppdata)
                     <option value="{{en_de_crypt($ppdata->id,'e')}}">{{$ppdata->product_name}}</option>
                     @endforeach
                   @endif 
                  </select>
                  </div>
            </div>
            <div class="form-group row mb-3">
               <label class="col-sm-2 col-form-label">Qty</label>
               <div class="col-sm-9">
               <input type="number" placeholder="Enter Qty" name="quantity" id="quantity_add" class="form-control" required/>
               </div>
            </div>
           
          </div>
        <!-- </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  id="addnew_stock" data-dismiss="modal">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  // $('#status').on('change', function (evt) {
  //     $( ".common_search_filter" ).click();
  // });
  $('ul').on('click', '.click_me', function(){
    $(this).toggleClass('active').siblings().removeClass('active');   // <--- The trick!
  })

  $("#station").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
});


$('.all_items').on('click', function(event) {
   $('#QtyUpdat').modal('show');
  });

  $('#addnew_stock').on('click', function(event) {
      var id = $( "#product_id").val();
      var quantity = $("#quantity_add").val();
       
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/inventory/add_new_stock',
                method: 'POST',
                data: {"id":id,"quantity":quantity},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){
                        // console.log(data.data.quantity);                        
                        location.reload();
                        $('#QtyUpdat').modal('hide');
                     }
                    }else{
                        swal(
                            'Error!',
                            data.msg,
                            'error'
                        )
                      //location.reload();
                    }  
                }
            });
        }
  });


  $('#search_product').on('keyup', function(event) {
      var product_name = $( "#search_product").val();
        if(product_name){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/inventory/search_inventory_data',
                method: 'POST',
                data: {"product_name":product_name},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){  
                        $('tbody').html(data.data);
                     }
                    }else{
                      $('tbody').html(data.data);
                        // swal(
                        //     'Error!',
                        //     data.msg,
                        //     'error'
                        // )
                      //location.reload();
                    }  
                }
            });
        }
        else{
          location.reload(true);
        }
  });

</script>
@endsection
