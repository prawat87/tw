{{ Form::open(array('url' => 'productunits')) }}
<div>
    <div class="row">
        <div class="form-group col-12">
        {{ Form::label('name', __('Product Unit Name'),['class'=>'form-label']) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
