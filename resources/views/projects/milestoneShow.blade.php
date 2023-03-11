<div>
    <ul class="list-unstyled">
        <li class="mb-2"><strong class="text-dark">{{ __('Milestone Title')}} :</strong> &nbsp; {{$milestone->title}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Milestone Description')}} :</strong> &nbsp;
            {{$milestone->description}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Status')}} :</strong> &nbsp; 
            @if($milestone->status == 'incomplete')
                <span class="badge rounded-pill bg-light-warning">{{__('Incomplete')}}</span>
            @endif
            @if($milestone->status == 'complete')
                <span class="badge rounded-pill bg-light-primary">{{__('Complete')}}</span>
            @endif
        </li>
        <li class="mb-2"><strong class="text-dark">{{ __('Milestone Cost')}} :</strong> &nbsp; {{\App\Models\Utility::getValByName('site_currency_symbol') .' '. number_format($milestone->cost)}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Milestone Progress')}} :</strong> &nbsp; {{$milestone->progress}}</li>
    </ul>
</div>

