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
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)$t_discount,2,'.',',') !!}</h3>
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
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)$amo_new,@$decimal_point,'.',',') !!}</h3>
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
                <i class="mdi mdi mdi-oil-temperature"></i>
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
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!!number_format((float)$amos,@$decimal_point,'.',',') !!}</h3>
                <small class="mb-0">{{@$currency_code}}</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
 
</div>
<script>
   
</script> 