<div class="row">
  <div class="col-lg-12">
      <div class="card px-2"  style="background:#fefefe">
          <div class="card-body">
             <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                <div class="table-responsive pt-3">
                  <h6 class="text-left">RCT Details</h6>
                  <table class=" table-bordered"  id="trncardtable">
                    <tbody>
                            <tr><td>DATE</td> <td>{{$rct_data->DATE}}</td></tr>
                            <tr><td>TIME</td> <td>{{$rct_data->TIME}}</td></tr>
                            <tr><td>TIN</td> <td>{{$rct_data->TIN}}</td></tr>
                            <tr><td>REGID</td> <td>{{$rct_data->REGID}}</td></tr>
                            <tr><td>EFDSERIAL</td> <td>{{$rct_data->EFDSERIAL}}</td></tr>
                            <tr><td>CUSTIDTYPE</td> <td>{{$rct_data->CUSTIDTYPE}}</td></tr>
                            <tr><td>CUSTID</td> <td>{{$rct_data->CUSTID}}</td></tr>
                            <tr><td>CUSTNAME</td> <td>{{$rct_data->CUSTNAME}}</td></tr>
                            <tr><td>MOBILENUM</td> <td>{{$rct_data->MOBILENUM}}</td></tr>
                            <tr><td>RCTNUM</td> <td>{{$rct_data->RCTNUM}}</td></tr>
                            <tr><td>DC</td> <td>{{$rct_data->DC}}</td></tr>
                            <tr><td>GC</td> <td>{{$rct_data->GC}}</td></tr>
                            <tr><td>ZNUM</td> <td>{{$rct_data->ZNUM}}</td></tr>
                            <tr><td>RCTVNUM</td> <td>{{$rct_data->RCTVNUM}}</td></tr>
                            <tr><td>station_id</td> <td>{{$rct_data->station_id}}</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                <div class="table-responsive pt-3">
                  <h6 class="text-left">Totals Detail</h6>
                  <table class=" table-bordered"  id="trncardtable">
                    <thead>
                      <tr>
                        <th scope="col">TOTALTAXEXCL</th>
                        <th scope="col">TOTALTAXINCL</th>
                        <th scope="col">DISCOUNT</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(isset($totals_datas))
                        @foreach($totals_datas as $totals_data)
                        <tr><td>{{$totals_data['TOTALTAXEXCL']}}</td>
                            <td>{{$totals_data['TOTALTAXINCL']}}</td>
                            <td>{{$totals_data['DISCOUNT']}}</td>
                            
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                  <div class="table-responsive pt-3">
                    <h6 class="text-left">Items Detail</h6>
                    <table class=" table-bordered"  id="trncardtable">
                    <thead>
                      <tr>
                        <th scope="col">STATION ID</th>
                        <th scope="col">DESC</th>
                        <th scope="col">QTY</th>
                        <th scope="col">TAXCODE</th>
                        <th scope="col">AMT</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(isset($item_datas))
                        @foreach($item_datas as $item_data)
                        <tr>
                          <td>{{$item_data['rct_id']}}</td>
                          <td>{{$item_data['DESC']}}</td>
                          <td>{{$item_data['QTY']}}</td>
                          <td>{{$item_data['TAXCODE']}}</td>
                          <td>{{$item_data['AMT']}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>
                  </div>
              </div>
              <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                <div class="table-responsive pt-3">
                  <h6 class="text-left">Payments Detail</h6>
                  <table class=" table-bordered"  id="trndiscounttable">
                    <thead>
                      <tr>
                        <th scope="col">PMTTYPE</th>
                        <th scope="col">PMTAMOUNT</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(isset($payments_datas))
                        @foreach($payments_datas as $payments_data)
                        <tr><td>{{$payments_data['PMTTYPE']}}</td>
                            <td>{{$payments_data['PMTAMOUNT']}}</td>
                        </tr> 
                        @endforeach
                        @endif
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="container-fluid mt-1 d-flex justify-content-center w-100">
                <div class="table-responsive pt-3">
                  <h6 class="text-left">Vattotals Detail</h6>
                  <table class=" table-bordered"  id="trndiscounttable">
                    <thead>
                      <tr>
                        <th scope="col">VATRATE</th>
                        <th scope="col">NETTAMOUNT</th>
                        <th scope="col">TAXAMOUNT</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(isset($vattotal_datas))
                        @foreach($vattotal_datas as $vattotal_data)
                    
                        <tr> <td>{{$vattotal_data['VATRATE']}}</td>
                             <td>{{$vattotal_data['NETTAMOUNT']}}</td>
                             <td>{{$vattotal_data['TAXAMOUNT']}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>