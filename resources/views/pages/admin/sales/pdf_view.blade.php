<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-deskphone"></i>
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


<div class="table-responsive">
    <table class="table table-striped" style="border-spacing: 0; border-collapse: collapse;">
        <thead>
                <tr>
                    <th width="35%">Invoice Number</th>
                    <th width="35%">Track Number</th>
                    <th width="35%">Pump Start Volume Total </th>
                    <th width="35%" >Pump End Volume Total</th>
                    <th width="35%" >VOL</th>
                    <th width="35%">Date Time</th>
                </tr>
        </thead>
        <tbody>
            @if(!$datas->isEmpty())
            @foreach($datas as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td style="text-align: center; ">{{$list->invoice_no}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->track_no}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->Pump_Start_Volume_Total}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->Pump_End_Volume_Total}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->VOL}}</td> 
                <td style="text-align: center; " class="text-capitalize">{{$list->DATE_TIME}}</td>    
            </tr>
            @endforeach
            @else
                <tr><td class="server-error" colspan="12">No Records Found..</td></tr>
            @endif
        </tbody>
    </table>
</div>
    