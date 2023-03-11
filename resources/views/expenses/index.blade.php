@extends('layouts.admin')
@section('page-title')
    {{__('Manage Expense')}}
@endsection

@section('action-button')
    @can('create expense')
        <a href="{{ route('expense.export') }}"  class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-export"></i></a>
        <a href="#" data-url="{{ route('expenses.create') }}" data-ajax-popup="true" data-title="{{__('Create Expense')}}" class="btn btn-sm btn-primary btn-icon" data-size="md" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
    @endcan
@endsection
@php
$attachments=\App\Models\Utility::get_file('/');

@endphp

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Sales')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Expense')}}</li>
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
                                <th>{{__('Category')}}</th>
                                <th> {{__('Description')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Project')}}</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Attachment')}}</th>
                                @if(Gate::check('edit expense') || Gate::check('delete expense'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{  (!empty($expense->category)?$expense->category->name:'')}}</td>
                                    <td>{{ (!empty($expense->description) ? $expense->description : '-') }}</td>
                                    <td>{{ Auth::user()->priceFormat($expense->amount) }} </td>
                                    <td>{{ Auth::user()->dateFormat($expense->date) }}</td>
                                    <td>{{ (!empty($expense->projects)?$expense->projects->name:'')  }}</td>
                                    <td>{{ (!empty($expense->user)?$expense->user->name:'') }}</td>
                                    <td class="text-right">
                                        @if($expense->attachment)
                                            <a href="{{ $attachments.$expense->attachment}}" class="action-btn bg-warning ms-2  btn btn-sm d-inline-flex align-items-center" title="{{__('Download')}}" data-bs-toggle="tooltip" data-bs-placement="top" download=""><span class="text-white"><i class="ti ti-download"></i></span></a>

                                            <a href="{{ $attachments.$expense->attachment }}" target="_blank"   class="action-btn bg-secondary ms-2  btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Preview')}}" data-bs-placement="top"><span class="text-white"><i class="ti ti-crosshair"></i></span></a>

                                        @else
                                            -
                                        @endif
                                    </td>
                                    @if(Gate::check('edit expense') || Gate::check('delete expense'))
                                        <td>
                                            @can('edit expense')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('expenses.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('delete expense')
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$expense->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                </div>

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['expenses.destroy', $expense->id],'id'=>'delete-form-'.$expense->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </td>
                                    @endif
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
