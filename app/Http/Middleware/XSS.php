<?php

namespace App\Http\Middleware;

use App\Models\Utility;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::check())
        {
            \App::setLocale(\Auth::user()->lang);

            if(\Auth::user()->type == 'super admin')
            {
                if(Schema::hasTable('messages'))
                {
                    if(Schema::hasColumn('messages', 'type') == false)
                    {
                        Schema::drop('messages');
                        \DB::table('migrations')->where('migration', 'like', '%messages%')->delete();
                    }
                }

                $migrations             = $this->getMigrations();
                $messengerMigration     = Utility::get_messenger_packages_migration();
                $dbMigrations           = $this->getExecutedMigrations();
                $numberOfUpdatesPending = (count($migrations) + $messengerMigration) - count($dbMigrations);

                if($numberOfUpdatesPending > 0)
                {
                    return redirect()->route('LaravelUpdater::welcome');
                }

                
            }
        }

        if(\Request::route()->getName() == 'chats')
        {
            if(!Auth::check())
            {
                return redirect()->back();
            }

            if(empty(env('CHAT_MODULE')) || Auth::user()->type == 'super admin' || Auth::user()->type == 'client')
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }

        $input = $request->all();
        array_walk_recursive(
            $input, function (&$input){
            $input = strip_tags($input);
        }
        );
        $request->merge($input);

        return $next($request);
    }
}
