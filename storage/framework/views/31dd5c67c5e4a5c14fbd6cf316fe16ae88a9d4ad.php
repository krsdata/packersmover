<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Id</th>
                <th>First Name</th>
                <th>Email</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Created At</th>                                                
                </tr>
    </thead>
    <tbody>
        <?php if(!$feedback->isEmpty()): ?>
            <?php $__currentLoopData = $feedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt(@$list->id,'e')); ?>">
            <td ><?php echo e(@$list->id); ?></td>
                <td ><?php echo e(@$list->name); ?></td>
                <td ><?php echo e(@$list->email); ?></td>
                <td ><?php echo e(@$list->review); ?></td>
                <td ><?php echo e(@$list->rating); ?></td>
                <td ><?php echo e(@$list->created_at); ?></td>                 
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Feedback Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $feedback->onEachSide(1)->links('ajax_pagination'); ?>

</div><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/feedback/table_view.blade.php ENDPATH**/ ?>