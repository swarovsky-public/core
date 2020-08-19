<?php

namespace Swarovsky\Core\Http\Middleware;

use App\Models\User; // TODO
use \Illuminate\Auth\Middleware\RequirePassword;


class ConfirmPassword extends RequirePassword
{
    protected function shouldConfirmPassword($request): bool
    {
        if (
            env('APP_ENV') === 'local' ||
            User::isGoogle(Auth()->user()->id) ||
            User::isFacebook(Auth()->user()->id)
        ) {
            return false;
        }
        $confirmedAt = time() - $request->session()->get('auth.password_confirmed_at', 0);

        return $confirmedAt > $this->passwordTimeout;
    }
}
