
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{(App\Models\Utility::getValByName('header_text')) ? App\Models\Utility::getValByName('header_text') : config('app.name', 'Workgo')}} &dash;  @yield('title') </title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset(Storage::url('logo/favicon.png'))}}" type="image">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('custom/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ac.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    @stack('head')
</head>

<body>
<div class="login-contain">
    <div class="login-inner-contain">
        <a class="navbar-brand" href="#">
            <img src="{{asset(Storage::url('logo/logo_dark.png'))}}" alt="{{ config('app.name', 'WorkGo') }}" class="navbar-brand-img">
        </a>
        @yield('content')
        <h5 class="copyright-text">
            {{__('Copyright')}} &copy; {{ (App\Models\Utility::getValByName('footer_text')) ? App\Models\Utility::getValByName('footer_text') :config('app.name', 'WorkGo') }} {{date('Y')}}
        </h5>
        @yield('language-bar')
    </div>
</div>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('custom/js/custom.js')}}"></script>
@stack('script')
</body>
</html>
