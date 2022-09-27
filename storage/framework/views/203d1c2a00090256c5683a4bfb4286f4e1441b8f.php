<?php $__env->startSection('dashcontent'); ?>

 <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                  <div class="col-sm-6">
                      <h4 class="cust-card-title">
                        <i class="mdi  mdi-account cust-box-icon"></i>
                        User list
                      </h4>
                  </div>
                  <!-- <div class="col-sm-2">
                      <a href="<?php echo e(route('customer_create')); ?>" ><button class="btn btn-primary border-radius-05">Add User</button></a>
                  </div> -->
                  <div class="col-sm-4">
                  <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                                <?php echo e(csrf_field()); ?>

                              
                    <div class="form-group d-flex custom-search-view-place">
                        <input value="<?php echo e($search_input); ?>" type="text" name="search_input" id="search_input" class="form-control" placeholder="Search Here...." >
                        <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify"></i></button>
                    </div>
                   </form>
                  </div>
                  </div>
                    <div id="table_filter_view">
                        <?php echo $__env->make('pages.admin.customer.table_view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
              </div>
            </div>
          </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/customer/index.blade.php ENDPATH**/ ?>