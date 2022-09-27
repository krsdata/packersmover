<?php if(!$users->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($users->firstItem()); ?>-<?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($users->total()); ?>  Records</p>
<?php endif; ?>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(!$users->isEmpty()): ?>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
                <td><?php echo e($list->name); ?></td>
                <td><?php echo e($list->last_name); ?></td>
                <td><?php echo e($list->email); ?></td>
                <td><?php echo e($list->contact); ?></td>
                
                <td class="actions" data-th="">
                
               <?php if($list->type != "admin" && $list->id != $user_id): ?>
                <a href="<?php echo e(url('/admin/user/edit/' . en_de_crypt($list->id,'e') )); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                
               <?php endif; ?>
              
              </td>
           </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No User Found..</td></tr>
        <?php endif; ?>
</tbody>
</table>
  <?php echo $users->onEachSide(1)->links('ajax_pagination'); ?>

</div>
<?php /**PATH /home/saipacker/public_html/resources/views/pages/admin/user/table_view.blade.php ENDPATH**/ ?>