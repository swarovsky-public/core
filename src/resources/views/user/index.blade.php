@extends(config('swarovsky-core.layout.admin'))

@section('content')

    <div class="uk-section-small">
        <div class="uk-container uk-container-large">
            <div class="uk-card uk-card-default uk-card-body uk-margin-remove uk-shadow-remove">
                <div class="uk-flex uk-flex-between uk-flex-middle">
                    <ul class="uk-breadcrumb">
                        <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                        <li><span>Users</span></li>
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
                        <th>Email</th>
                        <th>Verified</th>
                        <th>2 factor</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Phone</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @each('swarovsky-core::user.row', $users, 'user')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

