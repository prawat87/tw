<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Plan;
use App\Models\Projects;
use App\Models\User;
use App\Models\Utility;
use App\Models\Projectstages;
use App\Models\Task;
use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function login(Request $request)
    {
        // dd('hey');
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string'
        ]);

        if (!\Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }
        $user=\Auth::user();

        if($user->type == 'company')
        {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            if($user->plan != $free_plan->id)
            {
                if(date('Y-m-d') > $user->plan_expire_date)
                {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $projects = Projects::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $users    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get();
                    $clients  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get();

                    $projectCount = 0;
                    foreach($projects as $project)
                    {
                        $projectCount++;
                        if($projectCount <= $free_plan->max_projects)
                        {
                            $project->is_active = 1;
                            $project->save();
                        }
                        else
                        {
                            $project->is_active = 0;
                            $project->save();
                        }
                    }

                    $userCount = 0;
                    foreach($users as $user)
                    {
                        $userCount++;
                        if($userCount <= $free_plan->max_users)
                        {
                            $user->is_active = 1;
                            $user->save();
                        }
                        else
                        {
                            $user->is_active = 0;
                            $user->save();
                        }
                    }
                    $clientCount = 0;
                    foreach($clients as $client)
                    {
                        $clientCount++;
                        if($clientCount <= $free_plan->max_clients)
                        {
                            $client->is_active = 1;
                            $client->save();
                        }
                        else
                        {
                            $client->is_active = 0;
                            $client->save();
                        }
                    }
                }
            }
        }


        $settings              = Utility::settings(auth()->user()->id);
       
        $settings = [
            'shot_time'=> isset($settings['interval_time'])?$settings['interval_time']:10, 
        ];
        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user'=>auth()->user(),
            'settings' =>$settings,
        ],'Login successfully.');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([],'Tokens Revoked');
    }

    public function getProjects(Request $request){
        $user = \Auth::user();

        if($user->isUser())
        {
            $assign_pro_ids = Userprojects::where('user_id',$user->id)->pluck('project_id');
            $project_s      = Projects::with('tasks')->select(
                [
                    'name',
                    'id',
                    'client',
                ]
            )->whereIn('id', $assign_pro_ids)->get()->toArray();

        }
        else
        {
            $project_s = Projects::with('tasks')->select(
                [
                    'name',
                    'id',
                    'client',
                ]
            )->where('created_by', $user->id)->get()->toArray();

        }
        return $this->success([
            'projects' => $project_s,
        ],'Get Project List successfully.');
    }

    public function addTracker(Request $request){
       
        $user = auth()->user();
        if($request->has('action') && $request->action == 'start'){
         
            $validatorArray = [
                'task_id' => 'required|integer',
            ];
            $validator      = \Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return $this->error($validator->errors()->first(), 401);
            }
            $task= Task::find($request->task_id);
            if(empty($task)){
                return $this->error('Invalid task', 401);
            }
        
            $project_id = isset($task->project_id)?$task->project_id:'';
            TimeTracker::where('created_by', '=', $user->id)->where('is_active', '=', 1)->update(['end_time' => date("Y-m-d H:i:s")]);
            
            $track['name']        = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['project_id']  = $project_id;
            $track['is_billable'] =  $request->has('is_billable')? $request->is_billable:0;
            $track['tag_id']      = $request->has('workin_on') ? $request->input('workin_on') : '';
            $track['start_time']  = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
            $track['task_id']     = $request->has('task_id') ? $request->input('task_id') : '';
            $track['created_by']  = $user->id;
            $track                = TimeTracker::create($track);
            $track->action        ='start';
            return $this->success( $track,'Track successfully create.');
        }else{
            $validatorArray = [
                'task_id' => 'required|integer',
                'traker_id' =>'required|integer',
            ];
            $validator      = Validator::make(
                $request->all(), $validatorArray
            );
            if($validator->fails())
            {
                return Utility::error_res($validator->errors()->first());
            }
            $tracker = TimeTracker::where('id',$request->traker_id)->first();
            // dd($tracker);
            if($tracker)
            {
                $tracker->end_time   = $request->has('time') ?  date("Y-m-d H:i:s",strtotime($request->input('time'))) : date("Y-m-d H:i:s");
                $tracker->is_active  = 0;
                $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
                $tracker->save();
                return $this->success( $tracker,'Stop time successfully.');
            }
        }
         
    }

    public function uploadImage(Request $request){
        $user = auth()->user();
        $image_base64 = base64_decode($request->img);
        $file =$request->imgName;
        if($request->has('tracker_id') && !empty($request->tracker_id)){
            $app_path = storage_path('uploads/traker_images/').$request->tracker_id.'/';
            if (!file_exists($app_path)) {
                mkdir($app_path, 0777, true);
            }

        }else{
            $app_path = storage_path('uploads/traker_images/');
            if (is_dir($app_path)) {
                mkdir($app_path, 0777, true);
            }
        }
        $file_name =  $app_path.$file;
        file_put_contents( $file_name, $image_base64);
        $new = new TrackPhoto();
        $new->track_id = $request->tracker_id;
        $new->user_id  = $user->id;
        $new->img_path  = 'uploads/traker_images/'.$request->tracker_id.'/'.$file;
        $new->time  = $request->time;
        $new->status  = 1;
        $new->save();
        return $this->success( [],'Uploaded successfully.');
    }

}
