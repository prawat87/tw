@extends('layouts.admin')

@section('page-title')
    {{__('Manage Email Templates')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Email Templates')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table dataTable">
                        <thead>
                        <tr>
                            <th width="92%">{{__('Name')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($EmailTemplates as $EmailTemplate)
                            <tr>
                                <td>{{ $EmailTemplate->name }}</td>
                                <td class="Action">
                    
                                @can('edit email template lang')
                                <div class="action-btn bg-warning ms-2">
                                    <a href="{{ route('manage.email.language',[$EmailTemplate->id,\Auth::user()->currentLanguage()]) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-original-title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                    <span class="text-white"><i class="ti ti-eye"></i></span>
                                </a></div>
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
