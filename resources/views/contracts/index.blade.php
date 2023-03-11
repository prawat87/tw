@extends('layouts.admin')

@section('page-title')
    {{__('Manage Contract')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Contract')}}</li>
@endsection


@section('action-button')
    <div class="row align-items-center m-1">
        @if(\Auth::user()->type=='company')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('contracts.create') }}" data-ajax-popup="true" data-title="{{__('Create New contracts')}}" data-size="lg" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="ti ti-plus"></i>
                </a>
            </div>
        @endif
    </div>
@endsection

@section('content')
        <div class="row">
            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Total Contracts')}}</h6>
                                <h3 class="text-primary">{{ $cnt_contract['total'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-success text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Month Total Contracts')}}</h6>
                                <h3 class="text-info">{{ $cnt_contract['this_month'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-info text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Week Total Contracts')}}</h6>
                                <h3 class="text-warning">{{ $cnt_contract['this_week'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-warning text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body" style="min-height: 143px;">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Last 30 Days Total Contracts')}}</h6>
                                <h3 class="text-danger">{{ $cnt_contract['last_30days'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card table-card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple table dataTable">
                                <thead>
                                    <tr>
                                        <th>{{__('Contracts')}}</th>
                                        <th>{{__('Client')}}</th>
                                        <th>{{__('Project')}}</th>
                                        <th>{{__('Subject')}}</th>
                                        <th>{{__('Value')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Start Date')}}</th>
                                        <th>{{__('End Date')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th width="250px">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('contracts.show',$contract->id) }}" class="btn btn-outline-primary">{{ App\Models\Utility::contractNumberFormat($contract->id) }}</a>
                                            </td>
                                            <td>{{!empty($contract->clients)?$contract->clients->name:'' }}</td>
                                            <td>{{!empty( $contract->projectss ) ?  $contract->projectss->name  : ''}}</td>
                                            <td>{{ $contract->subject }}</td>
                                            <td>{{ Auth::user()->priceFormat($contract->value) }}</td>
                                            <td>{{ $contract->contract_type->name }}</td>
                                            <td>{{ Auth::user()->dateFormat($contract->start_date) }}</td>
                                            <td>{{ Auth::user()->dateFormat($contract->end_date) }}</td>
                                            <td>
                                                @if($contract->status == 'accept')
                                                    <span class="status_badge badge bg-primary  p-2 px-3 rounded">{{__('Accept')}}</span>
                                                @elseif($contract->status == 'decline')
                                                    <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __('Decline') }}</span>
                                                @elseif($contract->status == 'pending')  
                                                    <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __('Pending') }}</span>
                                                @endif
                                            </td>
                                            <td class="Action">
                                                <span>
                                                    @if(\Auth::user()->type=='company' && $contract->status == 'accept')
                                                    <div class="action-btn btn-secondary ms-2">
                                                        <a href="#" data-size="lg" data-url="{{route('contracts.copy',$contract->id)}}"data-ajax-popup="true" data-title="{{__('Duplicate')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Duplicate')}}" ><i class="ti ti-copy text-white"></i></a>
                                                    </div>
                                                   @endif
                                                   @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('contracts.show',$contract->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Detail" aria-label="Detail"><span class="text-white"><i class="ti ti-eye"></i></span></a>
                                                    </div>
                                                    @endif
                                                   @if(\Auth::user()->type=='company')
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="lg" data-url="{{ route('contracts.edit',$contract->id) }}"
                                                            data-ajax-popup="true" data-title="{{__('Edit contract')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit')}}" ><i class="ti ti-edit text-white"></i></a>
                                                    </div>
                                                    @endif
                                                   @if(\Auth::user()->type=='company')
                                                        <div class="action-btn bg-danger ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$contract->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contracts.destroy', $contract->id], 'id' => 'delete-form-' . $contract->id]) !!}
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endif
                                                </span>
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

@push('script-page')

<script type="text/javascript">
    $(document).on('change', '.client_id', function() {
    //    alert('hey');
        getUsers($(this).val());
    });

    function getUsers(id) {
        console.log();
        $("#project-div").html('');
        $('#project-div').append('<select class="form-control" id="project" name="project" ></select>');
        // console.log('project');
        $.get("{{ url('get-projects') }}/" + id, function(data, status) 
        {
            var list = '';
            $('#project').empty();
            if(data.length > 0){
                list += "<option value=''>  </option>";
            }else{
                list += "<option value=''> {{__('No Users')}} </option>";
            }
            $.each(data, function(i, item) {
                list += "<option value='"+item.id+"'>"+item.name+"</option>"
            });

            var select = '<select class="form-control" id="project" name="project" >'+list+'</select>';
            $('.project-div').html(select);
            select2();
        });
    }
</script>


@endpush