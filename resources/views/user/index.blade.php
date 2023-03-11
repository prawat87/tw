@extends('layouts.admin')
@section('page-title')
    {{__('Manage User')}}
@endsection
@php
    $logo=\App\Models\Utility::get_file('productimages/');
@endphp
@section('action-button')
    @can('create user')
        @if(\Auth::user()->type != 'super admin')
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="{{__('Import Users')}}" data-url="{{route('user.file.import')}}" data-size="md" title="{{__('Import')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-file-import"></i>
            </a>
        @endif
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="{{__('Create User')}}" data-url="{{route('users.create')}}" data-size="md" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-plus"></i>
            </a>
    @endcan
@endsection
@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
@endsection
@section('content')
    <div class="row">
        @foreach($users as $user)
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-white text-center">
                    @if(Gate::check('edit user') || Gate::check('delete user'))
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <div class="badge p-2 px-3 rounded bg-primary">{{ucfirst($user->type)}}</div>
                                </h6>
                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    @if($user->is_active == 1)
                                        <button type="button" class="btn dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('edit user')
                                                <a href="#" class="dropdown-item" data-url="{{ route('users.edit',$user->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Edit User')}}"><i class="ti ti-edit"></i> <span>{{__('Edit')}}</span></a>
                                            @endcan
                                            @can('delete user')
                                                <a class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$user['id']}}"><i class="ti ti-trash"></i>
                                                <span>@if($user->delete_status == 1){{__('Delete')}} @else {{__('Restore')}}</span>@endif
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                            <a href="#" class="dropdown-item" data-size="md" data-url="{{route('user.reset',\Crypt::encrypt($user->id))}}" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-toggle="tooltip" data-original-title="{{__('Reset Password')}}">
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

                        <a href="{{(!empty($user->avatar))?  $logo.($user->avatar): $logo."avatar.png"}}" target="_blank">
                            <img src="{{(!empty($user->avatar))?  $logo.($user->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle card-avatar">
                        </a>

                        {{-- <a href="{{(!empty($user->avatar))? asset(Storage::url("avatar/".$user->avatar)): asset(Storage::url("avatar/avatar.png"))}}" target="_blank">
                        <img alt="user-image" class="img-fluid rounded-circle card-avatar" src="{{(!empty($user->avatar))? asset(Storage::url("avatar/".$user->avatar)): asset(Storage::url("avatar/avatar.png"))}}"></a> --}}
                        
                        <h4 class="text-primary mt-2">{{$user->name}}</h4>
                        <small class="text-primary">{{$user->email}}</small>
                        @if($user->delete_status == 0)
                            <h5 class="text-danger mb-0">{{__('Deleted')}}</h5>
                        @endif
                        @if(\Auth::user()->type=='super admin')
                            <div class="row align-items-center mt-2">
                                <div class="col-6 text-center">
                                    <span class="text-primary text-sm">{{!empty($user->getPlan)?$user->getPlan->name : ''}}</span>
                                </div>
                                <div class="col-6 text-center Id">
                                    <a href="#" class="btn btn-sm btn-light-primary text-sm" data-url="{{ route('plan.upgrade',$user->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}">{{__('Upgrade Plan')}}</a>
                                </div>
                                <div class="col-12 text-center py-3">
                                    <span class="text-dark text-sm">{{__('Plan Expired : ') }} {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date): __('Unlimited')}}</span>
                                </div>
                            </div>
                        @endif
                        <div class="row mt-3">
                            <div class="col-12 col-sm-12">
                                <div class="card mb-0">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            @if(\Auth::user()->type=='super admin')
                                               <div class="col-3">
                                                    <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('User')}}"><i class="fas fa-users card-icon-text-space"></i>{{$user->total_company_user($user->id)}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('Project')}}"><i class="fas fa-file-invoice-dollar card-icon-text-space"></i>{{$user->total_company_project($user->id)}}</p>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('Client')}}"><i class="fas fa-tasks card-icon-text-space"></i>{{$user->total_company_client($user->id)}}</p>
                                                </div>
                                            @else
                                                <div class="col-3">
                                                    <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('User Project')}}"><i class="fas fa-briefcase mr-2 card-icon-text-space"></i>{{$user->user_project()}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('Price')}}"><i class="fas fa-file-invoice-dollar mr-2 card-icon-text-space"></i>{{\Auth::user()->priceFormat($user->user_expense())}}</p>
                                                </div>
                                                <div class="col-3">
                                                   <p class="text-muted text-sm mb-0" data-bs-toggle="tooltip" title="{{__('Task')}}"><i class="fas fa-tasks mr-2 card-icon-text-space"></i>{{$user->user_assign_task()}}</p>
                                                </div>
                                            @endif
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
            <a href="#" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create User')}}" data-ajax-popup="true" data-size="md" data-title="Create User" data-url="{{route('users.create')}}">
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{__('New User')}}</h6>
                <p class="text-muted text-center">{{__('Click here to add New User')}}</p>
            </a>
        </div>
    </div>
@endsection



