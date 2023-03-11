<?php

namespace App\Http\Controllers;

use File;

use Carbon\Carbon;
use App\Models\TaskGroup;
use App\Exports\bugExport;
use Illuminate\Http\Request;
use App\Exports\projectsExport;
use App\Imports\projectsImport;
use App\Exports\timesheetExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{TimeTracker, Utility, ActivityLog, Bug, BugComment, BugFile, BugStatus, CheckList, Client, ClientPermission, Comment, Invoice, Labels, Leads, Milestone, Plan, Project, ProjectFile, Projects, Projectstages, Task, TaskFile, Timesheet, User, Userprojects};

class ProjectsController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('manage project')) {
            $user = Auth::user();
            if ($user->type == 'client') {
                $projects = Projects::where('client', '=', $user->id)->get();
            } else if ($user->type == 'PMO') {
                $projects = Projects::get();
            } else {
                $projects = $user->projects;
            }
            $project_status = Projects::$project_status;
            return view('projects.index', compact('projects', 'project_status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugexport($id)
    {
        $name = 'bug_' . date('Y-m-d i:h:s');
        $data = Excel::download(new bugExport($id), $name . '.xlsx');

        return $data;
    }


    public function importFile()
    {
        return view('projects.import');
    }


    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $customers = (new projectsImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($customers) - 1;
        $errorArray    = [];
        for ($i = 1; $i <= count($customers) - 1; $i++) {
            $customer = $customers[$i];
            $customerData = new Projects();

            $customerData->name             = $customer[0];
            $customerData->price            = $customer[1];
            $customerData->start_date       = $customer[2];
            $customerData->due_date         = $customer[3];
            $customerData->client           = $customer[4];
            $customerData->description      = $customer[5];
            $customerData->label            = $customer[6];
            $customerData->lead             = $customer[7];
            $customerData->created_by       = \Auth::user()->creatorId();

            if (empty($customerData)) {
                $errorArray[] = $customerData;
            } else {
                $customerData->save();
            }
            $userproject             = new Userprojects();
            $userproject->user_id    = Auth::user()->creatorId();
            $userproject->project_id =   $customerData->id;

            $userproject->save();

            if (empty($customerData)) {
                $errorArray[] =  $userproject;
            } else {
                $userproject->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');

            foreach ($errorArray as $errorData) {
                $errorRecord[] = implode(',', $errorData);
            }
            \Session::put('errorArray', $errorRecord);
        }
        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function exporttimesheet()
    {
        $name = 'timesheet_' . date('Y-m-d i:h:s');
        $data = Excel::download(new timesheetExport(), $name . '.xlsx');

        return $data;
    }


    public function export()
    {
        $name = 'projects_' . date('Y-m-d i:h:s');
        $data = Excel::download(new projectsExport(), $name . '.xlsx');

        return $data;
    }

    public function create()
    {
        if (Auth::user()->can('create project')) {
            $users   = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
            $clients = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $labels  = Labels::where('created_by', '=', Auth::user()->creatorId())->get();
            $leads   = Leads::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

            $clients->prepend('Select Client', '');
            // $users->prepend('Select User', '');
            $leads->prepend('Select Lead', '');

            return view('projects.create', compact('clients', 'labels', 'users', 'leads'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create project')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:50',
                    'price' => 'required',
                    'start_date' => 'required',
                    'due_date' => 'required',
                    'label' => 'required',
                    'user' => 'required',
                    'lead' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('projects.index')->with('error', $messages->first());
            }

            $objUser = Auth::user()->creatorId();
            $objUser = User::find($objUser);

            $total_client = $objUser->countProject();
            $plan         = Plan::find($objUser->plan);

            if ($total_client < $plan->max_clients || $plan->max_clients == -1) {
                $project              = new Projects();
                $project->name        = $request->name;
                $project->price       = $request->price;
                $project->start_date  = $request->start_date;
                $project->due_date    = $request->due_date;
                $project->client      = $request->client;
                $project->label       = $request->label;
                $project->description = $request->description;
                $project->lead        = $request->lead;
                $project->created_by  = Auth::user()->creatorId();
                $project->save();

                $userproject             = new Userprojects();
                $userproject->user_id    = Auth::user()->creatorId();
                $userproject->project_id = $project->id;
                $userproject->save();

                $project    = Projects::find($project->id);
                $projectArr = [
                    'project_id' => $project->id,
                    'name' => $project->name,
                    'updated_by' => Auth::user()->id,
                ];

                $pArr = [
                    'project_name' => $project->name,
                    'project_label' => $project->label()->name,
                    'project_status' => Projects::$project_status[$project->status],
                ];
                $assigned_users = (array_values(array_filter($request->user, 'strlen')));

                foreach ($assigned_users as $key => $user) {
                    $userproject             = new Userprojects();
                    $userproject->user_id    = $user;
                    $userproject->project_id = $project->id;
                    $userproject->save();
                    Utility::sendNotification('assign_project', $user, $projectArr);
                    Utility::sendEmailTemplate('Project Assigned', $user, $pArr);
                }

                Utility::sendNotification('assign_project', $request->client, $projectArr);
                $resp = Utility::sendEmailTemplate('Project Assigned', $request->client, $pArr);

                $permissions = Projects::$permission;
                ClientPermission::create(
                    [
                        'client_id' => $project->client,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $permissions),
                    ]
                );

                $settings  = Utility::settings(Auth::user()->creatorId());
                if (isset($settings['project_notificaation']) && $settings['project_notificaation'] == 1) {
                    $msg =  $project->name . " created by  " . \Auth::user()->name . '.';

                    Utility::send_slack_msg($msg);
                }
                if (isset($settings['telegram_project_notificaation']) && $settings['telegram_project_notificaation'] == 1) {
                    $msg =  $project->name . " created by " . \Auth::user()->name . '.';

                    Utility::send_telegram_msg($msg);
                }

                return redirect()->back()->with('success', __('Project successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            } else {
                return redirect()->back()->with('error', __('Your project limit is over, Please upgrade plan.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit project')) {
            $clients = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $labels  = Labels::where('created_by', '=', Auth::user()->creatorId())->get();
            $leads   = Leads::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $assigned_users =  Userprojects::where("project_id", $id)->with(['project_users:id,name'])->get();
            $users   = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');



            $project = Projects::findOrfail($id);
            if ($project->created_by == Auth::user()->creatorId()) {
                return view('projects.edit', compact('project', 'clients', 'labels', 'leads', 'users', 'assigned_users'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($project_id)
    {
        $usr = Auth::user();

        if ($usr->can('show project')) {
            $project = Projects::where('id', $project_id)->first();

            if (!empty($project) && $project->created_by == $usr->creatorId()) {
                if ($usr->type != 'company' && $usr->type != 'PMO') {
                    $arrProjectUsers = $project->project_user()->pluck('user_id')->toArray();
                    array_push($arrProjectUsers, $project->client);

                    if (!in_array($usr->id, $arrProjectUsers)) {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }

                $project_status = Projects::$project_status;
                $permissions    = $project->client_project_permission();
                $perArr         = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

                return view('projects.show', compact('project', 'project_status', 'perArr'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit project')) {
            $project = Projects::findOrfail($id);
            if ($project->created_by == Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',
                        'price' => 'required',
                        'start_date' => 'required',
                        'due_date' => 'required',
                        'label' => 'required',
                        'lead' => 'required',
                        'client' => 'required'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $project->name        = $request->name;
                $project->price       = $request->price;
                $project->start_date  = $request->start_date;
                $project->due_date    = $request->due_date;
                $project->client      = $request->client;
                $project->label       = $request->label;
                $project->lead        = $request->lead;
                $project->description = $request->description;
                $project->save();

                $projectArr = [
                    'project_id' => $project->id,
                    'name' => $project->name,
                    'updated_by' => Auth::user()->id,
                ];

                $pArr = [
                    'project_name' => $project->name,
                    'project_label' => $project->label()->name,
                    'project_status' => Projects::$project_status[$project->status],
                ];

                $assigned_users = (array_values(array_filter($request->user, 'strlen')));
                // dd($assigned_users);
                $data = Userprojects::where('project_id', $project->id)->delete();

                // if (count($data) > 0) {

                //     $data->delete();
                // }
                foreach ($assigned_users as $key => $user) {
                    $userproject             = new Userprojects();

                    $userproject->user_id    = $user;
                    $userproject->project_id = $project->id;
                    $userproject->save();
                    Utility::sendNotification('assign_project', $user, $projectArr);
                    Utility::sendEmailTemplate('Project Assigned', $user, $pArr);
                }
                ClientPermission::where('client_id', '=', $project->client)->where('project_id', '=', $project->id)->delete();
                $permissions = Projects::$permission;
                ClientPermission::create(
                    [
                        'client_id' => $project->client,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $permissions),
                    ]
                );

                return redirect()->back()->with('success', __('Project successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->can('edit project')) {
            $project = Projects::findOrfail($id);
            if ($project->created_by == Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'status' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', 'Project Status is required.');
                }

                $project->status = $request->status;
                $project->save();

                return redirect()->back()->with('success', __('Status Updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete project')) {
            $project = Projects::findOrfail($id);
            if ($project->created_by == Auth::user()->creatorId()) {
                Milestone::where('project_id', $id)->delete();
                Userprojects::where('project_id', $id)->delete();
                ActivityLog::where('project_id', $id)->delete();

                $projectFile = ProjectFile::select('file_path')->where('project_id', $id)->get()->map(
                    function ($file) {
                        $dir        = storage_path('project_files/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if (!empty($projectFile)) {
                    foreach ($projectFile->pluck('file_path') as $file) {
                        File::delete($file);
                    }
                }
                ProjectFile::where('project_id', $id)->delete();

                Invoice::where('project_id', $id)->update(array('project_id' => 0));
                $tasks     = Task::select('id')->where('project_id', $id)->get()->pluck('id');
                $comment   = Comment::whereIn('task_id', $tasks)->delete();
                $checklist = CheckList::whereIn('task_id', $tasks)->delete();

                $taskFile = TaskFile::select('file')->whereIn('task_id', $tasks)->get()->map(
                    function ($file) {
                        $dir        = storage_path('tasks/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if (!empty($taskFile)) {
                    foreach ($taskFile->pluck('file') as $file) {
                        \File::delete($file);
                    }
                }
                TaskFile::whereIn('task_id', $tasks)->delete();
                Task::where('project_id', $id)->delete();

                $project->delete();

                return redirect()->route('projects.index')->with('success', __('Project successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function userInvite($project_id)
    {
        $assign_user = Userprojects::select('user_id')->where('project_id', $project_id)->get()->pluck('user_id');
        $employee    = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->whereNotIn('id', $assign_user)->get()->pluck('name', 'id');

        return view('projects.invite', compact('employee', 'project_id'));
    }

    public function Invite(Request $request, $project_id)
    {
        if (Auth::user()->can('invite user project')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'user' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('projects.show', $project_id)->with('error', $messages->first());
            }

            $project = Projects::find($project_id);

            $projectArr = [
                'project_id' => $project_id,
                'name' => $project->name,
                'updated_by' => Auth::user()->id,
            ];

            foreach ($request->user as $key => $user) {
                $userproject             = new Userprojects();
                $userproject->user_id    = $user;
                $userproject->project_id = $project_id;
                $userproject->save();

                Utility::sendNotification('assign_project', $user, $projectArr);
            }

            return redirect()->route('projects.show', $project_id)->with('success', __('User successfully Invited.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function removeUser($id, $user_id)
    {
        if (Auth::user()->can('invite user project')) {
            $project = Projects::find($id);
            if ($project->created_by == Auth::user()->creatorId()) {
                Userprojects::where('project_id', '=', $id)->where('user_id', '=', $user_id)->delete();

                return redirect()->back()->with('success', __('User successfully removed!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function milestone($project_id)
    {
        $project = Projects::find($project_id);
        $status  = Projects::$status;

        return view('projects.milestone', compact('project', 'status'));
    }

    public function milestoneStore(Request $request, $project_id)
    {
        $usr         = Auth::user();
        $project     = Projects::find($project_id);
        $userProject = Userprojects::where('project_id', '=', $project_id)->pluck('user_id')->toArray();

        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone              = new Milestone();
        $milestone->project_id  = $project->id;
        $milestone->title       = $request->title;
        $milestone->status      = $request->status;
        $milestone->cost        = $request->cost;
        $milestone->description = $request->description;
        $milestone->save();

        ActivityLog::create(
            [
                'user_id' => Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Create Milestone',
                'remark' => json_encode(['title' => $milestone->title]),
            ]
        );

        $projectArr = [
            'project_id' => $project->id,
            'name' => $project->name,
            'updated_by' => $usr->id,
        ];


        $settings  = Utility::settings(Auth::user()->creatorId());
        if (isset($settings['milestone_notificaation']) && $settings['milestone_notificaation'] == 1) {
            $msg = "New Milestone created by " . \Auth::user()->name . '.';

            Utility::send_slack_msg($msg);
        }


        if (isset($settings['telegram_milestone_notificaation']) && $settings['telegram_milestone_notificaation'] == 1) {
            $msg = "New Milestone created by  " . \Auth::user()->name . '.';

            Utility::send_telegram_msg($msg);
        }


        foreach (array_merge($userProject, [$project->client]) as $u) {
            Utility::sendNotification('create_milestone', $u, $projectArr);
        }

        return redirect()->back()->with('success', __('Milestone successfully created.'));
    }

    public function milestoneEdit($id)
    {
        $milestone = Milestone::find($id);
        $status    = Projects::$status;

        return view('projects.milestoneEdit', compact('milestone', 'status'));
    }

    public function milestoneUpdate($id, Request $request)
    {
        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone              = Milestone::find($id);
        $milestone->title       = $request->title;
        $milestone->status      = $request->status;
        $milestone->progress    = $request->progress;
        $milestone->cost        = $request->cost;
        $milestone->start_date  = $request->start_date;
        $milestone->due_date    = $request->due_date;
        $milestone->description = $request->description;
        $milestone->save();

        $settings  = Utility::settings(Auth::user()->creatorId());
        if (isset($settings['milestonest_notificaation']) && $settings['milestonest_notificaation'] == 1) {
            $msg = " Milestone status updated by  " . \Auth::user()->name . '.';

            Utility::send_slack_msg($msg);
        }

        if (isset($settings['telegram_milestonest_notificaation']) && $settings['telegram_milestonest_notificaation'] == 1) {
            $msg = " Milestone status updated by " . \Auth::user()->name . '.';

            Utility::send_telegram_msg($msg);
        }

        return redirect()->back()->with('success', __('Milestone updated successfully.'));
    }

    public function milestoneDestroy($id)
    {
        $milestone = Milestone::find($id);
        $milestone->delete();

        return redirect()->back()->with('success', __('Milestone successfully deleted.'));
    }

    public function milestoneShow($id)
    {
        $milestone = Milestone::find($id);

        return view('projects.milestoneShow', compact('milestone'));
    }

    public function fileUpload($id, Request $request)
    {
        $project     = Projects::find($id);
        $userProject = Userprojects::where('project_id', '=', $project->id)->pluck('user_id')->toArray();
        $request->validate(['file' => 'required']);

        $file_name = $request->file->getClientOriginalName();
        $file_path = $project->id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
        // $request->file->storeAs('project_files', $file_path);
        $dir = 'project_files/';
        $path = Utility::upload_file($request, 'file', $file_path, $dir, []);
        if ($path['flag'] == 1) {
            $file = $path['url'];
        } else {
            return redirect()->back()->with('error', __($path['msg']));
        }

        $file                 = ProjectFile::create(
            [
                'project_id' => $project->id,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ]
        );
        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'projects.file.download',
            [
                $project->id,
                $file->id,
            ]
        );
        $return['delete']     = route(
            'projects.file.delete',
            [
                $project->id,
                $file->id,
            ]
        );

        ActivityLog::create(
            [
                'user_id' => Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Upload File',
                'remark' => json_encode(['file_name' => $file_name]),
            ]
        );

        $projectArr = [
            'project_id' => $project->id,
            'name' => $project->name,
            'updated_by' => Auth::user()->id,
        ];

        foreach (array_merge($userProject, [$project->client]) as $u) {
            Utility::sendNotification('upload_file', $u, $projectArr);
        }

        return response()->json($return);
    }

    public function fileDownload($id, $file_id)
    {
        $project = Projects::find($id);
        $file    = ProjectFile::find($file_id);
        if ($file) {
            // $file_path = storage_path('project_files/' . $file->file_path);
            // $filename  = $file->file_name;

            // return \Response::download(
            //     $file_path, $filename, [
            //                   'Content-Length: ' . filesize($file_path),
            //               ]
            // );

            $logo = Utility::get_file('project_files/');

            $settings = Utility::getStorageSetting();
            try {
                if ($settings['storage_setting'] == 'local') {
                    $file_path = storage_path('project_files/' . $file->file_path);
                } else {
                    $file_path = $logo . $file->file_path;
                }

                // dd($file_path);
                $filename = $file->file_name;

                return \Response::download(
                    $file_path,
                    $filename,
                    [
                        'Content-Length: ' . filesize($file_path),
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __("File Not Exists."));
            }
        } else {
            return redirect()->back()->with('error', __('File type must be match with Storage setting.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        $project = Projects::find($id);

        $file = ProjectFile::find($file_id);
        if ($file) {
            $path = storage_path('project_files/' . $file->file_path);
            if (file_exists($path)) {
                \File::delete($path);
            }
            $file->delete();

            return response()->json(['is_success' => true], 200);
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ],
                200
            );
        }
    }

    public function taskBoard($project_id)
    {
        $user    = Auth::user();
        $project = Projects::where('id', $project_id)->first();

        //if (!empty($project) && $project->created_by == Auth::user()->creatorId()) {
        if (!empty($project)) {
            if ($user->type != 'company' && $user->type != 'PMO') {
                $arrProjectUsers = $project->project_user()->pluck('user_id')->toArray();
                array_push($arrProjectUsers, $project->client);

                if (!in_array($user->id, $arrProjectUsers)) {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            }

            $stages      = Projectstages::where('created_by', '=', Auth::user()->creatorId())->orderBy('order', 'ASC')->get();

            foreach ($stages as $stage) {
                $stage->tasks($project->id);
            }
            $permissions = $project->client_project_permission();
            $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

            return view('projects.taskboard', compact('project', 'stages', 'perArr'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function taskCreate($project_id)
    {
        $project    = Projects::where('created_by', '=', Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        $projects   = Projects::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $priority   = Projects::$priority;
        $usersArr   = Userprojects::where('project_id', '=', $project_id)->where('user_id', '!=', Auth::user()->creatorId())->get();
        $tasksArr   = Task::where('project_id', '=', $project->id)->get();
        $tasks      = array("0" => "No Parent");
        $users      = array('-1' => 'Everyone');
        $taskList   = array("0" => ["0" => "No Parent"]);
        $groupsArr  = TaskGroup::where('project_id', $project_id)->get();
        $groups     = array("0" => "No Group");

        foreach ($usersArr as $user) {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }

        foreach ($groupsArr as $groupInfo) {
            $groups[$groupInfo->id] = ($groupInfo->name);
        }

        foreach ($tasksArr as $taskInfo) {
            $tasks[$taskInfo->id] = ($taskInfo->title);
            $taskList[$taskInfo->group_id][$taskInfo->id] = $taskInfo->title;
        }



        return view('projects.taskCreate', compact('project', 'project_id', 'tasks', 'groups', 'taskList', 'projects', 'priority', 'users', 'milestones'));
    }

    public function taskStore(Request $request, $projec_id)
    {
        if (Auth::user()->type == 'company') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'priority' => 'required',
                    'assign_to' => 'required',
                    'due_date' => 'required',
                    'start_date' => 'required|before:due_date',
                    'estimated_mins' => 'required',
                ]
            );
        } else {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'priority' => 'required',
                    'due_date' => 'required',
                    'start_date' => 'required|before:due_date',
                    'estimated_mins' => 'required',
                ]
            );
        }
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->route('leads.index')->with('error', $messages->first());
        }

        $usr         = Auth::user();
        $userProject = Userprojects::where('project_id', '=', $projec_id)->pluck('user_id')->toArray();
        $project     = Projects::where('created_by', '=', Auth::user()->creatorId())->where('projects.id', '=', $projec_id)->first();

        if ($project) {
            $post = $request->all();

            //dd($post);

            if ($usr->type != 'company') {
                $post['assign_to'] = $usr->id;
            }
            // Task::setAssignToAttribute($post['assign_to']);
            $post['parent_task_id'] = $request->parent_task;
            $post['project_id'] = $projec_id;
            $post['stage']      = Projectstages::where('created_by', '=', $usr->creatorId())->first()->id;

            $post['estimated_mins'] = $request->estimated_mins * 60;

            //  exit();

            $task               = Task::create($post);

            if ($post['parent_task_id'] == 0) {
                $task->update(['parent_task_id' => $task->id]);
            }

            // print_r($post);
            // exit();
            $task = Task::find($task->id);

            ActivityLog::create(
                [
                    'user_id' => $usr->creatorId(),
                    'project_id' => $projec_id,
                    'log_type' => 'Create Task',
                    'remark' => json_encode(['title' => $task->title]),
                ]
            );

            $projectArr = [
                'project_id' => $project->id,
                'name' => $project->name,
                'updated_by' => $usr->id,
            ];

            $pArr = [
                'project_name' => $project->name,
                'project_label' => $project->label()->name,
                'project_status' => Projects::$project_status[$project->status],
                'task_name' => $task->title,
                'task_priority' => ucfirst($task->priority),
                'task_status' => ucfirst($task->status),
            ];

            // foreach (array_merge($userProject, [$project->client]) as $u) {
            //     Utility::sendNotification('create_task', $u, $projectArr);
            //     Utility::sendEmailTemplate('Task Created', $u, $pArr);
            // }


            // $settings  = Utility::settings(Auth::user()->creatorId());
            // if (isset($settings['task_notificaation']) && $settings['task_notificaation'] == 1) {
            //     $msg =    $request->title . " of " . $project->name . " created by " . \Auth::user()->name . '.';

            //     Utility::send_slack_msg($msg);
            // }


            // if (isset($settings['telegram_task_notificaation']) && $settings['telegram_task_notificaation'] == 1) {
            //     $msg =    $request->title . " of " . $project->name . " created by " . \Auth::user()->name . '.';

            //     Utility::send_telegram_msg($msg);
            // }

            return redirect()->route('project.taskboard', [$projec_id])->with('success', __('Task successfully created.'));
        } else {
            return redirect()->route('project.taskboard', [$projec_id])->with('error', __('You can \'t Add Task.'));
        }
    }

    public function taskEdit($task_id)
    {

        $task       = Task::find($task_id);
        $project    = Projects::where('created_by', '=', Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        $projects   = Projects::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $usersArr   = Userprojects::where('project_id', '=', $task->project_id)->where('user_id', '!=', Auth::user()->creatorId())->get();
        $priority   = Projects::$priority;
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $tasksArr   = Task::where('project_id', '=', $project->id)->get();
        $tasks      = array("0" => "No Parent");
        $users      = array('-1' => 'Everyone');
        $taskList = array();



        $groupsArr     = TaskGroup::where('project_id', $task->project_id)->get();

        //$groupsArr = (!empty($groupsArr)) ? $groupsArr->toArray() : array();
        $groups     = array("0" => "No Group");



        foreach ($groupsArr as $groupInfo) {
            $groups[$groupInfo->id] = ($groupInfo->name);
        }

        foreach ($tasksArr as $taskInfo) {
            $tasks[$taskInfo->id] = ($taskInfo->title);
            $taskList[$taskInfo->group_id][$taskInfo->id] = $taskInfo->title;
        }

        foreach ($usersArr as $user) {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }

        $project_id = $task->project_id;

        return view('projects.taskEdit', compact('project', 'project_id', 'projects', 'users', 'groups', 'taskList', 'tasks', 'task_id', 'task', 'priority', 'milestones'));
    }

    public function taskUpdate(Request $request, $task_id)
    {
        // dd($request->all());
        if (Auth::user()->type == 'company') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'priority' => 'required',
                    'assign_to' => 'required',
                    'due_date' => 'required',
                    'start_date' => 'required|before:due_date',
                    'due_date' => 'required',
                    'milestone_id' => 'required',
                    'estimated_mins' => 'required',
                ]
            );
        }

        $task    = Task::find($task_id);

        $project = Projects::where('created_by', '=', Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        if ($project) {
            $post               = $request->all();
            //dd($post);
            $post['project_id'] = $task->project_id;
            $post['estimated_mins'] = $post['estimated_mins'] * 60;
            $task->update($post);

            return redirect()->route(
                'project.taskboard',
                [$task->project_id]
            )->with('success', __('Task Updated Successfully!'));
        } else {
            return redirect()->route(
                'project.taskboard',
                [$task->project_id]
            )->with('error', __('You can \'t Edit Task!'));
        }
    }

    public function taskDestroy($task_id)
    {
        $task    = Task::find($task_id);
        $project = Projects::find($task->project_id);
        if ($project->created_by == Auth::user()->creatorId()) {
            $task->delete();

            return redirect()->route(
                'project.taskboard',
                [$task->project_id]
            )->with('success', __('Task successfully deleted'));
        } else {
            return redirect()->route(
                'project.taskboard',
                [$task->project_id]
            )->with('error', __('You can\'t Delete Task.'));
        }
    }

    public function taskOrderUpdate(Request $request, $slug, $projectID)
    {
        if (isset($request->sort)) {
            foreach ($request->sort as $index => $taskID) {
                echo $index . "-" . $taskID;
                $task        = Task::find($taskID);
                $task->order = $index;
                $task->save();
            }
        }

        if ($request->new_status != $request->old_status) {
            $task         = Task::find($request->id);
            $task->status = $request->new_status;
            $task->save();

            if (isset($request->client_id) && !empty($request->client_id)) {
                $client = Client::find($request->client_id);
                $name   = $client->name . " <b>(" . __('Client') . ")</b>";
                $id     = 0;
            } else {
                $name = Auth::user()->name;
                $id   = Auth::user()->creatorId();
            }

            ActivityLog::create(
                [
                    'user_id' => $id,
                    'project_id' => $projectID,
                    'log_type' => 'Move',
                    'remark' => json_encode(
                        [
                            'title' => $task->title,
                            'old_status' => ucwords($request->old_status),
                            'new_status' => ucwords($request->new_status),
                        ]
                    ),
                ]
            );

            return $task->toJson();
        }
    }

    public function order(Request $request)
    {
        $post        = $request->all();
        $task        = Task::find($post['task_id']);
        $stage       = Projectstages::find($post['stage_id']);
        $userProject = Userprojects::where('project_id', '=', $task->project_id)->pluck('user_id')->toArray();
        $project     = Projects::where('id', '=', $task->project_id)->first();

        if (!empty($stage)) {
            $task->stage = $post['stage_id'];
            $task->save();
        }

        foreach ($post['order'] as $key => $item) {
            $task_order        = Task::find($item);
            $task_order->order = $key;
            $task_order->stage = $post['stage_id'];
            $task_order->save();
        }

        $projectArr = [
            'project_id' => $task->project_id,
            'project_name' => $project->name,
            'task_id' => $task->id,
            'name' => $task->title,
            'updated_by' => Auth::user()->id,
            'old_status' => ucwords($request->old_status),
            'new_status' => ucwords($request->new_status),
        ];

        $pArr = [
            'project_name' => $project->name,
            'project_label' => $project->label()->name,
            'project_status' => Projects::$project_status[$project->status],
            'task_name' => $task->title,
            'task_priority' => ucfirst($task->priority),
            'task_status' => ucfirst($task->status),
            'task_old_stage' => ucwords($request->old_status),
            'task_new_stage' => ucwords($request->new_status),
        ];

        $settings  = Utility::settings(Auth::user()->creatorId());
        if (isset($settings['taskmove_notificaation']) && $settings['taskmove_notificaation'] == 1) {
            $msg =    $task->title . " status changed from  " . $request->old_status . " to " . $request->new_status . '.';

            Utility::send_slack_msg($msg);
        }
        if (isset($settings['telegram_taskmove_notificaation']) && $settings['telegram_taskmove_notificaation'] == 1) {
            $msg =    $task->title . " status changed from  " . $request->old_status . " to " . $request->new_status . '.';

            Utility::send_telegram_msg($msg);
        }

        foreach (array_merge($userProject, [$project->client]) as $u) {
            Utility::sendNotification('move_task', $u, $projectArr);
            Utility::sendEmailTemplate('Task Moved', $u, $pArr);
        }
    }

    public function taskShow($task_id, $client_id = '')
    {
        $task    = Task::find($task_id);
        $project = Projects::find($task->project_id);

        $permissions = $project->client_project_permission();
        $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

        return view('projects.taskShow', compact('task', 'perArr'));
    }

    public function commentStore(Request $request, $project_id, $task_id)
    {
        $task    = Task::find($task_id);
        $post               = [];
        $post['task_id']    = $task_id;
        $post['comment']    = $request->comment;
        $post['created_by'] = Auth::user()->authId();
        $post['user_type']  = Auth::user()->type;
        $comment            = Comment::create($post);

        $comment->deleteUrl = route('comment.destroy', [$comment->id]);

        $settings  = Utility::settings(Auth::user()->creatorId());
        if (isset($settings['taskcom_notificaation']) && $settings['taskcom_notificaation'] == 1) {
            $msg = "comment added in " . $task->title . ".";

            Utility::send_slack_msg($msg);
        }
        if (isset($settings['telegram_taskcom_notificaation']) && $settings['telegram_taskcom_notificaation'] == 1) {
            $msg = "comment added in " . $task->title . ".";

            Utility::send_telegram_msg($msg);
        }
        return $comment->toJson();
    }

    public function commentDestroy($comment_id)
    {
        $comment = Comment::find($comment_id);
        $comment->delete();

        return "true";
    }

    public function commentStoreFile(Request $request, $task_id)
    {
        // $fileName = $task_id . time() . "_" . $request->file->getClientOriginalName();
        $file_name = $request->file->getClientOriginalName();
        $fileName = $task_id . time() . "_" . $request->file->getClientOriginalName();
        $settings = Utility::getStorageSetting();
        // $url = '';
        $dir        = 'uploads/tasks/';
        $path = Utility::upload_file($request, 'file', $fileName, $dir, []);

        // dd($path);
        if ($path['flag'] == 1) {
            $url = $path['url'];
        } else {
            return redirect()->route('project.taskboard', \Auth::user()->id)->with('error', __($path['msg']));
        }

        // $request->file->storeAs('uploads/tasks', $fileName);
        $post['task_id']    = $task_id;
        $post['file']       = $fileName;
        $post['name']       = $request->file->getClientOriginalName();
        $post['extension']  = "." . $request->file->getClientOriginalExtension();
        $post['file_size']  = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        $post['created_by'] = Auth::user()->authId();
        $post['user_type']  = Auth::user()->type;

        $TaskFile            = TaskFile::create($post);
        $TaskFile->deleteUrl = route('comment.file.destroy', [$TaskFile->id]);

        return $TaskFile->toJson();
    }

    public function commentDestroyFile(Request $request, $file_id)
    {
        $commentFile = TaskFile::find($file_id);
        $path        = storage_path('tasks/' . $commentFile->file);
        if (file_exists($path)) {
            \File::delete($path);
        }
        $commentFile->delete();

        return "true";
    }

    public function checkListStore(Request $request, $task_id)
    {
        $request->validate(
            ['name' => 'required']
        );
        $post['task_id']      = $task_id;
        $post['name']         = $request->name;
        $post['created_by']   = Auth::user()->authId();
        $CheckList            = CheckList::create($post);
        $CheckList->deleteUrl = route(
            'task.checklist.destroy',
            [
                $CheckList->task_id,
                $CheckList->id,
            ]
        );
        $CheckList->updateUrl = route(
            'task.checklist.update',
            [
                $CheckList->task_id,
                $CheckList->id,
            ]
        );

        return $CheckList->toJson();
    }

    public function checklistDestroy(Request $request, $task_id, $checklist_id)
    {
        $checklist = CheckList::find($checklist_id);
        $checklist->delete();

        return "true";
    }

    public function checklistUpdate($task_id, $checklist_id)
    {
        $checkList = CheckList::find($checklist_id);
        if ($checkList->status == 0) {
            $checkList->status = 1;
        } else {
            $checkList->status = 0;
        }
        $checkList->save();

        return $checkList->toJson();
    }

    public function clientPermission($project_id, $client_id)
    {
        $client   = User::find($client_id);
        $project  = Projects::find($project_id);
        $selected = $client->clientPermission($project->id);
        if ($selected) {
            $selected = explode(',', $selected->permissions);
        } else {
            $selected = [];
        }
        $permissions = Projects::$permission;

        return view('clients.create', compact('permissions', 'project_id', 'client_id', 'selected'));
    }

    public function storeClientPermission(request $request, $project_id, $client_id)
    {
        $this->validate(
            $request,
            [
                'permissions' => 'required',
            ]
        );

        $project = Projects::find($project_id);
        if ($project->created_by == Auth::user()->creatorId()) {
            $client      = User::find($client_id);
            $permissions = $client->clientPermission($project->id);
            if ($permissions) {
                $permissions->permissions = implode(',', $request->permissions);
                $permissions->save();
            } else {
                ClientPermission::create(
                    [
                        'client_id' => $client->id,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $request->permissions),
                    ]
                );
            }

            return redirect()->back()->with('success', __('Permissions successfully updated.'))->with('status', 'clients');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'))->with('status', 'clients');
        }
    }

    public function getSearchJson(Request $request)
    {
        $html   = '';
        $usr    = Auth::user();
        $type   = $usr->type;
        $search = $request->keyword;

        if (!empty($search)) {
            if ($type == 'client') {
                $objProject = Projects::select(
                    [
                        'projects.id',
                        'projects.name',
                    ]
                )->where('projects.client', '=', Auth::user()->id)->where('projects.created_by', '=', $usr->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();

                $html .= '<li>
                            <span class="list-link">
                                <i class="fas fa-search"></i>' . __('Projects') . '
                            </span>
                        </li>';


                if ($objProject->count() > 0) {
                    foreach ($objProject as $project) {
                        $html .= '<li>
                            <a class="list-link pl-4" href="' . route('projects.show', $project->id) . '">
                                <span>' . $project->name . '</span>
                            </a>
                        </li>';
                    }
                } else {
                    $html .= '<li>
                                <a class="list-link pl-4" href="#">
                                    <span>' . __('No Projects Found.') . '</span>
                                </a>
                            </li>';
                }

                $objTask = Task::select(
                    [
                        'tasks.project_id',
                        'tasks.title',
                    ]
                )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client', '=', $usr->id)->where('projects.created_by', '=', $usr->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();

                $html .= '<li>
                            <span class="list-link">
                                <i class="fas fa-search"></i>' . __('Tasks') . '
                            </span>
                        </li>';

                if ($objTask->count() > 0) {
                    foreach ($objTask as $task) {
                        $html .= '<li>
                            <a class="list-link pl-4" href="' . route('project.taskboard', [$task->project_id]) . '">
                                <span>' . $task->title . '</span>
                            </a>
                        </li>';
                    }
                } else {
                    $html .= '<li>
                                <a class="list-link pl-4" href="#">
                                    <span>' . __('No Tasks Found.') . '</span>
                                </a>
                            </li>';
                }
            } else {
                $objProject = Projects::select(
                    [
                        'projects.id',
                        'projects.name',
                    ]
                )->join('userprojects', 'userprojects.project_id', '=', 'projects.id')->where('userprojects.user_id', '=', $usr->id)->where('projects.created_by', '=', $usr->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();

                $html .= '<li>
                            <span class="list-link">
                                <i class="fas fa-search"></i>' . __('Projects') . '
                            </span>
                        </li>';

                if ($objProject->count() > 0) {
                    foreach ($objProject as $project) {
                        $html .= '<li>
                                    <a class="list-link pl-4" href="' . route('projects.show', [$project->id]) . '">
                                        <span>' . $project->name . '</span>
                                    </a>
                                </li>';
                    }
                } else {
                    $html .= '<li>
                                <a class="list-link pl-4" href="#">
                                    <span>' . __('No Projects Found.') . '</span>
                                </a>
                            </li>';
                }

                $objTask = Task::select(
                    [
                        'tasks.project_id',
                        'tasks.title',
                    ]
                )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('userprojects', 'userprojects.project_id', '=', 'projects.id')->where('userprojects.user_id', '=', $usr->id)->where('projects.created_by', '=', $usr->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();

                $html .= '<li>
                            <span class="list-link">
                                <i class="fas fa-search"></i>' . __('Tasks') . '
                            </span>
                        </li>';

                if ($objTask->count() > 0) {
                    foreach ($objTask as $task) {
                        $html .= '<li>
                            <a class="list-link pl-4" href="' . route('project.taskboard', [$task->project_id]) . '">
                                <span>' . $task->title . '</span>
                            </a>
                        </li>';
                    }
                } else {
                    $html .= '<li>
                                <a class="list-link pl-4" href="#">
                                    <span>' . __('No Tasks Found.') . '</span>
                                </a>
                            </li>';
                }
            }
        } else {
            $html .= '<li>
                        <a class="list-link pl-4" href="#">
                        <i class="fas fa-search"></i>
                            <span>' . __('Type and search By Project & Tasks.') . '</span>
                        </a>
                      </li>';
        }

        print_r($html);
    }

    public function timeSheetCreate()
    {
        if (Auth::user()->can('create timesheet')) {
            $user_id = \Auth::user()->id;
            
            $projects = Auth::user()->getUserProjects([$user_id])->pluck('name', 'project_id')->prepend('Please Select Project', '');
            //$projects = Projects::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            //$projects->prepend('Select Project', '');

            return view('projects.timesheetCreate', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function timeSheetStore(Request $request)
    {
        if (Auth::user()->can('create timesheet')) {
            for ($i = 0; $i < count($request->project_id); $i++) {
                Timesheet::insert([
                    [
                        "project_id"  => $request->project_id[$i],
                        "user_id"     => Auth::user()->id,
                        "task_id"     => $request->task_id[$i],
                        "date"        => $request->date[$i],
                        "start_time"  => $request->start_time[$i],
                        "end_time"    => $request->end_time[$i],
                        "billable"    => $request->billable[$i],
                        "total_mins"  => $request->total_mins[$i],
                        "remark"      => $request->remark[$i]
                    ],
                ]);
            }

            return redirect()->route('task.timesheetRecord')->with('success', __('Task timesheet successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function dateWiseTimeSheets($timeSheets)
    {
        $allTimeLogs = $dateTotalTime = $totalTimeSum = $totalBillableTimeSum = $dateFormat = $taskGroup = [];
        foreach ($timeSheets as $timeSheet) {
            $getTask = Task::find($timeSheet->task_id);
            $group_id = ($getTask != null) ? $getTask->group_id : null;
            $getTaskGroup = TaskGroup::find($group_id);
            //print_r($timeSheet->task_group_id);
            $taskGroup = ($getTaskGroup != null) ? $getTaskGroup : [];

            $timeLogs['project_name'] = (!empty($timeSheet->project)) ? $timeSheet->project->name : '';
            $timeLogs['avatar'] = $timeSheet->user()->avatar;
            $timeLogs['user_name'] = (!empty($timeSheet->user())) ? $timeSheet->user()->name : '';
            $timeLogs['user_id'] = (!empty($timeSheet->user())) ? $timeSheet->user()->id : '';
            $timeLogs['estimated_mins'] = (!empty($timeSheet->task())) ? $timeSheet->task()->estimated_mins : '';
            $timeLogs['task_id'] = (!empty($timeSheet->task())) ? $timeSheet->task()->id : '';
            $timeLogs['task_title'] = (!empty($timeSheet->task())) ? $timeSheet->task()->title : '';
            $timeLogs['group_name'] = (!empty($taskGroup->name)) ? $taskGroup->name : '';
            $timeLogs['remark'] = $timeSheet->remark;
            $timeLogs['start_time'] = $timeSheet->start_time;
            $timeLogs['end_time'] = $timeSheet->end_time;
            $timeLogs['billable'] = $timeSheet->billable;
            $timeLogs['date'] = $timeSheet->date;
            $timeLogs['total_hrs_mins'] = (!empty($timeSheet->total_mins)) ? floor($timeSheet->total_mins / 60) . 'h  ' . ($timeSheet->total_mins -   floor($timeSheet->total_mins / 60) * 60) . 'm' : '';
            $timeLogs['id'] = $timeSheet->id;

            $timeLogs['total_mins'] = $timeSheet->total_mins;
            $timeLogs['end_time'] = $timeSheet->end_time;

            $allTimeLogs[$timeSheet->date][] = $timeLogs;

            if (array_key_exists($timeSheet->date, $totalTimeSum)) {
                $totalTimeSum[$timeSheet->date] += $timeSheet->total_mins;
            } else {
                $totalTimeSum[$timeSheet->date] = $timeSheet->total_mins;
                $dateFormat[$timeSheet->date] = date("l, d F Y", strtotime($timeSheet->date));
            }

            if ($timeSheet->billable === 'Yes') {
                if (array_key_exists($timeSheet->date, $totalBillableTimeSum)) {
                    $totalBillableTimeSum[$timeSheet->date] += $timeSheet->total_mins;
                } else {
                    $totalBillableTimeSum[$timeSheet->date] = $timeSheet->total_mins;
                }
            }
        }

        return [$allTimeLogs, $totalTimeSum, $totalBillableTimeSum, $dateFormat];
    }

    public function convertMinsToHoursMins($totalLoggedMins)
    {
        $totalLoggedHours = (intdiv($totalLoggedMins, 60) > 0) ? intdiv($totalLoggedMins, 60) . 'h' : '0h';
        $totalLoggedMins = (($totalLoggedMins % 60) > 0) ? ($totalLoggedMins % 60) . 'm' : '';
        $totalLogHours = $totalLoggedHours . ' ' . $totalLoggedMins;
        return $totalLogHours;
    }

    public function timeSheet()
    {
        $newTimeSheet = $task_ids = $finalTime = $totalBillableTimeSum = $dateFormat = $timeSheetsFilteredTools = [];
        $lastDate = '';
        $showMoreButtonStatus = true;
        $totalLogHours = $totalBillableLogHours = $totalNotBillableLogHours = $totalTasksEstimated = 0;

        $totalLoggedMins = $totalBillableLoggedMins = $totalNotBillableLoggedMins = $allTasksEstimatedMins = 0;

        if (Auth::user()->can('manage timesheet')) {
            $user           = Auth::user();
            $project_ids    = $user->projects->pluck('id')->toArray();
            $user_projects  = $user->projects->pluck('name', 'id')->toArray();

            if ($user->type == 'client') {
                $client_project  = Projects::where('client', '=', $user->id)->pluck('id')->toArray();
                $project_ids     = $client_project;
            }

            $timeSheets = Timesheet::whereIn('project_id', $project_ids)
                ->select('date')
                ->where('user_id', '=', $user->id)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->skip(4)->take(1)
                ->get()->toArray();

            if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                    $join->on('timesheets.task_id', '=', 'tasks.id');
                })->select('tasks.estimated_mins', 'timesheets.*')
                    ->whereIn('timesheets.project_id', $project_ids)
                    ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                    ->where('timesheets.user_id', '=', $user->id)
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                    $join->on('timesheets.task_id', '=', 'tasks.id');
                })->select('tasks.estimated_mins', 'timesheets.*')
                    ->whereIn('timesheets.project_id', $project_ids)
                    ->where('timesheets.user_id', '=', $user->id)
                    ->orderBy('date', 'desc')
                    ->get();

                $showMoreButtonStatus = false;
            }

            if ($totalTimeSheet->count() > 0) {
                $result = $this->dateWiseTimeSheets($totalTimeSheet);
                $totalTimeSheet = $result[0] ?? [];

                //$lastDate = date('Y-m-d', strtotime('-10 days'));

                $finalTime = $result[1] ?? [];
                $totalBillableTimeSum = $result[2] ?? [];
                $dateFormat = $result[3] ?? [];
            }

            // Fetch all the records of user to calculate the "Filtered Totals:"
            $totalTimeSheetFilteredTools = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.project_id', $project_ids)
                ->where('timesheets.user_id', '=', $user->id)
                ->orderBy('date', 'desc')
                ->get()->toArray();

            if (!empty($totalTimeSheetFilteredTools)) {
                // Get the Total Logged Hours and Minutes
                $totalLoggedMins += array_sum(array_column($totalTimeSheetFilteredTools, 'total_mins'));

                // Get the Total Billable Hours and Minutes
                $totalBillableLoggedMins += array_sum(array_column(array_filter($totalTimeSheetFilteredTools, function ($item) {
                    return $item['billable'] == 'Yes';
                }), 'total_mins'));

                // Get the Total Non-Billable Hours and Minutes
                $totalNotBillableLoggedMins += array_sum(array_column(array_filter($totalTimeSheetFilteredTools, function ($item) {
                    return $item['billable'] == 'No';
                }), 'total_mins'));

                // Get the Total Estimaged Hours and Minutes
                $allTasksEstimatedMins += array_sum(array_column($totalTimeSheetFilteredTools, 'estimated_mins'));
                $totalLogHours = $this->convertMinsToHoursMins($totalLoggedMins);
                $totalBillableLogHours = $this->convertMinsToHoursMins($totalBillableLoggedMins);
                $totalTasksEstimated = $this->convertMinsToHoursMins($allTasksEstimatedMins);
                $totalNotBillableLogHours = $this->convertMinsToHoursMins($totalNotBillableLoggedMins);

                $timeSheets = (is_array($totalTimeSheet)) ? $totalTimeSheet : $totalTimeSheet->toArray();
                $lastDate = array_key_last($timeSheets);
            }
            return view('projects.timeSheet', compact('user_projects', 'timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat', 'totalLogHours', 'totalBillableLogHours', 'totalNotBillableLogHours', 'totalTasksEstimated', 'lastDate', 'showMoreButtonStatus'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function teamTimeSheet()
    {
        $newTimeSheet = $task_ids = $finalTime = $totalBillableTimeSum = $dateFormat = [];
        $lastDate = '';
        $showMoreButtonStatus = true;
        $totalLogHours = $totalBillableLogHours = $totalNotBillableLogHours = $totalTasksEstimated = $totalLoggedMins = $totalBillableLoggedMins = $totalNotBillableLoggedMins = $allTasksEstimatedMins = 0;

        if (Auth::user()->can('manage timesheet')) {
            $user           = Auth::user();

            // get all the team users
            $teams = $user->getTeamUsers()->toArray();
            $team_ids = array_column($teams, 'id');
            $team_ids[] = $user->id;
            $teams[] = $user->toArray();
            //$user_projects = $user->projects->pluck('name', 'id')->toArray();
            $user_projects = $user->getUserProjects($team_ids)->pluck('name', 'project_id');

            if ($user->type == 'client') {
                $client_project  = Projects::where('client', '=', $user->id)->pluck('id')->toArray();
                //$project_ids     = $client_project;
            }

            $timeSheets = Timesheet::whereIn('user_id', $team_ids)
                ->select('date')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->skip(4)->take(1)
                ->get()->toArray();

            if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                    $join->on('timesheets.task_id', '=', 'tasks.id');
                })->select('tasks.estimated_mins', 'timesheets.*')
                    ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                    ->whereIn('timesheets.user_id', $team_ids)
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
                $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                    $join->on('timesheets.task_id', '=', 'tasks.id');
                })->select('tasks.estimated_mins', 'timesheets.*')
                    ->whereIn('timesheets.user_id', $team_ids)
                    ->orderBy('date', 'desc')
                    ->get();

                $showMoreButtonStatus = false;
            }

            if ($totalTimeSheet->count() > 0) {
                $result = $this->dateWiseTimeSheets($totalTimeSheet);
                $totalTimeSheet = $result[0] ?? [];

                $finalTime = $result[1] ?? [];
                $totalBillableTimeSum = $result[2] ?? [];
                $dateFormat = $result[3] ?? [];
            }

            // Fetch all the records of user to calculate the "Filtered Totals:"
            $totalTeamTimeSheetFilteredTools = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.user_id', $team_ids)
                ->orderBy('date', 'desc')
                ->get()->toArray();

            if (!empty($totalTeamTimeSheetFilteredTools)) {
                // Get the Total Logged Hours and Minutes
                $totalLoggedMins += array_sum(array_column($totalTeamTimeSheetFilteredTools, 'total_mins'));

                // Get the Total Billable Hours and Minutes
                $totalBillableLoggedMins += array_sum(array_column(array_filter($totalTeamTimeSheetFilteredTools, function ($item) {
                    return $item['billable'] == 'Yes';
                }), 'total_mins'));

                // Get the Total Non-Billable Hours and Minutes
                $totalNotBillableLoggedMins += array_sum(array_column(array_filter($totalTeamTimeSheetFilteredTools, function ($item) {
                    return $item['billable'] == 'No';
                }), 'total_mins'));

                // Get the Total Estimaged Hours and Minutes
                $allTasksEstimatedMins += array_sum(array_column($totalTeamTimeSheetFilteredTools, 'estimated_mins'));
                $totalLogHours = $this->convertMinsToHoursMins($totalLoggedMins);
                $totalBillableLogHours = $this->convertMinsToHoursMins($totalBillableLoggedMins);
                $totalTasksEstimated = $this->convertMinsToHoursMins($allTasksEstimatedMins);
                $totalNotBillableLogHours = $this->convertMinsToHoursMins($totalNotBillableLoggedMins);

                $timeSheets = (is_array($totalTimeSheet)) ? $totalTimeSheet : $totalTimeSheet->toArray();
                $lastDate = array_key_last($timeSheets);
            }
            return view('projects.teamTimeSheet', compact('user_projects', 'timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat', 'totalLogHours', 'totalBillableLogHours', 'totalNotBillableLogHours', 'totalTasksEstimated', 'lastDate', 'showMoreButtonStatus', 'teams'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function timeSheetEdit($timeSheet_id)
    {
        if (Auth::user()->can('edit timesheet')) {
            $projects  = Auth::user()->projects->pluck('name', 'id')->prepend('Please Select Project', '');
            $timeSheet = Timesheet::find($timeSheet_id);

            return view('projects.timesheetEdit', compact('timeSheet', 'projects'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function timeSheetUpdate(Request $request, $timeSheet_id)
    {
        if (Auth::user()->can('edit timesheet')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'project_id' => 'required',
                    'task_id' => 'required',
                    'date' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // Code to tackle missing second value in firefox time field
            $start_time = strtotime($request->start_time);
            $start_time_with_second = date("H:i:s", $start_time);
            $end_time = strtotime($request->end_time);
            $end_time_with_second = date("H:i:s", $end_time);

            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->date . $start_time_with_second);

            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->date . $end_time_with_second);

            $total_logged_mins = $to->diffInMinutes($from);


            $timeSheet             = Timesheet::find($timeSheet_id);
            $timeSheet->project_id = $request->project_id;
            $timeSheet->task_id    = $request->task_id;
            $timeSheet->date       = $request->date;
            $timeSheet->start_time      = $request->start_time;
            $timeSheet->end_time      = $request->end_time;
            $timeSheet->total_mins  = $total_logged_mins;
            $timeSheet->remark     = $request->remark;
            $timeSheet->save();

            return redirect()->back()->with('success', __('Task timesheet successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function timeSheetDestroy($timeSheet_id)
    {
        if (Auth::user()->can('delete timesheet')) {
            $timeSheet = Timesheet::find($timeSheet_id);
            $timeSheet->delete();

            return redirect()->route('task.timesheetRecord')->with('success', __('Task timesheet successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * My-time Filter records
     */
    public function timeSheetFilter(Request $request)
    {
        $project = $request->project ?? '';
        $date = $request->date ?? '';
        $start_date_range = $request->start_range ?? '';
        $end_date_range = $request->end_range ?? '';
        
        $showMoreButtonStatus = false;
        $endDate = '';

        $totalLogHours = $totalBillableLoggedMins = $totalBillableLogHours = $totalNotBillableLoggedMins = $totalNotBillableLogHours = $allTasksEstimatedMins = $totalTasksEstimated = '0h';
        $newTimeSheet = $task_ids = $taskIds = $timeSheets = $totalTimeSheet = [];

        $isCustomDateRange = ($start_date_range != '') ? true : null;

        if (Auth::user()->can('manage timesheet')) {
            $user        = Auth::user();
            $projects = $user->projects->pluck('id')->toArray();

            $project_ids = ($project == '-1') ?  $projects : [$project];

            switch ([$date, $start_date_range]) {
                case ['today', null]:
                    $today = date("Y-m-d");
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->where('timesheets.date', $today)
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->where('timesheets.user_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['yesterday', null]:
                    $yesterday = date("Y-m-d", strtotime('-1 days'));
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->where('timesheets.date', $yesterday)
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->where('timesheets.user_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['this_week', null]:
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->whereBetween('timesheets.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->where('timesheets.user_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;

                case ['last_week', null]:
                    $startWeek = Carbon::now()->subWeek()->startOfWeek();
                    $endWeek   = Carbon::now()->subWeek()->endOfWeek();

                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->whereBetween('timesheets.date', [$startWeek, $endWeek])
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->where('timesheets.user_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['this_month', null]:
                    $timeSheets = Timesheet::select('date')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->whereIn('project_id', $project_ids)->where('user_id', $user->id)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get();

                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
                            $showMoreButtonStatus = true;
                            $endDate = date('01-m-y');
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.user_id', '=', $user->id)
                            ->whereMonth('date', Carbon::now()->month)
                            ->whereYear('date', Carbon::now()->year)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = false;
                    }
                    break;

                case ['last_month', null]:

                    $timeSheets = Timesheet::select('date')->whereMonth('date', Carbon::now()->subMonth()->month)->whereYear('date', Carbon::now()->year)->whereIn('project_id', $project_ids)->where('user_id', $user->id)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get();
                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->whereMonth('date', Carbon::now()->subMonth()->month)
                            ->whereYear('date', Carbon::now()->subMonth()->year)
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
                            $showMoreButtonStatus = true;
                            $endDate = date('d-m-Y', strtotime(date('d-m-Y')." -1 month"));
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.user_id', '=', $user->id)
                            ->whereMonth('date', Carbon::now()->subMonth()->month)
                            ->whereYear('date', Carbon::now()->subMonth()->year)
                            ->orderBy('date', 'desc')
                            ->get();

                        $showMoreButtonStatus = false;
                    }
                    break;
                case ['custom', true]:
                    $timeSheets = Timesheet::select('date')->whereBetween('timesheets.date', [$start_date_range, $end_date_range])->whereIn('project_id', $project_ids)->where('user_id', $user->id)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get()->toArray();

                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereBetween('timesheets.date', [$timeSheets[0]['date'], $end_date_range])
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = true;
                        $endDate = $end_date_range;
                    }
                    else{
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereBetween('timesheets.date', [$start_date_range, $end_date_range])
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = false;
                    }
                    break;
                default:
                    $timeSheets = Timesheet::select('date')->whereIn('project_id', $project_ids)->where('user_id', '=', $user->id)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get()->toArray();

                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = true;
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.user_id', '=', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();

                        $showMoreButtonStatus = false;
                    }
            }

            $result = $this->dateWiseTimeSheets($totalTimeSheet);
            $timeSheets = $result[0];
            $finalTime = $result[1];
            $totalBillableTimeSum = $result[2];
            $dateFormat = $result[3];


            if (!empty($totalTimeSheet)) {
                $totalTimeSheet = $totalTimeSheet->toArray();
                //$totalTimeSheet = json_decode(json_encode($totalTimeSheet), true);

                // Get the Total Logged Hours and Minutes
                $totalLoggedMins = array_sum(array_column($totalTimeSheet, 'total_mins'));
                $totalLogHours = $this->convertMinsToHoursMins($totalLoggedMins);

                // Get the Total Billable Logged Hours and Minutes
                $totalBillableLoggedMins = array_sum(array_column(array_filter($totalTimeSheet, function ($item) {
                    return $item['billable'] == 'Yes';
                }), 'total_mins'));

                $totalBillableLogHours = $this->convertMinsToHoursMins($totalBillableLoggedMins);

                $totalNotBillableLoggedMins = array_sum(array_column(array_filter($totalTimeSheet, function ($item) {
                    return $item['billable'] == 'No';
                }), 'total_mins'));

                $totalNotBillableLogHours = $this->convertMinsToHoursMins($totalNotBillableLoggedMins);

                foreach ($totalTimeSheet as $data) {
                    if (!in_array($data['task_id'], $task_ids)) {
                        $task_ids[] = $data['task_id'];
                        $newTimeSheet[] = $data;
                    }
                }

                $allTasksEstimatedMins = array_sum(array_column($newTimeSheet, 'estimated_mins'));
                $totalTasksEstimated = $this->convertMinsToHoursMins($allTasksEstimatedMins);
            }

            $lastDate = array_key_last($timeSheets);

            if(!empty($endDate))
            {
                $endDate = date_format(date_create($endDate),"Y-m-d");
            }

            $html = view('projects.timesheetFilter', compact('timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat', 'totalLogHours', 'totalBillableLogHours', 'totalNotBillableLogHours', 'totalTasksEstimated'))->render();
            
            return response()->json([$html, $showMoreButtonStatus, $lastDate, $endDate]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.' . $request->filter_by));
        }
    }

    /**
     * Team Timesheet Filter records
     */
    public function teamTimeSheetFilter(Request $request)
    {
        $project = $request->project ?? '';
        $date = $request->date ?? '';
        $start_date_range = $request->start_range ?? '';
        $end_date_range = $request->end_range ?? '';
        $teammembers = $request->teammember ?? ['-1'];
        $showMoreButtonStatus = false;
        $endDate = '';

        $totalLogHours = $totalBillableLoggedMins = $totalBillableLogHours = $totalNotBillableLoggedMins = $totalNotBillableLogHours = $allTasksEstimatedMins = $totalTasksEstimated = '0h';
        $newTimeSheet = $task_ids = $taskIds = $timeSheets = $totalTimeSheet = [];

        $isCustomDateRange = ($start_date_range != '') ? true : null;

        
        if (Auth::user()->can('manage timesheet')) {
            $user        = Auth::user();
            if(in_array('-1',$teammembers))
            {
                // Get all the members of current user
                $teams = $user->getTeamUsers()->toArray();
                $teammembers = array_column($teams, 'id');
            }
            $teammembers[] = $user->id;

            //dd($teammembers);
            //$projects = $user->projects->pluck('id')->toArray();
            //$project_ids = ($project == '-1') ?  $projects : [$project];

            if($project == '-1')
            {
                $user_projects = $user->getUserProjects($teammembers)->pluck('name', 'project_id')->toArray();
                $project_ids = array_keys($user_projects);
            }else{
                $project_ids = [$project];
            }

            //dd($project_ids);

            switch ([$date, $start_date_range]) {
                case ['today', null]:
                    $today = date("Y-m-d");
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->where('timesheets.date', $today)
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->whereIn('timesheets.user_id', $teammembers)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['yesterday', null]:
                    $yesterday = date("Y-m-d", strtotime('-1 days'));
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->where('timesheets.date', $yesterday)
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->whereIn('timesheets.user_id', $teammembers)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['this_week', null]:
                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->whereBetween('timesheets.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->whereIn('timesheets.user_id', $teammembers)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;

                case ['last_week', null]:
                    $startWeek = Carbon::now()->subWeek()->startOfWeek();
                    $endWeek   = Carbon::now()->subWeek()->endOfWeek();

                    $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                        $join->on('timesheets.task_id', '=', 'tasks.id');
                    })->select('tasks.estimated_mins', 'timesheets.*')
                        ->whereBetween('timesheets.date', [$startWeek, $endWeek])
                        ->whereIn('timesheets.project_id', $project_ids)
                        ->whereIn('timesheets.user_id', $teammembers)
                        ->orderBy('date', 'desc')
                        ->get();
                    break;
                case ['this_month', null]:
                    $timeSheets = Timesheet::select('date')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->whereIn('project_id', $project_ids)->whereIn('user_id', $teammembers)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get();

                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();
                            $showMoreButtonStatus = true;
                            $endDate = date('01-m-y');
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->whereMonth('date', Carbon::now()->month)
                            ->whereYear('date', Carbon::now()->year)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = false;
                    }
                    break;

                case ['last_month', null]:

                    $timeSheets = Timesheet::select('date')->whereMonth('date', Carbon::now()->subMonth()->month)->whereYear('date', Carbon::now()->year)->whereIn('project_id', $project_ids)->whereIn('user_id', $teammembers)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get();
                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->whereMonth('date', Carbon::now()->subMonth()->month)
                            ->whereYear('date', Carbon::now()->subMonth()->year)
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();
                            $showMoreButtonStatus = true;
                            $endDate = date('01-m-Y', strtotime(date('d-m-Y')." -1 month"));
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->whereMonth('date', Carbon::now()->subMonth()->month)
                            ->whereYear('date', Carbon::now()->subMonth()->year)
                            ->orderBy('date', 'desc')
                            ->get();

                        $showMoreButtonStatus = false;
                    }
                    break;
                case ['custom', true]:
                    $timeSheets = Timesheet::select('date')->whereBetween('timesheets.date', [$start_date_range, $end_date_range])->whereIn('project_id', $project_ids)->whereIn('user_id', $teammembers)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get()->toArray();

                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereBetween('timesheets.date', [$timeSheets[0]['date'], $end_date_range])
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = true;
                        $endDate = $end_date_range;
                    }
                    else{
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereBetween('timesheets.date', [$start_date_range, $end_date_range])
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = false;
                    }
                    break;
                default:
                    $timeSheets = Timesheet::select('date')->whereIn('project_id', $project_ids)->whereIn('user_id', $teammembers)->groupBy('date')->orderBy('date', 'desc')->skip(4)->take(1)->get()->toArray();
                    if (isset($timeSheets[0]['date']) && !empty($timeSheets[0]['date'])) {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->where('timesheets.date', '>=', $timeSheets[0]['date'])
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();
                        $showMoreButtonStatus = true;
                    } else {
                        $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                            $join->on('timesheets.task_id', '=', 'tasks.id');
                        })->select('tasks.estimated_mins', 'timesheets.*')
                            ->whereIn('timesheets.project_id', $project_ids)
                            ->whereIn('timesheets.user_id', $teammembers)
                            ->orderBy('date', 'desc')
                            ->get();

                        $showMoreButtonStatus = false;
                    }
            }

            $result = $this->dateWiseTimeSheets($totalTimeSheet);
            $timeSheets = $result[0];
            $finalTime = $result[1];
            $totalBillableTimeSum = $result[2];
            $dateFormat = $result[3];


            if (!empty($totalTimeSheet)) {
                $totalTimeSheet = $totalTimeSheet->toArray();
                //$totalTimeSheet = json_decode(json_encode($totalTimeSheet), true);

                // Get the Total Logged Hours and Minutes
                $totalLoggedMins = array_sum(array_column($totalTimeSheet, 'total_mins'));
                $totalLogHours = $this->convertMinsToHoursMins($totalLoggedMins);

                // Get the Total Billable Logged Hours and Minutes
                $totalBillableLoggedMins = array_sum(array_column(array_filter($totalTimeSheet, function ($item) {
                    return $item['billable'] == 'Yes';
                }), 'total_mins'));

                $totalBillableLogHours = $this->convertMinsToHoursMins($totalBillableLoggedMins);

                $totalNotBillableLoggedMins = array_sum(array_column(array_filter($totalTimeSheet, function ($item) {
                    return $item['billable'] == 'No';
                }), 'total_mins'));

                $totalNotBillableLogHours = $this->convertMinsToHoursMins($totalNotBillableLoggedMins);

                foreach ($totalTimeSheet as $data) {
                    if (!in_array($data['task_id'], $task_ids)) {
                        $task_ids[] = $data['task_id'];
                        $newTimeSheet[] = $data;
                    }
                }

                $allTasksEstimatedMins = array_sum(array_column($newTimeSheet, 'estimated_mins'));
                $totalTasksEstimated = $this->convertMinsToHoursMins($allTasksEstimatedMins);
            }

            $lastDate = array_key_last($timeSheets);

            if(!empty($endDate))
            {
                $endDate = date_format(date_create($endDate),"Y-m-d");
            }

            $html = view('projects.timesheetFilter', compact('timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat', 'totalLogHours', 'totalBillableLogHours', 'totalNotBillableLogHours', 'totalTasksEstimated'))->render();
            
            return response()->json([$html, $showMoreButtonStatus, $lastDate, $endDate]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.' . $request->filter_by));
        }
    }

    public function projectTask(Request $request)
    {

        $task = new Task();
        if ($request->project_id) {
            $task = $task->where('project_id', '=', $request->project_id);
        }

        if ($request->group_id) {
            $task = $task->where('project_id', '=', $request->project_id)->where('group_id', $request->group_id);
        }
        //$task = $task->get()->pluck('title', 'id');
        $task = $task->select('id', 'parent_task_id', 'group_id', 'title')->with('TaskGroup')->get();
        if (!empty($task)) {
            $task = $task->toArray();
        }

        if (!$request->group_id) {
            // Sorting record for parent->child task relation
            array_multisort(
                array_column($task, 'group_id'),
                array_column($task, 'parent_task_id'),
                array_column($task, 'id'),
                $task
            );

            $array_by_group = Utility::group_by('group_id', $task);
        }

        $array_by_group = $task;

        //dd($array_by_group);
        return response()->json($array_by_group);
    }

    public function bug($project_id)
    {
        $user = Auth::user();
        if ($user->can('manage bug report')) {
            $project = Projects::find($project_id);
            if (!empty($project) && $project->created_by == Auth::user()->creatorId()) {
                if ($user->type != 'company') {
                    $arrProjectUsers = $project->project_user()->pluck('user_id')->toArray();
                    array_push($arrProjectUsers, $project->client);

                    if (!in_array($user->id, $arrProjectUsers)) {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }

                if ($user->type == 'company' || $user->type == 'client') {
                    $bugs = Bug::where('project_id', '=', $project_id)->get();
                } else {
                    $bugs = Bug::where('assign_to', '=', $user->id)->where('project_id', '=', $project_id)->get();
                }

                return view('projects.bug', compact('project', 'bugs'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugCreate($project_id)
    {
        if (Auth::user()->can('create bug report')) {
            $priority     = Bug::$priority;
            $status       = BugStatus::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('title', 'id');
            $project_user = Userprojects::where('project_id', $project_id)->get();
            $users        = array();
            foreach ($project_user as $user) {
                $user               = $user->project_users->first();
                $users[$user['id']] = $user['name'];
            }

            return view('projects.bugCreate', compact('status', 'project_id', 'priority', 'users'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function bugNumber()
    {
        $latest = Bug::where('created_by', '=', Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->bug_id + 1;
    }

    public function bugStore(Request $request, $project_id)
    {
        if (Auth::user()->can('create bug report')) {
            $validator = \Validator::make(
                $request->all(),
                [

                    'title' => 'required',
                    'priority' => 'required',
                    'status' => 'required',
                    'assign_to' => 'required',
                    'start_date' => 'required',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('task.bug', $project_id)->with('error', $messages->first());
            }

            $usr         = Auth::user();
            $userProject = Userprojects::where('project_id', '=', $project_id)->pluck('user_id')->toArray();
            $project     = Projects::where('id', '=', $project_id)->first();

            $bug              = new Bug();
            $bug->bug_id      = $this->bugNumber();
            $bug->project_id  = $project_id;
            $bug->title       = $request->title;
            $bug->priority    = $request->priority;
            $bug->start_date  = $request->start_date;
            $bug->due_date    = $request->due_date;
            $bug->description = $request->description;
            $bug->status      = $request->status;
            $bug->assign_to   = $request->assign_to;
            $bug->created_by  = Auth::user()->id;
            $bug->save();

            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'log_type' => 'Create Bug',
                    'remark' => json_encode(['title' => $bug->title]),
                ]
            );

            $projectArr = [
                'project_id' => $project_id,
                'name' => $project->name,
                'updated_by' => $usr->id,
            ];

            foreach (array_merge($userProject, [$project->client]) as $u) {
                Utility::sendNotification('create_bug', $u, $projectArr);
            }

            return redirect()->back()->with('success', __('Bug successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugEdit($project_id, $bug_id)
    {
        if (Auth::user()->can('edit bug report')) {
            $bug          = Bug::find($bug_id);
            $priority     = Bug::$priority;
            $status       = BugStatus::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('title', 'id');
            $project_user = Userprojects::where('project_id', $project_id)->get();
            $users        = array();
            foreach ($project_user as $user) {
                $user               = $user->project_users->first();
                $users[$user['id']] = $user['name'];
            }

            return view('projects.bugEdit', compact('status', 'project_id', 'priority', 'users', 'bug'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugUpdate(Request $request, $project_id, $bug_id)
    {
        if (Auth::user()->can('edit bug report')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'priority' => 'required',
                    'status' => 'required',
                    'assign_to' => 'required',
                    'start_date' => 'required',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('task.bug', $project_id)->with('error', $messages->first());
            }
            $bug              = Bug::find($bug_id);
            $bug->title       = $request->title;
            $bug->priority    = $request->priority;
            $bug->start_date  = $request->start_date;
            $bug->due_date    = $request->due_date;
            $bug->description = $request->description;
            $bug->status      = $request->status;
            $bug->assign_to   = $request->assign_to;
            $bug->save();

            return redirect()->route('task.bug', $project_id)->with('success', __('Bug successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugDestroy($project_id, $bug_id)
    {
        if (Auth::user()->can('delete bug report')) {
            $bug = Bug::find($bug_id);
            $bug->delete();

            return redirect()->back()->with('success', __('Bug successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugKanban($project_id)
    {
        $user = Auth::user();
        if ($user->can('move bug report')) {
            $project = Projects::find($project_id);
            if (!empty($project) && $project->created_by == $user->creatorId()) {
                if ($user->type != 'company') {
                    $arrProjectUsers = $project->project_user()->pluck('user_id')->toArray();
                    array_push($arrProjectUsers, $project->client);

                    if (!in_array($user->id, $arrProjectUsers)) {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }

                if ($user->type == 'company' || $user->type == 'client') {
                    $bugStatus = BugStatus::where('created_by', '=', Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
                } else {
                    $bugStatus = BugStatus::where('created_by', '=', Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
                }

                return view('projects.bugKanban', compact('project', 'bugStatus'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugKanbanOrder(Request $request)
    {
        if (Auth::user()->can('move bug report')) {
            $post   = $request->all();
            $bug    = Bug::find($post['bug_id']);
            $status = BugStatus::find($post['status_id']);

            if (!empty($status)) {
                $bug->status = $post['status_id'];
                $bug->save();
            }

            foreach ($post['order'] as $key => $item) {
                $bug_order         = Bug::find($item);
                $bug_order->order  = $key;
                $bug_order->status = $post['status_id'];
                $bug_order->save();
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bugShow($project_id, $bug_id)
    {
        $bug = Bug::find($bug_id);

        return view('projects.bugShow', compact('bug'));
    }

    public function bugCommentStore(Request $request, $project_id, $bug_id)
    {
        $post               = [];
        $post['bug_id']     = $bug_id;
        $post['comment']    = $request->comment;
        $post['created_by'] = Auth::user()->authId();
        $post['user_type']  = Auth::user()->type;
        $comment            = BugComment::create($post);
        $comment->deleteUrl = route('bug.comment.destroy', [$comment->id]);

        return $comment->toJson();
    }

    public function bugCommentDestroy($comment_id)
    {
        $comment = BugComment::find($comment_id);
        $comment->delete();

        return "true";
    }

    public function bugCommentStoreFile(Request $request, $bug_id)
    {

        // dd(round(($file_size / 1024) / 1024, 2) . ' MB');
        $file_size = $request->file->getSize();
        $request->validate(
            ['file' => 'required']
        );
        $fileName = $bug_id . time() . "_" . $request->file->getClientOriginalName();
        $dir        = 'bugs/';
        $path = Utility::upload_file($request, 'file', $fileName, $dir, []);

        // dd($path);
        if ($path['flag'] == 1) {
            $url = $path['url'];
        } else {
            return redirect()->back()->with('error', __($path['msg']));
        }

        // $request->file->storeAs('bugs', $fileName);

        $post['bug_id']     = $bug_id;
        $post['file']       = $fileName;
        $post['name']       = $request->file->getClientOriginalName();
        $post['extension']  = "." . $request->file->getClientOriginalExtension();
        $post['file_size']  = round(($file_size / 1024) / 1024, 2) . ' MB';
        $post['created_by'] = Auth::user()->authId();
        $post['user_type']  = Auth::user()->type;

        $BugFile            = BugFile::create($post);
        $BugFile->deleteUrl = route('bug.comment.file.destroy', [$BugFile->id]);

        return $BugFile->toJson();
    }

    public function bugCommentDestroyFile(Request $request, $file_id)
    {
        $commentFile = BugFile::find($file_id);
        $path        = storage_path('bugs/' . $commentFile->file);
        if (file_exists($path)) {
            \File::delete($path);
        }
        $commentFile->delete();

        return "true";
    }
    public function tracker($id)
    {

        $treckers = TimeTracker::where('project_id', $id)->get();
        return view('time_trackers.index', compact('treckers', 'id'));
    }


    public function getProjectChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration'] && $arrParam['duration'] == 'week') {
            $previous_week = Utility::getFirstSeventhWeekDay(-1);
            foreach ($previous_week['datePeriod'] as $dateObject) {
                $arrDuration[$dateObject->format('Y-m-d')] = $dateObject->format('D');
            }
        }

        $arrTask = [
            'label' => [],
            'color' => [],
        ];

        foreach ($arrDuration as $date => $label) {
            $objProject = Task::select('status', DB::raw('count(*) as total'))->whereDate('updated_at', '=', $date)->groupBy('status');

            if (isset($arrParam['project_id'])) {
                $objProject->where('project_id', '=', $arrParam['project_id']);
            }
            if (isset($arrParam['workspace_id'])) {
                $objProject->whereIn(
                    'project_id',
                    function ($query) use ($arrParam) {
                        $query->select('id')->from('projects')->where('workspace', '=', $arrParam['workspace_id']);
                    }
                );
            }
            $data = $objProject->pluck('total', 'status')->all();
            $arrTask['label'][] = __($label);
        }

        return $arrTask;
    }

    //Task Groups methods
    /**
     * Display a listing of the task group.
     *
     * @return \Illuminate\Http\Response
     */
    public function taskGroup($project_id)
    {

        if (\Auth::user()->can('manage task')) {

            $taskgroups = TaskGroup::where('created_by', '=', \Auth::user()->creatorId())
                ->where('project_id', $project_id)->get();
            if (!empty($taskgroups)) {
                $taskgroups = $taskgroups;
            }
            // $taskgroups = TaskGroup::toSql();
            //dd($taskgroups);
            return view('taskgroup.index', compact('taskgroups', 'project_id'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function taskgroupCreate($project_id)
    {
        if (\Auth::user()->can('create task')) {


            return view('taskgroup.create', compact("project_id"));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function taskgroupStore(Request $request, $project_id)
    {
        if (\Auth::user()->can('create task')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.taskgroup', [$project_id])->with('error', $messages->first());
            }

            $taskgroup             = new TaskGroup();
            $taskgroup->name       = $request->name;
            $taskgroup->project_id = $project_id;
            $taskgroup->created_by = \Auth::user()->creatorId();
            $taskgroup->save();

            return redirect()->route('project.taskgroup', [$project_id])->with('success', __('Group successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function taskgroupEdit($id)
    {

        if (\Auth::user()->can('edit task')) {
            $taskgroup = TaskGroup::findOrfail($id);

            if ($taskgroup->created_by == \Auth::user()->creatorId()) {
                return view('taskgroup.edit', compact('taskgroup'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function taskgroupUpdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit task')) {
            $taskgroup = TaskGroup::findOrfail($id);
            if ($taskgroup->created_by == \Auth::user()->creatorId()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:100'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('project.taskgroup', ['id' => $taskgroup->project_id])->with('error', $messages->first());
                }

                $taskgroup->name  = $request->name;
                $taskgroup->save();

                return redirect()->route('project.taskgroup', ['id' => $taskgroup->project_id])->with('success', __('Group name successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }


    public function teamTimeSheetShowMore(Request $request)
    {
        $startDate = $request->lastDate ?? '';
        $endDate = $request->endDate ?? '';
        $datewise = $request->datewise ?? '';
        $projectId = $request->projectId ?? '';
        $teams = $request->teams ?? [];
        $timeSheets = [];

        $user        = Auth::user();

        // get all the team users
        if(in_array('-1',$teams) || empty($teams))
        {
            // Get all the members of current user
            $teams = $user->getTeamUsers()->toArray();
            $teams = array_column($teams, 'id');
        }
        $teams[] = $user->id;

        if($projectId == '-1')
        {
            $user_projects = $user->getUserProjects($teams)->pluck('name', 'project_id')->toArray();
            $project_ids = array_keys($user_projects);
        }else{
            $project_ids = [$projectId];
        }
        
        if($endDate != '')
        {
            $nextTimeSheets = Timesheet::whereIn('project_id', $project_ids)
                        ->select('date')
                        ->whereIn('user_id', $teams)
                        ->where('date', '<', $startDate)
                        ->where('date', '>=', $endDate)
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->skip(4)->take(1)
                        ->get()->toArray();
        }else{
            $nextTimeSheets = Timesheet::whereIn('project_id', $project_ids)
                        ->select('date')
                        ->whereIn('user_id', $teams)
                        ->where('date', '<', $startDate)
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->skip(4)->take(1)
                        ->get()->toArray();
        }

        //dd($nextTimeSheets);

        $showMoreButtonStatus = (isset($nextTimeSheets[0]['date']) && !empty($nextTimeSheets[0]['date'])) ? true : false;
        if (isset($nextTimeSheets[0]['date']) && !empty($nextTimeSheets[0]['date'])) {
            $showMoreButtonStatus = true;
            $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.project_id', $project_ids)
                ->where('timesheets.date', '>=', $nextTimeSheets[0]['date'])
                ->where('timesheets.date', '<', $startDate)
                ->whereIn('timesheets.user_id', $teams)
                ->orderBy('date', 'desc')
                ->get();
        } else {
            $showMoreButtonStatus = false;

            $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.project_id', $project_ids)
                ->where('timesheets.date', '<', $startDate)
                ->where('timesheets.date', '>=', $endDate)
                ->whereIn('timesheets.user_id', $teams)
                ->orderBy('date', 'desc')
                ->get();
        }
        
        if ($totalTimeSheet->count() > 0) {
            $result = $this->dateWiseTimeSheets($totalTimeSheet);
            $timeSheets = $result[0];
            $finalTime = $result[1];
            $totalBillableTimeSum = $result[2];
            $dateFormat = $result[3];
        }
        if ($timeSheets) {
            $html = view('projects.timeSheetShowMore', compact('timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat'))->render();

            $lastDate = ($showMoreButtonStatus) ? $nextTimeSheets[0]['date'] : '';

            return [$html, $showMoreButtonStatus, $lastDate];
        }
        return [];
    }


    public function timeSheetShowMore(Request $request)
    {
        $startDate = $request->lastDate;
        $endDate = $request->endDate;
        $projectId = $request->projectId;

        $user        = Auth::user();
        
        if($projectId != '-1')
        {
            $project_ids = [$projectId];
        }else{
            $project_ids = $user->projects->pluck('id')->toArray();
        }

        if($endDate != '')
        {
            $nextTimeSheets = Timesheet::whereIn('project_id', $project_ids)
                        ->select('date')
                        ->where('user_id', '=', $user->id)
                        ->where('date', '<', $startDate)
                        ->where('date', '>=', $endDate)
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->skip(4)->take(1)
                        ->get()->toArray();
        }else{
            $nextTimeSheets = Timesheet::whereIn('project_id', $project_ids)
                        ->select('date')
                        ->where('user_id', '=', $user->id)
                        ->where('date', '<', $startDate)
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->skip(4)->take(1)
                        ->get()->toArray();
        }

        $showMoreButtonStatus = (isset($nextTimeSheets[0]['date']) && !empty($nextTimeSheets[0]['date'])) ? true : false;
        if (isset($nextTimeSheets[0]['date']) && !empty($nextTimeSheets[0]['date'])) {
            $showMoreButtonStatus = true;
            $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.project_id', $project_ids)
                ->where('timesheets.date', '>=', $nextTimeSheets[0]['date'])
                ->where('timesheets.date', '<', $startDate)
                ->where('timesheets.user_id', '=', $user->id)
                ->orderBy('date', 'desc')
                ->get();
        } else {
            $showMoreButtonStatus = false;

            $totalTimeSheet = Timesheet::leftJoin('tasks', function ($join) {
                $join->on('timesheets.task_id', '=', 'tasks.id');
            })->select('tasks.estimated_mins', 'timesheets.*')
                ->whereIn('timesheets.project_id', $project_ids)
                ->where('timesheets.date', '<', $startDate)
                ->where('timesheets.date', '>=', $endDate)
                ->where('timesheets.user_id', '=', $user->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        $result = $this->dateWiseTimeSheets($totalTimeSheet);
        $timeSheets = $result[0];
        $finalTime = $result[1];
        $totalBillableTimeSum = $result[2];
        $dateFormat = $result[3];
        
        if ($timeSheets) {
            $html = view('projects.timeSheetShowMore', compact('timeSheets', 'finalTime', 'totalBillableTimeSum', 'dateFormat'))->render();

            $lastDate = ($showMoreButtonStatus) ? $nextTimeSheets[0]['date'] : '';

            return [$html, $showMoreButtonStatus, $lastDate];
        }
        return [];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function teamTimeSheetEdit($user_id, $timeSheet_id)
    {
        if (Auth::user()->can('edit timesheet')) {
            $projects = Auth::user()->getUserProjects([$user_id])->pluck('name', 'project_id')->prepend('Please Select Project', '');
            $timeSheet = Timesheet::find($timeSheet_id);
            return view('projects.timesheetEdit', compact('timeSheet', 'projects'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
