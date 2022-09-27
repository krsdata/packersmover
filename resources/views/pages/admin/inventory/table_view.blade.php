@if(!$product->isEmpty())

<p class="float-right"> Displaying {{$product->firstItem()}}-{{ $product->lastItem() }} of {{$product->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$product->total()}}  Records</p>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Qty</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$product->isEmpty())
            @foreach($product as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="btn badge-primary btn-sm openTankStockDetails">{{$list->id}}</button></td>                
                <td class="text-capitalize">{{$list->product_name}}</td>
                <td class="text-capitalize" id="up_qty">{{$list->quantity}}</td>
                <td class="actions" data-th=""> 
                  <a href="#" class="stock_details" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Expense Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $product->onEachSide(1)->links('ajax_pagination') !!}
</div>

<div id="StockModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wizard-title">Edit Stock</h5>
      </div>
      <div class="modal-body" style="padding: 10px 10px;">
      <input type="hidden" id="edit_id" vallue="">
        <!-- <div class="tab-content mt-2"> -->
        
          <div class="tab-pane fade show active mb-3" id="details" role="tabpanel" >            
            <!-- <p id="e_title"></p> -->
            <label class="col-sm-6 col-form-label">Quantity</label>
            <div class="col-sm-12">
            <input type="number" placeholder="Enter Qty" name="quantity" id="quantity_edit" class="form-control" required/>
            </div>
            
          </div>
        <!-- </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  id="update_stock" data-dismiss="modal">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
// qty updates
  $('.stock_details').on('click', function(event) {
        var id =$(this).data("id");
        $("#edit_id").val(id);
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/inventory/get_stock_details',
                method: 'POST',
                data: {"id":id},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){
                         console.log(data);
                        // return false;
                        $("#quantity_edit").val(data.data.quantity);
                        $('#StockModal').modal('show');
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

  $('#update_stock').on('click', function(event) {
        var id = $("#edit_id").val();
        var quantity = $("#quantity_edit").val();
       
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/inventory/get_update_stock',
                method: 'POST',
                data: {"id":id,"quantity":quantity},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){
                        // console.log(data.data.quantity);                        
                        location.reload();
                        $('#StockModal').modal('hide');
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
</script>
