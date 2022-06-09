<?php

namespace Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters ;

use Closure;

class Eagerload
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('with')) {
            return $next($request);
        }
        return $next($request)->with(request()->with);

    }
}
