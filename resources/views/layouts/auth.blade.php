@php 
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
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') &dash; {{(Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'Workgo')}}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset(Storage::url('logo/favicon.png'))}}" type="image">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <!-- vendor css -->

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if (isset($layout_setting['dark_mode']) && $layout_setting['dark_mode'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"id="main-style-link">
    @endif

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


    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">
    
    @stack('head')
</head>

<body class="{{!empty($color) ? $color : 'theme-3'}}">

    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        {{-- <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" alt="{{ config('app.name', 'WorkGo') }}" class="navbar-brand-img auth-navbar-brand"> --}}

                        <img id="blah" alt="your image" src="{{$logo.(isset($company_logo) && !empty($company_logo)? $company_logo:'logo-dark.png')}}" alt="{{ config('app.name', 'WorkGo') }}" class="navbar-brand-img auth-navbar-brand">

                    </a>
                </div>
            </nav>
            @yield('content')
            <div class="auth-footer">
                <div class="container-fluid">
                    <p>{{__('Copyright')}} &copy; {{ (Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :config('app.name', 'WorkGo') }} {{date('Y')}}</p>
                </div>
            </div>
        </div>
    </div>

@stack('custom-scripts')
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('custom/js/custom.js')}}"></script>
@stack('script')

</body>
</html>
