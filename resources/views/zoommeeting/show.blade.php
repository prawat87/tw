<div>
    <ul class="list-unstyled">
        <li class="mb-2"><strong class="text-dark">{{__('Zoom Meeting Title')}} :</strong> &nbsp; {{$zoommeeting->title}}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Zoom Meeting ID')}} :</strong> &nbsp;
            {{$zoommeeting->meeting_id}}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Project Name')}} :</strong> &nbsp; {{ !empty($zoommeeting->project_id)?$zoommeeting->projectName->name:'' }}</li>
        <li class="mb-2"><strong class="text-dark">{{__('User Name')}} :</strong> &nbsp; {{!empty($zoommeeting->employee)?$zoommeeting->getUserNameAttribute('name'):'' }}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Client Name')}} :</strong> &nbsp; {{ !empty($zoommeeting->client_id)?$zoommeeting->getClientNameAttribute('name'):'' }}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Date')}} :</strong> &nbsp;
            {{\Auth::user()->dateFormat($zoommeeting->start_date)}}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Time')}} :</strong> &nbsp;
            {{\Auth::user()->timeFormat($zoommeeting->start_date)}}</li>
        <li class="mb-2"><strong class="text-dark">{{__('Duration')}} :</strong> &nbsp;
            {{$zoommeeting->duration }} Minutes</li>
    </ul>
</div>
