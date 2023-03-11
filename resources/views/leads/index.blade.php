@extends('layouts.admin')
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
                        show_toastr('{{__("Success")}}', 'card move Successfully!', 'success')
                        $.ajax({
                            url: '{{route('leads.order')}}',
                            type: 'POST',
                            data: {lead_id: id, stage_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
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
@endpush
@section('page-title')
    {{__('Manage Lead')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Leads')}}</li>
@endsection
@section('action-button')
    <div>
        @can('create lead')
            <div class="row">
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-primary btn-icon" data-url="{{ route('leads.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Lead')}}" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        

        <div class="col-sm-12">
            @php
                $json = [];
                foreach ($stages as $stage){
                    $json[] = 'lead-list-'.$stage->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}' data-plugin="dragula">
                @foreach($stages as $stage)
                    @if(\Auth::user()->type == 'company')
                        @php($leads = $stage->leads)
                    @else
                        @php($leads = $stage->user_leads())
                    @endif
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn btn-sm btn-primary btn-icon task-header">
                                        <span class="count text-white">{{count($leads)}}</span>
                                    </button>
                                </div>
                                <h4 class="mb-0">{{$stage->name}}</h4>
                            </div>
                            <div id="lead-list-{{$stage->id}}" data-id="{{$stage->id}}" class="card-body kanban-box">
                                @foreach($leads as $lead)
                                
                                    <div class="card" data-id="{{$lead->id}}">
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5>{{$lead->name}}</h5>
                                            @if(Gate::check('edit lead') || Gate::check('delete lead'))
                                            <div class="card-header-right">
                                                @if(!$lead->is_active)
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('edit lead')
                                                        <a class="dropdown-item" data-url="{{ URL::to('leads/'.$lead->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Lead')}}" href="#">
                                                            <i class="ti ti-edit"></i>
                                                            <span>{{__('Edit')}}</span>
                                                        </a>
                                                        @endcan
                                                        @can('delete lead')
                                                        <a class="dropdown-item bs-pass-para" href="#" data-title="{{__('Delete Lead')}}" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$lead->id}}">
                                                            <i class="ti ti-trash"></i>
                                                            <span>{{__('Delete')}}</span>
                                                        </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                        {!! Form::close() !!}
                                                        @endcan
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted text-sm">{{$lead->notes}}</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">
                                                    
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-calendar-stats"></i><span class="ms-2">{{ \Auth::user()->dateFormat($lead->created_at) }}</span></li>
                                                    
                                                    <li class="list-inline-item d-inline-flex align-items-center"><i
                                                            class="f-16 text-primary ti ti-receipt-2"></i><span class="ms-2">{{ \Auth::user()->priceFormat($lead->price) }}</span></li>
                                                </ul>
                                                <div class="user-group">
                                                    @if(\Auth::user()->type=='company')
                                                        
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($lead->user())?$lead->user()->name:'')}}" src="{{(!empty($lead->user()->avatar))?  \App\Models\Utility::get_file('productimages/'.$lead->user()->avatar): $logo."/avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25">
                                                        
                                                    @else
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{(!empty($lead->client())?$lead->client()->name:'')}}" src="{{(!empty($lead->user()->avatar))?  \App\Models\Utility::get_file('productimages/'.$lead->user()->avatar): $logo."/avatar.png"}}" class="img-fluid rounded-circle"  width="25" height="25">
                                                    @endif
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


