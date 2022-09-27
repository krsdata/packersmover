<?php if(!$category->isEmpty()): ?>

<p class="float-right"> Displaying <?php echo e($category->firstItem()); ?>-<?php echo e($category->lastItem()); ?> of <?php echo e($category->total()); ?>  Records</p>
<?php else: ?>
<p class="float-right"> Displaying <?php echo e(' 0 '); ?>-<?php echo e(' 0 '); ?> of <?php echo e($category->total()); ?>  Records</p>
<?php endif; ?>
<br/>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Category </th>
        <th>Sub Category </th>
        <th>Image</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php if(!$category->isEmpty()): ?>
            <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <?php if(!isset($main_cat[$list->parent_id])): ?>
              <?php continue; ?>
             <?php endif; ?>

             <?php 
              
             // $main_cat_name = $main_cat[$list->parent_id]??$list->name;
 
              
              ?> 
            <tr id="<?php echo e(en_de_crypt($list->id,'e')); ?>">
                <td><button class="sid-name-text-st-sm btn badge-primary btn-sm openTankStockDetails"><?php echo e($list->id); ?></button></td>
                <td class="text-capitalize"><?php echo e($main_cat[$list->parent_id]??'NA'); ?></td>

                <td class="text-capitalize"><?php echo e($list->name); ?></td>
                <td class="text-capitalize"><img src="<?php echo e(asset('images/category')); ?>/<?php echo e($list->image); ?>"></td>
                <td class="text-capitalize"><?php echo e($list->created_at); ?></td>
                <td class="actions" data-th="">
                  <a href="<?php echo e(url('/admin/category/edit/' . en_de_crypt($list->id,'e') )); ?>"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>                  
                  
                  <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="<?php echo e($list->id); ?>"><i class="mdi mdi-delete"></i></button>    
                  
                  <form method="GET" action="<?php echo e(url('/admin/category-list?' . en_de_crypt($list->id,'e') )); ?>" accept-charset="UTF-8" id="deleteForm_<?php echo e($list->id); ?>">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="<?php echo e($list->id); ?>">
                  <input type="hidden" name="id" value="<?php echo e(en_de_crypt($list->id,'e')); ?>">

                  </form>
                </td>
            </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <tr><td class="server-error" colspan="6">No Order Found..</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
  <?php echo $category->onEachSide(1)->links('ajax_pagination'); ?>

</div>
<?php /**PATH /var/www/html/app/resources/views/pages/admin/category/table_view.blade.php ENDPATH**/ ?>