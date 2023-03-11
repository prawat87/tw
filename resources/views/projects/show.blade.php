@extends('layouts.admin')
@push('css-page')
    <link rel="stylesheet" href="{{asset('custom/libs/dropzonejs/dropzone.css')}}">
@endpush
@php
    $logo = \App\Models\Utility::get_file('avatars/');
@endphp
@push('script-page')
    <script src="{{asset('custom/libs/dropzonejs/min/dropzone.min.js')}}"></script>
    <script>
        // Update Project Status
        $(document).on("change", "#submit_status select[name=status]", function () {
            $('#submit_status').submit();
        });
    </script>
@endpush
@section('page-title')
    {{__('Project Detail')}} <small>({{$project->name}})</small>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Details')}}</li>
@endsection
@section('action-button')
        @can('manage project')
            <a href="{{ route('project_report.show',$project->id) }}" class="btn btn-xs btn-primary btn-icon-only width-auto" title="{{__('Bug Report')}}">
                <span class="text-white">{{__('Project Report')}}</span>
            </a>
        @endcan

        @can('manage task')
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show task',$perArr)))
                <a href="{{  route('project.taskboard',$project->id) }}" class="btn btn-xs btn-primary btn-icon-only width-auto" title="{{__('Task')}}">
                    <span class="text-white">{{__('Task')}}</span>
                </a>
            @endif
        @endcan
        @if(\Auth::user()->type!='client')
            {{-- @if(\Auth::user()->type!='employee')
                <a href="{{ route('projecttime.tracker',$project->id) }}" class="btn btn-xs btn-primary btn-icon-only width-auto" title="{{__('Tracker Detail')}}">
                    <span class="text-white">{{__('Tracker Detail')}}</span>
                </a>
            @endif --}}
            <a href="{{ route('task.timesheetRecord') }}" class="btn btn-xs btn-primary btn-icon-only width-auto" title="{{__('Timesheet')}}">
                <span class="text-white">{{__('Timesheet')}}</span>
            </a>
        @endif
        @can('edit project')
            <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}" title="{{__('Edit')}}" data-size="lg">
                <span class="text-white">{{__('Edit Project')}}</span>
            </a>
        @endcan
        @can('delete task')
            <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$project->id}}" title="{{__('Delete')}}">
                <span class="text-white">{{__('Delete Project')}}</span>
            </a>
            {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
            {!! Form::close() !!}
        @endcan
@endsection

@section('content')
    @php
        $permissions=$project->client_project_permission();
        $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);
        $project_last_stage = ($project->project_last_stage($project->id))? $project->project_last_stage($project->id)->id:'';

        $total_task = $project->project_total_task($project->id);
        $completed_task=$project->project_complete_task($project->id,$project_last_stage);

        $percentage=0;
        if($total_task!=0){
            $percentage = intval(($completed_task / $total_task) * 100);
        }

        $label='';
        if($percentage<=15){
            $label='bg-danger';
        }else if ($percentage > 15 && $percentage <= 33) {
            $label='bg-warning';
        } else if ($percentage > 33 && $percentage <= 70) {
            $label='bg-primary';
        } else {
            $label='bg-success';
        }
    @endphp

    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="d-block d-sm-flex align-items-center justify-content-between">
                                <h4 class="text-white">{{$project->name}}
                                    @foreach($project_status as $key => $status)
                                        @if($key== $project->status)
                                            @if($status=='Completed')
                                                @php $status_color ='bg-light-success' @endphp
                                            @elseif($status=='On Going')
                                                @php $status_color ='bg-light-primary' @endphp
                                            @else
                                                @php $status_color ='bg-light-warning' @endphp
                                            @endif
                                            <span class="badge rounded-pill {{$status_color}}">{{__($status)}}</span>
                                        @endif
                                    @endforeach
                                    @php
                                        $proj_label = $project->label();
                                    @endphp
                                    @if(!is_null($proj_label) && !empty($proj_label))
                                        <span class="badge rounded-pill bg-light-{{$proj_label->color}}">{{$proj_label->name}}</span>
                                    @endif
                                </h4>

                                <div class="row col-9">
                                    <div class="col-3">
                                        <span class="text-white text-sm mb-2 d-block">{{__('Progress')}} : {{$percentage}}%</span>
                                        <h5 class="text-white text-nowrap"  style="padding: 5px !important;">
                                            <div class="progress custom_progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$percentage}}%">
                                            </div>
                                        </div></h5>
                                    </div>
                                    <div class="col-3 px-5">
                                        <small class="text-white mb-2 d-block">{{__('Start Date')}}</small>
                                        <h5 class="text-white text-nowrap">{{ \Auth::user()->dateFormat($project->start_date)}}</h5>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-white mb-2 d-block">{{__('Due Date')}}</small>
                                        <h5 class="text-white text-nowrap">{{ \Auth::user()->dateFormat($project->due_date)}}</h5>
                                    </div>
                                    <div class="col-3" style="">
                                        <div class="col-auto">
                                            <span class="text-white text-sm mb-2 d-block">{{__('Status')}}:</span>
                                        </div>
                                        @if (\Auth::user()->type =='employee')
                                            <h5 class="text-white text-nowrap">{{ucfirst($project->status)}}</h5>
                                        @else
                                        {{ Form::model($project, array('route' => array('projects.update.status', $project->id), 'method' => 'POST','id'=>'submit_status')) }}

                                      <div class="row">

                                        <div class="col-auto">
                                            <h5 class="text-white text-nowrap">

                                                <select class="daily-text btn btn-sm btn-light text-start" name="status" id="status">
                                                    @foreach($project_status as $key => $value)
                                                        <option value="{{ $key }}" {{ ($project->status == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>


                                                </h5>
                                        </div>
                                    </div>
                                        {{ Form::close() }}
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $datetime1 = new DateTime($project->due_date);
                            $datetime2 = new DateTime(date('Y-m-d'));
                            $interval = $datetime1->diff($datetime2);
                            $days = $interval->format('%a')
                        @endphp
                        <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti ti-alarm"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Estimated')}} </h6>
                                            <h6 class="mb-0">{{ $project->getProjectTotalEstimatedTimes() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-success bg-dark">
                                                <i class="ti ti-calendar-time"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Logged')}}</h6>
                                            <h6 class="mb-0">{{ $project->getProjectTotalLoggedHours() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-clipboard"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Tasks')}}</h6>
                                            <h6 class="mb-0">{{$project->countTask()}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-database"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Comments')}}</h6>
                                            <h6 class="mb-0">{{$project->countTaskComments()}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-users"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Members')}}</h6>
                                            <h6 class="mb-0">{{$project->project_user()->count()}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col row">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti ti-calendar"></i>
                                            </div>
                                        </div>
                                        <div class="col text-end">
                                            <h6 class="mb-3 text-sm text-muted">{{__('Days Left')}}</h6>
                                            <h6 class="mb-0">{{$days}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">{{__('Staff')}}</h5>
                                @can('invite user project')
                                    <div class="ms-2">
                                        <a href="#" data-url="{{ route('project.invite',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" title="{{__('Add')}}" data-size="md" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-sm btn-primary btn-icon">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                    <div class="row mt-3 mx-3">

                        @foreach($project->project_user() as $user)
                            @php $totalTask= $project->user_project_total_task($user->project_id,$user->user_id) @endphp
                            @php $completeTask= $project->user_project_comlete_task($user->project_id,$user->user_id,($project->project_last_stage())?$project->project_last_stage()->id:'' ) @endphp
                            <div class="col-lg-3 col-5">
                                <div class="card">
                                    @can('invite user project')
                                        <div class="card-header border-0 pb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <div class="badge p-2 px-3 rounded bg-primary">{{ucfirst($user->type)}}</div>
                                                </h6>
                                            </div>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a class="bs-pass-para align-items-center btn btn-sm d-inline-flex" href="#"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $user->user_id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Remove') }}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.remove.user', $project->id,$user->user_id], 'id' => 'delete-form-' . $user->user_id]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                    <div class="card-body text-center">
                                        {{-- <img alt="user-image" class="img-fluid rounded-circle card-avatar" src="{{(!empty($user->avatar)? asset(Storage::url('avatar/'.$user->avatar)) : asset(Storage::url('avatar/avatar.png')))}}"> --}}
                                        @if (\Auth::user()->type == 'employee' || \Auth::user()->type == 'Project Manager')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-2">
                                                <div class="badge p-2 px-3 rounded bg-primary">{{ucfirst($user->type)}}</div>
                                            </h6>
                                        </div>
                                        @endif

                                        <img alt="image" src="{{(!empty($user->avatar))?  \App\Models\Utility::get_file('productimages/'.$user->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle card-avatar"  width="25" height="25">

                                        <h4 class="text-primary mt-2">{{$user->name}}</h4>
                                        <small class="text-primary">{{$user->email}}</small>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card">
                    <div class="card-header">
                        <h5>{{__('Activity')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row timeline-wrapper">
                            @foreach($project->activities as $activity)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <h5 class="my-3">{{ $activity->log_type }}</h5>
                                    <h6 class="text-muted mt-3">{{date('d M Y H:i', strtotime($activity->created_at))}}</h6>
                                    <p class="text-muted text-sm mb-3">{!! $activity->getRemark() !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>
                {{--Description--}}
                @if(!empty($project->description))
                    <div class="col-xxl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{__('Description')}}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-sm">{{$project->description}}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(\Auth::user()->type !='client' || (\Auth::user()->type=='client' && in_array('show milestone',$perArr)))
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5>{{__('Milestones')}} ({{count($project->milestones)}})</h5>
                                @if((\Auth::user()->type!='employee') && (\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('create milestone',$perArr))))
                                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('project.milestone',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Milestone')}}" title="{{__('create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Cost')}}</th>
                                            <th>{{__('Start Date')}}</th>
                                            <th>{{__('End Date')}}</th>
                                            <th>{{__('Cost')}}</th>
                                            <th>{{__('Progress')}}</th>
                                          @if (\Auth::user()->type != 'employee')
                                            <th>{{__('Action')}}</th>
                                          @endif
                                        </tr>
                                        </thead>
                                    <tbody class="list">
                                        @foreach($project->milestones as $milestone)
                                            <tr>
                                                <td class="Id"><a href="#" data-ajax-popup="true" data-title="{{ __('Milestones Details') }}" data-url="{{route('project.milestone.show',[$milestone->id])}}">{{$milestone->title}}</a></td>
                                                <td class="mile-text"><span>{{Auth::user()->priceFormat($milestone->cost)}}</span></td>
                                                <td class="mile-text">{{($milestone->start_date)}}</td>
                                                <td class="mile-text">{{($milestone->due_date)}}</td>
                                                <td class="Due">
                                                    <div class="date-box">{{ucfirst($milestone->status)}}</div>
                                                </td>
                                                <td>
                                                    <div class="progress_wrapper">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"  style="width: {{ $milestone->progress }}px;"
                                                                aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="progress_labels">
                                                        <div class="total_progress">
                                                            <strong> {{ $milestone->progress }}%</strong>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                @if (\Auth::user()->type != 'employee')
                                                <td class="Action-icon">
                                                    <span>
                                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('edit milestone',$perArr)))
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " data-url="{{ route('project.milestone.edit',$milestone->id) }}" data-ajax-popup="true" data-title="{{__('Edit Milestone')}}" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                                            </div>
                                                        @endif
                                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('delete milestone',$perArr)))
                                                            <div class="action-btn bg-danger ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$milestone->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                            </div>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'delete-form-'.$milestone->id]) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </span>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Upload File')}}</h5>
                         </div>
                       <div class="card-body">
                            <div class="col-md-12 dropzone mx-428 min-428 browse-file" id="my-dropzone"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
@push('script-page')
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#my-dropzone", {
            url: "{{route('project.file.upload',[$project->id])}}",
            success: function (file, response) {
                if (response.is_success) {
                    show_toastr('{{__("Success")}}', 'Attachment Create Successfully!', 'success');
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('{{__("Error")}}', 'File type must be match with Storage setting.', 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('{{__("Error")}}', response.error, 'error');
                } else {
                    show_toastr('{{__("Error")}}', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{$project->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            } else {
                                show_toastr('{{__("Error")}}', response.error, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.setAttribute('class', "text-center mt-10");
            html.appendChild(download);
            html.appendChild(del);

            file.previewTemplate.appendChild(html);
        }

        @php
            $files = $project->files;

        @endphp

        @foreach($files as $file)
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('project_files/'.$file->file_path))}}};
        myDropzone.emit("addedfile", mockFile);
        {{--myDropzone.emit("thumbnail", mockFile, "{{asset('storage/project_files/'.$file->file_path)}}");--}}
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('project_files/'.$file->file_path))}}");
        myDropzone.emit("complete", mockFile);
        dropzoneBtn(mockFile, {download: "{{route('projects.file.download',[$project->id,$file->id])}}", delete: "{{route('projects.file.delete',[$project->id,$file->id])}}"});
        @endforeach
    </script>
@endpush

