<?php

namespace Swarovsky\Core\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Swarovsky\Core\Helpers\CacheHelper;
use Swarovsky\Core\Helpers\SessionHelper;
use Swarovsky\Core\Models\Permission;
use Swarovsky\Core\Models\Role;
use App\Models\User;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('advanced_permission:edit users', ['except' => ['profile', 'update_profile']]);
    }


    public function index(): Renderable
    {
        $users = User::with(['roles', 'passwordSecurity'])->get();
        return view('swarovsky-core::user.index', ['users' => $users]);
    }

    public function profile()
    {
        return view('swarovsky-core::user.profile', ['user' => Auth()->user()]);
    }

    public function update_profile(\Illuminate\Http\Request $request)
    {
        $user = Auth()->user();
        if ($request->has('avatar')) {
            $this->validate($request, [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $finalImg = Image::make($request->file('avatar'))->widen(800, static function ($constraint) {
                $constraint->upsize();
            });
            $finalImg->resize(250, 250);
            $finalImg->stream();

            Storage::disk('s3')->put('users/' . $user->id . '.jpg', $finalImg->__toString());
        }
        SessionHelper::add_message('User successfully updated!', 'success');
        return redirect()->back();
    }


    public function edit(User $user): Renderable
    {
        $roles = CacheHelper::get(Role::class, ['order' => 'name', 'with' => ['permissions']]);
        $permissions = CacheHelper::get(Permission::class, ['order' => 'name']);
        return view('swarovsky-core::user.edit', ['user' => $user, 'roles' => $roles, 'permissions' => $permissions]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if($request::has('roles')){
            if($user->syncRoles($request::input('roles'))){
                SessionHelper::add_message('User roles have been updated', 'success');
            } else {
                SessionHelper::add_message('Error saving user roles', 'danger');
            }
        }
        if($request::has('permissions')){
            if($user->syncPermissions($request::input('permissions'))){
                SessionHelper::add_message('User permissions have been updated', 'success');
            } else {
                SessionHelper::add_message('Error saving user permissions', 'danger');
            }
        }

        return redirect()->back();
    }

}
