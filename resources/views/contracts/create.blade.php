{{ Form::open(array('url' => 'contracts')) }}
<div>
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('client_name', __('Client Name'), ['class' => 'col-form-label']) }}
            {{ Form::select('client_name', $client_name, null, ['class' => 'form-control client_id','id' => 'client_id', 'data-toggle' => 'select', 'required' => 'required']) }}
            
        </div>  
        <div class="col-md-6 form-group">
            {{ Form::label('project', __('Project'), ['class' => 'col-form-label']) }}
            <div class="project-div"> 
                {{ Form::select('project_id', $project, null, ['class' => 'form-control', 'id' => 'project', 'name' => 'project','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'col-form-label']) }}
            {{ Form::text('subject', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('value', __('Value'),['class'=>'col-form-label']) }}
            {{ Form::number('value', '', array('class' => 'form-control','required'=>'required','min' => '1')) }}
        </div>
        
        <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('Due Date'),['class'=>'col-form-label']) }}
            {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'),['class'=>'col-form-label']) }}
            {!! Form::select('type', $contractType, null,array('class' => 'form-select','required'=>'required')) !!}
            @if(count($contractType) <= 0)
                <div class="text-muted text-xs">
                    {{__('Please create new contract type')}} <a href="{{route('contract_type.index')}}">{{__('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control')) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>

{{ Form::close() }}



        
