<?php $__env->startSection('dashcontent'); ?>
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-10">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-library-books cust-box-icon"></i>
                     Contact Us list
                  </h4>
               </div>                           
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">                     
                      <div class="col-md-5">
                        <input type="text" autoComplete="off" placeholder="Search Contact" name="name" id="search_contact" class="form-control">
                      </div>                      
                      <div class="col-md-4 d-flex">
                        <button class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div> 
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div id="table_filter_view">
               <?php echo $__env->make('pages.admin.contact.table_view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript">


// Booking key search
$('#search_contact').on('keyup', function(event) {
      var name = $( "#search_contact").val();
    
        if(name){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/contact/search_contact_data',
                method: 'POST',
                data: {"name":name},
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/contact/index.blade.php ENDPATH**/ ?>