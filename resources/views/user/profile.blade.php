@extends('layouts.admin')
@section('page-title')
    {{ __('Edit Profile') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
@endsection
@php
    $logo = \App\Models\Utility::get_file('productimages/');
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#Personal_Info" id="Personal_Info_tab"
                                    class="list-group-item list-group-item-action border-0">{{ __('Personal Information') }} <div
                                        class="float-end"><i class="ti ti-chevron-right border-0"></i></div></a>

                                <a href="#Change_Password" id="Change_Password_tab"
                                    class="list-group-item list-group-item-action">{{__('Change Password')}}<div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                        <div class="active" id="Personal_Info">
                            {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'POST', 'enctype' => "multipart/form-data"))}}
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Personal Information') }}</h5>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class=" setting-card">
                                                <div class="row">
                                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                                        <div class="card-body text-center">
                                                            <div class="logo-content">
                                                                <a href="{{(!empty($userDetail->avatar))? ($logo.$userDetail->avatar): $logo."/avatar.png"}}" target="_blank">
                                                                    <img src="{{(!empty($userDetail->avatar))? ($logo.$userDetail->avatar): $logo."/avatar.png"}}" class="rounded-circle-avatar" width="100" id="profile">
                                                                </a>
                                                            </div>
                                                            <div class="choose-files mt-4">
                                                                <label for="profile_pic">
                                                                    <div class="bg-primary profile_update" style="max-width: 100% !important;"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" class="file" name="profile" type="file" accept="image/*" id="profile_pic" onchange="document.getElementById('profile').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-sm-6 col-md-6">
                                                        <div class="card-body">
                                                            
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    {{Form::label('name',__('Name'),array('class'=>'col-form-label')) }}
                                                                    <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="name" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}" autocomplete="name">
                                                                    @error('name')
                                                                    <span class="invalid-feedback text-danger text-xs" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    {{Form::label('email',__('Email'),array('class'=>'col-form-label')) }}
                                                                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $userDetail->email }}" autocomplete="email">
                                                                    @error('email')
                                                                    <span class="invalid-feedback text-danger text-xs" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="col-sm-12 ">
                                                <div class="text-end">
                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
                        </div>
                        <div class="" id="Change_Password">
                            {{Form::model($userDetail,array('route' => array('update.password',$userDetail->id), 'method' => 'POST'))}}
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Change Password') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::label('current_password',__('Current Password'),array('class'=>'col-form-label')) }}
                                                        <input class="form-control @error('current_password') is-invalid @enderror" name="current_password" type="password" id="current_password" autocomplete="current_password" placeholder="{{ __('Enter Current Password') }}">
                                                        @error('current_password')
                                                        <span class="invalid-feedback text-danger text-xs" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::label('new_password',__('New Password'),array('class'=>'col-form-label')) }}
                                                        <input class="form-control @error('new_password') is-invalid @enderror" name="new_password" type="password" autocomplete="new_password" id="new_password" placeholder="{{ __('Enter New Password') }}">
                                                        @error('new_password')
                                                        <span class="invalid-feedback text-danger text-xs" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    {{Form::label('confirm_password',__('Re-type New Password'),array('class'=>'col-form-label')) }}
                                                    <input class="form-control @error('confirm_password') is-invalid @enderror" name="confirm_password" type="password" autocomplete="confirm_password" id="confirm_password" placeholder="{{ __('Confirm New Password') }}">
                                                    @error('confirm_password')
                                                    <span class="invalid-feedback text-danger text-xs" role="alert"><strong class="text-danger">{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="col-sm-12 ">
                                                <div class="text-end">
                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.list-group-item', function() {
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            setTimeout(() => {
                $(this).addClass('active').removeClass('text-primary');
            }, 10);
        });

        var type = window.location.hash.substr(1);
        $('.list-group-item').removeClass('active');
        $('.list-group-item').removeClass('text-primary');
        if (type != '') {
            $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
        } else {
            $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
        }




        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
