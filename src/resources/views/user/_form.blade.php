<form action="{{$action}}" method="POST">
    @csrf
    @method($method)

    <div class="uk-margin">
        <label class="uk-form-label" for="name">Name</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="name" name="name" placeholder="Enter name" value="{{$user->name}}">
        </div>
    </div>


    <div class="uk-margin">
        <label for="permissions">Permissions</label>
        <div class="uk-form-controls">
            <select class="uk-select js-example-basic-multiple" id="permissions" name="permissions[]"
                    multiple="multiple">
                <option>Select a permission</option>
                @foreach($permissions as $permission)
                    <option
                        value="{{$permission->id}}" {{\App\Helpers\SelectHelper::selectedUserPermission($user, $permission)}}>
                        {{$permission->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="roles">Roles</label>
        <div class="uk-form-controls">
            <select class="uk-select js-example-basic-multiple" id="roles" name="roles[]" multiple="multiple">
                <option>Select a role</option>
                @foreach($roles as $role)
                    <option
                        value="{{$role->id}}" {{\App\Helpers\SelectHelper::selectedRole($user, $role)}}>
                        {{$role->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="uk-text-right">
        <button type="submit" class="uk-button uk-button-primary">Submit</button>
    </div>

</form>




@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" defer></script>
    <script type="text/javascript" defer>
        document.addEventListener("DOMContentLoaded", () => {
            $('#roles').select2();
            $('#permissions').select2();
        });
    </script>
@endpush
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
@endpush
