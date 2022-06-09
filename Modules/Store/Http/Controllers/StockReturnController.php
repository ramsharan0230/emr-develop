<?php

namespace Modules\Store\Http\Controllers;

use App\AutoId;
use App\Entry;
use App\Events\StockLive;
use App\ExtraBrand;
use App\Invid;
use App\MedicineBrand;
use App\Purchase;
use App\StockReturn;
use App\Supplier;
use App\SurgBrand;
use App\Utils\Helpers;
use App\Utils\Storehelpers;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class StockReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'suppliers' => \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get(),
            'routes' => array_keys(array_slice(Helpers::getDispenserRoute(), 0, 12)),
            'date' => date('Y-m-d'),
        ];

        $data['fldcomp'] = Helpers::getCompName();

        return view('store::stockreturn.index', $data);
    }

    /**
     * loads Particulars from tblstockreturn into table
     */
    public function loadParticularsFromStockReturn(Request $request) {
        $response = array();
        $fldcomp = $request->fldcomp;
//       $fldcomp = 'comp07';
        try {
            $stockreturns = StockReturn::with(['Entry'])->where(['fldcomp' => $fldcomp, 'fldsave' => '0'])->orderBy('fldid', 'ASC')->get();

            $tbodycontent = "";
            if (count($stockreturns) > 0) {
                foreach ($stockreturns as $k => $stockreturn) {
                    $tbodycontent .= '<tr>';
                    $tbodycontent .= '<td>' . ++$k . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldid . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldcategory . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldstockid . '</td>';
                    $fldbatch = ($stockreturn->Entry) ? $stockreturn->Entry->fldbatch : '';
                    $tbodycontent .= '<td>' . $fldbatch . '</td>';
                    $fldexpiry = ($stockreturn->Entry && $stockreturn->Entry->fldexpiry != '' && $stockreturn->Entry->fldexpiry != NULL) ? Carbon::parse($stockreturn->Entry->fldexpiry)->format('m/d/Y') : '';
                    $tbodycontent .= '<td>' . $fldexpiry . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldqty . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldcost . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldsuppname . '</td>';
                    $tbodycontent .= '<td>' . $stockreturn->fldreference . '</td>';
                    $tbodycontent .='<td><button class="btn btn-danger" onclick="deleteentry(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    $tbodycontent .= '</tr>';
                }
            }


            $response['tbodycontent'] = $tbodycontent;
            $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function exportParticularsPdf($fldcomp) {
        $data = [];
        $fldcomp = decrypt($fldcomp);
        $stockreturns = StockReturn::with(['Entry'])->where(['fldcomp' => $fldcomp, 'fldsave' => '0'])->orderBy('fldid', 'ASC')->get();

        $data['stockreturns'] = $stockreturns;
        $pdf = view('store::layouts.pdf.stockreturnpdf', $data);
        $pdf->setpaper('a4', 'landscape');

        return $pdf->stream('stockreturn.pdf');
    }

    public function checkstockidintblentry(Request $request) {
        $fldroute = $request->fldroute;
        $genericorbrand = $request->genericbrand;
        $keyword = $request->keyword;
        $fldcomp = $request->fldcomp;
        $response = array();

        try {
            $fldbrandid = null;
            if ($fldroute == 'oral' || $fldroute == 'liquid' || $fldroute == 'fluid' || $fldroute == 'injection' || $fldroute == 'resp' || $fldroute == 'topical' || $fldroute == 'eye/ear' || $fldroute == 'anal/vaginal') {
                if ($genericorbrand == 'generic') {
                    $fldbrandid = MedicineBrand::select('fldbrandid')->where('fldbrandid', $keyword)->first();
                } elseif ($genericorbrand == 'brand') {
                    $fldbrandid = MedicineBrand::select('fldbrandid')->where('fldbrand', $keyword)->first();
                }
            } else if ($fldroute == 'suture' || $fldroute == 'msurg' || $fldroute == 'ortho') {
                    $fldbrandid = SurgBrand::select('fldbrandid')->where('fldbrandid', $keyword)->first();
            } else if ($fldroute == 'extra') {
                    $fldbrandid = ExtraBrand::select('fldbrandid')->where('fldbrandid', $keyword)->first();
            }

            $html = '<option value=""></option>';
            $suppliernameoption = '<option value=""></option>';
            $purchasereferenceoption = '<option  value=""></option>';
            $supplier = null;
            $entry = null;
            $purchase = null;
            if($fldbrandid != '' && $fldbrandid != null) {
                $entry = Entry::where(['fldstockid' => $fldbrandid->fldbrandid, 'fldcomp' => $fldcomp])->where('fldqty', '>', '0')->first();
                if($entry) {
                    $html .= '<option value="'. $entry->fldbatch .'">'. $entry->fldbatch .'</option>';

                    $purchase = Purchase::where('fldstockno', $entry->fldstockno)->first();

                    $fldsuppname = ($purchase) ? $purchase->fldsuppname : '';
                    $fldreference = ($purchase) ? $purchase->fldreference : '';
                    $suppliernameoption .= '<option value="'. $fldsuppname .'">'. $fldsuppname .'</option>';
                    $purchasereferenceoption .= '<option value="'. $fldreference .'">'. $fldreference .'</option>';

                    $supplier = Supplier::where('fldsuppname', $fldsuppname)->first();

                }

            }

            $response['fldexpiry'] = ($entry && $entry->fldexpiry) ? Carbon::parse($entry->fldexpiry)->format('m/d/Y') : '';
            $response['fldstatus'] = ($entry) ? $entry->fldstatus : '';
            $response['fldqty'] = ($entry) ? $entry->fldqty : '';
            $response['suppliernameoption']  = $suppliernameoption;
            $response['fldsuppaddress']  = ($supplier) ? $supplier->fldsuppaddress : '';
            $response['purchasereferenceoption']  = $purchasereferenceoption;
            $response['fldbillno'] = ($purchase) ? $purchase->fldbillno : '';
            $response['fldstockno'] = ($entry) ? $entry->fldstockno : '';
            $response['fldcost'] = ($entry) ? $entry->fldsellpr : '';
            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function insertStockReturn(Request $request) {
        $response = array();

        try {
           $stockreturndata = $request->all();

           unset($stockreturndata['_token']);

           StockReturn::insert($stockreturndata);
           $response['successmessage'] = "Stock return for new particular saved successfully";
           $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            //            $response['errormessage'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function exportPdfReprint(Request $request) {
        $data = [];
        $stockreturns = StockReturn::select('fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldsuppname','fldreference')
                                    ->where([
                                            'fldsave' => 1,
                                            'fldnewreference' => $request->fldnewreference
                                    ])
                                    ->where('fldnewreference','!=',null)
                                    ->orderBy('fldid', 'ASC')
                                    ->groupBy(['fldstockid','fldbatch'])
                                    ->get();
        $data['stockreturns'] = $stockreturns;
        $data['fldnewreference'] = $request->fldnewreference;
        $data['certificate'] = "STOCK RETURN";

        return view('store::layouts.pdf.stockreturnpdf', $data);
        // $pdf = view('store::layouts.pdf.stockreturnpdf', $data);
        // $pdf->setpaper('a4', 'landscape');

        // return $pdf->stream('stockreturn.pdf');
    }

    public function saveStockReturn() {

        try {
            $fldcomp = Helpers::getCompName();
            $stockreturns = StockReturn::with(['Entry'])->where(['fldcomp' => $fldcomp, 'fldsave' => '0'])->orderBy('fldid', 'ASC')->get();

            $invid = Invid::where('fldinvcode', 'SRE')->first();

            if(count($stockreturns) > 0) {
                $fldtype = 'ReferenceNo';
                $autoid = AutoId::where('fldtype', $fldtype)->first();


                if($autoid) {
                    $referenceNo = $autoid->fldvalue;
                    $newreferenceNo = AutoId::where('fldtype', 'ReferenceNo')->update(['fldvalue' => ($referenceNo + 1)]);

                }

                foreach($stockreturns as $stockreturn) {
                    $entry = Entry::where('fldstockno', $stockreturn->fldstockno)->first();
                    if($entry) {
                        $newqty = ($entry->fldqty - $stockreturn->fldqty);
                        $tblentryupdates = Entry::where('fldstockno',  $stockreturn->fldstockno)->update(['fldqty' => $newqty, 'fldsav' => 1, 'xyz' => '0']);
                        $entrieswithqtyzero = DB::select(DB::raw("SELECT * FROM `tblentry` WHERE fldstockid=:fldstockid and fldqty=0 and fldcomp=:fldcomp and fldstatus<>0"), array('fldcomp' => $fldcomp, 'fldstockid' => $stockreturn->fldstockid));
                        $entrieswithqtygreaterthanzero = DB::select(DB::raw("select fldstockno from tblentry where fldstockid=:fldstockid and fldqty>0 and fldcomp=:fldcomp ORDER by fldstatus ASC"), array('fldcomp' => $fldcomp, 'fldstockid' => $stockreturn->fldstockid));

//                         pending confusion tasks here
                    }

                    StockReturn::where('fldid', $stockreturn->fldid)->update(['fldsave' => 1, 'fldnewreference' => 'SRE-'.$referenceNo]);
                }

            }

        } catch(\Exception $e) {
            $error_message = $e->getMessage();
            $error_message = 'sorry something went wrong';
            Session::flash('error_message', $error_message);
        }

        Session::flash('success_message', 'stock returned sucessfully');

        return redirect()->route('inventory.stock-return.index');

    }


    //Stock return funcions added by anish

    public function getRefNo(Request  $request)
    {

        if (!$request->get('fldsuppname')) {
            return \response()->json(['error' => 'Please select supplier']);
        }
        try {
         $references = Purchase::select('fldreference')
                            ->where([
                                        'fldcomp' => Helpers::getCompName(),
                                        'fldsav' => '0',
                                        'fldsuppname' => $request->get('fldsuppname')
                                    ])
                            ->whereRaw('fldtotalqty - fldreturnqty > 0')
                            ->whereNotNull('fldreference')
                            ->groupBy('fldreference')
                            ->get();

         $html = '';
            if ($references) {
                $html .= '<option value="">--Select--</option>';
                foreach ($references as $ref) {
                    $html .= '<option value="' . $ref->fldreference . '">' . $ref->fldreference . '</option>';
                }
            }
            return response()->json($html);

        }catch (\Exception $exception){
            dd($exception);
        }

    }


    public function getMedicine(Request $request)
    {
        if (!$request->get('fldsuppname') || !$request->get('reference')) {
            return \response()->json(['error' => 'Please select supplier and reference']);
        }
        // $category ='';
        // if($request->get('route')){
        //     $category = $this->getParticularCategory($request->get('route'));
        // }
        $medicines = \App\Purchase::select('fldstockid')->distinct('fldstockid')->where([
                                        'fldsuppname' => $request->get('fldsuppname'),
                                        'fldcategory' => $request->get('route'),
                                        'fldreference' => $request->get('reference'),
                                    ])
                                    ->whereRaw('fldtotalqty - fldreturnqty > 0')
                                    ->whereNotNull('fldstockid')
                                    ->groupBy('fldstockid')
                                    ->get();
        $html = '';
        if ($medicines) {
            $html .= '<option value="">--Select--</option>';
            foreach ($medicines as $med) {
                $html .= '<option value="' . $med->fldstockid . '">' . $med->fldstockid . '</option>';
            }
        }
        return response()->json($html);
    }

    public function getMedicineWithReference(Request $request)
    {
        if (!$request->get('fldsuppname')) {
            return \response()->json(['error' => 'Please select supplier']);
        }
        $medicines = \App\Purchase::select('fldstockid','fldreference','fldcategory','fldbillno')->where([
            'fldsuppname' => $request->get('fldsuppname'),
            'fldcomp'=>Helpers::getCompName()
        ] )->whereRaw('fldtotalqty - fldreturnqty > 0')->whereNotNull('fldstockid')->whereNotNull('fldreference')
            ->groupBy(['fldstockid','fldbatch','fldreference'])
            ->get();
        $html = '';
        if ($medicines) {
            $html .= '<option value="">--Select--</option>';
            foreach ($medicines as $med) {
                $html .= '<option data-ref="'.$med->fldreference.'" data-categ="'.$med->fldcategory.'" value="' . $med->fldstockid . '">' . $med->fldstockid . '('.$med->fldreference.')</option>';
            }
        }

        $pendingStockReturns = StockReturn::where('fldsuppname',$request->fldsuppname)
            ->where('fldsave',0)
            ->get();
        $pendingReturnsHtml ='';
        if(count($pendingStockReturns) > 0){
            foreach($pendingStockReturns as $stock){
                $pendingReturnsHtml .= '<tr data-fldid="'.$stock->fldid.'">';
                $pendingReturnsHtml .='<td><input type="hidden" name="stockreturnid[]" class="stockreturnid" value="'.$stock->fldid.'">'.$stock->fldtime.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldstockno.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldbatch.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldsuppname.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldstockid.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldcarcost.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldqty.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldcost.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldreference.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->flduserid.'</td>';
                $pendingReturnsHtml .='<td><button class="btn btn-danger" onclick="deleteentry('.$stock->fldid.')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            }
        }

        return response()->json([
            'status'=> TRUE,
            'message' => 'Successfull',
            'html' => $html,
            'pendingStockReturns' => $pendingReturnsHtml
        ]);
        // return response()->json($html);
    }

    public function getBatch(Request $request)
    {
        if (!$request->get('medicine')) {
            return \response()->json(['error' => 'Please select medicine']);
        }

        // $category ='';
        // if($request->get('route')){
        //     $category = $this->getParticularCategory($request->get('route'));
        // }
        $batches = \App\Purchase::with('Entry')->select('fldstockid','fldstockno')->distinct('fldstockid')->where([
            'fldstockid' => $request->get('medicine'),
            'fldcategory' => $request->get('route'),
            'fldsuppname' => $request->get('fldsuppname'),
            'fldreference' => $request->get('reference'),
        ])->whereNotNull('fldstockid')->groupBy('fldstockid')->get();

        $html = '';
        if ($batches) {
            $html .= '<option value="">--Select--</option>';
            foreach ($batches as $med) {
                $html .= '<option value=' . (($med->Entry->fldbatch) ? $med->Entry->fldbatch : '') . '>' . (($med->Entry->fldbatch) ? $med->Entry->fldbatch : '') . '</option>';
            }
        }
        return response()->json($html);
    }


//    public function getExpiry(Request $request)
//    {
//        if (!$request->get('medicine') || !$request->get('batch')) {
//            return \response()->json(['error' => 'Please select medicine and batch']);
//        }
//        // $category ='';
//        // if($request->get('route')){
//        //     $category = $this->getParticularCategory($request->get('route'));
//        // }
//
//        // $expiry = Entry::select('fldexpiry', 'fldqty', 'fldstockno')
//        //     ->where('fldstockid', $request->get('medicine'))
//        //     ->where('fldbatch', $request->get('batch'))
//        //     ->where('fldcategory', $request->get('route'))
//        //     ->where('fldcomp', Helpers::getCompName())
//        //     ->with('pendingStockReturn')
//        //     ->first();
//
//        $entryDetails = Entry::where('fldstockid', $request->get('medicine'))
//                        ->where('fldbatch', $request->get('batch'))
//                        ->where('fldcategory', $request->get('route'))
//                        ->where('fldcomp', Helpers::getCompName())
//                        ->with('pendingStockReturn','hasPurchase')
//                        ->get();
//
//        if (count($entryDetails) > 0) {
//            $qty = 0;
//            $expiry = "";
//            $netcost = 0;
//            if($entryDetails[0]->hasPurchase){
//                $netcost = $entryDetails[0]->hasPurchase->fldnetcost;
//            }
//            foreach($entryDetails as $entryDetail){
//                $qty += $entryDetail->fldqty;
//                if(count($entryDetail->pendingStockReturn) > 0){
//                    $qty = $qty - $entryDetail->pendingStockReturn->sum('fldqty');
//                }
//                $expiry = $entryDetail->fldexpiry;
//            }
//            return \response()->json([
//                'expiry' => Carbon::parse($expiry)->format('Y-m-d'),
//                'qty' => $qty,
//                'netcost' => $netcost,
//            ]);
//        }
//
//        return \response()->json('');
//
//        // $pendingretqty = 0;
//        // if(count($expiry->pendingStockReturn) > 0){
//        //     $pendingretqty = $expiry->pendingStockReturn->sum('fldqty');
//        // }
//
//        // return response([
//        //     'expiry' => $expiry,
//        //     'pendingretqty' => $pendingretqty
//        // ]);
//
//    }

    public function getExpiry(Request $request)
    {
        if (!$request->get('medicine') || !$request->get('batch')) {
            return \response()->json(['error' => 'Please select medicine and batch']);
        }
        $reference = $request->reference;
        $category ='';
        if($request->get('route')){
            $category = $request->get('route');
            // $category = $this->getParticularCategory($request->get('route'));
        }

        $entryDetail = Entry::select('fldexpiry', 'fldqty', 'fldstockno')
            ->where('fldstockid', $request->get('medicine'))
            ->where('fldbatch', $request->get('batch'))
            ->where('fldcategory', $category)
            ->whereHas('hasPurchase', function($q) use ($reference){
                $q->where('fldreference', $reference);
            })
            ->with('hasPurchase:fldstockno,fldcarcost,fldstockid,fldreference,fldnetcost','pendingStockReturn','stockReturn')
            ->first();

        if ($entryDetail) {
            $qty = 0;
            $fldcost = 0;
            if($entryDetail->hasPurchase){
                $fldcost = $entryDetail->hasPurchase->fldnetcost;
            }
            $qty += $entryDetail->fldqty;
            if(count($entryDetail->pendingStockReturn) > 0){
                $qty = $qty - $entryDetail->pendingStockReturn->sum('fldqty');
            }
            $expiry = $entryDetail->fldexpiry;

            $stockReturned = StockReturn::select(\DB::raw('GROUP_CONCAT(fldid) as fldids'),'fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldsuppname','fldreference')
                ->where('fldreference', $request->reference)
                ->where('fldstockid', $request->get('medicine'))
                ->where('fldbatch', $request->get('batch'))
                ->where('fldcomp', Helpers::getCompName())
                ->where('fldsave',0)
                ->with('Entry')
                ->groupBy(['fldstockid','fldbatch'])
                ->get();
            $html = '';
            if(count($stockReturned)) {
                foreach ($stockReturned as $stock) {
                    $html .= '<tr data-fldid="' . $stock->fldids . '">';
                    $html .= '<td><input type="checkbox" class="js-stockreturn-select-class-checkbox" data-fldid="' . $stock->fldids . '"></td>';
                    $html .= '<td>' . $stock->Entry->fldcategory . '</td>';
                    $html .= '<td>' . $stock->fldstockid . '</td>';
                    $html .= '<td>' . $stock->fldbatch . '</td>';
                    $html .= '<td>' . $stock->Entry->fldexpiry . '</td>';
                    $html .= '<td>' . $stock->fldqty . '</td>';
                    $html .= '<td>' . $stock->fldcost . '</td>';
                    $html .= '<td>' . $stock->fldsuppname . '</td>';
                    $html .= '<td>' . $stock->fldreference . '</td>';
                    $html .= '<td><button class="btn btn-danger" onclick="deleteentry(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                }
            }

            return \response()->json([
                'expiry' => Carbon::parse($expiry)->format('Y-m-d'),
                'qty' => $qty,
                'fldstockno' => $entryDetail->fldstockno,
                'fldcost' => $fldcost,
                'html'=>$html
            ]);
        }

        return \response()->json('');

        // return response()->json($expiry);

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


    public function saveStockReturns(Request $request)
    {
        if (!$request->get('fldsuppname') || !$request->get('medicine') || !$request->get('reference')) {
            return \response()->json(['error', 'Please select Supplier,Medicine and batch']);
        }
        $reference = $request->reference;
        $category ='';
        if($request->get('route')){
            $category = $request->get('route');
            // $category = $this->getParticularCategory($request->get('route'));
        }
        $entryDetails = Entry::where('fldstockid', $request->get('medicine'))
                        ->where('fldbatch', $request->get('batch'))
                        ->where('fldcategory', $category)
                        ->where('fldcomp', Helpers::getCompName())
                        ->whereHas('hasPurchase', function($q) use ($reference){
                            $q->where('fldreference', $reference);
                        })
                        ->with('pendingStockReturn','hasPurchase')
                        ->get();

        $qty = $request->retqty;
        foreach($entryDetails as $entryDetail){
            $netcost = 0;
            if($entryDetail->hasPurchase){
                $netcost = $entryDetail->hasPurchase->fldnetcost;
            }
            $tempqty = $entryDetail->fldqty;
            if(count($entryDetail->pendingStockReturn) > 0){
                $tempqty = $tempqty - $entryDetail->pendingStockReturn->sum('fldqty');
            }
            if($tempqty > 0){
                if($qty > $tempqty){
                    $returnedQty = $tempqty;
                }else{
                    $returnedQty = $qty;
                }
                $qty = $qty - $returnedQty;
                if($returnedQty > 0){
                    $data = [
                        'fldstockno' => $entryDetail->fldstockno,
                        'fldstockid' => $request->get('medicine'),
                        'fldbatch' => $request->get('batch'),
                        'fldqty' => $returnedQty,
                        'fldcost' => $netcost * $returnedQty,
                        'fldsuppname' => $request->get('fldsuppname'),
                        'fldreference' => $request->get('reference'),
                        'fldcategory' => $request->get('reason'),
                        'flduserid' => Helpers::getCurrentUserName(),
                        'fldtime' => Carbon::now(),
                        'fldcomp' => Helpers::getCompName(),
                        'xyz' => 0,
                        'fldsave' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                    $stock =StockReturn::create($data);
                }
            }
        }

        $stockReturned = StockReturn::select(\DB::raw('GROUP_CONCAT(fldid) as fldids'),'fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldsuppname','fldreference')
                                    ->where('fldreference', $request->reference)
                                    ->where('fldcomp', Helpers::getCompName())
                                    ->where('fldsave',0)
                                    ->with('Entry')
                                    ->groupBy(['fldstockid','fldbatch'])
                                    ->get();
        $html = '';
        if(count($stockReturned)){
            foreach ($stockReturned as $stock) {
                $html .= '<tr data-fldid="'.$stock->fldids.'">';
                $html .= '<td><input type="checkbox" class="js-stockreturn-select-class-checkbox" data-fldid="'.$stock->fldids.'"></td>';
                $html .='<td>'.$stock->Entry->fldcategory.'</td>';
                $html .='<td>'.$stock->fldstockid.'</td>';
                $html .='<td>'.$stock->fldbatch.'</td>';
                $html .='<td>'.$stock->Entry->fldexpiry.'</td>';
                $html .='<td>'.$stock->fldqty.'</td>';
                $html .='<td>'.$stock->fldcost.'</td>';
                $html .='<td>'.$stock->fldsuppname.'</td>';
                $html .='<td>'.$stock->fldreference.'</td>';
                $html .='<td><button class="btn btn-danger" onclick="deleteentry(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            }
        }
        return \response()->json($html);
    }

    public  function finalSave(Request $request){
        if(!$request->get('returnids'))
        {
            return \response()->json(['error','Please select row first!']);
        }

        try {
            DB::beginTransaction();
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $stockReturnReference = 'SRE-'.$fiscalYear."-".Helpers::getNextAutoId('ReferenceNo',TRUE);
            if($request->returnids){
                foreach ($request->returnids as $stockreturn){
                    $stk = StockReturn::where('fldid',$stockreturn)->first();
                    $purchase = Purchase::where('fldstockno', $stk->fldstockno)->first();

                    if ($purchase) {
                        $returnQty = ($purchase->fldreturnqty + $stk->fldqty);
                        $purchase->where('fldid', $purchase->fldid)->update(['fldreturnqty'=>$returnQty]);
                    }

                    $entry = Entry::where('fldstockno',$stk->fldstockno)->first();
                    if($entry) {
                        $quantity = ($entry->fldqty)-($stk->fldqty);
                        $entry->where('fldstockno',$entry->fldstockno)->update(['fldqty'=>$quantity]);
                    }

                    $stk->where('fldid',$stk->fldid)->update([
                                                                'fldsave'=>1,
                                                                'fldnewreference'=>$stockReturnReference
                                                            ]);
                }
            }
            $html = $this->getPendingStockreturns($request);

            //event trigger for live stock
            event(new StockLive(1));
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
        }

        return response([
            'status' => true,
            'fldnewreference' => $stockReturnReference,
            'html' => $html
        ]);
        // return \response()->json('Saved successfully');
    }

    // public  function finalSave(Request $request){
    //     if(!$request->get('batch') || !$request->get('expiry') || !$request->get('medicine') )
    //     {
    //         return \response()->json(['error','Please select Batch,expiry ,medicine']);
    //     }
    //     $stockreturn = StockReturn::where('fldsave', 0)->get();

    //     try {
    //         DB::beginTransaction();
    //         if($stockreturn){
    //             foreach ($stockreturn as $stk){

    //                 $purchase = Purchase::where('fldsuppname',$stk->fldsuppname)
    //                     ->where('fldstockid', $stk->fldstockid)
    //                     ->where('fldstockno', $stk->fldstockno)
    //                     ->where('fldcategory', $stk->fldstockno)
    //                     ->where('fldreference', $stk->fldreference)->first();

    //                 if ($purchase) {$html .='<td><button class="btn btn-danger" onclick="deleteentry('.$pendingStockReturn->fldids.')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

    //                 $entry = Entry::where('fldstockid',$stk->fldstockid)
    //                     ->where('fldstockno',$stk->fldstockno)
    //                     ->where('fldcategory',$stk->fldcategory)
    //                     ->first();

    //                 $quantity = ($entry->fldqty)-($stk->fldqty);
    //                 $entry->where('fldstockno',$entry->fldstockno)
    //                     ->update(['fldqty'=>$quantity]);
    //                 $stk->where('fldid',$stk->fldid)->update(['fldsave'=>1]);
    //             }
    //             DB::commit();
    //         }

    //     }catch (\Exception $exception){
    //         DB::rollBack();
    //         dd($exception);
    //     }

    //     return \response()->json('Saved successfully');
    // }

    public function getPendingStockreturns(Request $request){
        $pendingStockReturns = StockReturn::select(\DB::raw('GROUP_CONCAT(fldid) as fldids'),'fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldsuppname','fldreference')
                                            ->where([
                                                ['fldreference',$request->reference],
                                                ['fldsave',0],
                                                ['fldcomp',Helpers::getCompName()]
                                            ])
                                            ->groupBy(['fldstockid','fldbatch'])
                                            ->get();
        $html ='';
        foreach($pendingStockReturns as $pendingStockReturn){
            $html .= '<tr data-fldid="'.$pendingStockReturn->fldids.'">';
            $html .= '<td><input type="checkbox" class="js-stockreturn-select-class-checkbox" data-fldid="'.$pendingStockReturn->fldids.'"></td>';
            $html .='<td>'.$pendingStockReturn->Entry->fldcategory.'</td>';
            $html .='<td>'.$pendingStockReturn->fldstockid.'</td>';
            $html .='<td>'.$pendingStockReturn->fldbatch.'</td>';
            $html .='<td>'.$pendingStockReturn->Entry->fldexpiry.'</td>';
            $html .='<td>'.$pendingStockReturn->fldqty.'</td>';
            $html .='<td>'.$pendingStockReturn->fldcost.'</td>';
            $html .='<td>'.$pendingStockReturn->fldsuppname.'</td>';
            $html .='<td>'.$pendingStockReturn->fldreference.'</td>';
            $html .='<td><button class="btn btn-danger" onclick="deleteentry(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
        }
        return $html;
    }

    public function deleteEntry(Request $request){
        DB::beginTransaction();
        try {
            $fldids = explode(",",$request->get('fldids'));
            $stockreturns = StockReturn::whereIn('fldid', $fldids)->get();
            foreach($stockreturns as $stockreturn){
                $stockreturn->delete();
            }
            DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Something went wrong...',
            ]);
        }
    }

}
