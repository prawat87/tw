<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use Notifiable;
    use HasRoles, HasApiTokens;

    // protected $appends = ['profile'];

    protected $fillable = [
        'name',
        'email',
        'user_parent_id',
        'password',
        'type',
        'avatar',
        'lang',
        'delete_status',
        'plan',
        'plan_expire_date',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public    $settings;

    public function authId()
    {
        return $this->id;
    }

    public function creatorId()
    {
        if ($this->type == 'company' || $this->type == 'super admin') {
            return $this->id;
        } else {

            return $this->created_by;
        }
    }

    public function createId()
    {
        if ($this->type == 'super admin') {
            return $this->id;
        } else {
            return $this->created_by;
        }
    }

    public function currentLanguage()
    {
        return $this->lang;
    }

    public function getGuard()
    {
        return $this->guard;
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Projects', 'userprojects', 'user_id', 'project_id');
    }

    public function user_project()
    {
        if (\Auth::user()->type != 'client') {
            return $this->belongsToMany('App\Models\Projects', 'userprojects', 'user_id', 'project_id')->count();
        } else {
            return Projects::where('client', '=', $this->authId())->count();
        }
    }

    public function user_assign_task()
    {
        return Task::where('assign_to', '=', $this->authId())->count();
    }

    public function client_lead()
    {
        return Leads::where('client', '=', $this->authId())->count();
    }

    public function user_expense()
    {
        return $this->hasMany('App\Models\Expense', 'user_id', 'id')->sum('amount');
    }

    public function client_project()
    {
        return $this->hasMany('App\Models\Projects', 'client', 'id')->count();
    }

    public function client_project_budget()
    {
        return $this->hasMany('App\Models\Projects', 'client', 'id')->sum('price');
    }

    public function priceFormat($price)
    {
        $settings = Utility::settings();

        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public function timeFormat($time)
    {
        $settings = Utility::settings();

        return date($settings['site_time_format'], strtotime($time));
    }

    public function clientPermission($project_id)
    {
        return ClientPermission::where('client_id', '=', $this->id)->where('project_id', '=', $project_id)->first();
    }

    public function last_leadstage()
    {
        return Leadstages::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function total_lead()
    {
        if (\Auth::user()->type == 'company') {
            return Leads::where('created_by', '=', $this->creatorId())->count();
        } elseif (\Auth::user()->type == 'client') {
            return Leads::where('client', '=', $this->authId())->count();
        } else {
            return Leads::where('owner', '=', $this->authId())->count();
        }
    }

    public function total_complete_lead($last_leadstage)
    {
        if (\Auth::user()->type == 'company') {
            return Leads::where('created_by', '=', $this->creatorId())->where('stage', '=', $last_leadstage)->count();
        } elseif (\Auth::user()->type == 'client') {
            return Leads::where('client', '=', $this->authId())->where('stage', '=', $last_leadstage)->count();
        } else {
            return Leads::where('owner', '=', $this->authId())->where('stage', '=', $last_leadstage)->count();
        }
    }

    public function created_total_project_task()
    {
        if (\Auth::user()->type == 'company') {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->count();
        } elseif (\Auth::user()->type == 'client') {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client', '=', $this->authId())->count();
        } else {
            return Task::select('tasks.*', 'userprojects.id as up_id')->join('userprojects', 'userprojects.project_id', '=', 'tasks.project_id')->where('userprojects.user_id', '=', $this->authId())->count();
        }
    }

    public function created_top_due_task()
    {
        if (\Auth::user()->type == 'company') {
            return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'tasks.assign_to', 'projectstages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        } elseif (\Auth::user()->type == 'client') {
            return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'tasks.assign_to', 'projectstages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('projects.client', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        } else {
            return Task::select('tasks.*', 'tasks.due_date as task_due_date', 'userprojects.id as up_id', 'projects.name as project_name', 'projectstages.name as stage_name')->join('userprojects', 'userprojects.project_id', '=', 'tasks.project_id')->join('projects', 'userprojects.project_id', '=', 'projects.id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('userprojects.user_id', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy(
                'tasks.due_date',
                'ASC'
            )->get();
        }
    }

    public function project_all_task()
    {
        if (\Auth::user()->type == 'company') {
            return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.priority', 'tasks.due_date as task_due_date', 'tasks.start_date as task_start_date', 'tasks.assign_to', 'projectstages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('projects.created_by', '=', $this->creatorId())->orderBy('task_due_date', 'ASC')->get();
        } elseif (\Auth::user()->type == 'client') {
            return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.priority', 'tasks.priority', 'tasks.due_date as task_due_date', 'tasks.start_date as task_start_date', 'tasks.assign_to', 'projectstages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('projects.client', '=', $this->authId())->orderBy('task_due_date', 'ASC')->get();
        } else {
            return Task::select('tasks.*', 'tasks.id as task_id', 'tasks.due_date as task_due_date', 'tasks.start_date as task_start_date', 'userprojects.id as up_id', 'projects.name as name', 'projectstages.name as stage_name')->join('userprojects', 'userprojects.project_id', '=', 'tasks.project_id')->join('projects', 'userprojects.project_id', '=', 'projects.id')->join('projectstages', 'tasks.stage', '=', 'projectstages.id')->where('userprojects.user_id', '=', $this->authId())->orderBy(
                'tasks.due_date',
                'ASC'
            )->get();
        }
    }

    public function project_all_bug()
    {
        if (\Auth::user()->type == 'company') {
            return Bug::select('projects.*', 'bugs.id as bug_id', 'bugs.title', 'bugs.priority', 'bugs.due_date as bug_due_date', 'bugs.start_date as bug_start_date', 'bugs.assign_to', 'bug_statuses.title as status_title')->join('projects', 'projects.id', '=', 'bugs.project_id')->join('bug_statuses', 'bugs.status', '=', 'bug_statuses.id')->where('projects.created_by', '=', $this->creatorId())->get();
        } elseif (\Auth::user()->type == 'client') {
            return Bug::select('projects.*', 'bugs.id as bug_id', 'bugs.title', 'bugs.priority', 'bugs.priority', 'bugs.due_date as bug_due_date', 'bugs.start_date as bug_start_date', 'bugs.assign_to', 'bug_statuses.title as status_title')->join('projects', 'projects.id', '=', 'bugs.project_id')->join('bug_statuses', 'bugs.status', '=', 'bug_statuses.id')->where('projects.client', '=', $this->authId())->get();
        } else {
            return Bug::select('bugs.*', 'bugs.id as bug_id', 'bugs.start_date as bug_start_date', 'bugs.due_date as bug_due_date', 'userprojects.id as up_id', 'projects.name as name', 'bug_statuses.title as status_title')->join('userprojects', 'userprojects.project_id', '=', 'bugs.project_id')->join('projects', 'userprojects.project_id', '=', 'projects.id')->join('bug_statuses', 'bugs.status', '=', 'bug_statuses.id')->where('userprojects.user_id', '=', $this->authId())->get();
        }
    }

    public function project_due_invoice()
    {
        $currentDate = date('Y-m-d');
        if (\Auth::user()->type == 'company') {

            return Invoice::select('invoices.*', 'projects.name')->join('projects', 'projects.id', '=', 'invoices.project_id')->where('invoices.created_by', '=', $this->creatorId())->where('invoices.due_date', '<', $currentDate)->get();
        } elseif (\Auth::user()->type == 'client') {
            return Bug::select('projects.*', 'bugs.id as bug_id', 'bugs.title', 'bugs.priority', 'bugs.priority', 'bugs.due_date as bug_due_date', 'bugs.start_date as bug_start_date', 'bugs.assign_to', 'bug_statuses.title as status_title')->join('projects', 'projects.id', '=', 'bugs.project_id')->join('bug_statuses', 'bugs.status', '=', 'bug_statuses.id')->where('projects.client', '=', $this->authId())->get();
        }
    }

    public function total_project()
    {
        return Projects::where('created_by', '=', $this->creatorId())->count();
    }

    public function last_projectstage()
    {
        return Projectstages::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function project_complete_task($project_last_stage)
    {

        if (\Auth::user()->type == 'company') {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.stage', '=', $project_last_stage)->count();
        } elseif (\Auth::user()->type == 'client') {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client', '=', $this->authId())->where('tasks.stage', '=', $project_last_stage)->count();
        } else {
            return Task::select('tasks.*', 'userprojects.id as up_id')->join('userprojects', 'userprojects.project_id', '=', 'tasks.project_id')->where('userprojects.user_id', '=', $this->authId())->where('tasks.stage', '=', $project_last_stage)->count();
        }
    }

    public function created_total_invoice()
    {
        if (\Auth::user()->type == 'company') {
            return Invoice::where('created_by', '=', $this->creatorId())->limit(5)->get();
        } elseif (\Auth::user()->type == 'client') {
            return Invoice::select('invoices.*', 'projects.client')->join('projects', 'projects.id', '=', 'invoices.project_id')->where(
                'projects.client',
                '=',
                $this->authId()
            )->get();
        }
    }

    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if ($plan) {
            $this->plan = $plan->id;
            if ($plan->duration == 'month') {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            } elseif ($plan->duration == 'year') {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            } else {
                $this->plan_expire_date = null;
            }
            $this->save();

            $projects = Projects::where('created_by', '=', \Auth::user()->creatorId())->get();
            $users    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get();
            $clients  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get();

            $projectCount = 0;
            foreach ($projects as $project) {
                $projectCount++;
                if ($projectCount <= $plan->max_projects) {
                    $project->is_active = 1;
                    $project->save();
                } else {
                    $project->is_active = 0;
                    $project->save();
                }
            }

            $userCount = 0;
            foreach ($users as $user) {
                $userCount++;
                if ($userCount <= $plan->max_users) {
                    $user->is_active = 1;
                    $user->save();
                } else {
                    $user->is_active = 0;
                    $user->save();
                }
            }
            $clientCount = 0;
            foreach ($clients as $client) {
                $clientCount++;
                if ($clientCount <= $plan->max_clients) {
                    $client->is_active = 1;
                    $client->save();
                } else {
                    $client->is_active = 0;
                    $client->save();
                }
            }

            return ['is_success' => true];
        } else {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted.',
            ];
        }
    }

    public function countUsers()
    {

        return User::where('type', '!=', 'client')->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countClient()
    {

        return User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countProject()
    {
        return Projects::where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countCompany()
    {
        return User::where('type', '=', 'company')->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'company')->whereNotIn(
            'plan',
            [
                0,
                1,
            ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function makeEmployeeRole($cid = '')
    {
        $employeeRole = Role::firstOrCreate(
            [
                'name' => 'employee',
                'created_by' => $this->id,
            ]
        );

        $empPermission = [
            'manage account',
            'change password account',
            'edit account',
            'manage project',
            'show project',
            'manage task',
            'move task',
            'show task',
            'create checklist',
            'manage note',
            'create note',
            'edit note',
            'delete note',
            'manage lead',
            'manage bug report',
            'create bug report',
            'edit bug report',
            'delete bug report',
            'move bug report',
            'manage timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet',
        ];
        foreach ($empPermission as $ap) {
            $permission = Permission::findByName($ap);
            $employeeRole->givePermissionTo($permission);
        }

        if (!empty($cid)) {
            $cmpUser = User::create(
                [
                    'name' => 'User',
                    'email' => 'user@example.com',
                    'password' => \Hash::make('1234'),
                    'type' => 'employee',
                    'lang' => 'en',
                    'avatar' => '',
                    'created_by' => $cid,
                ]
            );
            $cmpUser->assignRole($employeeRole);
        }
    }

    public function userDefaultData()
    {
        $id = $this->id;

        $colors = [
            '#e7505a',
            '#F4D03F',
            '#32c5d2',
            '#1BBC9B',
        ];

        // LeadStages
        $leadStages = [
            'Initial Contact',
            'Qualification',
            'Proposal',
            'Close',
        ];
        foreach ($leadStages as $key => $stage) {
            Leadstages::create(
                [
                    'name' => $stage,
                    'color' => $colors[$key],
                    'order' => $key,
                    'created_by' => $id,
                ]
            );
        }

        // ProjectStages
        $projectStages = [
            'To Do',
            'In Progress',
            'Bugs',
            'Done',
        ];
        foreach ($projectStages as $key => $stage) {
            Projectstages::create(
                [
                    'name' => $stage,
                    'color' => $colors[$key],
                    'order' => $key,
                    'created_by' => $id,
                ]
            );
        }

        // LeadSource
        $leadSource = [
            'Email',
            'Facebook',
            'Google',
            'Phone',
        ];
        foreach ($leadSource as $source) {
            Leadsource::create(
                [
                    'name' => $source,
                    'created_by' => $id,
                ]
            );
        }

        // Labels
        $labels = [
            'On Hold' => 'bg-red-thunderbird bg-font-red-thunderbird',
            'New' => 'bg-yellow-casablanca bg-font-yellow-casablanca',
            'Pending' => 'bg-purple-intense bg-font-purple-intense',
            'Loss' => 'bg-purple-medium bg-font-purple-medium',
            'Win' => 'bg-yellow-soft bg-font-yellow-soft',
        ];
        foreach ($labels as $label => $color) {
            Labels::create(
                [
                    'name' => $label,
                    'color' => $color,
                    'created_by' => $id,
                ]
            );
        }

        // ProductUnits
        $productUnits = [
            'Kilogram',
            'Piece',
            'Set',
            'Item',
            'Hour',
        ];
        foreach ($productUnits as $unit) {
            Productunits::create(
                [
                    'name' => $unit,
                    'created_by' => $id,
                ]
            );
        }

        // ExpenseCategory
        $expenseCat = [
            'Snack',
            'Server Charge',
            'Bills',
            'Office',
            'Assests',
        ];
        foreach ($expenseCat as $cat) {
            ExpensesCategory::create(
                [
                    'name' => $cat,
                    'created_by' => $id,
                ]
            );
        }

        // Payments
        $payments = [
            'Cash',
            'Bank',
        ];
        foreach ($payments as $payment) {
            Payment::create(
                [
                    'name' => $payment,
                    'created_by' => $id,
                ]
            );
        }

        // Bug Status
        $bugStatus = [
            'Confirmed',
            'Resolved',
            'Unconfirmed',
            'In Progress',
            'Verified',
        ];
        foreach ($bugStatus as $status) {
            BugStatus::create(
                [
                    'title' => $status,
                    'created_by' => $id,
                ]
            );
        }

        // Make Entry In User_Email_Template
        $allEmail = EmailTemplate::all();
        foreach ($allEmail as $email) {
            UserEmailTemplate::create(
                [
                    'template_id' => $email->id,
                    'user_id' => $id,
                    'is_active' => 1,
                ]
            );
        }
    }

    // For Email template Module
    public function defaultEmail()
    {
        // Email Template
        $emailTemplate = [
            'New User',
            'Project Assigned',
            'Task Created',
            'Task Moved',
            'Estimation Assigned',
            'New Contract'
        ];

        foreach ($emailTemplate as $eTemp) {
            EmailTemplate::create(
                [
                    'name' => $eTemp,
                    'from' => (env('APP_NAME')) ? env('APP_NAME') : "Workgo SaaS",
                    'created_by' => $this->id,
                ]
            );
        }

        $defaultTemplate = [
            'New User' => [
                'subject' => 'Login Detail',
                'lang' => [
                    'ar' => '<p>مرحبا،&nbsp;<br>مرحبا بك في {app_name}.</p><p><b>البريد الإلكتروني </b>: {email}<br><b>كلمه السر</b> : {password}</p><p>{app_url}</p><p>شكر،<br>{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Adgangskode</b> : {password}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {email}<br><b>Passwort</b> : {password}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>',
                    'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p><p><b>Email </b>: {email}<br><b>Password</b> : {password}</p><p>{app_url}</p><p>Thanks,<br>{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electrónico </b>: {email}<br><b>Contraseña</b> : {password}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>',
                    'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p><p><b>Email </b>: {email}<br><b>Mot de passe</b> : {password}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>',
                    'it' => '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Parola d\'ordine</b> : {password}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>',
                    'ja' => '<p>こんにちは、&nbsp;<br>へようこそ {app_name}.</p><p><b>Eメール </b>: {email}<br><b>パスワード</b> : {password}</p><p>{app_url}</p><p>おかげで、<br>{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Wachtwoord</b> : {password}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Hasło</b> : {password}</p><p>{app_url}</p><p>Dzięki,<br>{app_name}</p>',
                    'ru' => '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p><p><b>Электронное письмо </b>: {email}<br><b>пароль</b> : {password}</p><p>{app_url}</p><p>Спасибо,<br>{app_name}</p>',
                ],
            ],
            'Project Assigned' => [
                'subject' => 'New Project Assign',
                'lang' => [
                    'ar' => '<p>مرحبا،<br>تم تعيين مشروع جديد لك.</p><p><b>اسم المشروع</b> : {project_name}<br><b>تسمية المشروع</b> :&nbsp; {project_label}<br><b>حالة المشروع </b>: {project_status}</p>',
                    'da' => '<p>Hej,<br>Der er tildelt nyt projekt til dig.</p><p><b>Projekt navn</b> : {project_name}<br><b>Projektetiket</b> :&nbsp; {project_label}<br><b>Projektstatus </b>: {project_status}</p>',
                    'de' => '<p>Hallo,<br>Ihnen wurde ein neues Projekt zugewiesen.</p><p><b>Projektname</b> : {project_name}<br><b>Projektbezeichnung</b> :&nbsp; {project_label}<br><b>Projekt-Status </b>: {project_status}</p>',
                    'en' => '<p>Hello,<br>New Project has been Assign to you.</p><p><b>Project Name</b> : {project_name}<br><b>Project Label</b> :&nbsp; {project_label}<br><b>Project Status </b>: {project_status}</p>',
                    'es' => '<p>Hola,<br>Se le ha asignado un nuevo proyecto.</p><p><b>Nombre del proyecto</b> : {project_name}<br><b>Etiqueta del proyecto</b> :&nbsp; {project_label}<br><b>Estado del proyecto </b>: {project_status}</p>',
                    'fr' => '<p>Bonjour,<br>Un nouveau projet vous a été attribué.</p><p><b>nom du projet</b> : {project_name}<br><b>Libellé du projet</b> :&nbsp; {project_label}<br><b>L\'état du projet </b>: {project_status}</p>',
                    'it' => '<p>Ciao,<br>Nuovo progetto è stato assegnato a te.</p><p><b>Nome del progetto</b> : {project_name}<br><b>Etichetta del progetto</b> :&nbsp; {project_label}<br><b>Stato del progetto </b>: {project_status}</p>',
                    'ja' => '<p>こんにちは、<br>新しいプロジェクトが割り当てられました。</p><p><b>プロジェクト名</b> : {project_name}<br><b>プロジェクトラベル</b> :&nbsp; {project_label}<br><b>プロジェクトの状況 </b>: {project_status}</p>',
                    'nl' => '<p>Hallo,<br>Nieuw project is aan u toegewezen.</p><p><b>Naam van het project</b> : {project_name}<br><b>Projectlabel</b> :&nbsp; {project_label}<br><b>Project status </b>: {project_status}</p>',
                    'pl' => '<p>Witaj,<br>Nowy projekt został Ci przypisany.</p><p><b>Nazwa Projektu</b> : {project_name}<br><b>Etykieta projektu</b> :&nbsp; {project_label}<br><b>Stan projektu </b>: {project_status}</p>',
                    'ru' => '<p>Привет,<br>Новый проект был назначен вам.</p><p><b>название проекта</b> : {project_name}<br><b>Метка проекта</b> :&nbsp; {project_label}<br><b>Статус проекта </b>: {project_status}</p>',
                ],
            ],
            'Task Created' => [
                'subject' => 'New Task Assign',
                'lang' => [
                    'ar' => '<p>مرحبا،<br>تم تعيين مهمة جديدة لك.</p><p><b>اسم المشروع</b> : {project_name}<br><b>تسمية المشروع</b> :&nbsp; {project_label}<br><b>حالة المشروع </b>: {project_status}</p><p><b>اسم المهمة </b>: {task_name}<br><b>أولوية المهمة </b>: {task_priority}<br><b>حالة المهمة </b>: {task_status}</p>',
                    'da' => '<p>Hej,<br>Ny opgave er blevet tildelt til dig.</p><p><b>Projekt navn</b> : {project_name}<br><b>Projektetiket</b> :&nbsp; {project_label}<br><b>Projektstatus </b>: {project_status}</p><p><b>Opgavens navn </b>: {task_name}<br><b>Opgaveprioritet </b>: {task_priority}<br><b>Opgavestatus </b>: {task_status}</p>',
                    'de' => '<p>Hallo,<br>Ihnen wurde eine neue Aufgabe zugewiesen.</p><p><b>Projektname</b> : {project_name}<br><b>Projektbezeichnung</b> :&nbsp; {project_label}<br><b>Projekt-Status </b>: {project_status}</p><p><b>Aufgabennname </b>: {task_name}<br><b>Aufgabenpriorität </b>: {task_priority}<br><b>Aufgabenstatus </b>: {task_status}</p>',
                    'en' => '<p>Hello,<br>New Task has been Assign to you.</p><p><b>Project Name</b> : {project_name}<br><b>Project Label</b> :&nbsp; {project_label}<br><b>Project Status </b>: {project_status}</p><p><b>Task Name </b>: {task_name}<br><b>Task Priority </b>: {task_priority}<br><b>Task Status </b>: {task_status}</p>',
                    'es' => '<p>Hola,<br>Se le ha asignado una nueva tarea.</p><p><b>Nombre del proyecto</b> : {project_name}<br><b>Etiqueta del proyecto</b> :&nbsp; {project_label}<br><b>Estado del proyecto </b>: {project_status}</p><p><b>Nombre de la tarea </b>: {task_name}<br><b>Prioridad de tarea </b>: {task_priority}<br><b>Estado de la tarea </b>: {task_status}</p>',
                    'fr' => '<p>Bonjour,<br>Une nouvelle tâche vous a été assignée.</p><p><b>nom du projet</b> : {project_name}<br><b>Libellé du projet</b> :&nbsp; {project_label}<br><b>L\'état du projet </b>: {project_status}</p><p><b>Nom de la tâche </b>: {task_name}<br><b>Priorité des tâches </b>: {task_priority}<br><b>Statut de la tâche </b>: {task_status}</p>',
                    'it' => '<p>Ciao,<br>La nuova attività è stata assegnata a te.</p><p><b>Nome del progetto</b> : {project_name}<br><b>Etichetta del progetto</b> :&nbsp; {project_label}<br><b>Stato del progetto </b>: {project_status}</p><p><b>Nome dell\'attività </b>: {task_name}<br><b>Priorità dell\'attività </b>: {task_priority}<br><b>Stato dell\'attività </b>: {task_status}</p>',
                    'ja' => '<p>こんにちは、<br>新しいタスクが割り当てられました。</p><p><b>プロジェクト名</b> : {project_name}<br><b>プロジェクトラベル</b> :&nbsp; {project_label}<br><b>プロジェクトの状況 </b>: {project_status}</p><p><b>タスク名 </b>: {task_name}<br><b>タスクの優先度 </b>: {task_priority}<br><b>タスクのステータス </b>: {task_status}</p>',
                    'nl' => '<p>Hallo,<br>Nieuwe taak is aan u toegewezen.</p><p><b>Naam van het project</b> : {project_name}<br><b>Projectlabel</b> :&nbsp; {project_label}<br><b>Project status </b>: {project_status}</p><p><b>Opdrachtnaam </b>: {task_name}<br><b>Taakprioriteit </b>: {task_priority}<br><b>Taakstatus </b>: {task_status}</p>',
                    'pl' => '<p>Witaj,<br>Nowe zadanie zostało Ci przypisane.</p><p><b>Nazwa Projektu</b> : {project_name}<br><b>Etykieta projektu</b> :&nbsp; {project_label}<br><b>Stan projektu </b>: {project_status}</p><p><b>Nazwa zadania </b>: {task_name}<br><b>Priorytet zadania </b>: {task_priority}<br><b>Status zadania </b>: {task_status}</p>',
                    'ru' => '<p>Привет,<br>Новая задача была назначена вам.</p><p><b>название проекта</b> : {project_name}<br><b>Метка проекта</b> :&nbsp; {project_label}<br><b>Статус проекта </b>: {project_status}</p><p><b>Название задачи </b>: {task_name}<br><b>Приоритет задачи </b>: {task_priority}<br><b>Состояние задачи </b>: {task_status}</p>',
                ],
            ],
            'Task Moved' => [
                'subject' => 'Task Move in Project',
                'lang' => [
                    'ar' => '<p>مرحبا،<br>نقل المهمة {task_new_stage}.</p><p><span style="font-weight: bolder;">اسم المهمة&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">أولوية المهمة&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">حالة المهمة&nbsp;</span>: {task_status}<br></p>',
                    'da' => '<p>Hej,<br>Opgave Flyt ind {task_new_stage}.</p><p><span style="font-weight: bolder;">Opgavens navn&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Opgaveprioritet&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Opgavestatus&nbsp;</span>: {task_status}<br></p>',
                    'de' => '<p>Hallo,<br>Aufgabe Einzug {task_new_stage}.</p><p><span style="font-weight: bolder;">Aufgabennname&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Aufgabenpriorität&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Aufgabenstatus&nbsp;</span>: {task_status}<br></p>',
                    'en' => '<p>Hello,<br>Task Move in {task_new_stage}.</p><p><span style="font-weight: bolder;">Task Name&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Task Priority&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Task Status&nbsp;</span>: {task_status}<br></p>',
                    'es' => '<p>Hola,<br>Tarea Mover en {task_new_stage}.</p><p><span style="font-weight: bolder;">Nombre de la tarea&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Prioridad de tarea&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Estado de la tarea&nbsp;</span>: {task_status}<br></p>',
                    'fr' => '<p>Bonjour,<br>Déplacer la tâche {task_new_stage}.</p><p><span style="font-weight: bolder;">Nom de la tâche&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Priorité des tâches&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Statut de la tâche&nbsp;</span>: {task_status}<br></p>',
                    'it' => '<p>Ciao,<br>Attività Sposta in {task_new_stage}.</p><p><span style="font-weight: bolder;">Nome dell\'attività&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Priorità dell\'attività&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Stato dell\'attività&nbsp;</span>: {task_status}<br></p>',
                    'ja' => '<p>こんにちは、<br>タスクの入居 {task_new_stage}.</p><p><span style="font-weight: bolder;">タスク名&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">タスクの優先度&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">タスクのステータス&nbsp;</span>: {task_status}<br></p>',
                    'nl' => '<p>Hallo,<br>Taak Verplaatsen {task_new_stage}.</p><p><span style="font-weight: bolder;">Opdrachtnaam&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Taakprioriteit&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Taakstatus&nbsp;</span>: {task_status}<br></p>',
                    'pl' => '<p>Witaj,<br>Zadanie Przenieś {task_new_stage}.</p><p><span style="font-weight: bolder;">Nazwa zadania&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Priorytet zadania&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Status zadania&nbsp;</span>: {task_status}<br></p>',
                    'ru' => '<p>Привет,<br>Задача Переместить в {task_new_stage}.</p><p><span style="font-weight: bolder;">Название задачи&nbsp;</span>: {task_name}<br><span style="font-weight: bolder;">Приоритет задачи&nbsp;</span>: {task_priority}<br><span style="font-weight: bolder;">Состояние задачи&nbsp;</span>: {task_status}<br></p>',
                ],
            ],
            'Estimation Assigned' => [
                'subject' => 'New Estimation Assign',
                'lang' => [
                    'ar' => '<p>مرحبا،<br>تم تعيين تقدير جديد لك.</p><p><b>معرف التقدير</b> : {estimation_name}<br><b>مرحلة التقدير</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">تقدير&nbsp;</span><b>الحالة </b>: {estimation_status}</p>',
                    'da' => '<p>Hej,<br>Ny estimering er blevet tildelt til dig.</p><p><b>Estimations-id</b> : {estimation_name}<br><b>Estimeringsfase</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">estimering&nbsp;</span><b>status </b>: {estimation_status}</p>',
                    'de' => '<p>Hallo,<br>Neue Schätzung wurde Ihnen zugewiesen.</p><p><b>Schätz-Id</b> : {estimation_name}<br><b>Schätzungsphase</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Einschätzung&nbsp;</span><b>Status </b>: {estimation_status}</p>',
                    'en' => '<p>Hello,<br>New Estimation has been Assign to you.</p><p><b>Estimation Id</b> : {estimation_name}<br><b>Estimation Stage</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Estimation&nbsp;</span><b>Status </b>: {estimation_status}</p>',
                    'es' => '<p>Hola,<br>Se le ha asignado una nueva estimación.</p><p><b>ID de estimación</b> : {estimation_name}<br><b>Etapa de estimación</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Estimacion&nbsp;</span><b>Estado </b>: {estimation_status}</p>',
                    'fr' => '<p>Bonjour,<br>Une nouvelle estimation vous a été attribuée.</p><p><b>Identifiant d\'estimation</b> : {estimation_name}<br><b>Étape d\'estimation</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Estimation&nbsp;</span><b>Statut </b>: {estimation_status}</p>',
                    'it' => '<p>Ciao,<br>La nuova stima è stata assegnata a te.</p><p><b>ID stima</b> : {estimation_name}<br><b>Fase di stima</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Stima&nbsp;</span><b>Stato </b>: {estimation_status}</p>',
                    'ja' => '<p>こんにちは、<br>新しい見積もりが割り当てられました。</p><p><b>見積もりID</b> : {estimation_name}<br><b>見積もり段階</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">推定&nbsp;</span><b>状態 </b>: {estimation_status}</p>',
                    'nl' => '<p>Hallo,<br>Nieuwe schatting is aan u toegewezen.</p><p><b>Schattings-ID</b> : {estimation_name}<br><b>Schattingsfase</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Schatting&nbsp;</span><b>Toestand </b>: {estimation_status}</p>',
                    'pl' => '<p>Witaj,<br>Nowe oszacowanie zostało Ci przypisane.</p><p><b>Identyfikator szacunku</b> : {estimation_name}<br><b>Etap szacowania</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Oszacowanie&nbsp;</span><b>Status </b>: {estimation_status}</p>',
                    'ru' => '<p>Привет,<br>Новая оценка была назначена вам.</p><p><b>Идентификатор оценки</b> : {estimation_name}<br><b>Этап оценки</b> :&nbsp; {estimation_client}<br><span style="font-weight: bolder;">Предварительный расчет&nbsp;</span><b>Положение дел </b>: {estimation_status}</p>',
                ],
            ],
            'New Contract' => [
                'subject' => 'Contract',
                'lang' => [
                    'ar' => '<p>&nbsp;</p>
                    <p><b>مرحبا</b> { contract_client }</p>
                    <p><b>موضوع العقد</b> : { contract_subject }</p>
                    <p><b>مشروع العقد </b>: { contract_project }</p>
                    <p><b>تاريخ البدء</b> : { contract_start_date }</p>
                    <p><b>تاريخ الانتهاء</b> : { contract_end_date }</p>
                    <p>. أتطلع لسماع منك</p>
                    <p><b>Regards نوع ،</b></p>
                    <p>{ company_name }</p>',
                    'da' => '<p>&nbsp;</p>
                    <p><b>Hej </b>{ contract_client }</p>
                    <p><b>Kontraktemne :&nbsp;</b>{ contract_subject }</p>
                    <p><b>Kontrakt-projekt :&nbsp;</b>{ contract_project }</p>
                    <p><b>Startdato&nbsp;</b>: { contract_start_date }</p>
                    <p><b>Slutdato&nbsp;</b>: { contract_end_date }</p>
                    <p>Jeg glæder mig til at høre fra dig.</p>
                    <p><b>Kind Hilds,</b></p>
                    <p>{ company_name }</p><p></p>',
                    'de' => '<p>&nbsp;</p>
                    <p><b>Hi</b> {contract_client}</p>
                    <p>&nbsp;<b style="font-family: var(--bs-body-font-family); text-align: var(--bs-body-text-align);">Vertragsgegenstand :</b><span style="font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);"> {contract_subject}</span></p>
                    <p><b>Vertragsprojekt :&nbsp;</b>{contract_project}</p>
                    <p><b>Startdatum&nbsp;</b>: {contract_start_date}</p>
                    <p><b>Enddatum&nbsp;</b>: {contract_end_date}</p>
                    <p>Freuen Sie sich auf das Hören von Ihnen.</p>
                    <p><b>Gütige Grüße,</b></p>
                    <p>{company_name}</p>',
                    'en' => '<p>&nbsp;</p>
                    <p><strong>Hi</strong> {contract_client}</p>
                    <p><b>Contract Subject</b>&nbsp;: {contract_subject}</p>
                    <p><b>Contract Project</b>&nbsp;: {contract_project}</p>
                    <p><b>Start Date&nbsp;</b>: {contract_start_date}</p>
                    <p><b>End Date&nbsp;</b>: {contract_end_date}</p>
                    <p>Looking forward to hear from you.</p>
                    <p><strong>Kind Regards, </strong></p>
                    <p>{company_name}</p>',
                    'es' => '<p><b>Hi </b>{contract_client} </p><p><span style="text-align: var(--bs-body-text-align);"><b>asunto del contrato</b></span><b>&nbsp;:</b> {contract_subject}</p><p><b>contrato proyecto </b>: {<span style="font-family: var(--bs-body-font-family); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">contract_project</span><span style="font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">}</span></p><p> </p><p><b>Start Date :</b> {contract_start_date} </p><p><b>Fecha de finalización :</b> {contract_end_date} </p><p>Con ganas de escuchar de usted. </p><p><b>Regards de tipo, </b></p><p>{contract_name}</p>',
                    'fr' => '<p><b>Bonjour</b> { contract_client }</p>
                    <p><b>Objet du contrat :</b> { contract_subject } </p><p><span style="text-align: var(--bs-body-text-align);"><b>contrat projet :</b></span>&nbsp;{ contract_project } </p><p><b>Date de début&nbsp;</b>: { contract_start_date } </p><p><b>Date de fin&nbsp;</b>: { contract_end_date } </p><p>Regard sur lavenir.</p>
                    <p><b>Sincères amitiés,</b></p>
                    <p>{ nom_entreprise }</p>',
                    'it' => '<p>&nbsp;</p>
                    <p>Ciao {contract_client}</p>
                    <p><b>Oggetto contratto :&nbsp;</b>{contract_subject} </p><p><b>Contract Project :</b> {contract_project} </p><p><b>Data di inizio</b>: {contract_start_date} </p><p><b>Data di fine</b>: {contract_end_date} </p><p>Non vedo lora di sentirti<br></p>
                    <p><b>Kind Regards,</b></p>
                    <p>{company_name}</p>',
                    'ja' => '<p><span style="font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">こんにちは {contract_client}</span><br></p>
                    <p><b>契約件名&nbsp;</b>: {contract subject}</p>
                    <p><b>契約プロジェクト :</b> {contract_project}</p>
                    <p><b>開始日</b>: {contract_start_date}</p>
                    <p>&nbsp;<b style="font-family: var(--bs-body-font-family); text-align: var(--bs-body-text-align);">終了日</b><span style="font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">: {contract_end_date}</span></p><p><span style="text-align: var(--bs-body-text-align);">あなたから聞いて楽しみにして</span></p><p><span style="text-align: var(--bs-body-text-align);"><b>敬具、</b><br></span></p>
                    <p>{ company_name}</p>',
                    'nl' => '<p>&nbsp;</p>
                    <p><b>Hallo</b> { contract_client }</p>
                    <p><b>Contractonderwerp</b> : { contract_subject } </p><p><b>Contractproject</b> : { contract_project } </p><p><b>Begindatum</b> : { contract_start_date } </p><p><b>Einddatum&nbsp;</b>: { contract_end_date } </p><p>Naar voren komen om van u te horen.</p><p><b>Met vriendelijke groeten</b>,<br></p>
                    <p>{ bedrijfsnaam }</p>',
                    'pl' => '<p>&nbsp;</p>
                    <p><b>Witaj</b> {contract_client }</p>
                    <p><b>Temat umowy :&nbsp;</b>{contract_subject } </p><p><b>Projekt kontraktu</b>&nbsp;: {contract_project } </p><p><b>Data rozpoczęcia&nbsp;</b>: {contract_start_date } </p><p><b>Data zakończenia&nbsp;</b>: {contract_end_date } </p><p>Z niecierżną datą i z niecierżką na Ciebie.</p>
                    <p><b>W Odniesieniu Do Rodzaju,</b></p>
                    <p>{company_name }</p>',
                    'ru' => '<p></p>
                    <p><b>Здравствуйте</b> { contract_client }</p>
                    <p><b>Субъект договора :</b> { contract_subject } </p><p><b>Проект договора</b>: { contract_project } </p><p><b>Начальная дата </b>: { contract_start_date } </p><p><b>Конечная дата </b>: { contract_end_date } </p><p>нетерпением ожидаю услышать от вас.</p>
                    <p><b>Привет.</b></p>
                    <p>{ company_name }</p>',
                    'pt' => '<p>&nbsp;</p>
                    <p><span style="text-align: var(--bs-body-text-align);"><b>Olá</b></span>&nbsp;{contract_client}</p>
                    <p><span style="text-align: var(--bs-body-text-align);"><b>Assunto do Contrato</b></span>&nbsp;: {contract_subject}</p>
                    <p><span style="text-align: var(--bs-body-text-align);"><b>Projeto de contrato&nbsp;</b></span>: {contract_project}</p>
                    <p><span style="text-align: var(--bs-body-text-align);"><b>Data de início</b></span><b>&nbsp;</b>: {contract_start_date}</p>
                    <p><span style="text-align: var(--bs-body-text-align);"><b>Data final</b></span><b>&nbsp;</b>: {contract_end_date}</p>
                    <p>Ansioso para ouvir de você.</p>
                    <p><b>Atenciosamente,</b><br></p>
                    <p>{company_name}</p>',
                ],
            ],
        ];

        $email = EmailTemplate::all();

        foreach ($email as $e) {
            foreach ($defaultTemplate[$e->name]['lang'] as $lang => $content) {
                EmailTemplateLang::create(
                    [
                        'parent_id' => $e->id,
                        'lang' => $lang,
                        'subject' => $defaultTemplate[$e->name]['subject'],
                        'content' => $content,
                        'from' => (env('APP_NAME')) ? env('APP_NAME') : "Workgo SaaS",
                    ]
                );
            }
        }
    }

    // End Email template Module

    public function destroyUserProjectInfo($user_id)
    {
        return Userprojects::where('user_id', '=', $user_id)->delete();
    }

    public function removeUserLeadInfo($user_id)
    {
        return Leads::where('owner', '=', $user_id)->update(array('owner' => 0));
    }

    public function removeUserExpenseInfo($user_id)
    {
        return Expense::where('user_id', '=', $user_id)->update(array('user_id' => 0));
    }

    public function removeUserTaskInfo($user_id)
    {
        return Task::where('assign_to', '=', $user_id)->update(array('assign_to' => 0));
    }

    public function destroyUserNotesInfo($user_id)
    {
        return Note::where('created_by', '=', $user_id)->delete();
    }

    public function destroyUserTaskAllInfo($user_id)
    {
        CheckList::where('created_by', '=', $user_id)->delete();
        Comment::where('created_by', '=', $user_id)->delete();
        TaskFile::where('created_by', '=', $user_id)->delete();
    }

    public function removeClientProjectInfo($user_id)
    {
        return Projects::where('client', '=', $user_id)->update(array('client' => 0));
    }

    public function removeClientLeadInfo($user_id)
    {
        return Leads::where('client', '=', $user_id)->update(array('client' => 0));
    }

    public function total_company_user($company_id)
    {
        return User::where('type', '!=', 'client')->where('created_by', '=', $company_id)->count();
    }

    public function total_company_client($company_id)
    {
        return User::where('type', '=', 'client')->where('created_by', '=', $company_id)->count();
    }

    public function total_company_project($company_id)
    {
        return Projects::where('created_by', '=', $company_id)->count();
    }

    public function bugNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["bug_prefix"] . sprintf("%05d", $number);
    }

    public function header_title()
    {
        $settings = Utility::settings();

        if (!isset($settings["header_text"]) || empty($settings["header_text"])) {
            $settings["header_text"] = '';
        }

        return $settings["header_text"];
    }

    public function footer_title()
    {
        $settings = Utility::settings();

        if (!isset($settings["footer_text"]) || empty($settings["footer_text"])) {
            $settings["footer_text"] = '';
        }

        return $settings["footer_text"];
    }

    public function planPrice()
    {
        $user = \Auth::user();
        if ($user->type == 'super admin') {
            $userId = $user->id;
        } else {
            $userId = $user->created_by;
        }

        return \DB::table('settings')->where('created_by', '=', $userId)->get()->pluck('value', 'name');
    }

    public function clientEstimations()
    {
        return $this->hasMany('App\Models\Estimation', 'client_id', 'id')->count();
    }

    public function notifications()
    {
        return Notification::where('user_id', '=', \Auth::user()->id)->where('is_read', '=', 0)->orderBy('id', 'desc')->get();
    }

    public function unread()
    {
        return Message::where('from', '=', $this->id)->where('is_read', '=', 0)->count();
    }

    public function isUser()
    {
        return $this->type === 'user' ? 1 : 0;
    }

    public static function userDefualtView($request)
    {
        $userId      = \Auth::user()->id;
        $defaultView = UserDefualtView::where('module', $request->module)->where('user_id', $userId)->first();
        if (empty($defaultView)) {
            $userView = new UserDefualtView();
        } else {
            $userView = $defaultView;
        }
        $userView->module  = $request->module;
        $userView->route   = $request->route;
        $userView->view    = $request->view;
        $userView->user_id = $userId;
        $userView->save();
    }

    public function getTeamUsers()
    {
        return User::where('user_parent_id', '=', $this->id)->get();
    }

    public function getUserProjects($uid)
    {
        return Userprojects::select('projects.name', 'userprojects.user_id', 'userprojects.project_id')
                            ->join('projects', 'projects.id', '=', 'userprojects.project_id')
                            ->whereIN('userprojects.user_id', $uid)
                            ->get();
    }
}
