@extends('layouts.admin')
@section('page-title')
    {{__('Manage Lead Source')}}
@endsection

@section('action-button')
<div>
    <div class="row">
        @can('create lead source')
            <div class="col-auto">
                <a href="#" data-url="{{ route('leadsources.create') }}" data-ajax-popup="true" data-title="{{__('Create Lead Source')}}" class="btn btn-sm btn-primary btn-icon" data-size="md" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
            </div>       
        @endcan
    </div>
</div> 
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Lead Sources')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Source')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($leadsources as $leadsource)
                                <tr>
                                    <td>
                                        {{$leadsource->name}}
                                    </td>
                                    <td>
                                            <span>
                                            @can('edit lead source')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('leadsources.edit',$leadsource->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead Source')}}" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                            </div> 
                                                @endcan
                                                @can('delete lead source')
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$leadsource->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                </div>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leadsources.destroy', $leadsource->id],'id'=>'delete-form-'.$leadsource->id]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </span>
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
