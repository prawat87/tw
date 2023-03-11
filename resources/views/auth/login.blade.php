@extends('layouts.auth')
@section('title')
    {{__('Login')}}
@endsection
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $SITE_RTL = App\Models\Utility::getValByName('SITE_RTL');
    $layout_setting = App\Models\Utility::getLayoutsSetting();
    $setting = App\Models\Utility::settings();

    $dark_mode = 'off';
    if (!empty($setting['dark_mode']))
    {
        $dark_mode = $setting['dark_mode'];
    }

    $color = 'theme-3';
    if (!empty($layout_setting['theme_color']))
    {
        $color = $layout_setting['theme_color'];
    }

    $SITE_RTL = 'off';
    if (!empty($layout_setting['SITE_RTL']))
    {
        $SITE_RTL = $layout_setting['SITE_RTL'];
    }

    $is_sidebar_transperent = 'off';
    if (!empty($layout_setting['is_sidebar_transperent']))
    {
        $is_sidebar_transperent = $layout_setting['is_sidebar_transperent'];
    }
@endphp

@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush

@section('content')
<div class="card">
    <div class="row align-items-center text-start">
        <div class="col-xl-6">
            <div class="card-body">
                <div class="d-flex">
                    <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
                </div>
                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                    @csrf
                    <div>
                        <div class="form-group mb-3">
                            <label class="form-label d-flex">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="" placeholder="{{ __('Email') }}" required autofocus>
                            @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label d-flex">{{ __('Password') }}</label>
                            <input id="password" type="password" value="" class="form-control  @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required>
                            @error('password')
                            <span class="error invalid-password text-danger" role="alert">
                                <small>{{ $message }}</small>
                            </span>
                            @enderror
                            <!-- @if (Route::has('password.request'))
                            <div class="mb-2 mt-2 d-flex">
                                <a href="{{ route('password.request',$lang) }}" class="small text-muted text-underline--dashed border-primar">{{ __('Forgot Your Password?') }}</a>
                            </div>
                            @endif -->
                        </div>
                        <!-- @if(env('RECAPTCHA_MODULE') == 'yes')
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @endif -->

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block mt-2" tabindex="4">{{ __('Login') }}</button>
                        </div>

                        <!-- @if(Utility::getValByName('signup_button')=='on')
                            <p class="my-4 text-center">{{ __("Don't have an account?") }}
                                <a href="{{route('register',$lang)}}" class="my-4 text-primary">{{__('Register')}}</a>
                            </p>
                        @endif -->
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                <h3 class="text-white mb-4 mt-5"> {{ __('“Attention is the new currency”') }}</h3>
                <p class="text-white"> {{__('The more effortless the writing looks, the more effort the writer actually put into the process.')}}</p>
            </div>
        </div>
    </div>
</div>
@endsection


@section('language-bar')

    <li class="nav-item">
        <select name="language" id="language" class="custom_btn btn-primary ms-2 me-2 language_option_bg" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach(App\Models\Utility::languages() as $language)
                <option @if($lang == $language) selected @endif value="{{ route('login',$language) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>

@endsection

@push('custom-scripts')
<script src="{{asset('custom/libs/jquery/dist/jquery.min.js')}}"></script>
<script>
$(document).ready(function () {
  $("#form_data").submit(function (e) {
      $("#login_button").attr("disabled", true);
      return true;
  });
});
</script>
@if(env('RECAPTCHA_MODULE') == 'yes')
    {!! NoCaptcha::renderJs() !!}
@endif
@endpush
