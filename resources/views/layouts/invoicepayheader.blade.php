@php
    $layout_setting = App\Models\Utility::getLayoutsSetting();
    $SITE_RTL = 'off';
    if (!empty($layout_setting['SITE_RTL'])) 
    {
        $SITE_RTL = $layout_setting['SITE_RTL'];
    }
    $color = 'theme-3';
    if (!empty($setting['color'])) {
        $color = $setting['color'];
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$SITE_RTL == 'on'?'rtl':''}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    // $logo=asset(Storage::url('logo/'));
    $logo=\App\Models\Utility::get_file('uploads/logo/');
    $favicon=App\Models\Utility::getValByName('company_favicon');
    $color = App\Models\Utility::getValByName('theme_color');
@endphp
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>   {{(App\Models\Utility::getValByName('header_text')) ? App\Models\Utility::getValByName('header_text') : config('app.name', 'WorkGo')}} &dash; @yield('page-title')</title>
    <link rel="icon" href="{{$logo.'/'.(isset($favicon) && !empty($favicon)?$favicon:'favicon.png')}}" type="image/x-icon">
    @stack('css-page')

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('custom/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">
    <link rel="stylesheet" href="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('custom/libs/select2/dist/css/select2.min.css') }}">
    <!-- vendor css -->
    
    @if($SITE_RTL=='on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @else
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css')}}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">
</head>

<body class="{{!empty($color) ? $color : 'theme-3'}}">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
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
                                        <h4 class="m-b-10">@yield('page-title')</h4>
                                    </div>
                                </div>
                                @yield('action-button')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="dash-footer">
        <div class="footer-wrapper">
            <div class="py-1">
                <span class="text-muted">{{__('Copyright')}} &copy; {{ (App\Models\Utility::getValByName('footer_text')) ? App\Models\Utility::getValByName('footer_text') :config('app.name', 'WorkGo') }} {{date('Y')}}</span>
            </div>
        </div>
    </footer>

<!-- General JS Scripts -->
<script src="{{asset('custom/js/jquery.min.js')}}"></script>

<script src="{{asset('assets/js/plugins/popper.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/feather.min.js')}}"></script>
<script src="{{asset('assets/js/dash.js')}}"></script>
<script src="{{asset('assets/js/plugins/simple-datatables.js')}}"></script>
<script src="{{asset('assets/js/plugins/sweetalert2.all.min.js')}}"></script>

<script src="{{ asset('custom/libs/progressbar.js/dist/progressbar.min.js') }}"></script>
<script src="{{ asset('custom/libs/chart/chart.js') }}"></script>
<script src="{{ asset('custom/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('custom/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('custom/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{url('custom/js/jquery.form.js')}}"></script>
<script>
    var toster_pos="{{env('SITE_RTL') =='on' ?'left' : 'right'}}";
</script>
<script src="{{ asset('custom/js/custom.js') }}"></script>

@if(App\Models\Utility::getValByName('gdpr_cookie') == 'on')
<script type="text/javascript">

    var defaults = {
    'messageLocales': {
        /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
        'en': "{{App\Models\Utility::getValByName('cookie_text')}}"
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
    'noticeBgColor': 'var(--primary)',
    'noticeTextColor': '#fff',
    'linkColor': '#009fdd'
};
</script>
<script src="{{ asset('custom/js/cookie.notice.js')}}"></script>
@endif


<script>
    var date_picker_locale = {
        format: 'YYYY-MM-DD',
        daysOfWeek: [
            "{{__('Sun')}}",
            "{{__('Mon')}}",
            "{{__('Tue')}}",
            "{{__('Wed')}}",
            "{{__('Thu')}}",
            "{{__('Fri')}}",
            "{{__('Sat')}}"
        ],
        monthNames: [
            "{{__('January')}}",
            "{{__('February')}}",
            "{{__('March')}}",
            "{{__('April')}}",
            "{{__('May')}}",
            "{{__('June')}}",
            "{{__('July')}}",
            "{{__('August')}}",
            "{{__('September')}}",
            "{{__('October')}}",
            "{{__('November')}}",
            "{{__('December')}}"
        ],
    };

    $(document).ready(function () {
        if ($('.dataTable').length > 0) {
            $(".dataTable").dataTable({
                language: {
                    "lengthMenu": "{{__('Display')}} _MENU_ {{__('records per page')}}",
                    "zeroRecords": "{{__('No data available in table')}}",
                    "info": "{{__('Showing page')}} _PAGE_ {{__('of')}} _PAGES_",
                    "infoEmpty": "{{__('No page available')}}",
                    "infoFiltered": "({{__('filtered from')}} _MAX_ {{__('total records')}})",
                    "paginate": {
                        "previous": "{{__('Previous')}}",
                        "next": "{{__('Next')}}",
                        "last": "{{__('Last')}}"
                    }
                },
            })
        }

    })

</script>



@stack('script-page')



@if ($message = Session::get('success'))
    <script>show_toastr('{{__("Success")}}', '{!! $message !!}', 'success')</script>
@endif

@if ($message = Session::get('error'))
    <script>show_toastr('{{__("Error")}}', '{!! $message !!}', 'error')</script>
@endif

@if ($message = Session::get('info'))
    <script>show_toastr('{{__("Info")}}', '{!! $message !!}', 'info')</script>
@endif

</body>
</html>
