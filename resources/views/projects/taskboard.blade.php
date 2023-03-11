@extends('layouts.admin')
@push('css-page')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@endpush
@php
    $logo = \App\Models\Utility::get_file('avatars/');
@endphp
@push('script-page')
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
    <script>
        @can('move task')
            @if (\Auth::user()->type != 'client' || (\Auth::user()->type == 'client' && in_array('move task', $perArr)))
                ! function(a) {
                    "use strict";
                    var t = function() {
                        this.$body = a("body")
                    };
                    t.prototype.init = function() {
                        a('[data-plugin="dragula"]').each(function() {
                            var t = a(this).data("containers"),
                                n = [];
                            if (t)
                                for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                            else n = [a(this)[0]];
                            var r = a(this).data("handleclass");
                            r ? dragula(n, {
                                moves: function(a, t, n) {
                                    return n.classList.contains(r)
                                }
                            }) : dragula(n).on('drop', function(el, target, source, sibling) {

                                var order = [];
                                $("#" + target.id + " > div").each(function() {
                                    order[$(this).index()] = $(this).attr('data-id');
                                });

                                var id = $(el).attr('data-id');
                                var old_status = $("#" + source.id).attr('data-status');
                                var new_status = $("#" + target.id).attr('data-status');
                                var stage_id = $(target).attr('data-id');

                                $("#" + source.id).parent().find('.count').text($("#" + source.id +
                                    " > div").length);
                                $("#" + target.id).parent().find('.count').text($("#" + target.id +
                                    " > div").length);
                                show_toastr('{{ __('Success') }}', 'card move Successfully!', 'success')
                                $.ajax({
                                    url: '{{ route('taskboard.order') }}',
                                    type: 'POST',
                                    data: {
                                        task_id: id,
                                        stage_id: stage_id,
                                        order: order,
                                        old_status: old_status,
                                        new_status: new_status,
                                        "_token": $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(data) {

                                    },
                                    error: function(data) {
                                        data = data.responseJSON;
                                        show_toastr('{{ __('Error') }}', data.error,
                                            'error')
                                    }
                                });
                            });
                        })
                    }, a.Dragula = new t, a.Dragula.Constructor = t
                }(window.jQuery),
                function(a) {
                    "use strict";
                    a.Dragula.init()
                }(window.jQuery);
            @endif
        @endcan
    </script>

    <script>
        $(document).on('click', '#form-comment button', function(e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name = '{{ \Auth::user()->name }}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {
                        comment: comment,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function(data) {
                        data = JSON.parse(data);
                        var html = "<li class='media mb-20'>" +
                            "                    <div class='media-body'>" +
                            "                    <div class='d-flex justify-content-between align-items-end'><div>" +
                            "                        <h5 class='mt-0'>" + name + "</h5>" +
                            "                        <p class='mb-0 text-xs'>" + data.comment +
                            "</p></div>" +
                            "                           <div class='comment-trash' style=\"float: right\">" +
                            "                               <a href='#' class='btn btn-outline btn-sm text-danger delete-comment' data-url='" +
                            data.deleteUrl + "' >" +
                            "                                   <i class='ti ti-trash'></i>" +
                            "                               </a>" +
                            "                           </div>" +
                            "                    </div>" +
                            "                    </div>" +
                            "                </li>";
                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        show_toastr('{{ __('Success') }}', '{{ __('Comment Added Successfully!') }}',
                            'success');
                    },
                    error: function(data) {
                        show_toastr('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}', 'error');
                    }
                });
            } else {
                show_toastr('{{ __('Error') }}', '{{ __('Please write comment!') }}', 'error');
            }
        });

        $(document).on("click", ".delete-comment", function() {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        show_toastr('{{ __('Success') }}',
                            '{{ __('Comment Deleted Successfully!') }}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{ __('Error') }}', data.message, 'error');
                        } else {
                            show_toastr('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}',
                                'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function(e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('url'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    show_toastr('{{ __('Success') }}', '{{ __('File Added Successfully!') }}',
                        'success');
                    $('.file_update').html('');
                    $('#file-error').html('');
                    var delLink = '';
                    if (data.deleteUrl.length > 0) {
                        delLink =
                            "<a href='#' class='btn btn-outline btn-sm text-danger delete-comment-file m-0 px-2' data-id='" +
                            data.id + "' data-url='" + data.deleteUrl + "'>" +
                            "                                        <i class='ti ti-trash'></i>" +
                            "                                    </a>";
                    }

                    var html = '<div class="col-8 mb-2 file-' + data.id + '">' +
                        '                                <h5 class="mt-0 mb-1 font-weight-bold text-sm">' +
                        data.name + '</h5>' +
                        '                                <p class="m-0 text-xs">' + data.file_size +
                        '</p>' +
                        '                            </div>' +
                        '                            <div class="col-4 mb-2 file-' + data.id + '">' +
                        '                                <div class="comment-trash" style="float: right">' +
                        '                                    <a download href="{{ asset(Storage::url('tasks')) }}/' +
                        data.file + '" class="btn btn-outline btn-sm text-primary m-0 px-2">' +
                        '                                        <i class="ti ti-download"></i>' +
                        '                                    </a>' + delLink +
                        '                                </div>' +
                        '                            </div>';

                    $("#comments-file").prepend(html);
                },
                error: function(data) {
                    if (data.message) {
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        show_toastr('{{ __('Error') }}',
                            '{{ __('File type and size must be match with Storage setting.') }}',
                            'error');
                    }
                }
            });
        });

        $(document).on("click", ".delete-comment-file", function() {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        show_toastr('{{ __('Success') }}', '{{ __('File Deleted Successfully!') }}',
                            'success');
                        $('.file-' + btn.attr('data-id')).remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{ __('Error') }}', data.message, 'error');
                        } else {
                            show_toastr('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}',
                                'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-checklist', function(e) {
            e.preventDefault();
            if ($('.checklist-name').val() != '') {
                $.ajax({
                    url: $("#form-checklist").data('action'),
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        show_toastr('{{ __('Success') }}',
                            '{{ __('Checklist Added Successfully!') }}', 'success');
                        var html = '<li class="media">' +
                            '<div class="media-body">' +
                            '<h5 class="mt-0 mb-1 font-weight-bold"> </h5> ' +
                            '<div class="row"> ' +
                            '<div class="col-8"> ' +
                            '<div class="form-check form-check-inline"> ' +
                            '<input type="checkbox" id="checklist-' + data.id +
                            '" class="form-check-input"  data-url="' + data.updateUrl + '">' +
                            '<label for="checklist-' + data.id + '" class="form-check-label">' + data
                            .name + '</label> ' +
                            ' </div>' +
                            '</div> ' +
                            '<div class="col-4"> ' +
                            '<div class="comment-trash text-right"> ' +
                            '<a href="#" class="btn btn-outline btn-sm text-danger delete-checklist" data-url="' +
                            data.deleteUrl + '">' +
                            '<i class="ti ti-trash"></i>' +
                            '</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            ' </li>';


                        $("#check-list").prepend(html);
                        $("#form-checklist input[name=name]").val('');
                        $("#form-checklist").collapse('toggle');
                    },
                });
            } else {
                show_toastr('{{ __('Error') }}', '{{ __('Checklist name is required') }}', 'error');
            }
        });
        $(document).on("click", ".delete-checklist", function() {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        show_toastr('{{ __('Success') }}',
                            '{{ __('Checklist Deleted Successfully!') }}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('{{ __('Error') }}', data.message, 'error');
                        } else {
                            show_toastr('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}',
                                'error');
                        }
                    }
                });
            }
        });

        var checked = 0;
        var count = 0;
        var percentage = 0;

        $(document).on("change", "#check-list input[type=checkbox]", function() {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {},
                error: function(data) {
                    data = data.responseJSON;
                    show_toastr('{{ __('Error') }}', '{{ __('Some Thing Is Wrong!') }}', 'error');
                }
            });
            taskCheckbox();
        });
    </script>
