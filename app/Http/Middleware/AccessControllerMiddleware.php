<?php

namespace App\Http\Middleware;

use Closure;

class AccessControllerMiddleware
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
        try {
            if ($this->isSuperAdmin() || $this->isAccessPermission()) {
                return $next($request);
            }
            abort(401, "
            401 - Unauthorized : Access denied due to invalid credentials.
            You do not have permission to view this directory or page using this credentials that you supplied.
            ", []);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    public function  isSuperAdmin()
    {
        if (session('auth_guard_name')) {
            $user = session('auth_user');
            return count($user->user_is_superadmin)>0 ? true : false;
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
