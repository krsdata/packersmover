<?php $__env->startSection('dashcontent'); ?>
 <style type="text/css">
   
   .upload_btn {
     position: relative;
     overflow: hidden;
   }
   .type_file {
     position: absolute;
     font-size: 50px;
     opacity: 0;
     right: 0;
     top: 0;
   }

   .dropify-wrapper.has-preview {
    background-color: blue;
   }

</style>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
            <h4 class="cust-card-title">
              <i class="mdi  mdi-account cust-box-icon"></i>
              User Profile
            </h4>
        </div>
        <div class="col-sm-6">
            <?php if($role_name == "user"): ?>
            <h4 class="cust-card-title">
              <i class="mdi mdi-coin cust-box-icon"></i>
              Loyalty Points : <?php echo e($lp); ?>

            </h4>
            <?php endif; ?>
        </div>
        </div>
        <div class="row mt-5">
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="col-md-12 p-0 pt-1">
              <form name="user_update" id="user_update" method="POST" action = "<?php echo e(route('store_edit_posadmin')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" > 

                    <?php echo e(csrf_field()); ?>


                <input type="hidden" name="user_id" id="user_id" value="<?php echo e(en_de_crypt($user->id,'e')); ?>">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Edit User : </label>
                    </div>
                  </div>                  
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control required" data-msg-required="First name is required." id="name" placeholder="First name">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <input name="last_name" type="text" value="<?php echo $user->last_name; ?>" data-msg-required="Last name is required." class="form-control required" id="last_name" placeholder="Last name">
                    </div>
                  </div>
                  
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="Number" value="<?php echo $user->contact; ?>" name="contact" class="form-control required" data-msg-required="Contact Number is required." id="contact" placeholder="Contact Number">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="email" name="email" value="<?php echo $user->email; ?>" class="form-control required" data-msg-required="Email is required." id="email" placeholder="Email Address">
                    </div>
                  </div>                  
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="city" name="city" value="<?php echo $user->city; ?>" class="form-control required" data-msg-required="City is required." id="city" placeholder="City">
                    </div>
                  </div>                                                   
                  
                  <?php if(Auth::user()->type=='admin') { ?>
                  <div class="col-md-12">
                    <div class="form-group">
                      <?php if(!empty($logo)): ?>
                      <input type="file" name="image" id="image" class="dropify"data-default-file="<?php echo e(asset('images/logo')); ?>/<?php echo e($logo); ?>" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">  
                      <?php else: ?>
                      <input type="file" name="image" id="image" class="dropify"data-default-file="<?php echo e(asset('images/logo/logo.png')); ?>" data-allowed-file-extensions="png jpg jpeg" data-errors-position="outside">
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php } ?>
                  <br><br>
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-rounded btn-fw  submit_from">Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="col-md-12 p-0 pt-1">
              <form name="change_pass" id="change_pass" method="POST" action = "<?php echo e(route('change_user_pass')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" >

                <?php echo e(csrf_field()); ?>


                <input type="hidden" name="user_id_pass" id="user_id_pass" value="<?php echo e(en_de_crypt($user->id,'e')); ?>">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Change Password: </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="password" name="current_pass" class=" required form-control" id="current_pass" placeholder="Current Password" data-msg-required="Current password is required." >
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="password" name="password" class="form-control required pass" id="password" data-msg-required="New password is required." data-msg-pattern="Enter minimum 8 chars with atleast 1 number, lower, upper & special(@#$%&) char." placeholder="New Password">
                      <label id="password-error-server" class="server-error" for="password"></label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="password" name="confirm_password" class="form-control required" data-msg-required="Confirm password is required." id="confirm_password" placeholder="Confirm Password">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-rounded btn-fw submit_from">Change</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
   $(document).ready(function(){
         $('.dropify').dropify();
   });

  $(document).on('change','#image',function(){
         var data = new FormData($("#user_update")[0]); 
         $.ajax({
            type:"POST",
            headers: {
             'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            },
            url: APP_URL +"/admin/img_upload",
            data: data,
            cache : false,
             processData: false,
            contentType: false,
            success: function(res){
            if(res!="false"){
               $('#org_img').val(res);
            }
            }
         });
  });
</script>  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/pages/admin/user/edit_user.blade.php ENDPATH**/ ?>