{{ Form::open(['route' => 'zoommeeting.store','id'=>'store-user','method'=>'post']) }}
<div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Topic'),['class'=>'col-form-label']) }}
            {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => __('Enter Meeting Title'), 'required' => 'required')) }}
        </div> 

        <div class="form-group col-md-6">
            {{ Form::label('projects', __('Projects'),['class'=>'col-form-label']) }}
            {{ Form::select('project_id',$project ,null, array('class' => 'form-select project_select project_id','placeholder'=>__('Select Project'))) }}
        </div>


        <div class="form-group col-md-6 select2_option">
            {{ Form::label('employee', __('Users'),['class'=>'col-form-label']) }}
            <div id="members-div">
                {{Form::select('employee[]', [], null, ['class' => 'form-select', 'id' => 'members']) }}
            </div>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('datetime', __('Start Date / Time'),['class'=>'col-form-label']) }}
            {{ Form::text('start_date',null,array('class' => 'form-control date', 'placeholder' => __('Select Date/Time'), 'required' => 'required')) }}
        </div>    
        <div class="form-group col-md-6">
            {{ Form::label('duration', __('Duration'),['class'=>'col-form-label']) }}
            {{ Form::number('duration',null,array('class' => 'form-control', 'placeholder' => __('Enter Duration'), 'required' => 'required')) }}
        </div> 
          
        <div class="form-group col-md-6">
            {{ Form::label('password', __('Password (Optional)'),['class'=>'col-form-label']) }}
            {{ Form::password('password',array('class' => 'form-control', 'placeholder' => __('Enter Password'))) }}
        </div>
        <div class="form-group col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="client_id" checked="" name="client_id">
                <label class="form-check-label" for="client_id">
                    {{__('Invite Client For Zoom Meeting')}}
                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>

{{ Form::close() }}

<script type="text/javascript">
  

    $(document).ready(function () {
        $('.date').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "locale": {
                "format": 'MM/DD/YYYY H:mm'
            },
            "timePicker24Hour": true,
        }, function(start, end, label) {
            
        });
    });

    $(document).on('change', '.project_select', function () {
        var project_id = $(this).val();
        getparent(project_id);
    });

    function getparent(bid) {
        $("#members-div").html('');
        $('#members-div').append('<select class="form-control" id="members" name="employee[]" multiple></select>');

        $.ajax({
            url: `{{ url('zoom/project/select')}}/${bid}`,
            type: 'GET',
            success: function (data) {
                var list = '';
              
                $.each(data, function (i, item) {
                    
                    list += '<option value="' + item.id + '">' + item.name + '</option>';
                });

                
                $('#members').html(list);
                var multipleCancelButton = new Choices(
                    '#members', {
                        removeItemButton: true,
                       
                    }
                );
            }
        });
    }

</script>


