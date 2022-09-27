<?php $__env->startSection('dashcontent'); ?>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>  
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="cust-card-title"><i class="mdi mdi-format-list-bulleted cust-box-icon"></i> <?php if(isset($category)): ?> <?php echo e('Update Category'); ?> <?php else: ?> <?php echo e('Add New Category'); ?> <?php endif; ?></h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample" method="POST" action = "<?php echo e(url('/admin/category/store')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  <?php echo e(csrf_field()); ?>

          <input type="hidden" name="id" id="id" value="<?php if(isset($category)): ?><?php echo e(en_de_crypt($category->id,'e')); ?><?php endif; ?>">
          
          <div class="col-sm-12">&nbsp;
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Category Name</label>
                <div class="col-sm-9">
                  <input type="text" name="name" id="name" class="form-control"
                  data-msg-required="Name is required" value="<?php if(isset($category)): ?><?php echo e($category->name); ?><?php endif; ?>" required/>
                  <label id="name-error-server" class="server-error" for="name"></label>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Main Category</label>
                <div class="col-sm-9">
                  <select class="form-control dy_product" name="parent_id">
                  <option value="0">Select Category</option>
                   <?php if(isset($Categorys)): ?>
                   <?php $__currentLoopData = $Categorys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   <?php endif; ?> 
                  </select>
                  <label id="category-error-server" class="server-error" for="category"></label>
                </div>
              </div>

             
        
            </div>


            <div class="col-md-6">

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Category Image</label>
                <div class="col-sm-9">
                  <input type="hidden" name="image_val" id="image_val" 
                  value="<?php if(!empty($category->image)): ?><?php echo e($category->image); ?><?php endif; ?>">
                  <input type="file" name="image" id="image" class="dropify" data-default-file=" <?php if(!empty($category->image)): ?><?php echo e(asset('images/category')); ?>/<?php echo e($category->image); ?><?php endif; ?>" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                </div>  
              </div>
              
            

              <div class="form-group">
                  <button type="reset" value="reset" id="create_user_reset" style="display:none"></button>
                  <button type="button" class="btn btn-primary  float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> <?php if(!isset($tank->id)): ?><?php echo e('Submit'); ?> <?php else: ?> <?php echo e('Update'); ?> <?php endif; ?> </button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
  $('.dropify').dropify();
});

$(document).on('change','#image',function(){
  var data = new FormData($("#stock_frm")[0]); 
  $.ajax({
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
    },
    url: APP_URL +"/category/img_upload",
    data: data,
    cache : false,
    processData: false,
    contentType: false,
    success: function(res){
      if(res!="false"){
        $('#image_val').val(res);
        $('#image').val(res);
      }
    }
  });
});





</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/app/resources/views/pages/admin/category/create.blade.php ENDPATH**/ ?>