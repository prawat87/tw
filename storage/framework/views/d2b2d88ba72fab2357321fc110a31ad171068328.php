<?php echo e(Form::open(array('url'=>'roles','method'=>'post'))); ?>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('name',__('Name'),['class'=>'col-form-label'])); ?>

                    <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Role Name')))); ?>

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-name text-danger text-xs" role="alert"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <?php if(!empty($permissions)): ?>
                        <div class="table-border-style">
                        <label for="permissions" class="col-form-label"><?php echo e(__('Assign Permission to Roles')); ?></label>
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input align-middle" name="checkall"  id="checkall" >
                                        </div>
                                    </th>
                                    <th width="10%" class="text-dark"><?php echo e(__('Module')); ?></th>
                                    <th class="text-dark ps-0"><?php echo e(__('Permissions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $modules=['account','user','role'];
                                    //$modules=['account','user','client','role','company settings','project','product','lead','lead stage','project stage','lead source','label','product unit','expense category','expense','tax','invoice','payment','invoice product','invoice payment','task','checklist','plan','note','bug report','timesheet']; 
                                    if(Auth::user()->type == 'super admin'){
                                        $modules[] = 'language';
                                        $modules[] = 'permission';
                                         $modules[] = 'system settings';
                                    }
                                ?>
                                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                       <td width="10%">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input ischeck" name="checkall" data-id="<?php echo e(str_replace(' ', '', $module)); ?>">
                                            </div>
                                       </td>
                                        <td width="10%">
                                            <label class="ischeck" data-id="<?php echo e(str_replace(' ', '', $module)); ?>"><?php echo e(ucfirst($module)); ?></label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <?php if(in_array('manage '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('manage '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Manage',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('create '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('create '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Create',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('edit '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('edit '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Edit',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('delete '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('delete '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Delete',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('show '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('show '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Show',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('move '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('move '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Move',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('client permission',(array) $permissions)): ?>
                                                    <?php if($key = array_search('client permission '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Client Permission',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('invite user',(array) $permissions)): ?>
                                                    <?php if($key = array_search('invite user '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Invite User ',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('change password '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('change password '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Change Password',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array('buy '.$module,(array) $permissions)): ?>
                                                    <?php if($key = array_search('buy '.$module,$permissions)): ?>
                                                        <div class="col-md-3 form-check">
                                                            <?php echo e(Form::checkbox('permissions[] isscheck isscheck_'.str_replace(' ', '', $module),$key,false, ['class'=>'form-check-input','id' =>'permission'.$key])); ?>

                                                            <?php echo e(Form::label('permission'.$key,'Buy',['class'=>'form-check-label'])); ?><br>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary ms-2">
    </div>
<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function () {
           $("#checkall").click(function(){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });      
           $(".ischeck").click(function(){
                var ischeck = $(this).data('id');         
                $('.isscheck_'+ ischeck).prop('checked', this.checked);
            });           
        });
</script>
<?php /**PATH D:\wamp64\www\switches\resources\views/role/create.blade.php ENDPATH**/ ?>