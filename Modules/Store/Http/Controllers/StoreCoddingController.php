<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class StoreCoddingController extends Controller
{
    public function index(Request $request)
    {
        /*
            select fldstockno,fldstockid,fldbatch,fldcode from tblentry where fldcomp='comp07' and fldcategory='Medicines'
        */
        $medicines = $this->getMedicines($request);
        return view('store::storeCoding', compact('medicines'));
    }

    public function getMedicines(Request $request)
    {
        $fldcategory = $request->get('fldcategory', 'Medicines');
        $data = \App\Entry::select('fldstockno', 'fldstockid', 'fldbatch', 'fldcode')
            ->where([
                'fldcomp' => Helpers::getCompName(),
                'fldcategory' => $fldcategory,
            ])->get();
        if($request->ajax())
            return response()->json($data);

        return $data;
    }

    public function update(Request $request)
    {
        try{
            \App\Entry::where([
                'fldstockno' => $request->get('fldstockno')
            ])->update([
                'fldcode' => $request->get('fldcode'),
            ]);

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }
}
