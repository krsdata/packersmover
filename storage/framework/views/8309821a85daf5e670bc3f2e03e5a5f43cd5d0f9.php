<?php if(!$bookingdraw->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($bookingdraw->firstItem()); ?>-<?php echo e($bookingdraw->lastItem()); ?> of <?php echo e($bookingdraw->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($bookingdraw->total()); ?>  Records</p>
<?php endif; ?>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Booking Id</th>
                <th>Booking Type</th>
                <th>First Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Booking Status</th>
                <th>Action</th>
                </tr>
    </thead>
    <tbody>
        <?php if(!$bookingdraw->isEmpty()): ?>
            <?php $__currentLoopData = $bookingdraw; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
            <td ><?php echo e($list->id); ?></td>
                <td ><?php echo e($list->type); ?></td>
                <td ><?php echo e($list->first_name); ?></td>
                <td ><?php echo e($list->country); ?></td>
                <td ><?php echo e($list->city); ?></td>
                <td ><?php echo e($list->mobile); ?></td>               
                <td ><?php echo e($list->order_status); ?></td>
                 <td><a href="<?php echo e(url('/bookingview/' .$list->id)); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-display"></i></button></a>
                </td> 
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Booking Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $bookingdraw->onEachSide(1)->links('ajax_pagination'); ?>

</div><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/booking/table_view.blade.php ENDPATH**/ ?>