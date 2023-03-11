{{-- @dd($contract) --}}
{{ Form::model($contract, array('route' => array('contracts.update', $contract->id), 'method' => 'PUT')) }}
<div>
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('client_name', __('Client Name'), ['class' => 'col-form-label']) }}
            {{ Form::select('client_name', $client_name, null, ['class' => 'form-control client_id','id' => 'client_id', 'data-toggle' => 'select', 'required' => 'required']) }}
        </div>  
        <div class="col-md-6 form-group">
            {{ Form::label('project', __('Project'), ['class' => 'col-form-label']) }}
            <div class="project-div"> 
            {{ Form::select('project_id', $project, null, ['class' => 'form-control', 'id' => 'project', 'name' => 'project']) }}
            </div>
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'col-form-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-md-6 form-group">
            {{ Form::label('value', __('Value'),['class'=>'col-form-label']) }}
            {{ Form::number('value', null, array('class' => 'form-control','required'=>'required','min' => '1')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'),['class'=>'col-form-label']) }}
            {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('type', __('Type'),['class'=>'col-form-label']) }}
            {{ Form::select('type', $contractType, null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        
        <div class="col-md-12 form-group">
            {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>
</div>
{{ Form::close() }}