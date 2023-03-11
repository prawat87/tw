<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'start_date',
        'due_date',
        'cost',
        'progress',
        'summary',
    ];
}
