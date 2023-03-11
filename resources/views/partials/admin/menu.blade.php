    @php
        $emailTemplate = App\Models\EmailTemplate::first();
        $logo = \App\Models\Utility::get_file('logo/');
        $setting = \App\Models\Utility::settings();
        if (\Auth::user()->type == 'super admin') {
            $company_logo = Utility::get_superadmin_logo();
        } else {
            $company_logo = Utility::get_company_logo();
        }

    @endphp

    @if (
        (isset($layout_setting['is_sidebar_transperent']) && $layout_setting['is_sidebar_transperent'] == 'on') ||
            $layout_setting['SITE_RTL'] == 'on')
        <nav class="dash-sidebar light-sidebar transprent-bg">
        @else
            <nav class="dash-sidebar light-sidebar">
    @endif
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ============   change your logo hear   ============ -->
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                    alt="{{ config('app.name', 'WorkGo') }}" class="logo logo-lg" style="height: 40px;">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                <li class="dash-item {{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
                    <a class="dash-link" href="{{ route('dashboard') }}">
                        <span class="dash-micon"><i class="ti ti-home"></i></span><span
                            class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if (\Auth::user()->type == 'super admin')
                    @can('manage user')
                        <li class="dash-item dash-hasmenu {{ request()->is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span class="dash-mtext">{{ __('User') }}</span>
                            </a>
                        </li>
                    @endcan
                @else
                    @if (Gate::check('manage user') || Gate::check('manage client') || Gate::check('manage role'))
                        <li
                            class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'clients' || Request::segment(1) == 'roles' ? ' active' : 'collapsed' }}">
                            <a href="#" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span
                                    class="dash-mtext">{{ __('Staff') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul
                                class="dash-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'clients' || Request::segment(1) == 'roles' ? ' show' : '' }}">
                                @can('manage user')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : '' }}">
                                        <a class="dash-link" href="{{ route('users.index') }}">{{ __('User') }}</a>
                                    </li>
                                @endcan
                                <!-- @can('manage client')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'clients.index' || Request::route()->getName() == 'clients.create' || Request::route()->getName() == 'clients.edit' ? ' active' : '' }}">
                                        <a class="dash-link" href="{{ route('clients.index') }}">{{ __('Client') }}</a>
                                    </li>
                                @endcan -->
                                @can('manage role')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : '' }}">
                                        <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Role') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                @endif
                <!-- @if (Gate::check('manage lead') || \Auth::user()->type == 'client')
                    <li class="dash-item {{ Request::segment(1) == 'leads' ? 'active' : '' }}">
                        <a href="{{ route('leads.index') }}" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-box"></i></span><span
                                class="dash-mtext">{{ __('Leads') }}</span></a>
                    </li>
                @endif
                @if (Gate::check('manage estimations'))
                    <li class="dash-item {{ Request::segment(1) == 'estimations' ? 'active' : '' }}">
                        <a href="{{ route('estimations.index') }}" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-send"></i></span><span
                                class="dash-mtext">{{ __('Estimation') }}</span></a>
                    </li>
                @endif -->



                <!-- @if (Gate::check('manage project'))
                    <li class="dash-item {{ Request::segment(1) == 'projects' ? 'active open' : '' }}">
                        <a href="{{ route('projects.index') }}" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-list-check"></i></span><span
                                class="dash-mtext">{{ __('Project') }}</span></a>
                    </li>
                    @if (\Auth::user()->type != 'employee')
                        <li
                            class="dash-item {{ Request::route()->getName() == 'project_report.index' || Request::route()->getName() == 'project_report.show' ? 'active' : '' }}">
                            <a href="{{ route('project_report.index') }}" class="dash-link ">
                                <span class="dash-micon"><i class="ti ti-chart-line"></i></span>
                                <span class="dash-mtext">{{ __('Project Report') }}</span></a>
                        </li>
                    @endif
                @endif -->





                <!-- @if (Gate::check('manage product') ||
                        Gate::check('manage invoice') ||
                        Gate::check('manage expense') ||
                        Gate::check('manage payment') ||
                        Gate::check('manage tax') ||
                        \Auth::user()->type == 'client') -->
                    <!-- <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'products' || Request::segment(1) == 'expenses' || Request::segment(1) == 'invoices' || Request::segment(1) == 'invoices-payments' || Request::segment(1) == 'taxes' ? ' active' : 'collapsed' }}">
                        <a href="#" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-shopping-cart"></i></span><span
                                class="dash-mtext">{{ __('Sales') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul
                            class="dash-submenu collapse {{ Request::segment(1) == 'products' || Request::segment(1) == 'expenses' || Request::segment(1) == 'invoices' || Request::segment(1) == 'invoices-payments' || Request::segment(1) == 'taxes' ? ' show' : '' }}">
                            @if (Gate::check('manage payment') || \Auth::user()->type == 'client')
                                <li class="dash-item {{ Request::segment(1) == 'invoices' ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('invoices.index') }}">{{ __('Invoice') }}</a>
                                </li>
                                <li class="dash-item {{ Request::segment(1) == 'invoices-payments' ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('invoices.payments') }}">
                                        {{ __('Payment') }}
                                    </a>
                                </li>
                            @endif
                            @if (Gate::check('manage expense') || \Auth::user()->type == 'client')
                                <li class="dash-item {{ Request::segment(1) == 'expenses' ? 'active open' : '' }}">
                                    <a class="dash-link" href="{{ route('expenses.index') }}">
                                        {{ __('Expense') }}
                                    </a>
                                </li>
                            @endif
                            @can('manage tax')
                                <li class="dash-item {{ Request::segment(1) == 'taxes' ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('taxes.index') }}">
                                        {{ __('Tax Rates') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li> -->
                <!-- @endif


                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'client') -->
                    <!-- <li
                        class="dash-item {{ Request::route()->getName() == 'contracts.index' || Request::route()->getName() == 'contracts.show' ? 'active' : '' }}">
                        <a href="{{ route('contracts.index') }}" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-device-floppy"></i></span><span
                                class="dash-mtext">{{ __('Contracts') }}</span></a>
                    </li> -->
                <!-- @endif

                @if (Gate::check('manage timesheet')) -->

                    <!-- <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'timesheet' ? ' active' : 'collapsed' }}">
                        <a href="#" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-clock"></i></span>
                            <span class="dash-mtext">{{ __('Timesheet') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu {{ Request::segment(1) == 'timesheet' ? ' show' : '' }}">
                            <li class="dash-item {{ Request::segment(1) == 'timesheet' ? 'active open' : '' }}">
                                <a class="dash-link" href="{{ route('task.timesheetRecord') }}">
                                    <span class="dash-mtext"> {{ __('My Time') }}</span>
                                </a>
                            </li>
                            @if (\Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'PMO')
                                <li
                                    class="dash-item {{ Request::segment(1) == 'team-timesheet' ? 'active open' : '' }}">
                                    <a class="dash-link" href="{{ route('task.team.timesheetRecord') }}">
                                        <span class="dash-mtext"> {{ __('Team Timesheet') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li> -->
                <!-- @endif


                {{-- @if (\Auth::user()->type == 'company') -->
                    <!-- <li class="dash-item {{ (Request::segment(1) == 'time-tracker')?'active open':''}}">
                        <a class="dash-link" href="{{ route('time.tracker') }}">
                            <span class="dash-micon"><i class="ti ti-device-watch"></i></span><span class="dash-mtext"> {{__('Tracker')}}</span>
                        </a>
                    </li> -->
                <!-- @endif
--}}
                @if (\Auth::user()->type != 'super admin' && \Auth::user()->type != 'employee') -->
                    <!-- <li class="dash-item {{ (Request::segment(1) == 'calendar')?'active open':''}}">
                        <a class="dash-link" href="{{ route('calendar.index') }}">
                            <span class="dash-micon"><i class="ti ti-calendar"></i></span><span class="dash-mtext"> {{__('Calendar')}}</span>
                        </a>
                    </li>
                    {{-- <li class="dash-item {{ (Request::route()->getName() == 'zoommeeting.index' || Request::route()->getName() == 'zoommeeting.Calender') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('zoommeeting.index')}}">
                            <span class="dash-micon"><i class="ti ti-video"></i></span><span class="dash-mtext"> {{__('Zoom Meeting')}}</span>
                        </a>
                    </li> --}} -->
                <!-- @endif -
                {{-- @if (\Auth::user()->type != 'client' && \Auth::user()->type != 'super admin')
                    @if (Auth::user()->type != 'super admin' || Auth::user()->type != 'client' || Auth::user()->type != 'company' || env('CHAT_MODULE') == 'on') -->
                        <!-- <li class="dash-item {{ (Request::route()->getName() == 'chats') ? 'active' : '' }}">
                            <a href="{{url('chats')}}" class="dash-link"><span class="dash-micon"><i class="ti ti-messages"></i></span><span class="dash-mtext">{{__('Messenger')}}</span></a>
                        </li> -->
                    <!-- @endif
                @endif --}}
                @if (Gate::check('manage plan')) -->
                    <!-- <li
                        class="dash-item {{ Request::segment(1) == 'plans' || Request::route()->getName() == 'payment' ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('plans.index') }}">
                            <span class="dash-micon"><i class="ti ti-trophy"></i></span><span
                                class="dash-mtext">{{ __('Plan') }}</span>
                        </a>
                    </li> -->
                <!-- @endif

                @if (\Auth::user()->type == 'super admin') -->
                    <!-- <li class="dash-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                        <a href="{{ route('plan_request.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-git-pull-request"></i></span><span
                                class="dash-mtext">{{ __('Plan Request') }}</span>
                        </a>
                    </li> -->
                <!-- @endif



                {{-- @if (Gate::check('manage coupon')) -->
                    <!-- <li class="dash-item {{ (Request::segment(1) == 'coupons')?'active':''}}">
                        <a class="dash-link" href="{{ route('coupons.index') }}">
                            <span class="dash-micon"><i class="ti ti-gift"></i></span><span class="dash-mtext">{{__('Coupons')}}</span>
                        </a>
                    </li> -->
                <!-- @endif --}}
                @if (Gate::check('manage order')) -->
                    <!-- <li class="dash-item {{ Request::segment(1) == 'orders' ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('order.index') }}">
                            <span class="dash-micon"><i class="ti ti-credit-card"></i></span><span
                                class="dash-mtext">{{ __('Order') }}</span>
                        </a>
                    </li> -->
                <!-- @endif
                {{-- @if (\Auth::user()->type != 'client')
                    @if (Gate::check('manage note')) -->
                        <!-- <li class="dash-item {{ (Request::segment(1) == 'notes')?'active':''}}">
                            <a class="dash-link" href="{{ route('notes.index') }}">
                                <span class="dash-micon"><i class="ti ti-sticker"></i></span><span class="dash-mtext">{{__('Notes')}}</span>
                            </a>
                        </li> -->
                    <!-- @endif
                @endif --}}

                @if (Gate::check('manage lead stage') ||
                        Gate::check('manage project stage') ||
                        Gate::check('manage lead source') ||
                        Gate::check('manage label') ||
                        Gate::check('manage expense category') ||
                        Gate::check('manage payment'))
                    @if (\Auth::user()->type != 'Project Manager') -->
                        <!-- <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? ' active' : 'collapsed' }}"
                            role="button"
                            aria-expanded="{{ Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? 'true' : 'false' }}"
                            aria-controls="navbar-getting-constant">
                            <a href="#" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-chart-arcs"></i></span><span
                                    class="dash-mtext">{{ __('Constant') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul
                                class="dash-submenu collapse {{ Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? ' show' : '' }}">
                                @can('manage lead stage')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'leadstages.index' ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('leadstages.index') }}">
                                            {{ __('Lead Stage') }}</a>
                                    </li>
                                @endcan
                                @can('manage project stage')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'projectstages.index' ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('projectstages.index') }}">
                                            {{ __('Project Stage') }}</a>
                                    </li>
                                @endcan
                                {{-- @can('manage task')
                                <li class="dash-item {{ (Request::route()->getName() == 'project.taskgroup' ) ? 'active' : '' }}">
                                    <a class="dash-link" href="{{route('project.taskgroup', [1])}}"> {{__('Task Group')}}</a>
                                </li>
                            @endcan --}}
                                @can('manage lead source')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'leadsources.index' ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('leadsources.index') }}">{{ __('Lead Source') }}</a>
                                    </li>
                                @endcan
                                @can('manage label')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'labels.index' ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('labels.index') }}"> {{ __('Label') }}</a>
                                    </li>
                                @endcan
                                {{-- @can('manage product unit')
                                <li class="dash-item {{ (Request::route()->getName() == 'productunits.index' ) ? 'active' : '' }}">
                                    <a class="dash-link" href="{{route('productunits.index')}}">{{__('Product Unit')}}</a>
                                </li>
                            @endcan --}}
                                @can('manage expense category')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'expensescategory.index' ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('expensescategory.index') }}">{{ __('Expense Category') }}</a>
                                    </li>
                                @endcan
                                @can('manage payment')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'payments.index' ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('payments.index') }}">{{ __('Payment Method') }}</a>
                                    </li>
                                @endcan
                                <li class="dash-item {{ Request::segment(1) == 'bugstatus' ? 'active open' : '' }}"
                                    href="{{ route('bugstatus.index') }}">
                                    <a href="{{ route('bugstatus.index') }}"
                                        class="dash-link">{{ __('Bug Status') }}</a>
                                </li>

                                <li
                                    class="dash-item {{ Request::route()->getName() == 'contract_type.index' ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('contract_type.index') }}">{{ __('Contract Type') }}</a>
                                </li>
                            </ul>
                        </li> -->
                    <!-- @endif
                @endif
                @if (Gate::check('manage email templates') && \Auth::user()->type == 'super admin') -->
                    <!-- <li class="dash-item {{ request()->is('email_template*') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('email_template.index') }}">
                            <span class="dash-micon"><i class="ti ti-mail"></i></span><span
                                class="dash-mtext">{{ __('Email Templates') }}</span>
                        </a>
                    </li> -->
                <!-- @endif

                @if (Gate::check('manage system settings')) -->
                    <!-- <li class="dash-item {{ Request::route()->getName() == 'systems.index' ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('systems.index') }}">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext">{{ __('System Settings') }}</span>
                        </a>
                    </li> -->
                <!-- @endif
                @if (Gate::check('manage company settings')) -->
                    <!-- <li class="dash-item {{ Request::route()->getName() == 'settings' ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('settings') }}">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext">{{ __('Settings') }}</span>
                        </a>
                    </li> -->
                <!-- @endif -->
            </ul>
        </div>
    </div>
    </nav>
