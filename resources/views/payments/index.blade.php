@extends('layouts.admin')
@section('page-title')
    {{__('Manage Payment Method')}}
@endsection
@section('action-button')
<div>
    <div class="row">
        @can('create payment')
            <div class="col-auto">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('payments.create') }}" data-ajax-popup="true" data-title="{{__('Create Payment Method')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}"><i class="ti ti-plus"></i></a>
            </div>
        @endcan
    </div>
</div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Payment Method')}}</li>
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
                                    <th>{{__('Payment Method')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($payments as $payment)
                                <tr data-id="{{$payment->id}}">
                                    <td>
                                        {{$payment->name}}
                                    </td>
                                    <td>
                                        
                                        @can('edit payment')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('payments.edit',$payment->id) }}" data-ajax-popup="true"data-title="{{__('Edit Payment Method')}}"data-bs-original-title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete payment')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$payment->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <span class="text-white"><i class="ti ti-trash"></i></span>
                                                </a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['payments.destroy', $payment->id],'id'=>'delete-form-'.$payment->id]) !!}
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
