<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    protected $fillable = [
        'name','color','pipeline_id','created_by'
    ];

    public static $colors = [
        'warning',
        'success',
        'danger',
        'info'
    ];


}
