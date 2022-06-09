<?php

namespace Modules\Purchase\Http\Controllers;

use App\Entry;
use App\Events\StockLive;
use App\Exports\OpeningStockExcelFormatExport;
use App\Exports\PurchaseBillReportExport;
use App\Imports\PurchaseEntryImport;
use App\Order;
use App\PatBillCount;
use App\Purchase;
use App\PurchaseBill;
use App\SupplierDetails;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;
use Excel;
use Illuminate\Support\Facades\DB;

class PurchaseEntryController extends Controller
{
    public function index()
    {
        $data = [
            'date' => date('Y-m-d'),
            'minexpirydate' => (\Carbon\Carbon::now())->addDay()->format('Y-m-d'),
            'delivery_date' => (\Carbon\Carbon::now())->addMonth(3)->format('Y-m-d'),
            'suppliers' => \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get(),
            'routes' => array_keys(array_slice(Helpers::getDispenserRoute(), 0, 12)),
            'orders' => \App\Order::where([
                    'fldsav' => '0',
                    'fldcomp' => Helpers::getCompName(),
                ])->get(),
            'locations' => \App\Orderlocation::select('flditem')->distinct()->get()
        ];

        return view('purchase::purchaseEntry', $data);
    }

    public function getRefrence(Request $request)
    {
        try {

            // $pendingPurchaseEntries = Purchase::select('tblpurchase.fldid',
            // 'tblpurchase.fldnetcost',
            // 'tblpurchase.flsuppcost',
            // 'tblpurchase.fldvatamt',
            // 'tblpurchase.fldtotalqty',
            // 'tblpurchase.fldqtybonus',
            // 'tblpurchase.fldcasdisc',
            // '0  as fldcashbonus',
            // 'tblpurchase.fldcarcost',
            // '0 as fldcurrcost',
            // 'tblpurchase.fldsellprice',
            // 'tblpurchase.fldtotalcost',
            // 'tblpurchase.fldvatamt',
            // 'tblpurchase.fldvat as hasvat')
            //                                 ->leftJoin('tblentry','tblpurchase.fldstockno','=','tblentry.fldstockno')
            //                                 ->where('tblentry.fldsav',0)
            //                                 ->where('tblpurchase.fldbillno',$request->billno)
            //                                 ->where('tblpurchase.fldsuppname',$request->fldsuppname)
            //                                 ->where('tblpurchase.fldpurtype',$request->paymenttype)
            //                                 ->where('tblpurchase.fldisopening',$request->isOpening)
            //                                 ->where('tblpurchase.fldordref',null)
            //                                 ->get();
            $pendingPurchaseEntries = Purchase::select('tblpurchase.*','tblentry.*','tblpurchase.fldvat as hasvat')
                                            ->leftJoin('tblentry','tblpurchase.fldstockno','=','tblentry.fldstockno')
                                            ->where('tblentry.fldsav',0)
                                            ->where('tblpurchase.fldbillno',$request->billno)
                                            ->where('tblpurchase.fldsuppname',$request->fldsuppname)
                                            ->where('tblpurchase.fldpurtype',$request->paymenttype)
                                            ->where('tblpurchase.fldisopening',$request->isOpening)
                                            ->where('tblpurchase.fldordref',null)
                                            ->get();
            // $refDatas = \App\Demand::select('fldpono')
            //                         ->distinct('fldpono')->where([
            //                             'fldsuppname' => $request->get('fldsuppname'),
            //                             // 'fldcomp_order' => Helpers::getCompName(),
            //                         ])->whereNotNull('fldpono')->with('order')->get();
            $refDatas = \App\Order::where([
                                        'fldsuppname' => $request->get('fldsuppname'),
                                    ])
                                    ->where('fldreference','like','PO%')
                                    ->whereNotNull('fldreference')
                                    ->where('fldremqty','!=',0)
                                    ->distinct('fldreference')
                                    ->pluck('fldreference')
                                    ->toArray();
            $ordrefNotSaved = Purchase::where('fldordref','like','PO%')
                                    ->where('fldsav',1)
                                    ->where('fldsuppname',$request->get('fldsuppname'))
                                    ->distinct('fldordref')
                                    ->pluck('fldordref')
                                    ->toArray();
            $allRefDatas = array_unique(array_merge($refDatas,$ordrefNotSaved));
            rsort($allRefDatas, SORT_NATURAL | SORT_FLAG_CASE);
            $allRefDatas = $allRefDatas;
             ;
            return response()->json([
                'status'=> TRUE,
                'message' => 'Fetched reference data.',
                'refDatas' => $allRefDatas,
                'pendingPurchaseEntries' => $pendingPurchaseEntries
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to get reference data.',
            ]);
        }
    }

