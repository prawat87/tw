{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                {{Form::label('name',__('Name'),['class'=>'col-form-label']) }}
                {{Form::text('name',null,array('class'=>'form-control ','placeholder'=>__('Enter User Name')))}}
            </div>
            <div class="col-md-12 form-group">
                {{Form::label('email',__('Email'),['class'=>'col-form-label'])}}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
            </div>
            {{-- @if(\Auth::user()->type != 'super admin') --}}
                <div class="form-group col-md-12">
                    {{ Form::label('role', __('User Role'),['class'=>'form-label col-form-label']) }}
                    {!! Form::select('role', $roles, $user->roles,array('class' => 'form-select','required'=>'required')) !!}
                </div>
            {{-- @endif --}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('role', __('Reporting Manager'),['class'=>'form-label col-form-label']) }}
            {!! Form::select('user_parent_id', $allUsers, $user_parent_id, array('class' => 'form-select','required'=>'required')) !!}
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}