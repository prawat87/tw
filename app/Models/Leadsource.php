<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leadsource extends Model
{
    protected $fillable = [
        'name', 'created_by',
    ];


    protected $hidden = [

    ];
}
