@php
    $logo=asset(Storage::url('logo/'));
    $favicon=Utility::getValByName('company_favicon');
@endphp
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{(Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'WorkGo')}}</title>
        <link rel="icon" href="{{$logo.'/'.(isset($favicon) && !empty($favicon)?$favicon:'favicon.png')}}" type="image">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
@php
    $plan_id= \Illuminate\Support\Facades\Crypt::decrypt($data['plan_id']);
    $plandata=App\Models\Plan::where('id',$plan_id)->first();
@endphp


    <script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
    <div id="payment-form-container"> </div>
    <script>
        var brick = new Brick(
        {
            public_key: '{{ $admin_payment_setting['paymentwall_public_key']  }}', // please update it to Brick live key before launch your project
            amount: '{{$plandata->price}}',
            currency: '{{ $admin_payment_setting['currency']}}',
            container: 'payment-form-container',
            action: '{{route("paymentwall.payment",[$data["plan_id"],$data["coupon"]])}}',
            success_url:'{{route("plans.index")}}',
            form: {
                merchant: 'Paymentwall',
                product: '{{$plandata->name}}',
                pay_button: 'Pay',
                show_zip: true, // show zip code
                show_cardholder: true // show card holder name
            },
        });

        brick.showPaymentForm(function(data) 
        {
            if(data.flag == 1)
            {
                window.location.href ='{{route("error.plan.show",1)}}';
            }
            else
            {
                window.location.href ='{{route("error.plan.show",2)}}';
            }
        }, 
        function(errors) 
        {
            if(errors.flag == 1)
            {
                window.location.href ='{{route("error.plan.show",1)}}';
            }
            else
            {
                window.location.href ='{{route("error.plan.show",2)}}';
            }
        });
  </script>
