    <?php
        $emailTemplate = App\Models\EmailTemplate::first();
        $logo = \App\Models\Utility::get_file('logo/');
        $setting = \App\Models\Utility::settings();
        if (\Auth::user()->type == 'super admin') {
            $company_logo = Utility::get_superadmin_logo();
        } else {
            $company_logo = Utility::get_company_logo();
        }

    ?>

    <?php if(
        (isset($layout_setting['is_sidebar_transperent']) && $layout_setting['is_sidebar_transperent'] == 'on') ||
            $layout_setting['SITE_RTL'] == 'on'): ?>
        <nav class="dash-sidebar light-sidebar transprent-bg">
        <?php else: ?>
            <nav class="dash-sidebar light-sidebar">
    <?php endif; ?>
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="<?php echo e(route('dashboard')); ?>" class="b-brand">
                <!-- ============   change your logo hear   ============ -->
                <img src="<?php echo e($logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png')); ?>"
                    alt="<?php echo e(config('app.name', 'WorkGo')); ?>" class="logo logo-lg" style="height: 40px;">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                <li class="dash-item <?php echo e(Request::route()->getName() == 'dashboard' ? 'active' : ''); ?>">
                    <a class="dash-link" href="<?php echo e(route('dashboard')); ?>">
                        <span class="dash-micon"><i class="ti ti-home"></i></span><span
                            class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
                <?php if(\Auth::user()->type == 'super admin'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                        <li class="dash-item dash-hasmenu <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('users.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span class="dash-mtext"><?php echo e(__('User')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if(Gate::check('manage user') || Gate::check('manage client') || Gate::check('manage role')): ?>
                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'users' || Request::segment(1) == 'clients' || Request::segment(1) == 'roles' ? ' active' : 'collapsed'); ?>">
                            <a href="#" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span
                                    class="dash-mtext"><?php echo e(__('Staff')); ?></span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul
                                class="dash-submenu <?php echo e(Request::segment(1) == 'users' || Request::segment(1) == 'clients' || Request::segment(1) == 'roles' ? ' show' : ''); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('User')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage client')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'clients.index' || Request::route()->getName() == 'clients.create' || Request::route()->getName() == 'clients.edit' ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('clients.index')); ?>"><?php echo e(__('Client')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage role')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit' ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Role')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(Gate::check('manage lead') || \Auth::user()->type == 'client'): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'leads' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('leads.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-box"></i></span><span
                                class="dash-mtext"><?php echo e(__('Leads')); ?></span></a>
                    </li>
                <?php endif; ?>
                <?php if(Gate::check('manage estimations')): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'estimations' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('estimations.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-send"></i></span><span
                                class="dash-mtext"><?php echo e(__('Estimation')); ?></span></a>
                    </li>
                <?php endif; ?>



                <?php if(Gate::check('manage project')): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'projects' ? 'active open' : ''); ?>">
                        <a href="<?php echo e(route('projects.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-list-check"></i></span><span
                                class="dash-mtext"><?php echo e(__('Project')); ?></span></a>
                    </li>
                    <?php if(\Auth::user()->type != 'employee'): ?>
                        <li
                            class="dash-item <?php echo e(Request::route()->getName() == 'project_report.index' || Request::route()->getName() == 'project_report.show' ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('project_report.index')); ?>" class="dash-link ">
                                <span class="dash-micon"><i class="ti ti-chart-line"></i></span>
                                <span class="dash-mtext"><?php echo e(__('Project Report')); ?></span></a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>





                <?php if(Gate::check('manage product') ||
                        Gate::check('manage invoice') ||
                        Gate::check('manage expense') ||
                        Gate::check('manage payment') ||
                        Gate::check('manage tax') ||
                        \Auth::user()->type == 'client'): ?>
                    <li
                        class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'products' || Request::segment(1) == 'expenses' || Request::segment(1) == 'invoices' || Request::segment(1) == 'invoices-payments' || Request::segment(1) == 'taxes' ? ' active' : 'collapsed'); ?>">
                        <a href="#" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-shopping-cart"></i></span><span
                                class="dash-mtext"><?php echo e(__('Sales')); ?></span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul
                            class="dash-submenu collapse <?php echo e(Request::segment(1) == 'products' || Request::segment(1) == 'expenses' || Request::segment(1) == 'invoices' || Request::segment(1) == 'invoices-payments' || Request::segment(1) == 'taxes' ? ' show' : ''); ?>">
                            <?php if(Gate::check('manage payment') || \Auth::user()->type == 'client'): ?>
                                <li class="dash-item <?php echo e(Request::segment(1) == 'invoices' ? 'active' : ''); ?>">
                                    <a class="dash-link" href="<?php echo e(route('invoices.index')); ?>"><?php echo e(__('Invoice')); ?></a>
                                </li>
                                <li class="dash-item <?php echo e(Request::segment(1) == 'invoices-payments' ? 'active' : ''); ?>">
                                    <a class="dash-link" href="<?php echo e(route('invoices.payments')); ?>">
                                        <?php echo e(__('Payment')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(Gate::check('manage expense') || \Auth::user()->type == 'client'): ?>
                                <li class="dash-item <?php echo e(Request::segment(1) == 'expenses' ? 'active open' : ''); ?>">
                                    <a class="dash-link" href="<?php echo e(route('expenses.index')); ?>">
                                        <?php echo e(__('Expense')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage tax')): ?>
                                <li class="dash-item <?php echo e(Request::segment(1) == 'taxes' ? 'active' : ''); ?>">
                                    <a class="dash-link" href="<?php echo e(route('taxes.index')); ?>">
                                        <?php echo e(__('Tax Rates')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>


                <?php if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client'): ?>
                    <li
                        class="dash-item <?php echo e(Request::route()->getName() == 'contracts.index' || Request::route()->getName() == 'contracts.show' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('contracts.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-device-floppy"></i></span><span
                                class="dash-mtext"><?php echo e(__('Contracts')); ?></span></a>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage timesheet')): ?>

                    <li
                        class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'timesheet' ? ' active' : 'collapsed'); ?>">
                        <a href="#" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-clock"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Timesheet')); ?></span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu <?php echo e(Request::segment(1) == 'timesheet' ? ' show' : ''); ?>">
                            <li class="dash-item <?php echo e(Request::segment(1) == 'timesheet' ? 'active open' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('task.timesheetRecord')); ?>">
                                    <span class="dash-mtext"> <?php echo e(__('My Time')); ?></span>
                                </a>
                            </li>
                            <?php if(\Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'PMO'): ?>
                                <li
                                    class="dash-item <?php echo e(Request::segment(1) == 'team-timesheet' ? 'active open' : ''); ?>">
                                    <a class="dash-link" href="<?php echo e(route('task.team.timesheetRecord')); ?>">
                                        <span class="dash-mtext"> <?php echo e(__('Team Timesheet')); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>


                
                <?php if(\Auth::user()->type != 'super admin' && \Auth::user()->type != 'employee'): ?>
                    <li class="dash-item <?php echo e((Request::segment(1) == 'calendar')?'active open':''); ?>">
                        <a class="dash-link" href="<?php echo e(route('calendar.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-calendar"></i></span><span class="dash-mtext"> <?php echo e(__('Calendar')); ?></span>
                        </a>
                    </li>
                    
                <?php endif; ?> -
                
                <?php if(Gate::check('manage plan')): ?>
                    <li
                        class="dash-item <?php echo e(Request::segment(1) == 'plans' || Request::route()->getName() == 'payment' ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('plans.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-trophy"></i></span><span
                                class="dash-mtext"><?php echo e(__('Plan')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(\Auth::user()->type == 'super admin'): ?>
                    <li class="dash-item <?php echo e(request()->is('plan_request*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('plan_request.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-git-pull-request"></i></span><span
                                class="dash-mtext"><?php echo e(__('Plan Request')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>



                
                <?php if(Gate::check('manage order')): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'orders' ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('order.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-credit-card"></i></span><span
                                class="dash-mtext"><?php echo e(__('Order')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                

                <?php if(Gate::check('manage lead stage') ||
                        Gate::check('manage project stage') ||
                        Gate::check('manage lead source') ||
                        Gate::check('manage label') ||
                        Gate::check('manage expense category') ||
                        Gate::check('manage payment')): ?>
                    <?php if(\Auth::user()->type != 'Project Manager'): ?>
                        <li class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? ' active' : 'collapsed'); ?>"
                            role="button"
                            aria-expanded="<?php echo e(Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? 'true' : 'false'); ?>"
                            aria-controls="navbar-getting-constant">
                            <a href="#" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-chart-arcs"></i></span><span
                                    class="dash-mtext"><?php echo e(__('Constant')); ?></span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul
                                class="dash-submenu collapse <?php echo e(Request::segment(1) == 'leadstages' || Request::segment(1) == 'projectstages' || Request::segment(1) == 'leadsources' || Request::segment(1) == 'labels' || Request::segment(1) == 'productunits' || Request::segment(1) == 'expensescategory' || Request::segment(1) == 'payments' || Request::segment(1) == 'bugstatus' ? ' show' : ''); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage lead stage')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'leadstages.index' ? 'active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('leadstages.index')); ?>">
                                            <?php echo e(__('Lead Stage')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage project stage')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'projectstages.index' ? 'active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('projectstages.index')); ?>">
                                            <?php echo e(__('Project Stage')); ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage lead source')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'leadsources.index' ? 'active' : ''); ?>">
                                        <a class="dash-link"
                                            href="<?php echo e(route('leadsources.index')); ?>"><?php echo e(__('Lead Source')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage label')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'labels.index' ? 'active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('labels.index')); ?>"> <?php echo e(__('Label')); ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage expense category')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'expensescategory.index' ? 'active' : ''); ?>">
                                        <a class="dash-link"
                                            href="<?php echo e(route('expensescategory.index')); ?>"><?php echo e(__('Expense Category')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage payment')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'payments.index' ? 'active' : ''); ?>">
                                        <a class="dash-link"
                                            href="<?php echo e(route('payments.index')); ?>"><?php echo e(__('Payment Method')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <li class="dash-item <?php echo e(Request::segment(1) == 'bugstatus' ? 'active open' : ''); ?>"
                                    href="<?php echo e(route('bugstatus.index')); ?>">
                                    <a href="<?php echo e(route('bugstatus.index')); ?>"
                                        class="dash-link"><?php echo e(__('Bug Status')); ?></a>
                                </li>

                                <li
                                    class="dash-item <?php echo e(Request::route()->getName() == 'contract_type.index' ? 'active' : ''); ?>">
                                    <a class="dash-link"
                                        href="<?php echo e(route('contract_type.index')); ?>"><?php echo e(__('Contract Type')); ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(Gate::check('manage email templates') && \Auth::user()->type == 'super admin'): ?>
                    <li class="dash-item <?php echo e(request()->is('email_template*') ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('email_template.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-mail"></i></span><span
                                class="dash-mtext"><?php echo e(__('Email Templates')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Gate::check('manage system settings')): ?>
                    <li class="dash-item <?php echo e(Request::route()->getName() == 'systems.index' ? ' active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('systems.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext"><?php echo e(__('System Settings')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(Gate::check('manage company settings')): ?>
                    <li class="dash-item <?php echo e(Request::route()->getName() == 'settings' ? ' active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('settings')); ?>">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext"><?php echo e(__('Settings')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    </nav>
<?php /**PATH D:\wamp64\www\teamwork\resources\views/partials/admin/menu.blade.php ENDPATH**/ ?>