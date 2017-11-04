<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth as Base;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Closure;
use Auth;

class AuthenticateWithBasicAuth extends Base
{
    /**
     * Create a new middleware instance.
     *
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
        parent::__construct($auth);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        return $this->auth->guard($guard)->basic('name') ?: $next($request);
    }
}
