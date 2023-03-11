{{ Form::model($timeSheet, ['route' => ['task.timesheet.update', $timeSheet->id], 'method' => 'POST']) }}
<div>
    <div class="row">
        <div class="form-group col-md-2">
            {{ Form::label('date', __('Task Date'), ['class' => 'col-form-label']) }}
            {{ Form::date('date', $timeSheet->date, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-3">
            {{ Form::label('project_id', __('Project'), ['class' => 'col-form-label']) }}
            {!! Form::select('project_id', $projects, null, ['class' => 'form-select', 'required' => 'required']) !!}
        </div>
        <div class="form-group col-md-3">
            {{ Form::label('task_id', __('Task'), ['class' => 'col-form-label']) }}
            <select name="task_id" id="task_id" class="form-select" required>
                <option value="">{{ __('Select Task') }}</option>
            </select>
        </div>

        <div class="form-group col-md-2">
            {{ Form::label('start_time', __('Start Time'), ['class' => 'col-form-label']) }}
            {{ Form::time('start_time', $timeSheet->start_time, ['class' => 'time start form-control d-inline ml-4', 'required' => 'required', 'min' => 0]) }}
        </div>

        <div class="form-group col-md-2">
            {{ Form::label('end_time', __('End Time'), ['class' => 'col-form-label']) }}
            {{ Form::time('end_time', $timeSheet->end_time, ['class' => 'time form-control', 'required' => 'required', 'min' => 0]) }}
        </div>
        <div class="form-group col-1">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::input('text', 'hours', 2, ['class' => 'form-control', 'min' => 2, 'max' => 24]) }}
        </div>
        <div class="form-group col-1">
            {{ Form::label('minutes', __('Minutes'), ['class' => 'col-form-label']) }}
            {{ Form::input('text', 'minutes', 0, ['class' => 'form-control', 'min' => 0, 'max' => 59]) }}
        </div>
        <div class="form-group col-10">
            <div style="float:right;">
                <input type="checkbox" class="form-check-input input-primary" id="customCheckdef1" name="billable"
                    {{ $timeSheet->billable == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="customCheckdef1">{{ __('Billable') }}</label>
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
    </div>
</div>
{{-- <div>
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('project_id', __('Project'),['class'=>'col-form-label']) }}
                {!! Form::select('project_id', $projects, null,array('class' => 'form-select','required'=>'required')) !!}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('task_id', __('Task'),['class'=>'col-form-label']) }}
                <select name="task_id" id="task_id" class="form-select" required>
                    <option value="">{{__('Select Task')}}</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('date', __('Task Date'),['class'=>'col-form-label']) }}
                {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('time', __('Task Time'),['class'=>'col-form-label']) }}
                {{ Form::time('time', null, array('class' => 'form-control','required'=>'required','min'=>0)) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group  col-md-12">
                {{ Form::label('remark', __('Remark'),['class'=>'col-form-label']) }}
                {!! Form::textarea('remark', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div> --}}
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}


<script>
    var task_id = '{{ $timeSheet->task_id }}';
    var total_mins = '{{ $timeSheet->total_mins }}';
    $(document).ready(function() {


        let hrs = Math.floor(total_mins / 60); // Total Difference in Hours
        let mins = total_mins % 60; // Display Minutes

        $("#hours").val(hrs);
        $("#minutes").val(mins);

        $("#commonModal select[name=project_id]").trigger('change');
    });

    $(document).on("change", "#commonModal select[name=project_id]", function() {
        $.ajax({
            url: '{{ route('timesheet.project.task') }}',
            data: {
                project_id: $(this).val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(group_data) {
                $('#task_id').empty();
                //if(group_data.length > 0)
                if (Object.keys(group_data).length > 0) {
                    //console.log('A');
                    $.each(group_data, function(key, data) {
                        taskgroup = (data[0].task_group == null) ? 'No Group' : data[0]
                            .task_group['name'];
                        var optiongrp = $('<optgroup label="' + taskgroup + '">');
                        $.each(data, function(key, taskdata) {
                            itemClass = (taskdata.parent_task_id != taskdata.id) ?
                                'subtask' : '';

                            optiongrp.append('<option class="' + itemClass +
                                '" value="' + taskdata.id + '" >' + taskdata
                                .title + '</option>');
                        });
                        console.log(optiongrp);
                        $("#task_id").append(optiongrp);
                    })

                    if ($("#task_id").hasClass("selectpicker")) {
                        $("#task_id").selectpicker('destroy').addClass('selectpicker').selectpicker(
                            "render");
                    } else {
                        $("#task_id").removeClass('form-select').addClass('selectpicker')
                            .selectpicker("render");
                    }
                } else {
                    $("#task_id").selectpicker('destroy').addClass('selectpicker').selectpicker(
                        "render");
                }
            }
        });
    });
</script>
