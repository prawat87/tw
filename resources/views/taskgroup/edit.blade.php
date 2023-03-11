{{ Form::model($taskgroup, array('route' => array('taskgroup.update', $taskgroup->id), 'method' => 'Post')) }}
<div>
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Group Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', $taskgroup->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
