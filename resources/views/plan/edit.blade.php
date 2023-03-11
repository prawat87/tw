{{Form::model($plan, array('route' => array('plans.update', $plan->id), 'method' => 'PUT', 'enctype' => "multipart/form-data")) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{Form::label('name',__('Name'),['class'=>'col-form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control ','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
            </div>
            <div class="form-group col-md-6">
                {{Form::label('price',__('Price'),['class'=>'col-form-label'])}}
                {{Form::number('price',null,array('class'=>'form-control','placeholder'=>__('Enter Plan Price'),'required'=>'required'))}}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('duration', __('Duration'),['class'=>'col-form-label'])}}
                {!! Form::select('duration', $arrDuration, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group col-md-6">
                {{Form::label('max_users',__('Maximum Users'),['class'=>'col-form-label'])}}
                {{Form::number('max_users',null,array('class'=>'form-control','required'=>'required'))}}
                <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
            </div>
            <div class="form-group col-md-6">
                {{Form::label('max_clients',__('Mabimum Clients'),['class'=>'col-form-label'])}}
                {{Form::number('max_clients',null,array('class'=>'form-control','required'=>'required'))}}
                <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
            </div>
            <div class="form-group col-md-6">
                {{Form::label('max_projects',__('Maximum Projects'),['class'=>'col-form-label'])}}
                {{Form::number('max_projects',null,array('class'=>'form-control','required'=>'required'))}}
                <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label'])}}
                {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

