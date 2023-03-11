@extends('layouts.admin')
@section('page-title')
    {{__('Calendar')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Calendar')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{__('Calendar')}}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Tasks</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        @foreach(json_decode($calenderData) as $task)
                            @php
                                $month = date("m",strtotime($task->start));
                            @endphp
                            @if($month == date('m'))
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-calendar-event"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="m-0">
                                                    <a href="{{$task->url}}" class="fc-daygrid-event" style="white-space: inherit;">
                                                        <div class="fc-event-title-container">
                                                            <div class="fc-event-title text-dark">{{$task->title}}</div>
                                                        </div>
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{$task->start}}  to {{$task->end}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
       </div>
    </div>
@endsection
@push('script-page')

    <script>
        (function () {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                buttonText: {
                    timeGridDay: "{{__('Day')}}",
                    timeGridWeek: "{{__('Week')}}",
                    dayGridMonth: "{{__('Month')}}"
                },
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! ($calenderData) !!},
                locale: '{{basename(App::getLocale())}}',
                selectHelper: !0,
                eventStartEditable: !1,
            });
            calendar.render();
        })();

        $(document).on('click', '.fc-daygrid-event', function (e) {
            if ($(this).attr('href') != undefined) {
                if (!$(this).hasClass('deal')) {
                    e.preventDefault();
                    var event = $(this);
                    var title = $(this).find('.fc-event-title-container .fc-event-title').html();
                    console.log(title);
                    var size = 'md';
                    var url = $(this).attr('href');
                    var parts = url.split("/");
                    var last_part = parts[parts.length - 2];

                    if (last_part == 'invoices') {
                        window.location.href = url;
                    } else {
                        $("#commonModal .modal-title").html(title);
                        $("#commonModal .modal-dialog").addClass('modal-' + size);
                        $.ajax({
                            url: url,
                            success: function (data) {
                                $('#commonModal .modal-inner-data').html(data);
                                $("#commonModal").modal('show');
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                toastr('Error', data.error, 'error')
                            }
                        });
                    }
                }
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
                            "                                   <i class='ti ti-trash'></i>" +
                            "                               </a>" +
                            "                           </div>" +
                            "                    </div>" +
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

                    if (data.deleteUrl.length > 0) {
                        delLink = "<a href='#' class='btn btn-outline btn-sm text-danger delete-comment-file m-0 px-2' data-id='" + data.id + "' data-url='" + data.deleteUrl + "'>" +
                            "                                        <i class='ti ti-trash'></i>" +
                            "                                    </a>";
                    }

                    var html = '<div class="col-8 mb-2 file-' + data.id + '">' +
                        '                                <h5 class="mt-0 mb-1 font-weight-bold text-sm">' + data.name + '</h5>' +
                        '                                <p class="m-0 text-xs">' + data.file_size + '</p>' +
                        '                            </div>' +
                        '                            <div class="col-4 mb-2 file-' + data.id + '">' +
                        '                                <div class="comment-trash" style="float: right">' +
                        '                                    <a download href="{{asset(Storage::url('tasks'))}}/' + data.file + '" class="btn btn-outline btn-sm text-primary m-0 px-2">' +
                        '                                        <i class="ti ti-download"></i>' +
                        '                                    </a>' + delLink +
                        '                                </div>' +
                        '                            </div>';

                    $("#comments-file").prepend(html);
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        show_toastr('{{__("Error")}}', data.message, 'error');
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
        $(document).on('submit', '#form-checklist', function (e) {
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
                    success: function (data) {
                        show_toastr('{{__("Success")}}', '{{ __("Checklist Added Successfully!")}}', 'success');

                        var html = '<li class="media">' +
                            '<div class="media-body">' +
                            '<h5 class="mt-0 mb-1 font-weight-bold"> </h5> ' +
                            '<div class=" custom-control custom-checkbox checklist-checkbox"> ' +
                            '<input type="checkbox" id="checklist-' + data.id + '" class="custom-control-input"  data-url="' + data.updateUrl + '">' +
                            '<label for="checklist-' + data.id + '" class="custom-control-label"></label> ' +
                            '' + data.name + ' </div>' +
                            '<div class="comment-trash" style="float: right"> ' +
                            '<a href="#" class="btn btn-outline btn-sm red text-muted delete-checklist" data-url="' + data.deleteUrl + '">\n' +
                            '<i class="ti ti-trash"></i>' +
                            '</a>' +
                            '</div>' +
                            '</div>' +
                            ' </li>';

                        var html = '<li class="media">' +
                            '<div class="media-body">' +
                            '<h5 class="mt-0 mb-1 font-weight-bold"> </h5> ' +
                            '<div class="row"> ' +
                            '<div class="col-8"> ' +
                            '<div class="custom-control custom-checkbox checklist-checkbox"> ' +
                            '<input type="checkbox" class="form-check-input" id="checklist-' + data.id + '" class="custom-control-input"  data-url="' + data.updateUrl + '">' +
                            '<label for="checklist-' + data.id + '" class="custom-check-label">' + data.name + '</label> ' +
                            ' </div>' +
                            '</div> ' +
                            '<div class="col-4"> ' +
                            '<div class="comment-trash text-right"> ' +
                            '<a href="#" class="btn btn-outline btn-sm text-danger delete-checklist" data-url="' + data.deleteUrl + '">' +
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
                show_toastr('{{__("Error")}}', '{{ __("Checklist name is required")}}', 'error');
            }
        });
        $(document).on("click", ".delete-checklist", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('{{__("Success")}}', '{{ __("Checklist Deleted Successfully!")}}', 'success');
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

        var checked = 0;
        var count = 0;
        var percentage = 0;

        $( document ).ready(function() {
        $(document).on("change", "#check-list input[type=checkbox]", function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'POST',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('{{__("Error")}}', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            });
            taskCheckbox();
        });
    });

    </script>
@endpush
