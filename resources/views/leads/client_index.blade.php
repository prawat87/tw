@extends('layouts.admin')
@section('page-title')
    {{__('Manage Leads')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Leads')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style ">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Price')}}</th>
                                <th>{{__('User Assign')}} </th>
                                <th>{{__('Stage')}} </th>
                                <th>{{__('Notes')}} </th>
                            </thead>
                            <tbody>
                            @foreach ($leads as $lead)
                                <tr>
                                    <td>{{ $lead->name }}</td>
                                    <td>{{ $lead->price }}</td>
                                    <td>{{ (!empty($lead->user())?$lead->user()->name:'') }}</td>
                                    <td>{{ $lead->stage_name }}</td>
                                    <td>{{ $lead->notes }}</td>
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
