{{ Form::model($milestone, array('route' => array('project.milestone.update', $milestone->id), 'method' => 'POST')) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('title', __('Title'),['class'=>'col-form-label']) }}
                {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group  col-md-6">
                {{ Form::label('status', __('Status'),['class'=>'col-form-label']) }}
                {!! Form::select('status', $status, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group  col-md-12">
                {{ Form::label('cost', __('Cost'),['class'=>'col-form-label']) }}
                {{ Form::number('cost', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="col-md-12">
                <div class="form-group">
                      <label for="task-summary" class="col-form-label">{{ __('Progress')}}</label>
                    <input type="range" class="slider w-100 mb-0 " name="progress" id="myRange" value="{{($milestone->progress)?$milestone->progress:'0'}}" min="0" max="100" oninput="ageOutputId.value = myRange.value">
                    <output name="ageOutputName" id="ageOutputId">{{($milestone->progress)?$milestone->progress:"0"}}</output>
                    %
                </div>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
                {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('due_date', __('End Date'),['class'=>'col-form-label']) }}
                {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
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

