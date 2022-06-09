<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PatientAuth
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
        if ( !Auth::guard('patient_admin')->check() ) {
            return redirect()->route('cogent.login.form');
        }

        return $next($request);
    }
}
