{{ Form::open(array('url' => 'taxes')) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('name', __('Tax Rate Name'),['class'=>'col-form-label']) }}
                {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('rate', __('Tax Rate %'),['class'=>'col-form-label']) }}
                {{ Form::number('rate', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

