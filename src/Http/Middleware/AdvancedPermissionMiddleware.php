<?php

namespace Swarovsky\Core\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AdvancedPermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $pm) {
            if (app('auth')->user()->isAllowed($pm)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
