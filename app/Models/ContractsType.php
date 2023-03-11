<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractsType extends Model
{
    protected $table = 'contract_types';
    protected $fillable = [
        'name',
        'created_by',
    ];
}
