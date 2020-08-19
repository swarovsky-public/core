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
        <span uk-icon="{{$user->email_verified_at ? 'check' : 'close'}}"
              class="uk-text-{{$user->email_verified_at ? 'success' : 'danger'}}"></span>
    </td>
    <td class="align-middle">
        <span uk-icon="{{$user->passwordSecurity === null ? 'close' : 'check'}}"
              class="uk-text-{{$user->passwordSecurity === null ? 'danger' : 'success'}}"></span>
    </td>
    <td class="align-middle">
        @foreach($user->roles as $role)
            <div><code>{{$role->name}}</code></div>
        @endforeach
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

        @if(auth()->user()->isAllowed('Edit users'))
            <a href="{{route('users.edit', ['user' => $user])}}"
               class="uk-icon-button uk-button-primary" uk-icon="pencil"
               title="Edit user"></a>
            <button type="button"
                    class="uk-icon-button uk-button-default" uk-icon="lock"
                    onclick="return confirm('Are you sure?')"></button>

            <form method="POST" action="{{route('users.destroy', $user)}}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        title="Delete" class="uk-icon-button uk-button-danger" uk-icon="trash"
                        onclick="return confirm('Are you sure?')">
                </button>
            </form>
        @endif

    </td>

</tr>
