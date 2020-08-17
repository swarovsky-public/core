<tr>
    <td class="align-middle">
        {{$user->id}}
    </td>
    <td class="align-middle">
        {{$user->name}}
    </td>
    <td class="align-middle">
        {{$user->email}}
    </td>
    <td class="align-middle">
        {{$user->email_verified_at ? 'Y' : 'N'}}
    </td>
    <td class="align-middle">
        {{$user->passwordSecurity === null ? 'N' : 'Y'}}
    </td>
    <td class="align-middle">
        @if($user->roles)
            @foreach($user->roles as $role)
                <div><code>{{$role->name}}</code></div>
            @endforeach
        @else
            Has no role
        @endif
    </td>
    <td class="align-middle">
        @if($user->permissions)
            @foreach($user->permissions as $permission)
                <div><code>{{$permission->name}}</code></div>
            @endforeach
        @else
            Has no permissions
        @endif
    </td>
    <td class="align-middle">
        {{$user->phone}}
    </td>
    <td class="align-middle">
        {{$user->created_at->diffForHumans()}}
    </td>
    <td class="align-middle">
        <div class="uk-button-group">
            @if(auth()->user()->isAllowed('Edit users'))
                <a href="{{route('users.edit', ['user' => $user])}}"
                   class="uk-button uk-button-small uk-button-default"
                   title="Edit user">
                    Edit
                </a>
                <button type="button" class="uk-button uk-button-danger uk-button-small" title="Ban"
                    onclick="return confirm('Are you sure?')">
                    Ban
                </button>

                <form method="POST" action="{{route('users.destroy', $user)}}" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="uk-button uk-button-danger uk-button-small" title="Delete"
                            onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </td>

</tr>
