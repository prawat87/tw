<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractsAttachment extends Model
{
    protected $table = 'contract_attechments';
 
    protected $fillable = [
        'contract_id',
        'user_id',
        'files',
    ];  
}
