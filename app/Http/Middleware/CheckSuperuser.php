<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class CheckSuperuser
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
//        dd(Auth::guard('admin_frontend')->user()->user_is_superadmin);
        if (isset(Auth::guard('admin_frontend')->user()->user_is_superadmin) && count(Auth::guard('admin_frontend')->user()->user_is_superadmin)) {
            return $next($request);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('patient');
        }
    }
}
