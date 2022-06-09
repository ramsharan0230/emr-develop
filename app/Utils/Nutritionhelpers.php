<?php


namespace App\Utils;


use App\FoodList;
use App\FoodType;

class Nutritionhelpers
{

    public static function getFoodtype() {
        $foodtypes = FoodType::distinct()->orderBy('fldfoodtype', 'ASC')->get();
        // dd($foodtypes);
        return $foodtypes;
    }

    public static function getFoodlists($perpage = NULL) {

        if($perpage) {
            $foodlists = FoodList::with('FoodContent')->distinct()->orderBy('fldfood', 'ASC')->paginate(100);
        } else {
            $foodlists = FoodList::with('FoodContent')->distinct()->orderBy('fldfood', 'ASC')->get();
        }

        return $foodlists;
    }
}
