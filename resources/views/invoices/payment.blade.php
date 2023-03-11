{{ Form::model($invoice, array('route' => array('invoices.payments.store', $invoice->id), 'method' => 'POST')) }}
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount'),['class'=>'col-form-label']) }}
                    {{ Form::number('amount', $invoice->getDue(), array('class' => 'form-control','required'=>'required','min'=>'0',"step"=>"0.01")) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Payment Date'),['class'=>'col-form-label']) }}
                    {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('payment_id', __('Payment Method'),['class'=>'col-form-label']) }}
                    {{ Form::select('payment_id', $payment_methods,null, array('class' => 'form-select','required'=>'required')) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('notes', __('Notes'),['class'=>'col-form-label']) }}
                    {{ Form::textarea('notes', null, array('class' => 'form-control','rows'=>'2')) }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

