<?php

namespace Modules\Store\Http\Controllers;

use App\AutoId;
use App\Drug;
use App\Entry;
use App\Events\StockLive;
use App\ExtraBrand;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use App\MedicineBrand;
use App\SurgBrand;
use App\Surgical;
use App\Transfer;
use App\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

/**
 * Class StockTransferController
 * @package Modules\Store\Http\Controllers
 */
class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $data['routes'] = Drug::select('fldroute')->distinct()->orderby('fldroute', 'ASC')->get();

        $data['transferRequest'] = Transfer::select(\DB::raw('GROUP_CONCAT(tbltransfer.fldid) as fldids'),'tbltransfer.fldcategory','tbltransfer.fldstockid','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tbltransfer.fldqty) as fldqty'),'tbltransfer.fldsellpr',\DB::raw('sum(tbltransfer.fldnetcost) as fldnetcost'))
            ->leftJoin('tblentry','tblentry.fldstockno','=','tbltransfer.fldoldstockno')
            ->where('tbltransfer.fldsav', 0)
            ->where('tbltransfer.fldfromuser', \Auth::guard('admin_frontend')->user()->flduserid)
            ->where('tbltransfer.fldfromcomp', Helpers::getCompName())
            ->groupBy(['tbltransfer.fldstockid','tblentry.fldbatch'])
            ->get();

        // $data['transferRequest'] = Transfer::where('fldsav', 0)
        //     ->where('fldfromuser', \Auth::guard('admin_frontend')->user()->flduserid)
        //     ->where('fldfromcomp', Helpers::getCompName())
        //     ->with('batch')
        //     ->get();

        $data['hospital_department'] = HospitalDepartment::select('name', 'fldcomp', 'branch_id')->with('branchData')->get();

        return view('store::stocktransfer.index', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeRoute(Request $request)
    {

        if ($request->routeChanged == 'msurg' || $request->routeChanged == 'suture' || $request->routeChanged == 'ortho') {
            $surgicals = Surgical::select('fldsurgid')->where('fldsurgcateg', $request->routeChanged)->pluck('fldsurgid');

            $surgicalBrand = SurgBrand::select('fldbrandid')
                ->where('fldactive', 'Active')
                ->whereIn('fldsurgid', $surgicals)
                ->pluck('fldbrandid');

            $data['medicines'] = Entry::select('fldstockid as col')
                ->whereRaw('lower(fldstockid) like "%"')
                ->where('fldqty', '>', 0)
                ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
                ->whereIn('fldstockid', $surgicalBrand)
                ->orderBy('fldstockid', 'ASC')
                ->get();

        } elseif ($request->routeChanged == 'extra') {
            $surgicals = Surgical::select('fldbrandid')->where('fldactive', 'Active')->pluck('fldbrandid');

            $data['medicines'] = Entry::select('fldstockid as col')
                ->whereRaw('lower(fldstockid) like "%"')
                ->where('fldqty', '>', 0)
                ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
                ->whereIn('fldstockid', $surgicals)
                ->orderBy('fldstockid', 'ASC')
                ->get();
        } else {
            $drug = Drug::select('flddrug')->where('fldroute', $request->routeChanged)->pluck('flddrug');

            $medBrand = MedicineBrand::select('fldbrandid')
                ->where('fldactive', 'Active')
                ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
                ->whereIn('flddrug', $drug)
                ->pluck('fldbrandid');

            $data['medicines'] = Entry::select('fldstockid as col')
                ->whereRaw('lower(fldstockid) like "%"')
                ->where('fldqty', '>', 0)
                ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
                ->whereIn('fldstockid', $medBrand)
                ->orderBy('fldstockid', 'ASC')
                ->get();

        }
        $html = view('store::dynamic-views.med-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeStock(Request $request)
    {
        $data['batch'] = Entry::select('fldbatch as col')
            ->where('fldstockid', $request->medicineSelect)
            ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
            ->where('fldqty', '>', 0)
            ->distinct()
            ->get();

        $batch = view('store::dynamic-views.stock-batch', $data)->render();

        return $batch;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function batchChange(Request $request)
    {
        $entryDetails = Entry::select('fldstockno', 'fldexpiry', 'fldqty', 'fldstatus', 'fldcategory', 'fldsellpr')
            ->where('fldstockid', $request->medicineSelect)
            ->where('fldbatch', $request->batch)
            ->where('fldcomp', Session::get('selected_user_hospital_department')->fldcomp)
            ->first();

        return $entryDetails;
    }

    /**
     * @param Request $request
     */
    public function addStockConsumed(Request $request)
    {
        try {
            /*not working in application*/
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getPendingListsByDept(Request $request)
    {
        $pendings = Transfer::select(\DB::raw('GROUP_CONCAT(tbltransfer.fldid) as fldids'),'tbltransfer.fldcategory','tbltransfer.fldstockid','tblentry.fldbatch',\DB::raw('sum(tbltransfer.fldqty) as fldqty'),'tbltransfer.fldsellpr','tbltransfer.fldfromcomp','tbltransfer.fldtocomp')
            ->leftJoin('tblentry','tblentry.fldstockno','=','tbltransfer.fldoldstockno')
            ->where('tbltransfer.fldfromcomp', Helpers::getCompName())
            ->where('tbltransfer.fldtocomp', $request->fldcomp)
            ->where('tbltransfer.fldsav', True)
            ->where('tbltransfer.fldfromsav', True)
            ->where('tbltransfer.fldtosav', False)
            ->groupBy(['tbltransfer.fldstockid','tblentry.fldbatch'])
            ->get();

        // $pendings = Transfer::select('fldid', 'fldoldstockno', 'fldstockid', 'fldstockno', 'fldcategory', 'fldqty', 'fldsellpr', 'fldreference', 'fldremark', 'fldfromcomp', 'fldtocomp')
        //     ->where('fldfromcomp', Helpers::getCompName())
        //     ->where('fldtocomp', $request->fldcomp)
        //     ->where('fldsav', True)
        //     ->where('fldfromsav', True)
        //     ->where('fldtosav', False)
        //     ->get();
        $html = '';
        foreach($pendings as $pen){
            $html .= '<tr>
                        <td>'.$pen->fldstockid.'</td>
                        <td>'.$pen->fldcategory.'</td>
                        <td>'.$pen->fldqty.'</td>
                        <td>'.$pen->fldsellpr.'</td>
                        <td>'.\App\Utils\Helpers::getDepartmentFromCompID($pen->fldfromcomp).'</td>
                        <td>'.\App\Utils\Helpers::getDepartmentFromCompID($pen->fldtocomp).'</td>
                    </tr>';
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getReceivedListsByDept(Request $request)
    {
        $received = Transfer::select(\DB::raw('GROUP_CONCAT(tbltransfer.fldid) as fldids'),'tbltransfer.fldcategory','tbltransfer.fldstockid','tblentry.fldbatch',\DB::raw('sum(tbltransfer.fldqty) as fldqty'),'tbltransfer.fldsellpr','tbltransfer.fldfromcomp','tbltransfer.fldtocomp')
            ->leftJoin('tblentry','tblentry.fldstockno','=','tbltransfer.fldoldstockno')
            ->where('tbltransfer.fldtocomp', Helpers::getCompName())
            ->where('tbltransfer.fldfromcomp', $request->fldcomp)
            ->where('tbltransfer.fldsav', True)
            ->where('tbltransfer.fldfromsav', True)
            ->where('tbltransfer.fldtosav', False)
            ->groupBy(['tbltransfer.fldstockid','tblentry.fldbatch'])
            ->get();

        // $received = Transfer::select('fldid', 'fldoldstockno', 'fldstockid', 'fldstockno', 'fldcategory', 'fldqty', 'fldsellpr', 'fldfromcomp', 'fldreference', 'fldremark', 'fldfromcomp', 'fldtocomp')
        //                     ->where('fldtocomp', Helpers::getCompName())
        //                     ->where('fldfromcomp', $request->fldcomp)
        //                     ->where('fldsav', True)
        //                     ->where('fldfromsav', True)
        //                     ->where('fldtosav', False)
        //                     ->get();
        $html = '';
        foreach($received as $pen){
            $html .= '<tr data-fldid="'.$pen->fldids.'">
                        <td><input type="checkbox" class="js-receive-select-class-checkbox" data-fldid="'.$pen->fldids.'"></td>
                        <td>'.$pen->fldstockid.'</td>
                        <td>'.$pen->fldcategory.'</td>
                        <td>'.$pen->fldqty.'</td>
                        <td>'.$pen->fldsellpr.'</td>
                        <td>'.\App\Utils\Helpers::getDepartmentFromCompID($pen->fldfromcomp).'</td>
                        <td>'.\App\Utils\Helpers::getDepartmentFromCompID($pen->fldtocomp).'</td>
                    </tr>';
        }
        return $html;
    }

    public function getMedicineList(Request $request)
    {
        $route = $request->drug;
        $data['newOrderData'] = Entry::select('fldstockid')
                                    ->where('fldcategory',$route)
                                    ->where('fldsav',1)
                                    ->where('fldcomp', Helpers::getCompName())
                                    ->where('fldqty', '>', 0)
                                    ->groupBy('fldstockid')
                                    ->get();

        $html = view('store::stocktransfer.stock-send', $data)->render();

        return $html;
    }

    public function getMedicineListBatch(Request $request)
    {
        $drug = $request->drug;
        $medicine = $request->medicine;
        $data['newOrderData'] = Entry::select('fldstockno', 'fldstockid', 'fldbatch', 'fldsellpr', 'fldexpiry', 'fldsav', 'fldstatus', 'fldcategory', 'fldcomp', 'fldqty')
            ->where('fldstockid', $medicine)
            ->where('fldqty', '>', 0)
            ->where('fldsav', 1)
            ->where('fldcomp', Helpers::getCompName())
            ->with('pendingTransfer')
            ->get()
            ->groupBy('fldbatch');
        $html = view('store::stocktransfer.stock-batch', $data)->render();

        return $html;
    }

    public function addTransfer(Request $request)
    {
        $request->validate([
            'department_to' => 'required',
            'pharmacy_route' => 'required',
            'medicine_name' => 'required',
            'batch_medicine' => 'required',
            'id_qty' => 'required',
            'id_cost' => 'required',
        ]);
        DB::beginTransaction();
        try {
            // $entryData = Entry::where('fldbatch', $request->batch_medicine)
            //     ->where('fldstockid', $request->medicine_name)
            //     ->where('fldcomp',Helpers::getCompName())
            //     ->first();

            $entryDatas = Entry::where('fldbatch', $request->batch_medicine)
                ->where('fldstockid', $request->medicine_name)
                ->where('fldcomp',Helpers::getCompName())
                ->where('fldqty', '>', '0')
                ->orderBy('fldstatus','asc')
                ->get();
            $transferids = [];
            $qty = $request->id_qty;
            $html = '';
            foreach($entryDatas as $key=>$entryData){
                $tempqty = $entryData->fldqty;
                if(count($entryData->pendingTransfer) > 0){
                    $tempqty = $tempqty - $entryData->pendingTransfer->sum('fldqty');
                }
                if($tempqty > 0){
                    if($qty > $tempqty){
                        $trans_qty = $tempqty;
                    }else{
                        $trans_qty = $qty;
                    }
                    $qty = $qty - $trans_qty;
                    if($trans_qty > 0){
                        $stockNumber = Helpers::getNextAutoId('StockNo', TRUE);

                        $insertTransfer['fldstockno'] = $stockNumber;
                        $insertTransfer['fldoldstockno'] = $entryData->fldstockno;
                        $insertTransfer['fldstockid'] = $request->medicine_name;
                        $insertTransfer['fldcategory'] = $request->pharmacy_route;
                        $insertTransfer['fldqty'] = $trans_qty;
                        $insertTransfer['fldnetcost'] = $entryData->fldsellpr * $trans_qty;
                        // $insertTransfer['fldnetcost'] = $request->id_cost;
                        $insertTransfer['fldsellpr'] = $entryData->fldsellpr;
                        $insertTransfer['fldsav'] = 0;
                        $insertTransfer['fldfromentrytime'] = now();
                        $insertTransfer['fldfromuser'] = \Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                        $insertTransfer['fldfromcomp'] = $entryData->fldcomp;
                        $insertTransfer['fldfromsav'] = 0;
                        $insertTransfer['fldtosav'] = 0;
                        $insertTransfer['fldtoentrytime'] = NULL;
                        $insertTransfer['fldtouser'] = NULL;
                        $insertTransfer['fldreference'] = NULL;
                        $insertTransfer['fldtocomp'] = $request->department_to;
                        $insertTransfer['xyz'] = 0;
                        $insertTransfer['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        $transferid = Transfer::insertGetId($insertTransfer);
                        array_push($transferids,$transferid);
                    }
                }
            }
            if (count($transferids)) {
                $transferRequests = Transfer::select(\DB::raw('GROUP_CONCAT(tbltransfer.fldid) as fldids'),'tbltransfer.fldcategory','tbltransfer.fldstockid','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tbltransfer.fldqty) as fldqty'),'tbltransfer.fldsellpr',\DB::raw('sum(tbltransfer.fldnetcost) as fldnetcost'))
                        ->leftJoin('tblentry','tblentry.fldstockno','=','tbltransfer.fldoldstockno')
                        ->whereIn('tbltransfer.fldid', $transferids)
                        ->groupBy(['tbltransfer.fldstockid','tblentry.fldbatch'])
                        ->get();
                foreach ($transferRequests as $key => $transferRequest) {
                    $html .= "<tr><input type='hidden' name='sent_to_medicine[]' value='" . $transferRequest->fldids . "'>";
                    $html .= "<td>" . ++$key . "</td>";
                    $html .= "<td>" . $transferRequest->fldcategory ?? '' . "</td>";
                    $html .= "<td>$transferRequest->fldstockid</td>";
                    $html .= "<td>" . $transferRequest->fldbatch . "</td>";
                    $html .= "<td>" . $transferRequest->fldexpiry . "</td>";
                    $html .= "<td>$transferRequest->fldqty</td>";
                    $html .= "<td>$transferRequest->fldsellpr</td>";
                    $html .= "</tr>";
                }
            }

            DB::commit();

            return response([
                'html'=>$html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function saveTransfer(Request $request)
    {
        try {
            if ($request->transferId) {
                $transferIds = array_unique($request->transferId);
                $today_date = Carbon::now()->format('Y-m-d');
                $data = [];
                $fiscal_year = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
                $transferNumber = Helpers::getNextAutoId('TransferNo', TRUE);
                foreach ($transferIds as $transfer) {

                    $saveData['fldsav'] = 1;
                    $saveData['fldfromsav'] = 1;
                    $saveData['xyz'] = 0;
                    $saveData['fldtranref'] = "TN-$fiscal_year->fldname-$transferNumber";
                    $saveData['fldfromentrytime'] = now();
                    $saveData['fldfromuser'] = \Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    // $saveData['fldtosav'] = 1;

                    $transferData = Transfer::where('fldid', $transfer)->first();
                    Transfer::where('fldid', $transfer)->update($saveData);
                    $entryDetails = Entry::where('fldstockno', $transferData->fldoldstockno)->first();

                    // $fldstockno = Helpers::getNextAutoId('StockNo', TRUE);
                    \App\Entry::insert([
                        'fldstockno' => $transferData->fldstockno,
                        'fldstockid' => $entryDetails->fldstockid,
                        'fldcategory' => $entryDetails->fldcategory,
                        'fldbatch' => $entryDetails->fldbatch,
                        'fldexpiry' => $entryDetails->fldexpiry,
                        'fldqty' => $transferData->fldqty,
                        'fldstatus' => $entryDetails->fldstatus,
                        'fldsellpr' => $transferData->fldsellpr,
                        'fldsav' => 0,
                        'fldcomp' => $transferData->fldtocomp,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => $entryDetails->fldisopening,
                        'fldfiscalyear' => $entryDetails->fldfiscalyear,
                        'fldbarcode' => $entryDetails->fldbarcode
                    ]);

                    Entry::where('fldstockno', $transferData->fldoldstockno)->decrement('fldqty', $transferData->fldqty);
                }
            }

            return $this->getPendingHtml();
        } catch (\Exception $e) {

        }
    }

    private function getPendingHtml()
    {
        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $userdept = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->get();
        } else {
            $userdept = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->get();
        }
        $departmentComp = HospitalDepartment::whereIn('id', $userdept->pluck('hospital_department_id'))->get();

        $pending = Transfer::select('fldid', 'fldoldstockno', 'fldstockid', 'fldstockno', 'fldcategory', 'fldqty', 'fldsellpr', 'fldreference', 'fldremark', 'fldfromcomp', 'fldtocomp')
            ->whereIn('fldfromcomp', $departmentComp->pluck('fldcomp'))
            ->with('fromDepartment')
            ->where('fldsav', True)
            ->where('fldfromsav', True)
            ->where('fldtosav', False)
            ->get();

        $html = '';
        if ($pending) {
            foreach ($pending as $pen) {
                $html .= "<tr>
                            <td>$pen->fldstockid</td>
                            <td>$pen->fldcategory</td>
                            <td>$pen->fldqty</td>
                            <td>$pen->fldsellpr</td>
                            <td>" . ($pen->fromDepartment ? $pen->fromDepartment->name : '') . "</td>
                            <td>" . ($pen->toDepartment ? $pen->toDepartment->name : '') . "</td>
                        </tr>";
            }
        }
        return $html;
    }

    public function getItemByDemandNumber(Request $request)
    {
        $fldcomp = $request->get('fldcomp');
        $flddemandno = $request->get('flddemandno');

        return response()->json(\App\Demand::select("tbldemand.fldid", "tbldemand.fldroute", "tbldemand.fldstockid", "tbldemand.fldbatch", "tbldemand.fldremqty AS fldquantity", "tbldemand.fldrate", "tblentry.fldexpiry")
            ->join("tblentry", function($query) {
                $query->on("tbldemand.fldstockid", "=", "tblentry.fldstockid")->on("tbldemand.fldbatch", "=", "tblentry.fldbatch");
            })->where([
                // ["fldcomp_order" , Helpers::getCompName()],
                ["fldsave_order" , "1"],
                ["fldquotationno" , $flddemandno],
                ["fldremqty", ">", '0']
            ])->groupBy("tbldemand.fldid", "tbldemand.fldroute", "tbldemand.fldstockid", "tbldemand.fldbatch", "tbldemand.fldquantity", "tbldemand.fldrate", "tblentry.fldexpiry")
            ->get()
        );
    }

    public function getItemByBarcode(Request $request)
    {
        $fldcomp = $request->get('fldcomp');
        $fldbarcode = $request->get('fldbarcode');
        $entryDetails = \App\Entry::select('fldstockno', 'fldstockid', 'fldbatch', 'fldsellpr', 'fldexpiry', 'fldsav', 'fldstatus', 'fldcategory', 'fldcomp', 'fldqty')
                                    ->where([
                                        ["fldcomp" , $fldcomp],
                                        ["fldsav" , "1"],
                                        ["fldbarcode" , $fldbarcode],
                                        ["fldqty", ">", '0']
                                    ])
                                    ->with('pendingTransfer')
                                    ->get()
                                    ->groupBy('fldbatch');

        $batchOption = "<option>Select</option>";
        foreach($entryDetails as $batch=>$entryDetail){
            $totqty = 0;
            foreach($entryDetail as $entry){
                $dataqty = $entry->fldqty;
                if(count($entry->pendingTransfer) > 0){
                    $dataqty = $dataqty - $entry->pendingTransfer->sum('fldqty');
                }
                $totqty += $dataqty;
            }
            $batchOption .= '<option value="'.$batch.'" data-price="'.$entryDetail[0]->fldsellpr.'" data-qty="'.$totqty.'" data-expiry="'.$entryDetail[0]->fldexpiry.'">'.$batch.'</option>';
        }

        $itemRoute = "";
        $itemName = "";
        $itemDet = array_values($entryDetails->toArray())[0][0];
        if(isset($itemDet)){
            if($itemDet['fldcategory'] == "Medicines"){
                $item = MedicineBrand::select('tbldrug.fldroute as fldroute')
                                    ->join('tbldrug','tbldrug.flddrug','=','tblmedbrand.flddrug')
                                    ->where('tblmedbrand.fldbrandid',$itemDet['fldstockid'])
                                    ->first();
                $itemRoute = $item->fldroute;
            }elseif($itemDet['fldcategory'] == "Surgicals"){
                $item = Surgical::select('fldsurgcateg as fldroute')
                                ->where('fldsurgid',$itemDet['fldstockid'])
                                ->first();
                $itemRoute = $item->fldroute;
            }else{
                $itemRoute = "extra";
            }
            $itemName = $itemDet['fldstockid'];
        }

        $newOrderData = Entry::select('fldstockid')
                            ->where('fldcategory',$itemRoute)
                            ->where('fldsav',1)
                            ->where('fldcomp', Helpers::getCompName())
                            ->where('fldqty', '>', 0)
                            ->groupBy('fldstockid')
                            ->get();
        $options = '<option>Select</option>';
        if ($newOrderData) {
            foreach ($newOrderData as $newOrder) {
                $options .= '<option value="'.$newOrder->fldstockid.'">'.$newOrder->fldstockid.'</option>';
            }
        }

        return response()->json([
            'entryDetails' => $entryDetails,
            'itemRoute' => $itemRoute,
            'itemName' => $itemName,
            'options' => $options,
            'batchOption' => $batchOption
        ]);
    }

    public function saveItemByDemandNumber(Request $request)
    {
        $data = $request->get('data');
        $formated = array_combine(array_column($data, 'fldid'), array_column($data, 'fldquantity'));

        $demandData = \App\Demand::select("tbldemand.fldstockid", "tbldemand.fldid", "tbldemand.fldrate", "tbldemand.fldtime_order", "tbldemand.flduserid_order", "tbldemand.fldcomp_order", "tbldemand.fldordcomp", "tblentry.fldstockno", "tbldemand.fldroute")
            ->join("tblentry", function($query) {
                $query->on("tbldemand.fldstockid", "=", "tblentry.fldstockid")->on("tbldemand.fldbatch", "=", "tblentry.fldbatch");
            })->whereIn('fldid', array_column($data, 'fldid'))
            ->groupBy("tbldemand.fldstockid", "tbldemand.fldid", "tbldemand.fldrate", "tbldemand.fldtime_order", "tbldemand.flduserid_order", "tbldemand.fldcomp_order", "tbldemand.fldordcomp", "tblentry.fldstockno")
            ->get();

        $transfers = [];
        $departmentComp = Helpers::getUserSelectedHospitalDepartmentIdSession();
        $time = date('Y-m-d H:i:s');
        $user = \Auth::guard('admin_frontend')->user()->flduserid ?? 0;

        \DB::beginTransaction();
        try {
            $transferNumber = Helpers::getNextAutoId('TransferNo', TRUE);
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
            $transferNumber = "TN-$fiscal_year->fldname-$transferNumber";
            foreach($demandData as $demand) {
                $stockNumber = Helpers::getNextAutoId('StockNo', TRUE);
                $fldqty = (isset($formated[$demand->fldid])) ? $formated[$demand->fldid] : 0;
                $transfers[] = [
                    'fldstockno' => $stockNumber,
                    'fldoldstockno' => $demand->fldstockno,
                    'fldstockid' => $demand->fldstockid,
                    'fldcategory' => Helpers::getParticularCategory($demand->fldroute),
                    'fldqty' => $fldqty,
                    'fldnetcost' => $demand->fldrate,
                    'fldsellpr' => $demand->fldrate,
                    'fldsav' => 1,
                    'fldfromentrytime' => $time,
                    'fldfromuser' => $user,
                    'fldfromcomp' =>  $demand->fldcomp_order,
                    'fldfromsav' => 1,
                    'fldtoentrytime' => $demand->fldtime_order,
                    'fldtouser' => $demand->flduserid_order,
                    'fldtocomp' => $request->get('fldcomp'),
                    'fldtosav' => 0,
                    'fldreference' => NULL,
                    'xyz' => 0,
                    'hospital_department_id' => $departmentComp,
                    'fldremark' => NULL,
                    'fldtranref' => $transferNumber,
                    'fldrequest' => NULL,
                ];
                Entry::where('fldstockno', $demand->fldstockno)->decrement('fldqty', $fldqty);
                \App\Demand::where('fldid', $demand->fldid)->decrement('fldremqty', $fldqty);
            }
            Transfer::insert($transfers);
            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();
            return response()->json('Failed to save data.', 500);
        }

        return response()->json([
            'html' => $this->getPendingHtml()
        ]);
    }

    public function confirmStockReceive(Request $request){
        \DB::beginTransaction();
        try {
            if(isset($request->transferids)){
                $fiscalYear = Helpers::getFiscalYear()->fldname;
                $stockReceivedReference = 'TRNF-'.$fiscalYear."-".Helpers::getNextAutoId('ReferenceNo',TRUE);
                foreach($request->transferids as $transferid){
                    $saveData['fldtosav'] = 1;
                    $saveData['fldtoentrytime'] = now();
                    $saveData['fldtouser'] = \Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                    $saveData['fldreference'] = $stockReceivedReference;
                    Transfer::where('fldid', $transferid)->update($saveData);

                    $transferData = Transfer::where('fldid', $transferid)->first();

                    $updateEntryData['fldsav'] = 1;
                    Entry::where('fldstockno', $transferData->fldstockno)->update($updateEntryData);
                }
            }
            $html = $this->getReceivedListsByDept($request);
            \DB::commit();
            //event trigger for live stock
            event(new StockLive(1));
            return response([
                'status'=>true,
                'html'=>$html,
                'stockReceivedReference'=>$stockReceivedReference
            ]);
        } catch (Exception $ex) {
            \DB::rollBack();
            return response()->json('Failed to save data.', 500);
        }
    }

    public function exportReport(Request $request){
        $data['stockTransfered'] = Transfer::select('tbltransfer.fldstockno','tbltransfer.fldoldstockno','tbltransfer.fldcategory','tbltransfer.fldstockid',\DB::raw('sum(tbltransfer.fldqty) as fldqty'),\DB::raw('sum(tbltransfer.fldnetcost) as fldnetcost'),'tbltransfer.fldreference','tbltransfer.fldtranref','tblentry.fldbatch','tblentry.fldexpiry')
                                            ->leftJoin('tblentry','tblentry.fldstockno','=','tbltransfer.fldoldstockno')
                                            ->where('fldreference', $request->fldreference)
                                            ->groupBy(['tbltransfer.fldstockid','tblentry.fldbatch'])
                                            ->get();
        $data['certificate'] = "STOCK TRANSFER";
        $data['fldreference'] = $request->fldreference;
        return view('store::stocktransfer.stock-transfer-report', $data);
    }
}
