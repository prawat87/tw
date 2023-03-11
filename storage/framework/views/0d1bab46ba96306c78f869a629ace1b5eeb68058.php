
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Project')); ?>

<?php $__env->stopSection(); ?>
<?php
    $logo=\App\Models\Utility::get_file('avatar/');
?>
<?php $__env->startSection('action-button'); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create project')): ?>
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="<?php echo e(__('Import projects')); ?>" data-url="<?php echo e(route('project.file.import')); ?>" data-size="md" title="<?php echo e(__('Import')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-file-import"></i>
            </a>
            <a href="<?php echo e(route('project.export')); ?>" class="btn btn-sm btn-primary btn-icon" title="<?php echo e(__('Export')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-file-export"></i>
            </a>
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="<?php echo e(route('projects.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Project')); ?>" data-size="lg" title="<?php echo e(__('Create')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Project')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $permissions=$project->client_project_permission();
                $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

                $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

                $total_task = $project->project_total_task($project->id);
                $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                $percentage=0;
                if($total_task!=0){
                    $percentage = intval(($completed_task / $total_task) * 100);
                }

                $label='';
                if($percentage<=15){
                    $label='bg-danger';
                }else if ($percentage > 15 && $percentage <= 33) {
                    $label='bg-warning';
                } else if ($percentage > 33 && $percentage <= 70) {
                    $label='bg-primary';
                } else {
                    $label='bg-success';
                }

            ?>
            <div class="col-md-3 col-xxl-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <?php if($project->is_active==1): ?>
                                <h5 class="mb-0"><a href="<?php echo e(route('projects.show',$project->id)); ?>"><?php echo e($project->name); ?></a></h5>
                            <?php else: ?>
                                <h5 class="mb-0"><?php echo e($project->name); ?></h5>
                            <?php endif; ?>
                            <?php if($project->is_active==1): ?>
                                <div class="ms-2">
                                    <p class="text-muted text-sm mb-0"><a href="<?php echo e(route('projects.show',$project->id)); ?>" class="text-secondary" title="<?php echo e(__('Detail')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-eye"></i>
                                    </a></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <?php if($project->is_active == 1): ?>
                                    <?php if((Gate::check('edit project') || Gate::check('delete project'))): ?>
                                        <button type="button" class="btn dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project')): ?>
                                                <a href="#" class="dropdown-item" data-url="<?php echo e(route('projects.edit',$project->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Project')); ?>" data-size="lg" title="<?php echo e(__('Edit')); ?>">
                                                    <i class="ti ti-edit"></i>
                                                    <span><?php echo e(__('Edit')); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project')): ?>
                                                <a href="#" class="dropdown-item bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($project->id); ?>">
                                                    <i class="ti ti-trash"></i>
                                                    <span><?php echo e(__('Delete')); ?></span>
                                                </a>
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id], 'id' => 'delete-form-' . $project->id]); ?>

                                                <?php echo Form::close(); ?>


                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 justify-content-between">
                            <div class="col-auto">
                                <?php $__currentLoopData = $project_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($key== $project->status): ?>
                                        <?php if($status=='Completed'): ?>
                                            <?php $status_color ='bg-success' ?>
                                        <?php elseif($status=='On Going'): ?>
                                            <?php $status_color ='bg-primary' ?>
                                        <?php else: ?>
                                            <?php $status_color ='bg-warning' ?>
                                        <?php endif; ?>
                                        <span class="badge rounded-pill <?php echo e($status_color); ?>"><?php echo e(__($status)); ?></span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="col-auto">
                                <p class="text-muted text-sm mb-0"><?php echo e(__('Progress')); ?>: <?php echo e($percentage); ?>%</p>
                                <div class="mt-2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($percentage); ?>%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span class="mb-0"><b><?php echo e(\Auth::user()->dateFormat($project->start_date)); ?></b></span>
                                <p class="text-muted mb-0"><?php echo e(__('Start Date')); ?></p>
                            </div>
                            <div class="col-auto">
                                <span class="mb-0"><b class="text-end"><?php echo e(\Auth::user()->dateFormat($project->due_date)); ?></b></span>
                                <p class="text-muted mb-0 text-end"><?php echo e(__('Due Date')); ?></p>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span class="mb-0"><b><?php echo e($project->getProjectTotalEstimatedTimes()); ?></b></span>
                                <p class="text-muted mb-0"><?php echo e(__('Estimated Hours')); ?></p>
                            </div>
                            <div class="col-auto">
                                <span class="mb-0"><b class="text-end"><?php echo e($project->getProjectTotalLoggedHours()); ?></b></span>
                                <p class="text-muted mb-0 text-end"><?php echo e(__('Logged Hours')); ?></p>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span><?php echo e(__('Client')); ?></span>
                                <?php
                                $client=(!empty($project->client())?$project->client()->avatar:'')
                                ?>


                                <div class="user-group">

                                    

                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e((!empty($project->client())?$project->client()->name:'')); ?>" src="<?php echo e((!empty($project->client()->avatar))?  \App\Models\Utility::get_file('productimages/'.$project->client()->avatar): $logo."avatar.png"); ?>" class="img-fluid rounded-circle"  width="25" height="25">

                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <span><?php echo e(__('Members')); ?></span>
                                <div class="user-group">
                                    <?php $__currentLoopData = $project->project_user(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project_user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e((!empty($project_user)?$project_user->name:'')); ?>" src="<?php echo e((!empty($project_user->avatar))?  \App\Models\Utility::get_file('productimages/'.$project_user->avatar): $logo."avatar.png"); ?>" class="img-fluid rounded-circle"  width="25" height="25">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-0 mt-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6 p-0">
                                        <?php if($project->is_active==1): ?>
                                            <p class="text-muted mb-0">
                                            <a href="<?php echo e(route('project.taskboard',$project->id)); ?>" class=" text-muted"><i class="ti ti-list-check card-icon-text-space" class="text-muted"></i><?php echo e($project->countTask()); ?> <?php echo e(__('Tasks')); ?></a></p>
                                        <?php else: ?>
                                            <i class="ti ti-list-check card-icon-text-space text-muted"></i><?php echo e($project->countTask()); ?>

                                        <?php endif; ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if(\Auth::user()->type == "company"): ?>
        <div class="col-md-3">
            <a href="#" class="btn-addnew-project" data-url="<?php echo e(route('projects.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Project')); ?>" data-size="lg" title="<?php echo e(__('Create')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2"><?php echo e(__('New Project')); ?></h6>
                <p class="text-muted text-center"><?php echo e(__('Click here to add New U')); ?>ser</p>
            </a>
        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\teamwork\resources\views/projects/index.blade.php ENDPATH**/ ?>