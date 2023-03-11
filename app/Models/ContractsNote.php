<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractsNote extends Model
{
    protected $table = 'contract_note';

    protected $fillable = [
        'contract_id',
        'user_id',
        'notes',
        'created_by',
    ];
}
