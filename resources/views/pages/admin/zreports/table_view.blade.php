@if(!$order->isEmpty())

<p class="float-right"> Displaying {{$order->firstItem()}}-{{ $order->lastItem() }} of {{$order->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$order->total()}}  Records</p>
@endif

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-deskphone"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Order Count </p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$s_count}}</h3>
                <small class="mb-0">Count</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    
  
</div>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Order Id</th>
        <th>User Id</th>
        <th>Sale Time </th>
        <th>Total</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$order->isEmpty())
            @foreach($order as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="btn badge-primary btn-sm openTankStockDetails">{{$list->id}}</button></td>                
                <td class="text-capitalize">{{$list->user_id}}</td>
                <td class="text-capitalize">{{$list->created_at}}</td>
                <td class="text-capitalize">{{$list->total_amount}}</td>
                <td class="text-capitalize">{{$list->status}}</td>
                <td class="actions" data-th="">
                <a href="{{ url('/admin/sales/sales_detail/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-eye"></i></button></a>                  
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Expense Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $order->onEachSide(1)->links('ajax_pagination') !!}
</div>



<div id="sale_itemsdata" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wizard-title">Details</h5>
      </div>
      <div class="modal-body" style="padding: 10px 10px;">
        <!-- <div class="tab-content mt-2"> -->
        
          <div class="tab-pane fade show active" id="details" role="tabpanel">            
            <span>dshdjs</span>
            <hr>
          </div>
        <!-- </div> -->
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $('.sale_editorder').on('click', function(event) {
        var id = $(this).data("id");           
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/sales/get_sales_items',
                method: 'POST',
                data: {"id":id},
                success: function (data) {
                    if(data.success=="True"){                     
                      if(data.data){                      
                        $('#sale_itemsdata').modal('show');
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