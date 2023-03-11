{{ Form::model($leadstages, array('route' => array('projectstages.update', $leadstages->id), 'method' => 'PUT')) }}
<div>
    <div class="form-group col-12">
        {{ Form::label('name', __('Project Stage Name'),['class'=>'col-form-label']) }}
        {{ Form::text('name', null, array('class' => 'form-control ','required'=>'required')) }}
    </div>
    <div class="form-group col-12">
        {{ Form::label('color', __('Color'),['class'=>'col-form-label']) }}
        <input class="jscolor form-control " value="{{ $leadstages->color }}" name="color" id="color" required>
        <small class="small">{{ __('For chart representation') }}</small>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}

