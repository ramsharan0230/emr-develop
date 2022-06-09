<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
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
        if (Auth::guard($guard)->check()) {

            if ('web_admin' === $guard) {
                return redirect('/admin');
            }
            if ('admin_frontend' === $guard) {
                return redirect('/admin/dashboard');
            }
            if ('patient_admin' === $guard) {
                return redirect('/patient/dashboard');
            }

            return redirect('/home');
        }

        return $next($request);
    }

}
