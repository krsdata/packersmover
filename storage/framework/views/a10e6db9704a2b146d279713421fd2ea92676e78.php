<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('vendors/iconfonts/mdi/font/css/materialdesignicons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendors/css/vendor.bundle.base.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendors/css/vendor.bundle.addons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/horizontal-layout/style.css')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('images/logo-mini.png')); ?>" />
</head>
<body>
    <div class="container-scroller">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
      <!-- plugins:js -->
    <script src="<?php echo e(asset('vendors/js/vendor.bundle.base.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/js/vendor.bundle.addons.js')); ?>"></script>
    <script src="<?php echo e(asset('js/off-canvas.js')); ?>"></script>
    <script src="<?php echo e(asset('js/hoverable-collapse.js')); ?>"></script>
    <script src="<?php echo e(asset('js/template.js')); ?>"></script>
    <script src="<?php echo e(asset('js/settings.js')); ?>"></script>
    <script src="<?php echo e(asset('js/todolist.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\saipackers\resources\views/layouts/app.blade.php ENDPATH**/ ?>