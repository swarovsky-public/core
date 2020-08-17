<tr>
    <td class="align-middle">
        {{$role->id}}
    </td>
    <td class="align-middle">
        {{$role->name}}
    </td>
    <td class="align-middle">
        {{$role->guard_name}}
    </td>
    <td class="align-middle">
        @foreach($role->permissions as $permission)
            <div><code>{{$permission->name}}</code></div>
        @endforeach
    </td>
    <td class="align-middle">
        {{$role->updated_at->diffForHumans()}}
    </td>
    <td class="align-middle" style="display: flex;justify-content: space-between;">
        @if(auth()->user()->isAllowed('Edit roles'))
            <div class="uk-button-group">
            <a href="{{route('roles.edit', ['role' => $role])}}" class="uk-button uk-button-default uk-button-small"
               type="Edit role">
                Edit
            </a>
            <form method="POST" action="{{route('roles.destroy', $role)}}" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="uk-button uk-button-danger uk-button-small" title="Delete"
                        onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
            </div>
        @endif
    </td>

</tr>
