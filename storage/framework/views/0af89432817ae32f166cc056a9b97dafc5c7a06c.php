<?php $__env->startSection('content'); ?>

        <!-- register begin -->
        <div class="register login-page">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-md-8">
                        <div class="reg-body">
                        <form method="POST" action="<?php echo e(route('login')); ?>" class="pt-3">
                             <?php echo e(csrf_field()); ?>

                                <h4 class="sub-title">Login to your account</h4>
                                <input id="email" type="email" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="User Name*" required>
                                <?php if($errors->has('email')): ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($errors->first('email')); ?></strong>
                                </span>
                            <?php endif; ?>
                                <input id="password" type="password" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required autocomplete="current-password" required>
                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>    
                                <button type="submit" class="btn-form def-btn">Login</button>
                            </form>
                            <div class="">
                    Don't have an account? <a href="<?php echo e(url('registers')); ?>" class="text-primary">Create</a>
                     </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $__env->stopSection(); ?>
<?php echo $__env->make('front/layouts/main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/front/home/logins.blade.php ENDPATH**/ ?>