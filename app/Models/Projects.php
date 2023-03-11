<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $fillable = [
        'name',
        'price',
        'start_date',
        'due_date',
        'client',
        'user',
        'description',
        'label',
        'status',
        'is_active',
        'created_by',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'userprojects', 'project_id', 'user_id');
    }

    public function label()
    {
        return $this->hasOne('App\Models\Labels', 'id', 'label')->first();
    }

    public function taskGroup()
    {
        return $this->hasMany('App\Models\TaskGroup', 'id', 'taskGroup');
    }

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client')->first();
    }

    public function milestones()
    {
        return $this->hasMany('App\Models\Milestone', 'project_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany('App\Models\ActivityLog', 'project_id', 'id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany('App\Models\ProjectFile', 'project_id', 'id');
    }

    public function countTask()
    {
        return Task::where('project_id', '=', $this->id)->count();
    }

    public function countTaskComments()
    {
        return Task::join('comments', 'comments.task_id', '=', 'tasks.id')->where('project_id', '=', $this->id)->count();
    }

    public function project_expenses()
    {
        return Expense::where('project', '=', $this->id)->sum('amount');
    }

    public function project_user()
    {
        return Userprojects::select('userprojects.*', 'users.name', 'users.avatar', 'users.email', 'users.type')->join('users', 'users.id', '=', 'userprojects.user_id')->where('project_id', '=', $this->id)->whereNotIn('user_id', [$this->created_by])->get();
    }

    public function user_project_total_task($project_id, $user_id)
    {
        return Task::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->count();
    }

    public function user_project_comlete_task($project_id, $user_id, $last_stage_id)
    {
        return Task::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->where('stage', '=', $last_stage_id)->count();
    }

    public function project_total_task($project_id)
    {
        return Task::where('project_id', '=', $project_id)->count();
    }

    public function project_complete_task($project_id, $last_stage_id)
    {
        return Task::where('project_id', '=', $project_id)->where('stage', '=', $last_stage_id)->count();
    }

    public function project_last_stage()
    {
        return Projectstages::where('created_by', '=', $this->created_by)->orderBy('order', 'desc')->first();
    }

    public function client_project_permission()
    {
        return ClientPermission::where('project_id', $this->id)->where('client_id', $this->client)->first();
    }

    public static function getProjectStatus()
    {

        $projectData = [];
        if (Auth::user()->type == 'company') {
            $on_going  = Projects::where('status', '=', 'on_going')->where('created_by', '=', \Auth::user()->id)->count();
            $on_hold   = Projects::where('status', '=', 'on_hold')->where('created_by', '=', \Auth::user()->id)->count();
            $completed = Projects::where('status', '=', 'completed')->where('created_by', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        } else if (\Auth::user()->type == 'client') {
            $on_going  = Projects::where('status', '=', 'on_going')->where('client', '=', \Auth::user()->id)->count();
            $on_hold   = Projects::where('status', '=', 'on_hold')->where('client', '=', \Auth::user()->id)->count();
            $completed = Projects::where('status', '=', 'completed')->where('client', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        } else {
            $on_going  = Userprojects::join('projects', 'userprojects.project_id', '=', 'projects.id')->where('projects.status', '=', 'on_going')->where('user_id', '=', \Auth::user()->id)->count();
            $on_hold   = Userprojects::join('projects', 'userprojects.project_id', '=', 'projects.id')->where('projects.status', '=', 'on_hold')->where('user_id', '=', \Auth::user()->id)->count();
            $completed = Userprojects::join('projects', 'userprojects.project_id', '=', 'projects.id')->where('projects.status', '=', 'completed')->where('user_id', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }

        return $projectData;
    }

    public static $status         = [
        'incomplete' => 'Incomplete',
        'complete' => 'Complete',
    ];
    public static $priority       = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];
    public static $project_status = [
        'on_going' => 'On Going',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
    ];
    public static $permission     = [
        '',
        'show activity',
        'show milestone',
        'create milestone',
        'edit milestone',
        'delete milestone',
        'show task',
        'create task',
        'edit task',
        'delete task',
        'move task',
        'create checklist',
        'edit checklist',
        'delete checklist',
        'show checklist',
        'show uploading',
        'manage bug report',
        'create bug report',
        'edit bug report',
        'delete bug report',
        'move bug report',
        'manage timesheet',
        'create timesheet',
        'edit timesheet',
        'delete timesheet',
    ];

    public static function taxRate($client)
    {
        $taxArr  = explode(',', $client);
        $taxRate = 0;
        foreach ($taxArr as $tax) {
            $tax     = User::find($tax);

            $taxRate = $tax->name;
        }

        return $taxRate;
    }

    public static function label_nm($label_name)
    {
        $taxArr  = explode(',', $label_name);
        $label = 0;
        foreach ($taxArr as $tax) {
            $tax     = Labels::find($tax);

            $label = $tax->name;
        }

        return $label;
    }


    public static function task_nm($task_name)
    {
        $taxArr  = explode(',', $task_name);
        $label = 0;
        foreach ($taxArr as $tax) {
            $tax     = Task::find($tax);

            $label = $tax->title;
        }

        return $label;
    }


    public static function ExpensesCategoryss($cat_name)
    {
        $taxArr  = explode(',', $cat_name);
        $label = 0;
        foreach ($taxArr as $tax) {
            $tax     = ExpensesCategory::find($tax);

            $label = $tax->name;
        }

        return $label;
    }


    public static function bug_status($bug_name)
    {
        $taxArr  = explode(',', $bug_name);
        $label = 0;
        foreach ($taxArr as $tax) {
            $tax     = BugStatus::find($tax);

            $label = $tax->title;
        }

        return $label;
    }


    public static function lead_nm($lead_name)
    {
        $taxArr  = explode(',', $lead_name);
        $lead = 0;
        foreach ($taxArr as $tax) {
            $tax     = Leads::find($tax);

            $lead = $tax->name;
        }

        return $lead;
    }

    public static function tax_nm($tax_name)
    {
        $taxArr  = explode(',', $tax_name);
        $lead = 0;
        foreach ($taxArr as $tax) {
            $tax  = Tax::find($tax);

            $lead = $tax->name;
        }
        return $lead;
    }


    public function tasks()
    {
        if (\Auth::user()->isUser()) {
            return $this->hasMany('App\Models\Task', 'project_id', 'id');
        } else {
            return $this->hasMany('App\Models\Task', 'project_id', 'id');
        }
    }


    /**
     * project_progress
     *
     * @return void
     */
    public function project_progress()
    {
        // $total_task     = Task::where('project_id', '=', $this->id)->count();
        // $completed_task =  Task::where('project_id', '=', $this->id)->where('stage', '=', 4)->count();

        $project_last_stage = $this->project_last_stage($this->id) ? $this->project_last_stage($this->id)->id : '';

        $total_task = $this->project_total_task($this->id);
        $completed_task = $this->project_complete_task($this->id, $project_last_stage);

        if ($total_task > 0) {
            $percentage = intval(($completed_task / $total_task) * 100);
            return [
                'percentage' => $percentage . '%',
            ];
        } else {
            return [
                'percentage' => 0 . '%',
            ];
        }
    }



    /**
     * project_milestone_progress
     *
     * @return void
     */
    public function project_milestone_progress()
    {
        $total_milestone     = Milestone::where('project_id', '=', $this->id)->count();
        $total_progress_sum  = Milestone::where('project_id', '=', $this->id)->sum('progress');

        if ($total_milestone > 0) {
            $percentage = intval(($total_progress_sum / $total_milestone));


            return [

                'percentage' => $percentage . '%',
            ];
        } else {
            return [

                'percentage' => 0,
            ];
        }
    }



    /**
     * getProjectTotalEstimatedTimes
     *
     * @return void
     */
    public function getProjectTotalEstimatedTimes()
    {
        $total_estimated_time = Task::where("project_id", '=', $this->id)->sum('estimated_mins');

        $totalEstimatedHours = (intdiv($total_estimated_time, 60) > 0) ? intdiv($total_estimated_time, 60) . 'h' : '0h';
        $totalEstimatedMins = (($total_estimated_time % 60) > 0) ? ($total_estimated_time % 60) . 'm' : '';
        $totalEstimatedTime = $totalEstimatedHours . ' ' . $totalEstimatedMins;

        return $totalEstimatedTime;
    }

    /**
     * getProjectTotalLoggedHours
     *
     * @return void
     */
    public function getProjectTotalLoggedHours()
    {
        $total_logged_time = Timesheet::where("project_id", '=', $this->id)->sum('total_mins');

        $totalLoggedHours = (intdiv($total_logged_time, 60) > 0) ? intdiv($total_logged_time, 60) . 'h' : '0h';
        $totalLoggedMins = (($total_logged_time % 60) > 0) ? ($total_logged_time % 60) . 'm' : '';
        $totalLoggedTime = $totalLoggedHours . ' ' . $totalLoggedMins;

        return $totalLoggedTime;
    }
}
