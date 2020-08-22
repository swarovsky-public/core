<div id="sidebar" class="tm-sidebar-left uk-background-default">

    <div class="user">
        @if (Storage::disk('s3')->exists('users/' . Auth()->user()->id.'.jpg'))
            <img src="data:image/png;base64,{!! base64_encode(Storage::disk('s3')->get('users/' . Auth()->user()->id.'.jpg')) !!}"
                 class="uk-align-center uk-border-circle" width="100"  id="avatar"
                 alt="{{Auth()->user()->name . ' avatar'}}">
        @else
            <img id="avatar" width="100" class="uk-align-center uk-border-circle" alt="{{Auth()->user()->name}} avatar"
                 src="https://media.istockphoto.com/vectors/teacher-male-avatar-character-vector-id878841282?s=170x170"/>
        @endif
        <div class="uk-margin-top"></div>
        <div class="uk-text-center uk-text-truncate" style="font-size: 12px;">{{Auth()->user()->name}}</div>
        <div class="uk-text-center uk-text-truncate">{{Auth()->user()->email}}</div>
    </div>


    <ul uk-accordion>
        {{$slot}}

        <li>
            <a class="uk-accordion-title uk-text-small uk-text-bold" href="#">Security</a>
            <div class="uk-accordion-content uk-margin-remove-top">
                <ul class="uk-nav uk-nav-default">
                    <li><a href="{{route('roles.index')}}">Roles</a></li>
                    <li><a href="{{route('permissions.index')}}">Permissions</a></li>
                    <li><a href="{{route('users.index')}}">Users</a></li>
                </ul>
            </div>
        </li>
    </ul>
</div>
