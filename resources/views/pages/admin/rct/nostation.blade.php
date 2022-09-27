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
          <div id="table_filter_view">
              <h4>No station found</h4>
          </div>
      </div>
    </div>
  </div>
</div>
                                                                     
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
        <button type="button" class="btn btn-danger" onclick="closeModal('rctModal')">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection