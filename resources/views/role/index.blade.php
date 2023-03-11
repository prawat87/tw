@extends('layouts.admin')

@section('page-title')
    {{__('Manage Roles')}}
@endsection

@section('action-button')
    <div>
        @can('create role')
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Role')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Role')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <th>{{__('Role')}} </th>
                                <th>{{__('Permissions')}} </th>
                                <th width="200px">{{__('Action')}} </th>
                            </thead>
                            <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="Role">{{ $role->name }}</td>
                                    <td class="Permission">
                                        @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                            <span class="badge rounded px-3 bg-primary">{{$role->permissions()->pluck('name')[$j]}}</span>
                                        @endfor
                                    </td>
                                    <td>
                                        @can('edit role')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " data-url="{{ route('roles.edit',$role->id) }}" data-size="lg" data-ajax-popup="true"  data-title="{{__('Update Role')}}" title="{{__('Update')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit text-white"></i></span></a>
                                            </div>
                                        @endcan
                                        @can('delete role')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-confirm-yes="delete-form-{{$role->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <span class="text-white"><i class="ti ti-trash"></i></span>
                                                </a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script-page')


@endpush