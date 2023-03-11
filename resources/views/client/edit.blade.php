{{Form::model($client,array('route' => array('clients.update', $client->id), 'method' => 'PUT')) }}
    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                {{Form::label('name',__('Name'),['class'=>'col-form-label']) }}
                {{Form::text('name',null,array('class'=>'form-control ','placeholder'=>__('Enter Client Name')))}}
            </div>
            <div class="col-md-12 form-group">
                {{Form::label('email',__('Email'),['class'=>'col-form-label']) }}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Client Email')))}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}
