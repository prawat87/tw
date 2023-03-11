@if(isset($product))
    {{ Form::model($product, array('route' => array('estimations.products.update', $estimation->id,$product->id), 'method' => 'POST')) }}
@else
    {{ Form::model($estimation, array('route' => array('estimations.products.store', $estimation->id), 'method' => 'POST')) }}
@endif
<div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
            {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required','placeholder'=>__('Please enter your service or product name'))) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
            {{ Form::label('price', __('Price'),['class'=>'col-form-label']) }}
            {{ Form::number('price', isset($product)?null:1, array('class' => 'form-control','required'=>'required','min'=>'1')) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
            {{ Form::label('quantity', __('Quantity'),['class'=>'col-form-label']) }}
            {{ Form::number('quantity', isset($product)?null:1, array('class' => 'form-control','required'=>'required','min'=>'1')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('description', __('Description'),['class'=>'col-form-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    @if(isset($product))
        <input type="submit" value="{{__('Update')}}" class="btn btn-secondary btn-light ms-2">
    @else
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    @endif
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
</div>
{{ Form::close() }}

