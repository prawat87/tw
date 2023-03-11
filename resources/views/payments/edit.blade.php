{{ Form::model($payment, array('route' => array('payments.update', $payment->id), 'method' => 'PUT','enctype' => "multipart/form-data")) }}
    <div>
        <div class="row">
            <div class="form-group col-12">
                {{ Form::label('name', __('Payment Name'),['class'=>'col-form-label']) }}
                {{ Form::text('name', null, array('class' => 'form-control ','required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Update')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

