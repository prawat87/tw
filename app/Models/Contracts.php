<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    protected $fillable = [
        'client_name', 
        'project_id',
        'subject',
        'value',
        'type',
        'start_date',
        'end_date',
        'status',
        'contract_description',
        'client_signature',
        'company_signature',
        'description',
        'created_by',
    ];

    public function contract_type()
    {
        return $this->hasOne('App\Models\ContractsType', 'id', 'type');
    }


    public function clients()
    {
        return $this->hasOne('App\Models\User', 'id', 'client_name');
    }


    public static function getContractSummary($contracts)
    {
        $total = 0;

        foreach($contracts as $contract)
        {
            $total += $contract->value;
        }

        return \Auth::user()->priceFormat($total);
    }

    public function projectss()
    {
        return $this->hasOne('App\Models\Projects', 'id', 'project_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\ContractsAttachment', 'contract_id' , 'id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\ContractsComment', 'contract_id', 'id');
    }
    public function note()
    {
        return $this->hasMany('App\Models\ContractsNote', 'contract_id', 'id');
    }

    public function ContractAttechment()
    {
        return $this->belongsTo('App\Models\ContractsAttachment', 'id', 'contract_id');
    }

    public function ContractComment()
    {
        return $this->belongsTo('App\Models\ContractsComment', 'id', 'contract_id');
    }

    public function ContractNote()
    {
        return $this->belongsTo('App\Models\ContractsNote', 'id', 'contract_id');
    }

    public static function status()
    {
        $editstatus = [
            'accept' => 'Accept',
            'decline' => 'Decline',
           
        ];
        return $editstatus;
    }
    
}
