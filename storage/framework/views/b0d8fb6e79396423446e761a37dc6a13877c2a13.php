<?php $__env->startSection('dashcontent'); ?>
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-sm-10">
                  <h4 class="cust-card-title">
                     <i class="mdi mdi-library-books cust-box-icon"></i>
                     Feedback list
                  </h4>
               </div>                           
            </div>
            <br>                   
            <div id="table_filter_view">
               <?php echo $__env->make('pages.admin.feedback.table_view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
         </div>
      </div>
   </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/feedback/index.blade.php ENDPATH**/ ?>