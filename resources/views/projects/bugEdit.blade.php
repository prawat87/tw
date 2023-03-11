{{ Form::model($bug, array('route' => array('task.bug.update', $project_id,$bug->id ), 'method' => 'POST')) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('title', __('Title'),['class'=>'col-form-label']) }}
                {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('priority', __('Priority'),['class'=>'col-form-label']) }}
                {!! Form::select('priority', $priority, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group  col-md-6">
                {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
                {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group  col-md-6">
                {{ Form::label('due_date', __('Due Date'),['class'=>'col-form-label']) }}
                {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('status', __('Bug Status'),['class'=>'col-form-label']) }}
                {!! Form::select('status', $status, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('assign_to', __('Assigned To'),['class'=>'col-form-label']) }}
                {{ Form::select('assign_to', $users, null,array('class' => 'form-select','required'=>'required')) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group  col-md-12">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
                {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

