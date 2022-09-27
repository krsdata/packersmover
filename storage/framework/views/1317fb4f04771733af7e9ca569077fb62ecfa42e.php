<?php $__env->startSection('content'); ?>

        <!-- register begin -->
        <div class="register">
            <div class="container">
                <div class="reg-body">
                <form  class="form-sample" method="POST" action = "<?php echo e(url('/front/store')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <h4 class="sub-title">Personal Information</h4>
                                <input type="text" placeholder="First Name*" name="name" required>
                                <input type="text" placeholder="Last Name*" name="last_name" required>
                                <input type="email" placeholder="Email*" name="email" required>                                
                                <input type="text" placeholder="Phone No:*" name="contact" required>    
                                
                                <select name="gender" id="gender" class="form-control" style="height: 60px;width: 100%;">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                </select>  
                                
                                <select name="country" id="country" class="form-control" style="height: 60px;width: 100%;" required>
                                <option value="0">Select Country</option>
                                <?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($clist->name); ?>" data-id="<?php echo e($clist->id); ?>"><?php echo e($clist->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 additional-info">
                                <h4 class="sub-title">Additional Information</h4>

                                <select name="state" id="state" class="form-control" style="height: 60px;width: 100%;" required>
                                <option value="0">Select State</option>
                                </select>

                                <select name="city" id="city" class="form-control" style="height: 60px;width: 100%;" required>
                                <option value="0">Select City</option>
                                </select>

                                <input type="password" placeholder="Password*" name="password" required>
                                <input type="password" placeholder="Confirm Password*" name="password_confirmation" required>

                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="exampleRadios" id="exampleRadios5" value="option2">
                                    <label class="form-check-label" for="exampleRadios5">
                                        I am 18+ and I accept terms and conditions
                                    </label>
                                    <p>(*) We will never share your personal information with third parties.</p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <button type="submit" class="def-btn btn-form">Sign Up <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="text-right mt-4 font-weight-light">
                    Already have an account? <a href="<?php echo e(url('logins')); ?>" class="text-primary">Login</a>
                     </div>
                </div>
            </div>
        </div>
        <!-- register end -->
 <?php $__env->stopSection(); ?>

 <script src="<?php echo e(asset('front/js/jquery.js')); ?>"></script>
 <script>
$(document).ready(function(){

     $("#country").change(function(){
        var id = $(this).find(':selected').attr('data-id');
        
        if(id == 0)
        {
            alert("Please Select Country..");
            return false;
        }
                $.ajax({
                type:"GEt",
                headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                },
                url: APP_URL +"/get_states",
                data: {"id":id},
               
                success: function(res){
                    var data = $.parseJSON(res);
                    $(data).each(function (i, val) {
                    
                       $("#state").append("<option value="+val.name+" data-id="+val.id+">"+val.name+"</option>");
                    }); 

                }
            });

     })

     $("#state").change(function(){
        var id = $(this).find(':selected').attr('data-id');

                $.ajax({
                type:"GEt",
                headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                },
                url: APP_URL +"/get_city",
                data: {"id":id},
               
                success: function(res){
                    var data = $.parseJSON(res);
                    $(data).each(function (i, val) {
                       
                       $("#city").append("<option value="+val.name+" data-id="+val.id+">"+val.name+"</option>");
                    }); 

                }
            });

     })
    
})

 </script>
<?php echo $__env->make('front/layouts/main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/front/home/registers.blade.php ENDPATH**/ ?>