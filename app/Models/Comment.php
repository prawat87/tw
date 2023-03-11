<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment', 'task_id','created_by', 'user_type',
    ];


    protected $hidden = [

    ];

    public function comment_user(){
       return User::where('id','=',$this->created_by)->first();
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','created_by');
    }

}
