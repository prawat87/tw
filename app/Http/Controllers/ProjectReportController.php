<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Projects;
use App\Models\User;
use App\Models\Utility;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Models\Milestone;
use App\Models\Timesheet;
use App\Models\Projectstages;
use App\Models\Userprojects;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\task_reportExport;



class ProjectReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage project')) {
            $user = Auth::user();
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
            } else {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();
            }

            if ($user->type == 'client') {
                $projects = Projects::where('client', '=', $user->id)->get();
            } else {
                $projects = $user->projects;
            }

            $project_status = Projects::$project_status;

            return view('project_report.index', compact('projects', 'project_status', 'users'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function ajax_data(Request $request)
    {
        $user = Auth::user();

        if ($user->type == 'client') {
            $projects = Projects::where('client', '=', $user->id);
        } elseif (\Auth::user()->type == 'employee' || $user->type == 'Project Manager') {
            $projects = Projects::select('projects.*')->leftjoin('userprojects', 'userprojects.project_id', 'projects.id')->where('userprojects.user_id', '=', $user->id);
        } else {
            $projects = Projects::where('created_by', '=', $user->id);
        }

        if ($request->all_users) {

            unset($projects);
            $UserEmailTemp = Userprojects::where('user_id', $request->all_users)->pluck('project_id');
            $projects = Projects::whereIn('id', $UserEmailTemp);
        }
        if ($request->status) {
            $projects->where('status', '=', $request->status);
        }

        if ($request->start_date) {
            $projects->where('start_date', '=', $request->start_date);
        }

        if ($request->due_date) {
            $projects->where('due_date', '=', $request->end_date);
        }

        $projects = $projects->get();



        $data = [];
        foreach ($projects as $project) {

            $tmp = [];
            $tmp['id'] = $project->id;
            $tmp['name'] = $project->name;
            $tmp['start_date'] = $project->start_date;
            $tmp['end_date'] = $project->due_date;

            $tmp['members'] = '<div class="user-group mx-2">';

            foreach ($project->users as $user) {
                if ($user->type == 'company') continue;
                $logo = Utility::get_file('productimages/');
                $logo_project = Utility::get_file('avatar/');
                if ($user->avatar == '' && empty($user->avatar)) {
                    $path_1 =  $logo_project . 'avatar.png';
                }
                $path =  ($user->avatar) ? $logo . $user->avatar : $path_1;
                $avatar = $user->avatar ? 'src="' . $path . '"' : 'src="' . $path_1 . '"';
                if ($user->is_active) {
                    $tmp['members'] .=
                        '<a href="#" class="img_group" data-toggle="tooltip" data-placement="top" title=" ' . $user->name . '">
                        <img alt="' . $user->name . '" ' . $avatar . '/></a>';
                }
            }
            $tmp['members'] .=   '</div>';
            $percentage = $project->project_progress();

            $tmp['Progress'] =
                '<div class="progress_wrapper">
                <div class="progress">
                    <div class="progress-bar" role="progressbar"
                    style="width:' . $percentage["percentage"] . '"
                        aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                <div class="progress_labels">
                    <div class="total_progress">

                        <strong>' . $percentage["percentage"] . '</strong>
                    </div>

                </div>
            </div>';

            if ($project->status == 'completed') {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-success">' . 'Finished' . '</span>';
            } elseif ($project->status == 'on_going') {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-secondary">' . 'Ongoing' . '</span>';
            } else {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-warning">' . 'OnHold' . '</span>';
            }

            if (\Auth::user()->type != 'client') {
                $tmp['action'] = '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Project') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                    'project_report.show',
                    [
                        $project->id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a>

                <a data-url="' . route('projects.edit', $project->id) . '" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('Edit Project') . '" data-ajax-popup="true" data-size="lg" data-title="' . __('Edit') . '" style="text:white;"><i class="ti ti-edit text-white"></i></a>';
            } else {
                $tmp['action'] = '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Project') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                    'project_report.show',
                    [
                        $project->id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a>';
            }
            $data[] = array_values($tmp);
        }
        return response()->json(['data' => $data], 200);
    }


    public function getProjectChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration'] && $arrParam['duration'] == 'week') {
            $previous_week = Utility::getFirstSeventhWeekDay(-1);
            foreach ($previous_week['datePeriod'] as $dateObject) {
                $arrDuration[$dateObject->format('Y-m-d')] = $dateObject->format('D');
            }
        }

        $arrTask = [
            'label' => [],
            'color' => [],
        ];


        foreach ($arrDuration as $date => $label) {
            $objProject = Task::select('status', DB::raw('count(*) as total'))->whereDate('updated_at', '=', $date)->groupBy('status');

            if (isset($arrParam['project_id'])) {
                $objProject->where('project_id', '=', $arrParam['project_id']);
            }
            $arrTask['label'][] = __($label);
        }
        return $arrTask;
    }




    public function show(Request $request, $id)
    {

        $user = Auth::user();
        if (\Auth::user()->type == 'super admin') {
            $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
        } else {
            $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();
        }

        if ($user->type == 'client') {
            $projects = Projects::where('client', '=', $user->id)->where('id', $id)->first();
        } elseif (\Auth::user()->type == 'employee') {
            $projects = Projects::select('projects.*')->leftjoin('userprojects', 'userprojects.project_id', 'projects.id')->where('userprojects.user_id', '=', $user->id)->first();

            // dd($project);
        } else {
            $projects = Projects::where('id', $id)->first();
            //$projects = Projects::where('created_by', '=', $user->id)->where('id', $id)->first();

        }
        if ($user) {
            $chartData = $this->getProjectChart(
                [
                    'project_id' => $id,
                    'duration' => 'week',
                ]
            );
            $daysleft = round((((strtotime($user->end_date) - strtotime(date('Y-m-d'))) / 24) / 60) / 60);

            $project_status_task = Projectstages::join("tasks", "tasks.stage", "=", "projectstages.id")->where('tasks.project_id', '=', $id)->groupBy('name')->selectRaw('count(tasks.id) as count, name')->pluck('count', 'name');

            $totaltask = Task::where('project_id', $id)->count();

            $arrProcessPer_status_task = [];
            $arrProcess_Label_status_tasks = [];
            foreach ($project_status_task as $lables => $percentage_stage) {
                $arrProcess_Label_status_tasks[] = $lables;
                if ($totaltask == 0) {
                    $arrProcessPer_status_task[] = 0.00;
                } else {
                    $arrProcessPer_status_task[] = round(($percentage_stage * 100) / $totaltask, 2);
                }
            }

            $project_priority_task = Task::where('project_id', $id)->groupBy('priority')->selectRaw('count(id) as count, priority')->pluck('count', 'priority');

            $arrProcessPer_priority = [];
            $arrProcess_Label_priority = [];
            foreach ($project_priority_task as $lable => $process) {
                $arrProcess_Label_priority[] = $lable;
                if ($totaltask == 0) {
                    $arrProcessPer_priority[] = 0.00;
                } else {
                    $arrProcessPer_priority[] = round(($process * 100) / $totaltask, 2);
                }
            }
            $arrProcessClass = [
                'text-success',
                'text-primary',
                'text-danger',
            ];

            $chartData = app('App\Http\Controllers\ProjectsController')->getProjectChart([
                'duration' => 'week',
            ]);


            $stages = Projectstages::all();

            $milestones = Milestone::where('project_id', $id)->get();

            //Logged Hours

            $logged_hour_chart = 0;
            $total_hour = 0;
            $logged_hour = 0;
            $logged_time = 0;

            $tasks = Task::where('project_id', $id)->get();

            $data = [];
            foreach ($tasks as $task) {
                $timesheets_task = Timesheet::where('task_id', $task->id)->where('project_id', $id)->get();
                foreach ($timesheets_task as $timesheet) {
                    // $date_time = $timesheet->time;
                    // $hours =  date('H', strtotime($date_time));
                    // $minutes =  date('i', strtotime($date_time));
                    // $total_hour = $hours + ($minutes / 60);
                    // $logged_hour += $total_hour;

                    $data_time = $timesheet->total_mins;
                    $logged_time += $data_time / 60;

                    $logged_hour_chart = number_format($logged_time, 2, '.', '');
                }
            }
            //Estimated Hours

            //$esti_logged_hour_chart = Task::where('project_id', $id)->sum('hours');
            $esti_logged_mins_chart = Task::where('project_id', $id)->sum('estimated_mins');
            $esti_logged_hour_chart = number_format($esti_logged_mins_chart / 60, 2, '.', '');
            // dd($user->id);
            // dd($projects);

            return view('project_report.show', compact('user', 'users', 'arrProcessPer_priority', 'arrProcess_Label_priority', 'projects', 'chartData', 'daysleft', 'arrProcessClass', 'milestones', 'logged_hour_chart', 'stages', 'arrProcessPer_status_task', 'arrProcess_Label_status_tasks', 'esti_logged_hour_chart'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function ajax_tasks_report(Request $request, $id)
    {
        $userObj = Auth::user();
        $tasks = Task::where('project_id', '=', $id);


        if ($request->assign_to) {
            // $tasks->whereRaw("find_in_set('" . $request->assign_to . "',assign_to)");
            $tasks->where("assign_to", "LIKE", "%" . implode(",", $request->assign_to) . "%")->orWhere("assign_to", "LIKE", "%-1%");
        }

        if ($request->priority) {
            $tasks->where('priority', '=', $request->priority);
        }

        if ($request->milestone_id) {
            $tasks->where('milestone_id', '=', $request->milestone_id);
        }
        if ($request->stage) {
            $tasks->where('stage', '=', $request->stage);
        }

        if ($request->start_date) {
            $tasks->where('start_date', '=', $request->status);
        }

        if ($request->due_date) {
            $tasks->where('due_date', '=', $request->due_date);
        }

        $tasks = $tasks->get();

        $hour_format_number = 0;
        $total_hour = 0;
        $logged_hour = 0;


        $data = [];
        foreach ($tasks as $task) {
            $timesheets_task = Timesheet::where('project_id', $id)->where('task_id', $task->id)->get();

            // dd($timesheets_task);
            $hour_format_number = 0;
            foreach ($timesheets_task as $timesheet) {

                // $date_time = $timesheet->time;
                // $hours =  date('H', strtotime($date_time));
                // $minutes =  date('i', strtotime($date_time));
                // $total_hour = $hours + ($minutes / 60);
                // $logged_hour += $total_hour;
                // $hour_format_number = number_format($logged_hour, 2, '.', '');


                $date_time = $timesheet->total_mins;
                $logged_hours = $date_time / 60;
                $hour_format_number = number_format($logged_hours, 2, '.', '');
            }

            $tmp = [];
            $tmp['title'] = '<a href="' . route(
                'project.taskboard',
                [
                    $task->project_id,
                ]

            ) . '" class="text-body">' . $task->title . '</a>';

            $tmp['milestone'] = ($milestone = $task->milestone_report()) ? $milestone->title : '';
            $start_date = '<span class="text-body">' . date('Y-m-d', strtotime($task->start_date)) . '</span> ';

            $due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ';
            $tmp['start_date'] = $start_date;
            $tmp['due_date'] = $due_date;

            if (\Auth::user()->type != "employee") {
                $tmp['user_name'] = "";

                if (empty($task->users())) {
                    $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">Test</span>';
                }
                foreach ($task->users() as $user) {
                    // print_r($user);
                    if (isset($user) && $user) {
                        $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">' . $user->name . '</span> ';
                    } else {
                        $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">Test</span>';
                    }
                }

                // if ($request->assign_to == null) {
                //     $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">All</span>';
                // }
            }

            $tmp['logged_hours'] = $hour_format_number;

            if ($task->priority == "high") {
                $tmp['priority'] = '<span class="priority_badge badge bg-danger p-2 px-3 rounded" style="width: 77px;">' . __('High') . '</span>';
            } elseif ($task->priority == "medium") {
                $tmp['priority'] = '<span class="priority_badge badge bg-info p-2 px-3 rounded" style="width: 77px;">' . __('Medium') . '</span>';
            } else {
                $tmp['priority'] = '<span class="priority_badge badge bg-success p-2 px-3 rounded" style="width: 77px;">' . __('Low') . '</span>';
            }

            if ($task->complete == 1) {
                $tmp['stage'] = '<span class="status_badge badge bg-success p-2 px-3 rounded" style="width: 87px;">' . __($task->Projectstages->name) . '</span>';
            } else {
                $tmp['stage'] = '<span class="status_badge badge bg-primary p-2 px-3 rounded" style="width: 87px;">' . __($task->Projectstages->name) . '</span>';
            }

            $data[] = array_values($tmp);

            unset($hour_format_number);
        }
        return response()->json(['data' => $data], 200);
    }

    public function export($id)
    {
        $name = 'task_report_' . date('Y-m-d i:h:s');
        $data = \Excel::download(new task_reportExport($id), $name . '.xlsx');
        ob_end_clean();

        return $data;
    }
}
