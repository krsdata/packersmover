<?php if(!$terms->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($terms->firstItem()); ?>-<?php echo e($terms->lastItem()); ?> of <?php echo e($terms->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($terms->total()); ?>  Records</p>
<?php endif; ?>

<br/>
<div class="row my-3 m-0">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-file-outline"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">terms Count </p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1"><?php echo e($s_count); ?></h3>
                <small class="mb-0">Count</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>    
</div>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(!$terms->isEmpty()): ?>
            <?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">        
                <td class="text-capitalize"><?php echo e($list->id); ?></td>        
                <td class="text-capitalize"><?php echo e($list->name); ?></td>
                <td class="text-capitalize"><?php echo e($list->created_at); ?></td>
                <td class="actions" data-th="">
                  <a href="<?php echo e(url('/admin/terms/edit/' . en_de_crypt($list->id,'e') )); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                </td>
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No terms Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $terms->onEachSide(1)->links('ajax_pagination'); ?>

</div><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/terms/table_view.blade.php ENDPATH**/ ?>