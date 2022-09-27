@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-10">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-library-books cust-box-icon"></i>
                     Withdraw list
                  </h4>
               </div>                           
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">                     
                      <div class="col-md-5">
                        <input type="text" autoComplete="off" placeholder="Search Booking" name="first_name" id="search_booking" class="form-control">
                      </div>                      
                      <div class="col-md-4 d-flex">
                        <button class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div> 
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div id="table_filter_view">
               @include('pages.admin.withdraw.table_view')
            </div>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript">


// Booking key search
$('#search_booking').on('keyup', function(event) {
      var _name = $( "#search_booking").val();
        if(product_name){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/order/search_product_get',
                method: 'POST',
                data: {"product_name":product_name},
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
