<?php

namespace Modules\Nutrition\Http\Controllers;

use App\Nutrition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FoodRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('nutrition::foodrequirements');
    }

    public function addNutrition(Request $request) {
        try {
            $foodrequirementdata = $request->all();
            unset($foodrequirementdata['_token']);

            Nutrition::insert($foodrequirementdata);

            echo 'food requirement inserted successfully';
        } catch(\Exception $e) {
            $errormessage = $e->getMessage();
            $errormessage = "something went wrong while inserting data to the nutrition table";

            echo $errormessage;
        }

    }
}
