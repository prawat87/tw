@extends('layouts.admin')
@section('page-title')
    {{__('Manage Label')}}
@endsection

@section('action-button')
<div>
    <div class="row">
        @can('create label')
            <div class="col-auto">
                <a href="#" data-url="{{ route('labels.create') }}" data-ajax-popup="true" data-title="{{__('Create New Label')}}" class="btn btn-sm btn-primary btn-icon" data-size="md" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
            </div>       
        @endcan
    </div>
</div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Label')}}</li>
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
                                <th>{{__('Label')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($labels as $k => $label)
                                <tr data-id="{{$label->id}}">
                                    <td>
                                        <div class="custom-control custom-radio mb-3 {{$label->color}}">
                                            <label class="custom-control-label ">{{$label->name}}</label>
                                        </div>
                                    </td>
                                    <td class="Action">
                                        <span>
                                        @can('edit label')
                                        <div class="action-btn bg-info ms-2">
                                                <a href="#" data-url="{{ route('labels.edit',$label->id) }}" data-ajax-popup="true" data-title="{{__('Edit Label')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-edit text-white "></i></a>
                                        </div>        
                                            @endcan
                                            @can('delete label')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$label->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['labels.destroy', $label->id],'id'=>'delete-form-'.$label->id]) !!}
                                                {!! Form::close() !!}
                                            </div>    
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
