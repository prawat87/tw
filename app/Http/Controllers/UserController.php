<?php

namespace App\Http\Controllers;

use File;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Utility;
use App\Models\Notification;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if (\Auth::user()->can('manage user')) {
            $users = User::where('id', '!=', $user->id)->get();
            // if(\Auth::user()->type == 'super admin')
            // {
            //     $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
            // }
            // else
            // {
            //     $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();
            // }

            return view('user.index')->with('users', $users);
        } else {
            return redirect()->back();
        }
    }

    function UserNumber()
    {
        $latest = User::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->id + 1;
    }



    public function importFile()
    {
        return view('user.import');
    }


    public function import(Request $request)
    {
        $rules = [
            'file' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $Users = (new UsersImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($Users) - 1;
        $errorArray    = [];


        for ($i = 1; $i <= count($Users) - 1; $i++) {
            $user = $Users[$i];

            $userByEmail = User::where('email', $user[1])->first();

            if (!empty($userByEmail)) {
                $userData = $userByEmail;
            } else {
                $userData            = new User();
                $userData->id = $this->UserNumber();
            }

            $userData->name               = $user[0];
            $userData->email              = $user[1];
            $userData->password           = Hash::make($user[2]);
            $userData->type               = $user[3];
            $userData->lang               = Utility::getValByName('default_language');
            $userData->plan               = Plan::first()->id;
            $userData->created_by         = \Auth::user()->creatorId();

            if (empty($userData)) {
                $errorArray[] = $userData;
            } else {
                $userData->save();
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

    public function create()
    {
        $allUsers = [];
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');

        $users = User::select('name', 'id', 'type')->where('is_active', '=', 1)->where('id', '!=', $user->id)->get();
        foreach ($users as $usr) {
            $allUsers[$usr['id']] = $usr['name'] . ' [' . $usr['type'] . ']';
        }

        if (\Auth::user()->can('create user')) {
            return view('user.create', compact('roles', 'allUsers'));
        } else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create user')) {
            $resp = '';

            if (\Auth::user()->type == 'super admin') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:6',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users.index')->with('error', $messages->first());
                }

                $user               = new User();
                $user['name']       = $request->name;
                $user['email']      = $request->email;
                $user['password']   = Hash::make($request->password);
                $user['type']       = 'company';
                $user['lang']       = Utility::getValByName('default_language');
                $user['created_by'] = \Auth::user()->creatorId();
                $user['plan']       = Plan::first()->id;
                $user->save();

                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                $user->makeEmployeeRole();
                $user->userDefaultData();
            } else {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:6',
                        'role' => 'required',
                        'user_parent_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users.index')->with('error', $messages->first());
                }

                $objUser    = \Auth::user();
                $total_user = $objUser->countUsers();
                $plan       = Plan::find($objUser->plan);
                $password   = $request->password;

                if ($total_user < $plan->max_users || $plan->max_users == -1) {
                    $role_r                = Role::findById($request->role);
                    $request['password']   = Hash::make($request->password);
                    $request['type']       = $role_r->name;
                    $request['lang']       = Utility::getValByName('default_language');
                    $request['created_by'] = \Auth::user()->creatorId();
                    $user                  = User::create($request->all());


                    $uArr = [
                        'email' => $request->email,
                        'password' => $password,
                    ];

                    // Send Email
                    $resp = Utility::sendEmailTemplate('New User', $user->id, $uArr);

                    $user->assignRole($role_r);
                } else {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }

            return redirect()->route('users.index')->with(
                'success',
                __('User successfully added.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '')
            );
        } else {
            return redirect()->back();
        }
    }





    public function edit($id)
    {
        $allUsers = [];
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');

        $users = User::select('name', 'id', 'type')->where('is_active', '=', 1)->where('id', '!=', $user->id)->get();
        foreach ($users as $usr) {
            $allUsers[$usr['id']] = $usr['name'] . ' [' . $usr['type'] . ']';
        }

        if (\Auth::user()->can('edit user')) {
            $user = User::findOrFail($id);
            $user_parent_id = $user->user_parent_id ?? null;

            return view('user.edit', compact('user', 'roles', 'allUsers', 'user_parent_id'));
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit user')) {
            if (\Auth::user()->type == 'super admin') {
                $user = User::findOrFail($id);

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users.index')->with('error', $messages->first());
                }

                $input = $request->all();
                $user->fill($input)->save();

                return redirect()->route('users.index')->with(
                    'success',
                    __('User successfully updated.')
                );
            } else {
                $user      = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                        'role' => 'required',
                        'user_parent_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users.index')->with('error', $messages->first());
                }

                $role          = Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $res = $user->fill($input)->save();
                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    __('User successfully updated.')
                );
            }
        } else {
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete user')) {
            $user = User::find($id);
            if ($user) {
                if (\Auth::user()->type == 'super admin') {
                    if ($user->delete_status == 0) {
                        $user->delete_status = 1;
                    } else {
                        $user->delete_status = 0;
                    }
                    $user->save();
                } else {
                    $user->delete();
                    $user->destroyUserProjectInfo($user->id);
                    $user->removeUserLeadInfo($user->id);
                    $user->destroyUserNotesInfo($user->id);
                    $user->removeUserExpenseInfo($user->id);
                    $user->removeUserTaskInfo($user->id);
                    $user->destroyUserTaskAllInfo($user->id);
                }

                return redirect()->route('users.index')->with('success', __('User Deleted Successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back();
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('user.profile')->with('userDetail', $userDetail);
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
            ]
        );

        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $settings = Utility::getStorageSetting();
            $dir        = 'avatar/';
            $url = '';
            $dir        = 'productimages/';
            $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('profile', \Auth::user()->id)->with('error', __($path['msg']));
            }

            $user->avatar  = $fileNameToStore;
        }

        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    public function updatePassword(Request $request)
    {
        if (\Auth::user()->can('change password account')) {
            if (Auth::Check()) {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if (Hash::check($request_data['current_password'], $current_password)) {
                    $user_id            = Auth::User()->id;
                    $obj_user           = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->save();

                    return redirect()->back()->with('success', __('Password updated successfully.'));
                } else {
                    return redirect()->back()->with('error', __('Please enter correct current password.'));
                }
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function upgradePlan($user_id)
    {
        $user  = User::find($user_id);
        $plans = Plan::get();

        return view('user.plan', compact('user', 'plans'));
    }

    public function activePlan($user_id, $plan_id)
    {
        $user       = User::find($user_id);
        $user->plan = $plan_id;
        $user->save();
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);

        if ($assignPlan['is_success'] == true && !empty($plan)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => env('CURRENCY'),
                    'txn_id' => '',
                    'payment_type' => __('Manually Upgrade By Super Admin'),
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Plan successfully activated.'));
    }

    public function notificationSeen($user_id)
    {
        Notification::where('user_id', '=', $user_id)->update(['is_read' => 1]);

        return response()->json(['is_success' => true], 200);
    }

    public function employeePassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);



        return view('user.reset', compact('user'));
    }

    public function employeePasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->back()->with(
            'success',
            'Password successfully updated.'
        );
    }
}
