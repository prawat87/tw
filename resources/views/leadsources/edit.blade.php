{{ Form::model($leadsources, array('route' => array('leadsources.update', $leadsources->id), 'method' => 'PUT')) }}
<div>
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Lead Source Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control ','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
