<?php if(!$customers->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($customers->firstItem()); ?>-<?php echo e($customers->lastItem()); ?> of <?php echo e($customers->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($customers->total()); ?>  Records</p>
<?php endif; ?>
<a class="btn btn-success" href="<?php echo e(route('exportUsers')); ?>">Export Users</a>
<a class="btn btn-success" href="<?php echo e(route('generate_pdf')); ?>">Export Pdf users</a>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        
      </tr>
    </thead>
    <tbody>
        <?php if(!$customers->isEmpty()): ?>
        <?php $i=1;?>
            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
                <td><?php echo e($i++); ?></td>
                <td><?php echo e($list->name); ?> <?php echo e($list->last_name); ?></td>
                <td><?php echo e($list->email); ?></td>
                <td><?php echo e($list->contact); ?></td>
           </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No User Found..</td></tr>
        <?php endif; ?>
</tbody>
</table>
  <?php echo $customers->onEachSide(1)->links('ajax_pagination'); ?>

</div>
<?php /**PATH /home/saipacker/public_html/resources/views/pages/admin/customer/table_view.blade.php ENDPATH**/ ?>