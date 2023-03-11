@extends('layouts.admin')
@section('page-title')
    {{__('Manage Bug Report')}}
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/css/plugins/dragula.min.css')}}">
@endpush
@php
    $logo = \App\Models\Utility::get_file('avatars/');
@endphp
@push('script-page')
    <script src="{{asset('assets/js/plugins/dragula.min.js')}}"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');
                        var stage_id = $(target).attr('data-id');

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                        show_toastr('{{__("Success")}}', '{{ __("Card move Successfully!")}}', 'success');
                        $.ajax({
                            url: '{{route('bug.kanban.order')}}',
                            type: 'POST',
                            data: {bug_id: id, status_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('{{__("Error")}}', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
    <script>
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
                        '                                        <a download href="{{asset(Storage::url('bugs'))}}/' + data.file + '" class="btn btn-outline btn-sm text-primary m-0 px-2">' +
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
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('projects.show',$project->id)}}">{{__('Project Detail')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Bug Report')}}</li>
@endsection
@section('action-button')
    @can('manage bug report')
        <a href="{{ route('task.bug',$project->id) }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Bug List')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-bug"></i></a>
    @endcan
    @can('create bug report')
        <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('task.bug.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create Bug')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
    <a href="{{route('projects.show',$project->id)}}" title="{{__('Back')}}" data-bs-toggle="tooltip" class="btn btn-sm btn-primary">
        <i class=" ti ti-arrow-back-up"></i> 
    </a>  
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            @php
                $json = [];
                foreach ($bugStatus as $status){
                    $json[] = 'lead-list-'.$status->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}' data-plugin="dragula">
                @foreach($bugStatus as $status)
                    @php $bugs = $status->bugs($project->id) @endphp
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn btn-sm btn-primary btn-icon task-header">
                                        <span class="count text-white">{{count($bugs)}}</span>
                                    </button>
                                </div>
                                <h4 class="mb-0">{{$status->title}}</h4>
                            </div>
                            <div id="lead-list-{{$status->id}}" data-id="{{$status->id}}" class="card-body kanban-box">
                                @foreach($bugs as $bug)
                                    <div class="card" data-id="{{$bug->id}}">
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5 style="margin-top: 35px;"><a href="#" data-url="{{ route('task.bug.show',[$project->id,$bug->id]) }}" data-ajax-popup="true" data-title="{{__('Bug Report')}}" class="text-dark">{{$bug->title}}</a></h5>
                                            @if(Gate::check('edit bug report') || Gate::check('delete bug report'))
                                                <div class="card-header-right">

                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @can('edit bug report')
                                                            <a class="dropdown-item" data-url="{{ route('task.bug.edit',[$project->id,$bug->id]) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Bug Report')}}" href="#"><i class="ti ti-edit"></i><span> {{__('Edit')}}</span></a>
                                                            @endcan
                                                            @can('delete bug report')
                                                                <a class="dropdown-item bs-pass-para" href="#" data-title="{{__('Delete Bug Report')}}" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$bug->id}}"> <i class="ti ti-trash"></i><span> {{__('Delete')}}</span></a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['task.bug.destroy', $project->id,$bug->id],'id'=>'delete-form-'.$bug->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted text-sm">{{(!empty($bug->description)) ? $bug->description : '-'}}</p>
                                            @if($bug->priority =='low')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-success p-2 px-3 rounded">{{ ucfirst($bug->priority) }}</div>
                                                </div>
                                            @elseif($bug->priority =='medium')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-warning p-2 px-3 rounded">{{ ucfirst($bug->priority) }}</div>
                                                </div>
                                            @elseif($bug->priority =='high')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-danger p-2 px-3 rounded">{{ ucfirst($bug->priority) }}</div>
                                                </div>
                                            @endif
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-calendar-stats"></i><span class="ms-2">{{ \Auth::user()->dateFormat($bug->start_date) }}</span></li>
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-calendar-stats"></i><span class="ms-2">{{ \Auth::user()->dateFormat($bug->due_date) }}</span></li>
                                                </ul>
                                                <div class="user-group">

                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($bug->task_user)?$bug->task_user->name:'')}}" src="{{(!empty($bug->task_user->avatar))?  \App\Models\Utility::get_file($bug->task_user->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection
