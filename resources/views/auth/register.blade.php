@extends('layouts.auth')
@section('title')
    {{__('Register')}}
@endsection
@php
    $logo=asset(Storage::url('uploads/logo/'));
@endphp

@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush

@section('language-bar')

    <li class="nav-item">
        <select name="language" id="language" class="custom_btn btn-primary ms-2 me-2 language_option_bg" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach(App\Models\Utility::languages() as $language)
                <option @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>

@endsection

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <form method="POST" action="{{ route('register') }}" class="needs-validation" validate="">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex">
                            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
                        </div>
                        <div class="">
                            <div class="form-group mb-3">
                                <label class="form-label d-flex">{{ __('Full Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{__('Enter Your Name')}}">
                                @error('name')
                                <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label d-flex">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="{{__('Enter Your Email')}}">
                                @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label d-flex">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{__('Enter Your Password')}}">
                                @error('password')
                                <span class="error invalid-password text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label d-flex">{{__('Confirm Password')}}</label>
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('Confirm Your Password')}}">
                                @error('password_confirmation')
                                    <span class="error invalid-password_confirmation text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            @if(env('RECAPTCHA_MODULE') == 'yes')
                                <div class="form-group col-lg-12 col-md-12 mt-3">
                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                    <span class="error small text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            @endif

                            <div class="d-grid">
                                <button class="btn btn-primary btn-block mt-2" type="submit">{{ __('Register') }}</button>
                            </div>

                        </div>
                        <p class="mb-2 my-4 text-center">{{ __('Already have an account?') }} <a href="{{route('login',$lang)}}" class="f-w-400 text-primary">{{ __('Login') }}</a></p>
                    </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{asset('assets/images/auth/img-auth-3.svg')}}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">{{ __('“Attention is the new currency”') }}</h3>
                    <p class="text-white">{{ __('The more effortless the writing looks, the more effort the writer actually put into the process.')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush

