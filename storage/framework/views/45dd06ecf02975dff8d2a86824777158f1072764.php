<?php if(!$withdraw->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($withdraw->firstItem()); ?>-<?php echo e($withdraw->lastItem()); ?> of <?php echo e($withdraw->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($withdraw->total()); ?>  Records</p>
<?php endif; ?>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Id</th>
                <th>Acc No</th>
                <th>Branch Code</th>   
                <th>Amount</th>
                <th>Mobile</th>
                </tr>
    </thead>
    <tbody>
        <?php if(!$withdraw->isEmpty()): ?>
            <?php $__currentLoopData = $withdraw; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
            <td ><?php echo e($list->id); ?></td>
                <td ><?php echo e($list->account_number); ?></td>
                <td ><?php echo e($list->branch_code); ?></td>
                <td ><?php echo e($list->amount); ?></td>
                <td ><?php echo e($list->contact); ?></td>                 
                </td> 
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Request Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $withdraw->onEachSide(1)->links('ajax_pagination'); ?>

</div><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/withdraw/table_view.blade.php ENDPATH**/ ?>