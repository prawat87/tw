{{ Form::open(array('url' => 'bugstatus')) }}
    <div>
        <div class="row">
            <div class="form-group col-12">
                {{ Form::label('title', __('Bug Status Title'),['class'=>'col-form-label']) }}
                {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

