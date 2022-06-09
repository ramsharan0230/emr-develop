<?php

namespace Modules\Store\Http\Controllers;

use App\Adjustment;
use App\CogentUsers;
use App\Entry;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

/**
 * Class StockConsumeController
 * @package Modules\Store\Http\Controllers
 */
class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $data['stockAdjusted'] = Adjustment::select(\DB::raw('GROUP_CONCAT(tbladjustment.fldid) as fldids'),'tbladjustment.fldstockno', 'tbladjustment.fldcategory', 'tbladjustment.fldstockid', 'tbladjustment.fldreference','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tbladjustment.fldcompqty) as fldcompqty'),\DB::raw('sum(tbladjustment.fldcurrqty) as fldcurrqty'),\DB::raw('sum(tbladjustment.fldnetcost) as fldnetcost'),\DB::raw('sum(tbladjustment.fldsellpr) as fldsellpr'),'tbladjustment.fldreason')
                                    ->leftJoin('tblentry','tblentry.fldstockno','=','tbladjustment.fldstockno')
                                    ->where('tbladjustment.fldcomp', Helpers::getCompName())
                                    ->where('tbladjustment.fldsav',0)
                                    ->with('stock')
                                    ->groupBy(['tbladjustment.fldstockid','tblentry.fldbatch'])
                                    ->get();
        return view('store::stockadjustment.index', $data);
    }

    public function changeRoute(Request $request){
        try {
            $data['particulars'] = Entry::select('fldstockid as col')
                ->whereRaw('lower(fldstockid) like "%"')
                ->where('fldqty', '>', 0)
                ->where('fldcategory', $request->route)
                ->where('fldcomp', Helpers::getCompName())
                ->where('fldsav',1)
                ->orderBy('fldstockid', 'ASC')
                ->distinct('fldstockid')
                ->get();

            $html = '<option>--Select--</option>';
            if ($data['particulars']) {
                foreach ($data['particulars'] as $particular) {
                    $html .= '<option value="' . $particular->col . '">' . $particular->col . '</option>';
                }
            }
            return \response()->json($html);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function changeStock(Request $request){
        try {
            $data['batch'] = Entry::select('fldbatch as col')
                ->where('fldstockid', $request->particularSelect)
                ->where('fldcomp', Helpers::getCompName())
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->distinct('fldbatch')
                ->get();
            $html = '<option value=""> --Select--</option>';
            if ($data['batch']) {
                foreach ($data['batch'] as $batch) {
                    $html .= '<option value="' . $batch->col . '">' . $batch->col . '</option>';
                }
            }
            return \response()->json($html);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function batchChange(Request $request){
        try {
            $entryDetails = Entry::where('fldstockid', $request->medicineSelect)
                ->where('fldbatch', $request->batch)
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->where('fldcomp', Helpers::getCompName())
                ->with('pendingStockAdjust')
                ->get();

            if ($entryDetails) {
                $qty = 0;
                $expiry = "";
                foreach($entryDetails as $entryDetail){
                    $qty += $entryDetail->fldqty;
                    if(count($entryDetail->pendingStockAdjust) > 0){
                        $qty = $qty - $entryDetail->pendingStockAdjust->sum('fldcurrqty');
                    }
                    $expiry = $entryDetail->fldexpiry;
                }
                return \response()->json([
                    'expiry' => Carbon::parse($expiry)->format('Y-m-d'),
                    'qty' => $qty,
                ]);
            }

            return \response()->json('');

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function addStockAdjustment(Request $request){
        DB::beginTransaction();
        try {
            $entryDetails = Entry::where('fldstockid', $request->flditem)
                ->where('fldbatch', $request->batch)
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->where('fldcomp', Helpers::getCompName())
                ->orderBy('fldstatus','asc')
                ->get();

            $qty = $request->adjust_qty;
            foreach($entryDetails as $entryDetail){
                $tempqty = $entryDetail->fldqty;
                if(count($entryDetail->pendingStockAdjust) > 0){
                    $tempqty = $tempqty - $entryDetail->pendingStockAdjust->sum('fldcurrqty');
                }
                if($tempqty > 0){
                    if($qty > $tempqty){
                        $adjustqty = $tempqty;
                    }else{
                        $adjustqty = $qty;
                    }
                    $qty = $qty - $adjustqty;
                    if($adjustqty > 0){
                        $dataInsert['fldcategory'] = $request->route;
                        $dataInsert['fldstockno'] = $entryDetail->fldstockno;
                        $dataInsert['fldstockid'] = $request->flditem;
                        $dataInsert['fldnetcost'] = $entryDetail->fldsellpr * $adjustqty;
                        $dataInsert['fldsellpr'] = $entryDetail->fldsellpr;
                        $dataInsert['fldcompqty'] = $request->selected_quantity;
                        $dataInsert['fldcurrqty'] = $adjustqty;
                        $dataInsert['fldreason'] = $request->reason;
                        $dataInsert['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                        $dataInsert['fldtime'] = date("Y-m-d H:i:s");
                        $dataInsert['fldcomp'] = Helpers::getCompName();
                        $dataInsert['fldsav'] = 0;
                        $dataInsert['fldreference'] = NULL;
                        $dataInsert['xyz'] = 0;
                        $dataInsert['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        Adjustment::create($dataInsert);
                    }
                }
            }

            $html = $this->listStockAdjusted();
            DB::commit();
            return response()->json([
                'status' => TRUE,
                'data' => $html,
                'message' => 'Success.',
            ]);
        } catch (\GearmanException $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public  function finalSave(Request $request){
        if(!$request->get('adjustids'))
        {
            return \response()->json(['error','Please select row first!']);
        }

        try {
            DB::beginTransaction();
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $stockAdjustmentReference = 'SAJF-'.$fiscalYear."-".Helpers::getNextAutoId('ReferenceNo',TRUE);
            if($request->adjustids){
                foreach ($request->adjustids as $stockadjust){
                    $adjustment = Adjustment::where('fldid',$stockadjust)->first();

                    $entry = Entry::where('fldstockno',$adjustment->fldstockno)->first();
                    if($entry) {
                        $quantity = ($entry->fldqty)-($adjustment->fldcurrqty);
                        $entry->update(['fldqty'=>$quantity]);
                    }

                    $adjustment->update([
                                        'fldsav'=>1,
                                        'fldreference'=>$stockAdjustmentReference
                                    ]);
                }
            }
            $html = $this->listStockAdjusted();
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
        }

        return response([
            'status' => true,
            'fldreference' => $stockAdjustmentReference,
            'html' => $html
        ]);
    }

    public function listStockAdjusted()
    {
        $stockAdjusted = Adjustment::select(\DB::raw('GROUP_CONCAT(tbladjustment.fldid) as fldids'),'tbladjustment.fldstockno', 'tbladjustment.fldcategory', 'tbladjustment.fldstockid', 'tbladjustment.fldreference','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tbladjustment.fldcompqty) as fldcompqty'),\DB::raw('sum(tbladjustment.fldcurrqty) as fldcurrqty'),\DB::raw('sum(tbladjustment.fldnetcost) as fldnetcost'),\DB::raw('sum(tbladjustment.fldsellpr) as fldsellpr'),'tbladjustment.fldreason')
                                    ->leftJoin('tblentry','tblentry.fldstockno','=','tbladjustment.fldstockno')
                                    ->where('tbladjustment.fldcomp', Helpers::getCompName())
                                    ->where('tbladjustment.fldsav',0)
                                    ->with('stock')
                                    ->groupBy(['tbladjustment.fldstockid','tblentry.fldbatch'])
                                    ->get();

        $html = '';
        $count = 1;
        if (count($stockAdjusted)) {
            foreach ($stockAdjusted as $stock) {
                $html .= '<tr data-fldid="'.$stock->fldids.'">';
                $html .= '<td><input type="checkbox" class="js-stockadjust-select-class-checkbox" data-fldid="'.$stock->fldids.'"></td>';
                $html .= '<td>' . $stock->fldcategory . '</td>';
                $html .= '<td>' . $stock->fldstockid . '</td>';
                $html .= '<td>' . $stock->fldbatch . '</td>';
                $html .= '<td>' . $stock->fldexpiry . '</td>';
                $html .= '<td>' . $stock->fldcurrqty . '</td>';
                $html .= '<td>' . $stock->fldnetcost . '</td>';
                $html .= '</tr>';
                $count++;
            }
        }
        return $html;
    }

    public function exportReport(Request  $request)
    {
        $data['stockAdjusted'] = Adjustment::select('tbladjustment.fldstockno', 'tbladjustment.fldcategory', 'tbladjustment.fldstockid', 'tbladjustment.fldreference','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tbladjustment.fldcompqty) as fldcompqty'),\DB::raw('sum(tbladjustment.fldcurrqty) as fldcurrqty'),\DB::raw('sum(tbladjustment.fldnetcost) as fldnetcost'),\DB::raw('sum(tbladjustment.fldsellpr) as fldsellpr'),'tbladjustment.fldreason')
                                        ->leftJoin('tblentry','tblentry.fldstockno','=','tbladjustment.fldstockno')
                                        ->where('fldreference', $request->fldreference)
                                        ->with('stock')
                                        ->groupBy(['tbladjustment.fldstockid','tblentry.fldbatch'])
                                        ->get();
        $data['certificate'] = "STOCK ADJUSTMENT";
        $data['fldreference'] = $request->fldreference;
        return view('store::stockadjustment.stock-adjustment-report', $data);
    }

    public function authenticateUser(Request $request)
    {
        $pwd = $request->get('password');

        $generated_pwd = "";
        for ($i = 0, $iMax = strlen($pwd); $i < $iMax; $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }

        $user = CogentUsers::where('username', $request->get('username'))
            ->first();

        $currentdate = date('Y-m-d');


        /**USER DOES NOT EXITS*/
        if (!$user) {
            return response([
                'status' => false,
                'msg' => "Invalid username or password."
            ]);
        }
        /**DELETED USER*/
        if ($user->status == "Deleted") {
            return response([
                'status' => false,
                'msg' => "Invalid username or password."
            ]);
        }

        /**INACTIVE USER*/
        if ($user->status == "InActive") {
            return response([
                'status' => false,
                'msg' => "Invalid username or password."
            ]);
        }

        /**CHECK USER PASSWORD*/
        if (!($generated_pwd == $user->password)) {
            return response([
                'status' => false,
                'msg' => "Invalid username or password."
            ]);
        }

        if (!isset($user->user_is_superadmin) && count($user->user_is_superadmin) <= 0) {
            /**IF USER IS EXPIRED*/
            if ($currentdate > $user->fldexpirydate) {
                return response([
                    'status' => false,
                    'msg' => "User login credential expired. Please contact system admin."
                ]);
            }
        }

        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            return response([
                'status' => true,
                'msg' => "Successfull Authentication."
            ]);
        }else{
            if (\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower("Stock Adjustment")))){
                return response([
                    'status' => true,
                    'msg' => "Successfull Authentication."
                ]);
            }else{
                return response([
                    'status' => false,
                    'msg' => "You donnot have the required access. Please contact system admin."
                ]);
            }
        }
    }
}
