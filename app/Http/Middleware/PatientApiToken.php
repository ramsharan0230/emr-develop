<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\PatientCredential;

class PatientApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($request->header('Authorization')){
            if($this->verify($request)){
                return $next($request);
            }
        }        
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function verify($request)
    {
        return PatientCredential::select('id')->where('api_token',$request->header('Authorization'))->exists();
    }
}
