{{Form::open(array('url'=>'users','method'=>'post'))}}
    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                {{Form::label('name',__('Name'),['class'=>'col-form-label']) }}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
            </div>
            <div class="col-md-12 form-group">
                {{Form::label('email',__('Email'),['class'=>'col-form-label'])}}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
            </div>
            <div class="col-md-12 form-group">
                {{Form::label('password',__('Password'),['class'=>'col-form-label'])}}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            </div>
            {{-- @if(\Auth::user()->type != 'super admin') --}}
                <div class="form-group col-md-12">
                    {{ Form::label('role', __('User Role'),['class'=>'form-label col-form-label']) }}
                    {!! Form::select('role', $roles, null,array('class' => 'form-select','required'=>'required')) !!}
                </div>
            {{-- @endif --}}
            {{-- @if(\Auth::user()->type != 'super admin') --}}
                <div class="form-group col-md-12">
                    {{ Form::label('role', __('Reporting Manager'),['class'=>'form-label col-form-label']) }}
                    {!! Form::select('user_parent_id', $allUsers, null,array('class' => 'form-select','required'=>'required')) !!}
                </div>
            {{-- @endif --}}
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
