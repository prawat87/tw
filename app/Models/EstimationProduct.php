<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimationProduct extends Model
{
    protected $fillable = [
        'estimation_id',
        'name',
        'price',
        'quantity',
        'description',
    ];
}
