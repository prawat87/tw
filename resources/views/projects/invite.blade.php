{{ Form::open(array('route' => array('invite',$project_id))) }}
    <div>
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('user', __('User'),['class'=>'col-form-label']) }}
                {!! Form::select('user[]', $employee, null,array('class' => 'select asj-selectpicker','data-width'=>'100%','required'=>'required', 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Add')}}" class="btn btn-primary ms-2">
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
