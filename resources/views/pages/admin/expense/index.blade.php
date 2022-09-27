@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-file-document cust-box-icon"></i>
                     Expenses list
                  </h4>
               </div>
               @role('manager')
                <div class="col-md-1">
                  <a href="{{route('expense_create')}}" ><button class="btn badge-primary btn-xs"><i class="mdi mdi-plus-circle"></i></button></a>
                </div>
               @endrole
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-md-4 mb-3">
                         <select class="form-control" placeholder="Status"  name="status" id="status" >
                           <option value="" >All Status</option>
                           @if($user->type == 'accountant' or $user->type == 'manager' or $user->type == 'admin' or $user->type == 'owner')
                            <option @if(isset($status) && $status=="accountant reject") selected="selected" @endif value="accountant reject">Expense rejected by Accountant</option>
                           @endif
                           @if($user->type == 'accountant' or $user->type == 'manager' or $user->type == 'admin' or $user->type == 'owner' or $user->type == 'account-manager')
                           <option @if(isset($status) && $status=="accountant accept") selected="selected" @endif value="accountant accept">Expense accepted by Accountant</option>
                           <option @if(isset($status) && $status=="account_manager accept") selected="selected" @endif value="account_manager accept">Expense accepted by Account-Manager</option>
                           <option @if(isset($status) && $status=="account_manager reject") selected="selected" @endif value="account_manager reject">Expense rejected by Account-Manager</option>
                           @endif
                           @if($user->type == 'accountant' or $user->type == 'manager' or $user->type == 'owner' or $user->type == 'admin' or $user->type == 'owner')
                           <option @if(isset($status) && $status=="pending") selected="selected" @endif value="pending">Pending</option>
                           @endif
                         </select>
                      </div>
                      <div class="col-md-4 add-select-muletile12 mb-3">
                        <select class="form-control Station_Id" name="station[]" id="station" data-msg-required="stations is required"  multiple="multiple" required>
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
                      <div class="col-md-4 d-flex mb-3">
                        <input type="text" class="form-control common_date" placeholder="Date" name="dates"value="@if(!empty($dates)) {{$dates}} @endif" />
                        <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div> 
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div class="mt-4" id="table_filter_view">
               @include('pages.admin.expense.table_view')
            </div>
         </div>
      </div>
   </div>
</div>
@if(!$expenses->isEmpty())
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
