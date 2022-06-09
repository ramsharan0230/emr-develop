<?php

namespace Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters ;

use Closure;

class FindOrFailPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('id')) {
            return $next($request);
        }
        return $next($request)->findOrFail( request()->id );

    }
}
