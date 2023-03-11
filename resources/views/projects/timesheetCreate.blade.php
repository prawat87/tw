    @php
        $project_id= Request::segment(2)
    @endphp
<style>
   /* .bootstrap-datetimepicker-widget.dropdown-menu {
  border: 1px solid #34495e;
  border-radius: 0;
  box-shadow: none;
  margin: 5px 0 0 0;
  padding: 0;
  min-width: 300px;
  max-width: 100%;
  max-height: 250px;
  width: auto;
  display: inline;
  background-color: red;
  &.bottom:before,
  &.bottom:after {
    display: none;
  } */

  .usetwentyfour {display: block !important;padding: 0; width:230px !important;}
  /* .usetwentyfour span { border: 1px solid rgb(112, 227, 11); float: left; height: 20px !important; line-height: 10px !important; }*/
  .usetwentyfour span { line-height: 30px !important; }
  .usetwentyfour span:hover { line-height: 30px !important; height: 30px; }
  .usetwentyfour a { padding: 0!important;}
  .usetwentyfour  table { text-align: center; }


  .usetwentyfour tr {
    margin: 0px!important;
    padding: 0!important;
  }
 .usetwentyfour td{
    text-align: center;
    display: inline-block;
    float: none;
    margin: 0px!important;
    padding: 0!important;
  }


  </style>
   <!-- {{ Form::open() }} -->
    <div>
        <div class="row">
            <div class="form-group col-md-2">
                {{ Form::label('date', __('Task Date'),['class'=>'col-form-label']) }}
                {{ Form::date('date', date('Y-m-d'), array('class' => 'form-control','required'=>'required')) }}
            </div>
            <div class="form-group col-md-3">
                {{ Form::label('project_id', __('Project'),['class'=>'col-form-label']) }}
                {!! Form::select('project_id', $projects, null,array('class' => 'form-select show-tick', 'required' => 'required')) !!}
                @if(\Auth::user()->type == "company")
                    @if(count($projects) < 2)
                        <small>{{__('Please Create Project')}} <a href="{{ route('projects.index') }}">{{__('here')}}</a></small>
                    @endif
                @endif
            </div>
            <div class="form-group col-md-3">
                {{ Form::label('task_id', __('Task'),['class'=>'col-form-label']) }}
                <select name="task_id" id="task_id" title="Select a Task" data-live-search="true" class="form-select show-tick" required>
                </select>
            </div>

            <div class="form-group col-md-2">
                {{ Form::label('start_time', __('Start Time'),['class'=>'col-form-label']) }}
                <div class="input-group time timesheet-time">

                    {{ Form::text('start_time', date('H:i'), array('class' => 'start form-control d-inline ml-4','required'=>'required')) }}
                    <span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-clock"></i></span></span>
                </div>

            </div>

            <div class="form-group col-md-2">
                {{ Form::label('end_time', __('End Time'),['class'=>'col-form-label']) }}
                {{ Form::time('end_time', date("H:i", strtotime("+2 hours")), array('class' => 'form-control','required'=>'required','min'=>0)) }}
            </div>
            <div class="form-group col-1">
                {{ Form::label('hours', __('Hours'),['class'=>'col-form-label']) }}
                {{ Form::input('text', 'hours', 2, array('class' => 'form-control', 'min'=>2, 'max'=>24)) }}
            </div>
            <div class="form-group col-1">
                {{ Form::label('minutes', __('Minutes'),['class'=>'col-form-label']) }}
                {{ Form::input('text', 'minutes', 0, array('class' => 'form-control', 'min'=>0, 'max'=>59)) }}
            </div>
            <div class="form-group col-10">
                <div style="float:right;">
                    <input type="checkbox" class="form-check-input input-primary" id="customCheckdef1" name="billable" checked>
                    <label class="form-check-label" for="customCheckdef1">{{ __('Billable') }}</label>
                </div>
            </div>
            <div class="form-group  col-md-12">
                {{ Form::label('remark', __('Remark'),['class'=>'col-form-label']) }}
                {!! Form::textarea('remark', null, ['class'=>'form-control','rows'=>'2']) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <!-- <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal"> -->
        <input id='submit_timesheet' type="submit" value="{{__('Add')}}" class="btn btn-primary mx-2">
    </div>
    <!-- {{ Form::close() }} -->

    {{ Form::open(array('route' => array('task.timesheet.store',$project_id), 'id' => 'showEntries')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns"><div class="dataTable-container"><table class="table mb-0 dataTable dataTable-table">
                            <thead>
                                <tr>
                                    <th data-sortable="" style="width: 20%;">
                                        <a href="#" class="dataTable-sorter">{{__('Project')}}</a>
                                    </th>
                                    <th data-sortable="" style="width: 20%;">
                                        <a href="#" class="dataTable-sorter">{{__('Task')}}</a>
                                    </th>
                                    <th data-sortable="" style="width: 10%;">
                                        <a href="#" class="dataTable-sorter">{{__('Date')}}</a>
                                    </th>
                                    <th data-sortable="" style="width: 10%;">
                                        <a href="#" class="dataTable-sorter">{{__('Start Time')}}</a>
                                    </th>
                                    <th data-sortable="" style="width: 10%;">
                                        <a href="#" class="dataTable-sorter">{{__('End Time')}}</a>
                                    </th>
                                    <th class="text-right" style="width: 5%;">
                                        <a href="#" class="dataTable-sorter">{{__('Time')}}</a>
                                    </th>
                                    <th style="width: 5%;">
                                        <a href="#" class="dataTable-sorter">{{__('Remark')}}</a>
                                    </th>
                                    <th style="width: 5%;">
                                        <a href="#" class="dataTable-sorter">{{__('Billable')}}</a>
                                    </th>
                                    <th class="text-right" style="width: 5%;">
                                        <a href="#" class="dataTable-sorter">{{__('Action')}}</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="entries">
                            </tbody>
                        </table></div></div>
                        <div class="modal-footer">
                            <div class="action-btn bg-danger mx-5"><a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center timesheet-remove-all-logs" data-id="'+ctr+'" data-logged-time="'+msec+'" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><input type="button" value="{{__('Cancel')}}" class="btn btn-danger"></a></div>
                            <input type="submit" value="{{__('Submit')}}" class="btn btn-primary mx-2">
                        </div>
                        <div class="modal-footer">
                            <p>{{__('Total Time')}} : <span id="totalTime">0</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
<script>


$(function() {

    $('#showEntries').hide();

    var itemClass = '';
    $(document).on("change", "#commonModal select[name=project_id]", function () {
        $.ajax({
            url: '{{route('timesheet.project.task')}}',
            data: {project_id: $(this).val(), _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (group_data) {
                $('#task_id').empty();
                //if(group_data.length > 0)
                if(Object.keys(group_data).length > 0)
                {
                    //console.log('A');
                    $.each(group_data, function (key, data){
                        taskgroup = (data[0].task_group == null) ? 'No Group' : data[0].task_group['name'];
                       var optiongrp = $('<optgroup label="'+taskgroup+'">');
                        $.each(data, function (key, taskdata) {
                        itemClass = (taskdata.parent_task_id != taskdata.id) ? 'subtask' : '';

                        optiongrp.append('<option class="'+ itemClass +'" value="' + taskdata.id + '" >' + taskdata.title + '</option>');
                        });
                        console.log(optiongrp);
                        $("#task_id").append(optiongrp);
                    })

                    if($("#task_id").hasClass("selectpicker"))
                    {
                        $("#task_id").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
                    } else {
                        $("#task_id").removeClass('form-select').addClass('selectpicker').selectpicker("render");
                    }
                } else {
                    $("#task_id").selectpicker('destroy').addClass('selectpicker').selectpicker("render");
                }
            }
        });
    });


});
</script>

<script> $(window).on('shown.bs.modal', function() {
$(".timesheet-time").datetimepicker({
    format: 'HH:mm',

    icons: {
      up: "fa fa-chevron-up fa-xs",
      down: "fa fa-chevron-down fa-xs"
    }
  });
      });
             </script>
