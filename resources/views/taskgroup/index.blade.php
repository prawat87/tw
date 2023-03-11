@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Task Group') }}
@endsection

@section('action-button')
    <div>

        <div class="row">
            <div class="col-auto">
                <a href="{{ route('project.taskboard', [$project_id]) }}"
                    data-url="{{ route('project.taskboard', [$project_id]) }}" data-ajax-popup="false"
                    data-title="{{ __('Go to Task Kanban') }}" class="btn btn-sm btn-primary btn-icon" data-size="md"
                    title="{{ __('Go to Task Kanban') }}" data-bs-toggle="tooltip"
                    data-bs-placement="top"><span>Task</span></a>
                @can('create task')
                    <a href="#" data-url="{{ route('taskgroup.create', [$project_id]) }}" data-ajax-popup="true"
                        data-title="{{ __('Create New Group') }}" class="btn btn-sm btn-primary btn-icon" data-size="md"
                        title="{{ __('Create') }}" data-bs-toggle="tooltip" data-bs-placement="top"><i
                            class="ti ti-plus"></i></a>
                @endcan
            </div>




        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('projects.index') }}">{{ __('Project') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"><a
            href="{{ route('projects.show', $project_id) }}">{{ __('Project Detail') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Group') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Group') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($taskgroups as $k => $taskgroup)
                                    <tr data-id="{{ $taskgroup->id }}">
                                        <td>
                                            <div class="custom-control custom-radio mb-3 {{ $taskgroup->color }}">
                                                <label class="custom-control-label ">{{ $taskgroup->name }}</label>
                                            </div>
                                        </td>
                                        <td class="Action">
                                            <span>
                                                @can('edit task')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#"
                                                            data-url="{{ route('taskgroup.edit', $taskgroup->id) }}"
                                                            data-ajax-popup="true" data-title="{{ __('Edit Label') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><i class="ti ti-edit text-white "></i></a>
                                                    </div>
                                                @endcan
                                                @can('delete task')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#"
                                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $taskgroup->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><span class="text-white"><i
                                                                    class="ti ti-trash"></i></span></a>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['taskgroup.destroy', $taskgroup->id],
                                                            'id' => 'delete-form-' . $taskgroup->id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
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
