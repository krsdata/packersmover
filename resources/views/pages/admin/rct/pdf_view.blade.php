<div class="row">
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
                             {!! number_format($rct_count) !!} 
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
                           {!! number_format((float)$p_count,$decimal_point,'.',',') !!} 
                        <small class="mb-0">{{$currency_code}}</small>
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
                          {!! number_format($i_count) !!}   
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
        <th>No.</th>
        <!-- <th>Date</th>
        <th>Time</th> -->
        <th>TIN</th>
        <th>REGID</th>
        <th>EFDSERIAL</th>
        <th>CUSTID</th>
        <th>CUST <br> NAME</th>
        <th>MOBILE <br> NUM</th>
        <th>RCT <br> NUM</th>
        <th>DC</th>
        <th>GC</th>
        <th>ZNUM</th>
        <!-- <th>Station <br> ID</th> -->
        <th>RCTV <br> NUM</th>
      </tr>
    </thead>
    <tbody>
        
        @if(!$obj->isEmpty())
            @foreach($obj as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="btn badge-primary btn-sm" id="openrctdetail">{{$list->id}}</button></td>
                <!-- <td>{{$list->DATE}}</td>
                <td>{{$list->TIME}}</td> -->
                <td>{{$list->TIN}}</td>
                <td>{{$list->REGID}}</td>
                <td>{{$list->EFDSERIAL}}</td>
                <td>{{$list->CUSTID}}</td>
                <td>@php echo substr($list->CUSTNAME, 0, 6).'...'; @endphp</td>
                <td>@php echo substr($list->MOBILENUM, 0, 6).'...'; @endphp</td>
                <td>{{$list->RCTNUM}}</td>
                <td>{{$list->DC}}</td>
                <td>{{$list->GC}}</td>
                <td>{{$list->ZNUM}}</td>
               <!--  <td>{{$list->station_id}}</td> -->
                <td>{{$list->RCTVNUM}}</td>
           </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="12">No Records Found..</td></tr>
        @endif
</tbody>
</table>
</div>
<script>
    // jQuery(document).ready(function($) {
    //     $("#search_input option:first").attr('selected','selected');
    // }); 
    // $('#search_input').on('change', function() {
    //     $('#search_input option').removeAttr('selected');
    //     var value = $(this).val();
    //     $(this).find('option[value="' + value + '"]').attr("selected", "selected");
            
    // });
</script>    
