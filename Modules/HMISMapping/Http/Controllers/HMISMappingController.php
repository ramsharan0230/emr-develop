<?php

namespace Modules\HMISMapping\Http\Controllers;

use App\Delivery;
use App\Department;
use App\Discount;
use App\Hmismapping;
use App\Mapping;
use App\Sampletype;
use App\ServiceCost;
use App\tbldelivery;
use App\TblDepartment;
use App\Tbldiscount;
use App\tblsampletype;
use App\Tblservicecost;
use App\Tbltest;
use App\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class HMISMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        return view('hmismapping::index');
    }

    /** Emergency saving and fetching */
    public function emergencyOptions()
    {
        try {
            $options = Department::where('fldcateg','like','Emergency')
                ->orWhere('fldcateg', 'like', 'OPD Visit')
                ->orWhere('fldcateg', 'like', 'emergency')
                ->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            dd($exception);
            return \response()->json(['error','something went wrong']);
        }
    }

    /** INPatient fetching */
    public function inPatientOptions()
    {
        try {
            $options = Department::where('fldcateg','like','Patient Ward')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** DIagnostic fetching */
    public function diagnosticService()
    {
        try {

            $options = ServiceCost::where([
                ['flditemtype','=','Radio Diagnostics'],
                ['fldgroup','like','%'],
            ])->orWhere('fldgroup','=','%')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** DIagnostic fetching */
    public function delivery()
    {
        try {
            $options = Delivery::select('flditem')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** Laboratory fetching */
    public function laboratory(Request $request)
    {

        try {
            if(!$request->sub_category)
            {
                return  false;
            }

            if($request->sub_category =='HAEMATOLOGY')
            {

                $options = Test::select('fldtestid')->where('fldcategory','=','HAEMATOLOGY')->orWhere('fldcategory','=','Hematology')
                    ->get();
                return \response()->json($options);
            }

            if($request->sub_category =='BACTERIOLOGY')
            {

                $options = Test::select('fldtestid')->where('fldcategory','=','BACTERIOLOGY')->orWhere('fldcategory','=','Microbiology')
                    ->get();
                return \response()->json($options);
            }


            $options = Test::select('fldtestid')
                ->where('fldcategory','=',$request->sub_category)
                ->orWhere('fldcategory','=',strtolower($request->sub_category))
                ->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** Culture fetching */
    public function culture()

    {
        try {
            $options = Test::select('fldtestid')
                ->where('fldcategory','=','Microbiology')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }


    /** Culture fetching */
    public function cultureSpecimen()

    {
        try {
            $options = Sampletype::select('fldsampletype')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** Culture fetching */
    public function freeService()

    {
        try {
            $options = Discount::select('fldtype')->get();
            return \response()->json($options);
        }
        catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    /** Fucntion for saving mappings */
    public function save_mappings(Request $request)
    {
        try {
            if($request->category ==  null)
            {
                return \response()->json(['error','something went wrong']);
            }

            if($request->service_name !=null)
            {

                $existed_data =[];
                $is_inserted =false;
                foreach ($request->service_name as $k => $v)
                {
                    $data = [
                        'category' => $request->category ?? null,
                        'sub_category'=> (is_array($request->sub_category) ? $request->sub_category[$k] : $request->sub_category ) ?? null,
                        'service_name'=> $v ?? null,
                        'service_value' => $request->service_value[$k] ?? null,
                    ];

                    if (Hmismapping::where('category', '=', $request->category )
                        ->where('sub_category','=',(is_array($request->sub_category) ? $request->sub_category[$k] : $request->sub_category ))
                        ->where('service_name','=', $v)
                        ->where('service_value','=',$request->service_value[$k])
                        ->exists()) {
                        $existed_data[] = [
                            'category' =>$request->category,
                            'sub_category'=> $request->sub_category,
                            'service_name'=> $v,
                            'service_value'=> $request->service_value[$k]
                        ];
                    }else{
                        $is_inserted =true;
                        Hmismapping::updateOrCreate($data);
                    }
                }

                return  \response(compact('existed_data','is_inserted'));
            }

        }catch (\Exception $exception)
        {
            return \response()->json(['error','something went wrong']);
        }
    }

    public function saveTest(Request $request)
    {
        if($request->service_value !=null)
        {
            $subcategory = $request->get('sub_category');
            if($subcategory=='HAEMATOLOGY')
            {
                $subcategory ='HEMATOLOGY';
            }


            $existed_data =[];
            $is_inserted =false;

            try {

                foreach ($request->service_value as $k => $v)
                {

                    $data = [
                        'category' => $request->category ?? null,
                        'sub_category'=> (is_array($request->sub_category) ? $request->sub_category[$k] : $request->sub_category ) ?? null,
                        'service_name'=> $v ?? null,
                        'service_value' => $request->service_value[$k] ?? null,
                    ];


                    if (Hmismapping::where('category', '=', $request->category )
                        ->where('sub_category','=',(is_array($request->sub_category) ? $request->sub_category[$k] : $request->sub_category ))
                        ->where('service_name','=', $v)
                        ->where('service_value','=',$request->service_value[$k])
                        ->exists()) {
                        $existed_data[] = [
                            'category' =>$request->category,
                            'sub_category'=> $request->sub_category,
                            'service_name'=> $v,
                            'service_value'=> $request->service_value[$k]
                        ];
                    }else{
                        Hmismapping::create($data);
                        $is_inserted=true;
                    }
                }
                return  \response(compact('existed_data','is_inserted'));

            }catch (\Exception $exception){
                dd($exception);
                return \response()->json(['error','something went wrong']);
            }
        }

    }


    public function mappingReport()
    {
        $data['mappings'] = Hmismapping::paginate(50);
        return view('hmismapping::mapping_report', $data);
    }
    // for deleting mapped data
    public function delete($id)
    {
        if($id)
        {
            try {

                Hmismapping::where('id',$id)->delete();
                return redirect()->back()->with('success_message','Deleted successfully');

            }catch (\Exception $e)
            {
                dd($e);
                return redirect()->back()->with('error_message','Something went wrong');
            }

        }
        return redirect()->back()->with('error_message','Something went wrong');
    }
}
