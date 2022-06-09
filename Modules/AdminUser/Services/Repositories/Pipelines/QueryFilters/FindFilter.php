<?php

namespace Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters ;

use Closure;

class FindFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('id')) {
            return $next($request);
        }
        return $next($request)->where('id',  request()->id );

    }
}
