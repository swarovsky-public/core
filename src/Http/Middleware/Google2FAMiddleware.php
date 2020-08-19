<?php

namespace Swarovsky\Core\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Swarovsky\Core\Support\Google2FAAuthenticator;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
