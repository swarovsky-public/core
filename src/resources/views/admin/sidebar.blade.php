<div id="sidebar" class="tm-sidebar-left uk-background-default">
    <center>
        <div class="user">
            <img id="avatar" width="100" class="uk-border-circle" src="https://media.istockphoto.com/vectors/teacher-male-avatar-character-vector-id878841282?s=170x170" />
            <div class="uk-margin-top"></div>
            <div class="uk-text-truncate" style="font-size: 12px;">{{Auth()->user()->name}}</div>
            <div class="uk-text-truncate">{{Auth()->user()->email}}</div>
        </div>
        <br />
    </center>

    <ul uk-accordion>
        <li class="uk-open">
            <a class="uk-accordion-title uk-text-small uk-text-bold" href="#">OnBoarding</a>
            <div class="uk-accordion-content uk-margin-remove-top">
                <ul class="uk-nav uk-nav-default">
                    <li><a href="{{route('step.index')}}">Steps</a></li>
                    <li><a href="{{route('goal.index')}}">Goals</a></li>
                    <li><a href="{{route('onboarding.index')}}">Onboardings</a></li>
                </ul>
            </div>
        </li>
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
