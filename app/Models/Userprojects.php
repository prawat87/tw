<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userprojects extends Model
{
    protected $table = 'userprojects';
    protected $fillable = [
        'user_id', 'project_id','is_active','permission'
    ];

    public function project_assign_user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function project_users(){
        return $this->hasMany('App\Models\User','id','user_id');
    }

    
    public function projectUser()
    {
        return ProjectUser::select('project_users.*', 'users.name', 'users.avatar', 'users.email', 'users.type')->join('users', 'users.id', '=', 'project_users.user_id')->where('project_id', '=', $this->id)->whereNotIn('user_id', [$this->created_by])->get();
    }

}
