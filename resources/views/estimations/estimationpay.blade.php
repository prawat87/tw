@extends('layouts.invoicepayheader')
@php
$SITE_RTL = env('SITE_RTL');
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}
@endphp
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

@section('content')
<div class="row text-center d-print-none">
    @can('view estimation')
        <div class="invoice-btn-group text-end mb-3">
            <button type="button" class="btn btn-print-invoice btn-md btn-primary m-b-10 m-r-10 "> 
                <a href="{{ route('get.estimation',$estimation->id) }}" class="text_white" title="{{__('Print Estimation')}}">
                    <span class="text-white"><i class="ti ti-printer text-white"></i></span>
                </a>
            </button>
        </div>
    @endcan
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card" id="printTable">
            <div class="card-header">
                <h5 class="">{{  App\Models\Utility::estimateNumberFormat($estimation->estimation_id) }}</h5>   
            </div>
            <div class="card-body" style="margin-top: -30px;">
                <div class="row" style="margin-bottom: 25px;">
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
                                {{-- <div class="col-sm-12"> --}}
                                    <h5>{{ __('To') }}:</h5>
                                    @if ($client)
                                    <p>{{ $client->name }}<br>
                                        {{$client->email }}<br>
                                    </p>
                                    @endif
                                {{-- </div> --}}
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
                                            @php
                                                $user = \App\Models\User::where('id',$estimation->created_by)->first();
                                            @endphp
                                            <td>{{ $user->dateFormat($estimation->issue_date) }}</td>

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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive mb-4">
                            <table class="table invoice-detail-table m-t-10">
                                <thead>
                                    <tr class="thead-default">
                                        <th>#</th>
                                        <th>{{__('Item')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Quantity')}}</th>
                                        <th>{{__('Totals')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach($estimation->getProducts as $product)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$usr->priceFormat($product->price)}}</td>
                                            <td>{{$product->quantity}}</td>
                                            @php
                                                $price = $product->price * $product->quantity;
                                            @endphp
                                            <td class="text-right">{{$usr->priceFormat($price)}}</td>
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
                                        <td>{{$usr->priceFormat($subTotal)}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Discount')}} :</th>
                                        <td>{{$usr->priceFormat($estimation->discount)}}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $tax = $estimation->getTax();
                                        @endphp
                                        <th>{{__('Tax')}} ({{$estimation->tax->rate}} %) :</th>
                                        <td>{{$usr->priceFormat($tax)}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <hr/>
                                            <h5 class="text-primary m-r-10">{{__('Total')}}</h5>
                                        </td>
                                        <td>
                                            <hr/>
                                            <h5 class="text-primary">{{$usr->priceFormat($subTotal-$estimation->discount+$tax)}}</h5>
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
</div>
@endsection
