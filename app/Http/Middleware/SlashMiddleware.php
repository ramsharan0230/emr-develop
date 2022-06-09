<?php

namespace App\Http\Middleware;

use Closure;

class SlashMiddleware
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
            $params = array_keys($request->all());
            if ($params && count($params)) {
                foreach ($params as $param) {
                    $request[$param] = str_replace('/', '-', $request[$param]);
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $next($request);
    }
}
