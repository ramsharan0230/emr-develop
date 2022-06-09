<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class CheckAuth
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
        if (Auth::guard('admin_frontend')->check()) {
            try {
                return $next($request);
                // if ($this->isSuperAdmin() || $this->isAccessPermission() || \Request::route()->getName() == "admin.dashboard") {
                //     return $next($request);
                // } else {
                //     return redirect(route("admin.dashboard"));
                // }
                // return response()->view('error500', ['errorcode' => 401], 401);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
        return redirect()->route('cogent.login.form');
    }

    public function  isSuperAdmin()
    {
        if (session('auth_guard_name')) {
            $user = session('auth_user');
            return count($user->user_is_superadmin) > 0 ? true : false;
        }
    }
    public function isAccessPermission()
    {
        $authorized_routes = session('authorized_routes');
        if ($authorized_routes) {
            $route_name = \Request::route()->getName();
            if ($route_name && $route_name != null) {
                return in_array($route_name, $authorized_routes);
            } else {
                $path = \Request::path();
                return in_array("/$path", $authorized_routes);
            }
        }
        return false;
    }
}
