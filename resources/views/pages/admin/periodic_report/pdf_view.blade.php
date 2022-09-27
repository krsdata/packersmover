<div class="header" style="padding-bottom:30px!important; border: none !important;">
    <table class="table" width="100%" id="myTable" style="padding-bottom: 30px!important;border: none !important;">
        <tbody>
                <tr>
                    @if(!empty($station_details))
                    @foreach($station_details as $key => $list)
                    <td>
                        <span style="font-size: 20px">
                           {{@$list['title']}}<br> 
                           {{@$list['info']}}<br>
                           {{@$list['tel']}}<br>
                           TIN: {{@$list['tin']}} &nbsp; VRN: {{@$list['vrn']}}<br> 
                           Service Station: {{@$list['service_station']}}<br>  
                            Serial Number: {{@$list['serial_number']}}<br> 
                      </span>     
                    </td>
                    <td>
                        @if($key==0)
                        @if(!empty($logo)) 
                        <img src="{{ asset('images/logo') }}/{{$logo }}"  style="float:right;max-height: 80px; max-width:250px;height:auto;width:auto;"/>
                        @else 
                        <img src="{{ asset('images/logo/logo.png') }}"  style="float:right;max-height: 80px; max-width:250px;height:auto;width:auto;"/>
                        @endif  
                        @endif
                       @if(!empty(@$list['logo']))
                       <img src="{{ asset('images/station_logo') }}/{{@$list['logo']}}"  style="float:right;max-height: 80px; max-width:250px;height:auto;width:auto;">
                       @endif
                    </td>

                    @endforeach
                    @endif
                </tr>
        </tbody>
    </table>
</div>
    
<hr/>
 
<div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:center;"><span>Periodic Report FOR THE PERIOD FROM : {{$dates}}</span></div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary icon-rounded-xs">
                <i class="mdi mdi mdi-cash-multiple"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">AMOUNT DISCOUNT</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)@$t_discount,2,'.',',') !!}</h3>
                <small class="mb-0">{{@$currency_code}}</small>
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
            <div class="icon-rounded-inverse-primary icon-rounded-xs">
                <i class="mdi mdi mdi-cash-multiple"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">AMOUNT NEW</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)@$amo_new,@$decimal_point,'.',',') !!}</h3>
                <small class="mb-0">{{@$currency_code}}</small>
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
            <div class="icon-rounded-inverse-primary icon-rounded-xs">
                <i class="mdi mdi mdi-cash-multiple"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">TOTAL LITER</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!! number_format((float)@$vol,@$decimal_point,'.',',') !!}</h3>
                <small class="mb-0">Ltr</small>
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
            <div class="icon-rounded-inverse-primary icon-rounded-xs">
                <i class="mdi mdi mdi-cash-multiple"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">TOTAL AMOUNT</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)@$amos,@$decimal_point,'.',',') !!}</h3>
                <small class="mb-0">{{@$currency_code}}</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
<br>
<div class="header" style="padding-bottom:30px!important; border: none !important;">
    <table class="table" width="100%" id="myTable" style="padding-bottom: 30px!important;border: none !important;">
    <tbody>
        <tr><td><span style="font-size: 20px">
                       @if($tank!='') Tank Name: {{@$tank}} @endif<br>
                </span>     
            </td>
        </tr>
    </tbody>
    </table>
</div>
