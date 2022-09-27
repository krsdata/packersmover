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
                
               <?php if(Auth::user()->type == "sai-manager"): ?>
               <a href="<?php echo e(url('/admin/user/edit/' . en_de_crypt($list->id,'e') )); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="<?php echo e($list->id); ?>"><i class="mdi mdi-delete"></i></button>    
                  
                  <form method="GET" action="<?php echo e(url('/admin/user-list?' . en_de_crypt($list->id,'e') )); ?>" accept-charset="UTF-8" id="deleteForm_<?php echo e($list->id); ?>">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="<?php echo e($list->id); ?>">
                  <input type="hidden" name="id" value="<?php echo e(en_de_crypt($list->id,'e')); ?>">

                  </form>
               <?php elseif($list->type != "admin" && $list->id != $user_id): ?>
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
<?php /**PATH /var/www/html/app/resources/views/pages/admin/user/table_view.blade.php ENDPATH**/ ?>