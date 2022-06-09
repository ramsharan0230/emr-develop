<?php

namespace Modules\Nutrition\Http\Controllers;

use App\FoodContent;
use App\FoodList;
use App\JobRecord;
use App\Utils\Helpers;
use App\Utils\Nutritionhelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class NutritionalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
        {
            $jobrecorddata = [];
            $jobrecordata['fldindex'] = '2020420175312:1:44049393';
            $jobrecordata['fldfrmname'] = 'fmDietContent';
            $jobrecordata['fldfrmlabel'] = 'Dietary Info';
            $jobrecordata['flduser'] = Helpers::getCurrentUserName();
            $jobrecordata['fldcomp'] = Helpers::getCompName();
            $jobrecordata['fldentrytime'] = '2020-04-20 18:05:53.305';
            $jobrecordata['fldexittime'] = NULL;
            $jobrecordata['fldpresent'] = '1';
            $jobrecordata['fldhostuser'] = 'sihrantech';
            $jobrecordata['fldhostip'] = NULL;
            $jobrecordata['fldhostname'] = 'hari';
            $jobrecordata['fldhostmac'] = '50:5b:c2:ee:97:25';

            JobRecord::firstOrCreate(
                        ['fldindex' => '2020420175312:1:44049393'],
                        $jobrecordata
            );

            $data = [];

    //        $foodlists = DB::table('tblfoodlist')->distinct()->orderBy('fldfood', 'ASC')->get()->toArray();
//            $foodlists = FoodList::with('FoodContent')->distinct()->orderBy('fldfood', 'ASC')->get();
            $foodlists = Nutritionhelpers::getFoodlists();
//            $foodtypes = DB::table('tblfoodtype')->distinct()->orderBy('fldfoodtype', 'ASC')->get();
            $foodtypes = Nutritionhelpers::getFoodtype();
            $data['foodlists'] = $foodlists;
            $data['foodtypes'] = $foodtypes;

            return view('nutrition::dietary-info', $data);
        }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function addFoodContent(Request $request)
    {
        $request->validate([
            "fldfood" => "required",
            "fldfoodtype" => "required",
            "fldfoodcode" => "required"
        ],
        [
            'fldfood.required' => 'Food Name is required',
            'fldfoodtype.required' => 'Category Field is required',
            'fldfoodcode.required' => 'Status is required'
        ]);

        try {
            $foodcontent = $request->all();

            unset($foodcontent['_token']);
            unset($foodcontent['food_name']);
            unset($foodcontent['foodname_bottom']);
            unset($foodcontent['category_name']);
            unset($foodcontent['foodcategory_bottom']);

            $foodcontent['fldfoodid'] = $request->fldfood.'('.strtoupper($request->fldformat).')'.'('.$request->fldsource.')';

            FoodContent::insert($foodcontent);
            Session::flash('success_message', 'Nutrition added sucessfully');

            return redirect()->route('nutritionalinfo');

        } catch (\Exception $e) {
//            dd($e->getMessage());
            Session::flash('error_message', 'Sorry something went wrong while adding the nutrition.');

            return redirect()->route('nutritionalinfo');
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function editFoodContent($fldfoodid)
    {
        $fldfoodid = decrypt($fldfoodid);
        $foodcontent = FoodContent::where('fldfoodid', $fldfoodid)->first();

        $data = [];
        $foodlists = FoodList::with('FoodContent')->distinct()->orderBy('fldfood', 'ASC')->get();

        $foodtypes = DB::table('tblfoodtype')->distinct()->orderBy('fldfoodtype', 'ASC')->get();
        $data['foodcontent'] = $foodcontent;

        $data['foodlists'] = $foodlists;
        $data['foodtypes'] = $foodtypes;
        return view('nutrition::editfoodcontent', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateFoodContent(Request $request, $fldfoodid)
    {
        $fldfoodid = decrypt($fldfoodid);
        $request->validate([
            "fldfoodtype" => "required",
            "fldfoodcode" => "required",
        ]);



        try {
            $foodcontent = FoodContent::where('fldfoodid', $fldfoodid)->first();

            $requestfoodcontent = $request->all();
            unset($requestfoodcontent['_token']);
            unset($requestfoodcontent['_method']);
            unset($requestfoodcontent['food_name']);
            unset($requestfoodcontent['foodname_bottom']);
            unset($requestfoodcontent['category_name']);
            unset($requestfoodcontent['foodcategory_bottom']);

            FoodContent::where('fldfoodid', $fldfoodid)->update($requestfoodcontent,['timestamps' => false]);
            Session::flash('success_message', $foodcontent->fldfoodid.' Nutrition updated sucessfully');

            return redirect()->route('nutritionalinfo');

        } catch (\Excetion $e) {
            Session::flash('error_message', $e->getMessage());

            return redirect()->route('nutritionalinfo');
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function deleteFoodContent($fldfoodid)
    {
        try {
            $fldfoodid = decrypt($fldfoodid);
            $foodcontent = FoodContent::where('fldfoodid', $fldfoodid)->first();

            if($foodcontent) {
                DB::table('tblfoodcontent')->where('fldfoodid', $fldfoodid)->delete();
                Session::flash('success_message', $foodcontent->fldfoodcontnet.' Nutrition deleted sucessfully');
            }
        } catch(\Exception $e) {
            Session::flash('error_message', $e->getMessage());
        }

        return redirect()->route('nutritionalinfo');

    }


}
