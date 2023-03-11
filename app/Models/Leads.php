<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $fillable = [
        'id',
        'name',
        'price',
        'stage',
        'owner',
        'client',
        'source',
        'created_by',
    ];
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner')->first();
    }

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client')->first();
    }

    public function removeProjectLead($lead_id){
        return Projects::where('lead','=',$lead_id)->update(array('lead' => 0));
    }

    public function sources()
    {
        return $this->hasOne('App\Models\Leadsource', 'id', 'source')->first();
    }
}
