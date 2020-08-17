@extends('layouts.admin')

@section('content')

<div class="uk-section-small">
    <div class="uk-container uk-container-large">
        <div class="uk-flex uk-flex-between uk-flex-middle">
            <ul class="uk-breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                <li><span>Roles</span></li>
            </ul>
            @if(auth()->user()->isAllowed('Edit roles'))
                <a class="uk-button uk-button-primary uk-button-small" href="{{route('roles.create')}}">
                    Create new role
                </a>
            @endif
        </div>

        <hr>

        <table class="uk-table uk-table-striped uk-table-small">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guard</th>
                <th>Permissions</th>
                <th>Last update</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @each('role.row', $roles, 'role')
            </tbody>
        </table>

    </div>
</div>
@endsection

