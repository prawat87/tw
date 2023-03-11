@extends('layouts.admin')
@php
    $dir= asset(Storage::url('plan'));
@endphp
@section('page-title')
    {{__('Manage Note')}}
@endsection

@section('action-button')
<div>
    <div class="row">
        @can('create lead stage')
            <div class="col-auto">
                <a href="#" data-url="{{ route('notes.create') }}" data-ajax-popup="true" data-title="{{__('Create Note')}}" class="btn btn-sm btn-primary btn-icon" data-size="md" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-plus"></i></a>
            </div>       
        @endcan
    </div>
</div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Note')}}</li>
@endsection
@section('content')

@if($notes->count() > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="row notes-list">
            @foreach($notes as $note)
                <div class="col-md-4">
                    <div class="card ">
                        
                        <div class="card-header">
                           
                                <div class="d-flex align-items-center">
                                    <h5  class="border-0">{{$note->title}}</h5>
                                    <span class="badge bg-{{$note->color}} me-2 ms-2 custom-badge-round px-2"></span>
                                </div>
                                
                        
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                @can('edit note')    
                                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Note') }}" data-url="{{ route('notes.edit',$note->id) }}">
                                        <i class="ti ti-edit"></i> <span>{{ __('Edit') }}</span>
                                    </a>
                                @endcan
                                @can('delete note')
                                <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$note->id}}">
                                   <i class="ti ti-trash"></i>  <span>{{ __('Delete')}}</span>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['notes.destroy', $note->id],'id'=>'delete-form-'.$note->id]) !!}
                                                        {!! Form::close() !!}
                                @endcan
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="note-text">
                                {{$note->text}}
                            </div>
                                <b>{{\Auth::user()->dateFormat($note->created_at)}}</b>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@else
    <div class="card">
        <div class="card-body p-4">
            <div class="page-error">
                <div class="page-inner">
                    <div class="page-description">
                        {{__("No Notes Found.!")}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
