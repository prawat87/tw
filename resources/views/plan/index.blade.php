@extends('layouts.admin')
@section('page-title')
    {{__('Manage Plans')}}
@endsection
@section('action-button')
    <div>
        @can('create plan')
            @if(count($payment_setting)>0)
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('plans.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create Plan')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
            @endif
        @endcan
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Plan')}}</li>
@endsection
@section('content')
    @can('create plan')
        <div class="row">
            <div class="col-12">
                @if(count($payment_setting)==0)
                    <div class="alert alert-warning"><i class="fe fe-info"></i> {{__('Please set payment api key & secret key for add new plan')}}</div>
                @endif
            </div>
        </div>
    @endcan

    <div class="row">
        @foreach($plans as $plan)
            <div class="col-lg-3 col-md-4">
                <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                    <div class="card-body" style="min-height: 327px;">
                        <span class="price-badge bg-primary">{{ $plan->name }}</span>

                            <div class="d-flex flex-row-reverse m-0 p-0 ">
                                @can('edit plan')
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Plan')}}" data-url="{{route('plans.edit',$plan->id)}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                    </div>
                                @endcan
                                @if(\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                <span class="d-flex align-items-center ms-2">
                                    <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                    <span class="ms-2">{{ __('Active') }}</span>
                                </span>
                                @endif
                            </div>


                            <span class="mb-4 f-w-600 p-price">{{(env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$')}}{{ number_format($plan->price) }}<small class="text-sm">/ {{$plan->duration}}</small></span>
                            <p class="mb-0">
                                {{-- {{ $plan->name }} {{__('Plan')}} --}}
                            </p>
                            <p class="mb-0">
                                {{ $plan->description }}
                            </p>

                            <ul class="list-unstyled my-4">
                                <li>
                                  <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ ($plan->max_users < 0) ? __('Unlimited'):$plan->max_users }} {{__('Users')}}
                                </li>
                                <li>
                                  <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ ($plan->max_clients < 0) ? __('Unlimited'):$plan->max_clients }} {{__('Clients')}}
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ ($plan->max_projects < 0) ? __('Unlimited'):$plan->max_projects }} {{__('Projects')}}
                                </li>
                            </ul>
                            <div class="row d-flex justify-content-between">
                                @can('buy plan')
                                    @if($plan->id != \Auth::user()->plan && $plan->price!=0)
                                        <div class="col-8">
                                            <div class="d-grid text-center">
                                                <a href="{{route('payment',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}" class="btn btn-primary btn-sm d-flex justify-content-center align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Subscribe')}}">{{__('Subscribe')}}
                                                <i class="fas fa-arrow-right m-1"></i></a>
                                                <p></p>
                                            </div>
                                        </div>
                                        @elseif($plan->price<=0)
                                        {{-- <p class="mb-0">
                                            {{ __('Plan Expired : ') }}
                                            {{ !empty($plan_expire_date) ? \Auth::user()->dateFormat($plan_expire_date) : 'Unlimited' }}
                                        </p> --}}
                                    @endif
                                @endcan
                                @if($plan->id != 1 && \Auth::user()->plan != $plan->id && \Auth::user()->type == 'company')
                                    <div class="col-3">
                                        @if(\Auth::user()->requested_plan != $plan->id)
                                            <a href="{{ route('send.request',[\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}" class="btn btn-primary btn-icon btn-sm" data-title="{{__('Send Request')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Send Request')}}">
                                                <span class="btn-inner--icon"><i class="fas fa-share"></i></span>
                                            </a>
                                        @else
                                            <a href="{{ route('request.cancel',\Auth::user()->id) }}" class="btn btn-danger btn-icon btn-sm" data-title="{{__('Cancle Request')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Cancle Request')}}">
                                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                @if(\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id )
                                    @if(empty(\Auth::user()->plan_expire_date))
                                        <p class="mb-0">{{__('Unlimited')}}</p>
                                    @else
                                        <p class="mb-0">
                                            {{__('Expire on ')}} {{ (date('d M Y',strtotime(\Auth::user()->plan_expire_date))) }}
                                        </p>
                                    @endif
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
@endsection
