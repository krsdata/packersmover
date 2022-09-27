<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi mdi-transfer"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Expense </p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$e_count}}</h3>
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
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Total Amount</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$e_amt}}</h3>
                <small class="mb-0">Amt</small>
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
                    <th width="35%">Title</th>
                    <th width="35%">Description</th>
                    <th width="35%">Amount</th>
                    <th width="35%">ACCOUNTANT STATUS</th>
                    <th width="35%">ACCOUNT MANAGER STATUS</th>
                </tr>
        </thead>
         <tbody>
            @if(!$expenses->isEmpty())
            @foreach($expenses as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td style="text-align: center; ">{{$list->title}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->description}}</td>
                <td style="text-align: center; " class="text-capitalize">{{$list->amount}}</td>
                <td style="text-align: center; " class="text-capitalize">
                    <?php if(!empty($list->accountant_status) && $list->accountant_status!='pending' ){
                        echo substr($list->accountant_status, strpos($list->accountant_status, " ") + 1);
                    }else{
                            echo $list->accountant_status; 
                    } 
                    ?>
                </td>
                <td style="text-align: center; " class="text-capitalize">
                    <?php if(!empty($list->account_manager_status) && $list->account_manager_status!='pending' ) {
                        echo substr($list->account_manager_status, strpos($list->account_manager_status, " ") + 1);
                    }else{
                           echo $list->account_manager_status; 
                    }
                    ?>
                </td>   
            </tr>
            @endforeach
            @else
                <tr><td class="server-error" colspan="12">No Records Found..</td></tr>
            @endif
        </tbody>
    </table>
</div>
<br>
<div class="header" style="padding-bottom:30px!important; border: none !important;">
    <table class="table" width="100%" id="myTable" style="padding-bottom: 30px!important;border: none !important;">
    <tbody>
        <tr><td><span style="font-size: 20px">
                           @if($status!='') Status: {{@$status}} @endif <br> 
                </span>     
            </td>
        </tr>
    </tbody>
    </table>
</div>
    