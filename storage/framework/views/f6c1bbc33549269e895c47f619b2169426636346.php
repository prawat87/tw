

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/apexcharts.min.js')); ?>"></script>
    <script>
        var teamLoggedHoursChart = (function() {
            // var $chart = $('#chart-sales');
            var $teamLoggedChart = $('#team-logged-hours');

            function initTeamLog($this) {
                var teamLoggedHoursChart = new Chart($this, {
                    type: 'bar',

                    data: {
                        labels: <?php echo json_encode($teamLoggedData['label']); ?>,
                        datasets: <?php echo json_encode($teamLoggedData['dataset']); ?>

                    },
                    options: {
                        title: {
                            display: true,
                            text: "Total Logged Hours: " + <?php echo $teamLoggedData['total_logged_hrs']; ?>

                        },
                        responsive: true,
                        tooltip: {
                            mode: 'index'
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        legend: {
                            display: true,

                        },
                        scales: {

                            yAxes: [{
                                type: 'linear',

                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Hours"
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        if (value % 1 === 0) {
                                            return value;
                                        }
                                    }
                                },

                            }]
                        },

                    },
                });

                $this.data('chart', teamLoggedHoursChart);
            };
            if ($teamLoggedChart.length) {
                initTeamLog($teamLoggedChart);
            }
        })();

        var loggedHoursChart = (function() {
            var $chart = $('#chart-sales');

            function init($this) {
                var loggedHoursChart = new Chart($this, {
                    type: 'line',

                    data: {
                        labels: <?php echo json_encode($taskData['label']); ?>,
                        datasets: <?php echo json_encode($taskData['dataset']); ?>

                    },
                    options: {
                        title: {
                            display: true,
                            text: "Total Logged Hours: " + <?php echo $taskData['total_logged_hrs']; ?>

                        },
                        responsive: true,
                        tooltip: {
                            mode: 'index'
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        legend: {
                            display: true,

                        },
                        scales: {

                            yAxes: [{
                                type: 'linear',

                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Hours"
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        if (value % 1 === 0) {
                                            return value;
                                        }
                                    }
                                },

                            }]
                        },

                    },
                });
                $this.data('chart', loggedHoursChart);
            };
            if ($chart.length) {
                init($chart);
            }
        })();


        (function() {
            var options = {
                chart: {
                    height: 230,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },

                series: <?php echo json_encode(array_values($projectData)); ?>,
                colors: ["rgb(24, 45, 189)", "#f36a5b", "rgb(111, 217, 67)"],
                labels: <?php echo json_encode($project_status); ?>,
                legend: {
                    show: true
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                },
            };
            var chart = new ApexCharts(document.querySelector("#chart-doughnut"), options);
            chart.render();
        })();
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <?php
        $lead_percentage = $lead['lead_percentage'];
        $project_percentage = $project['project_percentage'];
        $client_project_budget_due_per = @$project['client_project_budget_due_per'];
        $invoice_percentage = @$invoice['invoice_percentage'];

        $label = '';
        if ($lead_percentage <= 15) {
            $label = 'text-danger';
        } elseif ($lead_percentage > 15 && $lead_percentage <= 33) {
            $label = 'text-warning';
        } elseif ($lead_percentage > 33 && $lead_percentage <= 70) {
            $label = 'text-primary';
        } else {
            $label = 'text-success';
        }

        $label1 = '';
        if ($project_percentage <= 15) {
            $label1 = 'text-danger';
        } elseif ($project_percentage > 15 && $project_percentage <= 33) {
            $label1 = 'text-warning';
        } elseif ($project_percentage > 33 && $project_percentage <= 70) {
            $label1 = 'text-primary';
        } else {
            $label1 = 'text-success';
        }

        $label2 = '';
        if ($invoice_percentage <= 15) {
            $label2 = 'text-danger';
        } elseif ($invoice_percentage > 15 && $invoice_percentage <= 33) {
            $label2 = 'text-warning';
        } elseif ($invoice_percentage > 33 && $invoice_percentage <= 70) {
            $label2 = 'text-primary';
        } else {
            $label2 = 'text-success';
        }

        $label3 = '';
        if ($client_project_budget_due_per <= 15) {
            $label3 = 'text-danger';
        } elseif ($client_project_budget_due_per > 15 && $client_project_budget_due_per <= 33) {
            $label3 = 'text-warning';
        } elseif ($client_project_budget_due_per > 33 && $client_project_budget_due_per <= 70) {
            $label3 = 'text-primary';
        } else {
            $label3 = 'text-success';
        }
    ?>

    <div class="row">
        <!-- [ sample-page ] start -->
        <h2>Under Construction</h2>
        <?php /* ?><div class="col-sm-12">
            <div class="row">
                @if (\Auth::user()->type == 'Project Manager')
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="">{{ __('Team Logged Hours') }}</h5>
                                <h6 class="last-day-text">{{ __('Last 7 Days') }}</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="team-logged-hours" height="200" class="p-3"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-xxl-7">
                    <div class="row">

                        @if (Auth::user()->type == 'company' || Auth::user()->type == 'client')
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body" style="min-height: 222px;">
                                        <div class="theme-avtar bg-warning mb-3">
                                            <i class="ti ti-receipt-2"></i>
                                        </div>
                                        <h6 class="mb-3">{{ __('Total Invoice') }}</h6>
                                        <h3 class="mb-0">
                                            {{ $invoice['total_invoice'] > 100 ? '99+' : $invoice['total_invoice'] }}
                                            <span class="{{ $label2 }} text-sm">{{ $invoice_percentage }}%</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (Auth::user()->type == 'company')
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body" style="min-height: 222px;">
                                        <div class="theme-avtar bg-danger mb-3">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <h6 class="mb-3">{{ __('Total Staff') }}</h6>
                                        <h3 class="mb-0">{{ $users['staff'] > 100 ? '99+' : $users['staff'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (Auth::user()->type == 'client')
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="theme-avtar bg-info mb-3">
                                            <i class="ti ti-click"></i>
                                        </div>
                                        <h6 class="mb-3">{{ __('Total Project Budget') }}</h6>
                                        <h3 class="mb-0">{{ Auth::user()->priceFormat($project['project_budget']) }}<span
                                                class="{{ $label3 }} text-sm">{{ $client_project_budget_due_per }}%</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (Auth::user()->type == 'company' || Auth::user()->type == 'client')
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Top Due Payment') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice ID') }}</th>
                                                <th>{{ __('Due Amount') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @forelse($top_due_invoice as $invoice)
                                                <tr>
                                                    <td class="Id">
                                                        <a
                                                            href="{{ route('invoices.show', $invoice->id) }}">{{ App\Models\Utility::invoiceNumberFormat($invoice->id) }}</a>
                                                    </td>
                                                    <td>{{ Auth::user()->priceFormat($invoice->getDue()) }}</td>
                                                    <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                                    <td>
                                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                                            class="mx-3 btn-warning btn btn-sm d-inline-flex align-items-end"
                                                            data-bs-whatever="{{ __('View') }}" data-bs-toggle="tooltip"
                                                            title="{{ __('View') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <span class="text-white">
                                                                <i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="4">{{ __('No Data Found.!') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (Auth::user()->type == 'employee' || Auth::user()->type == 'Project Manager')
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Assigned Project') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Project Name') }}</th>
                                                <th>{{ __('Total Task') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th>{{ __('Project Report') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @forelse($project['projects'] as $project)
                                                <?php
                                                    $datetime1 = new DateTime($project->due_date);
                                                    $datetime2 = new DateTime(date('Y-m-d'));
                                                    $interval = $datetime1->diff($datetime2);
                                                    $days = $interval->format('%a');

                                                    $project_last_stage = $project->project_last_stage($project->id) ? $project->project_last_stage($project->id)->id : '';
                                                    $total_task = $project->project_total_task($project->id);
                                                    $completed_task = $project->project_complete_task($project->id, $project_last_stage);
                                                    $remain_task = $total_task - $completed_task;
                                                ?>
                                                <tr>
                                                    <td class="id-web">
                                                        <a href="{{ route('projects.show', $project->id) }}" title="{{ $project->name }}" >  {{ $project->name }}</a>
                                                    </td>
                                                    <td>{{ $total_task }}</td>
                                                    <td>{{ Auth::user()->dateFormat($project->due_date) }}</td>
                                                    <td>
                                                        <a href="{{ route('project_report.show', $project->id) }}"
                                                            class="mx-3 btn-warning btn btn-sm d-inline-flex align-items-end"
                                                            data-bs-whatever="{{ __('View') }}" data-bs-toggle="tooltip"
                                                            title="{{ __('View') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <span class="text-white">
                                                                <i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="4">{{ __('No Data Found.!') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Top Due Task') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Task Name') }}</th>
                                            <th>{{ __('Assign To') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @forelse($top_tasks as $top_task)
                                            <tr>
                                                <td class="id-web">
                                                    {{ $top_task->title }}
                                                </td>
                                                <td>
                                                    @if (\Auth::user()->type != 'client' && \Auth::user()->type != 'company')
                                                        {{ $top_task->project_name }}
                                                    @else
                                                        {{ isset($top_task->task_user->name) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($top_task->stage_name == 'In Progress')
                                                        <div class="badge bg-warning p-2 px-3 rounded">
                                                            {{ $top_task->stage_name }}</div>
                                                    @elseif($top_task->stage_name == 'Bugs')
                                                        <div class="badge bg-danger p-2 px-3 rounded">
                                                            {{ $top_task->stage_name }}</div>
                                                    @elseif($top_task->stage_name == 'To Do')
                                                        <div class="badge bg-info p-2 px-3 rounded">
                                                            {{ $top_task->stage_name }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="4">{{ __('No Data Found.!') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if (\Auth::user()->type != 'super admin')
                    <div class="col-xxl-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="">{{ __('My Logged Hours') }}</h5>
                                <h6 class="last-day-text">{{ __('Last 7 Days') }}</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chart-sales" height="200" class="p-3"></canvas>
                            </div>
                        </div>
                @endif
                @if (\Auth::user()->type != 'super admin')
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Project Status') }}</h5>
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
                                <div id="chart-doughnut" class="chart-canvas chartjs-render-monitor" height="150">
                                </div>
                            </div>
                            <div class="project-details">
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="tx-gray-500 small">{{ __('On Going') }}</div>
                                        <div class="font-weight-bold">{{ number_format($projectData['on_going'], 2) }} %
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="tx-gray-500 small">{{ __('On Hold') }}</div>
                                        <div class="font-weight-bold">{{ number_format($projectData['on_hold'], 2) }} %
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="tx-gray-500 small">{{ __('Completed') }}</div>
                                        <div class="font-weight-bold">{{ number_format($projectData['completed'], 2) }} %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endif
        </div> <?php */ ?>
    </div>
    <!-- [ sample-page ] end -->
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\switches\resources\views/dashboard/index.blade.php ENDPATH**/ ?>