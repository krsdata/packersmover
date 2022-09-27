@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-deskphone cust-box-icon"></i>
                     POS Sales Order
                  </h4>
               </div>
            </div>

            <div class="row">
               <div class="col-sm-12">
               <div class="col-md-6">
                   <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                     {{ csrf_field() }}
                      <div class="form-group">
                      <select class="form-control dy_product" placeholder="All Reports" name="type" id="" >
                        <option value="0">Reports</option>
                         <option value="daily">Daily Report</option>
                         <option value="weekly">Weekly Report</option>
                         <option value="monthly">Monthly Report</option>
                      </select>
                      </div>    
                      <button class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>                 
                   </form>
                    </div>
                    <!-- <div class="col-md-6">
                        <input type="text" class="form-control common_date" placeholder="Date" name="dates"value="{{$dates}}" />
                      <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                    </div> -->
                 </div>
            </div>

            <br>       
            <div id="table_filter_view">
               @include('pages.admin.sales.table_view')
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   $('#reports').on('change', function(event) {
      var type = $( "#reports").val();
        if(reports){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/sales/all_reports',
                method: 'POST',
                data: {"type":type},
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
