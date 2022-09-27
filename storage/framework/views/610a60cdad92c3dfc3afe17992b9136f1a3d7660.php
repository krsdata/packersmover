<!DOCTYPE html>
<html lang="en">
<!-- head -->
<head>

<?php echo $__env->make('front/layouts/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</head>
<!-- end head -->

<body>
<!-- wrapper -->
<div id="wrapper">
<?php echo $__env->yieldContent('content'); ?>
</div>
<!-- end wrapper -->

<!-- footer -->
<?php echo $__env->make('front/layouts/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- end footer -->
</body>

</html><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/front/layouts/main.blade.php ENDPATH**/ ?>