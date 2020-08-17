<?php

namespace Swarovsky\Core\Helpers;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;


class SelectHelper
{



    public static function selectedRole(User $user, Role $role): string
    {
        $found = $user->roles->where('id', '=', $role->id)->first();
        return ($found !== null) ? 'selected' : '';
    }

    public static function selectedPermission(Role $role, Permission $permission): string
    {
        $found = $role->permissions->where('id', '=', $permission->id)->first();
        return ($found !== null) ? 'selected' : '';
    }


    public static function selectedUserPermission(User $user, Permission $permission): string
    {
        $found = $user->permissions->where('id', '=', $permission->id)->first();
        return ($found !== null) ? 'selected' : '';
    }

}
