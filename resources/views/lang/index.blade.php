@extends('layouts.admin')
@section('page-title')
    {{__('Manage Language')}}
@endsection

@section('action-button')
<div>
    <div class="row">
        @can('create language')
            <div class="col-auto">
                <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Create Language')}}" data-url="{{route('create.language')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i> </a>
            </div>
            @if($currantLang != \App\Models\Utility::settings()['default_language'])
                <div class="col-auto">
                    <a href="#" class="btn-submit btn btn-sm btn-danger btn-icon px-1 py-1 bs-pass-para" data-bs-toggle="tooltip" 
                        data-original-title="{{__('Delete This Language')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" 
                        data-confirm-yes="delete-form-{{$currantLang}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash text-white"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['destroy.language', $currantLang],'id'=>'delete-form-'.$currantLang]) !!}
                    {!! Form::close() !!}
                </div>
            @endif
        @endcan
    </div>
</div>    
        
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Manage Language')}}</li>
@endsection
@section('content')
    <!-- <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" role="tablist">
                        @foreach($languages as $lang)
                            <li class="nav-item">
                                <a href="{{route('manage.language',[$lang])}}" class="nav-link {{($currantLang == $lang)?'active':''}} text-sm font-weight-bold">
                                    {{Str::upper($lang)}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li>
                            <a class="active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Labels')}}</a>
                        </li>
                        <li>
                            <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Messages')}}</a>
                        </li>
                    </ul>
                    @can('create language')
                        <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                            @csrf
                            @endcan
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row">
                                        @foreach($arrLabel as $label => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label text-dark">{{$label}} </label>
                                                    <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="row">
                                        @foreach($arrMessage as $fileName => $fileValue)
                                            <div class="col-lg-12">
                                                <h4>{{ucfirst($fileName)}}</h4>
                                            </div>
                                            @foreach($fileValue as $label => $value)
                                                @if(is_array($value))
                                                    @foreach($value as $label2 => $value2)
                                                        @if(is_array($value2))
                                                            @foreach($value2 as $label3 => $value3)
                                                                @if(is_array($value3))
                                                                    @foreach($value3 as $label4 => $value4)
                                                                        @if(is_array($value4))
                                                                            @foreach($value4 as $label5 => $value5)
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-control-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label class="form-control-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-control-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-control-label text-dark">{{$fileName}}.{{$label}}</label>
                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @can('create language')
                                <div class="form-group col-12 text-right">
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn-create badge-blue">
                                </div>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($languages as $lang)
                            <a href="{{route('manage.language',[$lang])}}" class="nav-link text-sm font-weight-bold @if($currantLang == $lang) active @endif">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">{{Str::upper($lang)}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @can('create language')
        <div class="col-lg-9">
                    <div class="p-3 card">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#labels" type="button">{{ __('Labels')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#messages" type="button">{{ __('Messages')}}</button>
                </li>

            </ul>
        </div>
        <div class="card">
            <div class="card-body p-3">
                <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane active" id="labels">
                            <div class="row">
                                @foreach($arrLabel as $label => $value)
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label text-dark">{{$label}}</label>
                                            <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane" id="messages">
                            @foreach($arrMessage as $fileName => $fileValue)
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h6>{{ucfirst($fileName)}}</h6>
                                    </div>
                                    @foreach($fileValue as $label => $value)
                                        @if(is_array($value))
                                            @foreach($value as $label2 => $value2)
                                                @if(is_array($value2))
                                                    @foreach($value2 as $label3 => $value3)
                                                        @if(is_array($value3))
                                                            @foreach($value3 as $label4 => $value4)
                                                                @if(is_array($value4))
                                                                    @foreach($value4 as $label5 => $value5)
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label text-dark">{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="col-lg-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label text-dark">{{$fileName}}.{{$label}}</label>
                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-end">
                        <input type="submit" value="{{__('Save Changes')}}" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        </div>
        @endcan
    </div>
@endsection

