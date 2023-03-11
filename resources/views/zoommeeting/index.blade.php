@extends('layouts.admin')
@section('page-title')
    {{__('Zoom Meeting')}}
@endsection

@section('action-button')
{{-- <div class="row"> --}}
    
    {{-- <div class="col-auto"> --}}
        <a href="{{route('zoommeeting.Calender')}}" class="btn btn-sm btn-primary btn-icon"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Calender')}}"><i class="ti ti-calendar-event"></i></a>
    {{-- </div> --}}
    @if(\Auth::user()->type == 'company')
        {{-- <div class="col-auto ps-0"> --}}
            <a href="#" class="btn btn-sm btn-primary btn-icon" id="add-user" data-size="lg" data-ajax-popup="true" data-title="{{ __('Create Meeting') }}" data-url="{{route('zoommeeting.create')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}"><i class="ti ti-plus"></i></a>
        {{-- </div> --}}
    @endif
{{-- </div> --}}
@endsection

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/daterangepicker.css')}}">
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Zoom Meeting')}}</li>
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
                                <th> {{ __('TITLE') }} </th>
                                @if(\Auth::user()->type == 'employee')
                                <th> {{ __('CLIENT') }}  </th>
                                @endif
                                <th> {{ __('PROJECT') }}  </th>
                                <th> {{ __('MEETING TIME') }} </th>
                                <th> {{ __('DURATION') }} </th>
                                <th> {{ __('JOIN URL') }} </th>
                                <th> {{ __('STATUS') }} </th>
                                @if(\Auth::user()->type == 'company')
                                <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetings as $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                     
                                        @if(\Auth::user()->type == 'employee')
                                        <td>{{$item->client_name}}</td>
                                        @endif
                                        <td>{{ !empty($item->projectName)?$item->projectName->name:'' }}</td>
                                        <td>{{$item->start_date}}</td>
                                        <td>{{$item->duration}} {{__("Minutes")}}</td>
                                       
                                        <td>

                                            @if($item->created_by == \Auth::user()->id && $item->checkDateTime())
                                            <a href="{{$item->start_url}}" target="_blank"> {{__('Start meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                            @elseif($item->checkDateTime())
                                                <a href="{{$item->join_url}}" target="_blank"> {{__('Join meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->checkDateTime())
                                         
                                                @if($item->status == 'waiting')
                                                    <div class="badge bg-info p-2 px-3 rounded">{{ucfirst($item->status)}}</div>
                                                @else
                                                    <div class="badge bg-success p-2 px-3 rounded">{{ucfirst($item->status)}}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-danger p-2 px-3 rounded">{{__("End")}}</span>
                                            @endif
                                        </td>
                                        @if(\Auth::user()->type == 'company')
                                        <td>
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$item->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['zoommeeting.destroy', $item->id],'id'=>'delete-form-'.$item->id]) !!}
                                            {!! Form::close() !!}
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
    </div>

@endsection

@push('scripts')   
<script src="{{url('assets/js/daterangepicker.js')}}"></script>
<script type="text/javascript">

$(document).on('change', '#client_id', function() {
    getProjects($(this).val());
});

function getProjects(id){
    $.get("{{url('get-projects')}}/"+id, function(data, status){
     
     var list = '';
     $('#project_id').empty();
     if(data.length > 0){
         list += "<option value=''> {{__('Select Project')}}</option>";
     }else{
         list += "<option value=''> {{__('No Projects')}} </option>";
     }

     $.each(data, function(i, item) {
         list += "<option value='"+item.id+"'>"+item.name+"</option>"
     });
     $('#project_id').html(list);
 });
}
$(document).on("click", '.member_remove', function () {
    var rid = $(this).attr('data-id');
    alert(rid);
    $('.confirm_yes').addClass('m_remove');
    $('.confirm_yes').attr('uid', rid);
    $('#cModal').modal('show');
});
$(document).on('click', '.m_remove', function (e) {
    var id = $(this).attr('uid');
    var p_url = "{{url('zoom-meeting')}}"+'/'+id;
    var data = {id: id};
    deleteAjax(p_url, data, function (res) {
        toastrs(res.flag, res.msg);
        if(res.flag == 1){
            location.reload();
        }
        $('#cModal').modal('hide');
    });
});
</script>
@endpush