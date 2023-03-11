<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'remark',
    ];

    public function task()
    {
        return Task::where('id', '=', $this->task_id)->first();
    }

    public function user()
    {
        return User::where('id', '=', $this->user_id)->first();
    }

    public function project()
    {
        return $this->hasOne('App\Models\Projects', 'id', 'project_id');
    }

    public static function project_nm($project_name)
    {
        $taxArr  = explode(',', $project_name);
        $lead = 0;
        foreach ($taxArr as $tax) {
            $tax     = Tax::find($tax);
            $lead = isset($tax->name) ? $tax->name : '';
        }

        return $lead;
    }
}
