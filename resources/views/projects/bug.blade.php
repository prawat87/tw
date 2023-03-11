@extends('layouts.admin')
@section('page-title')
    {{__('Manage Bug Report')}}
@endsection

@section('action-button')
    @can('manage bug report')
        <a  href="{{ route('task.bug.kanban',$project->id) }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Bug kanban')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-layout-kanban"></i>
        </a>
    @endcan
        <a  href="{{ route('bug.export',$project->id) }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-file-export"></i>
        </a>
    
    @can('create bug report')
        <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('task.bug.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create Bug')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
    @if($project->id != '-1')
        <a href="{{route('projects.show',$project->id)}}" title="{{__('Back')}}" data-bs-toggle="tooltip" class="btn btn-sm btn-primary">
            <i class=" ti ti-arrow-back-up"></i> 
        </a>    
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('projects.show',$project->id)}}">{{__('Project Detail')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Bug Report')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <th> {{__('Bug Id')}}</th>
                                <th> {{__('Assign To')}}</th>
                                <th> {{__('Bug Title')}}</th>
                                <th> {{__('Start Date')}}</th>
                                <th> {{__('Due Date')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Priority')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Created By')}}</th>
                                <th> {{__('Action')}}</th>
                            </thead>
                            <tbody>
                            @foreach ($bugs as $bug)
                                <tr>
                                    <td><a href="#" class="btn btn-outline-primary" data-url="{{ route('task.bug.show',[$project->id,$bug->id]) }}" data-ajax-popup="true" data-title="{{__('Bug Report')}}">{{ \Auth::user()->bugNumberFormat($bug->bug_id)}}</a></td>
                                    <td>{{ (!empty($bug->assignTo)?$bug->assignTo->name:'') }}</td>
                                    <td>{{ $bug->title}}</td>
                                    <td>{{ Auth::user()->dateFormat($bug->start_date) }}</td>
                                    <td>{{ Auth::user()->dateFormat($bug->due_date) }}</td>
                                    <td>{{ (!empty($bug->bug_status)?$bug->bug_status->title:'') }}</td>
                                    <td>{{ $bug->priority }}</td>
                                    <td>{{ $bug->description }}</td>
                                    <td>{{ $bug->createdBy->name }}</td>
                                    <td class="Action">
                                        @can('edit bug report')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('task.bug.edit',[$project->id,$bug->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Bug Report')}}" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                            </div>
                                        @endcan
                                        @can('delete bug report')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$bug->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['task.bug.destroy', $project->id,$bug->id],'id'=>'delete-form-'.$bug->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
$(document).on('submit', '#form-file', function (e) {
    e.preventDefault();
    $.ajax({
        url: $("#form-file").data('url'),
        type: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,

        success: function (data) {
            show_toastr('{{__("Success")}}', '{{ __("File Added Successfully!")}}', 'success');
            var delLink = '';

            $('.file_update').html('');
            $('#file-error').html('');

            if (data.deleteUrl.length > 0) {
                delLink = "<a href='#' class='text-danger text-muted delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                    "                                        <i class='dripicons-trash'></i>" +
                    "                                    </a>";
            }

            var html = '<div class="col-8 mb-2 file-' + data.id + '">' +
                '                                    <h5 class="mt-0 mb-1 font-weight-bold text-sm"> ' + data.name + '</h5>' +
                '                                    <p class="m-0 text-xs">' + data.file_size + '</p>' +
                '                                </div>' +
                '                                <div class="col-4 mb-2 file-' + data.id + '">' +
                '                                    <div class="comment-trash" style="float: right">' +
                '                                        <a download href="{{\App\Models\Utility::get_file('bugs')}}/' + data.file + '" class="btn btn-outline btn-sm text-primary m-0 px-2">' +
                '                                            <i class="ti ti-download"></i>' +
                '                                        </a>' +
                '                                        <a href="#" class="btn btn-outline btn-sm red text-danger delete-comment-file m-0 px-2" data-id="' + data.id + '" data-url="' + data.deleteUrl + '">' +
                '                                            <i class="ti ti-trash"></i>' +
                '                                        </a>' +
                '                                    </div>' +
                '                                </div>';

            $("#comments-file").prepend(html);
        },
        error: function (data) {
            
          
            show_toastr('{{__("Error")}}', '{{ __("File type and size must be match with Storage setting.")}}', 'error');
            data = data.responseJSON;
          
            if (data.message) {
                $('#file-error').text(data.errors.file[0]).show();
            } else {
                show_toastr('{{__("Error")}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
            }
        }
    });
});

$(document).on("click", ".delete-comment-file", function () {
    if (confirm('Are You Sure ?')) {
        var btn = $(this);
        $.ajax({
            url: $(this).attr('data-url'),
            type: 'DELETE',
            data: {_token: $('meta[name="csrf-token"]').attr('content')},
            dataType: 'JSON',
            success: function (data) {
                show_toastr('{{__("Success")}}', '{{ __("File Deleted Successfully!")}}', 'success');
                $('.file-' + btn.attr('data-id')).remove();
            },
            error: function (data) {
                data = data.responseJSON;
                if (data.message) {
                    show_toastr('{{__("Error")}}', data.message, 'error');
                } else {
                    show_toastr('{{__("Error")}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            }
        });
    }
});

$(document).on('click', '#form-comment button', function (e) {
    var comment = $.trim($("#form-comment textarea[name='comment']").val());
    var name = '{{\Auth::user()->name}}';
    if (comment != '') {
        $.ajax({
            url: $("#form-comment").data('action'),
            data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                data = JSON.parse(data);
                var html = "<li class='media mb-20'>" +
                    "                    <div class='media-body'>" +
                    "                    <div class='d-flex justify-content-between align-items-end'><div>" +
                    "                        <h5 class='mt-0'>" + name + "</h5>" +
                    "                        <p class='mb-0 text-xs'>" + data.comment + "</p></div>" +
                    "                           <div class='comment-trash' style=\"float: right\">" +
                    "                               <a href='#' class='btn btn-outline btn-sm text-danger delete-comment' data-url='" + data.deleteUrl + "' >" +
                    "                                   <i class='fa fa-trash'></i>" +
                    "                               </a>" +
                    "                           </div>" +
                    "                           </div>" +
                    "                    </div>" +
                    "                </li>";
                $("#comments").prepend(html);
                $("#form-comment textarea[name='comment']").val('');
                show_toastr('{{__("Success")}}', '{{ __("Comment Added Successfully!")}}', 'success');
            },
            error: function (data) {
                show_toastr('{{__("Error")}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
            }
        });
    } else {
        show_toastr('{{__("Error")}}', '{{ __("Please write comment!")}}', 'error');
    }
});

$(document).on("click", ".delete-comment", function () {
    if (confirm('Are You Sure ?')) {
        var btn = $(this);
        $.ajax({
            url: $(this).attr('data-url'),
            type: 'DELETE',
            data: {_token: $('meta[name="csrf-token"]').attr('content')},
            dataType: 'JSON',
            success: function (data) {
                show_toastr('{{__("Success")}}', '{{ __("Comment Deleted Successfully!")}}', 'success');
                btn.closest('.media').remove();
            },
            error: function (data) {
                data = data.responseJSON;
                if (data.message) {
                    show_toastr('{{__("Error")}}', data.message, 'error');
                } else {
                    show_toastr('{{__("Error")}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            }
        });
    }
});


</script>
@endpush