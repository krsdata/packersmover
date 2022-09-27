@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="col-sm-3">
                  <h4 class="cust-card-title">
                    <i class="mdi  mdi-sale cust-box-icon"></i>
                    RCT list
                  </h4>
              </div>
              <div class="col-sm-9">
              </div>
          </div>  
          <div class="row">  
              <div class="col-sm-12">
              <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                      <select class=""  name="station[]" id="station"   multiple="multiple">
                        @php
                            $selected =" ";
                            if(isset($stations_data) && !empty($stations_data)){
                              foreach($stations_data as $station){
                                if(!empty($stations)){
                                  if(in_array($station->id,$stations)){

                                    $selected ="selected";
                                  }else{
                                         $selected =" ";
                                  }
                                }
                                echo '<option value="'.$station->id.'" '.$selected.'>'.$station->title.'</option>';
                              }  
                            }   
                        @endphp
                      </select>
                    </div>
                    <div class="col-md-4">
                      <select class="form-control" placeholder="Station"  name="item" id="item" >
                        <option value="0" >All Customers</option>
                        @if(!empty($item)) 
                          @foreach($item as $items)
                           @if(!empty($items->CUSTNAME)) 
                           <option  @if (isset($s_name) && $s_name == $items->CUSTNAME ) selected="selected" @endif value="{{@$items->CUSTNAME}}" >{{@$items->CUSTNAME}}</option>
                           @endif
                          @endforeach
                        @endif 
                      </select>
                    </div>
                    <div class="col-md-4 d-flex">
                        <input type="text" class="form-control common_date" placeholder="Date" name="dates"value="{{$dates}}" />
                        <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                    </div>
                </div>
                <br>
              </form>
              </div>
          </div>
          <div id="table_filter_view">
              @include('pages.admin.rct.table_view')
          </div>
      </div>
    </div>
  </div>
</div>
@if(!$obj->isEmpty())
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
<div class="modal fade reportmodel" id="rctModal" role="dialog" url="{{ route('rct_detail',[0]) }}">
  <div class="modal-dialog modal-lg" style="margin: 10px auto">
    <div class="modal-content">
      <div class="modal-body" id="rctdetailbody">
        
      </div>
      <div class="modal-footer">
        <div id="rcttxt" style="display:none"></div>
        <div id="rctxls" style="display:none"></div>
        {{-- <button type="button" class="btn btn-primary ml-2" onclick="printDiv('rctdetailbody')" >Print</button> --}}
        <button type="button" class="btn btn-primary ml-2 downloadTXT" textid="rcttxt" filename="" >Export TXT</button>
        <button type="button" class="btn btn-primary ml-2 downloadXLS" textid="rctxls" filename="" >Export XLS</button>
        <button type="button" class="btn btn-primary ml-2 downloadPDF" textid="rct" filename="" >Export PDF</button>
        <button type="button" class="btn btn-primary ml-2 sendMail" url="{{ route('rct_mail',[0]) }}" textid="rct" filename="" >Send Mail</button>
        <button type="button" class="btn btn-danger" onclick="closeModal('rctModal')">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
   $("#station").select2({
          placeholder: "Select a station",
          allowClear: true,
          width: '100%' 
    });

    // $('#station').on('change', function (evt) {
    //   $( ".common_search_filter" ).click();
    // });

    $(document).on("click","#openrctdetail",function(){
    console.log($this = $(this));
    $this = $(this);
    eid = $this.closest("tr").attr("id");
    console.log(eid);
    callback = "openRctDetailCallback";
    data = { "eid" : eid };
    url = $("#rctModal").attr("url");
    console.log(url);
    url = url.replace("/0", "/"+eid);
    ajaxCallGET(url,data,callback,$this);
});

function openRctDetailCallback(data,$this){
    $("#rctdetailbody").html(data.html);
    $("#rcttxt").html(data.txt);
    $("#rctxls").html(data.xls);
    $(".downloadTXT").attr("filename",data.file_name+".txt");
    $(".downloadXLS").attr("filename",data.file_name+".xls");
    $(".downloadPDF").attr("filename",data.file_name+".pdf");
    $(".downloadPDF").attr("pdffileurl",data.pdffileurl);
    $(".sendMail").attr("filename",data.file_name+".pdf");
    $("#rctModal").modal("show");
}

</script>
@endsection
@section('scripts')

@stop
