

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
        <div class="col-sm-12">
            <div class="row">
                <?php if(\Auth::user()->type == 'Project Manager'): ?>
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class=""><?php echo e(__('Team Logged Hours')); ?></h5>
                                <h6 class="last-day-text"><?php echo e(__('Last 7 Days')); ?></h6>
                            </div>
                            <div class="card-body">
                                <canvas id="team-logged-hours" height="200" class="p-3"></canvas>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-xxl-7">
                    <div class="row">

                        <?php if(Auth::user()->type == 'company' || Auth::user()->type == 'client'): ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body" style="min-height: 222px;">
                                        <div class="theme-avtar bg-warning mb-3">
                                            <i class="ti ti-receipt-2"></i>
                                        </div>
                                        <h6 class="mb-3"><?php echo e(__('Total Invoice')); ?></h6>
                                        <h3 class="mb-0">
                                            <?php echo e($invoice['total_invoice'] > 100 ? '99+' : $invoice['total_invoice']); ?>

                                            <span class="<?php echo e($label2); ?> text-sm"><?php echo e($invoice_percentage); ?>%</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(Auth::user()->type == 'company'): ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body" style="min-height: 222px;">
                                        <div class="theme-avtar bg-danger mb-3">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <h6 class="mb-3"><?php echo e(__('Total Staff')); ?></h6>
                                        <h3 class="mb-0"><?php echo e($users['staff'] > 100 ? '99+' : $users['staff']); ?></h3>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(Auth::user()->type == 'client'): ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="theme-avtar bg-info mb-3">
                                            <i class="ti ti-click"></i>
                                        </div>
                                        <h6 class="mb-3"><?php echo e(__('Total Project Budget')); ?></h6>
                                        <h3 class="mb-0"><?php echo e(Auth::user()->priceFormat($project['project_budget'])); ?><span
                                                class="<?php echo e($label3); ?> text-sm"><?php echo e($client_project_budget_due_per); ?>%</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(Auth::user()->type == 'company' || Auth::user()->type == 'client'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('Top Due Payment')); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Invoice ID')); ?></th>
                                                <th><?php echo e(__('Due Amount')); ?></th>
                                                <th><?php echo e(__('Due Date')); ?></th>
                                                <th><?php echo e(__('Action')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php $__empty_1 = true; $__currentLoopData = $top_due_invoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="Id">
                                                        <a
                                                            href="<?php echo e(route('invoices.show', $invoice->id)); ?>"><?php echo e(App\Models\Utility::invoiceNumberFormat($invoice->id)); ?></a>
                                                    </td>
                                                    <td><?php echo e(Auth::user()->priceFormat($invoice->getDue())); ?></td>
                                                    <td><?php echo e(Auth::user()->dateFormat($invoice->due_date)); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('invoices.show', $invoice->id)); ?>"
                                                            class="mx-3 btn-warning btn btn-sm d-inline-flex align-items-end"
                                                            data-bs-whatever="<?php echo e(__('View')); ?>" data-bs-toggle="tooltip"
                                                            title="<?php echo e(__('View')); ?>"
                                                            data-bs-original-title="<?php echo e(__('View')); ?>">
                                                            <span class="text-white">
                                                                <i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr class="text-center">
                                                    <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(Auth::user()->type == 'employee' || Auth::user()->type == 'Project Manager'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('Assigned Project')); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Project Name')); ?></th>
                                                <th><?php echo e(__('Total Task')); ?></th>
                                                <th><?php echo e(__('Due Date')); ?></th>
                                                <th><?php echo e(__('Project Report')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php $__empty_1 = true; $__currentLoopData = $project['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                                        <a href="<?php echo e(route('projects.show', $project->id)); ?>" title="<?php echo e($project->name); ?>" >  <?php echo e($project->name); ?></a>
                                                    </td>
                                                    <td><?php echo e($total_task); ?></td>
                                                    <td><?php echo e(Auth::user()->dateFormat($project->due_date)); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('project_report.show', $project->id)); ?>"
                                                            class="mx-3 btn-warning btn btn-sm d-inline-flex align-items-end"
                                                            data-bs-whatever="<?php echo e(__('View')); ?>" data-bs-toggle="tooltip"
                                                            title="<?php echo e(__('View')); ?>"
                                                            data-bs-original-title="<?php echo e(__('View')); ?>">
                                                            <span class="text-white">
                                                                <i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr class="text-center">
                                                    <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Top Due Task')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Task Name')); ?></th>
                                            <th><?php echo e(__('Assign To')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $__empty_1 = true; $__currentLoopData = $top_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="id-web">
                                                    <?php echo e($top_task->title); ?>

                                                </td>
                                                <td>
                                                    <?php if(\Auth::user()->type != 'client' && \Auth::user()->type != 'company'): ?>
                                                        <?php echo e($top_task->project_name); ?>

                                                    <?php else: ?>
                                                        <?php echo e(isset($top_task->task_user->name)); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($top_task->stage_name == 'In Progress'): ?>
                                                        <div class="badge bg-warning p-2 px-3 rounded">
                                                            <?php echo e($top_task->stage_name); ?></div>
                                                    <?php elseif($top_task->stage_name == 'Bugs'): ?>
                                                        <div class="badge bg-danger p-2 px-3 rounded">
                                                            <?php echo e($top_task->stage_name); ?></div>
                                                    <?php elseif($top_task->stage_name == 'To Do'): ?>
                                                        <div class="badge bg-info p-2 px-3 rounded">
                                                            <?php echo e($top_task->stage_name); ?></div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="text-center">
                                                <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(\Auth::user()->type != 'super admin'): ?>
                    <div class="col-xxl-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class=""><?php echo e(__('My Logged Hours')); ?></h5>
                                <h6 class="last-day-text"><?php echo e(__('Last 7 Days')); ?></h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chart-sales" height="200" class="p-3"></canvas>
                            </div>
                        </div>
                <?php endif; ?>
                <?php if(\Auth::user()->type != 'super admin'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Project Status')); ?></h5>
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
                                        <div class="tx-gray-500 small"><?php echo e(__('On Going')); ?></div>
                                        <div class="font-weight-bold"><?php echo e(number_format($projectData['on_going'], 2)); ?> %
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="tx-gray-500 small"><?php echo e(__('On Hold')); ?></div>
                                        <div class="font-weight-bold"><?php echo e(number_format($projectData['on_hold'], 2)); ?> %
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="tx-gray-500 small"><?php echo e(__('Completed')); ?></div>
                                        <div class="font-weight-bold"><?php echo e(number_format($projectData['completed'], 2)); ?> %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- [ sample-page ] end -->
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\teamwork\resources\views/dashboard/index.blade.php ENDPATH**/ ?>