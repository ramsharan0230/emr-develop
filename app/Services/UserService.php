<?php

namespace App\Services;

use App\CogentUsers;
use App\UserShare;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public static function getDoctors(array $select): Collection
    {
        $doctors = CogentUsers::orWhere([
            'fldopconsult' => 1,
            'fldipconsult' => 1
        ])->select($select)->get();

        return $doctors;
    }

    public static function getShareForService($user_id, $item_type, $item_name, $category): float
    {
        $result = UserShare::where([
            ['flduserid', $user_id],
            ['flditemtype', $item_type],
            ['category', $category]
        ])
        ->where(function($query) use ($item_name) {
            $query->orWhere('flditemname', $item_name)
            ->orWhere('flditemname', 'all');
        })->orderBy('flditemname', 'ASC')
        ->first();

        if ($result) {
            return $result->flditemshare;
        }
        return 0;
    }

    public static function getShareTaxForService($user_id, $item_type, $item_name, $category): float
    {
        $result = UserShare::where([
            ['flduserid', $user_id],
            ['flditemtype', $item_type],
            ['category', $category]
        ])
        ->where(function($query) use ($item_name) {
            $query->orWhere('flditemname', $item_name)
            ->orWhere('flditemname', 'all');
        })
        ->first();

        if ($result) {
            return $result->flditemtax;
        }
        return 0;
    }
}
