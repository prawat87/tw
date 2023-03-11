@extends('layouts.admin')

@section('page-title')
    {{__('Invoice Detail')}}
@endsection
@push('script-page')
    @php
        $settings = \App\Models\Utility::settings();
        $dir_payment = asset(Storage::url('payments'));
    @endphp
    <script>
        function getTask(obj, project_id) {
            $('#task_id').empty();
            var milestone_id = obj.value;
            $.ajax({
                url: '{!! route('invoices.milestone.task') !!}',
                data: {
                    "milestone_id": milestone_id,
                    "project_id": project_id,
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                success: function (data) {
                    $('#task_id').empty();
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].id + '>' + data[i].title + '</option>';
                    }
                    $('#task_id').append(html);
                    $('#task_id').select2('refresh');
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('{{__("Error")}}', data.error, 'error')
                }
            });
        }

        $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('Success', '{{__('Link Copy on Clipboard')}}', 'success')
        });

 
        function hide_show(obj) {
            if (obj.value == 'milestone') {
                document.getElementById('milestone').style.display = 'block';
                document.getElementById('other').style.display = 'none';
            } else {
                document.getElementById('other').style.display = 'block';
                document.getElementById('milestone').style.display = 'none';
            }
        }

    </script>

    @if(Auth::user()->type == 'client' && $invoice->getDue() > 0 && $settings['site_enable_stripe'] == 'on')
        <?php $stripe_session = Session::get('stripe_session');?>
        <?php if(isset($stripe_session) && $stripe_session): ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('{{ $admin_payment_setting['stripe_key'] }}');
            stripe.redirectToCheckout({
                sessionId: '{{ $stripe_session->id }}',
            }).then((result) => {
                console.log(result);
            });
        </script>
        <?php endif ?>
    @endif

    @if(Auth::user()->type == 'client' && $invoice->getDue() > 0 && isset($payment_setting['is_paystack_enabled']) && $payment_setting['is_paystack_enabled'] == 'on')

        <script src="https://js.paystack.co/v1/inline.js"></script>

        <script type="text/javascript">
            $(document).on("click", "#pay_with_paystack", function () {

                $('#paystack-payment-form').ajaxForm(function (res) {
                    if(res.flag == 1){
                        var coupon_id = res.coupon;

                        var paystack_callback = "{{ url('/invoice-pay-with-paystack') }}";
                        var order_id = '{{time()}}';
                        var handler = PaystackPop.setup({
                            key: '{{ $payment_setting['paystack_public_key']  }}',
                            email: res.email,
                            amount: res.total_price*100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                console.log(response.reference,order_id);
                                window.location.href = "{{url('/invoice/paystack')}}/"+response.reference+"/{{encrypt($invoice->id)}}";
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    }else if(res.flag == 2){

                    }else{
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        </script>
    @endif

    @if(Auth::user()->type == 'client' && $invoice->getDue() > 0 && isset($payment_setting['is_flutterwave_enabled']) && $payment_setting['is_flutterwave_enabled'] == 'on')

        <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

        <script type="text/javascript">

            //    Flaterwave Payment
            $(document).on("click", "#pay_with_flaterwave", function () {

                $('#flaterwave-payment-form').ajaxForm(function (res) {
                    if(res.flag == 1){
                        var coupon_id = res.coupon;
                        var API_publicKey = '';
                        if("{{ isset($payment_setting['flutterwave_public_key'] ) }}"){
                            API_publicKey = "{{$payment_setting['flutterwave_public_key']}}";
                        }
                        var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                        var flutter_callback = "{{ url('/invoice-pay-with-flaterwave') }}";
                        var x = getpaidSetup({
                            PBFPubKey: API_publicKey,
                            customer_email: '{{Auth::user()->email}}',
                            amount: res.total_price,
                            currency: '{{$payment_setting['currency']}}',
                            txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                {{ date('Y-m-d') }},
                            meta: [{
                                metaname: "payment_id",
                                metavalue: "id"
                            }],
                            onclose: function () {
                            },
                            callback: function (response) {
                                var txref = response.tx.txRef;
                                if(response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                                    window.location.href = "{{url('/invoice/flaterwave')}}/"+txref+"/{{encrypt($invoice->id)}}";
                                }else{
                                    // redirect to a failure page.
                                }
                                x.close(); // use this to close the modal immediately after payment.
                            }});
                    }else if(res.flag == 2){

                    }else{
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        </script>

    @endif

    @if(Auth::user()->type == 'client' && $invoice->getDue() > 0 && isset($payment_setting['is_razorpay_enabled']) && $payment_setting['is_razorpay_enabled'] == 'on')

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script type="text/javascript">
            // Razorpay Payment
            $(document).on("click", "#pay_with_razorpay", function () {
                $('#razorpay-payment-form').ajaxForm(function (res) {
                    
                    if(res.flag == 1){

                        var razorPay_callback = "{{url('/invoice-pay-with-razorpay')}}";
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var API_publicKey = '';
                        if("{{isset($payment_setting['razorpay_public_key'])}}"){
                            API_publicKey = "{{$payment_setting['razorpay_public_key']}}";
                        }
                        var options = {
                            "key": API_publicKey, // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Invoice Payment',
                            "currency": '{{$payment_setting['currency']}}',
                            "description": "",
                            "handler": function (response) {
                                window.location.href = "{{url('/invoice/razorpay')}}/"+response.razorpay_payment_id +"/{{encrypt($invoice->id)}}";
                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    }else if(res.flag == 2){

                    }else{
                        show_toastr('Error', data.message, 'msg');
                    }
                }).submit();
            });
        </script>
    
    @endif


@endpush

@push('css-page')
    <style>
        #card-element 
        {
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            padding: 10px;
        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('invoices.index')}}">{{__('Invoices')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Details')}}</li>
@endsection
@section('action-button') 
    @if(\Auth::user()->type == 'company') 
        <a href="#" class="btn btn-sm btn-primary btn-icon cp_link" data-link="{{route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))}}" data-toggle="tooltip" data-original-title="{{__('Click to copy invoice link')}}" title="{{__('Copy')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-copy"></i>
        </a>
    @endif  
    @if(\Auth::user()->type == 'client' && $invoice->getDue() > 0 && count($payment_setting)>0)
        <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{__('Pay Now')}}" data-bs-toggle="modal" data-bs-target="#paymentModal" data-bs-size="lg" data-bs-placement="top">
            <i class="ti ti-credit-card text-white"></i>
        </a>
    @endif
    
    @can('edit invoice')
        <a href="#" data-size="lg" data-url="{{ route('invoices.edit',$invoice->id) }}"
        data-ajax-popup="true" data-title="{{__('Edit Invoice')}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit')}}" >
            <i class="ti ti-pencil text-white"></i>
        </a>
    @endcan
    @can('send invoice')
        <a href="{{route('invoice.sent',$invoice->id)}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-original-title="{{__('Send Email')}}">
            <i class="ti ti-mail text-white"></i>
        </a>
    @endcan
    @can('payment reminder invoice')
        <a href="{{route('invoice.payment.reminder',$invoice->id)}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-original-title="{{__('Payment Reminder')}}">
            <i class="ti ti-bell text-white"></i>
        </a>

    @endcan
    @can('custom mail send invoice')
        <a href="#!" class="btn btn-sm btn-primary btn-icon" data-title="{{__('Send Invoice')}}" data-bs-toggle="tooltip" data-ajax-popup="true" data-bs-original-title="{{__('Send Invoice Mail')}}" data-url="{{ route('invoice.custom.send',$invoice->id) }}">
            <i class="ti ti-mail text-white"></i>
        </a>
    @endcan
        <a href="{{ route('get.invoice',Crypt::encrypt($invoice->id)) }}"  class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Print')}}" target="_blanks"><i class="ti ti-printer text-white"></i></a>
    @can('create invoice payment')
        <a href="#" data-url="{{ route('invoices.payments.create',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-primary btn-icon" title="{{__('Add Payment')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-report-money"></i>
        </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="card">
        <div class="">
            <div class="row">
                <!-- [ Invoice ] start -->
                <div class="container">
                    <div class="" id="printTable">
                        <div class="card-header">
                            <h5 class="" style=" left: -12px !important;">{{ App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}</h5>   
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
                                                    <h5>{{ __('To') }}:</h5>
                                                    {{-- @if ($client) --}}
                                                    <p>{{(!empty($user))?$user->name:''}}<br>
                                                        {{(!empty($user))?$user->email:''}}
                                                    </p>
                                                    {{-- @endif --}}
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
                                                            <th>{{ __('Issue Date') }} :</th>
                                                            <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ __('Due Date') }} :</th>
                                                            <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                                        </tr>
                                                        <tr>  
                                                            <div>
                                                                <th>{{__('Status')}} :</th>
                                                                @if($invoice->status == 0)
                                                                <td>      
                                                                    <span class="badge rounded p-2 px-3 bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                                </td>
                                                                @elseif($invoice->status == 1)
                                                                <td>
                                                                    <span class="badge rounded p-2 px-3 bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                                </td>
                                                                @elseif($invoice->status == 2)
                                                                <td>
                                                                    <span class="badge rounded p-2 px-3 bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                                </td>
                                                                @elseif($invoice->status == 3)
                                                                <td>
                                                                    <span class="badge rounded p-2 px-3 bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                                </td>
                                                                @elseif($invoice->status == 4)
                                                                <td>
                                                                    <span class="badge rounded p-2 px-3 bg-info">{{ __
                                                                    (\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                                </td>
                                                                @endif
                                                            </div>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-2 qr_code">
                                        <div class="text-end" style="margin: 26px 0px 0px 0px;">
                                            {!! DNS2D::getBarcodeHTML(route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)), "QRCODE",2,2) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 text-end m-b-10">
                                    <div class="justify-content-between align-items-center d-flex">
                                        <h5 class="m-0">{{__('ORDER SUMMARY')}}</h5>
                                        @can('create invoice product')
                                            <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('invoices.products.add',$invoice->id) }}" data-title="{{__('Add Item')}}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Item')}}"><span class="text-white"><i class="ti ti-plus"></i></span>
                                            </a>
                                        @endcan
                                    </div>
                                </div>

                                <div class="row" style="padding: 25px;">
                                    <div class="col-sm-12">
                                        <div class="table-responsive mb-4">
                                            <table class="table invoice-detail-table m-t-10">
                                                <thead>
                                                    <tr class="thead-default">                                            
                                                        <th>#</th>
                                                        <th>{{__('Item')}}</th>
                                                        <th>{{__('Price')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i=0; @endphp
                                                    @foreach($invoice->items as $items)
                                                        <tr>                                                
                                                            <td>
                                                                {{++$i}}
                                                            </td>
                                                            <td width="600" style="white-space: normal;">
                                                                {{$items->iteam}}
                                                            </td>
                                                            <td>
                                                                {{Auth::user()->priceFormat($items->price)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12 mb-4">
                                        <div class="invoice-total">
                                            <table class="table invoice-table ">
                                                <tbody>
                                                    <tr>
                                                        @php
                                                            $subTotal = $invoice->getSubTotal();
                                                            $tax = $invoice->getTax();
                                                        @endphp
                                                        <th>{{__('Subtotal')}} :</th>
                                                        <td>{{Auth::user()->priceFormat($subTotal)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('Discount')}} :</th>
                                                        <td>{{Auth::user()->priceFormat($invoice->discount)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{(!empty($invoice->tax)?$invoice->tax->name:'Tax')}} ({{(!empty($invoice->tax)?$invoice->tax->rate:'0')}} %) :</th>
                                                        <td>{{\Auth::user()->priceFormat($tax)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('Due Amount')}} :</th>
                                                        <td>{{Auth::user()->priceFormat($invoice->getDue())}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <hr/>
                                                            <h5 class="text-primary m-r-10">{{__('Total')}}</h5>
                                                        </td>
                                                        <td>
                                                            <hr/>
                                                            <h5 class="text-primary">{{Auth::user()->priceFormat($subTotal-$invoice->discount+$tax)}}</h5>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row invoive-info">
                                    <div class="col-sm-12">
                                        <h6 class="m-0">{{__('Payment History')}}</h6>
                                    </div>
                                </div>
                                <div class="row" style="padding: 25px;">
                                    <div class="col-sm-12">
                                        <div class="table-responsive mb-4">
                                            <table class="table invoice-detail-table m-t-10">
                                                <thead>
                                                    <tr class="thead-default">
                                                        <th>{{__('Transaction ID')}}</th>
                                                        <th>{{__('Payment Date')}}</th>
                                                        <th>{{__('Payment Method')}}</th>
                                                        <th>{{__('Payment Type')}}</th>
                                                        <th>{{__('Note')}}</th>
                                                        <th class="text-right">{{__('Amount')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i=0; @endphp
                                                    @foreach($invoice->payments as $payment)
                                                        <tr>
                                                            <td>{{sprintf("%05d", $payment->transaction_id)}}</td>
                                                            <td>{{ Auth::user()->dateFormat($payment->date) }}</td>
                                                            <td>{{(!empty($payment->payment)?$payment->payment->name:'-')}}</td>
                                                            <td>{{$payment->payment_type}}</td>
                                                            <td>{{!empty($payment->notes) ? $payment->notes : '-'}}</td>
                                                            <td class="text-right">{{\Auth::user()->priceFormat($payment->amount)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(\Auth::user()->type == 'client' && $invoice->getDue() > 0 && count($payment_setting)>0)
    <div class="modal fade bd-example-modal-lg" id="paymentModal" tabindex="-1" data-backdrop="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div>
                          
                    <div class="row pb-3 px-2">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            @if(isset($payment_setting['is_stripe_enabled']) && $payment_setting['is_stripe_enabled'] == 'on')
                                @if((isset($payment_setting['stripe_key']) && !empty($payment_setting['stripe_key'])) && 
                                (isset($payment_setting['stripe_secret']) && !empty($payment_setting['stripe_secret'])))
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#stripe-payment" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Stripe')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_paypal_enabled']) && $payment_setting['is_paypal_enabled'] == 'on')
                                @if((isset($payment_setting['paypal_client_id']) && !empty($payment_setting['paypal_client_id'])) && 
                                (isset($payment_setting['paypal_secret_key']) && !empty($payment_setting['paypal_secret_key'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-paypal-tab" data-bs-toggle="pill" href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{__('Paypal')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_paystack_enabled']) && $payment_setting['is_paystack_enabled'] == 'on')
                                @if((isset($payment_setting['paystack_public_key']) && !empty($payment_setting['paystack_public_key'])) && 
                                (isset($payment_setting['paystack_secret_key']) && !empty($payment_setting['paystack_secret_key'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-paystack-tab" data-bs-toggle="pill" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false">{{__('Paystack')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_flutterwave_enabled']) && $payment_setting['is_flutterwave_enabled'] == 'on')
                                @if((isset($payment_setting['flutterwave_secret_key']) && !empty($payment_setting['flutterwave_secret_key'])) && 
                                (isset($payment_setting['flutterwave_public_key']) && !empty($payment_setting['flutterwave_public_key'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-flutterwave-tab" data-bs-toggle="pill" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false">{{__('Flutterwave')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_razorpay_enabled']) && $payment_setting['is_razorpay_enabled'] == 'on')
                                @if((isset($payment_setting['razorpay_public_key']) && !empty($payment_setting['razorpay_public_key'])) && 
                                (isset($payment_setting['razorpay_secret_key']) && !empty($payment_setting['razorpay_secret_key'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-razorpay-tab" data-bs-toggle="pill" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false">{{__('Razorpay')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_mercado_enabled']) && $payment_setting['is_mercado_enabled'] == 'on')
                                 @if((isset($payment_setting['mercado_access_token']) && !empty($payment_setting['mercado_access_token'])) )
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-mercado-tab" data-bs-toggle="pill" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{__('Mercado Pago')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_paytm_enabled']) && $payment_setting['is_paytm_enabled'] == 'on')
                                @if((isset($payment_setting['paytm_merchant_id']) && !empty($payment_setting['paytm_merchant_id'])) && 
                                (isset($payment_setting['paytm_merchant_key']) && !empty($payment_setting['paytm_merchant_key'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-paytm-tab" data-bs-toggle="pill" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{__('Paytm')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_mollie_enabled']) && $payment_setting['is_mollie_enabled'] == 'on')
                                @if((isset($payment_setting['mollie_api_key']) && !empty($payment_setting['mollie_api_key'])) && 
                                (isset($payment_setting['mollie_profile_id']) && !empty($payment_setting['mollie_profile_id'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-mollie-tab" data-bs-toggle="pill" href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{__('Mollie')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_skrill_enabled']) && $payment_setting['is_skrill_enabled'] == 'on')
                                @if((isset($payment_setting['skrill_email']) && !empty($payment_setting['skrill_email'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-skrill-tab" data-bs-toggle="pill" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{__('Skrill')}}</a>
                                    </li>
                                @endif
                            @endif
                            @if(isset($payment_setting['is_coingate_enabled']) && $payment_setting['is_coingate_enabled'] == 'on')
                                @if((isset($payment_setting['coingate_auth_token']) && !empty($payment_setting['coingate_auth_token'])))
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-coingate-tab" data-bs-toggle="pill" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false">{{__('CoinGate')}}</a>
                                    </li>
                                @endif
                            @endif

                            @if(isset($payment_setting['is_paymentwall_enabled']) && $payment_setting['is_paymentwall_enabled'] == 'on')
                            @if((isset($payment_setting['paymentwall_public_key']) && !empty($payment_setting['paymentwall_public_key'])) && 
                            (isset($payment_setting['paymentwall_private_key']) && !empty($payment_setting['paymentwall_private_key'])))
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-paymentwall-tab" data-bs-toggle="pill" href="#paymentwall-payment" role="tab" aria-controls="paymentwall" aria-selected="false">{{__('PaymentWall')}}</a>
                                </li>
                            @endif
                        @endif
                        </ul>
                    </div>
                    

                    <div class="tab-content">
                        
                        @if(isset($payment_setting['is_stripe_enabled']) && $payment_setting['is_stripe_enabled'] == 'on')
                            @if((isset($payment_setting['stripe_key']) && !empty($payment_setting['stripe_key'])) && 
                                (isset($payment_setting['stripe_secret']) && !empty($payment_setting['stripe_secret'])))
                                <div class="tab-pane fade {{ isset($payment_setting['is_stripe_enabled']) && $payment_setting['is_stripe_enabled'] == 'on'  ? 'show active' : ''}}" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                    <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('invoice.pay.with.stripe') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{ $payment_setting['currency_symbol'] }}</div>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                                @error('amount')
                                                <span class="invalid-amount text-danger text-xs" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_paypal_enabled']) && $payment_setting['is_paypal_enabled'] == 'on')
                            @if((isset($payment_setting['paypal_client_id']) && !empty($payment_setting['paypal_client_id'])) && 
                                (isset($payment_setting['paypal_secret_key']) && !empty($payment_setting['paypal_secret_key'])))
                                <div class="tab-pane fade" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                    
                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('client.pay.with.paypal', $invoice->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                    <span class="invalid-amount text-danger text-xs" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>
                                    
                                        
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_paystack_enabled']) && $payment_setting['is_paystack_enabled'] == 'on')
                            @if((isset($payment_setting['paystack_public_key']) && !empty($payment_setting['paystack_public_key'])) && 
                                (isset($payment_setting['paystack_secret_key']) && !empty($payment_setting['paystack_secret_key'])))
                                <div class="tab-pane fade" id="paystack-payment" role="tabpanel" aria-labelledby="paystack-payment">
                                    
                                        <form method="post" action="{{route('invoice.pay.with.paystack')}}" class="require-validation" id="paystack-payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="button" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10" id="pay_with_paystack">
                                            </div>
                                        </form>
                                    
                                        
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_flutterwave_enabled']) && $payment_setting['is_flutterwave_enabled'] == 'on')
                            @if((isset($payment_setting['flutterwave_secret_key']) && !empty($payment_setting['flutterwave_secret_key'])) && 
                                (isset($payment_setting['flutterwave_public_key']) && !empty($payment_setting['flutterwave_public_key'])))
                                <div class="tab-pane fade " id="flutterwave-payment" role="tabpanel" aria-labelledby="flutterwave-payment">
                                    
                                        <form method="post" action="{{route('invoice.pay.with.flaterwave')}}" class="require-validation" id="flaterwave-payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="button" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10" id="pay_with_flaterwave">
                                            </div>
                                        </form>
                                    
                                        
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_razorpay_enabled']) && $payment_setting['is_razorpay_enabled'] == 'on')
                            @if((isset($payment_setting['razorpay_public_key']) && !empty($payment_setting['razorpay_public_key'])) && 
                                (isset($payment_setting['razorpay_secret_key']) && !empty($payment_setting['razorpay_secret_key'])))
                                <div class="tab-pane fade " id="razorpay-payment" role="tabpanel" aria-labelledby="flutterwave-payment">
                                    <form method="post" action="{{route('invoice.pay.with.razorpay')}}" class="require-validation"      id="razorpay-payment-form">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-end">
                                            <input type="button" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10" id="pay_with_razorpay">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_mollie_enabled']) && $payment_setting['is_mollie_enabled'] == 'on')
                            @if((isset($payment_setting['mollie_api_key']) && !empty($payment_setting['mollie_api_key'])) && 
                                (isset($payment_setting['mollie_profile_id']) && !empty($payment_setting['mollie_profile_id'])))
                                <div class="tab-pane fade " id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                    <form method="post" action="{{route('invoice.pay.with.mollie')}}" class="require-validation" id="mollie-payment-form">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-end">
                                            <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_mercado_enabled']) && $payment_setting['is_mercado_enabled'] == 'on')
                             @if((isset($payment_setting['mercado_access_token']) && !empty($payment_setting['mercado_access_token'])) )
                                <div class="tab-pane fade " id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                    <form method="post" action="{{route('invoice.pay.with.mercado')}}" class="require-validation" id="mercado-payment-form">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-end">
                                            <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_paytm_enabled']) && $payment_setting['is_paytm_enabled'] == 'on')
                            @if((isset($payment_setting['paytm_merchant_id']) && !empty($payment_setting['paytm_merchant_id'])) && 
                                (isset($payment_setting['paytm_merchant_key']) && !empty($payment_setting['paytm_merchant_key'])))
                                <div class="tab-pane fade " id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                    <form method="post" action="{{route('invoice.pay.with.paytm')}}" class="require-validation" id="paytm-payment-form">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <div class="form-group">
                                                    <label for="mobile" class="col-form-label">{{__('Mobile Number')}}</label>
                                                    <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}" required>
                                                </div>
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-end">
                                            <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_skrill_enabled']) && $payment_setting['is_skrill_enabled'] == 'on')
                            @if((isset($payment_setting['skrill_email']) && !empty($payment_setting['skrill_email'])))
                                <div class="tab-pane fade " id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                    
                                        <form method="post" action="{{route('invoice.pay.with.skrill')}}" class="require-validation" id="skrill-payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $skrill_data = [
                                                    'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                    'user_id' => 'user_id',
                                                    'amount' => 'amount',
                                                    'currency' => 'currency',
                                                ];
                                                session()->put('skrill_data', $skrill_data);
                                            @endphp
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    
                                    
                                </div>
                            @endif
                        @endif
                        @if(isset($payment_setting['is_coingate_enabled']) && $payment_setting['is_coingate_enabled'] == 'on')
                            @if((isset($payment_setting['coingate_auth_token']) && !empty($payment_setting['coingate_auth_token'])))
                                <div class="tab-pane fade " id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                    
                                        <form method="post" action="{{route('invoice.pay.with.coingate')}}" class="require-validation" id="coingate-payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    
                                    
                                </div>
                            @endif
                        @endif 

                        <div class="tab-pane fade" id="paymentwall-payment" role="tabpanel" aria-labelledby="paymentwall-payment-tab">
                            @if(isset($payment_setting['is_paymentwall_enabled']) && $payment_setting['is_paymentwall_enabled'] == 'on')
                                @if((isset($payment_setting['paymentwall_public_key']) && !empty($payment_setting['paymentwall_public_key'])) && 
                                    (isset($payment_setting['paymentwall_private_key']) && !empty($payment_setting['paymentwall_private_key'])))
                                    
                                    <form method="post" action="{{route('paymentwall.invoice')}}" class="require-validation" id="paymentwall-payment-form">
                                        @csrf
                                        <div class="row">
                                    
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">{{isset($payment_setting['currency_symbol'])?$payment_setting['currency_symbol']:'$'}}</div>
                                                    <input claszs="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    <input type="hidden" value="{{$invoice->id}}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-end">
                                            <input type="submit" value="{{__('Make Payment')}}" class="btn btn-print-invoice  btn-primary m-r-10" id="pay_with_paymentwall">
                                        </div>
                                    </form>
                                    
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
