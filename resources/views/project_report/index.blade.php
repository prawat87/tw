@extends('layouts.admin')

@php
    $profile = asset(Storage::url('uploads/avatar'));
@endphp

@section('page-title')
    {{ __('Project Reports') }}
@endsection

@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Project Reports') }}</h5>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Project report') }}</li>
@endsection

@section('action-button')
    <a href="#" class="btn btn-sm btn-primary filter" title="" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="Filter">
        <i class="ti ti-filter"></i>
    </a>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/css/datatables.min.css') }}">

    <style>
        .table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .display-none {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="row  display-none" id="show_filter">
        {{-- @if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client') --}}
        <div class="  col-2">
            <select class="select form-select" name="all_users" id="all_users">
                <option value="" class="">{{ __('All Users') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- @endif --}}
        <div class="form-group col-3">
            <select class="select form-select" name="status" id="status">
                <option value="" class="px-4">{{ __('All Status') }}</option>
                <option value="on_going">{{ __('Ongoing') }}</option>
                <option value="completed">{{ __('Finished') }}</option>
                <option value="on_hold">{{ __('OnHold') }}</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control" type="date" id="start_date" name="start_date" value=""
                    autocomplete="off" required="required" placeholder="{{ __('Start Date') }}">
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control" type="date" id="end_date" name="end_date" value="" autocomplete="off"
                    required="required" placeholder="{{ __('End Date') }}">
            </div>
        </div>
        <div class=" col-1">
            <button class="btn btn-primary btn-filter apply">{{ __('Apply') }}</button>
        </div>
    </div>


    <div class="col-xl-12 mt-3">
        <div class="card table-card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="selection-datatable1">
                        <thead class="p-4">
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Projects') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Projects Members') }}</th>
                                <th>{{ __('Progress') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script-page')
    <script>
        (function() {
            const d_week = new Datepicker(document.querySelector(''), {
                buttonClass: 'btn',
                todayBtn: true,
                clearBtn: true,
                format: 'yyyy-mm-dd',
            });
        })();
    </script>

    <script>
        (function() {
            const d_week = new Datepicker(document.querySelector(''), {
                buttonClass: 'btn',
                todayBtn: true,
                clearBtn: true,
                format: 'yyyy-mm-dd',
            });
        })();
    </script>
    <script src="{{ asset('custom/js/jquery.dataTables.min.js') }}"></script>
    <script>
        var dataTableLang = {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            },
            lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
            zeroRecords: "{{ __('No data available in table.') }}",
            info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
            infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
            infoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
            search: "{{ __('Search:') }}",
            thousands: ",",
            loadingRecords: "{{ __('Loading...') }}",
            processing: "{{ __('Processing...') }}"
        }
    </script>
    <script type="text/javascript">
        $(".filter").click(function() {
            $("#show_filter").toggleClass('display-none');
        });
    </script>
    <script>
        $(document).ready(function() {

            var table = $("#selection-datatable1").DataTable({
                order: [],
                select: {
                    style: "multi"
                },
                "language": dataTableLang,
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            $(document).on("click", ".btn-filter", function() {

                getData();
            });

            function getData() {
                table.clear().draw();
                $("#selection-datatable1 tbody tr").html(
                    '<td colspan="11" class="text-center"> {{ __('Loading ...') }}</td>');

                var data = {
                    status: $("#status").val(),
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    all_users: $("#all_users").val(),
                };


                $.ajax({
                    url: '{{ route('projects.ajax') }}',
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        table.rows.add(data.data).draw(true);
                        loadConfirm();
                    },
                    error: function(data) {
                        show_toastr('Info', data.error, 'error')
                    }
                })
            }

            getData();

        });
    </script>
@endpush
