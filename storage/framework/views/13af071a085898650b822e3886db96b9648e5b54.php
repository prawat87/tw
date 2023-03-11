
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Client')); ?>

<?php $__env->stopSection(); ?>
<?php
    $logo = \App\Models\Utility::get_file('avatars/');
?>
<?php $__env->startSection('action-button'); ?>
    <a href="#"  class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="<?php echo e(__('Create User')); ?>" data-url="<?php echo e(route('client.file.import')); ?>" data-size="md" title="<?php echo e(__('Import')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-import"></i></a>
    <a href="<?php echo e(route('client.export')); ?>" class="btn btn-sm btn-primary btn-icon" title="<?php echo e(__('Export')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-file-export"></i></a>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create client')): ?>
        <a href="#" data-url="<?php echo e(route('clients.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Client')); ?>" data-size="md" class="btn btn-sm btn-primary btn-icon" title="<?php echo e(__('Create')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Client')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-white text-center">
                    <?php if(Gate::check('edit client') || Gate::check('delete client')): ?>
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <div class="badge p-2 px-3 rounded bg-primary"><?php echo e(ucfirst($client->type)); ?></div>
                                </h6>
                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <?php if($client->is_active == 1): ?>
                                        <button type="button" class="btn dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit client')): ?>
                                                <a href="#" class="dropdown-item" data-url="<?php echo e(route('clients.edit',$client->id)); ?>"  data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Edit Client')); ?>"><i class="ti ti-edit"></i> <span><?php echo e(__('Edit')); ?></span></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete client')): ?>
                                                <a class="dropdown-item bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($client['id']); ?>"><i class="ti ti-trash"></i>
                                                <span><?php if($client->delete_status == 1): ?><?php echo e(__('Delete')); ?> <?php else: ?> <?php echo e(__('Restore')); ?></span><?php endif; ?>
                                                </a>
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]); ?>

                                                <?php echo Form::close(); ?>

                                            <?php endif; ?>
                                            <a href="#" class="dropdown-item" data-size="md" data-url="<?php echo e(route('user.reset',\Crypt::encrypt($client->id))); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Reset Password')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Reset Password')); ?>">
                                            <i class="ti ti-key"></i> <span><?php echo e(__('Reset Password')); ?></span>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="btn text-muted">
                                            <i class="ti ti-lock"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        
                        

                        <a href="<?php echo e((!empty($client->avatar))?  \App\Models\Utility::get_file($client->avatar): $logo."/avatar.png"); ?>" target="_blank">
                            <img src="<?php echo e((!empty($client->avatar))?  \App\Models\Utility::get_file($client->avatar): $logo."/avatar.png"); ?>" class="img-fluid rounded-circle card-avatar">
                        </a>

                        <h4 class="text-primary mt-2"><?php echo e($client->name); ?></h4>
                        <small class="text-primary"><?php echo e($client->email); ?></small>
                        <?php if($client->delete_status == 0): ?>
                            <h5 class="text-danger mb-0"><?php echo e(__('Deleted')); ?></h5>
                        <?php endif; ?>
                        
                        <div class="row mt-3">
                            <div class="col-12 col-sm-12">
                                <div class="card mb-0">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <p class="text-muted text-sm mb-0"><i class="fas fa-briefcase mr-2 card-icon-text-space"></i><?php echo e($client->client_project()); ?></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted text-sm mb-0"><i class="fas fa-file-invoice-dollar mr-2 card-icon-text-space"></i><?php echo e(\Auth::user()->priceFormat($client ->client_project_budget())); ?></p>
                                            </div>
                                            <div class="col-3">
                                               <p class="text-muted text-sm mb-0"><i class="fas fa-tasks mr-2 card-icon-text-space"></i><?php echo e($client->client_lead()); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3">
            <a href="#" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Create User')); ?>" data-ajax-popup="true" data-size="md" data-title="Create User" data-url="<?php echo e(route('clients.create')); ?>">
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2"><?php echo e(__('New Client')); ?></h6>
                <p class="text-muted text-center"><?php echo e(__('Click here to add New User')); ?></p>
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\teamwork\resources\views/client/index.blade.php ENDPATH**/ ?>