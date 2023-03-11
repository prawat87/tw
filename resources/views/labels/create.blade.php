{{ Form::open(array('url' => 'labels')) }}
<div>
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Label Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-12">
            <div class="row align-items-center">
                {{ Form::label('name', __('Color'),['class'=>'col-auto col-form-label']) }}
                <div class="col-auto">

                    @foreach($colors as $k=>$color)
                    <div class="form-check custom-radio-inline radio-border">
                        <input class="form-check-input input-{{$color}}" type="radio" name="color" id="customCheck_{{$k}}" value="{{$color}}">
                        <label class="form-check-label" for="customCheck_{{$k}}"></label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
