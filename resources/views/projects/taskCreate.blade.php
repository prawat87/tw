{{ Form::open(['route' => ['task.store', $project->id]]) }}
<div>
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('priority', __('Priority'), ['class' => 'col-form-label']) }}
            {!! Form::select('priority', $priority, null, ['class' => 'form-select', 'required' => 'required']) !!}
        </div>
        {{-- @if (\Auth::user()->type == 'company') --}}
        <div class="form-group  col-md-12">
            {{ Form::label('group_id', __('Group'), ['class' => 'col-md-3 col-form-label']) }}
            {!! Form::select('group_id', $groups, null, [
                'class' => 'asj-selectpicker show-tick form-select',
                'required' => 'required',
            ]) !!}
        </div>
        {{-- @endif --}}
        {{-- @if (\Auth::user()->type == 'company') --}}
        <div class="form-group  col-md-12">
            {{-- {{ Form::label('parent_task', __('Parent'),['class'=>'col-form-label']) }}
                    {!! Form::select('parent_task', $tasks, null,array('class' => 'form-select','required'=>'required')) !!} --}}
            <label for="parent_task" class="col-sm-3 control-label"> Parent </label>
            <select name="parent_task" id="parent_task" class="asj-selectpicker show-tick form-select" required>

            </select>
        </div>
        {{-- @endif --}}
        <div class="form-group  col-md-6">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('start_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('due_date', __('Due Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('due_date', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('estimated_mins', __('Estimation Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('estimated_mins', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('milestone_id', __('Milestone'), ['class' => 'col-form-label']) }}
            {!! Form::select('milestone_id', $milestones, null, ['class' => 'form-select']) !!}
        </div>
        @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'PMO' || \Auth::user()->type == 'Project Manager')
            <div class="form-group  col-md-12">
                {{ Form::label('assign_to', __('Assign To'), ['class' => 'col-form-label']) }}
                {!! Form::select('assign_to', $users, null, [
                    'class' => 'form-select asj-selectpicker',
                    'data-live-search' => 'true',
                    'name' => 'assign_to[]',
                    'multiple',
                    'required' => 'required',
                ]) !!}
            </div>
        @endif
    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control ', 'rows' => '2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}

<script>

$(document).on("change", "#commonModal select[name=group_id]", function () {
        $.ajax({
            url: '{{route('timesheet.project.task')}}',
            data: {project_id: {{$project_id}}, group_id: $(this).val(), _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (group_data) {
                $('#parent_task').empty();
                //if(group_data.length > 0)
                if(Object.keys(group_data).length > 0)
                {
                    //console.log('A');
                    $.each(group_data, function (key, data){


                        itemClass = (data.parent_task_id != data.id) ? 'subtask' : '';

                        $('#parent_task').append('<option class="'+ itemClass +'" value="' + data.id + '" >' + data.title + '</option>');



                    })

                    if($("#parent_task").hasClass("selectpicker"))
                    {
                        $("#parent_task").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
                    } else {
                        $("#parent_task").removeClass('form-select').addClass('selectpicker').selectpicker("render");
                    }
                } else {
                    $("#parent_task").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
                }
            }
        });
    });
    $(window).on('shown.bs.modal', function() {

        if ($(".asj-selectpicker").hasClass("selectpicker")) {
            $(".asj-selectpicker").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
        } else {

            $(".asj-selectpicker").removeClass('form-select').addClass('selectpicker').selectpicker("render");
        }
    });
</script>
