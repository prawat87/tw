<title>{{ config('chatify.name') }}</title>

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="route" content="{{ $route }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>


{{-- styles --}}

<link href="{{ asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" />
<link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
{{-- scripts --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script> 


{{-- Messenger Color Style--}}
@include('Chatify::layouts.messengerColor')
