<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class FixedAssetController extends Controller
{
    public function index()
    {
        $data = [
            'items' => Helpers::getAssetNames(),
            'suppliers' => Helpers::getSuppliers(),
        ];
        return view('purchase::fixed-asset', $data);
    }

    public function getItems()
    {
        return response()->json(Helpers::getAssetNames());
    }

    public function addItem(Request $request)
    {
        try{
            $data = [
                'flditemname' => $request->get('flditemname'),
                'fldledger' => $request->get('fldledger'),
                'fldgroup' => $request->get('fldgroup'),
            ];
            $data['fldid'] = \App\AssetsName::insertGetId($data);

            return response()->json([
                'data' => $data,
                'status'=> TRUE,
                'message' => 'Successfully inserted information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to insert information.',
            ]);
        }
    }

    public function deleteItem(Request $request)
    {
        try{
            \App\AssetsName::where('fldid', $request->get('fldid'))->delete();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully deleted information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to delete information.',
            ]);
        }
    }

    public function getAssetsEntry(Request $request)
    {
        if ($request->get('fldid')) {
            $data = \App\AssetsEntry::where('fldid', $request->get('fldid'))->first();
        } else {
            $data = \App\AssetsEntry::select('fldid', 'flditemname', 'fldmanufacturer', 'fldledger', 'fldmodel', 'fldserial', 'fldqty', 'fldditemamt', 'fldcomp');
            if ($request->get('flditemname'))
                $data = $data->where('flditemname', 'like', '%' . $request->get('flditemname') . '%');

            $data = $data->get();
        }

        return response()->json($data);
    }

    public function saveAssetsEntry(Request $request)
    {
        try{
            // INSERT INTO `tblassetsentry` ( `flditemname`, `fldgroup`, `fldspecs`, `fldcode`, `fldmanufacturer`, `fldmodel`, `fldserial`, `fldledger`, `fldsuppname`, `fldpurdate`, `fldqty`, `fldunit`, `flditemrate`, `fldtaxamt`, `flddiscamt`, `fldditemamt`, `fldcomp`, `fldcondition`, `fldcomment`, `fldrepairdate`, `flduser`, `xyz` ) VALUES ( 'asd', 's df sfss', 'specification', 'code', 'manufact', 'model', 'setial', 'asds', 'SHARAN MEDICINE DISTRIBUTORS', '2020-06-02 00:00:00', 10, NULL, 11, 0, 0, 110, 'location', 'condition', 'remaarks', '2020-06-03 00:00:00', 'admin', '0' )
            $data = [
                'flditemname' => $request->get('flditemname'),
                'fldgroup' => $request->get('fldgroup'),
                'fldspecs' => $request->get('fldspecs'),
                'fldcode' => $request->get('fldcode'),
                'fldmanufacturer' => $request->get('fldmanufacturer'),
                'fldmodel' => $request->get('fldmodel'),
                'fldserial' => $request->get('fldserial'),
                'fldledger' => $request->get('fldledger'),
                'fldsuppname' => $request->get('fldsuppname'),
                'fldpurdate' => $request->get('fldpurdate'),
                'fldqty' => $request->get('fldqty'),
                'fldunit' => $request->get('fldqty') ?: NULL,
                'flditemrate' => $request->get('flditemrate'),
                'fldtaxamt' => $request->get('fldtaxamt'),
                'flddiscamt' => $request->get('flddiscamt'),
                'fldditemamt' => $request->get('fldditemamt'),
                'fldcomp' => $request->get('fldcomp'),
                'fldcondition' => $request->get('fldcondition'),
                'fldcomment' => $request->get('fldcomment'),
                'fldrepairdate' => $request->get('fldrepairdate'),
                'flduser' => Helpers::getCompName(),
                'xyz' => '0',
            ];
            $data['fldid'] = \App\AssetsEntry::insertGetId($data);

            return response()->json([
                'data' => $data,
                'status'=> TRUE,
                'message' => 'Successfully inserted information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to insert information.',
            ]);
        }
    }

    public function updateAssetsEntry(Request $request)
    {
        try{
            $data = [
                'flditemname' => $request->get('flditemname'),
                'fldgroup' => $request->get('fldgroup'),
                'fldspecs' => $request->get('fldspecs'),
                'fldcode' => $request->get('fldcode'),
                'fldmanufacturer' => $request->get('fldmanufacturer'),
                'fldmodel' => $request->get('fldmodel'),
                'fldserial' => $request->get('fldserial'),
                'fldledger' => $request->get('fldledger'),
                'fldsuppname' => $request->get('fldsuppname'),
                'fldpurdate' => $request->get('fldpurdate'),
                'fldqty' => $request->get('fldqty'),
                'fldunit' => $request->get('fldqty') ?: NULL,
                'flditemrate' => $request->get('flditemrate'),
                'fldtaxamt' => $request->get('fldtaxamt'),
                'flddiscamt' => $request->get('flddiscamt'),
                'fldditemamt' => $request->get('fldditemamt'),
                'fldcomp' => $request->get('fldcomp'),
                'fldcondition' => $request->get('fldcondition'),
                'fldcomment' => $request->get('fldcomment'),
                'fldrepairdate' => $request->get('fldrepairdate'),
            ];
            $fldid = $request->get('fldid');
            \App\AssetsEntry::where('fldid', $fldid)->update($data);
            $data['fldid'] = $fldid;

            return response()->json([
                'data' => $data,
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Surgical Brand']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }

    }

}


