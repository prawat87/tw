
@php
$assigned = [];
    foreach ($assigned_users as $key => $user) {
       foreach ($user->project_users as $index => $value) {
        if($value->id == 4) continue;
        $assigned[] = $value->id;
       }
    }


@endphp
{{ Form::model($project, array('route' => array('projects.update', $project->id), 'method' => 'PUT')) }}
<div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Project Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Project Price'),['class'=>'col-form-label']) }}
            {{ Form::number('price', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'col-form-label']) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('due_date', __('Due Date'),['class'=>'col-form-label']) }}
            {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('client', __('Client'),['class'=>'col-form-label']) }}
            {!! Form::select('client', $clients, null,array('class' => 'form-select','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('user', __('User'),['class'=>'col-form-label']) }}
            {!! Form::select('user[]', $users, $assigned,array('class' => 'select asj-selectpicker','required'=>'required', 'data-width'=>'100%', 'multiple')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('lead', __('Lead'),['class'=>'col-form-label']) }}
            {!! Form::select('lead', $leads, null,array('class' => 'form-select','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('label', __('Label'),['class'=>'col-form-label me-2']) }}

            @foreach($labels as $k=>$label)
                <div class="form-check custom-radio-inline radio-border">
                    <input class="form-check-input input-{{$label->color}}" type="radio" name="label" id="customCheck_{{$k}}" value="{{$label->id}}" {{($label->id==$project->label)?'checked':''}}>
                    <label class="form-check-label" for="customCheck_{{$k}}">{{$label->name}}</label>
                </div>
            @endforeach
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
<script>
    $(window).on('shown.bs.modal', function() {

        if ($(".asj-selectpicker").hasClass("selectpicker")) {
            $(".asj-selectpicker").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
        } else {

            $(".asj-selectpicker").removeClass('form-select').addClass('selectpicker').selectpicker("render");
        }
    });
</script>
