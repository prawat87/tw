@php
    $import = \App\Models\Utility::get_file('uploads/'); 
@endphp

{{ Form::open(array('route' => array('client.import'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div>
        <div class="row">
            <div class="col-md-12 mb-6">
                {{Form::label('file',__('Download Sample Client CSV File'),['class'=>'col-form-label'])}}
                <a href="{{$import.'/sample'.'/sample_clients_.csv'}}" class="btn btn-sm btn-primary btn-icon">
                    <i class="ti ti-download"></i>
                </a>
            </div>
            <div class="col-md-12">
                <div class="choose-file form-group">
                    <label for="file" class="col-form-label">
                        <div>{{__('Select CSV File')}}</div>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </label>
                    <p class="upload_file"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Upload')}}" class="btn btn-primary ms-2">
    </div>
{{ Form::close() }}
