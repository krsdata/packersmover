<!-- <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
            <div class="card border-0 border-radius-2 bg-primary">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
                    <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                        <i class="mdi mdi mdi-transfer"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Total RCT</p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">
                              
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
                        <i class="mdi mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Total </p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">
                            
                        <small class="mb-0"></small>
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
                        <i class="mdi mdi mdi-transfer"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Total Item  </p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">
                             
                        <small class="mb-0">Count</small>
                        </div>
                    </div>
                    </div>
                </div>
            </div> 
        </div>
</div>  -->   

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <!-- <th>No.</th>
        <th>FDC Date</th>
        <th>FDC Time</th> -->
        <th width="15%">RDGNo</th>
        <th width="15%">FP No</th>
        <th width="15%" >Nozzle No</th>
        <th>Product Name</th>
        <th>Pump Start Volume Total</th>
        <th>Pump End Volume Total</th>
        <th>Volume By Total</th>
      </tr>
  </thead>
  <tbody>
        @if(!$obj->isEmpty())
            @foreach($obj as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <!-- <td><button class="btn badge-primary btn-sm openTrnDetail">{{$list->FDC_SAVE_NUM}}</button></td>
                <td>{{$list->TANK_TRN_DATE}}</td>
                <td>{{$list->TANK_TRN_TIME}}</td> -->
                <td>{{$list->RDG_ID}}</td>
                <td>{{$list->FP}}</td>
                <td>{{$list->NOZ}}</td>
                <td>{{$list->FDC_PROD_NAME}}</td>
                <td><?php echo number_format((float)$list->Pump_Start_Volume_Total,2,'.',',')?></td>
                <td><?php echo number_format((float)$list->Pump_End_Volume_Total,2,'.',',')?></td>
                <td>{{$list->VOL}}</td>
           </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="12">No Records Found..</td></tr>
        @endif
  </tbody>
</table>
</div>
<script>
    
</script>    
