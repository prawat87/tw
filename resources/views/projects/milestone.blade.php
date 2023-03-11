{{ Form::open(array('route' => array('project.milestone.store',$project->id))) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('title', __('Title'),['class'=>'col-form-label']) }}
                {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('status', __('Status'),['class'=>'col-form-label']) }}
                {!! Form::select('status', $status, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('cost', __('Cost'),['class'=>'col-form-label']) }}
                {{ Form::number('cost', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
                {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Add')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

