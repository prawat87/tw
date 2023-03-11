@extends('layouts.admin')

@section('page-title')
    {{__('Manage Orders')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Orders')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Coupon')}}</th>
                                <th>{{__('Invoice')}}</th>
                            </tr>
                            </thead>
                            
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->user_name}}</td>
                                    <td>{{$order->plan_name}}</td>
                                    <td>${{number_format($order->price)}}</td>
                                    <td>
                                        @if($order->payment_status == 'succeeded')
                                            <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                                        @else
                                            <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                                        @endif
                                    </td>
                                    <td>{{$order->payment_type}}</td>
                                    <td>{{$order->created_at->format('d M Y')}}</td>
                                    <td>{{!empty($order->use_coupon)?$order->use_coupon->coupon_detail->name:''}}</td>
                                    {{-- <td class="Id">
                                        @if(empty($order->receipt))
                                            <p>{{__('Manually plan upgraded by super admin')}}</p>
                                        @elseif($order->receipt =='free coupon')
                                            <p>{{__('Used 100 % discount coupon code.')}}</p>
                                        @else
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{$order->payment_type}}" target="_blank"  class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Invoice')}}" data-bs-toggle="tooltip" data-bs-title="{{__('Invoice')}}" data-bs-placement="top"><span class="text-white"><i class="ti ti-file-invoice"></i></span></a>
                                        </div>
                                        @endif
                                    </td> --}}

                                    <td class="Id">
                                        @if (!empty($order->receipt))
                                        <a href="{{$order->receipt}}" class="btn  btn-outline-primary" target="_blank"><i class="fas fa-file-invoice"></i> {{__('Invoice')}}</a>
                                        @endif
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
