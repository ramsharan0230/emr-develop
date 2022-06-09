<?php

namespace Modules\Dispensar\Http\Controllers;

use DB;
use Log;
use Auth;
use Throwable;
use Exception;
use App\Year;
use App\Entry;
use Carbon\Carbon;
use App\Encounter;
use App\PatBilling;
use App\PatBillCount;
use App\PatBillDetail;
use App\Departmentbed;
use App\Utils\Helpers;
use App\Utils\Options;
use App\PatBillingShare;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\DepartmentRevenueService;
use App\Services\MaternalisedService;

class ReturnFormController extends Controller
{
    public function index()
    {
        $data = [
            'itemTypes' => Helpers::getItemType(TRUE),
        ];
        return view('dispensar::returnForm', $data);
    }

    public function getPatientDetail(Request $request)
    {
    	/*
			select fldencounterval from tblpatbilldetail where fldbillno='CAS-13444'
			select fldpatientval from tblencounter where fldencounterval='E8752GH'
			select fldptnamefir,fldptnamelast,fldencrypt from tblpatientinfo where fldpatientval='8727GH'
			select fldpatientval from tblencounter where fldencounterval='E8752GH'
			select fldptsex from tblpatientinfo where fldpatientval='8727GH'
			select fldpatientval from tblencounter where fldencounterval='E8752GH'
			select fldptaddvill from tblpatientinfo where fldpatientval='8727GH'
			select fldpatientval from tblencounter where fldencounterval='E8752GH'
			select fldptadddist from tblpatientinfo where fldpatientval='8727GH'
			select fldid,fldordtime,flditemtype,flditemno,flditemname,flditemrate,flditemqty,fldtaxper,flddiscper,fldditemamt as tot,flduserid from tblpatbilling where fldencounterval='E8752GH' and fldsave='0' and fldprint='0' and fldordcomp='comp01' and fldstatus='Punched' and flditemqty<0 and (flditemtype='Medicines' or flditemtype='Surgicals' or flditemtype='Extra Items')
    	*/
        try {
            $queryColumn = $request->get('queryColumn');
            $querytobuilt = $request->get('queryValue');
            if($queryColumn == "invoice") {
                if(substr_count ($querytobuilt, '-') > 0) {
                    $billNumberGenerate = $querytobuilt;
                } else {
                    $dateToday = Carbon::now();
                    $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                    $billNumberGenerate = "%-{$year->fldname}-{$querytobuilt}" . Options::get('hospital_code').'%';
                }
                $patBilling = PatBilling::select('fldbillno', 'fldencounterval')->where('fldbillno','LIKE',$billNumberGenerate)->first();
                if(!$patBilling){
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Invalid bill number.'
                    ]);
                }

                $billnumbergen = $patBilling->fldbillno;
                $encounterId = $patBilling->fldencounterval;
            } else {
                if(substr_count ($querytobuilt, '-') > 0) {
                    $encountergen = $querytobuilt;
                } else {
                    $dateToday = Carbon::now();
                    $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                    $encountergen = "%{$year->fldname}-{$querytobuilt}".'%';
                }
                $patBilling = PatBilling::select('fldencounterval')->where('fldencounterval','LIKE',$encountergen)->first();
                if(!$patBilling){
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Invalid encounter number.'
                    ]);
                }
                $encounterId = $patBilling->fldencounterval;
            }

            $patientInfo = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank')
                ->where('fldencounterval', $encounterId)
                ->first();


            $items = PatBilling::select('fldid', 'fldbillno', 'fldordtime', 'flditemtype', 'flditemno', 'flditemname', 'flditemrate', 'flditemqty', 'fldtaxper', 'flddiscper', 'fldditemamt', 'fldorduserid', 'fldsample', 'fldstatus', 'fldsave', 'fldprint', 'fldretbill')
                // ->with('medicine:fldstockid', 'medicine.batches:fldstockid,fldbatch,fldexpiry')
                ->with('medicine:fldstockid,fldbatch,fldexpiry,fldstockno')
                ->where('fldencounterval', $encounterId)
                ->where(function($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })->get();

            $results = $items->whereIn('fldsample', ['Waiting', 'Removed'])->where('flditemqty', '>' , 0)->where('fldstatus', 'Cleared');
            if($queryColumn == "invoice") {
                $results = $results->where('fldbillno', $billnumbergen);
            }
            $remainingItems = [];
            if($results && count($results) > 0) {
                $returns = PatBilling::select('fldparent', DB::raw("SUM(flditemqty) AS sum"))
                    ->whereIn('fldparent', $results->pluck('fldid')->toArray())
                    ->groupBy('fldparent')
                    ->get()
                    ->pluck('sum', 'fldparent');
                foreach ($results as $item) {
                    $returnQty = (isset($returns[$item->fldid])) ? abs($returns[$item->fldid]) : 0;
                    if (($actual = $item->flditemqty - $returnQty) <= 0)
                        continue;
                    if($item->medicine) {
                        $itemName = "";
                        if($queryColumn == "invoice") {
                            $itemName = $item->medicine->fldstockid . ' | ' . $item->medicine->fldbatch;
                        } else {
                            $itemName = $item->medicine->fldstockid . ' | ' . $item->medicine->fldbatch . ' | ' . $item->fldbillno;
                        }
                        $item->fldstockno = $item->medicine->fldstockno;
                        $item->itemname = $itemName;
                        $item->fldexpiry = $item->medicine->fldexpiry;
                        $item->flditemqty = $actual;
                        $item->returnQty = $returnQty;
                        $item->hasReturnQty = (isset($returns[$item->fldid]));
                        $item->lessZero = (($actual) <= 0);
                        array_push($remainingItems, $item);
                    }
                }
            }
            $returnItems = $items->where('fldsave', '0')
                ->where('fldprint', '0')
                ->where('fldstatus', 'Punched')
                ->where('flditemqty', '<' , 0);
            if($queryColumn == "invoice") {
                $returnItems = $returnItems->where('fldretbill', $billnumbergen);
            }
            $patbilldetail = array();
            if($queryColumn == "invoice"){
                $patbilldetail = PatBillDetail::select('fldbilltype','payment_mode','fldpayitemname','fldcurdeposit')->where('fldbillno','LIKE',$billNumberGenerate)->first();
            }
            // dd($patbilldetail);
            return response()->json([
                'status' => TRUE,
                'patientInfo' => $patientInfo,
                'returnItems' => $returnItems,
                'savedItems' => $items->where('fldsave', '0')
                    ->where('fldprint', '0')
                    ->where('fldstatus', 'Done')
                    ->where('flditemqty', '<' , 0),
                'items' => $remainingItems,
                'value' => $queryColumn == "invoice" ? $billnumbergen : $encounterId,
                'patbilldetail' => $patbilldetail,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in return form get patient detail', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getEntryList(Request $request)
    {
    	/*
			select distinct(flditemname) as col from tblpatbilling where fldbillno='PHM-1617' and fldstatus='Cleared' and flditemtype='Extra Items' and (flditemqty-fldretqty)>0
        */

        $flditemtypeTranslation = [
            'Radio' => 'Radio Diagnostics',
            'Others' => 'Other Items',
            'Test' => 'Diagnostic Tests',
            'Services' => 'General Services',
        ];
    	$flditemtype = $request->get('flditemtype');
    	$queryColumn = $request->get('queryColumn');
        $queryValue = $request->get('queryValue');
    	$queryColumn = ($queryColumn == 'encounter') ? 'fldencounterval' : 'fldbillno';
    	$encounterId = $queryValue;
    	if ($queryColumn == 'fldbillno') {
			$encounterId = PatBilling::select('fldencounterval')->where('fldbillno', $queryValue)->first();
    		$encounterId = $encounterId->fldencounterval;
    	}

        $flditemtype = (isset($flditemtypeTranslation[$flditemtype])) ? $flditemtypeTranslation[$flditemtype] : $flditemtype;
		$medicines = PatBilling::select('fldid', 'flditemname', 'flditemno', 'flditemqty', 'flditemrate', 'fldtaxper', 'flddiscper')
        	->with('medicine:fldstockid', 'medicine.batches:fldstockid,fldbatch,fldexpiry')
            ->where([
        		// ['fldencounterval', $encounterId],
				['flditemtype', $flditemtype],
				['fldstatus', 'Cleared'],
                // ['fldsample', 'Waiting'],
        	])->where(function($query) {
        		$query->where('fldsample', 'Waiting')
                    ->orWhere('fldsample', 'Removed');
            })->where(function($query) use ($queryValue, $queryColumn) {
                if ($queryColumn == 'fldbillno')
                    $query->where('fldbillno', $queryValue)->orWhere('fldtempbillno', $queryValue);
                else
                    $query->where($queryColumn, $queryValue);
            })->whereRaw('(flditemqty-COALESCE(fldretqty, 0))>0')
            ->get();

        $returnItems = [];
        if ($medicines) {
            $returns = PatBilling::select('fldparent', \DB::raw("SUM(flditemqty) AS sum"))
                ->whereIn('fldparent', $medicines->pluck('fldid')->toArray())
                ->groupBy('fldparent')
                ->get()
                ->pluck('sum', 'fldparent');

            foreach ($medicines as &$item) {
                $returnQty = (isset($returns[$item->fldid])) ? abs($returns[$item->fldid]) : 0;
                if (($item->flditemqty-$returnQty) <= 0)
                    continue;

                $item->flditemqty = $item->flditemqty-$returnQty;
                $item->returnQty = $returnQty;
                $item->hasReturnQty = (isset($returns[$item->fldid]));
                $item->lessZero = (($item->flditemqty-$returnQty) <= 0);
                $returnItems[] = $item;
            }
        }

        $medicines = ($returnItems) ? $returnItems : $medicines;
        return response()->json($medicines);
    }

    public function returnEntry(Request $request)
    {
        /*
            select fldexpiry from tblentry where fldstockid='Amlodipine besylate- 5 mg (AMCAB-5MG)' and fldbatch='ACT2202'

            select flditemqty,fldretqty from tblpatbilling where flditemname='Amlodipine besylate- 5 mg (AMCAB-5MG)' and fldbillno='PHM-29865' and fldstatus='Cleared' and flditemtype='Medicines' and flditemno in(select fldstockno from tblentry where fldbatch='ACT2202') and (flditemqty-fldretqty)>0

            select fldid,fldencounterval,flditemtype,flditemrate,flditemqty,fldtaxper,flddiscper,fldparent,fldbillno,fldbillingmode,fldvatamt from tblpatbilling where flditemname='Amlodipine besylate- 5 mg (AMCAB-5MG)' and fldbillno='PHM-29865' and fldstatus='Cleared' and flditemtype='Medicines' and flditemno in(select fldstockno from tblentry where fldbatch='ACT2202') and (flditemqty-fldretqty)>0

            select fldstockno from tblentry where fldstockid='Amlodipine besylate- 5 mg (AMCAB-5MG)' and fldbatch='ACT2202' and fldcomp='comp07'

            START TRANSACTION

            select fldstockno from tblentry where fldstockid='Amlodipine besylate- 5 mg (AMCAB-5MG)' and fldbatch='ACT2202' and fldcomp='comp07'

            show full columns from `tblpatbilling`

            INSERT INTO `tblpatbilling` ( `fldencounterval`, `fldbillingmode`, `flditemtype`, `flditemno`, `flditemname`, `flditemrate`, `flditemqty`, `fldtaxper`, `flddiscper`, `fldtaxamt`, `flddiscamt`, `fldditemamt`, `fldorduserid`, `fldordtime`, `fldordcomp`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldbillno`, `fldparent`, `fldprint`, `fldstatus`, `fldalert`, `fldtarget`, `fldpayto`, `fldrefer`, `fldreason`, `fldretbill`, `fldretqty`, `fldsample`, `xyz`, `fldvatamt`, `fldvatper` ) VALUES ( 'E16766GH', 'HealthInsuranceProvider', 'Medicines', 24, 'Amlodipine besylate- 5 mg (AMCAB-5MG)', 5.44, -1, 0, 100, 0, -5.44, 0, 'admin', '2020-10-13 12:14:47.590', 'comp07', NULL, NULL, NULL, '0', NULL, 70426, '0', 'Punched', '1', NULL, NULL, NULL, 'adsasd', 'PHM-29865', 0, 'Waiting', '0', 0, 0 )

            COMMIT

            select fldid,fldordtime,flditemtype,flditemno,flditemname,flditemrate,flditemqty,fldtaxper,flddiscper,fldditemamt as tot,flduserid from tblpatbilling where fldencounterval='E16766GH' and fldsave='0' and fldprint='0' and fldordcomp='comp07' and fldstatus='Punched' and flditemqty<0 and (flditemtype='Medicines' or flditemtype='Surgicals' or flditemtype='Extra Items')

            select distinct(flditemname) as col from tblpatbilling where fldbillno='PHM-29865' and fldstatus='Cleared' and flditemtype='' and (flditemqty-fldretqty)>0
        */
        try {
            $queryColumn = $request->get('queryColumn');
            $queryValue = $request->get('queryValue');
            $retqty = "-" . $request->get('retqty');
            $fldreason = $request->get('fldreason');
            $details = explode("|", $request->get('flditemname'));
            $flditemname = trim($details[0]);
            $fldstockno = $request->get('fldstockno');
            $id = $request->get('id');

            $invoiceNo = null;
            if($queryColumn == "invoice") {
                $invoiceNo = $queryValue;
            } else {
                $invoiceNo = trim($details[2]);
            }
            $oldData = PatBilling::with('medicine')
                ->where(\DB::raw('(flditemqty-COALESCE(fldretqty, 0))'), '>', '0')
                ->where([
                    ['flditemname', $flditemname],
                    ['fldid', $id],
                    ['fldstatus', 'Cleared'],
                ])
                ->where(function($query) use ($invoiceNo) {
                    $query->where('fldbillno', $invoiceNo)->orWhere('fldtempbillno', $invoiceNo);
                })
                ->where(function($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })
                ->whereHas('medicine', function($query) use ($fldstockno) {
                    $query->where('fldstockno', $fldstockno);
                })
                ->first();

            if (!$oldData) {
                return response()->json([
                    'status' => FALSE,
                    'message' => "Data not found.",
                ]);
            }

            $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $oldData->fldencounterval)->first();
            $fldopip = "OP";
            if ($encounterData) {
                $department = Departmentbed::select('fldbed', 'flddept')
                    ->with('department:flddept,fldcateg')
                    ->where('fldbed', $encounterData->fldcurrlocat)
                    ->first();
                if ($department && $department->department) {
                    if ($department->department->fldcateg == 'Patient Ward' || $department->department->fldcateg == 'Emergency')
                        $fldopip = "IP";
                    else
                        $fldopip = "OP";
                }
            }

            $returnQty = 0;
            $chkReturnQty = PatBilling::select(\DB::raw('SUM(flditemqty) as qnty'))
                ->where([
                    ['flditemname', $flditemname],
                    ['fldid', $oldData->fldid],
                    ['fldretbill', $oldData->fldbillno],
                    ['fldstatus', 'Punched'],
                    ['fldsample', 'Waiting'],
                ])
                ->where(function($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })->first()->qnty;

            $returnQty = ($chkReturnQty != null) ? $chkReturnQty : 0;
            if(($oldData->flditemqty - ($returnQty * (-1)) - ($request->retqty)) < 0){
                $maxReturnQty = $oldData->flditemqty - $oldData->fldretqty - ($returnQty * (-1));
                if($maxReturnQty > 0)
                    $message = "Maximum return quantity is " . $maxReturnQty;
                else
                    $message = "You cannot return further.";

                return response()->json([
                    'status' => FALSE,
                    'message' => $message,
                ]);
            }

            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();
            $itemamount = $oldData->flditemrate*$retqty;
            $discount = ($itemamount*$oldData->flddiscper)/100;
            $tax = (($itemamount-$discount)*$oldData->fldtaxper)/100;

            $data = [
                'fldencounterval' => $oldData->fldencounterval,
                'fldbillingmode' => $oldData->fldbillingmode,
                'flditemtype' => $oldData ? $oldData->flditemtype : "",
                'flditemno' => $oldData->flditemno,
                'flditemname' => $flditemname,
                'flditemrate' => Helpers::numberFormat($oldData->flditemrate,'insert'),
                'flditemqty' => $retqty,
                'fldtaxper' => Helpers::numberFormat($oldData->fldtaxper,'insert'),
                'flddiscper' => Helpers::numberFormat($oldData->flddiscper,'insert'),
                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                'fldditemamt' => Helpers::numberFormat(($itemamount-$discount+$tax),'insert'),
                'fldorduserid' => $userid,
                'fldordtime' => $time,
                'fldordcomp' => $computer,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldopip' => $fldopip,
                'fldcomp' => $computer,
                'fldsave' => '0',
                'fldbillno' => NULL,
                'fldparent' => $oldData->fldid,
                'fldprint' => '0',
                'fldstatus' => 'Punched',
                'fldalert' => '1',
                'fldtarget' => NULL,
                'fldpayto' => NULL,
                'fldrefer' => NULL,
                'fldreason' => $fldreason,
                'fldretbill' => $oldData->fldbillno,
                'fldretqty' => 0,
                'fldsample' => 'Waiting',
                'xyz' => '0',
                'fldvatamt' => Helpers::numberFormat(0,'insert'),
                'fldvatper' => Helpers::numberFormat(0,'insert'),
            ];
            $data['fldid'] = PatBilling::insertGetId($data);
            Helpers::logStack(["Pat billing created", "Event"], ['current_data' => $data]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Data saved.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in return form return entry', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function saveAndBill(Request $request){
        // dd($request->all());
        \DB::beginTransaction();
        try{
            $queryColumn = $request->get('queryColumn');
            $queryValue = $request->get('queryValue');
            $authorizedby = $request->get('authorizedby');
            $queryColumn = ($queryColumn == 'encounter') ? 'fldencounterval' : 'fldbillno';
            $totItemRate = 0;
            $totItemAmount = 0;
            $totDiscamt = 0;
            $totTax = 0;
            $fldencounterval = $queryValue;
            if ($queryColumn == 'fldbillno') {
                $fldencounterval = PatBilling::select('fldencounterval')->where('fldbillno', $queryValue)->first();
                if ($fldencounterval)
                    $fldencounterval = $fldencounterval->fldencounterval;
                else
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Invalid bill number.'
                    ]);
            }

            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
            $dateToday = \Carbon\Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
            $billNumberGeneratedString = "RET-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');

            $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $fldencounterval)->first();
            $fldopip = "OP";
            if ($encounterData) {
                $department = Departmentbed::select('fldbed', 'flddept')
                    ->with('department:flddept,fldcateg')
                    ->where('fldbed', $encounterData->fldcurrlocat)
                    ->first();
                if ($department && $department->department) {
                    if ($department->department->fldcateg == 'Patient Ward' || $department->department->fldcateg == 'Emergency')
                        $fldopip = "IP";
                    else
                        $fldopip = "OP";
                }
            }

            $parentbillno = NULL;
            $returnItems = PatBilling::select('fldid', 'fldordtime', 'flditemtype', 'flditemno', 'flditemname', 'flditemrate', 'flditemqty', 'fldtaxper', 'flddiscper', 'fldditemamt', 'flduserid','fldencounterval', 'fldparent', 'fldretbill', 'flddiscamt', 'fldtaxamt')
                ->where([
                    // [$queryColumn, $queryValue],
                    ['fldsave', '0'],
                    ['fldprint', '0'],
                    ['fldstatus', 'Punched'],
                    ['flditemqty', '<', 0],
                ])->where(function($query) use ($queryValue, $queryColumn) {
                    if ($queryColumn == 'fldbillno')
                        $query->where('fldretbill', $queryValue);
                    else
                        $query->where($queryColumn, $queryValue);
                })->where(function($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })->get();


            $datetime = date('Y-m-d H:i:s');
            $orginalIds = $returnItems->pluck('fldparent')->toArray();
            $orginalIds = array_filter($orginalIds);
            if ($orginalIds) {
                PatBillingShare::whereIn('pat_billing_id', $orginalIds)->update([
                    'is_returned' => TRUE,
                    'returned_at' => $datetime,
                ]);
                Helpers::logStack(["Pat billing share updated", "Event"], ['current_data' => [
                    'is_returned' => TRUE,
                    'returned_at' => $datetime,
                ], 'previous_data' => $returnItems]);

            }

            foreach($returnItems as $returnItem){
                $parentbillno = ($parentbillno == NULL) ? $returnItem->fldretbill : $parentbillno;
                $tempBill = PatBilling::where('fldid',$returnItem->fldid)->first();
                $totItemRate += $returnItem->flditemrate;
                $totItemAmount += ($returnItem->flditemrate * $returnItem->flditemqty);
                $totDiscamt += $returnItem->flddiscamt;
                $totTax += $returnItem->fldtaxamt;

                PatBilling::where('fldid', $returnItem->fldid)->update([
                    'fldsave' => 1,
                    'fldstatus' => 'Cleared',
                    'fldbillno' => $billNumberGeneratedString,
                    // 'fldsample' => 'Canceled',
                ]);
                PatBilling::where('fldid', $returnItem->fldparent)->update([
                    'fldretqty' => $tempBill->fldretqty + ($returnItem->flditemqty * (-1)),
                    // 'fldsample' => 'Canceled',
                ]);
                Helpers::logStack(["Pat billing updated", "Event"], ['current_data' => [
                    'fldsave' => 1,
                    'fldstatus' => 'Cleared',
                    'fldbillno' => $billNumberGeneratedString,
                    'fldretqty' => $tempBill->fldretqty + ($returnItem->flditemqty * (-1)),
                ], 'previous_data' => $returnItems]);
                $retrnQty = abs($returnItem->flditemqty);
                $entry = Entry::where([
                    'fldstockno' => $returnItem->flditemno,
                ])->first();
                Entry::where([
                    'fldstockno' => $returnItem->flditemno,
                ])->update([
                    'fldqty' => \DB::raw("(fldqty+{$retrnQty})"),
                ]);
                Helpers::logStack(["Medicine entry updated", "Event"], ['current_data' => ['fldqty' => \DB::raw("(fldqty+{$retrnQty})")], 'previous_data' => $entry]);
            }

            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();
            $hospital_department_id = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $grandTotal = $totItemAmount - $totDiscamt + $totTax;
            $returnAmt = $grandTotal;
            // echo $currentdeposit.'/'.$grandTotal.'/'.$returnAmt; exit;
            $oldPatBillDetail = PatBillDetail::where('fldbillno', $parentbillno)->first();
            $depositdata = PatBillDetail::where('fldencounterval',$fldencounterval)->orderBy('fldid','DESC')->whereNotNull('fldcurdeposit')->where('fldbilltype', 'Credit')->where('fldcomp',Helpers::getCompName())->get();
            $currentdeposit = (isset($depositdata) and $depositdata->isNotEmpty()) ? $depositdata[0]->fldcurdeposit : 0;
            // echo $currentdeposit.'/'.$grandTotal.'/'.$returnAmt; exit;
            $patbilldetail = [
                'fldencounterval' => $fldencounterval,
                'fldbillno' => $billNumberGeneratedString,
                'fldprevdeposit' => Helpers::numberFormat(0,'insert'),
                'flditemamt' => Helpers::numberFormat($totItemAmount,'insert'),
                'fldtaxamt' => Helpers::numberFormat($totTax,'insert'),
                'flddiscountamt' => Helpers::numberFormat($totDiscamt,'insert'),
                'flddiscountgroup' => $oldPatBillDetail->flddiscountgroup,
                'fldchargedamt' => Helpers::numberFormat($grandTotal,'insert'),
                'fldreceivedamt' => Helpers::numberFormat($returnAmt, 2,'insert'),
                'fldcurdeposit' => Helpers::numberFormat(0,'insert'),
                'fldbilltype' => ($request->payment_mode !='') ? $request->payment_mode : $oldPatBillDetail->fldbilltype,
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldbill' => 'INVOICE',
                'fldsave' => '1',
                'xyz' => '0',
                'hospital_department_id' => $hospital_department_id,
                'fldauthorizedby' => $authorizedby,
                'payment_mode' => ($request->payment_mode !='') ? $request->payment_mode : $oldPatBillDetail->payment_mode
            ];

            if ($oldPatBillDetail && $oldPatBillDetail->fldbilltype == 'Credit') {
                $newdeposit =  $currentdeposit-$grandTotal;
                if($request->payment_mode == 'Credit'){
                    $patbilldetail['fldcurdeposit'] = Helpers::numberFormat($newdeposit,'insert');
                    $patbilldetail['fldreceivedamt'] = Helpers::numberFormat(0,'insert');
                    $patbilldetail['fldprevdeposit'] = Helpers::numberFormat($currentdeposit,'insert');
                    $patbilldetail['fldchargedamt'] = Helpers::numberFormat($grandTotal,'insert');
                }else{
                    $patbilldetail['fldprevdeposit'] = Helpers::numberFormat(0,'insert');
                    $patbilldetail['fldcurdeposit'] = Helpers::numberFormat(0,'insert');
                    $patbilldetail['fldreceivedamt'] = Helpers::numberFormat($grandTotal,'insert');
                    $patbilldetail['fldchargedamt'] = Helpers::numberFormat($grandTotal,'insert');
                }
                
                

            }
            // dd($patbilldetail);

            $patDetailsData = PatBillDetail::create($patbilldetail);
            Helpers::logStack(["Pat bill detail created", "Event"], ['current_data' => $patDetailsData]);
            $patDetailsData['location'] = $encounterData->fldcurrlocat;
            DepartmentRevenueService::inserRevenueOrReturn($patDetailsData, "Expenditure");
            $patcount = PatBillCount::create(['fldtempbillno' => $billNumberGeneratedString, 'fldcount' => 1]);
            Helpers::logStack(["Pat bill count created", "Event"], ['current_data' => $patcount]);

            MaternalisedService::insertMaternalisedFiscalPharmacyReturn($fldencounterval,$billNumberGeneratedString,$oldPatBillDetail->payment_mode);

            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Return Successfull!',
                'encounterId' => $fldencounterval,
                'billno' => $billNumberGeneratedString
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in return form save and bill', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => 'Something went wrong!',
            ]);
        }
    }

    // to return all item in bill
    public function getReturnItems(Request $request)
    {
        if (!$request->get('bill_no'))
            return response()->json(['error', 'Please enter bill no']);

        $encounterId = PatBilling::select('fldencounterval')->where('fldbillno', $request->get('bill_no'))->first();
        if ($encounterId) {
            $encounterId = $encounterId->fldencounterval;

            $patientInfo = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank')
                ->where('fldencounterval', $encounterId)
                ->first();
            $returnItems = PatBilling::select('fldid', 'fldordtime', 'flditemtype', 'flditemno', 'flditemname', 'flditemrate', 'flditemqty', 'fldtaxper', 'flddiscper', 'fldditemamt', 'flduserid')
                ->where([
                    ['fldencounterval', $encounterId],
                    ['fldsave', '0'],
                    ['fldprint', '0'],
                    ['fldbillno', $request->get('bill_no')],
                    ['fldstatus', 'Punched'],
                    ['flditemqty', '<', 0],
                ])->where(function ($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })->get();

            return response()->json([
                'status' => TRUE,
                'patientInfo' => $patientInfo,
                'returnItems' => $returnItems,
            ]);

        } else {
            return \response(['error', 'No data']);
        }
    }

    public function returnBill(Request $request)
    {
        try {
            $querytobuilt = $request->get('queryValue');
            $reason = $request->get('reason');
            $dateToday = Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                ->first();
            if(substr_count ($querytobuilt, '-') > 0) {
                $billNumberGenerate = $querytobuilt;
            } else {
                $billNumberGenerate = "%-{$year->fldname}-{$querytobuilt}" . Options::get('hospital_code').'%';
            }
            $patBilling = PatBilling::select('fldbillno', 'fldencounterval')->where('fldbillno','LIKE',$billNumberGenerate)->first();
            if(!$patBilling){
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Invalid bill number.'
                ]);
            }
            $billnumbergen = $patBilling->fldbillno;
            $encounterId = $patBilling->fldencounterval;

            $patientInfo = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank')
                ->where('fldencounterval', $encounterId)
                ->first();

            $results = PatBilling::select('fldid', 'fldbillno', 'fldbillingmode', 'fldordtime', 'flditemtype', 'flditemno', 'flditemname', 'flditemrate', 'flditemqty', 'fldtaxper', 'flddiscper', 'fldditemamt', 'fldorduserid', 'fldsample', 'fldstatus')
                // ->with('medicine:fldstockid', 'medicine.batches:fldstockid,fldbatch,fldexpiry')
                ->with('medicine:fldstockid,fldbatch,fldexpiry')
                ->where([
                    ['fldencounterval', $encounterId],
                    ['fldbillno', $billnumbergen]
                ])
                ->whereIn('fldsample', ['Waiting', 'Removed'])
                ->where('flditemqty', '>' , 0)
                ->where('fldstatus', 'Cleared')
                ->where(function($query) {
                    $query->where('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                })->get();

            $fldopip = "OP";
            $insertdata = [];
            $time = date('Y-m-d H:i:s');
            $userid = Auth::guard('admin_frontend')->user()->flduserid ?? "";
            $computer = Helpers::getCompName() ?? "";
            if($results && count($results) > 0) {
                $returns = PatBilling::select('fldparent', DB::raw("SUM(flditemqty) AS sum"))
                    ->whereIn('fldparent', $results->pluck('fldid')->toArray())
                    ->groupBy('fldparent')
                    ->get()
                    ->pluck('sum', 'fldparent');

                foreach ($results as $item) {
                    $returnQty = (isset($returns[$item->fldid])) ? abs($returns[$item->fldid]) : 0;
                    if (($actual = $item->flditemqty - $returnQty) <= 0)
                        continue;
                    $retqty = -1 * ($actual);
                    $itemamount = $item->flditemrate*$retqty;
                    $discount = ($itemamount*$item->flddiscper)/100;
                    $tax = (($itemamount-$discount)*$item->fldtaxper)/100;
                    $data = [
                        'fldencounterval' => $encounterId,
                        'fldbillingmode' => $item->fldbillingmode,
                        'flditemtype' => $item->flditemtype,
                        'flditemno' => $item->flditemno,
                        'flditemname' => $item->flditemname,
                        'flditemrate' => Helpers::numberFormat($item->flditemrate,'insert'),
                        'flditemqty' => $retqty,
                        'fldtaxper' => Helpers::numberFormat($item->fldtaxper,'insert'),
                        'flddiscper' => Helpers::numberFormat($item->flddiscper,'insert'),
                        'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                        'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                        'fldditemamt' => Helpers::numberFormat(($itemamount - $discount + $tax),'insert'),
                        'fldorduserid' => $userid,
                        'fldordtime' => $time,
                        'fldordcomp' => $computer,
                        'flduserid' => $userid,
                        'fldtime' => $time,
                        'fldcomp' => $computer,
                        'fldopip' => $fldopip,
                        'fldsave' => '0',
                        'fldbillno' => null,
                        'fldparent' => $item->fldid,
                        'fldprint' => '0',
                        'fldstatus' => 'Punched',
                        'fldalert' => '1',
                        'fldtarget' => NULL,
                        'fldpayto' => NULL,
                        'fldrefer' => NULL,
                        'fldreason' => $reason,
                        'fldretbill' => $item->fldbillno,
                        'fldretqty' => 0,
                        'fldsample' => 'Waiting',
                        'xyz' => '0',
                        'fldvatamt' => Helpers::numberFormat(0,'insert'),
                        'fldvatper' => Helpers::numberFormat(0,'insert'),
                    ];
                    $data['fldid'] = PatBilling::insertGetId($data);
                    Helpers::logStack(["Pat billing created", "Event"], ['current_data' => $data]);
                    $insertdata[] = $data;
                }
            }
            $billtype = PatBillDetail::select('fldbilltype', 'fldcurdeposit')->where('fldbillno',$billnumbergen)->first();
            return response()->json([
                'status' => TRUE,
                'message' => 'Data saved.',
                'data' => $insertdata,
                'patientInfo' => $patientInfo,
                'value' => $billnumbergen,
                'billtype' => $billtype->fldbilltype,
                'currentdeposit' => $billtype->fldcurdeposit,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in return form return bill', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function changeQuantity(Request $request)
    {
        try {
            $quantity = $request->get('quantity');
            $bill = PatBilling::where('fldid', '=', $request->get('fldid'))->first();

            if(!$bill) {
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Data not found.',
                ]);
            }

            $itemamount = $bill->flditemrate*$quantity;
            $discount = ($itemamount*$bill->flddiscper)/100;
            $tax = ($itemamount*$bill->fldtaxper)/100;

            $update = [
                'flditemqty' => $quantity,
                'fldtaxamt' => $tax ? Helpers::numberFormat($tax,'insert') : 0,
                'flddiscamt' => $discount ? Helpers::numberFormat($discount,'insert') : 0,
                'fldditemamt' => Helpers::numberFormat(($itemamount-$discount+$tax),'insert'),
            ];

            PatBilling::where('fldid', $request->get('fldid'))->update($update);
            Helpers::logStack(["Pat billing updated", "Event"], ['current_data' => $update, 'previous_data' => $bill]);
            
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully updated data.',
                'update' => $update,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in return form change quantity', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update data.',
            ]);
        }
    }

}
