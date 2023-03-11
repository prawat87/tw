@extends('layouts.admin')

@section('page-title')
    {{__('Manage Estimation')}}
@endsection

@section('action-button')
    @can('create estimation')
        {{-- <div class="col-auto pe-0"> --}}
            <a href="{{ route('Estimation.export') }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top" style="margin-right: 5px;"><i class="ti ti-file-export"></i></a>
            
            <a href="#" data-url="{{ route('estimations.create') }}" data-ajax-popup="true" data-title="{{__('Create Estimate')}}" class="btn btn-sm btn-primary btn-icon" title="{{__('Create')}}" data-size="md" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
        {{-- </div> --}}
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Estimation')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted">{{__('Total Estimate')}}</small>
                            <h3 class="m-0">{{ $cnt_estimation['total'] }}</h3>
                        </div>
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="theme-avtar bg-primary mb-3">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted">{{__('This Month Total Estimate')}}</small>
                            <h3 class="m-0">{{ $cnt_estimation['this_month'] }}</h3>
                        </div>
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="theme-avtar bg-info mb-3">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted">{{__('This Week Total Estimate')}}</small>
                            <h3 class="m-0">{{ $cnt_estimation['this_week'] }}</h3>
                        </div>
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="theme-avtar bg-danger mb-3">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <small class="text-muted">{{__('Last 30 Days Total Estimate')}}</small>
                            <h3 class="m-0">{{ $cnt_estimation['last_30days'] }}</h3>
                        </div>
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="theme-avtar bg-warning mb-3">
                                <i class="ti ti-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Estimate')}}</th>
                                <th>{{__('Client')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Auth::user()->type != 'client')
                                    <th width="250px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($estimations as $estimate)
                                <tr>
                                    <td class="Id">
                                        @can('view estimation')
                                        
                                            <a href="{{route('estimations.show',$estimate->id)}}" class="btn btn-outline-primary">{{App\Models\Utility::estimateNumberFormat($estimate->estimation_id) }}</a>
                                        @else
                                            {{App\Models\Utility::estimateNumberFormat($estimate->estimation_id) }}
                                        @endcan
                                    </td>
                                    <td>{{ !empty($estimate->client)?$estimate->client->name:'-'}}</td>
                                    <td>{{ Auth::user()->dateFormat($estimate->issue_date) }}</td>
                                    <td>{{ Auth::user()->priceFormat($estimate->getTotal()) }}</td>
                                    <td>
                                        @if($estimate->status == 0)
                                            <span class="badge p-2 px-3 rounded bg-primary">{{ __(\App\Models\Estimation::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 1)
                                            <span class="badge p-2 px-3 rounded bg-danger">{{ __(\App\Models\Estimation::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 2)
                                            <span class="badge p-2 px-3 rounded bg-warning">{{ __(\App\Models\Estimation::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 3)
                                            <span class="badge p-2 px-3 rounded bg-success">{{ __(\App\Models\Estimation::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 4)
                                            <span class="badge p-2 px-3 rounded bg-info">{{ __(\App\Models\Estimation::$statues[$estimate->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->type != 'client')
                                        <td>
                                            @can('view estimation')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{route('estimations.show',$estimate->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-eye"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('edit estimation')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" data-url="{{ URL::to('estimations/'.$estimate->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Estimation')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-size="md" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('delete estimation')
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$estimate->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                </div>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['estimations.destroy', $estimate->id],'id'=>'delete-form-'.$estimate->id]) !!}
                                                {!! Form::close() !!}
                                            @endif
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
