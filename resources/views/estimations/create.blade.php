{{ Form::open(array('url' => 'estimations')) }}
    <div>
        <div class="row">
            <div class="form-group col-6">
                {{ Form::label('client_id', __('Client'),['class'=>'form-label col-form-label']) }}
                {!! Form::select('client_id', $client, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group col-6">
                {{ Form::label('issue_date', __('Issue Date'),['class'=>'col-form-label']) }}
                {{ Form::date('issue_date',null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-12">
               
                {{ Form::label('tax_id', __('Tax %'),['class'=>'form-label col-form-label']) }}
                {!! Form::select('tax_id', $taxes, null,array('class' => 'form-select','required'=>'required')) !!}
                
                @if(count($taxes) <= 0)
                    <small>
                        {{__('Please create new Tax')}} <a href="{{route('taxes.index')}}">{{__('here')}}</a>.
                    </small>
                @endif
            </div>
            <div class="form-group col-12">
                {{ Form::label('terms', __('Terms'),['class'=>'col-form-label']) }}
                {{ Form::textarea('terms',null, array('class' => 'form-control')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

