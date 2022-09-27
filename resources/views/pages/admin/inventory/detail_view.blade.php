<div class="row">
  <div class="col-lg-12">
      <div class="card px-2"  style="background:#fefefe">
          <div class="card-body">
             <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                <div class="table-responsive pt-3">
                  <h6 class="text-left">Tank Stock Details</h6>
                  <table class=" table-bordered"  id="trncardtable">
                    <tbody>
                            <tr><td>ID</td> <td></td></tr>
                            <tr><td>Station Name</td> <td>{{$station_name}}</td></tr>
                            <tr><td>Product Name</td> <td>{{$tankstock_data->product_name}}</td></tr>
                            <tr><td>Tank Name</td> <td>{{$tank_name}}</td></tr>
                            <tr><td>Invoice Number</td> <td>{{$tankstock_data->invoice_no}}</td></tr>
                            <tr><td>Track Number</td> <td>{{$tankstock_data->track_no}}</td></tr>
                            <tr><td>Driver Name</td> <td>{{$tankstock_data->driver_name}}</td></tr>
                            <tr><td>Pump Start Volume Total</td> <td>{{$tankstock_data->Pump_Start_Volume_Total}}</td></tr>
                            <tr><td>Pump End Volume Total</td> <td>{{$tankstock_data->Pump_End_Volume_Total}}</td></tr>
                            <tr><td>VOL</td> <td>{{$tankstock_data->VOL}}</td></tr>
                            <tr><td>Date Time</td> <td>{{$tankstock_data->DATE_TIME}}</td></tr>

                    </tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>