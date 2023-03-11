

@extends('layouts.admin')
@section('page-title')
    {{ __('Estimation Detail') }}
@endsection
@push('script-page')
 <script>
      $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '{{__('Link Copy on Clipboard')}}', 'success')
        });

</script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('estimations.index')}}">{{__('Estimation')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Details')}}</li>
@endsection

@section('action-button')
    <div>
        <div style="margin-right: 2px;">
            @if(\Auth::user()->type == 'company') 
                {{-- <div class="col-auto pe-0"> --}}
                    <a href="#" class="btn btn-sm btn-primary btn-icon cp_link" data-link="{{route('pay.estimation',\Illuminate\Support\Facades\Crypt::encrypt($estimation->id))}}" data-toggle="tooltip" data-original-title="{{__('Click to copy invoice link')}}" title="{{__('Copy')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-copy"></i>
                    </a>
                {{-- </div> --}}
            @endif        
            @can('edit estimation')
                {{-- <div class="col-auto pe-0"> --}}
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{URL::to('estimations/'.$estimation->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Estimation')}}" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-edit"></i>
                    </a>
                {{-- </div> --}}
            @endcan
            {{-- <div class="col-auto pe-0"> --}}
                <a href="{{ route('get.estimation',$estimation->id) }}"  class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Print Estimation')}}" target="_blanks"><i class="ti ti-printer text-white"></i></a>
            {{-- </div> --}}
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card" id="printTable">
            <div class="card-header">
                <h5 class="">{{  App\Models\Utility::estimateNumberFormat($estimation->estimation_id) }}</h5>   
            </div>
            <div class="card-body" style="margin-top: -30px;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="invoice-contact">
                            <div class="invoice-box row">
                                <div class="col-sm-12">
                                    <h6>{{ __('From') }}:</h6>
                                    @if ($settings['company_name'])
                                        <h6 class="m-0">{{$settings['company_name']}}</h6>
                                    @endif

                                    @if ($settings['company_address'])
                                        {{$settings['company_address']}} ,
                                        <br>
                                    @endif

                                    @if ($settings['company_city'])
                                        {{ $settings['company_city'] }},
                                    @endif
                                    @if ($settings['company_state'])
                                        {{ $settings['company_state'] }},
                                    @endif

                                    @if ($settings['company_zipcode'])
                                        -{{ $settings['company_zipcode'] }},<br>
                                    @endif
                                    @if ($settings['company_country'])
                                        {{ $settings['company_country'] }},<br>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-3 invoice-client-info">
                        <div class="invoice-contact">
                            <div class="invoice-box row">
                                {{-- <di;v class="col-sm-12"> --}}
                                    <h5>{{ __('To') }}:</h5>
                                    @if ($client)
                                    <p>{{ $client->name }}<br>
                                        {{$client->email }}<br>
                                    </p>
                                    @endif
                                {{-- </di;v> --}}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3  invoice-client-info">
                        <div class="invoice-contact">
                        
                            <div class="col-sm-12">
                                <h5>{{__('Description')}}</h5>
                                <table class="">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('Issue Date') }}:</th>
                                            <td>{{ Auth::user()->dateFormat($estimation->issue_date) }}</td>
                                        </tr>
                                        
                                        <tr>  
                                            <div>
                                            <th>{{__('Status')}} :</th>
                                            @if($estimation->status == 0)
                                            <td>      
                                                <span class="badge rounded p-2 px-3 bg-primary">{{ __(\App\Models\Estimation::$statues[$estimation->status]) }}</span>
                                            </td>
                                            @elseif($estimation->status == 1)
                                            <td>
                                                <span class="badge rounded p-2 px-3 bg-danger">{{ __(\App\Models\Estimation::$statues[$estimation->status]) }}</span>
                                            </td>
                                            @elseif($estimation->status == 2)
                                            <td>
                                                <span class="badge rounded p-2 px-3 bg-warning">{{ __(\App\Models\Estimation::$statues[$estimation->status]) }}</span>
                                            </td>
                                            @elseif($estimation->status == 3)
                                            <td>
                                                <span class="badge rounded p-2 px-3 bg-success">{{ __(\App\Models\Estimation::$statues[$estimation->status]) }}</span>
                                            </td>
                                            @elseif($estimation->status == 4)
                                            <td>
                                                <span class="badge rounded p-2 px-3 bg-info">{{ __(\App\Models\Estimation::$statues[$estimation->status]) }}</span>
                                            </td>
                                            @endif
                                            </div>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-md-2  qr_code">
                        <div class="text-end" style="margin: 26px 0px 0px 0px;">
                            {!! DNS2D::getBarcodeHTML(route('pay.estimation',\Illuminate\Support\Facades\Crypt::encrypt($estimation->estimation_id)), "QRCODE",2,2) !!}
                        </div>
                    </div>
                </div>

                    <div class="col-md-12 text-end">
                        <a href="#" data-url="{{ route('estimations.products.add',$estimation->id) }}" data-ajax-popup="true" data-title="{{__('Add Item')}}" class="btn btn-sm btn-primary btn-icon" title="{{__('Add Item')}}" data-size="md" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
                    </div>
                </div>
                <div class="row" style="padding: 25px;">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th >{{__('#')}}</th>
                                        <th >{{__('Item')}}</th>
                                        <th >{{__('Price')}}</th>
                                        <th >{{__('Quantity')}}</th>
                                        <th  class="text-end">{{__('Totals')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                     @foreach($estimation->getProducts as $product)
                                        <tr>
                                            <td class="invoice-order">{{++$i}}</td>
                                            <td class="small-order">{{$product->name}}</td>
                                            <td class="small-order">{{Auth::user()->priceFormat($product->price)}}</td>
                                            <td class="small-order">{{$product->quantity}}</td>
                                            @php
                                                $price = $product->price * $product->quantity;
                                            @endphp
                                            <td class="invoice-order text-end">{{Auth::user()->priceFormat($price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice-total">
                            <table class="table invoice-table ">
                                <tbody>
                                    <tr>
                                        @php
                                            $subTotal = $estimation->getSubTotal();
                                        @endphp
                                        <th>{{__('Subtotal')}} :</th>
                                        <td>{{Auth::user()->priceFormat($subTotal)}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Discount')}} :</th>
                                        <td>{{Auth::user()->priceFormat($estimation->discount)}}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $tax = $estimation->getTax();
                                        @endphp
                                        <th>{{__('Tax')}} ({{$estimation->tax->rate}} %) :</th>
                                        <td>{{Auth::user()->priceFormat($tax)}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <hr/>
                                            <h5 class="text-primary m-r-10">{{__('Total')}}</h5>
                                        </td>
                                        <td>
                                            <hr/>
                                            <h5 class="text-primary">{{Auth::user()->priceFormat($subTotal-$estimation->discount+$tax)}}</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        {{-- <div class="row text-center d-print-none">
            @can('view estimation')
            <div class="col-sm-12 invoice-btn-group text-center mb-3">
                <button type="button"
                    class="btn btn-print-invoice  btn-primary m-b-10 m-r-10 "> <a href="{{ route('get.estimation',$estimation->id) }}" class="text_white" title="{{__('Print Estimation')}}"><span class="text-white">{{ __('Print') }}</span>
                </a></button>
                
                <h3 class="mt-4">{{__('Thank you!')}}</h3>
            </div>
            @endcan
        </div> --}}
    </div>
</div>
@endsection
