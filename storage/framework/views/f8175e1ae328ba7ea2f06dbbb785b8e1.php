<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Humanity Foundation</title>
    <script>
        // Redirect to login page
        window.location.href = "<?php echo e(route('login')); ?>";
    </script>
</head>

<body>
    <p>Redirecting to login...</p>
    <p><a href="<?php echo e(route('login')); ?>">Click here if not redirected</a></p>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\welcome.blade.php ENDPATH**/ ?>