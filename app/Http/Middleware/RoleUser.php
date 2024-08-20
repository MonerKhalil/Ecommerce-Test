<?php

namespace App\Http\Middleware;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class RoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {
        $user = user();
        if(!in_array($user->role,explode('|', $role))){
            throw new AuthorizationException();
        }
        return $next($request);
    }
}
