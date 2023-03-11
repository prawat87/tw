
@php
$profiles = \App\Models\Utility::get_file('productimages/');
$profile = \App\Models\Utility::get_file('/');
@endphp

{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <table class="messenger-list-item m-li-divider @if('user_'.Auth::user()->id == $id && $id != "0") m-list-active @endif">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
            <div class="avatar av-m" style="background-color: #d9efff; text-align: center;">
                <span class="far fa-bookmark Saved_Messages" style="font-size: 22px; color: #68a5ff; margin-top: calc(50% - 10px);"></span>
            </div>
            </td>
            {{-- center side --}}
            <td style="width: 100%;">
                <p data-id="{{ 'user_'.Auth::user()->id }}" style="text-align: start;">Saved Messages <span>You</span></p>
                <span style="justify-content: left;display: flex;">Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- All users/group list -------------------- --}}
@if(!empty($user) && $user->count() > 0)
@if($get == 'users')
<table class="messenger-list-item @if($user->id == $id && $id != "0") m-list-active @endif" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
            @if($user->active_status)
                <span class="activeStatus"></span>
            @endif
           @if(!empty($user->avatar))
        <div class="avatar av-m" 
        style="background-image: url('{{ $profiles.'/'.$user->avatar }}');">
        </div>
        @else
         <div class="avatar av-m"
                         style="background-image: url('{{ $profile.'/avatar.png' }}');">
                    </div>
                @endif
        </td>
        {{-- center side --}}
        @if(!empty($lastMessage))
        <td style="width: 100%;">
        <p data-id="{{ $type.'_'.$user->id }}" style="text-align: start;">
            {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }} 
            <span>{{ !empty($lastMessage)?$lastMessage->created_at->diffForHumans():'' }}</span></p>
            <span style="justify-content: left;display: flex;">
                {{-- Last Message user indicator --}}
                {!!
                    $lastMessage->from_id == Auth::user()->id 
                    ? '<span class="lastMessageIndicator">'.__('You :').'</span>'
                    : ''
                !!}
                {{-- Last message body --}}
                @if($lastMessage->attachment == null)
                {!!
                    strlen($lastMessage->body) > 30 
                    ? trim(substr($lastMessage->body, 0, 30)).'..'
                    : $lastMessage->body
                !!}
                @else
                <span class="fas fa-file"></span> Attachment
                @endif
            </span>
        {{-- New messages counter --}}
            {!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}
        </td>
        @else
        <td></td>
        @endif
    </tr>
</table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
             @if($user->active_status)
                    <span class="activeStatus"></span>
                @endif

          @if(!empty($user->avatar))   
        <div class="avatar av-m"
        style="background-image: url('{{ $profiles.'/'.$user->avatar }}');">
        </div>
        @else
         <div class="avatar av-m"
                         style="background-image: url('{{$profile.'/avatar.png' }}');">
                    </div>
                @endif
        </td>
        {{-- center side --}}
        <td>
        <p data-id="{{ $type.'_'.$user->id }}">
            {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }} 
        </td>
        
    </tr>
</table>
@endif


{{-- -------------------- Get All Members -------------------- --}}

@if($get == 'all_members')
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td style="position: relative">
                @if($user->active_status)
                    <span class="activeStatus"></span>
                @endif
                @if(!empty($user->avatar))
                    <div class="avatar av-m"
                         style="background-image: url('{{ $profiles.'/'.$user->avatar }}');">
                    </div>
                @else
                    <div class="avatar av-m"
                         style="background-image: url('{{ $profile.'/avatar.png' }}');">
                    </div>
                @endif
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $type.'_'.$user->id }}">
                {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
            </td>

        </tr>
    </table>
@endif
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif


