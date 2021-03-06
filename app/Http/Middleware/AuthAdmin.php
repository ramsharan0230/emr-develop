<?php

namespace App\Http\Middleware;

use Closure;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !Auth::guard('admin_frontend')->check() ) {

//            Auth::guard('admin')->logout();
            return redirect()->route('cogent.login.form');
        }

        return $next($request);
    }
}