    public function getPendingPurchaseByRefNo(Request $request){
        try {
            $pendingPurchaseEntries = Purchase::select('tblpurchase.*','tblentry.*','tblpurchase.fldvat as hasvat')
                                            ->leftJoin('tblentry','tblpurchase.fldstockno','=','tblentry.fldstockno')
                                            ->where('tblentry.fldsav',0)
                                            ->where('tblpurchase.fldbillno',$request->billno)
                                            ->where('tblpurchase.fldsuppname',$request->fldsuppname)
                                            ->where('tblpurchase.fldpurtype',$request->paymenttype)
                                            ->where('tblpurchase.fldordref',$request->refNo)
                                            ->where('tblpurchase.fldisopening',$request->isOpening)
                                            ->get();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Fetched reference data.',
                'pendingPurchaseEntries' => $pendingPurchaseEntries
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to get reference data.',
            ]);
        }
    }

    public function getPendingOpeningStocks(Request $request){
        try {
            $pendingPurchaseEntries = Purchase::leftJoin('tblentry','tblpurchase.fldstockno','=','tblentry.fldstockno')
                                            ->where('tblentry.fldsav',0)
                                            ->where('tblpurchase.fldisopening',1)
                                            ->get();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Success',
                'pendingPurchaseEntries' => $pendingPurchaseEntries
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function getMedicineList(Request $request)
    {
            $reforderno = $request->get('reforderno');
            $orderBy = $request->get('orderBy');
            $route = $request->get('route');
            $is_expired = $request->get('is_expired');
            $expiry = date('Y-m-d H:i:s');
            if ($is_expired)
                $expiry = date('Y-m-d H:i:s', strtotime('-20 years', strtotime($expiry)));

            $medicineRoutes = ['oral', 'liquid', 'fluid', 'injection', 'resp', 'topical', 'eye/ear', 'anal/vaginal',];
            $surgicalRoutes = ['suture', 'msurg', 'ortho',];
            $medcategory = "";
            if (in_array($route, $medicineRoutes))
                $medcategory = "Medicines";
            else if (in_array($route, $surgicalRoutes))
                $medcategory = "Surgicals";
            else
                $medcategory = "Extra Items";

            $table = "tblmedbrand";
            $drugJoin = "INNER JOIN tbldrug ON tblmedbrand.flddrug=tbldrug.flddrug";
            if ($medcategory == 'Surgicals') {
                $table = "tblsurgbrand";
                $drugJoin = "INNER JOIN tblsurgicals ON $table.fldsurgid=tblsurgicals.fldsurgid";
            } elseif ($medcategory == 'Extra Items') {
                $table = "tblextrabrand";
                $drugJoin = "";
            }

            $data = \DB::select("
                SELECT tblorder.flditemname, tblorder.fldqty, tblorder.fldremqty, tblorder.fldid, tblorder.fldrate
                FROM tblorder
                WHERE
                    tblorder.fldreference=?
                    -- AND
                    -- tblorder.fldroute=?
                GROUP BY tblorder.flditemname, tblorder.fldqty
                ", [
                    $reforderno,
                    // $route,
            ]);

            return response()->json($data);


    }

    public function save(Request $request)
    {
        // dd($request->all());
        \DB::beginTransaction();
        try {
            $fldstockno = Helpers::getNextAutoId('StockNo', TRUE);
            $fldstockid = $request->get('fldstockid');
            $computer = Helpers::getCompName();
            $fldsellprice = $request->get('fldsellprice');
            $userid = Helpers::getCurrentUserName();
            $time = date('Y-m-d H:i:s');
            $meddata = \App\MedicineBrand::where('fldbrandid',$fldstockid)->first();
            $extradata = \App\ExtraBrand::where('fldbrandid',$fldstockid)->first();
            $surgdata = \App\SurgBrand::where('fldbrandid',$fldstockid)->first();
            if(isset($meddata) and !empty($meddata)){
                $fldcategory = 'Medicines';
            }

            if(isset($extradata) and !empty($extradata)){
                $fldcategory = 'Extra Items';
            }

            if(isset($surgdata) and !empty($surgdata)){
                $fldcategory = 'Surgicals';
            }

            $fldbarcode = $request->get('fldbarcode');
            $fldordref = $request->get('fldreference');

            $fldstatus = \App\Entry::where([
                ['fldstockid', $fldstockid],
                ['fldcomp', $computer],
            ])->max('fldstatus');

            $fldstatus = ($fldstatus) ? ($fldstatus+1) : 1;

            if(isset($request->fldreference) && $request->fldreference != null){
                $orderData = Order::where('fldid',$request->ordfldid)->first();
                // $orderData = Order::where([['fldreference',$request->fldreference],['fldsuppname',$request->fldsuppname],['flditemname',$request->fldstockid]])->first();
                $orderRemainingQty = (isset($orderData->fldremqty)) ? $orderData->fldremqty : 0;
                if($orderRemainingQty <= 0){
                    return response()->json([
                        'status'=> FALSE,
                        'message' => 'No remaining quantity for this reference number'
                    ]);
                }else{
                    if(($orderRemainingQty - $request->fldtotalqty) < 0){
                        return response()->json([
                            'status'=> FALSE,
                            'message' => 'Maximum purchase quantity is '.$orderRemainingQty
                        ]);
                    }
                }
                Order::where('fldid',$request->ordfldid)->update([
                    'fldremqty' => $orderRemainingQty - $request->fldtotalqty
                ]);
            }

            \App\Entry::insert([
                'fldstockno' => $fldstockno,
                'fldstockid' => $fldstockid,
                'fldcategory' => $fldcategory,
                'fldbatch' => $request->get('fldbatch'),
                'fldexpiry' => $request->get('fldexpiry'),
                // 'fldqty' => $request->get('fldtotalqty'),
                'fldqty' => $request->get('fldtotalentryqty'),
                // 'fldqty' => $request->get('fldqty'),
                'fldstatus' => $fldstatus,
                'fldsellpr' => $fldsellprice,
                'fldsav' => '0',
                'fldcomp' => $computer,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldisopening' => ($request->has('isOpeningStock') && $request->get('isOpeningStock') == 1) ? 1 : 0,
                'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                'fldbarcode' => $fldbarcode
            ]);

            $data = [];
            $data = [
                'fldcategory' => $fldcategory,
                'fldstockno' => $fldstockno,
                'fldstockid' => $fldstockid,
                'fldmrp' => Helpers::numberFormat($request->get('fldmrp', 0),'insert'),
                'flsuppcost' =>Helpers::numberFormat($request->get('flsuppcost', 0),'insert'),
                'fldcasdisc' => Helpers::numberFormat($request->get('fldcasdisc', 0),'insert'),
                'fldcasbonus' => Helpers::numberFormat($request->get('fldcasbonus', 0),'insert'),
                'fldqtybonus' =>$request->get('fldqtybonus', 0),
                'fldcarcost' => Helpers::numberFormat($request->get('fldcarcost', 0),'insert'),
                'fldnetcost' => Helpers::numberFormat($request->get('fldnetcost', 0),'insert'),
                'fldmargin' => Helpers::numberFormat($request->get('fldmargin', 0),'insert'),
                'fldsellprice' => Helpers::numberFormat($request->get('fldsellprice', 0),'insert'),
                'fldtotalqty' => $request->get('fldtotalqty', 0),
                'fldreturnqty' => $request->get('fldreturnqty', 0),
                'fldtotalcost' => Helpers::numberFormat($request->get('fldtotalcost', 0),'insert'),
                'fldpurdate' => $request->get('fldpurdate'),
                'flduserid' => $userid,
                'fldtime' => date('Y-m-d H:i:s'),
                'fldcomp' => $computer,
                'fldsav' => '1',
                'fldchk' => '0',
                'xyz' => '0',
                'fldvat' => $request->get('fldvatamt') ? 'Yes' : 'No',
                'fldvatamt' => Helpers::numberFormat($request->get('fldvatamt', 0),'insert'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                'fldreference' => null,
                'fldbarcode' => $fldbarcode,
                'fldordref' => $fldordref,
                'fldgrnno' => "GRN000".Helpers::getNextAutoId('GRNNo', TRUE)
            ];
            if($request->has('isOpeningStock') && $request->isOpeningStock == 1){
                $hospitalDepartSession = Helpers::getUserSelectedHospitalDepartmentSession();
                $fiscalYear = Helpers::getFiscalYear();
                $data['fldpurtype'] = "Cash Payment";
                if($hospitalDepartSession !== null){
                    $data += [ 'fldsuppname' => $fiscalYear->fldname." OPENING STOCK - ". $hospitalDepartSession->name ];
                }else{
                    $data += [ 'fldsuppname' => $fiscalYear->fldname." OPENING STOCK" ];
                }
                $data += [ 'fldbillno' => "000" ];

                $data += [ 'fldisopening' => 1 ];
                $data += [ 'fldbatch' => "000" ];
            }else{
                $data += [ 'fldpurtype' => $request->get('fldpurtype') ];
                $data += [ 'fldbillno' => $request->get('fldbillno') ];
                $data += [ 'fldsuppname' => $request->get('fldsuppname') ];
                $data += [ 'fldbatch' => $request->get('fldbatch') ];
                // $data += [ 'fldreference' => NULL ];
            }

            $fldid = \App\Purchase::insertGetId($data);

            \DB::commit();

            return response()->json([
                'status'=> TRUE,
                'data' => $request->all() + [
                    'fldstockno' => $fldstockno,
                    'fldstatus' => $fldstatus,
                    'flduserid' => $userid,
                    'computer' => $computer,
                    'fldtime' => $time,
                    'fldid' => $fldid,
                    'hasvat' => $request->get('fldvatamt') ? 'Yes' : 'No',
                    'suppname' => $data['fldsuppname']
                ],
                'message' => 'Data saved.',
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save data.',
            ]);
        }
    }

    public function finalSave(Request $request)
    {
        $groupvatamt = $request->get('groupvatamt') ? $request->get('groupvatamt')  : 0;
        $grouptaxon = $request->get('grouptaxon') ? $request->get('grouptaxon')  : 0;
        $fldpurtype = $request->get('fldpurtype');
        $fldbillno = $request->get('fldbillno');
        $fldsuppname = $request->get('fldsuppname');
        $fldpurdate = $request->get('fldpurdate');
        $computer = Helpers::getCompName();
        $userid = Helpers::getCurrentUserName();
        $time = date('Y-m-d H:i:s');
        $groupDisc = ($request->has('groupdiscount')) ? $request->groupdiscount : 0;
        $totaltax = ($request->has('totaltax')) ? $request->totaltax : 0;
        $individualtax =  $request->individualtax;

        if($request->has('isOpeningStock') && $request->isOpeningStock == 1){
            // if is opening stock
            \DB::beginTransaction();
            try {
                if($request->has('purchaseIds') && count($request->purchaseIds)>0){
                    $totalamt = 0;
                    // $totaltax = 0;
                    $hospitalDepartSession = Helpers::getUserSelectedHospitalDepartmentSession();
                    $fiscalYear = Helpers::getFiscalYear();
                    $purchaseRefNo = Helpers::getNextAutoId('OpeningStockRefNo', true);
                    $purchaseRefNo = "OPENING-STOCK-".$fiscalYear->fldname."-". $purchaseRefNo;

                    $checkAutoIdAlreadyUsed = Purchase::where('fldreference',$purchaseRefNo)->get();
                    if(count($checkAutoIdAlreadyUsed) > 0){
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'msg' => "Unable to save. Auto Id already used."
                        ]);
                    }
                    if($hospitalDepartSession !== null){
                        $supplyName = "OPENING-STOCK-".$fiscalYear->fldname."-".$hospitalDepartSession->name;
                    }else{
                        $supplyName = "OPENING-STOCK-".$fiscalYear->fldname;
                    }
                    $totalitem = count($request->purchaseIds);
                    foreach($request->purchaseIds as $purchaseId){
                        $purchaseData = Purchase::where('fldid',$purchaseId)->first();
                        $totalamt += $purchaseData->fldtotalcost;
                        // $totaltax += $purchaseData->fldvatamt;
                        Entry::where('fldstockno', $purchaseData->fldstockno)->update([
                            'fldsav' => '1',
                            'fldqty' => ($purchaseData->fldtotalqty + $purchaseData->fldqtybonus),
                        ]);
                        Purchase::where('fldid',$purchaseId)->update([
                            'flduserid' => $userid,
                            'fldtime' => $time,
                            'fldcomp' => $computer,
                            'fldreference' => $purchaseRefNo,
                            'fldsav' => '0'
                        ]);
                    }

                    $sumdiscount = Purchase::where('fldreference',$purchaseRefNo)->sum('fldcasdisc');
                    //dd($sumdiscount);

                    if($request->vatableamt > 0 && $request->nonvatableamt > 0){
                        $dataarray =[
                            'fldsuppname' => $supplyName,
                            'fldpurtype' => $fldpurtype,
                            'fldbillno' => $fldbillno,
                            'fldcategory' => 'PurEntry',
                            'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                            'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                            'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                            'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                            'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                            'fldpurdate' => $fldpurdate,
                            'flduser' => $userid,
                            'fldsav' => '1',
                            'fldreference' => $purchaseRefNo,
                            'xyz' => '0',
                            'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'fldisopening' => 1,
                            'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                            'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                            'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                            'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                        ];

                    }elseif($individualtax > 0){
                        $dataarray =[
                            'fldsuppname' => $supplyName,
                            'fldpurtype' => $fldpurtype,
                            'fldbillno' => $fldbillno,
                            'fldcategory' => 'PurEntry',
                            'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                            'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                            'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                            'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                            'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                            'fldpurdate' => $fldpurdate,
                            'flduser' => $userid,
                            'fldsav' => '1',
                            'fldreference' => $purchaseRefNo,
                            'xyz' => '0',
                            'fldtotalvat' => 0,
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'fldisopening' => 1,
                            'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                            'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                            'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                            'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                        ];
                    }elseif($grouptaxon == 1 && $groupvatamt > 0 ){
                        $dataarray =[
                            'fldsuppname' => $supplyName,
                            'fldpurtype' => $fldpurtype,
                            'fldbillno' => $fldbillno,
                            'fldcategory' => 'PurEntry',
                            'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                            'flddebit' => Helpers::numberFormat($totalamt,'insert'),

                            'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                            'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                            'fldpurdate' => $fldpurdate,
                            'flduser' => $userid,
                            'fldsav' => '1',
                            'fldreference' => $purchaseRefNo,
                            'xyz' => '0',
                            'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                            'fldtotaltax' => 0,
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'fldisopening' => 1,
                            'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                            'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                            'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                            'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                        ];

                    }else{

                        $dataarray =[
                            'fldsuppname' => $supplyName,
                            'fldpurtype' => $fldpurtype,
                            'fldbillno' => $fldbillno,
                            'fldcategory' => 'PurEntry',
                            'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                            'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                            'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                            'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                            'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                            'fldpurdate' => $fldpurdate,
                            'flduser' => $userid,
                            'fldsav' => '1',
                            'fldreference' => $purchaseRefNo,
                            'xyz' => '0',
                            'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'fldisopening' => 1,
                            'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                            'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                            'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                            'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                        ];

                    }

                    PurchaseBill::insert($dataarray);

                }else{


                    \DB::rollBack();
                    return response()->json([
                        'status'=> FALSE,
                        'message' => 'Failed to save data.',
                    ]);
                }

                SupplierDetails::insert([
                    'fldsuppname' => $supplyName,
                    'fldpurreference' => $purchaseRefNo,
                    'fldbillno' => "000",
                    'fldtotalitem' => Helpers::numberFormat($totalitem,'insert'),
                    'fldtotalamt' => Helpers::numberFormat($totalamt,'insert'),
                    'fldpurdate' => $time
                ]);

                \DB::commit();
                //event trigger for live stock
                event(new StockLive(1));
                return response()->json([
                    'status'=> TRUE,
                    'purchaseRefNo' => $purchaseRefNo,
                    'message' => 'Data saved.',
                ]);
            } catch (Exception $e) {
              //  dd($e);
                \DB::rollBack();
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Failed to save data.',
                ]);
            }
        }else{


            \DB::beginTransaction();
            try {




                    $supplyName =$fldsuppname;



                $purchaseBill = [];
                $totalamt = 0;
                // $totaltax = 0;
                $purchaseRefNo = Helpers::getNextAutoId('PurchaseRefNo', true);
                $purchaseRefNo = "PUR-" . Helpers::getNepaliFiscalYear() . "-" . $purchaseRefNo;

                $checkAutoIdAlreadyUsed = Purchase::where('fldreference',$purchaseRefNo)->get();
                if(count($checkAutoIdAlreadyUsed) > 0){
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => "Unable to save. Auto Id already used."
                    ]);
                }
                $totalitem = count($request->purchaseIds);

                foreach($request->purchaseIds as $purchaseId){
                    $purchaseData = Purchase::where('fldid',$purchaseId)->first();
                    $totalamt += $purchaseData->fldtotalcost;
                    // $totaltax += $purchaseData->fldvatamt;
                    Entry::where('fldstockno', $purchaseData->fldstockno)->update([
                        'fldsav' => '1',
                        'fldqty' => ($purchaseData->fldtotalqty + $purchaseData->fldqtybonus),
                    ]);
                    Purchase::where('fldid',$purchaseId)->update([
                        'flduserid' => $userid,
                        'fldtime' => $time,
                        'fldcomp' => $computer,
                        'fldreference' => $purchaseRefNo,
                        'fldsav' => '0'
                    ]);
                }

                $sumdiscount = Purchase::where('fldreference',$purchaseRefNo)->sum('fldcasdisc');
                //dd($sumdiscount);


                if($request->vatableamt > 0 && $request->nonvatableamt > 0){
                    $dataarray =[
                        'fldsuppname' => $supplyName,
                        'fldpurtype' => $fldpurtype,
                        'fldbillno' => $fldbillno,
                        'fldcategory' => 'PurEntry',
                        'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                        'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                        'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                        'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                        'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                        'fldpurdate' => $fldpurdate,
                        'flduser' => $userid,
                        'fldsav' => '1',
                        'fldreference' => $purchaseRefNo,
                        'xyz' => '0',
                        'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                        'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                        'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                    ];

                }elseif($individualtax > 0){
                    $dataarray =[
                        'fldsuppname' => $supplyName,
                        'fldpurtype' => $fldpurtype,
                        'fldbillno' => $fldbillno,
                        'fldcategory' => 'PurEntry',
                        'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                        'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                        'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                        'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                        'fldpurdate' => $fldpurdate,
                        'flduser' => $userid,
                        'fldsav' => '1',
                        'fldreference' => $purchaseRefNo,
                        'xyz' => '0',
                        'fldtotalvat' => 0,
                        'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                        'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                        'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                    ];
                }elseif($grouptaxon == 1 && $groupvatamt > 0 ){
                    $dataarray =[
                        'fldsuppname' => $supplyName,
                        'fldpurtype' => $fldpurtype,
                        'fldbillno' => $fldbillno,
                        'fldcategory' => 'PurEntry',
                        'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                        'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                        'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                        'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                        'fldpurdate' => $fldpurdate,
                        'flduser' => $userid,
                        'fldsav' => '1',
                        'fldreference' => $purchaseRefNo,
                        'xyz' => '0',
                        'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                        'fldtotaltax' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                        'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                        'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                    ];

                }else{

                    $dataarray =[
                        'fldsuppname' => $supplyName,
                        'fldpurtype' => $fldpurtype,
                        'fldbillno' => $fldbillno,
                        'fldcategory' => 'PurEntry',
                        'fldcredit' => Helpers::numberFormat($totalamt,'insert'),
                        'flddebit' => Helpers::numberFormat($totalamt,'insert'),
                        'fldtotaltax' => Helpers::numberFormat($totaltax,'insert'),
                        'fldlastdisc' => $groupDisc ? $groupDisc : 0,
                        'flddiscounted' =>$sumdiscount ? $sumdiscount : 0,
                        'fldpurdate' => $fldpurdate,
                        'flduser' => $userid,
                        'fldsav' => '1',
                        'fldreference' => $purchaseRefNo,
                        'xyz' => '0',
                        'fldtotalvat' => Helpers::numberFormat($groupvatamt,'insert'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        'vatableamount' => Helpers::numberFormat($request->vatableamt,'insert'),
                        'nonvatableamount' => Helpers::numberFormat($request->nonvatableamt,'insert'),
                        'cccharge' => Helpers::numberFormat($request->cccharge,'insert'),
                    ];


                }


                \App\PurchaseBill::insert($dataarray);





                $suppCreditRaw = ($fldpurtype == 'Cash Payment') ? "fldpaiddebit+{$totalamt}" : "fldpaiddebit";
                \App\Supplier::where('fldsuppname', $fldsuppname)->update([
                    'fldpaiddebit' => \DB::raw("fldpaiddebit+{$totalamt}"),
                    'fldleftcredit' => \DB::raw($suppCreditRaw),
                ]);

                SupplierDetails::insert([
                    'fldsuppname' => $fldsuppname,
                    'fldpurreference' => $purchaseRefNo,
                    'fldbillno' => $fldbillno,
                    'fldtotalitem' => $totalitem,
                    'fldtotalamt' => $totalamt,
                    'fldpurdate' => $time
                ]);
            //event trigger for live stock
                event(new StockLive(1));
                \DB::commit();
                return response()->json([
                    'status'=> TRUE,
                    'purchaseRefNo' => $purchaseRefNo,
                    'message' => 'Data saved.',
                ]);
            } catch (Exception $e) {
             //   dd($e);
                \DB::rollBack();
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Failed to save data.',
                ]);
            }
        }
    }

    public function export(Request $request)
    {
        $fldreference = $request->get('fldreference');
        $purchaseEntries = \App\Purchase::where('fldreference', $fldreference)->get();
        $purchaseBillDetails = PurchaseBill::where('fldreference', $fldreference)->first();

        $countdata = PatBillCount::where('fldbillno', $fldreference)->pluck('fldcount')->first();

        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;

        // $updatedata['fldcount'] = $countdata->fldcount + 1;
        if (isset($countdata) and $countdata != '') {
            PatBillCount::where('fldbillno', $fldreference)->update($updatedata);
        } else {
            $insertdata['fldbillno'] = $fldreference;
            $insertdata['fldcount'] = 1;
            PatBillCount::insert($insertdata);
        }
        $billCount = $count;
        $purchasereport =  "select
            fldid,
            fldpurtype,
            fldpurdate,
            fldbillno,
            fldsuppname,
            fldcategory,
            flddrug as generic,
            fldbrand,
            fldstockno,
            flsuppcost,
            fldtotalqty,
            fldnetcost,
            fldtotalcost,
            fldreference,
            fldcarcost,
            fldqtybonus,
            fldstockid as refno,
            fldvatamt,
            fldvolunit,
            fldcasdisc
        from
            tblpurchase
        inner join tblmedbrand on
            tblmedbrand.fldbrandid = tblpurchase.fldstockid
        where
            fldreference = '$fldreference'
        union
        select
            fldid,
            fldpurtype,
            fldpurdate,
            fldbillno,
            fldsuppname,
            fldcategory,
            fldsurgid as generic,
            fldbrand,
            fldstockno,
            flsuppcost,
            fldtotalqty,
            fldnetcost,
            fldtotalcost,
            fldreference,
            fldcarcost,
            fldqtybonus,
            fldbrandid as ref,
            fldvatamt,
            fldvolunit,
            fldcasdisc
        from
            tblpurchase
        inner join tblsurgbrand on
            tblsurgbrand.fldbrandid = tblpurchase.fldstockid
        where
            fldreference = '$fldreference'
        union
        select
            fldid,
            fldpurtype,
            fldpurdate,
            fldbillno,
            fldsuppname,
            fldcategory,
            fldextraid as generic,
            fldbrand,
            fldstockno,
            flsuppcost,
            fldtotalqty,
            fldnetcost,
            fldtotalcost,
            fldreference,
            fldcarcost,
            fldqtybonus,
            fldbrandid as ref,
            fldvatamt,
            fldvolunit,
            fldcasdisc
        from
            tblpurchase
        inner join tblextrabrand on
            tblextrabrand.fldbrandid = tblpurchase.fldstockid
        where
            fldreference = '$fldreference'";
       $purchasereportdata= DB::select(
            $purchasereport
        );
        // dd($purchaseEntries);
        if($purchaseBillDetails && $purchaseEntries)
            return view('purchase::pdf.purchaseentry', compact('purchaseEntries','purchaseBillDetails','billCount','purchasereportdata'));


    }

    public function exportPurchaseBillExcel(Request $request)
    {
        $fldreference = $request->get('fldreference');
        $export = new PurchaseBillReportExport($fldreference);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'PurchaseBillReport.xlsx');
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchaseData = \App\Purchase::where('fldid', $request->get('fldid'))->first();

            if($purchaseData->fldordref != null){
                $order = Order::where([['fldreference',$purchaseData->fldordref],['flditemname',$purchaseData->fldstockid]])->first();
                if(isset($order)){
                    Order::where([['fldreference',$purchaseData->fldordref],['flditemname',$purchaseData->fldstockid]])
                            ->update([
                                'fldremqty' => $order->fldremqty + $purchaseData->fldtotalqty
                            ]);
                }
            }

            \App\Purchase::where('fldid', $request->get('fldid'))->delete();
            DB::commit();

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function downloadExcelFormat(){
        $export = new OpeningStockExcelFormatExport();
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'PurchaseEntryExcel.xlsx');
    }

    public function importPurchaseEntry(Request $request){
        \DB::beginTransaction();
        try {
            $import = new PurchaseEntryImport($request);

            Excel::import($import, $request->file('import-purchase-entry'));
            if(isset($import->data['err_message']) && $import->data['err_message'] == 1){
                \DB::rollBack();
                return response()->json([
                    'data'=>$import->data,
                    'status' => FALSE,
                    'message' => 'Please fill all the fields in the excelsheet.',
                ]);
            }
            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Purchase entry successfully imported',
                'html' => $import->data['html'],
                'subtotal' => $import->data['subtotal'],
                'totaldisc' => $import->data['totaldisc'],
                'totaltax' => $import->data['totaltax'],
                'totalamt' => $import->data['totalamt'],
                'vatableAmt' => $import->data['vatableAmt'],
                'nonvatableAmt' => $import->data['nonvatableAmt'],
                'totalCarryCost' => $import->data['totalCarryCost'],
            ]);
        }catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to import purchase entry.',
                'err_message' => $e->getMessage().' '.$e->getLine(),
            ]);
        }
    }

    public function getParticularCategory($particularName){
        if(isset($particularName)){
            $particulars = [
                'oral' => 'Medicines',
                'liquid' => 'Medicines',
                'fluid' => 'Medicines',
                'injection' => 'Medicines',
                'resp' => 'Medicines',
                'topical' => 'Medicines',
                'eye/ear' => 'Medicines',
                'anal/vaginal' => 'Medicines',
                'suture' => 'Surgicals',
                'msurg' => 'Surgicals',
                'ortho' => 'Surgicals',
                'extra' => 'Extra Items'
            ];
            return $particulars[$particularName];
        }else{
            return "";
        }
    }

    public function checkBatchExpiry(Request $request){
        try{
            $checkBatch = Entry::where([['fldbatch',$request->batch],['fldstockid',$request->particulars]])->orderBy('fldstockno','desc')->get()->take(1);
            if(count($checkBatch) > 0){
                $expiryDate = $checkBatch[0]->fldexpiry;
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Batch Found',
                    'expiryDate' => date('Y-m-d', strtotime($expiryDate))
                ]);
            }else{
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Batch Not Found.',
                ]);
            }
        }catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Batch Not Found.',
            ]);
        }
    }
}
