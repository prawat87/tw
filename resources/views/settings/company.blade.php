
@extends('layouts.admin')
    @php
        // $logo = asset(Storage::url('logo/'));
        $logo=\App\Models\Utility::get_file('logo/');
        $invoice_logo = \App\Models\Utility::get_file('invoice_logo/');
        $estimation_logo=\App\Models\Utility::get_file('estimation_logo/');
        $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';
        $layout_setting = App\Models\Utility::getLayoutsSetting();
        $GetLogo = App\Models\Utility::GetLogo();

        $SITE_RTL = 'off';
        if (!empty($layout_setting['SITE_RTL']))
        {
            $SITE_RTL = $layout_setting['SITE_RTL'];
        }

        $dark_mode = 'off';
        $company_logo_light = $layout_setting['company_logo_light'];
        $company_logo = $layout_setting['company_logo'];
        $company_favicon = $layout_setting['company_favicon'];



        if (!empty($layout_setting['dark_mode']))
        {
            $dark_mode = $layout_setting['dark_mode'];
            $company_logo = $layout_setting['company_logo'];
        }

        $is_sidebar_transperent = 'on';
        if (!empty($layout_setting['is_sidebar_transperent']))
        {
            $is_sidebar_transperent = $layout_setting['is_sidebar_transperent'];
        }
    @endphp

@section('page-title')
    {{__('Settings')}}
@endsection

