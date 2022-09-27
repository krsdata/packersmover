@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-file-outline cust-box-icon"></i>
                     Privacy Policy list
                  </h4>
               </div>
               <div class="col-md-1">
                         <a href="{{route('privacy_create')}}" ><button class="btn badge-primary btn-xs"><i class="mdi mdi-plus-circle"></i></button></a> 
                </div>              
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">                      
                    <div class="col-md-5">
                        <input type="text" autoComplete="off" placeholder="Search Privacy" name="name" id="search_privacy" class="form-control">
                      </div> 
                      <div class="col-md-4 d-flex">
                        <button class="btn btn-primary" ><i class="mdi mdi-magnify "></i></button>
                      </div>
                    
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div id="table_filter_view">
               @include('pages.admin.privacy.table_view')
            </div>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript">

$('#search_ticket').on('keyup', function(event) {
      var ticket_name = $( "#search_ticket").val();
        if(ticket_name){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/ticket/search_ticket_data',
                method: 'POST',
                data: {"ticket_name":ticket_name},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){  
                        $('tbody').html(data.data);
                     }
                    }else{
                      $('tbody').html(data.data);
                        // swal(
                        //     'Error!',
                        //     data.msg,
                        //     'error'
                        // )
                      //location.reload();
                    }  
                }
            });
        }
        else{
          location.reload(true);
        }
  });

</script>
@endsection
