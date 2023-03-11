<?php 
    // $logo=asset(Storage::url('logo/'));
    $logo=\App\Models\Utility::get_file('logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $color = App\Models\Utility::getValByName('theme_color');
    // $dark_mode = App\Models\Utility::getValByName('dark_mode');
    $layout_setting = App\Models\Utility::getLayoutsSetting();
    $SITE_RTL = 'off';
    if (!empty($layout_setting['SITE_RTL'])) 
    {
        $SITE_RTL = $layout_setting['SITE_RTL'];
    }
    $dark_mode = 'off';
    if (!empty($layout_setting['dark_mode'])) 
    {
      $dark_mode = $layout_setting['dark_mode'];
    }
?>


<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(env('SITE_RTL') == 'on'?'rtl':''); ?>">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title> <?php echo $__env->yieldContent('title'); ?> &dash; <?php echo e((Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'Workgo')); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset(Storage::url('logo/favicon.png'))); ?>" type="image">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">
    <!-- vendor css -->

    <?php if($SITE_RTL == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>">
    <?php endif; ?>
    <?php if(isset($layout_setting['dark_mode']) && $layout_setting['dark_mode'] == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>"id="main-style-link">
    <?php endif; ?>

<style>
    [dir="rtl"] .dash-sidebar {
        left: auto !important;
    }
    [dir="rtl"] .dash-header {
        left: 0;
        right: 280px;
    }
    [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
        padding: 0 0 0 30px;
    }
    [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
        margin-left: 0px !important;
    }
    [dir="rtl"] .me-auto.dash-mob-drp {
        margin-right: 10px !important;
    }
    [dir="rtl"] .me-auto {
        margin-left: 10px !important;
    }

</style>


    <link rel="stylesheet" href="<?php echo e(asset('assets/css/customizer.css')); ?>">
    <!-- custom css -->
    <link rel="stylesheet" href="<?php echo e(asset('custom/css/custom.css')); ?>">
    
    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<body class="<?php echo e(!empty($color) ? $color : 'theme-3'); ?>">

    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        

                        <img id="blah" alt="your image" src="<?php echo e($logo.(isset($company_logo) && !empty($company_logo)? $company_logo:'logo-dark.png')); ?>" alt="<?php echo e(config('app.name', 'WorkGo')); ?>" class="navbar-brand-img auth-navbar-brand">

                    </a>
                </div>
            </nav>
            <?php echo $__env->yieldContent('content'); ?>
            <div class="auth-footer">
                <div class="container-fluid">
                    <p><?php echo e(__('Copyright')); ?> &copy; <?php echo e((Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :config('app.name', 'WorkGo')); ?> <?php echo e(date('Y')); ?></p>
                </div>
            </div>
        </div>
    </div>

<?php echo $__env->yieldPushContent('custom-scripts'); ?>
<script src="<?php echo e(asset('assets/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('custom/js/custom.js')); ?>"></script>
<?php echo $__env->yieldPushContent('script'); ?>

</body>
</html>
<?php /**PATH D:\wamp64\www\switches\resources\views/layouts/auth.blade.php ENDPATH**/ ?>