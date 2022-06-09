<?php

namespace Modules\Store\Http\Controllers;

use App\Entry;
use App\Purchase;
use App\Supplier;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class PurchaseEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       // if (Permission::checkPermissionFrontendAdmin('view-purchase-entry')) {
            //        $purchases = Purchase::with(['Entry'])->where(['fldpurtype' => 'Credit Payment', 'fldbillno' => '1111o', 'fldsuppname' => 'OPENING STOCK', 'fldcomp' => 'comp07', 'fldsav' => '1'])->orderBy('fldid', 'DESC')->get();
            //        dd($purchases);

            $data = [];
            $data['fldcomp'] = Helpers::getCompName();

            return view('store::purchaseentry.index', $data);
        // } else {
        //     Session::flash('display_popup_error_success', true);
        //     Session::flash('error_message', 'You are not authorized for this action.');
        //     return redirect()->route('admin.dashboard');
        // }
    }

    public function supplierAddress(Request $request)
    {
        $fldsuppname = $request->fldsuppname;
        $fldpurtype  = $request->fldpurtype;
        $fldbillno = $request->fldbillno;
        $fldcomp  = $request->fldcomp;
        $response = array();

        try {

            $supplier = Supplier::where('fldsuppname', $fldsuppname)->first();

            $response['fldsuppaddress'] = '';
            if ($supplier) {
                $response['fldsuppaddress'] = $supplier->fldsuppaddress;
            }

            $purchases = Purchase::with(['Entry'])->where(['fldpurtype' => $fldpurtype, 'fldbillno' => $fldbillno, 'fldsuppname' => $fldsuppname, 'fldcomp' => $fldcomp, 'fldsav' => '1'])->orderBy('fldid', 'DESC')->get();
//            $purchases = Purchase::with(['Entry'])->where(['fldpurtype' => 'Credit Payment', 'fldbillno' => '1111o', 'fldsuppname' => 'OPENING STOCK', 'fldcomp' => 'comp07', 'fldsav' => '1'])->orderBy('fldid', 'DESC')->get();
            $table = '<thead>';
            $table .= '<th></th>';
            $table .= '<th></th>';
            $table .= '<th>Payment</th>';
            $table .= '<th>PurDate</th>';
            $table .= '<th>Invoice</th>';
            $table .= '<th>Supplier</th>';
            $table .= '<th>Code</th>';
            $table .= '<th>Particulars</th>';
            $table .= '<th>Batch</th>';
            $table .= '<th>Expiry</th>';
            $table .= '<th>MRP</th>';
            $table .= '<th>TotalCost</th>';
            $table .= '<th>Margin</th>';
            $table .= '<th>TotQTY</th>';
            $table .= '<th>CasDisc</th>';
            $table .= '<th>CasBon</th>';
            $table .= '<th>QTYBon</th>';
            $table .= '<th>CCost</th>';
            $table .= '<th>NetCost</th>';
            $table .= '<th>DistCost</th>';
            $table .= '<th>SellPr</th>';
            $table .= '<th>User</th>';
            $table .= '<th>DateTime</th>';
            $table .= '<th>Comp</th>';
            $table .= '</thead>';
            $table .= '<tbody>';

            if (count($purchases) > 0) {
                foreach ($purchases as $k => $purchase) {
                    $table .= '<tr>';
                    $table .= '<td>' . ++$k . '</td>';
                    $table .= '<td>' . $purchase->fldid . '</td>';
                    $table .= '<td>' . $purchase->fldpurtype . '</td>';
                    $table .= '<td>' . Carbon::parse($purchase->fldpurdate)->format('m/d/Y') . '</td>';
                    $table .= '<td>' . $purchase->fldbillno . '</td>';
                    $table .= '<td>' . $purchase->fldsuppname . '</td>';
                    $table .= '<td>' . $purchase->fldstockno . '</td>';
                    $table .= '<td>' . $purchase->fldstockid . '</td>';
                    $fldbatch = ($purchase->Entry) ? $purchase->Entry->fldbatch : '';
                    $table .= '<td>' . $fldbatch . '</td>';
                    $fldexpiry = ($purchase->Entry && $purchase->Entry->fldexpiry != '' && $purchase->Entry->fldexpiry != NULL) ? Carbon::parse($purchase->Entry->fldexpiry)->format('m/d/Y') : '';
                    $table .= '<td>' . $fldexpiry . '</td>';
                    $table .= '<td>' . $purchase->fldmrp . '</td>';
                    $table .= '<td>' . $purchase->fldtotalcost . '</td>';
                    $table .= '<td>' . $purchase->fldmargin . '</td>';
                    $table .= '<td>' . $purchase->fldtotalqty . '</td>';
                    $table .= '<td>' . $purchase->fldcasdisc . '</td>';
                    $table .= '<td>' . $purchase->fldcasbonus . '</td>';
                    $table .= '<td>' . $purchase->fldqtybonus . '</td>';
                    $table .= '<td>' . $purchase->fldcarcost . '</td>';
                    $table .= '<td>' . $purchase->fldnetcost . '</td>';
                    $table .= '<td>' . $purchase->flsuppcost . '</td>';
                    $table .= '<td>' . $purchase->fldsellprice . '</td>';
                    $fldtime = ($purchase->fldtime != '' && $purchase->fldtime != NULL) ? Carbon::parse($purchase->fldtime)->format('m/d/Y') : '';
                    $table .= '<td>' . $purchase->flduserid . '</td>';
                    $table .= '<td>' . $fldtime . '</td>';
                    $table .= '<td>' . $purchase->fldcomp . '</td>';
                    $table .= '</tr>';
                }
            }

            $table .= '</tbody>';

            $response['table'] = $table;
            $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function getMedicineFromFldroute(Request $request)
    {
        $fldroute = $request->fldroute;
        $genericbrand = $request->genericbrand;
        $response = array();

        try {
            $html = '<option value=""></option>';

            if ($fldroute == 'oral' || $fldroute == 'liquid' || $fldroute == 'fluid' || $fldroute == 'injection' || $fldroute == 'resp' || $fldroute == 'topical' || $fldroute == 'eye/ear' || $fldroute == 'anal/vaginal') {
                if ($genericbrand == 'generic') {
                    $medbrandgenerics =  DB::select(DB::raw("select * from tblmedbrand where lower(tblmedbrand.fldbrandid) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.fldmaxqty<>-1 and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute=:fldroute) ORDER BY tblmedbrand.fldbrandid ASC"), array('fldroute' => $fldroute));
                    if (count($medbrandgenerics) > 0) {
                        foreach ($medbrandgenerics as $medbrandcgeneric) {
                            $html .= '<option value="' . $medbrandcgeneric->fldbrandid . '">' . $medbrandcgeneric->fldbrandid . '</option>';
                        }
                    }
                } elseif ($genericbrand == 'brand') {
                    $medbrandbrands = DB::select(DB::raw("select * from tblmedbrand where lower(tblmedbrand.fldbrand) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.fldmaxqty<>-1 and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute=:fldroute) ORDER BY tblmedbrand.fldbrand ASC"), array('fldroute' => $fldroute));
                    if (count($medbrandbrands) > 0) {
                        foreach ($medbrandbrands as $medbrandbrand) {
                            $html .= '<option value="' . $medbrandbrand->fldbrandid . '">' . $medbrandbrand->fldbrand . '</option>';
                        }
                    }
                }
            } else if ($fldroute == 'suture' || $fldroute == 'msurg' || $fldroute == 'ortho') {
                $surgbrands = DB::select(DB::raw("select * from tblsurgbrand where lower(tblsurgbrand.fldbrandid) like '%' and tblsurgbrand.fldactive='Active' and tblsurgbrand.fldmaxqty<>-1 and tblsurgbrand.fldsurgid in(select tblsurgicals.fldsurgid from tblsurgicals where tblsurgicals.fldsurgcateg=:fldroute) ORDER BY tblsurgbrand.fldbrandid ASC"), array('fldroute' => $fldroute));
                if (count($surgbrands) > 0) {
                    foreach ($surgbrands as $surgbrand) {
                        $html .= '<option value="' . $surgbrand->fldbrandid . '">' . $surgbrand->fldbrandid . '</option>';
                    }
                }
            } else if ($fldroute == 'extra') {
                $extrabrands = DB::select(DB::raw("select * from tblextrabrand where lower(tblextrabrand.fldbrandid) like '%' and tblextrabrand.fldactive='Active' and tblextrabrand.fldmaxqty<>-1 ORDER BY tblextrabrand.fldbrandid ASC"));
                if (count($extrabrands) > 0) {
                    foreach ($extrabrands as $extrabrand) {
                        $html .= '<option value="' . $extrabrand->fldbrandid . '">' . $extrabrand->fldbrandid . '</option>';
                    }
                }
            }

            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function ShowAllPurchaselistFromComp(Request $request)
    {
        $response = array();
        $fldcomp = $request->fldcomp;
//       $fldcomp = 'comp07';
        try {
            $purchases = Purchase::with(['Entry'])->where(['fldcomp' => $fldcomp, 'fldsav' => '1'])->orderBy('fldid', 'DESC')->get();
            $table = '<thead>';
            $table .= '<th></th>';
            $table .= '<th></th>';
            $table .= '<th>Payment</th>';
            $table .= '<th>PurDate</th>';
            $table .= '<th>Invoice</th>';
            $table .= '<th>Supplier</th>';
            $table .= '<th>Code</th>';
            $table .= '<th>Particulars</th>';
            $table .= '<th>Batch</th>';
            $table .= '<th>Expiry</th>';
            $table .= '<th>MRP</th>';
            $table .= '<th>TotalCost</th>';
            $table .= '<th>Margin</th>';
            $table .= '<th>TotQTY</th>';
            $table .= '<th>CasDisc</th>';
            $table .= '<th>CasBon</th>';
            $table .= '<th>QTYBon</th>';
            $table .= '<th>CCost</th>';
            $table .= '<th>NetCost</th>';
            $table .= '<th>DistCost</th>';
            $table .= '<th>SellPr</th>';
            $table .= '<th>User</th>';
            $table .= '<th>DateTime</th>';
            $table .= '<th>Comp</th>';
            $table .= '</thead>';
            $table .= '<tbody>';

            if (count($purchases) > 0) {
                foreach ($purchases as $k => $purchase) {
                    $table .= '<tr>';
                    $table .= '<td>' . ++$k . '</td>';
                    $table .= '<td>' . $purchase->fldid . '</td>';
                    $table .= '<td>' . $purchase->fldpurtype . '</td>';
                    $table .= '<td>' . Carbon::parse($purchase->fldpurdate)->format('m/d/Y') . '</td>';
                    $table .= '<td>' . $purchase->fldbillno . '</td>';
                    $table .= '<td>' . $purchase->fldsuppname . '</td>';
                    $table .= '<td>' . $purchase->fldstockno . '</td>';
                    $table .= '<td>' . $purchase->fldstockid . '</td>';
                    $fldbatch = ($purchase->Entry) ? $purchase->Entry->fldbatch : '';
                    $table .= '<td>' . $fldbatch . '</td>';
                    $fldexpiry = ($purchase->Entry && $purchase->Entry->fldexpiry != '' && $purchase->Entry->fldexpiry != NULL) ? Carbon::parse($purchase->Entry->fldexpiry)->format('m/d/Y') : '';
                    $table .= '<td>' . $fldexpiry . '</td>';
                    $table .= '<td>' . $purchase->fldmrp . '</td>';
                    $table .= '<td>' . $purchase->fldtotalcost . '</td>';
                    $table .= '<td>' . $purchase->fldmargin . '</td>';
                    $table .= '<td>' . $purchase->fldtotalqty . '</td>';
                    $table .= '<td>' . $purchase->fldcasdisc . '</td>';
                    $table .= '<td>' . $purchase->fldcasbonus . '</td>';
                    $table .= '<td>' . $purchase->fldqtybonus . '</td>';
                    $table .= '<td>' . $purchase->fldcarcost . '</td>';
                    $table .= '<td>' . $purchase->fldnetcost . '</td>';
                    $table .= '<td>' . $purchase->flsuppcost . '</td>';
                    $table .= '<td>' . $purchase->fldsellprice . '</td>';
                    $fldtime = ($purchase->fldtime != '' && $purchase->fldtime != NULL) ? Carbon::parse($purchase->fldtime)->format('m/d/Y') : '';
                    $table .= '<td>' . $purchase->flduserid . '</td>';
                    $table .= '<td>' . $fldtime . '</td>';
                    $table .= '<td>' . $purchase->fldcomp . '</td>';
                    $table .= '</tr>';
                }
            }

            $table .= '</tbody>';

            $response['table'] = $table;
            $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function checkExpiry(Request $request) {
        $fldstockid = $request->fldstockid;
        $batch = $request->batch;
        $response = array();

        try {
            $expiryDate = Entry::select('fldexpiry')->where(['fldstockid' => $fldstockid, 'fldbatch' => $batch])->first();
//            $expiryDate = Entry::select('fldexpiry')->where(['fldstockid' => 'Tinidazole- 500 mg(FREE)', 'fldbatch' => 'T-75101'])->first();
            $expirydatevalue = ($expiryDate) ? Carbon::parse($expiryDate->fldexpiry)->format('Y-m-d') : '';
            $response['expirydatevalue'] = $expirydatevalue;
            $response['message'] = 'success';
        } catch (\Exception $e) {
            $response['errormessage'] = $e->getMessage();
            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }


        return json_encode($response);
    }

    public function exportPdfReprint($fldreference)
    {
        $data = [];
        $purchaseEntries = Purchase::where('fldreference', $fldreference)->get();

        $subtotalcost = '';
        $totalparticularcost = [];
        if(count($purchaseEntries) > 0) {
            foreach($purchaseEntries as $k=>$purchaseEntry) {
                $totalparticularcost[$k] = $purchaseEntry->fldtotalqty * $purchaseEntry->flsuppcost;
            }
        }

        $subtotalcost = array_sum($totalparticularcost);
        $tax = '0.00';
        $extrachargediscount = '0.00';
        $grandtotalcost = $subtotalcost + $tax + $extrachargediscount;

        $data['purchaseentries'] = $purchaseEntries;
        $data['fldreference'] = $fldreference;
        $data['subtotalcost'] = $subtotalcost;
        $data['tax'] = $tax;
        $data['grandtotalcost'] = $grandtotalcost;
        $data['extrachargediscount'] = $extrachargediscount;
        $pdf = view('store::layouts.pdf.purchasereprintpdf', $data);
        $pdf->setpaper('a4', 'landscape');


        return $pdf->stream('purchasereprintpdf.pdf');
    }

    public function netUnitCostCalc(Request $request) {
        $fldtotalcost = $request->fldtotalcost;
        $fldtotalqty = $request->fldtotalqty;
        $response = array();

        try {
            if($fldtotalcost != 0 && $fldtotalqty != 0) {
                $fldnetcost = ($fldtotalcost / $fldtotalqty);
            } else {
                $fldnetcost = 0;
            }

            $response['fldnetcost'] = $fldnetcost;
            $response['message'] = 'success';
        } catch(\Exception $e) {
            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function savePurchaseData(Request $request) {

        $response = array();

        try {
            $purchaseData = $request->all();
            unset($purchaseData['_token']);
            $fldroute = $request->fldroute;
            unset($purchaseData['fldroute']);
            unset($purchaseData['currsellprice']);

            $purchaseData['fldcategory'] = '';
            if ($fldroute == 'oral' || $fldroute == 'liquid' || $fldroute == 'fluid' || $fldroute == 'injection' || $fldroute == 'resp' || $fldroute == 'topical' || $fldroute == 'eye/ear' || $fldroute == 'anal/vaginal') {
                $purchaseData['fldcategory'] = 'Medicines';
            } else if ($fldroute == 'suture' || $fldroute == 'msurg' || $fldroute == 'ortho') {
                $purchaseData['fldcategory'] = 'Surgicals';
            } else if ($fldroute == 'extra') {
                $purchaseData['fldcategory'] = 'Extra Items';
            }
            $flduserid = Helpers::getCurrentUserName();
            $fldcomp = Helpers::getCompName();
            $purchaseData['flduserid'] = $flduserid;
            $purchaseData['fldcomp'] = $fldcomp;
            $purchaseData['fldsav'] = false;
            $purchaseData['fldchk'] = false;
            $purchaseData['xyz'] = false;
            $purchaseData['fldreference'] = 'PUR';

            Purchase::insert($purchaseData);

            $latestpurchases = Purchase::with(['Entry'])->where(['fldpurtype' => $purchaseData['fldpurtype'], 'fldbillno' => $purchaseData['fldbillno'], 'fldsuppname' => $purchaseData['fldsuppname'], 'fldcomp' => $fldcomp])->get();

            $table = '<thead>';
            $table .= '<th></th>';
            $table .= '<th></th>';
            $table .= '<th>Payment</th>';
            $table .= '<th>PurDate</th>';
            $table .= '<th>Invoice</th>';
            $table .= '<th>Supplier</th>';
            $table .= '<th>Code</th>';
            $table .= '<th>Particulars</th>';
            $table .= '<th>Batch</th>';
            $table .= '<th>Expiry</th>';
            $table .= '<th>MRP</th>';
            $table .= '<th>TotalCost</th>';
            $table .= '<th>Margin</th>';
            $table .= '<th>TotQTY</th>';
            $table .= '<th>CasDisc</th>';
            $table .= '<th>CasBon</th>';
            $table .= '<th>QTYBon</th>';
            $table .= '<th>CCost</th>';
            $table .= '<th>NetCost</th>';
            $table .= '<th>DistCost</th>';
            $table .= '<th>SellPr</th>';
            $table .= '<th>User</th>';
            $table .= '<th>DateTime</th>';
            $table .= '<th>Comp</th>';
            $table .= '</thead>';
            $table .= '<tbody>';

            if(count($latestpurchases) > 1) {
                foreach($latestpurchases as $latestpurchase) {
                    $table .= '<tr>';
                    $table .= '<td></td>';
                    $table .= '<td>' . $latestpurchase->fldid . '</td>';
                    $table .= '<td>' . $latestpurchase->fldpurtype . '</td>';
                    $table .= '<td>' . Carbon::parse($latestpurchase->fldpurdate)->format('m/d/Y') . '</td>';
                    $table .= '<td>' . $latestpurchase->fldbillno . '</td>';
                    $table .= '<td>' . $latestpurchase->fldsuppname . '</td>';
                    $table .= '<td>' . $latestpurchase->fldstockno . '</td>';
                    $table .= '<td>' . $latestpurchase->fldstockid . '</td>';
                    $fldbatch = ($latestpurchase->Entry) ? $latestpurchase->Entry->fldbatch : '';
                    $table .= '<td>' . $fldbatch . '</td>';
                    $fldexpiry = ($latestpurchase->Entry && $latestpurchase->Entry->fldexpiry != '' && $latestpurchase->Entry->fldexpiry != NULL) ? Carbon::parse($latestpurchase->Entry->fldexpiry)->format('m/d/Y') : '';
                    $table .= '<td>' . $fldexpiry . '</td>';
                    $table .= '<td>' . $latestpurchase->fldmrp . '</td>';
                    $table .= '<td>' . $latestpurchase->fldtotalcost . '</td>';
                    $table .= '<td>' . $latestpurchase->fldmargin . '</td>';
                    $table .= '<td>' . $latestpurchase->fldtotalqty . '</td>';
                    $table .= '<td>' . $latestpurchase->fldcasdisc . '</td>';
                    $table .= '<td>' . $latestpurchase->fldcasbonus . '</td>';
                    $table .= '<td>' . $latestpurchase->fldqtybonus . '</td>';
                    $table .= '<td>' . $latestpurchase->fldcarcost . '</td>';
                    $table .= '<td>' . $latestpurchase->fldnetcost . '</td>';
                    $table .= '<td>' . $latestpurchase->flsuppcost . '</td>';
                    $table .= '<td>' . $latestpurchase->fldsellprice . '</td>';
                    $fldtime = ($latestpurchase->fldtime != '' && $latestpurchase->fldtime != NULL) ? Carbon::parse($latestpurchase->fldtime)->format('m/d/Y') : '';
                    $table .= '<td>' . $latestpurchase->flduserid . '</td>';
                    $table .= '<td>' . $fldtime . '</td>';
                    $table .= '<td>' . $latestpurchase->fldcomp . '</td>';
                    $table .= '</tr>';
                }
            }

            $table .= '</tbody>';

            $response['table'] = $table;
            $response['message'] = 'success';
        } catch(\Exception $e) {
            $response['errormessage'] = $e->getMessage();
//            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function savePurchasesPurchasebill(Request $request) {
        $response = array();

        try {

            $response['message'] = 'success';
        } catch(\Exception $e) {
            $response['errormessage'] = $e->getMessage();
//            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function exportPdfSave($billno) {


        $data = [];
        $purchaseEntries = Purchase::where('fldbillno', $billno)->get();

        $subtotalcost = '';
        $totalparticularcost = [];
        if(count($purchaseEntries) > 0) {
            foreach($purchaseEntries as $k=>$purchaseEntry) {
                $totalparticularcost[$k] = $purchaseEntry->fldtotalqty * $purchaseEntry->flsuppcost;
            }
        }

        $subtotalcost = array_sum($totalparticularcost);
        $tax = '0.00';
        $extrachargediscount = '0.00';
        $grandtotalcost = $subtotalcost + $tax + $extrachargediscount;

        $data['purchaseentries'] = $purchaseEntries;
        $data['billno'] = $billno;
        $data['subtotalcost'] = $subtotalcost;
        $data['tax'] = $tax;
        $data['grandtotalcost'] = $grandtotalcost;
        $data['extrachargediscount'] = $extrachargediscount;
        $pdf = view('store::layouts.pdf.purchaseentrysavepdf', $data);
        $pdf->setpaper('a4', 'landscape');

        return $pdf->stream('purchasereprintpdf.pdf');
    }
}
