
@extends('layouts.admin')
@section('page-title')
    {{ __('Email Templates')}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('custom/libs/summernote/summernote-bs4.js')}}"></script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Email Templates')}}</li>
    {{-- <li class="breadcrumb-item active" aria-current="page">{{$emailTemplate->name}}</li> --}}
@endsection


@section('action-button')
<div class="text-end">
    <div class="d-flex justify-content-end drp-languages">
        <ul class="list-unstyled mb-0 m-2">
            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                   href="#" role="button" aria-haspopup="false" aria-expanded="false"
                   id="dropdownLanguage">
                    {{-- <i class="ti ti-world nocolor"></i> --}}
                    <span
                        class="drp-text hide-mob text-primary">{{ Str::upper($currEmailTempLang->lang) }}</span>
                        {{-- @dd($EmailTemplates) --}}
                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                     aria-labelledby="dropdownLanguage">
       

                    @foreach ($languages as $lang)
                        <a href="{{ route('manage.email.language', [$emailTemplate->id, $lang]) }}"
                           class="dropdown-item {{ $currEmailTempLang->lang == $lang ? 'text-primary' : '' }}">{{ Str::upper($lang) }}</a>
                    @endforeach
                </div>
            </li>
        </ul>
        <ul class="list-unstyled mb-0 m-2">
            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                   href="#" role="button" aria-haspopup="false" aria-expanded="false"
                   id="dropdownLanguage">
                    <span
                        class="drp-text hide-mob text-primary">{{ __('Template: ') }}{{ $emailTemplate->name }}</span>
                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                    @foreach ($EmailTemplates as $EmailTemplate)
                        <a href="{{ route('manage.email.language', [$EmailTemplate->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang)]) }}"
                           class="dropdown-item {{$emailTemplate->name == $EmailTemplate->name ? 'text-primary' : '' }}">{{ $EmailTemplate->name }}
                        </a>
                    @endforeach
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection

@section('content')
    <div class="row">    
    <div class="col-12">
        <div class="card">
            
            <div class="card-body">
                
                <div class="language-wrap">
                    <div class="row ">
                        
                        <h6>Place Holders</h6>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-xs">
                                        @if($emailTemplate->name == 'Project Assigned')
                                        <div class="row">
                                            {{-- <h6 class="font-weight-bold">{{__('Project')}}</h6> --}}
                                            <p class="col-4">{{__('Project Name')}} : <span class="pull-right text-primary">{project_name}</span></p>
                                            <p class="col-4">{{__('Project Label')}} : <span class="pull-right text-primary">{project_label}</span></p>
                                            <p class="col-4">{{__('Project Status')}} : <span class="pull-right text-primary">{project_status}</span></p>
                                        </div>
                                        @endif
                                        @if($emailTemplate->name == 'Task Created' || $emailTemplate->name == 'Task Moved')
                                        <div class="row">
                                            {{-- <h6 class="font-weight-bold">{{__('Task')}}</h6> --}}
                                            <p class="col-4">{{__('Task Name')}} : <span class="pull-right text-primary">{task_name}</span></p>
                                            <p class="col-4">{{__('Task Priority')}} : <span class="pull-right text-primary">{task_priority}</span></p>
                                            <p class="col-4">{{__('Task Status')}} : <span class="pull-right text-primary">{task_status}</span></p>
                                            <p class="col-4">{{__('Task Old Stage')}} : <span class="pull-right text-primary">{task_old_stage}</span></p>
                                            <p class="col-4">{{__('Task New Stage')}} : <span class="pull-right text-primary">{task_new_stage}</span></p>
                                        </div>
                                        @endif
                                        @if($emailTemplate->name == 'Estimation Assigned')
                                            <div class="row">
                                                {{-- <h6 class="font-weight-bold">{{__('Estimation')}}</h6> --}}
                                                <p class="col-4">{{__('Estimation Id')}} : <span class="pull-right text-primary">{estimation_name}</span></p>
                                                <p class="col-4">{{__('Estimation Client')}} : <span class="pull-right text-primary">{estimation_client}</span></p>
                                                <p class="col-4">{{__('Estimation Status')}} : <span class="pull-right text-primary">{estimation_status}</span></p>
                                            </div>
                                        @endif

                                        @if($emailTemplate->name=='New Contract')
                                        <div class="row">
                                            {{-- <h6 class="font-weight-bold pb-3">{{__('Create Contract')}}</h6> --}}
                                            <p class="col-4">{{__('Contract Subject')}} : <span class="pull-right text-primary">{contract_subject}</span></p>
                                            <p class="col-4">{{__('Contract Client')}} : <span class="pull-right text-primary">{contract_client}</span></p>
                                            <p class="col-4">{{__('Contract Project')}} : <span class="pull-right text-primary">{contract_project}</span></p>
                                            <p class="col-4">{{__('Contract Start Date')}} : <span class="pull-right text-primary">{contract_start_date}</span></p>
                                            <p class="col-4">{{__('Contract End Date')}} : <span class="pull-right text-primary">{contract_end_date}</span></p>
                                        </div>
                                        @endif

                                        <div class="row">
                                            {{-- <h6 class="font-weight-bold">{{__('Other')}}</h6> --}}
                                            <p class="col-4">{{__('App Name')}} : <span class="pull-right text-primary">{app_name}</span></p>
                                            <p class="col-4">{{__('Company Name')}} : <span class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{__('Email')}} : <span class="pull-right text-primary">{email}</span></p>
                                            <p class="col-4">{{__('Password')}} : <span class="pull-right text-primary">{password}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 language-form-wrap">
                            {{Form::model($currEmailTempLang, array('route' => array('store.email.language',$currEmailTempLang->parent_id), 'method' => 'POST')) }}
                            <div class="row">
                                <div class="form-group col-6    `">
                                    {{Form::label('subject',__('Subject'),['class'=>'col-form-label text-dark'])}}
                                    {{Form::text('subject',null,array('class'=>'form-control ','required'=>'required'))}}
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('from',__('From'),['class'=>'col-form-label text-dark'])}}
                                    {{Form::text('from',null,array('class'=>'form-control ','required'=>'required'))}}
                                </div>
                                <div class="form-group col-12">
                                    {{Form::label('content',__('Email Message'),['class'=>'col-form-label text-dark'])}}
                                    {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote-simple','required'=>'required'))}}
                                </div>

                                
                                @can('edit email template lang')
                                    <div class="col-md-12 text-end">
                                        {{Form::hidden('lang',null)}}
                                        <input type="submit" value="{{__('Save Changes')}}" class="btn btn-primary">
                                    </div>
                                @endcan
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


