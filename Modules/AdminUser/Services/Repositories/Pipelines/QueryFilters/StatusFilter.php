<?php

namespace Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters ;

use Closure;

class StatusFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($request);
        }
        $stuatus = (request()->status == 'active')  ? true : false ;
        return $next($request)->where('status', $stuatus );
    }
}