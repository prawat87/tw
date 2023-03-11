@extends('layouts.admin')
@section('page-title')
    {{__('Manage Client')}}
@endsection
@php
    $logo = \App\Models\Utility::get_file('avatars/');
@endphp
@section('action-button')
    <a href="#"  class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="{{__('Create User')}}" data-url="{{ route('client.file.import') }}" data-size="md" title="{{__('Import')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-import"></i></a>
    <a href="{{ route('client.export') }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-export"></i></a>
    @can('create client')
        <a href="#" data-url="{{ route('clients.create') }}" data-ajax-popup="true" data-title="{{__('Create New Client')}}" data-size="md" class="btn btn-sm btn-primary btn-icon" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Client')}}</li>
@endsection
@section('content')
    <div class="row">
        @foreach($clients as $client)
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-white text-center">
                    @if(Gate::check('edit client') || Gate::check('delete client'))
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <div class="badge p-2 px-3 rounded bg-primary">{{ucfirst($client->type)}}</div>
                                </h6>
                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    @if($client->is_active == 1)
                                        <button type="button" class="btn dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('edit client')
                                                <a href="#" class="dropdown-item" data-url="{{ route('clients.edit',$client->id) }}"  data-ajax-popup="true" data-size="md" data-title="{{__('Edit Client')}}"><i class="ti ti-edit"></i> <span>{{__('Edit')}}</span></a>
                                            @endcan
                                            @can('delete client')
                                                <a class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$client['id']}}"><i class="ti ti-trash"></i>
                                                <span>@if($client->delete_status == 1){{__('Delete')}} @else {{__('Restore')}}</span>@endif
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                            <a href="#" class="dropdown-item" data-size="md" data-url="{{route('user.reset',\Crypt::encrypt($client->id))}}" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-toggle="tooltip" data-original-title="{{__('Reset Password')}}">
                                            <i class="ti ti-key"></i> <span>{{__('Reset Password')}}</span>
                                            </a>
                                        </div>
                                    @else
                                        <button type="button" class="btn text-muted">
                                            <i class="ti ti-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        
                        {{-- <a href="{{(!empty($client->avatar))? asset(Storage::url("avatar/".$client->avatar)): asset(Storage::url("avatar/avatar.png"))}}" target="_blank">
                        <img alt="user-image" class="img-fluid rounded-circle card-avatar" src="{{(!empty($client->avatar))? asset(Storage::url("avatar/".$client->avatar)): asset(Storage::url("avatar/avatar.png"))}}"></a> --}}

                        <a href="{{(!empty($client->avatar))?  \App\Models\Utility::get_file($client->avatar): $logo."/avatar.png"}}" target="_blank">
                            <img src="{{(!empty($client->avatar))?  \App\Models\Utility::get_file($client->avatar): $logo."/avatar.png"}}" class="img-fluid rounded-circle card-avatar">
                        </a>

                        <h4 class="text-primary mt-2">{{$client->name}}</h4>
                        <small class="text-primary">{{$client->email}}</small>
                        @if($client->delete_status == 0)
                            <h5 class="text-danger mb-0">{{__('Deleted')}}</h5>
                        @endif
                        
                        <div class="row mt-3">
                            <div class="col-12 col-sm-12">
                                <div class="card mb-0">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <p class="text-muted text-sm mb-0"><i class="fas fa-briefcase mr-2 card-icon-text-space"></i>{{$client->client_project()}}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted text-sm mb-0"><i class="fas fa-file-invoice-dollar mr-2 card-icon-text-space"></i>{{\Auth::user()->priceFormat($client ->client_project_budget())}}</p>
                                            </div>
                                            <div class="col-3">
                                               <p class="text-muted text-sm mb-0"><i class="fas fa-tasks mr-2 card-icon-text-space"></i>{{$client->client_lead()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a href="#" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create User')}}" data-ajax-popup="true" data-size="md" data-title="Create User" data-url="{{route('clients.create')}}">
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{__('New Client')}}</h6>
                <p class="text-muted text-center">{{__('Click here to add New User')}}</p>
            </a>
        </div>
    </div>
@endsection
