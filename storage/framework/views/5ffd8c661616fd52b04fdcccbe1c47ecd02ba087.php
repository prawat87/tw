<?php echo e(Form::open(array('url'=>'users','method'=>'post'))); ?>

    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                <?php echo e(Form::label('name',__('Name'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))); ?>

            </div>
            <div class="col-md-12 form-group">
                <?php echo e(Form::label('email',__('Email'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))); ?>

            </div>
            <div class="col-md-12 form-group">
                <?php echo e(Form::label('password',__('Password'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))); ?>

            </div>
            
                <div class="form-group col-md-12">
                    <?php echo e(Form::label('role', __('User Role'),['class'=>'form-label col-form-label'])); ?>

                    <?php echo Form::select('role', $roles, null,array('class' => 'form-select','required'=>'required')); ?>

                </div>
            
            
                <div class="form-group col-md-12">
                    <?php echo e(Form::label('role', __('Reporting Manager'),['class'=>'form-label col-form-label'])); ?>

                    <?php echo Form::select('user_parent_id', $allUsers, null,array('class' => 'form-select','required'=>'required')); ?>

                </div>
            
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary ms-2">
    </div>
<?php echo e(Form::close()); ?>

<?php /**PATH D:\wamp64\www\teamwork\resources\views/user/create.blade.php ENDPATH**/ ?>