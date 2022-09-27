<?php $__env->startSection('content'); ?>
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
            <div class="brand-logo">
                <?php if(session('status')): ?>
                    <p class="alert alert-success"><?php echo e(session('status')); ?></p>
                <?php endif; ?>
            </div>
            <h4>Welcome back!</h4>
            <h6 class="font-weight-light">Happy to see you again!</h6>
            <form method="POST" action="<?php echo e(route('login')); ?>" class="pt-3">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="email"><?php echo e(__('E-Mail Address')); ?></label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-account-outline text-primary"></i>
                    </span>
                </div>
                <input id="email" type="email" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

                <?php if($errors->has('email')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password"><?php echo e(__('Password')); ?></label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-lock-outline text-primary"></i>
                    </span>
                    <span toggle="#password" class="mdi mdi-eye-off toggle-password" onclick="myFunction(this)"></span>
                </div>
                <input id="password" type="password" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required autocomplete="current-password">
                <?php if($errors->has('password')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('password')); ?></strong>
                    </span>
                <?php endif; ?>                     
                </div>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <?php echo e(__('Remember Me')); ?>

                </label>
                </div>
                <?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="auth-link text-black"><?php echo e(__('Forgot Your Password?')); ?></a>
                <?php endif; ?>
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"> <?php echo e(__('Login')); ?></button>
            </div>
           
            <div class="text-center mt-4 font-weight-light">
                Don't have an account? <a href="<?php echo e(route('register')); ?>" class="text-primary">Create</a>
            </div>
            </form>
             <div style="position: relative;top: 50px;">
             <!-- <p><a href="https://appristine.com/" target="_blank">Developed by Wevaluesoft Technologies</a></p>--></div>
        </div>
        </div>
        <div class="col-lg-6 login-half-bg d-flex flex-row">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright ©2021 Sai Packers And Movers Limited. All rights reserved.</p>
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->
<style>
.eyeicons{
    right: 1%;
    position: absolute;
    z-index: 9999;
    top: 19px;
    font-size: 19px;
    color: #5258c5;
}
</style>
<script>
function myFunction(e) {

var x = document.getElementById("password");
if (x.type === "password") {
    e.classList.add('mdi-eye')
  e.classList.remove('mdi-eye-off')
  x.type = "text";
} else {
    e.classList.add('mdi-eye-off')
  e.classList.remove('mdi-eye')
  x.type = "password";
}
}</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/app/resources/views/auth/login.blade.php ENDPATH**/ ?>