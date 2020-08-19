<div uk-sticky class="uk-navbar-container tm-navbar-container uk-active">
    <div class="uk-container uk-container-expand">
        <nav uk-navbar>
            <div class="uk-navbar-left">
                <a id="sidebar_toggle" class="uk-navbar-toggle" uk-navbar-toggle-icon ></a>
                <a href="#" class="uk-navbar-item uk-logo">
                    UI Admin
                </a>
            </div>
            <div class="uk-navbar-right uk-light">
                <ul class="uk-navbar-nav">
                    <li class="uk-active">
                        <a href="#">{{Auth()->user()->name}}<span uk-icon="chevron-down"></span></a>
                        <div uk-dropdown="pos: bottom-right; mode: click; offset: -17;">
                           <ul class="uk-nav uk-navbar-dropdown-nav">
                               <li class="uk-nav-header">Options</li>
                               <li><a href="{{ route('user.profile') }}">Edit Profile</a></li>
                               <li><a href="{{ route('user.security') }}">{{ __('Security') }}</a></li>
                               <li class="uk-nav-header">Actions</li>
                               <li>
                                   <a class="pure-menu-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                           </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
