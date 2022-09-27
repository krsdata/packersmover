<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-file-multiple"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Expense TOTAL </p>
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
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Amount TOTAL</p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$e_amt}}</h3>
                <small class="mb-0"></small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@if(!$expenses->isEmpty())
<p class="float-right"> Displaying {{$expenses->firstItem()}}-{{ $expenses->lastItem() }} of {{$expenses->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$expenses->total()}}  Records</p>
@endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Accountant Status</th>
        <th>Accountant Manager Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$expenses->isEmpty())
            @foreach($expenses as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td>{{$list->title}}</td>
                <td class="text-capitalize">{{$list->description}}</td>
                <td class="text-capitalize">{{$list->amount}}</td>
                <td class="text-capitalize">
                    <?php if(!empty($list->accountant_status) && $list->accountant_status!='pending' ){
                                 echo substr($list->accountant_status, strpos($list->accountant_status, " ") + 1);
                          }else{
                                 echo $list->accountant_status;  
                          } 
                    ?>
                </td>
                <td class="text-capitalize">
                    <?php if(!empty($list->account_manager_status) && $list->account_manager_status!='pending' ){
                                 echo substr($list->account_manager_status, strpos($list->account_manager_status, " ") + 1);
                          }else{
                                 echo $list->account_manager_status;  
                          } 
                    ?>
                </td>
                <td class="actions" data-th="">
                  @if($user->type == 'admin' or $user->type == 'manager' or $user->type == 'owner')
                  @if($list->accountant_status=='pending')
                    <a href="{{ url('/admin/expense/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                    <a href="#" class="expense_delete" data-id={{en_de_crypt($list->id,'e')}} data-model="Expenses"><button class="btn badge-primary btn-xs"><i class="mdi mdi-delete-circle"></i></button></a>
                  @endif  
                  <a href="#" class="expense_details" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-eye"></i></button></a>
                @endif
                @role('accountant')  
                <a href="#" class="pending_request" style="cursor: pointer"  data-status="accept" data-role="accountant" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-check-circle-outline"></i></button></a>
                <a href="#" class="pending_request" style="cursor: pointer" data-status="reject" data-role="accountant" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-close-circle-outline"></i></button></a>
                @endrole
                @role('account-manager') 
                @if($list->accountant_status=='accountant accept' or $list->accountant_status=='account-manager accept' or $list->accountant_status=='account-manager reject')    
                <a href="#" class="pending_request" style="cursor: pointer" data-status="accept"  data-role="account_manager" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-check-circle-outline"></i></button></a>
                <a href="#" class="pending_request" style="cursor: pointer" data-status="reject"  data-role="account_manager" data-id={{en_de_crypt($list->id,'e')}}><button class="btn badge-primary btn-xs"><i class="mdi mdi-close-circle-outline"></i></button></a>
                @endif
                @endrole
            </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Expense Found..</td></tr>
        @endif
</tbody>
</table>
  {!! $expenses->onEachSide(1)->links('ajax_pagination') !!}
  <!--  Expense Modal -->
<!-- <div id="ExpenseModal" class="modal fade bs-example-modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="tabbable column-wrapper"> 
          <ul class="nav nav-tabs sideTab column left">
            <li class="click_me active"><a class="tab1" href="#tab1" data-toggle="tab">
              Expense Details<i class="mdi mdi-file-document cust-box-icon"></i></a></li> 
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <li  class="click_me"><a href="#tab2" data-toggle="tab">Log<i class="mdi mdi-file-document cust-box-icon"></i></a></li>
          </ul>
          <div class="tab-content column rigth" id="tabs" style="padding:30px;">
            <div class="tab-pane active" id="tab1">
              <h5>Title</h5>
              <p id="e_title"></p>
              <hr>
              <h5>Description</h5>
              <p id="e_desc"></p>
              <hr>
              <h5>Amount</h5>
              <p id="e_amt"></p>
              <hr>
              <h5>Image</h5>
              <img id="e_image" name="e_image" src="" />                      
            </div>
            <div class="tab-pane" id="tab2">
              <table class="table table-striped" id="logtable">
                  <thead>
                    <tr>
                      <th>Id</th> 
                      <th>Status</th>
                      <th>Rejected Reason</th>
                      <th>Note</th>
                    </tr>
                  </thead>
                  <tbody id="logtbody">
                   
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
<div id="ExpenseModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wizard-title">Expense Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#details" role="tab"><i class="mdi mdi-file-document"></i>  Details</a>
          <li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#log" role="tab"><i class="mdi mdi-table-large"></i> Log</a>
          <li>
        </ul>
        <div class="tab-content mt-2">
          <div class="tab-pane fade show active" id="details" role="tabpanel">
            <h5>Title</h5>
            <p id="e_title"></p>
            <hr>
            <h5>Description</h5>
            <p id="e_desc"></p>
            <hr>
            <h5>Amount</h5>
            <p id="e_amt"></p>
            <hr>
            <h5>Image</h5>
            <img id="e_image" name="e_image" width="20%" height="20%" src="" />
          </div>
          <div class="tab-pane fade" id="log" role="tabpanel">
            <table class="table table-striped" id="logtable">
              <thead>
                <tr>
                  <th>Id</th> 
                  <th>Status</th>
                  <th>Rejected Reason</th>
                  <th>Note</th>
                  </tr>
              </thead>
              <tbody id="logtbody">
                   
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
  <!--  Reject Modal -->
<div class="modal fade" id="ExpenseModalReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="expense-reject"  method="post">
          <div class="modal-body">
            <input type ="hidden" id="r_id" name="r_id" value="">
            <input type ="hidden" id="r_status" name="r_status" value="">
            <input type ="hidden" id="r_role" name="r_role" value="">
            <h5>Reason of Rejection </h5>
            <textarea rows="4" class="form-control" cols="50" name="reject_reason" id="reject_reason"></textarea>
            <label id="description-error-server" class="server-error" for="title" style="display: none">Reason is required</label>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
     var logtable =  $('#logtable').DataTable( {});
    


    $('.pending_request').on('click', function(event) {
        var id=$(this).data("id");
        var role=$(this).data("role");
        var status=$(this).data("status");
        if(status=="reject"){
           $("#r_id").val(id); 
           $("#r_status").val(status); 
           $("#r_role").val(role); 
           $('#reject_reason').val(' ');
           $('#ExpenseModalReject').modal('show'); 
        }else{
            ajax_request(id,role,status,reject_data='');
        }
        
    });

       
    $('.expense_details').on('click', function(event) {
        var id =$(this).data("id");
        if(id){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/get_expense_details',
                method: 'POST',
                data: {"id":id},
                success: function (data) {
                   if(data.success=="True"){
                    logtable.clear().draw();
                     if(data.data.expense){
                        $("#e_title").text(data.data.expense.title);
                        $("#e_desc").text(data.data.expense.description);
                        $("#e_amt").text(data.data.expense.amount);
                        console.log(data.data.expense.image) 
                        if (!data.data.expense.image) {
                           console.log("null");
                           $("#e_image").attr('src', '');
                           $("#e_image").hide();
                        }else{
                          console.log("not null")
                          $("#e_image").attr('src', APP_URL+'/images/expense_img/'+data.data.expense.image);
                          $("#e_image").show();
                        }
                        $('#ExpenseModal').modal('show');
                     }

                     if(data.data.log.length > 0){
                      $.each(data.data.log, function( index, value ) {
                          if(value.reject_reason=='NULL'){
                            logtable.row.add([value.id,value.status,' ',value.note]).draw();
                          }else{
                            logtable.row.add([value.id,value.status,value.reject_reason,value.note]).draw();
                          }
                      });
                      }
                   }else{
                         logtable.clear().draw();
                        swal(
                            'Error!',
                            data.msg,
                            'error'
                        )
                      //location.reload();
                   }  
                }
            });
        }
    });

    
    $('.expense_delete').on('click', function(event) {
        var id=$(this).data("id");
        var model=$(this).data("model");
        swal({
            title: 'Are you sure?',
            text: "It will permanently deleted !",
            type: 'warning',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes!",
            showCancelButton: true,
        })
        .then((willDelete) => {
           if (willDelete.value == true) {
              if(id){
                   $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': _token
                      }
                  });
                  $.ajax({
                      url: APP_URL + '/admin/common/delete',
                      method: 'POST',
                      data: {"id":id,"model":model},
                      success: function (data) {
                         if(data.msg_type=="success"){
                             swal(data.msg);
                             swal(
                              'Success',
                              data.msg,
                              'success'
                            )
                             location.reload();
                         }else{
                              swal(
                                  'Error!',
                                  data.msg,
                                  'error'
                              )
                            //location.reload();
                         }  
                      }
                  });
              }
          }else{
              swal("Cancelled", "Your Expense is safe :)", "error");
           }
        });
        
    });

  }); 

  // expense reject 
    $(function(){
       $('#expense-reject').on('submit', function(e){
            e.preventDefault();
            var id=$("#r_id").val();
            var role=$("#r_role").val();
            var status=$("#r_status").val();
            var reject_data=$("#reject_reason").val();
            if(!reject_data){
                $("#description-error-server").show();
            }else{
                   $("#description-error-server").hide();
                   ajax_request(id,role,status,reject_data);
            }
            
        });        
    });

    function  ajax_request(id,role,status,reject_reason){
        if(id && role && status){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/update_pending_status',
                method: 'POST',
                data: {"id":id,"role":role,"status":status,"reject_reason":reject_reason},
                success: function (data) {
                   if(data.success=="True"){
                       swal(data.msg);
                       swal(
                        'Success',
                        data.msg,
                        'success'
                      )
                       location.reload();
                   }else{
                        swal(
                            'Error!',
                            data.msg,
                            'error'
                        )
                      //location.reload();
                   }  
                }
            });
        }
    }    

</script>
