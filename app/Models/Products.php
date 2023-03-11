<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'unit',
        'description',
    ];


    protected $hidden = [

    ];

    public function unit()
    {
        return $this->hasOne('App\Models\Productunits', 'id', 'unit')->first();
    }

}
