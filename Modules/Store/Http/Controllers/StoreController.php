<?php

namespace Modules\Store\Http\Controllers;


use App\Entry;
use App\Target;
use App\Nepalicalendar;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class StoreController extends Controller
{
    public function getStore()
    {
        return view('store::store');
    }


    // insert target variable
    public function insertTargetVariable(Request $request)
    {
        try{
            $checkifexist = Target::where([
                'flditem' => $request->flditem
            ])->first();
            if($checkifexist != null){
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Variable Already Exists.',
                ]);
            }
            $data = [
                'flditem' => $request->flditem,
            ];
            $insert = Target::insert($data);
            if($insert){
                return response()->json([
                    'status'=> TRUE,
                    'message' => 'Successfully Added Variable.',
                ]);
            }else{
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Failed to Add Variable.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    // delete target variable
    public function deleteTargetVariable(Request $request)
    {
        try{
            $checkifexist = Target::where([
                'fldid' => $request->fldid
            ])->first();
            if($checkifexist == null){
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }
            $delete = Target::where('fldid', $request->fldid)->delete();
            if($delete){
                return response()->json([
                    'status'=> TRUE,
                    'message' => 'Successfully Deleted Variable.',
                ]);
            }else{
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Failed to Delete Variable.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getTargetVariable()
    {
        $get_target_variables = Target::select('fldid', 'flditem as col')->get();
        return response()->json($get_target_variables);
    }

    public function getMed()
    {
        $fldsurgcateg = Input::get('cat');
        $get_related_data = Entry::select('fldstockid')->orderBy('fldstockid', 'ASC')->get();
        return response()->json($get_related_data);
    }



    public function englishtonepalidate(Request $request) {
        $response = array();
        $engdate = $request->engdate;
        $cal = new Nepalicalendar();
        list($y,$m,$d) = explode("-", $engdate);


        $date = $cal->eng_to_nep($y,$m,$d);

        $nepalidate = $date['year'].'-'.$date['month'].'-'.$date['date'];
        $response['nepalidate'] = $nepalidate;
        return json_encode($response);
    }

    public function nepalitoenglishdate(Request $request){
        $response = array();
        $value = $request->nepdate;

        $cal = new Nepalicalendar();
        list($y,$m,$d) = explode("-", $value);
        $date = $cal->nep_to_eng($y,$m,$d);

        $englishdate = $date['year'].'-'.$date['month'].'-'.$date['date'];
        $englishdate = Carbon::parse($englishdate)->format('Y-m-d');

        $response['englishdate'] = $englishdate;
        echo json_encode($response);

    }
}
