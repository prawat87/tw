
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/dragula.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php
    $logo = \App\Models\Utility::get_file('avatars/');
?>
<?php $__env->startPush('script-page'); ?>
    
    <script src="<?php echo e(asset('assets/js/plugins/dragula.min.js')); ?>"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');
                        var stage_id = $(target).attr('data-id');

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                        show_toastr('<?php echo e(__("Success")); ?>', 'card move Successfully!', 'success')
                        $.ajax({
                            url: '<?php echo e(route('leads.order')); ?>',
                            type: 'POST',
                            data: {lead_id: id, stage_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('<?php echo e(__("Error")); ?>', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Lead')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Leads')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create lead')): ?>
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="<?php echo e(route('leads.create')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Create New Lead')); ?>" title="<?php echo e(__('Create')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        

        <div class="col-sm-12">
            <?php
                $json = [];
                foreach ($stages as $stage){
                    $json[] = 'lead-list-'.$stage->id;
                }
            ?>
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='<?php echo json_encode($json); ?>' data-plugin="dragula">
                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Auth::user()->type == 'company'): ?>
                        <?php ($leads = $stage->leads); ?>
                    <?php else: ?>
                        <?php ($leads = $stage->user_leads()); ?>
                    <?php endif; ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn btn-sm btn-primary btn-icon task-header">
                                        <span class="count text-white"><?php echo e(count($leads)); ?></span>
                                    </button>
                                </div>
                                <h4 class="mb-0"><?php echo e($stage->name); ?></h4>
                            </div>
                            <div id="lead-list-<?php echo e($stage->id); ?>" data-id="<?php echo e($stage->id); ?>" class="card-body kanban-box">
                                <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                    <div class="card" data-id="<?php echo e($lead->id); ?>">
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5><?php echo e($lead->name); ?></h5>
                                            <?php if(Gate::check('edit lead') || Gate::check('delete lead')): ?>
                                            <div class="card-header-right">
                                                <?php if(!$lead->is_active): ?>
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit lead')): ?>
                                                        <a class="dropdown-item" data-url="<?php echo e(URL::to('leads/'.$lead->id.'/edit')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit Lead')); ?>" href="#">
                                                            <i class="ti ti-edit"></i>
                                                            <span><?php echo e(__('Edit')); ?></span>
                                                        </a>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete lead')): ?>
                                                        <a class="dropdown-item bs-pass-para" href="#" data-title="<?php echo e(__('Delete Lead')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($lead->id); ?>">
                                                            <i class="ti ti-trash"></i>
                                                            <span><?php echo e(__('Delete')); ?></span>
                                                        </a>
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]); ?>

                                                        <?php echo Form::close(); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted text-sm"><?php echo e($lead->notes); ?></p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">
                                                    
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-calendar-stats"></i><span class="ms-2"><?php echo e(\Auth::user()->dateFormat($lead->created_at)); ?></span></li>
                                                    
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-receipt-2"></i><span class="ms-2"><?php echo e(\Auth::user()->priceFormat($lead->price)); ?></span></li>
                                                </ul>
                                                <div class="user-group">
                                                    <?php if(\Auth::user()->type=='company'): ?>
                                                        
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e((!empty($lead->user())?$lead->user()->name:'')); ?>" src="<?php echo e((!empty($lead->user()->avatar))?  \App\Models\Utility::get_file('productimages/'.$lead->user()->avatar): $logo."/avatar.png"); ?>" class="img-fluid rounded-circle"  width="25" height="25">
                                                        
                                                    <?php else: ?>
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e((!empty($lead->client())?$lead->client()->name:'')); ?>" src="<?php echo e((!empty($lead->user()->avatar))?  \App\Models\Utility::get_file('productimages/'.$lead->user()->avatar): $logo."/avatar.png"); ?>" class="img-fluid rounded-circle"  width="25" height="25">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\teamwork\resources\views/leads/index.blade.php ENDPATH**/ ?>