<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Projects;
use App\Models\User;
use App\Models\Utility;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
       
    }
    public function store(LoginRequest $request)
    {
        if(env('RECAPTCHA_MODULE') == 'yes')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else
        { 
        $validation=[];
        }
        $this->validate($request, $validation);

        $request->authenticate();

        $request->session()->regenerate();
       
        $user = Auth::user();

        if($user->delete_status == 0)
        {
             auth()->logout();
         }

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


                    $user =\Auth::user();
        if($user->type == 'company')
        {
            $plan = plan::find($user->plan);
            if($plan)
            {
                if($plan->duration != 'unlimited')
                {
                    $datetime1 = new \DateTime($user->plan_expire_date);
                    $datetime2 = new \DateTime(date('Y-m-d'));
                    $interval = $datetime2->diff($datetime1);
                    $days =$interval->format('%r%a');
                    if($days <= 0)
                    {
                        $user->assignplan(1);
                        return redirect()->intended(RouteServiceProvider::HOME)->with('error',__('Yore plan is expired'));
                    }
                }
            }
        }
                    return redirect()->route('dashboard')->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }
        }

          return redirect()->intended(RouteServiceProvider::HOME);

    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    
    public function showLoginForm($lang = '')
    {
        if(empty($lang))
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {
        if(empty($lang))
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.forgot-password', compact('lang'));
    }
}