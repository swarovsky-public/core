<?php

namespace App\Http\Controllers;

use App\Helpers\Cache\CacheHelper;
use App\Helpers\ErrorHelper;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('advanced_permission:edit users', ['except' => ['profile', 'update_profile']]);
    }


    public function index(): Renderable
    {
        //$users = CacheHelper::get(User::class, ['with' => ['roles']]);
        $users = User::with(['roles', 'passwordSecurity'])->get();
        return view('user.index', ['users' => $users]);
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth()->user()]);
    }

    public function update_profile(\Illuminate\Http\Request $request)
    {
        $user = Auth()->user();
        /*
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
        */
        ErrorHelper::add_error('User successfully updated!', 'success');
        return redirect()->back();
    }


    public function edit(User $user): Renderable
    {
        $roles = CacheHelper::get(Role::class, ['order' => 'name', 'with' => ['permissions']]);
        $permissions = CacheHelper::get(Permission::class, ['order' => 'name']);
        return view('user.edit', ['user' => $user, 'roles' => $roles, 'permissions' => $permissions]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if($request::has('roles')){
            if($user->syncRoles($request::input('roles'))){
                ErrorHelper::add_error('User roles have been updated', 'success');
            } else {
                ErrorHelper::add_error('Error saving user roles', 'danger');
            }
        }
        if($request::has('permissions')){
            if($user->syncPermissions($request::input('permissions'))){
                ErrorHelper::add_error('User permissions have been updated', 'success');
            } else {
                ErrorHelper::add_error('Error saving user permissions', 'danger');
            }
        }

        return redirect()->back();
    }

}
