<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-elevation-rise"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Stock Count </p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$s_count}}</h3>
                <small class="mb-0">Count</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-oil-temperature"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">TOTAL Liter</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$total_liter}}</h3>
                <small class="mb-0">Ltr</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@if(!$tankstock->isEmpty())
<p class="float-right"> Displaying {{$tankstock->firstItem()}}-{{ $tankstock->lastItem() }} of {{$tankstock->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$tankstock->total()}}  Records</p>
@endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Invoice Number</th>
        <th>Track Number</th>
        <th>Pump Start Volume Total </th>
        <th>Pump End Volume Total</th>
        <th>VOL</th>
        <th>Date Time</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$tankstock->isEmpty())
            @foreach($tankstock as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="btn badge-primary btn-sm openTankStockDetails">{{$list->id}}</button></td>
                <td>{{$list->invoice_no}}</td>
                <td class="text-capitalize">{{$list->track_no}}</td>
                <td class="text-capitalize">{{$list->Pump_Start_Volume_Total}}</td>
                <td class="text-capitalize">{{$list->Pump_End_Volume_Total}}</td>
                <td class="text-capitalize">{{$list->VOL}}</td>
                <td class="text-capitalize">{{$list->DATE_TIME}}</td>
                <td class="actions" data-th="">
                  <a href="{{ url('/admin/stock/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>   
                  <a href="#" class="stock_details" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-eye"></i></button></a>
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error text-center" colspan="8">No Expense Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $tankstock->onEachSide(1)->links('ajax_pagination') !!}

</div>
<div id="StockModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wizard-title">Stock Details</h5>
        <img class="rounded-circle" id="station_logo" name="station_logo" width="10%" height="10%" src="" / style="float:right">
      </div>
      <div class="modal-body">
        <!-- <div class="tab-content mt-2"> -->
          <div class="tab-pane fade show active" id="details" role="tabpanel">
            <h5>Invoice Number</h5>
            <p id="e_title"></p>
            <hr>
            <h5>Track Number</h5>
            <p id="e_desc"></p>
            <hr>
            <h5>Driver Name </h5>
            <p id="e_amt"></p>
            <hr>
            <h5>Total Number Liters Ordered</h5>
            <p id="e_ltr"></p>
            <hr>
            <h5>Image</h5>
            <img id="e_image" name="e_image" width="20%" height="20%" src="" />
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
  $('.stock_details').on('click', function(event) {
        var id =$(this).data("id");
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/get_stock_details',
                method: 'POST',
                data: {"id":id},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){
                        $("#e_title").text(data.data.invoice_no);
                        $("#e_desc").text(data.data.track_no);
                        $("#e_amt").text(data.data.driver_name);
                        $("#e_ltr").text(data.data.total_number_liters_ordered);
                        console.log(data.data.image) 
                        if (!data.data.stock_image) {
                           console.log("null");
                           $("#e_image").attr('src', '');
                           $("#e_image").hide();
                        }else{
                          console.log("not null")
                          $("#e_image").attr('src', APP_URL+'/images/stock/'+data.data.stock_image);
                          $("#e_image").show();
                        }
                        if (!data.station_logo) {
                           console.log("null");
                           $("#station_logo").attr('src', '');
                           $("#station_logo").hide();
                        }else{
                          console.log("not null")
                          $("#station_logo").attr('src', APP_URL+'/images/station_logo/'+data.station_logo);
                          $("#station_logo").show();
                        }
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

     

     

</script>
