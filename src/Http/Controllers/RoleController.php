<?php

namespace Swarovsky\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Swarovsky\Core\Helpers\CacheHelper;
use Swarovsky\Core\Helpers\SessionHelper;
use Swarovsky\Core\Models\Permission;
use Swarovsky\Core\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('advanced_permission:edit roles');
    }

    public function index(): Renderable
    {
        $roles = CacheHelper::get(Role::class, ['order' => 'name', 'with' => ['permissions']]);
        return view('swarovsky-core::role.index', ['roles' => $roles]);
    }

    public function create(): Renderable
    {
        $role = new Role();
        $permissions = CacheHelper::get(Permission::class, ['order' => 'name']);
        return view('swarovsky-core::role.create', ['role' => $role,  'permissions' => $permissions]);
    }

    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $validator = [
            'name' => ['required', 'string', 'unique:roles'],
            'guard_name' => ['required', 'string', 'unique:roles'],
        ];
        return Validator::make($data, $validator);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validator($request::all())->getData();
        if ($role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name']
        ])) {
            if($request->has('permissions')){
                $role->syncPermissions($request::input('permissions'));
            }
            SessionHelper::add_message('Successfully created!', 'success');
        } else {
            SessionHelper::add_message('Error while saving!', 'danger');
        }

        CacheHelper::clear(Role::class);
        return redirect()->route('roles.create', ['role' => new Role()]);
    }

    public function edit(Role $role): Renderable
    {
        $permissions = CacheHelper::get(Permission::class, ['order' => 'name']);
        return view('swarovsky-core::role.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = [
            'name' => $request::input('name'),
            'guard_name' => $request::input('guard_name')
        ];
        if ($role->update($data) && $role->syncPermissions($request::input('permissions'))) {
            SessionHelper::add_message('Successfully updated!', 'success');
        } else {
            SessionHelper::add_message('Nothing to save!', 'danger');
        }
        CacheHelper::clear(Role::class);
        return redirect()->back();
    }


    public function destroy(Role $role): RedirectResponse
    {
        $name = $role->name;
        try {
            $role->delete();
            SessionHelper::add_message('Successfully deleted!', 'success');
        } catch (\Exception $e) {
            SessionHelper::add_message('Error while deleting!', 'danger');
        }
        return redirect()->route('roles.index');
    }

}
