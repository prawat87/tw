@extends('layouts.admin')
@section('page-title')
    {{__('Manage Project Stages')}}
@endsection
@push('script-page')
    <script src="{{ asset('custom/js/jscolor.js') }}"></script>
    <script src="{{ asset('custom/libs/jquery-ui/jquery-ui.js') }}"></script>
    <script>
        $(function () {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
            $(".sortable").sortable({
                stop: function () {
                    var order = [];
                    $(this).find('li').each(function (index, data) {
                        order[index] = $(data).attr('data-id');
                    });
                    $.ajax({
                        url: "{{route('projectstages.order')}}",
                        data: {order: order, _token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        success: function (data) {
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            show_toastr('{{__("Error")}}', data.error, 'error')
                        }
                    })
                }
            });
        });
    </script>
@endpush

@section('action-button')
<div>
        @can('create product unit')
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('projectstages.create') }}" data-ajax-popup="true" data-title="{{__('Create Project Stage')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Project Stages')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info note-constant text-xs">
                <strong>{{__('Note')}} :</strong> {{__('System will consider last stage as a completed / done task for get progress on project.')}}
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="list-group sortable">
                        @foreach ($projectstages as $projectstage)
                            <li class="list-group-item" data-id="{{$projectstage->id}}">
                                <div class="row">
                                    <div class="col-6  text-dark">{{$projectstage->name}}</div>
                                    <div class="col-4  text-dark">{{$projectstage->created_at}}</div>
                                    <div class="col-2">
                                        @can('edit project stage')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ URL::to('projectstages/'.$projectstage->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Project Stages')}}" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                                        </div>
                                        @endcan
                                        @can('delete project stage')
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$projectstage->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['projectstages.destroy', $projectstage->id],'id'=>'delete-form-'.$projectstage->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="alert alert-dark" role="alert">
                {{__('Note')}} : {{__('You can easily change order of Lead stage using drag')}} &amp; {{__('drop.')}}
            </div>
        </div>
    </div>
@endsection
