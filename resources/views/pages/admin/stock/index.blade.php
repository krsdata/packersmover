@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-elevation-rise cust-box-icon"></i>
                     Stock list
                  </h4>
               </div>
               <div class="col-md-1">
                         <a href="{{route('stock_create')}}" ><button class="btn badge-primary btn-xs"><i class="mdi mdi-plus-circle"></i></button></a> 
                </div>
               @role('manager')
                <div class="col-md-1">
                  <!-- <a href="{{route('expense_create')}}" ><button class="btn badge-primary btn-xs"><i class="mdi mdi-beer"></i></button></a> -->
                </div>
               @endrole
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-md-4 add-select-muletile12">
                        <select class="form-control Station_Id dy_Station" name="station[]" id="station" data-msg-required="stations is required"  multiple="multiple" required>
                          @php
                              $selected =" ";
                              if(isset($station_arrray) && !empty($station_arrray)){
                                foreach($station_arrray as $data){
                                  if(!empty($station)){
                                    if(in_array($data->id,$station)){

                                      $selected ="selected";
                                    }else{
                                          $selected =" ";
                                    }
                                  }
                                  echo '<option value="'.$data->id.'" '.$selected.'>'.$data->title.'</option>';
                                }  
                              }   
                          @endphp
                        </select> 
                      </div>
                      <div class="col-md-2">
                        <select class="form-control product_name dy_product" placeholder="Product" name="PROD_NAMES">
                           <option value="">All PRODUCT</option>
                         </select>
                      </div>
                      <div class="col-md-2">
                        <select class="form-control tank" placeholder="Tank" name="tank">
                        <option value="">All Tank</option>
                      </select>
                      </div> 
                      <div class="col-md-4 d-flex">
                        <input type="text" class="form-control common_date" placeholder="Date" name="dates"value="@if(!empty($dates)) {{$dates}} @endif" />
                        <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div> 
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div class="mt-4" id="table_filter_view">
               @include('pages.admin.stock.table_view')
            </div>
         </div>
      </div>
   </div>
</div>
@if(!$tankstock->isEmpty())
<div class="row">
  <div class="col-sm-12">
    <div class="clear-fix">
        <div style="float:right;width:auto;">
            <a href="{{ $txtUrl }}"  class="btn btn-primary ml-2 " data-type="txt" >Export TXT</a>
            <a href="{{ $xmlUrl }}"  class="btn btn-primary ml-2 " data-type="xls" >Export XLS</a>
            <a href="{{ $fullUrl }}"  class="btn btn-primary ml-2 " data-type="pdf" >Export PDF</a>
        </div>
    </div>
  </div>
</div>
@endif
<!-- Modal -->
<div class="modal fade reportmodel" id="tankstockModal" role="dialog" url="{{ route('tankstock_detail',[0]) }}">
  <div class="modal-dialog modal-lg" style="margin: 10px auto">
    <div class="modal-content">
      <div class="modal-body" id="tankstockdetailbody">
        
      </div>
      <div class="modal-footer">
        <div id="tankstocktxt" style="display:none"></div>
        <div id="tankstockxls" style="display:none"></div>
        {{-- <button type="button" class="btn btn-primary ml-2" onclick="printDiv('tankstockdetailbody')" >Print</button> --}}
        <button type="button" class="btn btn-primary ml-2 downloadTXT" textid="tankstocktxt" filename="" >Export TXT</button>
        <button type="button" class="btn btn-primary ml-2 downloadXLS" textid="tankstockxls" filename="" >Export XLS</button>
        <button type="button" class="btn btn-primary ml-2 downloadPDF" textid="tankstock" filename="" >Export PDF</button>
        <!-- <button type="button" class="btn btn-primary ml-2 sendMail" url="{{ route('tanktrn_mail',[0]) }}" textid="tankstock" filename="" >Send Mail</button> -->
        <button type="button" class="btn btn-danger" onclick="closeModal('tankstockModal')">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  // $('#status').on('change', function (evt) {
  //     $( ".common_search_filter" ).click();
  // });
  $('ul').on('click', '.click_me', function(){
    $(this).toggleClass('active').siblings().removeClass('active');   // <--- The trick!
  })

  $("#station").select2({
    placeholder: "Select a station",
    allowClear: true,
    width: '100%',
    maximumSelectionLength: 1
});
</script>
@endsection
