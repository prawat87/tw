{{ Form::open(array('url' => 'invoices')) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('project_id', __('Project'),['class'=>'col-form-label']) }}
                {{ Form::select('project_id', $projects,null, array('class' => 'form-select')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('issue_date', __('Issue Date'),['class'=>'col-form-label']) }}
                {{ Form::date('issue_date', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('due_date', __('Due Date'),['class'=>'col-form-label']) }}
                {{ Form::date('due_date', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('tax_id', __('Tax %'),['class'=>'col-form-label']) }}
                {{ Form::select('tax_id', $taxes,null, array('class' => 'form-select')) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('terms', __('Terms'),['class'=>'col-form-label']) }}
                {!! Form::textarea('terms', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}
