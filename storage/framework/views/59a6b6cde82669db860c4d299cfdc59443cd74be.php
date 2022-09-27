<?php if(!$order->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($order->firstItem()); ?>-<?php echo e($order->lastItem()); ?> of <?php echo e($order->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($order->total()); ?>  Records</p>
<?php endif; ?>

<br/>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(!$order->isEmpty()): ?>
            <?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
                <td><button class="btn badge-primary btn-sm openTankStockDetails"><?php echo e($list->id); ?></button></td>                
                <td class="text-capitalize"><?php echo e($list->name); ?></td>
                <td class="text-capitalize"><?php echo e($list->email); ?></td>
                <td class="text-capitalize"><?php echo e($list->contact); ?></td>
                <td class="actions" data-th="">
                  <a href="<?php echo e(url('/admin/order/order_detail/' . en_de_crypt($list->id,'e') )); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-eye"></i></button></a>                  
                  
                  <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="<?php echo e($list->id); ?>"><i class="mdi mdi-delete"></i></button>    
                  
                  <a class="btn btn-success" target="_blank" href="<?php echo e(url('/admin/order/generate_invoicepdf/' . en_de_crypt($list->id,'e'))); ?>">Pdf</a>

                  <form method="GET" action="<?php echo e(url('/admin/order/order_delete/' . en_de_crypt($list->id,'e') )); ?>" accept-charset="UTF-8" id="deleteForm_<?php echo e($list->id); ?>">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="<?php echo e($list->id); ?>">

                  </form>

                </td>
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Expense Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $order->onEachSide(1)->links('ajax_pagination'); ?>

</div>

<?php /**PATH /var/www/html/app/resources/views/pages/admin/order/table_view.blade.php ENDPATH**/ ?>