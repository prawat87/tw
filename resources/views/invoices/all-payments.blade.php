@extends('layouts.admin')
@section('page-title')
    {{__('Manage Payment')}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{__('Sales')}}</li>

    <li class="breadcrumb-item active" aria-current="page">{{__('Payment')}}</li>
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
                                <th> {{__('Transaction ID')}}</th>
                                <th> {{__('Invoice')}}</th>
                                <th> {{__('Payment Date')}}</th>
                                <th> {{__('Payment Method')}}</th>
                                <th> {{__('Payment Type')}}</th>
                                <th> {{__('Note')}}</th>
                                <th> {{__('Amount')}}</th>
                                @if(Gate::check('show invoice') || \Auth::user()->type=='client')
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{sprintf("%05d", $payment->transaction_id)}}</td>
                                    <td class="Id">
                                        <a href="{{ route('invoices.show',$payment->invoice->id) }}" class="btn btn-outline-primary">{{ App\Models\Utility::invoiceNumberFormat($payment->id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($payment->date) }}</td>
                                    <td>{{(!empty($payment->payment)?$payment->payment->name:'-')}}</td>
                                    <td>{{$payment->payment_type}}</td>
                                    <td>{{$payment->notes}}</td>
                                    <td>{{Auth::user()->priceFormat($payment->amount)}}</td>
                                    @if(Gate::check('show invoice') || \Auth::user()->type=='client')
                                        <td>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('invoices.show',$payment->invoice->id) }}"  class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-original-title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
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
