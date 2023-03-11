
@extends('layouts.admin')

@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp

@section('page-title')
    {{__('Project Details')}} <small>(<a href="{{ route('projects.show',$projects->id) }}" target="_blank" title="{{$projects->name}}">{{$projects->name}}</a>)</small>
@endsection

@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Project Details')}}</h5>
    </div>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('public/custom/css/datatables.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('project_report.index')}}">{{__('Project Report')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Project Details')}}</li>
@endsection

@section('action-button')
    <a href="#" onclick="saveAsPDF()" class="btn btn-sm btn-primary" style="margin-right: 25px;" data-bs-toggle="tooltip" data-title="{{('Download')}}"  data-toggle="popover" title="{{ __('Download') }}">
    <i class="ti ti-file-download"></i>
    </a>
@endsection

@section('content')
<style>.hide { display: none;}</style>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div  class= "row" id="printableArea">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Overview')}}</h5>
                        </div>
                        <div class="card-body" style="min-height: 280px;">
                            <div class="row align-items-center">
                                <div class="col-7">
                                    <table class="table" id="pc-dt-simple">
                                        <tbody>
                                            <tr class="border-0" >
                                                <th class="border-0" >{{ __('Project Name')}}:</th>
                                                <td class="border-0">{{$projects->name}}</td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">{{ __('Project Status')}}:</th>
                                                <td class="border-0">
                                                    @if($projects->status == 'completed')
                                                        <div class="badge  bg-success p-2 px-3 rounded"> {{ __('Finished')}}</div>
                                                    @elseif($projects->status == 'on_going')
                                                        <div class="badge  bg-secondary p-2 px-3 rounded">{{ __('Ongoing')}}</div>
                                                    @else
                                                        <div class="badge bg-warning p-2 px-3 rounded">{{ __('OnHold')}}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr role="row">
                                                <th class="border-0">{{ __('Start Date') }}:</th>
                                                <td class="border-0">{{($projects->start_date)}}</td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">{{ __('Due Date') }}:</th>
                                                <td class="border-0">{{($projects->due_date)}}</td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">{{ __('Total Members')}}:</th>
                                                <td class="border-0">{{(int) $projects->users->count()}}</td>
                                            </tr>
                                        </tbody>
                                   </table>
                                </div>
                                <div class="col-5 ">
                                    @php
                                        $task_percentage = $projects    ->project_progress()['percentage'];
                                        $data =trim($task_percentage,'%');
                                        $status = $data > 0 && $data <= 25 ? 'red' : ($data > 25 && $data <= 50 ? 'orange' : ($data > 50 && $data <= 75 ? 'blue' : ($data > 75 && $data <= 100 ? 'green' : '')));
                                    @endphp


                                    <div class="circular-progressbar p-0">
                                        <div class="flex-wrapper">
                                            <div class="single-chart">
                                                <svg viewBox="0 0 36 36"
                                                    class="circular-chart orange {{ $status }}">
                                                    <path class="circle-bg" d="M18 2.0845
                                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                    <path class="circle"
                                                        stroke-dasharray="{{ $data }}, 100" d="M18 2.0845
                                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                    <text x="18" y="20.35"
                                                        class="percentage">{{ $data }}%</text>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $mile_percentage = $projects->project_milestone_progress()['percentage'];
                    $mile_percentage =trim($mile_percentage,'%');
                @endphp

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="padding: 25px 35px !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="row">
                                    <h5 class="mb-0">{{ __('Milestone Progress') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <div id="milestone-chart" class="chart-canvas chartjs-render-monitor" height="150"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header" style="padding: 25px 35px !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="row">
                                    <h5 class="mb-0">{{ __('Task Priority') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="min-height: 280px;">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <div id="chart_priority" class="chart-canvas chartjs-render-monitor" height="150"></div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-5">
                    <div class="card">
                      <div class="card-header">
                          <div class="float-end">
                              <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                      class=""></i></a>
                          </div>
                          <h5>{{ __('Task Status') }}</h5>
                      </div>
                          <div class="card-body" style="min-height: 280px;">
                              <div class="row align-items-center">
                                  <div class="col-12">
                                      <div id="chart"></div>
                                  </div>

                              </div>
                          </div>
                      </div>
                  </div>


                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                    class=""></i></a>
                            </div>
                            <h5>{{ __('Hours Estimation') }}</h5>
                        </div>
                        <div class="card-body" style="min-height: 280px;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div id="chart-hours"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-5">
                    <div class="card">
                           <div class="card-header">
                                <h5>{{ __('Users') }}</h5>
                            </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive" style="height: 269px; overflow:auto">
                            <table class=" table">
                                <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Assigned Tasks')}}</th>
                                        <th>{{__('Done Tasks')}}</th>
                                        <th>{{__('Logged Hours')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php

                                    @endphp
                                    @foreach($projects->users as $user)

                                    @php
                                        $hours_format_number = 0;
                                        $total_hours = 0;
                                        $hourdiff_late = 0;
                                        $esti_late_hour =0;
                                        $esti_late_hour_chart=0;


                                        $total_user_task = App\Models\Task::where('project_id',$projects->id)->whereRaw("FIND_IN_SET(?,  assign_to) > 0", [$user->id])->get()->count();

                                        $all_task = App\Models\Task::where('project_id',$projects->id)->whereRaw("FIND_IN_SET(?,  assign_to) > 0", [$user->id])->get();

                                        $total_complete_task = App\Models\Task::join('projectstages','projectstages.id','=','tasks.stage')->where('project_id','=',$projects->id)->where('stage',4)->where('assign_to','=',$user->id)->get()->count();

                                        $logged_hours = 0;
                                        $timesheets = App\Models\Timesheet::where('project_id',$projects->id)->where('user_id' ,$user->id)->sum('total_mins');

                                        $logged_hours = $timesheets/60;
                                        $hours_format_number = number_format($logged_hours, 2, '.', '');

                                        //dd($timesheets);

                                    @endphp

                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$total_user_task}}</td>
                                        <td>{{$total_complete_task}}</td>
                                        <td>{{$hours_format_number}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-7">
                    <div class="card">
                           <div class="card-header">
                                    <h5>{{ __('Milestones') }}</h5>
                                </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive" style="height: 269px; overflow:auto">
                    <table class=" table " >
                        <thead>
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Progress')}}</th>
                                <th> {{__('Cost')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Start Date')}}</th>
                                <th> {{__('End Date')}}</th>
                            </tr>
                        </thead>
                         <tbody>
                           @foreach($projects->milestones as $milestone)
                            <tr>
                               <td>{{$milestone->title}}</td>
                               <td>
                               <div class="progress_wrapper">
                                           <div class="progress">
                                              <div class="progress-bar" role="progressbar"  style="width: {{ $milestone->progress }}px;"
                                                 aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                           </div>
                                           <div class="progress_labels">
                                              <div class="total_progress">

                                                 <strong> {{ $milestone->progress }}%</strong>
                                              </div>

                                           </div>
                                        </div>
                                        </td>
                               <td>{{$milestone->cost}}</td>
                               <td> @if($milestone->status == 'complete')
                                                    <label class="badge bg-success p-2 px-3 rounded">{{__('Complete')}}</label>
                                                @else
                                                    <label class="badge bg-warning p-2 px-3 rounded">{{__('Incomplete')}}</label>
                                                @endif</td>
                               <td>{{$milestone->start_date}}</td>
                               <td>{{$milestone->due_date}}</td>


                            </tr>
                             @endforeach

                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
            </div>
        </div>

        @if(\Auth::user()->type !='employee')

        <div class="row">

            <div class="col-md-12 mt-3 mb-2 row d-sm-flex align-items-center justify-content-end" id="show_filter" >
                {{-- @if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client') --}}
                    <div class="col-3">
                        <select class="selectpicker select" name="all_users[]" id="all_users" multiple>
                            <option value="" class="">{{ __('All Users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" >{{ $user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                {{-- @endif --}}

                <div class="col-2">
                    <select class=" form-select" name="milestone_id" id="milestone_id">
                        <option value="" class="px-4">{{ __('All Milestones') }}</option>
                        @foreach ($milestones as $milestone)
                        <option value="{{ $milestone->id }}">{{ $milestone->title }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <select class="select form-select" name="stage" id="stage">
                        <option value="" class="px-4">{{ __('All Status') }}</option>
                        @foreach ($stages as $stage)
                            <option value="{{ $stage->id }}">{{ __($stage->name) }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <select class="select form-select"  name="priority" id="priority">
                        <option value="" class="px-4">{{ __('All Priority') }}</option>
                        <option value="low">{{ __('Low') }}</option>
                        <option value="medium">{{ __('Medium') }}</option>
                        <option value="high">{{ __('High') }}</option>
                    </select>
                </div>
                <div class="col-1">
                <button class=" btn btn-primary btn-filter apply">{{ __('Apply') }}</button>
            </div>
            <div class="col-1">

                <button class=" btn btn-primary mx-2 btn-filter apply">
                    <a href="{{ route('project_report.export',$projects->id)}}" class="text-white">
                        {{ __('Export') }}
                    </a>
                </button>
            </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-3 mx-2">
                        <div class="row">
                            <div class="col-md-12 mt-2">

                                <div class="table-responsive">
                                    <table class="table table-centered table-hover mb-0 animated selection-datatable px-4 mt-2"
                                        id="selection-datatable1">
                                        <thead>
                                            <th>{{ __('Task Name') }}</th>
                                            <th>{{ __('Milestone') }}</th>
                                             <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            @if (Auth::user()->type != 'employee')
                                                <th>{{ __('Assigned to') }}</th>
                                            @endif
                                            <th> {{__('Total Logged Hours')}}</th>
                                            <th>{{ __('Priority') }}</th>
                                            <th>{{ __('Status') }}</th>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif
    </div>
</div>

@endsection



@push('script-page')
<script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('public/custom/js/jquery.dataTables.min.js') }}"></script>
   <script>
    var dataTableLang = {
        paginate: {previous: "<i class='fas fa-angle-left' style='margin-bottom: 20px;'>", next: "<i class='fas fa-angle-right'>"},
        lengthMenu: "{{__('Show')}} _MENU_ {{__('entries')}}",
        zeroRecords: "{{__('No data available in table.')}}",
        info: "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
        infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
        infoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
        search: "{{__('Search:')}}",
        thousands: ",",
        loadingRecords: "{{ __('Loading...') }}",
        processing: "{{ __('Processing...') }}"
    }

    </script>

<script>
    var filename = $('#chart-hours').val();

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,

            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A2'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

<script>
    $(document).ready(function() {
        var table = $("#selection-datatable1").DataTable({
                        order: [],
                        select: {
                            style: "multi"
                        },
                        "language": dataTableLang,
                        drawCallback: function() {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                        }
                    });
        $(document).on("click", ".btn-filter", function() {
            getData();
        });

        function getData() {
        table.clear().draw();
            $("#selection-datatable1 tbody tr").html(
                '<td colspan="11" class="text-center"> {{ __('Loading ...') }}</td>');

        var data = {
                assign_to: $("#all_users").val(),
                priority: $("#priority").val(),
                due_date_order: $("#due_date_order").val(),
                milestone_id:  $("#milestone_id").val(),
                start_date: $("#start_date").val(),
                due_date:  $("#due_date").val(),
                stage: $("#stage").val(),
            };


            $.ajax({
                url: '{{ route('tasks.report.ajaxdata', [$projects->id]) }}',
                type: 'POST',
                data: data,
                success: function(data) {
                    table.rows.add(data.data).draw(true);
                    loadConfirm();
                },
                error: function(data) {
                    show_toastr('Info', data.error, 'error')
                }
            }  )
        }
        getData();
    });
</script>


<script>

//================================== Milestone Progress ====================
(function () {
    var options = {
        series: [{!! json_encode($mile_percentage) !!}],
        chart: {
            height: 475,
            type: 'radialBar',
            offsetY: -20,
            sparkline: {
                enabled: true
            }
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                track: {
                    background: "#e7e7e7",
                    strokeWidth: '97%',
                    margin: 5, // margin is in pixels
                },
                dataLabels: {
                    name: {
                        show: true
                    },
                    value: {
                        offsetY: -50,
                        fontSize: '20px'
                    }
                }
            }
        },
        grid: {
            padding: {
                top: -10
            }
        },
        colors: ["#51459d"],
        labels: ['Progress'],
    };
    var chart = new ApexCharts(document.querySelector("#milestone-chart"), options);
    chart.render();
})();



// ========================================== Task Priority ===========================

var options = {
          series: [{
          data: {!! json_encode($arrProcessPer_priority) !!}
        }],
          chart: {
          height: 210,
          type: 'bar',
        },
        plotOptions: {
          bar: {

            columnWidth: '50%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true
        },
        xaxis: {
          categories: {!! json_encode($arrProcess_Label_priority) !!},
          labels: {
            style: {
              colors: {!! json_encode($chartData['color']) !!},

            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart_priority"), options);
        chart.render();




//===================== Hour Chart =============================================================

    var options = {
          series: [{
           data: [{!! json_encode($esti_logged_hour_chart) !!},{!! json_encode($logged_hour_chart) !!}],

        }],
          chart: {
          height: 210,
          type: 'bar',
        },
//         tooltip: {
//     custom: function({ series, seriesIndex, dataPointIndex, w }) {
//       return (
//         '<div class="arrow_box">' +
//         "<span>" +
//         w.globals.labels[dataPointIndex] +
//         ": " +
//         series[seriesIndex][dataPointIndex] +
//         "</span>" +
//         "</div>"
//       );
//     }
//   },
        colors: ['#963aff','#ffa21d'],
        plotOptions: {
          bar: {
                horizontal: true,
                columnWidth: '30%',
                distributed: true,
          }
        },
        dataLabels: {
          enabled: true
        },
        legend: {
          show: true
        },
        xaxis: {
          categories: ["Estimated Hours","Logged Hours "],

        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-hours"), options);
    chart.render();


    // =============================  Task Status  ===========================
    var options = {
          series:  {!! json_encode($arrProcessPer_status_task) !!},
          chart: {
          width: 380,
          type: 'pie',
        },
        color: {!! json_encode($chartData['color']) !!},
        labels:{!! json_encode($arrProcess_Label_status_tasks) !!},
        responsive: [{
          breakpoint: 480,
          options: {
                chart: {
                    width: 100
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script>

@endpush