@push('script-page')
    <script>
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{url('/invoices/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='estimation_template'], input[name='estimation_color']", function () {
            var template = $("select[name='estimation_template']").val();
            var color = $("input[name='estimation_color']:checked").val();
            $('#estimation_frame').attr('src', '{{url('/estimations/preview')}}/' + template + '/' + color);
        });
    </script>
    <script type="text/javascript">
        @can('on-off email template')
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('{{__("Success")}}', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('{{__("Error")}}', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('{{__("Error")}}', response.error, 'error');
                    } else {
                        show_toastr('{{__("Error")}}', response, 'error');
                    }
                }
            })
        });
        @endcan
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
@endsection
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">

                            <a href="#business-settings" id="business-setting-tab" class="list-group-item list-group-item-action border-0">{{__('Business Settings')}} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#system-settings" id="system-setting-tab" class="list-group-item list-group-item-action border-0">{{__('System Settings')}} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#company-settings" id="company-setting-tab" class="list-group-item list-group-item-action border-0">{{__('Company Settings')}} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#paymeny-settings" id="paymeny-setting-tab" class="list-group-item list-group-item-action border-0">{{__('Payment Settings')}} <div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#invoice-print-settings" id="invoice-print-tab" class="list-group-item list-group-item-action border-0">{{__('Invoice Print Settings')}} <div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#estimation-print-settings" id="estimation-print-tab" class="list-group-item list-group-item-action border-0">{{__('Estimation Print Settings')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a id="email-notification-tab" data-toggle="tab" href="#email-notification-settings" role="tab" aria-controls="" aria-selected="false" class="list-group-item list-group-item-action border-0">{{__('Email Notification Settings')}}<div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            @if(Auth::user()->type == 'company')
                            <a href="#zoom-meeting-settings" id="zoom-meeting-tab" class="list-group-item list-group-item-action border-0">{{__('Zoom Meeting')}} <div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#slack-settings" id="slack-tab" class="list-group-item list-group-item-action border-0">{{__('Slack Settings')}} <div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#telegram-settings" id="telegram-tab" class="list-group-item list-group-item-action border-0">{{__('Telegram Settings')}} <div
                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="" id="business-settings">
                       {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Business Settings') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Logo dark') }}</h5>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4 setting-logo">
                                                                {{-- <a href="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" target="_blank">
                                                                <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" id="blah" class="logo logo-sm"></a> --}}

                                                                <a href="{{$logo.(isset($company_logo) && !empty($company_logo)? $company_logo:'logo-dark.png')}}" target="_blank">
                                                                <img id="blah" alt="your image" src="{{$logo.(isset($company_logo) && !empty($company_logo)? $company_logo:'logo-dark.png')}}" width="150px" class="logo logo-sm">
                                                                </a>

                                                            </div>
                                                            <div class="choose-files mt-5">
                                                                <label for="company_logo">
                                                                    <div class=" bg-primary company_logo_update"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input style="margin-top: -25px;" type="file" class="form-control file" name="company_logo" id="company_logo" accept=".jpeg,.jpg,.png" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                            </div>
                                                            @error('company_logo')
                                                                <div class="row">
                                                                    <span class="invalid-company_logo" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Logo Light') }}</h5>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4 text-center setting-logo">

                                                                {{-- <a href="{{$logo.'/'.(isset($company_logo_light) && !empty($company_logo_light)?$company_logo_light:'logo-light.png')}}" target="_blank">
                                                                <img src="{{$logo.'/'.(isset($company_logo_light) && !empty($company_logo_light)?$company_logo_light:'logo-light.png')}}
                                                             " id="blah1" class="logo logo-sm img_setting" style="filter: drop-shadow(2px 3px 7px #011c4b);"></a> --}}

                                                                <a href="{{$logo.(isset($company_logo_light) && !empty($company_logo_light)? $company_logo_light:'logo-light.png')}}" target="_blank">
                                                                <img id="blah1" alt="your image" src="{{$logo.(isset($company_logo_light) && !empty($company_logo_light)? $company_logo_light:'logo-light.png')}}" width="150px" class="logo logo-sm" style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                                </a>

                                                            </div>
                                                            <div class="choose-files mt-5">
                                                                <label for="company_logo_light">
                                                                    <div class=" bg-primary dark_logo_update"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input style="margin-top: -25px;" type="file" class="form-control file" name="company_logo_light" id="company_logo_light" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                            </div>
                                                            @error('company_logo_light')
                                                                <div class="row">
                                                                    <span class="invalid-company_logo_light" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Favicon') }}</h5>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4 setting-logo">
                                                                {{-- <a href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" target="_blank">
                                                                <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" width="50px" id="blah2" class="logo logo-sm img_setting"></a> --}}

                                                                <a href="{{$logo.(isset($company_favicon) && !empty($company_favicon)? $company_favicon:'favicon.png')}}" target="_blank">
                                                                    <img id="blah2" alt="your image" src="{{$logo.(isset($company_favicon) && !empty($company_favicon)? $company_favicon:'favicon.png')}}" width="150px" class="logo logo-sm img_setting">
                                                                </a>
                                                            </div>
                                                            <div class="choose-files mt-5">
                                                                <label for="company_favicon">
                                                                    <div class=" bg-primary company_favicon_update"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input style="margin-top: -25px;" type="file" class="form-control file" name="company_favicon" id="company_favicon" accept=".jpeg,.jpg,.png" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                            </div>
                                                            @error('company_favicon')
                                                                <div class="row">
                                                                    <span class="invalid-logo" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="pct-body">
                                                    <div class="row">
                                                        <div class="col-12 p-3">
                                                            <h4 class="small-title">{{__('Theme Customizer')}}</h4>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class="">
                                                                <i data-feather="credit-card" class="me-2"></i>{{__('Primary color settings')}}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="theme-color themes-color">
                                                            <input type="hidden" name="theme_color" id="color_value" value="{{ $setting['theme_color'] }}">
                                                            <a href="#!" class="themes-color-change {{($setting['theme_color'] =='theme-1') ? 'active_color' : ''}}" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                            <input type="radio" class="theme_color d-none" name="theme_color" value="theme-1" {{($setting['theme_color'] =='theme-1') ? 'checked' : ''}} >
                                                            <a href="#!" class="themes-color-change {{($setting['theme_color'] =='theme-2') ? 'active_color' : ''}}" data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                            <input type="radio" class="theme_color d-none" name="theme_color" value="theme-2" {{($setting['theme_color'] =='theme-2') ? 'checked' : ''}} >
                                                            <a href="#!" class="themes-color-change {{($setting['theme_color'] =='theme-3') ? 'active_color' : ''}}" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                            <input type="radio" class="theme_color d-none" name="theme_color" value="theme-3" {{($setting['theme_color'] =='theme-3') ? 'checked' : ''}} >
                                                            <a href="#!" class="themes-color-change {{($setting['theme_color'] =='theme-4') ? 'active_color' : ''}}" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                            <input type="radio" class="theme_color d-none" name="theme_color" value="theme-4" {{($setting['theme_color'] =='theme-4') ? 'checked' : ''}} >
                                                            <a href="#!" class="themes-color-change {{($setting['theme_color'] =='theme-5') ? 'active_color' : ''}}" data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                                            <input type="radio" class="theme_color d-none" name="theme_color" value="theme-5" {{($setting['theme_color'] =='theme-5') ? 'checked' : ''}} >
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class=" ">
                                                                <i data-feather="layout" class="me-2"></i>{{__('Sidebar settings')}}
                                                            </h6>
                                                            <hr class="my-2 " />
                                                            <div class="form-check form-switch ">
                                                                <input type="checkbox" class="form-check-input" id="is_sidebar_transperent" name="is_sidebar_transperent"  @if($is_sidebar_transperent == 'on') checked @endif/>
                                                                <label class="form-check-label f-w-600 pl-1" for="is_sidebar_transperent">{{__('Transparent layout')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class=" ">
                                                                <i data-feather="sun" class=""></i>{{__('Layout settings')}}
                                                            </h6>
                                                            <hr class=" my-2  " />
                                                            <div class="form-check form-switch mt-2 ">
                                                                <input type="checkbox" class="form-check-input" id="dark_mode" name="dark_mode"@if($dark_mode == 'on') checked @endif/>

                                                                <label class="form-check-label f-w-600 pl-1" for="dark_mode">{{__('Dark Layout')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-9">
                                                {{Form::label('header_text',__('Header Text'),['class'=>'col-form-label']) }}
                                                {{Form::text('header_text',null,array('class'=>'form-control','placeholder'=>__('Header Text')))}}

                                                @error('header_text')

                                                <span class="invalid-header_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-3 ">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">
                                                      {{Form::label('SITE_RTL',__('Enable RTL'),array('class'=>'col-form-label')) }}
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class=""  name="SITE_RTL" id="SITE_RTL" {{$SITE_RTL == 'on' ? 'checked="checked"' : '' }}>
                                                             <label class="custom-control-label mb-1" for="SITE_RTL"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <div class="col-sm-12 px-2">
                                            <div class="text-end">
                                                {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="" id="system-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('System Settings') }}</h5>
                            </div>
                            {{Form::model($settings,array('route'=>'system.settings','method'=>'post'))}}
                            <div class="card-body">
                                <div class="row company-setting">
                                    <div class="form-group col-md-4">
                                        {{Form::label('site_currency',__('Currency *'),['class'=>'col-form-label']) }}
                                        {{Form::text('site_currency',null,array('class'=>'form-control '))}}
                                        <small class="text-xs">
                                            {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                            <a href="https://stripe.com/docs/currencies" target="_blank">{{ __('You can find out how to do that here.') }}</a>
                                        </small>
                                        @error('site_currency')
                                        <br>
                                        <span class="text-xs text-danger invalid-site_currency" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('site_currency_symbol',__('Currency Symbol *'),['class'=>'col-form-label']) }}
                                        {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                        @error('site_currency_symbol')
                                        <span class="text-xs text-danger invalid-site_currency_symbol" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                        <label class="col-form-label">{{__('Currency Symbol Position')}}</label>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline m-1">
                                                <input class="form-check-input" type="radio" id="pre" value="pre" name="site_currency_symbol_position"  @if($settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                <label class="form-check-labe" for="pre">{{__('Pre')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline m-1">
                                                <input class="form-check-input" type="radio" id="post" value="post" name="site_currency_symbol_position"  @if($settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                <label class="form-check-labe" for="post">{{__('Post')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="site_date_format" class="col-form-label">{{__('Date Format')}}</label>
                                        <select type="text" name="site_date_format" class="form-control" id="site_date_format">
                                            <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                            <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                            <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                            <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="site_time_format" class="col-form-label">{{__('Time Format')}}</label>
                                        <select type="text" name="site_time_format" class="form-control" id="site_time_format">
                                            <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                            <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                            <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('invoice_prefix',__('Invoice Prefix'),['class'=>'col-form-label']) }}
                                        {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                        @error('invoice_prefix')
                                        <span class="text-xs text-danger invalid-invoice_prefix" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('contract_prefix',__('Contract Prefix'),['class'=>'col-form-label']) }}
                                        {{Form::text('contract_prefix',null,array('class'=>'form-control'))}}
                                        @error('contract_prefix')
                                        <span class="text-xs text-danger invalid-contract_prefix" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('bug_prefix',__('Bug Prefix'),['class'=>'col-form-label']) }}
                                        {{Form::text('bug_prefix',null,array('class'=>'form-control'))}}
                                        @error('bug_prefix')
                                        <span class="text-xs text-danger invalid-bug_prefix" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('estimation_prefix',__('Estimation Prefix'),['class'=>'col-form-label']) }}
                                        {{Form::text('estimation_prefix',null,array('class'=>'form-control'))}}
                                        @error('estimation_prefix')
                                        <span class="text-xs text-danger invalid-estimation_prefix" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="footer_title" class="col-form-label">{{__('Invoice/Estimation Title')}}  </label>
                                        <input type="text" name="footer_title" class="form-control" id="footer_title" value="{{$settings['footer_title']}}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="footer_note" class="col-form-label">{{__('Invoice/Estimation Note')}}  </label>
                                        <textarea name="footer_note" class="form-control" id="footer_note">{{$settings['footer_note']}}</textarea>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">{{__('Application URL')}}</label> <br>
                                        {{ Form::text('currency',URL::to('/'), ['class' => 'form-control', 'placeholder' => __('Enter Currency'),'disabled'=>'true']) }}
                                        <small>{{__("Application URL to log into the app.")}}</small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">{{__('Tracking Interval')}}</label> <br>
                                        {{ Form::number('interval_time',isset($settings['interval_time'])?$settings['interval_time']:'10', ['class' => 'form-control', 'placeholder' => __('Enter Tracking Interval'),'required'=>'required']) }}
                                        <small>{{__("Image Screenshot Take Interval time ( 1 = 1 min)")}}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer ">
                                <div class="col-sm-12 px-2">
                                    <div class="text-end">
                                        {{Form::submit(__('Save Changes'),array('class'=>'btn btn-print-invoice  btn-primary m-r-10 save_btn_signature'))}}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="" id="company-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Company Settings') }}</h5>
                            </div>
                            {{Form::model($settings,array('route'=>'company.settings','method'=>'post'))}}
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_name *',__('Company Name *'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_name',null,array('class'=>'form-control '))}}

                                        @error('company_name')

                                        <span class="invalid-company_name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_address',__('Address'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_address',null,array('class'=>'form-control '))}}
                                        @error('company_address')
                                        <span class="invalid-company_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_city',__('City'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_city',null,array('class'=>'form-control '))}}
                                        @error('company_city')
                                        <span class="invalid-company_city" role="alert">
                                             <strong class="text-danger">{{ $message }}</strong>
                                         </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_state',__('State'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_state',null,array('class'=>'form-control '))}}
                                        @error('company_state')
                                        <span class="invalid-company_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_zipcode',__('Zip/Post Code'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                        @error('company_zipcode')
                                        <span class="invalid-company_zipcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_country',__('Country'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_country',null,array('class'=>'form-control '))}}
                                        @error('company_country')
                                        <span class="invalid-company_country" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_telephone',__('Telephone'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                        @error('company_telephone')
                                        <span class="invalid-company_telephone" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_email',__('System Email *'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                        @error('company_email')
                                        <span class="invalid-company_email" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{Form::label('company_email_from_name',__('Email (From Name) *'),['class'=>'col-form-label']) }}
                                        {{Form::text('company_email_from_name',null,array('class'=>'form-control '))}}
                                        @error('company_email_from_name')
                                        <span class="invalid-company_email_from_name" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{__('Save Changes')}}
                                </button>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>

                    <div class="card" id="paymeny-settings">
                        <div class="card-header">
                            <h5>{{('Payment Settings')}}</h5>
                            <small class="text-dark font-weight-bold">{{__("These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.")}}</small>
                        </div>
                        <form id="setting-form" method="post" action="{{route('payment.settings')}}">
                            @csrf
                        <div class="card-body">

                                <div class="row">
                                    <div class="col-12">

                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label class="col-form-label">{{__('Currency')}} *</label>
                                                        <input type="text" name="currency" class="form-control" id="currency" value="{{(!isset($payment['currency']) || is_null($payment['currency'])) ? '' : $payment['currency']}}" required>
                                                        <small class="text-xs">
                                                            {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                            <a href="https://stripe.com/docs/currencies" target="_blank">{{ __('You can find out how to do that here.') }}</a>
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label for="currency_symbol" class="col-form-label">{{__('Currency Symbol')}} *</label>
                                                        <input type="text" name="currency_symbol" class="form-control" id="currency_symbol" value="{{(!isset($payment['currency_symbol']) || is_null($payment['currency_symbol'])) ? '' : $payment['currency_symbol']}}" required>
                                                    </div>

                                        </div>
                                        <div class="faq justify-content-center">
                                            <div class="row">


                                                <div class="col-sm-12 col-md-12 col-xxl-12">
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-2">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Stripe') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse0" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_stripe_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($payment['is_stripe_enabled']) && $payment['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="enable_whatsapp">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="stripe_key" class="col-form-label">{{__('Stripe Key')}}</label>
                                                                                <input class="form-control" placeholder="{{__('Stripe Key')}}" name="stripe_key" type="text" value="{{(!isset($payment['stripe_key']) || is_null($payment['stripe_key'])) ? '' : $payment['stripe_key']}}" id="stripe_key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="stripe_secret" class="col-form-label">{{__('Stripe Secret')}}</label>
                                                                                <input class="form-control " placeholder="{{ __('Stripe Secret') }}" name="stripe_secret" type="text" value="{{(!isset($payment['stripe_secret']) || is_null($payment['stripe_secret'])) ? '' : $payment['stripe_secret']}}" id="stripe_secret">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-2">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Paypal') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse1" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_paypal_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2" name="is_paypal_enabled" id="is_paypal_enabled" {{ isset($payment['is_paypal_enabled']) && $payment['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_paypal_enabled">{{ __('Enable') }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label class="form-check-labe text-dark">
                                                                                            <input type="radio" name="paypal_mode" value="sandbox" class="form-check-input" {{ !isset($store_settings['paypal_mode']) || $store_settings['paypal_mode'] == '' || $store_settings['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                            {{__('Sandbox')}}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label class="form-check-labe text-dark">
                                                                                            <input type="radio" name="paypal_mode" value="live" class="form-check-input" {{ isset($store_settings['paypal_mode']) && $store_settings['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                            {{__('Live')}}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label" for="paypal_client_id">{{ __('Client ID') }}</label>
                                                                                <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{(!isset($payment['paypal_client_id']) || is_null($payment['paypal_client_id'])) ? '' : $payment['paypal_client_id']}}" placeholder="{{ __('Client ID') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="col-form-label" for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{(!isset($payment['paypal_secret_key']) || is_null($payment['paypal_secret_key'])) ? '' : $payment['paypal_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-2">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Paystack') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse2" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_paystack_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2" name="is_paystack_enabled" id="is_paystack_enabled" {{ isset($payment['is_paystack_enabled']) && $payment['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_paystack_enabled">{{__('Enable')}}</label>

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_client_id" class="col-form-label">{{ __('Public Key')}}</label>
                                                                                <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{isset($payment['paystack_public_key']) ? $payment['paystack_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{isset($payment['paystack_secret_key']) ? $payment['paystack_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FLUTTERWAVE -->
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-5">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Flutterwave') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse4" class="accordion-collapse collapse"aria-labelledby="heading-2-5"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{ isset($payment['is_flutterwave_enabled'])  && $payment['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                                                <label  class="form-check-label" for="is_flutterwave_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_client_id" class="col-form-label">{{ __('Public Key')}}</label>
                                                                                <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{(!isset($payment['flutterwave_public_key']) || is_null($payment['flutterwave_public_key'])) ? '' : $payment['flutterwave_public_key']}}" placeholder="Public Key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{(!isset($payment['flutterwave_secret_key']) || is_null($payment['flutterwave_secret_key'])) ? '' : $payment['flutterwave_secret_key']}}" placeholder="Secret Key">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">

                                                        <!-- Razorpay -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-6">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Razorpay') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse5" class="accordion-collapse collapse"aria-labelledby="heading-2-6"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($payment['is_razorpay_enabled']) && $payment['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_razorpay_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paypal_client_id" class="col-form-label">{{  __('Public Key')}}</label>

                                                                                <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{(!isset($payment['razorpay_public_key']) || is_null($payment['razorpay_public_key'])) ? '' : $payment['razorpay_public_key']}}" placeholder="Public Key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paystack_secret_key" class="col-form-label"> {{  __('Secret Key')}}</label>
                                                                                <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{(!isset($payment['razorpay_secret_key']) || is_null($payment['razorpay_secret_key'])) ? '' : $payment['razorpay_secret_key']}}" placeholder="Secret Key">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- Paytm -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-7">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Paytm') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse6" class="accordion-collapse collapse"aria-labelledby="heading-2-7"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_paytm_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($payment['is_paytm_enabled']) && $payment['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_paytm_enabled">{{__('Enable')}}</label>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 pb-4">
                                                                            <label class="paypal-label col-form-label" for="paypal_mode">{{ __('Paytm Environment')}}</label> <br>
                                                                            <div class="d-flex">
                                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="paytm_mode" value="local" class="form-check-input" {{ !isset($payment['paytm_mode']) || $payment['paytm_mode'] == '' || $payment['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Local')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mr-2">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="paytm_mode" value="production" class="form-check-input" {{ isset($payment['paytm_mode']) && $payment['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Production')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_public_key" class="col-form-label">{{ __('Merchant ID')}}</label>
                                                                                <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{isset($payment['paytm_merchant_id'])? $payment['paytm_merchant_id']:''}}" placeholder="{{ __('Merchant ID') }}"/>
                                                                                @if ($errors->has('paytm_merchant_id'))
                                                                                    <span class="invalid-feedback d-block">
                                                                                        {{ $errors->first('paytm_merchant_id') }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_secret_key"  class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                                <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{ isset($payment['paytm_merchant_key']) ? $payment['paytm_merchant_key']:''}}" placeholder="{{ __('Merchant Key') }}"/>
                                                                                @if ($errors->has('paytm_merchant_key'))
                                                                                    <span class="invalid-feedback d-block">
                                                                                        {{ $errors->first('paytm_merchant_key') }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paytm_industry_type"  class="col-form-label">{{ __('Industry Type') }}</label>
                                                                                <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{isset($payment['paytm_industry_type']) ?$payment['paytm_industry_type']:''}}" placeholder="{{ __('Industry Type') }}"/>
                                                                                @if ($errors->has('paytm_industry_type'))
                                                                                    <span class="invalid-feedback d-block">
                                                                                        {{ $errors->first('paytm_industry_type') }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- Mercado Pago-->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-8">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="true" aria-controls="collapse7">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Mercado Pago') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse7" class="accordion-collapse collapse"aria-labelledby="heading-2-8"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_mercado_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_mercado_enabled" id="is_mercado_enabled" {{isset($payment['is_mercado_enabled']) &&  $payment['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_mercado_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 pb-4">
                                                                            <label class="coingate-label col-form-label" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                                            <div class="d-flex">
                                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="mercado_mode" value="sandbox" class="form-check-input" {{ isset($payment['mercado_mode']) && $payment['mercado_mode'] == '' || isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Sandbox')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mr-2">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="mercado_mode" value="live" class="form-check-input" {{ isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Live')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="mercado_access_token" class="col-form-label">{{ __('Access Token') }}</label>
                                                                                <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($payment['mercado_access_token']) ? $payment['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>
                                                                                @if ($errors->has('mercado_secret_key'))
                                                                                    <span class="invalid-feedback d-block">
                                                                                        {{ $errors->first('mercado_access_token') }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- Mollie -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-9">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Mollie') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse8" class="accordion-collapse collapse"aria-labelledby="heading-2-9"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_mollie_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_mollie_enabled" id="is_mollie_enabled" {{ isset($payment['is_mollie_enabled']) && $payment['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_mollie_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="mollie_api_key" class="col-form-label">{{ __('Mollie Api Key')}}</label>
                                                                                <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{(!isset($payment['mollie_api_key']) || is_null($payment['mollie_api_key'])) ? '' : $payment['mollie_api_key']}}" placeholder="Mollie Api Key">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="mollie_profile_id" class="col-form-label">{{ __('Mollie Profile Id')}}</label>
                                                                                <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{(!isset($payment['mollie_profile_id']) || is_null($payment['mollie_profile_id'])) ? '' : $payment['mollie_profile_id']}}" placeholder="Mollie Profile Id">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="mollie_partner_id" class="col-form-label">{{ __('Mollie Partner Id')}}</label>
                                                                                <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{(!isset($payment['mollie_partner_id']) || is_null($payment['mollie_partner_id'])) ? '' : $payment['mollie_partner_id']}}" placeholder="Mollie Partner Id">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- Skrill -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-10">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('Skrill') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse9" class="accordion-collapse collapse"aria-labelledby="heading-2-10"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_skrill_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_skrill_enabled" id="is_skrill_enabled" {{ isset($payment['is_skrill_enabled']) && $payment['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_skrill_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="mollie_api_key" class="col-form-label">{{ __('Skrill Email')}}</label>
                                                                                <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="{{ isset($payment['skrill_email'])?$payment['skrill_email']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                                                @if ($errors->has('skrill_email'))
                                                                                    <span class="invalid-feedback d-block">
                                                                                            {{ $errors->first('skrill_email') }}
                                                                                        </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- CoinGate -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-11">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('CoinGate') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse10" class="accordion-collapse collapse"aria-labelledby="heading-2-11"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_coingate_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_coingate_enabled" id="is_coingate_enabled" {{ isset($payment['is_coingate_enabled']) && $payment['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label  class="form-check-label" for="is_coingate_enabled">{{__('Enable')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 pb-4">
                                                                            <label class="col-form-label" for="coingate_mode">{{ __('CoinGate Mode')}}</label> <br>
                                                                            <div class="d-flex">
                                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="coingate_mode" value="sandbox" class="form-check-input" {{ !isset($payment['coingate_mode']) || $payment['coingate_mode'] == '' || $payment['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Sandbox')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mr-2">
                                                                                    <div class="border card p-3">
                                                                                        <div class="form-check">
                                                                                            <label class="form-check-labe text-dark">
                                                                                                <input type="radio" name="coingate_mode" value="live" class="form-check-input" {{ isset($payment['coingate_mode']) && $payment['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                                {{__('Live')}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="coingate_auth_token" class="col-form-label">{{ __('CoinGate Auth Token')}}</label>
                                                                                <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{(!isset($payment['coingate_auth_token']) || is_null($payment['coingate_auth_token'])) ? '' : $payment['coingate_auth_token']}}" placeholder="CoinGate Auth Token">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <!-- PaymentWall -->
                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header" id="heading-2-12">
                                                                <button class="accordion-button collapsed"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                                                    <span class="d-flex align-items-center">
                                                                        <i class="ti ti-credit-card text-primary"></i> {{ __('PaymentWall') }}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse11" class="accordion-collapse collapse"aria-labelledby="heading-2-12"data-bs-parent="#accordionExample" >
                                                                <div class="accordion-body">
                                                                    <div class="row">

                                                                        <div class="col-12 text-end">
                                                                            <div class="form-check form-switch form-switch-right mb-2">
                                                                                <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                                <input type="checkbox" class="form-check-input mx-2"  name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{ isset($payment['is_paymentwall_enabled']) && $payment['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="form-check-label" for="is_paymentwall_enabled">{{ __('Enable') }} </label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paymentwall_public_key" class="col-form-label">{{ __('Public Key')}}</label>
                                                                                <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{(!isset($payment['paymentwall_public_key']) || is_null($payment['paymentwall_public_key'])) ? '' : $payment['paymentwall_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="paymentwall_private_key" class="col-form-label">{{ __('Private Key') }}</label>
                                                                                <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control" value="{{(!isset($payment['paymentwall_private_key']) || is_null($payment['paymentwall_private_key'])) ? '' : $payment['paymentwall_private_key']}}" placeholder="{{ __('Private Key') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{__('Save Changes')}}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="" id="invoice-print-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Invoice Print Settings') }}</h5>
                            </div>
                            <form id="setting-form" method="post" action="{{route('template.setting')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">

                                                <div class="card-header card-body">
                                                    <div class="form-group">
                                                        <label for="address" class="col-form-label">{{__('Invoice Template')}}</label>
                                                        <select class="form-select" name="invoice_template">
                                                            @foreach(App\Models\Utility::templateData()['templates'] as $key => $template)
                                                                <option value="{{$key}}" {{(isset($settings['invoice_template']) && $settings['invoice_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{__('Color')}}</label>
                                                        <div class="row gutters-xs">
                                                             @foreach(App\Models\Utility::templateData()['colors'] as $key => $color)
                                                                <div class="col-auto">
                                                                    <label class="colorinput">
                                                                        <input name="invoice_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['invoice_color']) && $settings['invoice_color'] == $color) ? 'checked' : ''}}>
                                                                        <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{__('Invoice Logo')}}</label>
                                                        <div class="choose-files">
                                                            <label for="invoice_logo">
                                                                <div class=" bg-primary invoice_logo_update" style="width: 180px"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo"  onchange="document.getElementById('blah4').src = window.URL.createObjectURL(this.files[0])">
                                                                {{-- <a href="{{$invoice_logo.'/'.(issept($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" target="_blank">
                                                                    <img src="{{$invoice_logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" class="logo logo-sm" id="blah4" width="100%">
                                                                </a> --}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                        </div>
                                        <div class="col-sm-12 col-md-12 col-xxl-8">
                                            <div class="main_invoice">
                                                @if(isset($settings['invoice_template']) && isset($settings['invoice_color']))
                                                <iframe id="invoice_frame" class="w-100 h-1050" frameborder="0" src="{{route('invoice.preview',[$settings['invoice_template'],$settings['invoice_color']])}}"  width="100%" height="1080px"></iframe>
                                                @else
                                                <iframe id="invoice_frame" class="w-100 h-1050" frameborder="0" src="{{route('invoice.preview',['template1','fffff'])}}"  width="100%" height="1080px"></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                    {{__('Save Changes')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="" id="estimation-print-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Estimation Print Settings') }}</h5>
                            </div>
                             <form id="setting-form" method="post" action="{{route('template.setting')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">

                                            <div class="card-header card-body">
                                                <div class="form-group">
                                                    <label for="address" class="col-form-label">{{__('Estimation Template')}}</label>
                                                    <select class="form-select" name="estimation_template">
                                                        @foreach(App\Models\Utility::templateData()['templates'] as $key => $template)
                                                            <option value="{{$key}}" {{(isset($settings['estimation_template']) && $settings['estimation_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">{{__('Color')}}</label>
                                                     <div class="row gutters-xs">
                                                        @foreach(App\Models\Utility::templateData()['colors'] as $key => $color)
                                                            <div class="col-auto">
                                                                <label class="colorinput">
                                                                    <input name="estimation_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['estimation_color']) && $settings['estimation_color'] == $color) ? 'checked' : ''}}>
                                                                    <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">{{__('Estimation Logo')}}</label>
                                                    <div class="choose-files">
                                                        <label for="estimation_logo">
                                                            <div class=" bg-primary estimation_logo_update" style="width:180px"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" class="form-control file" name="estimation_logo" id="estimation_logo" accept=".jpeg,.jpg,.png" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                            {{-- <a href="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" target="_blank">
                                                            <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}" class="logo logo-sm" id="blah3" width="100%"></a> --}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-xxl-8">
                                            <div class="main_invoice">
                                                @if(isset($settings['estimation_template']) && isset($settings['estimation_color']))
                                                    <iframe id="estimation_frame" frameborder="0" class="w-100 h-1050" src="{{route('estimations.preview',[$settings['estimation_template'],$settings['estimation_color']])}}" height="1080px"></iframe>
                                                @else
                                                    <iframe id="estimation_frame" frameborder="0" class="w-100 h-1050" src="{{route('estimations.preview',['template1','fffff'])}}" height="1080px"></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                    {{__('Save Changes')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="email-notification-settings" class="card">
                        <div class="card-header">
                            <h5>{{ __('Email Notification Settings') }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                @foreach ($EmailTemplates as $EmailTemplate)

                                {{-- @dd($EmailTemplate->template); --}}
                                @can('on-off email template')
                                    <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                        <div class="list-group">
                                            <div class="list-group-item form-switch form-switch-right">
                                                <label class="form-label" style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                                                <input class="form-check-input email-template-checkbox" id="email_tempalte_{{$EmailTemplate->template->id}}" type="checkbox" @if($EmailTemplate->template->is_active == 1) checked="checked" @endif type="checkbox" value="{{$EmailTemplate->template->is_active}}"
                                                    data-url="{{route('status.email.language',[$EmailTemplate->template->id])}}" />
                                                <label class="form-check-label" for="email_tempalte_{{$EmailTemplate->template->id}}"></label>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->type == 'company')
                    <div class="" id="zoom-meeting-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Zoom Meeting') }}</h5>
                            </div>
                            {{ Form::open(['url' => route('setting.ZoomSettings'), 'enctype' => 'multipart/form-data']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{Form::label('Zoom API Key',__('Zoom API Key'),['class'=>'col-form-label']) }}
                                        {{Form::text('zoom_api_key',!empty($settings['zoom_api_key']) ? $settings['zoom_api_key'] : '' ,array('class'=>'form-control ' ,'placeholder'=>"Zoom API Key"))}}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{Form::label('Zoom Secret Key',__('Zoom Secret Key'),['class'=>'col-form-label']) }}
                                        {{Form::text('zoom_secret_key', !empty($settings['zoom_secret_key']) ? $settings['zoom_secret_key'] : '' ,array('class'=>'form-control', 'placeholder'=>'Zoom Secret Key'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                {{__('Save Changes')}}
                                </button>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>

                    <div class="" id="slack-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Slack Settings') }}</h5>
                            </div>
                            {{ Form::open(['route' => 'slack.setting','id'=>'slack-setting','method'=>'post' ,'class'=>'d-contents']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                        {{Form::label('Slack Webhook URL',__('Slack Webhook URL'),['class'=>'col-form-label']) }}
                                        {{ Form::text('slack_webhook', isset($settings['slack_webhook']) ?$settings['slack_webhook'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Slack Webhook URL') ]) }}
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                        {{Form::label('Module Setting',__('Module Settings'),['class'=>'col-form-label']) }}
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Lead create',__('New Lead'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('lead_notificaation', '1',isset($settings['lead_notificaation']) && $settings['lead_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'lead_notificaation'))}}
                                                    <label class="col-form-label" for="lead_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                              {{Form::label('Estimation create',__('New Estimation'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('estimation_notificaation', '1',isset($settings['estimation_notificaation']) && $settings['estimation_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'estimation_notificaation'))}}
                                                    <label class="col-form-label" for="estimation_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Project create',__('New Project'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('project_notificaation', '1',isset($settings['project_notificaation']) && $settings['project_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'project_notificaation'))}}
                                                    <label class="col-form-label" for="project_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Task create',__('New Task'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('task_notificaation', '1',isset($settings['task_notificaation']) && $settings['task_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'task_notificaation'))}}
                                                    <label class="col-form-label" for="task_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Task move',__('Task Moved'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('taskmove_notificaation', '1',isset($settings['taskmove_notificaation']) && $settings['taskmove_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'taskmove_notificaation'))}}
                                                    <label class="col-form-label" for="taskmove_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Task comment',__('New Task Comment'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('taskcom_notificaation', '1',isset($settings['taskcom_notificaation']) && $settings['taskcom_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'taskcom_notificaation'))}}
                                                    <label class="col-form-label" for="taskcom_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Milestone create',__('New Milestone'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('milestone_notificaation', '1',isset($settings['milestone_notificaation']) && $settings['milestone_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'milestone_notificaation'))}}
                                                    <label class="col-form-label" for="milestone_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Milestone status',__('Milestone Status Updated'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('milestonest_notificaation', '1',isset($settings['milestonest_notificaation']) && $settings['milestonest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'milestonest_notificaation'))}}
                                                    <label class="col-form-label" for="milestonest_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Invoice create',__('New Invoice'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('invoice_notificaation', '1',isset($settings['invoice_notificaation']) && $settings['invoice_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoice_notificaation'))}}
                                                    <label class="col-form-label" for="invoice_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Invoice status updated',__('Invoice Status Updated'),['class'=>'form-control-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('invoicest_notificaation', '1',isset($settings['invoicest_notificaation']) && $settings['invoicest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoicest_notificaation'))}}
                                                    <label class="col-form-label" for="invoicest_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                {{__('Save Changes')}}
                                </button>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>

                    <div class="" id="telegram-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Telegram Settings') }}</h5>
                            </div>
                            {{ Form::open(['route' => 'telegram.setting','id'=>'telegram-setting','method'=>'post' ,'class'=>'d-contents']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{Form::label('Telegram Access Token',__('Telegram Access Token'),['class'=>'col-form-label']) }}
                                        {{ Form::text('telegram_token', isset($settings['telegram_token']) ?$settings['telegram_token'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Telegram Access Token'), 'required' => 'required']) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{Form::label('Telegram ChatID',__('Telegram ChatID'),['class'=>'col-form-label']) }}
                                        {{ Form::text('telegram_chatid', isset($settings['telegram_chatid']) ?$settings['telegram_chatid'] :'', ['class' => 'form-control', 'placeholder' => __('Enter Telegram ChatID'), 'required' => 'required']) }}
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                        {{Form::label('Module Setting',__('Module Settings'),['class'=>'col-form-label']) }}
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Lead create',__('New Lead'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_lead_notificaation', '1',isset($settings['telegram_lead_notificaation']) && $settings['telegram_lead_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_lead_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_lead_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                              {{Form::label('Estimation create',__('New Estimation'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_estimation_notificaation', '1',isset($settings['telegram_estimation_notificaation']) && $settings['telegram_estimation_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_estimation_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_estimation_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Project create',__('New Project'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_project_notificaation', '1',isset($settings['telegram_project_notificaation']) && $settings['telegram_project_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_project_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_project_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Task create',__('New Task'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_task_notificaation', '1',isset($settings['telegram_task_notificaation']) && $settings['telegram_task_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_task_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_task_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Task move',__('Task Moved'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_taskmove_notificaation', '1',isset($settings['telegram_taskmove_notificaation']) && $settings['telegram_taskmove_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_taskmove_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_taskmove_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Task comment',__('New Task Comment'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_taskcom_notificaation', '1',isset($settings['telegram_taskcom_notificaation']) && $settings['telegram_taskcom_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_taskcom_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_taskcom_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Milestone create',__('New Milestone'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_milestone_notificaation', '1',isset($settings['telegram_milestone_notificaation']) && $settings['telegram_milestone_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_milestone_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_milestone_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                               {{Form::label('Milestone status',__('Milestone Status Updated'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_milestonest_notificaation', '1',isset($settings['telegram_milestonest_notificaation']) && $settings['telegram_milestonest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_milestonest_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_milestonest_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Invoice create',__('New Invoice'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_invoice_notificaation', '1',isset($settings['telegram_invoice_notificaation']) && $settings['telegram_invoice_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoice_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_invoice_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{Form::label('Invoice status updated',__('Invoice Status Updated'),['class'=>'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{Form::checkbox('telegram_invoicest_notificaation', '1',isset($settings['telegram_invoicest_notificaation']) && $settings['telegram_invoicest_notificaation'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'telegram_invoicest_notificaation'))}}
                                                    <label class="col-form-label" for="telegram_invoicest_notificaation"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                {{__('Save Changes')}}
                                </button>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')

    <script>
        $(document).on('click', 'input[name="theme_color"]', function () {
            var eleParent = $(this).attr('data-theme');
            $('#themefile').val(eleParent);
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });

        $(document).ready(function () {
            setTimeout(function (e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });

        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="'+color_val+'"]').prop('checked', true);
            $('#color_value').val(color_val);
        }
    </script>

    <script>

        $(document).ready(function() {
            if ($('.gdpr_fulltime').is(':checked')) {
                $('.fulltime').show();
            } else {
                $('.fulltime').hide();
            }
            $('#gdpr_cookie').on('change', function() {
                if ($('.gdpr_fulltime').is(':checked')) {
                    $('.fulltime').show();
                } else {
                    $('.fulltime').hide();
                }
            });
        });

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        $('.themes-color-change').on('click',function(){
            var color_val = $(this).data('value');
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);

        });
    </script>


@endpush
