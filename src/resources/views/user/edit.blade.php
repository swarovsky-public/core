@extends('swarovsky-core::layouts.admin')

@section('content')
<div class="uk-section-small">
    <div class="uk-container uk-container-large">

        <div class="uk-flex uk-flex-between uk-flex-middle">
            <ul class="uk-breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                <li><a href="{{route("users.index")}}">Users</a></li>
                <li><span>Edit</span></li>
            </ul>
            <a class="uk-button uk-button-primary uk-button-small" href="{{route('roles.index')}}">
                Back to Index
            </a>
        </div>
        <hr>

        @component('swarovsky-core::user._form', [
            'roles' => $roles,
            'permissions' => $permissions,
            'user' => $user,
            'action' => route("users.update", ['user' => $user]),
            'method' => 'PUT'
        ])@endcomponent

    </div>
</div>
@endsection

