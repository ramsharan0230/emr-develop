<?php

namespace Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters ;

use Closure;

class NameFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('module_name')) {
            return $next($request);
        }
        return $next($request)->where('id',  request()->module_name );

    }
}