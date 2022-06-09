<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (!function_exists('can')) {
    function can($perm, $next = true)
    {
        if (session('is_superadmin')) {
            return true;
        }
        if ($perm) {
            $permissions  = session('access_permissions');
            if ($permissions && count($permissions) > 0) {
                if (gettype($perm) == 'string') {
                    if (in_array($perm, $permissions)) {
                        return true;
                    }
                    return $next?false:response()->view('error500', ['errorcode' => 401], 401);
                } else if (gettype($perm) == 'array') {
                    foreach ($perm as $p) {
                        if ($permissions && in_array($p, $permissions)) {
                            return true;
                        }
                    }
                }
            }
            return $next?false:response()->view('error500', ['errorcode' => 401], 401);
        }
    }
}