@endpush

@section('page-title')
    {{ __('Manage Task') }}
@endsection

@section('action-button')
    <a href="{{ route('project.taskgroup', $project->id) }}" data-url="{{ route('project.taskgroup', $project->id) }}"
        data-title="{{ __('Task Group') }}" title="{{ __('Task Group') }}" data-bs-toggle="tooltip" data-bs-placement="top"
        class="btn btn-sm btn-primary btn-icon">
        <span>Task Group</span>
    </a>
    @can('create task')
        <a href="#" data-url="{{ route('task.create', $project->id) }}" data-ajax-popup="true"
            data-title="{{ __('Add New Task') }}" title="{{ __('Create') }}" data-bs-toggle="tooltip" data-bs-placement="top"
            class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
    @if ($project->id != '-1')
        <a href="{{ route('projects.show', $project->id) }}" title="{{ __('Back') }}" data-bs-toggle="tooltip"
            class="btn btn-sm btn-primary">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    @endif
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('projects.index') }}">{{ __('Project') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"><a
            href="{{ route('projects.show', $project->id) }}">{{ __('Project Detail') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Task') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            @php
                $json = [];
                foreach ($stages as $stage) {
                    $json[] = 'lead-list-' . $stage->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}'
                data-plugin="dragula">
                @foreach ($stages as $stage)
                    @php
                        $tasks = $stage->tasks($project->id);

                    @endphp

                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn btn-sm btn-primary btn-icon task-header">
                                        <span class="count text-white">{{ count($tasks) }}</span>
                                    </button>
                                </div>
                                <h4 class="mb-0">{{ $stage->name }}</h4>
                            </div>
                            <div id="lead-list-{{ $stage->id }}" data-status="{{ $stage->name }}"
                                data-id="{{ $stage->id }}" class="card-body kanban-box">
                                @foreach ($tasks as $task)
                                    <div class="card" data-id="{{ $task->id }}">
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5 style="margin-top: 35px;"><a href="#"
                                                    data-url="{{ route('task.show', $task->id) }}" data-ajax-popup="true"
                                                    data-title="{{ __('Task Board') }}"
                                                    class="text-dark">{{ $task->title }}</a></h5>
                                            @if (Gate::check('edit task') || Gate::check('delete task'))
                                                <div class="card-header-right">

                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @can('edit task')
                                                                <a class="dropdown-item"
                                                                    data-url="{{ route('task.edit', $task->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Task') }}"
                                                                    data-original-title="{{ __('Edit Task') }}"
                                                                    href="#"><i class="ti ti-edit"></i><span>
                                                                        {{ __('Edit') }}</span></a>
                                                            @endcan
                                                            @can('delete task')
                                                                <a class="dropdown-item bs-pass-para" href="#"
                                                                    data-title="{{ __('Delete Bug Report') }}"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $task->id }}"> <i
                                                                        class="ti ti-trash"></i><span>
                                                                        {{ __('Delete') }}</span></a>
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['task.destroy', $task->id],
                                                                    'id' => 'delete-form-' . $task->id,
                                                                ]) !!}
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted text-md">
                                                {{ Utility::getTaskGroupNameByGroupID($task->group_id) }}</p>
                                            @if ($task->priority == 'low')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-success p-2 px-3 rounded">
                                                        {{ ucfirst($task->priority) }}</div>
                                                </div>
                                            @elseif($task->priority == 'medium')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-warning p-2 px-3 rounded">
                                                        {{ ucfirst($task->priority) }}</div>
                                                </div>
                                            @elseif($task->priority == 'high')
                                                <div class="position-absolute top-0 start-0 pt-3 ps-3">
                                                    <div class="badge bg-danger p-2 px-3 rounded">
                                                        {{ ucfirst($task->priority) }}</div>
                                                </div>
                                            @endif
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center"><span
                                                            class="@if ($task->taskCompleteCheckListCount() == $task->taskTotalCheckListCount() && $task->taskCompleteCheckListCount() != 0) text-success @else text-warning @endif">{{ $task->taskCompleteCheckListCount() }}/{{ $task->taskTotalCheckListCount() }}</span>
                                                    </li>
                                                    <li class="list-inline-item d-inline-flex align-items-center ms-5"><i
                                                            class="f-16 text-primary ti ti-calendar-stats"></i><span
                                                            class="ms-2">{{ \Auth::user()->dateFormat($task->start_date) }}</span>
                                                    </li>
                                                </ul>
                                                <div class="user-group">
                                                    @php
                                                        if (!empty($task->assign_to)) {
                                                            $userInfo = \App\Models\Utility::getAssignedUserDetails($task->assign_to);
                                                        }

                                                    @endphp

                                                    @foreach ($userInfo as $assignedUser)
                                                        <img alt="image" data-toggle="tooltip" data-bs-placement="top"
                                                            data-original-title="{{ !empty($assignedUser) ? $assignedUser['name'] : '' }}"
                                                            title="{{ !empty($assignedUser) ? $assignedUser['name'] : '' }}"
                                                            src="{{ !empty($assignedUser['avatar']) ? asset(Storage::url('avatar/' . $assignedUser['avatar'])) : asset(Storage::url('avatar/avatar.png')) }}"
                                                            class="rounded-circle " width="25" height="25">
                                                    @endforeach
                                                    {{-- <img alt="image" data-toggle="tooltip" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}" src="{{(!empty($task->task_user->avatar)? asset(Storage::url('avatar/'.$task->task_user->avatar)) : asset(Storage::url('avatar/avatar.png')))}}" class="rounded-circle " width="25" height="25"> --}}

                                                    {{-- <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($task->task_user)?$task->task_user->name:'')}}" src="{{(!empty($task->task_user->avatar))?  \App\Models\Utility::get_file($task->task_user->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25"> --}}

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
