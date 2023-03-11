{{ Form::model($expense, array('route' => array('expenses.update', $expense->id), 'method' => 'PUT','enctype' => "multipart/form-data")) }}
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('category_id', __('Category'),['class'=>'col-form-label']) }}
                {{ Form::select('category_id', $category,null, array('class' => 'form-select','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('amount', __('Amount'),['class'=>'col-form-label']) }}
                {{ Form::number('amount', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('date', __('Date'),['class'=>'col-form-label']) }}
                {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('project', __('Project'),['class'=>'col-form-label']) }}
                {{ Form::select('project', $projects,null, array('class' => 'form-select','required'=>'required')) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
                {{ Form::select('user_id', $users,null, array('class' => 'form-select','required'=>'required')) }}
            </div>
            <div class="col-md-12">
                {{ Form::label('attachment', __('Attachment'),['class'=>'col-form-label']) }}
                <div class="choose-file">
                    <label for="attachment" class="col-form-label">
                        <div>{{__('Choose file here')}}</div>
                        <input type="file" class="form-control" name="attachment" id="attachment" accept=".jpeg,.jpg,.png,.doc,.pdf" onchange="document.getElementById('file').src = window.URL.createObjectURL(this.files[0])">
                        <img src="" id="file" class="mt-2" width="25%">
                    </label>
                    <p class="attachment_update"></p>
                </div>
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
                {!! Form::textarea('description', null, ['class'=>'form-control ','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}

