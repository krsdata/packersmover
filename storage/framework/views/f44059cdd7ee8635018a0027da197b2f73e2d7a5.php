<?php $__env->startSection('dashcontent'); ?>
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-11">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-format-list-bulleted cust-box-icon"></i>
                     Banner list
                  </h4>
               </div>
               <div class="col-md-1">
                         <a href="<?php echo e(route('banner_create')); ?>" ><button class="btn badge-primary btn-xs"><i class="mdi mdi-plus-circle"></i></button></a> 
                </div>              
            </div>
            <br>
            <div class="row">  
              <div class="col-sm-12">
                  <form name="add_expense" id="add_expense" class="search_filter_form" method="GET" accept-charset="UTF-8">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">                      
                    <div class="col-md-5">
                        <input type="text" autoComplete="off" placeholder="Search Banner" name="name" id="search_banner" class="form-control">
                      </div> 
                      <div class="col-md-4 d-flex">
                        <button class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                      </div>
                    </div> 
                    
                  </form>   
              </div>
            </div>        
                        
            <div id="table_filter_view">
               <?php echo $__env->make('pages.admin.banner.table_view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
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


$('#search_banner').on('keyup', function(event) {
      var name = $( "#search_banner").val();
        if(name){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/banner/search_banner_data',
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/banner/index.blade.php ENDPATH**/ ?>