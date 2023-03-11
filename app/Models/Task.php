<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    protected $fillable = [
        'title',
        'parent_task_id',
        'priority',
        'description',
        'group_id',
        'due_date',
        'start_date',
        'estimated_mins',
        'hours',
        'assign_to',
        'project_id',
        'milestone_id',
        'status',
        'order',
        'stage',
    ];

    protected $casts = [
        'assign_to' => 'json',
    ];

    public function project()
    {
        return $this->hasOne('App\Models\Projects', 'id', 'project_id');
    }

    public function users()
    {
        //dd($this->assign_to);
        if (!in_array('-1', $this->assign_to)) {
            return User::whereIn('id', $this->assign_to)->get();
        } else {
            $project = Projects::find($this->project_id);
            return $users =  $project->project_user();

            //dd($users);
        }


        //return User::whereIn('id', explode(',', $this->assign_to))->get();
    }

    public function Projectstages()
    {
        return $this->hasOne('App\Models\Projectstages', 'id', 'stage');
    }

    public function TaskGroup()
    {
        return $this->hasOne('App\Models\TaskGroup', 'id', 'group_id');
    }

    public function task_user()
    {
        return $this->hasMany('App\Models\User', 'id', 'assign_to');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskFiles()
    {
        return $this->hasMany('App\Models\TaskFile', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskCheckList()
    {
        return $this->hasMany('App\Models\CheckList', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskCompleteCheckListCount()
    {
        return $this->hasMany('App\Models\CheckList', 'task_id', 'id')->where('status', '=', '1')->count();
    }

    public function taskTotalCheckListCount()
    {
        return $this->hasMany('App\Models\CheckList', 'task_id', 'id')->count();
    }

    public function milestone()
    {
        return $this->hasOne('App\Models\Milestone', 'id', 'milestone_id');
    }
    public function milestone_report()
    {
        return $this->milestone_id ? Milestone::find($this->milestone_id) : null;
    }
    public function subTaskPercentage()
    {
        $completedChecklist = $this->taskCompleteSubTaskCount();
        $allChecklist = max($this->taskTotalSubTaskCount(), 1);

        $percentageNumber = ceil(($completedChecklist / $allChecklist) * 100);
        $percentageNumber = $percentageNumber > 100 ? 100 : ($percentageNumber < 0 ? 0 : $percentageNumber);

        return (int) number_format($percentageNumber);
    }
}
