<?php

namespace Modules\Nutrition\Http\Controllers;

use App\FoodList;
use App\FoodType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class NutritionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('nutrition::index');
    }

    public function addFoodName(Request $request) {
        $response = array();
        try {
            $foodname = $request->foodname;

            $data=[];
            $data['fldfood'] = $foodname;

            $checkdublicate = FoodList::where('fldfood',  $foodname)->get();
            if(count($checkdublicate) > 0) {
                $response['message'] = 'Foodname exist already.';
            } else {
                FoodList::Insert($data);

                $latestfood = FoodList::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Foodname added successfully';
                $response['fldid'] = $latestfood->fldid;
                $response['fldfood'] = $latestfood->fldfood;
            }

        } catch(\Exception $e) {
//            dd($e->getMessage());
            $response['message'] = $e->getMessage();
        }

        return  json_encode($response);
    }

    public function deleteFoodName($fldid) {
        $response = array();
        try {

            $foodname = FoodList::find($fldid);

            if($foodname) {
                $foodname->delete();
            }

            $response['message'] = 'success';
            $response['successmessage'] = 'foodname deleted successfully.';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';
            $response['message'] = 'error';

        }

        return  json_encode($response);
    }

    public function addFoodType(Request $request) {

        $response = array();

        try {
            $foodtype = $request->foodtype;

            $data=[];
            $data['fldfoodtype'] = $foodtype;

            $checkdublicate = FoodType::where('fldfoodtype',  $foodtype)->get();
            if(count($checkdublicate) > 0) {
                $response['message'] = 'foodtype exist already.';
            } else {
                FoodType::Insert($data);

                $latestfoodtye = FoodType::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'category added successfully';
                $response['fldid'] = $latestfoodtye->fldid;
                $response['fldfoodtype'] = $latestfoodtye->fldfoodtype;
            }



        } catch(\Exception $e) {

            $response['message'] = 'Something went wrong while inserting category';
        }

        return json_encode($response);
    }

    public function deleteFoodType($fldid) {
        $response = array();
        try {

            $foodtype = FoodType::find($fldid);

            if($foodtype) {
                $foodtype->delete();
            }

            $response['successmessage'] = 'category deleted successfully.';
            $response['message'] = 'success';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';
            $response['message'] = 'error';
        }

        return  json_encode($response);
    }

}
