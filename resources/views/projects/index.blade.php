@extends('layouts.admin')
@section('page-title')
    {{__('Manage Project')}}
@endsection
@php
    $logo=\App\Models\Utility::get_file('avatar/');
@endphp
@section('action-button')
        @can('create project')
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="{{__('Import projects')}}" data-url="{{ route('project.file.import') }}" data-size="md" title="{{__('Import')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-file-import"></i>
            </a>
            <a href="{{ route('project.export') }}" class="btn btn-sm btn-primary btn-icon" title="{{__('Export')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-file-export"></i>
            </a>
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project')}}" data-size="lg" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Project')}}</li>
@endsection
@section('content')
    <div class="row">
        @foreach ($projects as $project)
            @php
                $permissions=$project->client_project_permission();
                $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

                $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

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
            <div class="col-md-3 col-xxl-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            @if($project->is_active==1)
                                <h5 class="mb-0"><a href="{{route('projects.show',$project->id)}}">{{ $project->name }}</a></h5>
                            @else
                                <h5 class="mb-0">{{ $project->name }}</h5>
                            @endif
                            @if($project->is_active==1)
                                <div class="ms-2">
                                    <p class="text-muted text-sm mb-0"><a href="{{ route('projects.show',$project->id) }}" class="text-secondary" title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-eye"></i>
                                    </a></p>
                                </div>
                            @endif
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                @if($project->is_active == 1)
                                    @if((Gate::check('edit project') || Gate::check('delete project')))
                                        <button type="button" class="btn dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('edit project')
                                                <a href="#" class="dropdown-item" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}" data-size="lg" title="{{__('Edit')}}">
                                                    <i class="ti ti-edit"></i>
                                                    <span>{{__('Edit')}}</span>
                                                </a>
                                            @endcan
                                            @can('delete project')
                                                <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$project->id}}">
                                                    <i class="ti ti-trash"></i>
                                                    <span>{{__('Delete')}}</span>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id], 'id' => 'delete-form-' . $project->id]) !!}
                                                {!! Form::close() !!}

                                            @endcan
                                        </div>
                                    @endif
                                @else
                                    <button type="button" class="btn">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 justify-content-between">
                            <div class="col-auto">
                                @foreach($project_status as $key => $status)
                                    @if($key== $project->status)
                                        @if($status=='Completed')
                                            @php $status_color ='bg-success' @endphp
                                        @elseif($status=='On Going')
                                            @php $status_color ='bg-primary' @endphp
                                        @else
                                            @php $status_color ='bg-warning' @endphp
                                        @endif
                                        <span class="badge rounded-pill {{$status_color}}">{{__($status)}}</span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-auto">
                                <p class="text-muted text-sm mb-0">{{__('Progress')}}: {{$percentage}}%</p>
                                <div class="mt-2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage}}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span class="mb-0"><b>{{ \Auth::user()->dateFormat($project->start_date) }}</b></span>
                                <p class="text-muted mb-0">{{__('Start Date')}}</p>
                            </div>
                            <div class="col-auto">
                                <span class="mb-0"><b class="text-end">{{ \Auth::user()->dateFormat($project->due_date) }}</b></span>
                                <p class="text-muted mb-0 text-end">{{__('Due Date')}}</p>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span class="mb-0"><b>{{ $project->getProjectTotalEstimatedTimes() }}</b></span>
                                <p class="text-muted mb-0">{{__('Estimated Hours')}}</p>
                            </div>
                            <div class="col-auto">
                                <span class="mb-0"><b class="text-end">{{ $project->getProjectTotalLoggedHours() }}</b></span>
                                <p class="text-muted mb-0 text-end">{{__('Logged Hours')}}</p>
                            </div>
                        </div>
                        <div class="row g-2 justify-content-between mt-2">
                            <div class="col-auto">
                                <span>{{__('Client')}}</span>
                                @php
                                $client=(!empty($project->client())?$project->client()->avatar:'')
                                @endphp


                                <div class="user-group">

                                    {{-- <img src="{{(!empty($project->client()->avatar)? asset(Storage::url('avatar/'.$client)) : asset(Storage::url('avatar/avatar.png')))}}" alt="image" data-bs-toggle="tooltip" title="{{!empty($project->client())?$project->client()->name:'-'}}" data-original-title="{{$project->client()->name}}"> --}}

                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($project->client())?$project->client()->name:'')}}" src="{{(!empty($project->client()->avatar))?  \App\Models\Utility::get_file('productimages/'.$project->client()->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25">

                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <span>{{__('Members')}}</span>
                                <div class="user-group">
                                    @foreach($project->project_user() as $project_user)
                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($project_user)?$project_user->name:'')}}" src="{{(!empty($project_user->avatar))?  \App\Models\Utility::get_file('productimages/'.$project_user->avatar): $logo."avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card mb-0 mt-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6 p-0">
                                        @if($project->is_active==1)
                                            <p class="text-muted mb-0">
                                            <a href="{{ route('project.taskboard',$project->id) }}" class=" text-muted"><i class="ti ti-list-check card-icon-text-space" class="text-muted"></i>{{$project->countTask()}} {{__('Tasks')}}</a></p>
                                        @else
                                            <i class="ti ti-list-check card-icon-text-space text-muted"></i>{{$project->countTask()}}
                                        @endif
                                    </div>
                                    {{-- <div class="col-6 p-0 text-end">
                                        @if($project->is_active==1)
                                        <p class="text-muted mb-0"><a href="{{ route('project.taskboard',$project->id) }}" class="text-muted"><i class="ti ti-messages card-icon-text-space"></i>{{$project->countTaskComments()}} {{__('Comments')}}</a></p>
                                        @else
                                            <i class="ti ti-messages card-icon-text-space"></i>{{$project->countTaskComments()}} {{__('Comments')}}
                                        @endif
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @if(\Auth::user()->type == "company")
        <div class="col-md-3">
            <a href="#" class="btn-addnew-project" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project')}}" data-size="lg" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{__('New Project')}}</h6>
                <p class="text-muted text-center">{{__('Click here to add New U')}}ser</p>
            </a>
        </div>
        @endif
    </div>
@endsection
