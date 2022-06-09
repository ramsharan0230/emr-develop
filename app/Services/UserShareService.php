<?php

namespace App\Services;

use App\UserShare;

class UserShareService
{
    public static function getUserShareByItemNameType($itemName, $itemType): int
    {
        $share = UserShare::where([
            ['flditemname', $itemName],
            ['flditemtype', $itemType]
        ])->first();

        return $share? $share->flditemshare: 100;
    }
}
