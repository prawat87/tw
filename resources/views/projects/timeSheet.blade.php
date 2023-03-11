@extends('layouts.admin')
@section('page-title')
    {{ __('Manage TimeSheet') }}
@endsection

@section('action-button')
    @can('manage timesheet')
        <a href="#!" data-bs-toggle="tooltip" data-bs-placement="top" class="filter-records btn btn-sm btn-primary btn-icon"
            title="{{ __('Filter') }}"><i class="ti ti-filter"></i></a>
        <a href="{{ route('timesheet.export') }}" data-bs-toggle="tooltip" data-bs-placement="top"
            class="btn btn-sm btn-primary btn-icon" title="{{ __('Export') }}"><i class="ti ti-file-export"></i></a>
        <a href="#" data-url="{{ route('task.timesheet') }}" data-bs-toggle="tooltip" data-ajax-popup="true"
            data-title="{{ __('Create Time Sheet') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('TimeSheet') }}</li>
@endsection
@section('content')
    <style>
        .dash-content {
            padding-right: 0;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .dash-content.toggled {
            padding-right: 265px;
        }

        #sidebar-wrapper {
            z-index: 1000;
            position: fixed;
            right: 350px;
            width: 0;
            height: 100%;
            margin-right: -350px;
            overflow-y: auto;
            border: 1px solid #c3cde1;
            background: #f5f7fa;
            border-radius: 5px;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
            top: 20%;
            height: 100%;
            color: #000;
        }


        .dash-content.toggled #sidebar-wrapper {
            width: 350px;
            padding: 15px;
        }

        #page-content-wrapper {
            width: 100%;
            position: absolute;
            padding: 15px;
        }

        .dash-content.toggled #page-content-wrapper {
            position: absolute;
            margin-left: -350px;
        }

        /* Sidebar Styles */

        .sidebar-nav {
            position: absolute;
            top: 0;
            width: 320px;
            margin: 0;
            padding: 0;
            list-style: none;

        }

        .sidebar-nav li {
            /* text-indent: 20px; */
            line-height: 40px;
            padding-top: 10px;
        }

        .sidebar-nav li a {
            display: block;
            text-decoration: none;
            color: #999999;
        }

        .sidebar-nav li a:hover {
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-nav li a:active,
        .sidebar-nav li a:focus {
            text-decoration: none;
        }

        .sidebar-nav>.sidebar-brand {
            height: auto;
            font-size: 18px;
            line-height: 28px;
            padding-top: 20px;
            font-weight: bold;
        }

        .sidebar-nav>.sidebar-brand a {
            color: #999999;
        }

        .sidebar-nav>.sidebar-brand a:hover {
            color: #fff;
            background: none;
        }

        @media (min-width: 768px) {
            .dash-content {
                padding-right: 0;
            }

            .dash-content.toggled {
                padding-right: 365px;
            }

            #sidebar-wrapper {
                width: 0;
            }

            .dash-content.toggled #sidebar-wrapper {
                width: 350px;
            }

            #page-content-wrapper {
                padding: 20px;
                position: relative;
            }

            .dash-content.toggled #page-content-wrapper {
                position: relative;
                margin-left: 0;
            }
        }

        .timesheet-entries { margin-top: 10px;}
        .timesheet-entries tr td:first-child {width:20%;}
        .timesheet-entries tr td:first-child .ms-3 {margin-left:0!important; overflow-wrap: anywhere;}
        .timesheet-entries tr td { overflow-wrap: anywhere;}

        .page-header { position: sticky; top: 0; background: #fff;}
        .page-header h4 { margin-top: 10px;}
    </style>

<div class="timesheet-entries">
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-expand-lg navbar-light" style="border-radius: 20px; padding-left: 18.1px; background-color: #f5f7fa">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <span><strong>Filtered Totals: </strong></span>
                      </li>
                      <li class="nav-item">
                        <span><strong>Logged: </strong> {{ $totalLogHours ?? '0' }} </span>
                      </li>
                      <li class="nav-item">
                        <span><strong>Billable: </strong> {{ $totalBillableLogHours ?? '0'}}</span>
                      </li>
                      <li class="nav-item">
                        <span><strong>Estimated: </strong> {{ $totalTasksEstimated ?? '0' }}</span>
                      </li>
                      <li class="nav-item">
                        <span><strong>Non-billable: </strong> {{ $totalNotBillableLogHours ?? '0' }}</span>
                      </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        @if (count($timeSheets) > 0)
            @foreach ($timeSheets as $date => $logs)
                <h5 class="my-3">{{ $dateFormat[$date]; }}</h5>
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 10%;">{{ __('Project') }}</th>
                            <th style="width: 10%;">{{ __('Who') }}</th>
                            <th style="width: 20%;">{{ __('Description') }}</th>
                            <th style="width: 10%;">{{__('Task List')}}</th>
                            <th style="width: 10%;">{{ __('Start') }}</th>
                            <th style="width: 10%;">{{ __('End') }}</th>
                            <th style="width: 10%;">{{ __('Billable') }}</th>
                            <th style="width: 10%;">{{ __('Time') }}</th>
                            <th style="width: 10%;">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $timeSheet)
                            @php
                            $user_avtar = !empty($timeSheet['avatar']) ? $timeSheet['avatar'] : 'profile.jpg';
                            @endphp
                            <tr>
                                <td style="width: 10%;">
                                    <div class="ms-3">
                                        <p>{{ $timeSheet['project_name'] ?? '' }}</p>
                                    </div>
                                </td>
                                <td style="width: 10%;">
                                    <div class="d-flex align-items-center">
                                        <img src='{{ asset("storage/productimages/$user_avtar") }}' alt=""
                                            style="width: 45px; height: 45px" class="rounded-circle" />
                                    </div>
                                    <div>
                                        <p class="fw-bold mb-1">
                                          {{ $timeSheet['user_name'] ?? '' }}
                                        </p>
                                    </div>
                                </td>
                                <td style="width: 20%;">
                                    <p class="fw-normal mb-1">Task :
                                        {{ $timeSheet['task_title'] ?? '' }}</p>
                                    <p class="text-muted mb-0">
                                        {{ $timeSheet['remark'] ?? '' }}</p>
                                </td>
                                <td>
                                  <p class="fw-normal mb-1">{{ $timeSheet['group_name'] ?? ''}}</p>
                              </td>
                                <!-- <td>
                                                                                                                                    <span class="badge badge-success rounded-pill d-inline">Active</span>
                                                                                                                                  </td> -->
                                <td style="width: 10%;">{{ $timeSheet['start_time'] ?? '' }}</td>
                                <td style="width: 10%;">{{ $timeSheet['end_time'] ?? '' }}</td>
                                <td style="width: 10%;">
                                    @if($timeSheet['billable'] == 'Yes')
                                    <img src="{{URL::asset('public/assets/images/icons/accept.png')}}" alt="Yes" height="20" width="20" style="margin-left: 20px;">
                                  @else
                                    <img src="{{URL::asset('public/assets/images/icons/reject.png')}}" alt="No" height="20" width="20" style="margin-left: 20px;">
                                  @endif</td>
                                <td style="width: 10%;">{{ $timeSheet['total_hrs_mins'] ?? '' }}</td>
                                @if (\Auth::user()->type != 'client')
                                    <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-url="{{ route('task.timesheet.edit', [$timeSheet['id']]) }}"
                                                data-ajax-popup="true" data-title="{{ __('Edit Time Sheet') }}"
                                                title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-size="md"><span class="text-white"><i
                                                        class="ti ti-edit"></i></span></a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $timeSheet['id'] }}"
                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top"><span class="text-white"><i
                                                        class="ti ti-trash"></i></span></a>
                                        </div> {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['task.timesheet.destroy', $timeSheet['id']],
                                            'id' => 'delete-form-' . $timeSheet['id'],
                                        ]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                @else
                                    <td>{{ $timeSheet['user_name'] ?? '' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- <h6 class="my-3" style="text-align: right">Total: 8h (8.00) Billable Time: 8h (8.00)</h6> -->
                <p class="my-3" style="text-align: right"><strong>Total: </strong> {{ (!empty($finalTime[$date])) ? floor($finalTime[$date] / 60).'h ' : '' }} {{ (($finalTime[$date] - floor($finalTime[$date] / 60) * 60) > 0) ? ($finalTime[$date] - floor($finalTime[$date] / 60) * 60) . 'm' : '' }} <strong>  Billable Time: </strong> {{ (!empty($totalBillableTimeSum[$date])) ? floor($totalBillableTimeSum[$date] / 60).'h ' : '' }} {{ (isset($totalBillableTimeSum[$date])) ? (($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) > 0) ? ($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) . 'm' : '' : '0h' }}</p>
            @endforeach

            @else
                <h3 class='noRecords' style="margin-left:30%; margin-top:10%">There are no time logs that match your filter</h3>
            @endif
        </div>

    </div>
</div>
@if($showMoreButtonStatus)
<button type="button" class="btn btn-primary my-2" data-endDate="" data-lastDate="{{ $lastDate }}" id="showMore">Show More</button>
@endif
    <!-- Sidebar -->
    <div id="sidebar-wrapper">

        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    Filter
                </a>
            </li>
            <li>

                <label for="projects">Project</label>
                <select name="projects" data-filter="project" id="projects" title="Select Project"
                    data-live-search="true" class=" form-select asj-timesheet-filter">
                        <option value="-1">All</option>
                    @foreach ($user_projects as $key=>$user_project )
                    <option value="{{ $key}}">{{ $user_project}}</option>
                    @endforeach
                </select>
            </li>
            <li>
                <label for="datewise">Date</label>
                <select name="datewise" data-filter="date" id="datewise" title="Select Period"
                    class="form-select show-tick asj-timesheet-filter">
                    <option value="-1">Anytime</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="this_week">This week (Mon - Sun)</option>
                    <option value="last_week">Last week (Mon - Sun)</option>
                    <option value="this_month">This month</option>
                    <option value="last_month">Last month</option>
                    <option value="custom">Custom date range</option>
                </select>
            </li>
            <li class="date-range" style="border: 1px solid #777;padding: 9px; margin: 5px; display:none;">
                <div class="col-md-12"><label for="start_range">Start Date</label><input type="date" data-filter="range"  id="start_range" name="start_range" class="asj-filter-range form-control"></div>
                <div class="col-md-12"><label for="end_range">Start Date</label><input type="date" data-filter="range" id="end_range" name="end_range" class="asj-filter-range form-control"></div>

            </li>

        </ul>
    </div>
    <!-- /#sidebar-wrapper -->


@endsection
@push('script-page')
<script>
    $(function () {
    // global app configuration object
    var config = {
        routes: {
            filter: "{{ route('timesheet.entries.filter') }}"
        }
    };

    var start = document.getElementById('start_range');
    var end = document.getElementById('end_range');

    $(document).on("change", ".asj-filter-range", function(){

        var project = $('select[data-filter="project"]').val();
        var date = $('select[data-filter="date"]').val();

        var start_range = $("#start_range").val();
        var end_range = $("#end_range").val();

        console.log("start_range ",start_range);
        console.log("end_range ",end_range);

        if($(this).attr('id') == 'start_range')
        {
            if(start_range > end_range) { $("#end_range").val(start_range)}
        }
        if($(this).attr('id') == 'end_range')
        {
            if(end_range < start_range) { $("#start_range").val(end_range)}
        }

        var  start_range_val = $("#start_range").val();
        var  end_range_val = $("#end_range").val();


        console.log("start_range_val ",start_range_val);
        console.log("end_range_val ",end_range_val);

        $.ajax({
            url: config.routes.filter,
            data: {
                date: date,
                project: project,
                start_range: start_range_val,
                end_range: end_range_val,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            success: function (result) {
                $(".timesheet-entries").html("").html(result[0]);
                let showMoreButtonStatus = result[1];
                if(showMoreButtonStatus)
                {
                  let lastDate = result[2];
                  //let endDate = result[3];
                  $('#showMore').attr("data-lastDate", lastDate);
                  $('#showMore').attr("data-endDate", start_range);
                  $('#showMore').show();
                }else{
                  $('#showMore').attr("data-lastDate", '');
                  $('#showMore').hide();
                }
            },
            error: function (e) {
                console.log("error", e);
            },
        });

    })


    // filter records
    $(document).on("change", ".asj-timesheet-filter", function () {
        var project = $('select[data-filter="project"]').val();
        var date = $('select[data-filter="date"]').val();

        var dateRangeOpen = ($(this).val() == 'custom') ? true : false;

        if( date == 'custom') {
            $(".date-range").show();

            if(dateRangeOpen) {
                $("#start_range").val(new Date().toJSON().slice(0,10));
                $("#end_range").val(new Date().toJSON().slice(0,10));
            }

            var  start_range_val = $("#start_range").val();
            var  end_range_val = $("#end_range").val();
        } else {
            $(".date-range").hide();

            $("#start_range").val('');
            $("#end_range").val('');
        }


        $.ajax({
            url: config.routes.filter,
            data: {
                date: date,
                project: project,
                start_range: start_range_val,
                end_range: end_range_val,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            success: function (result) {
                $(".timesheet-entries").html("").html(result[0]);
                let showMoreButtonStatus = result[1];
                if(showMoreButtonStatus)
                {
                  let lastDate = result[2];
                  let endDate = result[3];
                  $('#showMore').attr("data-lastDate", lastDate);
                  $('#showMore').attr("data-endDate", endDate);
                  $('#showMore').show();
                }else{
                  $('#showMore').attr("data-lastDate", '');
                  $('#showMore').hide();
                }
            },
            error: function (e) {
                console.log("error", e);
            },
        });
    });
})
</script>

@endpush
