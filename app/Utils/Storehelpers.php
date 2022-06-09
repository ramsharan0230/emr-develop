<?php


namespace App\Utils;


use App\PurchaseBill;
use App\StockReturn;

class Storehelpers
{

    public static function stockreturnReprintOptions() {

        $stockreturn = StockReturn::where('fldsave', 1)->get();

        return $stockreturn;
    }

    public static function purchaseBillReturnOptions() {

        $purchasebill = PurchaseBill::all();

        return $purchasebill;
    }
}
