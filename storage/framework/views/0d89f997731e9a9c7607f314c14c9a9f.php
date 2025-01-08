

<?php $__env->startSection('content'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
</head><link href="<?php echo e(asset('css/login.css')); ?>" rel="stylesheet">
<body>
    <div class="login-container">
        <h1>Login</h1>
        <a href="<?php echo e(route('register')); ?>">Daftar</a>
        <form action="<?php echo e(route('authenticate')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auth.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\sulis_pitri\resources\views/auth/login.blade.php ENDPATH**/ ?>