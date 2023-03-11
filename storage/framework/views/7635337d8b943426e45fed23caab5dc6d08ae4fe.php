<?php echo e(Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT'))); ?>

    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                <?php echo e(Form::label('name',__('Name'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::text('name',null,array('class'=>'form-control ','placeholder'=>__('Enter User Name')))); ?>

            </div>
            <div class="col-md-12 form-group">
                <?php echo e(Form::label('email',__('Email'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))); ?>

            </div>
            
                <div class="form-group col-md-12">
                    <?php echo e(Form::label('role', __('User Role'),['class'=>'form-label col-form-label'])); ?>

                    <?php echo Form::select('role', $roles, $user->roles,array('class' => 'form-select','required'=>'required')); ?>

                </div>
            
        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('role', __('Reporting Manager'),['class'=>'form-label col-form-label'])); ?>

            <?php echo Form::select('user_parent_id', $allUsers, $user_parent_id, array('class' => 'form-select','required'=>'required')); ?>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary ms-2">
    </div>
<?php echo e(Form::close()); ?><?php /**PATH D:\wamp64\www\teamwork\resources\views/user/edit.blade.php ENDPATH**/ ?>