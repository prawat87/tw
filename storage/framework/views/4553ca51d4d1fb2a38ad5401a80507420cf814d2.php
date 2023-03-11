<?php
    $logo = asset(Storage::url('logo/'));
    $favicon = App\Models\Utility::getValByName('company_favicon');
    $color = App\Models\Utility::getValByName('theme_color');
    $SITE_RTL = App\Models\Utility::getValByName('SITE_RTL');
    $layout_setting = App\Models\Utility::getLayoutsSetting();
    $setting = App\Models\Utility::settings();

    $dark_mode = 'off';
    if (!empty($setting['dark_mode'])) {
        $dark_mode = $setting['dark_mode'];
    }

    $color = 'theme-3';
    if (!empty($layout_setting['theme_color'])) {
        $color = $layout_setting['theme_color'];
    }

    $SITE_RTL = 'off';
    if (!empty($layout_setting['SITE_RTL'])) {
        $SITE_RTL = $layout_setting['SITE_RTL'];
    }

    $is_sidebar_transperent = 'off';
    if (!empty($layout_setting['is_sidebar_transperent'])) {
        $is_sidebar_transperent = $layout_setting['is_sidebar_transperent'];
    }

?>

<!DOCTYPE html>

<html lang="en" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php echo e(App\Models\Utility::getValByName('header_text') ? App\Models\Utility::getValByName('header_text') : config('app.name', 'WorkGo')); ?>

        &dash; <?php echo $__env->yieldContent('page-title'); ?></title>
    <link rel="icon" href="<?php echo e($logo . '/' . (isset($favicon) && !empty($favicon) ? $favicon : 'favicon.png')); ?>"
        type="image/x-icon">
    <?php echo $__env->yieldPushContent('css-page'); ?>

    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/animate.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/@fortawesome/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/bootstrap-switch-button.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/select2/dist/css/select2.min.css')); ?>">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet">

    <!-- vendor css -->

    <?php if($SITE_RTL == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>">
    <?php endif; ?>
    <?php if($dark_mode == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
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

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header)~.dash-container {
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
    <meta name="url" content="<?php echo e(url('') . '/' . config('chatify.routes.prefix')); ?>"
        data-user="<?php echo e(Auth::user()->id); ?>">
    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>
</head>

<body class="<?php echo e(!empty($color) ? $color : 'theme-3'); ?>">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ navigation menu ] start -->
    <?php echo $__env->make('partials.admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- [ Header ] start -->
    <?php echo $__env->make('partials.admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- [ Main Content ] start -->
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="d-block d-sm-flex align-items-center justify-content-between">
                                <div>
                                    <div class="page-header-title">
                                        <h4 class="m-b-10"><?php echo $__env->yieldContent('page-title'); ?></h4>
                                    </div>
                                    <?php if(Request::route()->getName() != 'dashboard'): ?>
                                    <ul class="breadcrumb my-4">
                                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Home')); ?></a></li>
                                        <?php echo $__env->yieldContent('breadcrumb'); ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php echo $__env->yieldContent('action-button'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <!-- [ Main Content ] end -->
    <footer class="dash-footer">
        <div class="footer-wrapper">
            <div class="py-1">
                <span class="text-muted"><?php echo e(__('Copyright')); ?> &copy;
                    <?php echo e(App\Models\Utility::getValByName('footer_text') ? App\Models\Utility::getValByName('footer_text') : config('app.name', 'WorkGo')); ?>

                    <?php echo e(date('Y')); ?></span>
            </div>
        </div>
    </footer>

    <!-- Modal -->

<div class="modal fade" data-backdrop="static" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="commonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?php if(Request::segment(1) == 'timesheet' || Request::segment(1) == 'team-timesheet'): ?>
            <div class="modal-content" style="margin-left: -250px; width: 225%;">
        <?php else: ?>
            <div class="modal-content" style="width: auto;">
        <?php endif; ?>
            <div class="modal-header">
                <h5 class="modal-title" id="commonModalLabel"></h5>
                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center timesheet-remove-all-logs" data-id="'+ctr+'" data-logged-time="'+msec+'" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" title="<?php echo e(__('Delete')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><input type="button" value="" class="btn btn-close"></a>
            </div>
            <div class="modal-inner-data modal-body">

                </div>
            </div>
        </div>
    </div>

    <input type="checkbox" class="d-none" id="cust-theme-bg"
        <?php echo e(Utility::getValByName('is_sidebar_transperent') == 'on' ? 'checked' : ''); ?> />
    <input type="checkbox" class="d-none" id="cust-darklayout"
        <?php echo e(Utility::getValByName('dark_mode') == 'on' ? 'checked' : ''); ?> />

    <!-- General JS Scripts -->

    <script src="<?php echo e(asset('assets/js/plugins/choices.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/js/jquery.min.js')); ?>"></script>

    <script src="<?php echo e(asset('assets/js/plugins/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/wow.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dash.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/simple-datatables.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/sweetalert2.all.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap-switch-button.min.js')); ?>"></script>

    <script src="<?php echo e(asset('custom/libs/select2/dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/progressbar.js/dist/progressbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/chart/Chart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/chart/Chart.extension.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/moment/min/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="<?php echo e(url('custom/js/jquery.form.js')); ?>"></script>
    <script>
        $(document).ready(function () {
            pushNotification('<?php echo e(Auth::id()); ?>');
        });

        function pushNotification(id) {

            // ajax setup form csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = false;

            var pusher = new Pusher('<?php echo e(env('PUSHER_APP_KEY')); ?>', {
                cluster: '<?php echo e(env('PUSHER_APP_CLUSTER')); ?>',
                forceTLS: true
            });

            var channel = pusher.subscribe('send_notification');
            channel.bind('notification', function (data) {
                if (id == data.user_id) {
                    $(".notification-toggle").addClass('dots');
                    $(".notification-dropdown #notification-list").prepend(data.html);
                }
            });

            // Pusher Message
            var msgChannel = pusher.subscribe('my-channel');
            msgChannel.bind('my-chat', function (data) {
                console.log(data);
                if (id == data.to) {
                    getChat();
                }
            });
        }

        // Mark As Read Notification
        $(document).on("click", ".mark_all_as_read", function () {
            $.ajax({
                url: '<?php echo e(route('notification.seen',\Auth::user()->id)); ?>',
                type: "get",
                cache: false,
                success: function (data) {
                    $('.notification-dropdown #notification-list').html('');
                    $(".notification-toggle").removeClass('dots');
                }
            })
        });

        // Get chat for top ox
//removed getChat and message.seen route reques

        var toster_pos = "<?php echo e($SITE_RTL == 'on' ? 'left' : 'right'); ?>";
    </script>
    <script src="<?php echo e(asset('custom/js/custom.js')); ?>"></script>

    <?php if(App\Models\Utility::getValByName('gdpr_cookie') == 'on'): ?>
        <script type="text/javascript">
            var defaults = {
                'messageLocales': {
                    /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                    'en': "<?php echo e(App\Models\Utility::getValByName('cookie_text')); ?>"
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'cookieNoticePosition': 'bottom',
                'learnMoreLinkEnabled': false,
                'learnMoreLinkHref': '/cookie-banner-information.html',
                'learnMoreLinkText': {
                    'it': 'Saperne di più',
                    'en': 'Learn more',
                    'de': 'Mehr erfahren',
                    'fr': 'En savoir plus'
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'expiresIn': 30,
                'buttonBgColor': '#d35400',
                'buttonTextColor': '#fff',
                'noticeBgColor': '#000',
                'noticeTextColor': '#fff',
                'linkColor': '#009fdd'
            };
        </script>
        <script src="<?php echo e(asset('custom/js/cookie.notice.js')); ?>"></script>
    <?php endif; ?>


    
    <?php if(\Auth::user()->type != 'super admin'): ?>
        <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
        <script>
            $(document).ready(function() {
                pushNotification('<?php echo e(Auth::id()); ?>');
            });

            function pushNotification(id) {

                // ajax setup form csrf token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = false;

                var pusher = new Pusher('<?php echo e(env('PUSHER_APP_KEY')); ?>', {
                    cluster: '<?php echo e(env('PUSHER_APP_CLUSTER')); ?>',
                    forceTLS: true
                });

                var channel = pusher.subscribe('send_notification');
                channel.bind('notification', function(data) {
                    if (id == data.user_id) {
                        $(".notification-toggle").addClass('dots');
                        $(".notification-dropdown #notification-list").prepend(data.html);
                    }
                });

                // Pusher Message
                var msgChannel = pusher.subscribe('my-channel');
                msgChannel.bind('my-chat', function(data) {
                    console.log(data);
                    if (id == data.to) {
                        getChat();
                    }
                });
            }

            // Mark As Read Notification
            $(document).on("click", ".mark_all_as_read", function() {
                $.ajax({
                    url: '<?php echo e(route('notification.seen', \Auth::user()->id)); ?>',
                    type: "get",
                    cache: false,
                    success: function(data) {
                        $('.notification-dropdown #notification-list').html('');
                        $(".notification-toggle").removeClass('dots');
                    }
                })
            });

            // Get chat for top ox

            //getChat method removed
        </script>
    <?php endif; ?>

    <script>
        var date_picker_locale = {
            format: 'YYYY-MM-DD',
            daysOfWeek: [
                "<?php echo e(__('Sun')); ?>",
                "<?php echo e(__('Mon')); ?>",
                "<?php echo e(__('Tue')); ?>",
                "<?php echo e(__('Wed')); ?>",
                "<?php echo e(__('Thu')); ?>",
                "<?php echo e(__('Fri')); ?>",
                "<?php echo e(__('Sat')); ?>"
            ],
            monthNames: [
                "<?php echo e(__('January')); ?>",
                "<?php echo e(__('February')); ?>",
                "<?php echo e(__('March')); ?>",
                "<?php echo e(__('April')); ?>",
                "<?php echo e(__('May')); ?>",
                "<?php echo e(__('June')); ?>",
                "<?php echo e(__('July')); ?>",
                "<?php echo e(__('August')); ?>",
                "<?php echo e(__('September')); ?>",
                "<?php echo e(__('October')); ?>",
                "<?php echo e(__('November')); ?>",
                "<?php echo e(__('December')); ?>"
            ],
        };

        $(document).ready(function() {
            if ($('.dataTable').length > 0) {

                const dataTable = new simpleDatatables.DataTable(".dataTable");

                /*
                            $(".dataTable").dataTable({
                                language: {
                                    "lengthMenu": "<?php echo e(__('Display')); ?> _MENU_ <?php echo e(__('records per page')); ?>",
                                    "zeroRecords": "<?php echo e(__('No data available in table')); ?>",
                                    "info": "<?php echo e(__('Showing page')); ?> _PAGE_ <?php echo e(__('of')); ?> _PAGES_",
                                    "infoEmpty": "<?php echo e(__('No page available')); ?>",
                                    "infoFiltered": "(<?php echo e(__('filtered from')); ?> _MAX_ <?php echo e(__('total records')); ?>)",
                                    "paginate": {
                                        "previous": "<?php echo e(__('Previous')); ?>",
                                        "next": "<?php echo e(__('Next')); ?>",
                                        "last": "<?php echo e(__('Last')); ?>"
                                    }
                                },
                            })*/
            }

            <?php if(Auth::user()->type != 'super admin'): ?>
                $(document).on('keyup', '.search_keyword', function() {
                    search_data($(this).val());
                });
            <?php endif; ?>
        })

        <?php if(Auth::user()->type != 'super admin'): ?>
            // Common main search
            var currentRequest = null;

            function search_data(keyword = '') {
                currentRequest = $.ajax({
                    url: '<?php echo e(route('search.json')); ?>',
                    data: {
                        keyword: keyword
                    },
                    beforeSend: function() {
                        if (currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
                    success: function(data) {
                        $('.search-output').html(data);
                    }
                });
            }
        <?php endif; ?>
    </script>
    <script>
        $(document).ready(function() {
            // cust_theme_bg();
            // $('#dark_mode').trigger('dark_mode');
        });

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        var custthemebg = document.querySelector("#is_sidebar_transperent");
        if (custthemebg !== null) {

            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }

        function cust_theme_bg() {
            var custthemebg = document.querySelector("#is_sidebar_transperent");
            if (custthemebg === null) {
                return false
            }
            // custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
            // });
        }

        var custdarklayout = document.querySelector("#dark_mode");
        if (custdarklayout !== null) {

            custdarklayout.addEventListener("click", function() {
                if (custdarklayout.checked) {
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "<?php echo e(asset('assets/css/style-dark.css')); ?>");
                } else {
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "<?php echo e(asset('assets/css/style.css')); ?>");
                }
            });
        }

        function cust_darklayout() {
            var custdarklayout = document.querySelector("#dark_mode");
            // custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "<?php echo e(asset('assets/css/style-dark.css')); ?>");
            } else {
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "<?php echo e(asset('assets/css/style.css')); ?>");
            }
            // });
        }

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>
    <?php echo $__env->yieldPushContent('script-page'); ?>

    <?php if($message = Session::get('success')): ?>
        <script>
            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo $message; ?>', 'success')
        </script>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
        <script>
            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo $message; ?>', 'error')
        </script>
    <?php endif; ?>

    <?php if($message = Session::get('info')): ?>
        <script>
            show_toastr('<?php echo e(__('Info')); ?>', '<?php echo $message; ?>', 'info')
        </script>
    <?php endif; ?>
    <?php echo $__env->make('Chatify::layouts.footerLinks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH D:\wamp64\www\teamwork\resources\views/layouts/admin.blade.php ENDPATH**/ ?>