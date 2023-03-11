<?php

namespace App\Models;
use App\Models\Projects;
use App\Models\Userprojects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zoommeeting extends Model
{
    protected $fillable = [
          'title',
          'meeting_id',
          'client_id',
          'project_id',
          'employee',
          'start_date',
          'duration',
          'start_url',
          'password',
          'join_url',
          'status',
          'created_by',
    ];
    protected $appends  = array(
        'client_name',
        'project_name',
    );
    public function getClientNameAttribute($value)
    {
        $client = User::select('id', 'name')->where('id', $this->client_id)->first();

        return $client ? $client->name : '';
    }
    public function getUserNameAttribute($value)
    {
        $user = User::select('id', 'name')->where('id', $this->employee)->first();

        return $user ? $user->name : '';
    }

    public function checkDateTime(){
        $m = $this;

        if (\Carbon\Carbon::parse($m->start_date)->addMinutes($m->duration)->gt(\Carbon\Carbon::now())) {
           
            return 1;
        }else{
             
            return 0;
        }
    }

    public function projectUser()
    {
        return Userprojects::select('userprojects.*', 'users.name', 'users.avatar', 'users.email', 'users.type')->join('users', 'users.id', '=', 'userprojects.user_id')->where('project_id', '=', $this->id)->whereNotIn('user_id', [$this->created_by])->get();
    }

    public function projectName()
    {
        return $this->hasOne('App\Models\Projects', 'id', 'project_id');
    }
}