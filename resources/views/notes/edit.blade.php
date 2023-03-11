{{Form::model($note, array('route' => array('notes.update', $note->id), 'method' => 'PUT')) }}
<div>
    <div class="form-group col-md-12">
        {{Form::label('title',__('Title'),['class'=>'form-label'])}}
        {{Form::text('title',null,array('class'=>'form-control ','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('text', __('Description'),['class'=>'form-label']) }}
        {!! Form::textarea('text', null, ['class'=>'form-control','rows'=>'4','required'=>'required']) !!}
    </div>
    <div class="form-group col-md-12">
        <div class="form-group col-md-12">
            <div class="row align-items-center">
                {{ Form::label('name', __('Color'),['class'=>'col-auto col-form-label']) }}
                <div class="col-auto">

                    @foreach($colors as $k=>$color)
                    <div class="form-check custom-radio-inline radio-border">
                        <input class="form-check-input input-{{$color}}" type="radio" name="color" id="customCheck_{{$k}}" value="{{$color}}" @if($note->color == $color) checked @endif >
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
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
