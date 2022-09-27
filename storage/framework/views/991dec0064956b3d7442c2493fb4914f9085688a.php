<?php if(!$contact->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($contact->firstItem()); ?>-<?php echo e($contact->lastItem()); ?> of <?php echo e($contact->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($contact->total()); ?>  Records</p>
<?php endif; ?>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>              
                <th>Id</th>
                <th>Name</th>
                <th>Email Id</th>
                <th>Mobile</th>
                <th>Message</th>
                <th>Type</th>
                <th>Created At</th>
                </tr>
    </thead>
    <tbody>
        <?php if(!$contact->isEmpty()): ?>
            <?php $__currentLoopData = $contact; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
            <td ><?php echo e($list->id); ?></td>
                <td ><?php echo e($list->name); ?></td>
                <td ><?php echo e($list->email_id); ?></td>
                <td ><?php echo e($list->mobile_no); ?></td>
                <td ><?php echo e($list->message); ?></td>
                <td ><?php echo e($list->type); ?></td>               
                <td ><?php echo e($list->created_at); ?></td>                 
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Contact Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $contact->onEachSide(1)->links('ajax_pagination'); ?>

</div><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/contact/table_view.blade.php ENDPATH**/ ?>