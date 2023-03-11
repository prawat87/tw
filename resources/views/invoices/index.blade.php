@extends('layouts.admin')

@section('page-title')
    {{__('Manage Invoice')}}
@endsection

@section('action-button')
    @can('create invoice')
        {{-- <div class="col-auto pe-0"> --}}
            <a href="{{ route('invoice.export') }}"  class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-export"></i></a>
        
            <a href="#" data-url="{{ route('invoices.create') }}" data-ajax-popup="true" data-title="{{__('Create Invoice')}}" class="btn btn-sm btn-primary btn-icon" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-plus"></i>
            </a>
        {{-- </div> --}}
    @endcan
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{__('Sales')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Invoices')}}</li>
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
                                <th> {{__('Invoice')}}</th>
                                <th> {{__('Project')}}</th>
                                <th> {{__('Issue Date')}}</th>
                                <th> {{__('Due Date')}}</th>
                                <th> {{__('Value')}}</th>
                                <th> {{__('Status')}}</th>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('invoices.show',$invoice->id) }}" class="btn btn-outline-primary">{{ App\Models\Utility::invoiceNumberFormat($invoice->id) }}</a>
                                    </td>
                                    <td>{{ (isset($invoice->project) && !empty($invoice->project)) ? $invoice->project->name : '-' }}</td>
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                    <td>{{ Auth::user()->priceFormat($invoice->getTotal()) }}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge p-2 px-3 rounded bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge p-2 px-3 rounded bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge p-2 px-3 rounded bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge p-2 px-3 rounded bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge p-2 px-3 rounded bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit invoice') || Gate::check('delete invoice'))
                                        <td class="Action">
                                            <span>
                                                @can('show invoice')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('invoices.show',$invoice->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-eye"></i></span></a>
                                                    </div>
                                                @endcan
                                                @can('edit invoice')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" data-url="{{ route('invoices.edit',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete invoice')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$invoice->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                            <span class="text-white"><i class="ti ti-trash"></i></span>
                                                        </a>
                                                    </div>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoices.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </span>
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
