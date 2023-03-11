@extends('layouts.admin')
@section('page-title')
    {{__('Manage Product Unit')}}
@endsection

@section('action-button')
    <div>
        @can('create product unit')
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('productunits.create') }}" data-ajax-popup="true" data-title="{{__('Create Product Unit')}}" title="{{__('Create')}}">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection
@section('breadcrumb')
        <li class="breadcrumb-item active" aria-current="page">{{__('Product Unit')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Unit')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productunits as $productunit)
                                <tr data-id="{{$productunit->id}}">
                                    <td>{{$productunit->name}}
                                    </td>
                                    <td class="Action">
                                        <span>
                                        @can('edit product unit')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('productunits.edit',$productunit->id) }}" data-ajax-popup="true" data-title="{{__('Edit Product Unit')}}" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                            </div>
                                            @endcan
                                            @can('delete product unit')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$productunit->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                            </div>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['productunits.destroy', $productunit->id],'id'=>'delete-form-'.$productunit->id]) !!}
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
