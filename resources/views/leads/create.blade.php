{{ Form::open(array('url' => 'leads')) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
                {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            </div>

            <div class="form-group  col-md-6">
                {{ Form::label('price', __('Price'),['class'=>'col-form-label']) }}
                {{ Form::number('price', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group  col-md-6">
                {{ Form::label('stage', __('Stage'),['class'=>'form-label col-form-label']) }}
                {{ Form::select('stage', $stages,null, array('class' => 'form-select','required'=>'required')) }}
            </div>
            @if(\Auth::user()->type=='company')
                <div class="form-group  col-md-6">
                    {{ Form::label('owner', __('Lead User'),['class'=>'form-label col-form-label']) }}
                    {!! Form::select('owner', $owners, null,array('class' => 'form-select','required'=>'required')) !!}
                </div>
            @endif
            <div class="form-group  col-md-6">
                {{ Form::label('client', __('Client'),['class'=>'form-label col-form-label']) }}
                {!! Form::select('client', $clients, null,array('class' => 'form-select','required'=>'required')) !!}        
            </div>
            <div class="form-group  col-md-6">
                {{ Form::label('source', __('Source'),['class'=>'form-label col-form-label']) }}
                {!! Form::select('source', $sources, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group  col-md-12">
                {{ Form::label('notes', __('Notes'),['class'=>'col-form-label']) }}
                {!! Form::textarea('notes', '',array('class' => 'form-control','rows'=>'3')) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

