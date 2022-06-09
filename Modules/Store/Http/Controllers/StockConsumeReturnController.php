<?php

namespace Modules\Store\Http\Controllers;

use App\AutoId;
use App\BulkSale;
use App\ConsumeReturn;
use App\Entry;
use App\ExtraBrand;
use App\Invid;
use App\Purchase;
use App\StockReturn;
use App\Supplier;
use App\SurgBrand;
use App\Target;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class StockConsumeReturnController extends Controller
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
        $data['targets'] = Target::select('flditem')->get();
        $data['fldcomp'] = Helpers::getCompName();

        return view('store::stockconsumereturn.index', $data);
    }

    public function exportPdfReprint(Request $request) {

        $data = [];
        $consumereturns = ConsumeReturn::select('fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldtarget','fldreference')
                                        ->where([
                                                'fldsave' => 1,
                                                'fldnewreference' => $request->fldnewreference
                                            ])
                                        ->orderBy('fldid', 'ASC')
                                        ->groupBy(['fldstockid','fldbatch'])
                                        ->get();

        $grandtotalcost = '';
        $totalparticularcost = [];
        if(count($consumereturns) > 0) {
            foreach($consumereturns as $k=>$consumereturn) {
                $totalparticularcost[$k] = $consumereturn->fldqty * $consumereturn->fldcost;
            }
        }

        $grandtotalcost = array_sum($totalparticularcost);

        $data['grandtotalcost'] = $grandtotalcost;
        $data['consumereturns'] = $consumereturns;
        $data['fldnewreference'] = $request->fldnewreference;
        $data['certificate'] = "CONSUME RETURN";

        return view('store::layouts.pdf.consumereturnpdf', $data);
    }

    public function getRefNo(Request  $request)
    {

        if (!$request->get('target')) {
            return \response()->json(['error' => 'Please select target']);
        }
        try {
         $references = BulkSale::select('fldreference')
                            ->where([
                                        'fldcomp' => Helpers::getCompName(),
                                        'fldtarget' => $request->target
                                    ])
                            ->whereRaw('fldqtydisp - fldqtyret > 0')
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
        if (!$request->get('target') || !$request->get('reference')) {
            return \response()->json(['error' => 'Please select supplier and reference']);
        }
        $medicines = BulkSale::select('fldstockid')->distinct('fldstockid')->where([
                                        'fldtarget' => $request->get('target'),
                                        'fldcategory' => $request->get('route'),
                                        'fldreference' => $request->get('reference'),
                                        'fldcomp' => Helpers::getCompName()
                                    ])
                                    ->whereRaw('fldqtydisp - fldqtyret > 0')
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

    public function getBatch(Request $request)
    {
        if (!$request->get('medicine')) {
            return \response()->json(['error' => 'Please select medicine']);
        }

        $batches = BulkSale::with('Entry')->select('fldstockid','fldstockno')->distinct('fldstockid')->where([
            'fldstockid' => $request->get('medicine'),
            'fldtarget' => $request->get('target'),
            'fldcategory' => $request->get('route'),
            'fldreference' => $request->get('reference'),
            'fldcomp' => Helpers::getCompName()
        ])
        ->whereRaw('fldqtydisp - fldqtyret > 0')
        ->whereNotNull('fldstockid')
        ->groupBy('fldstockid')
        ->get();

        $html = '';
        if ($batches) {
            $html .= '<option>--Select--</option>';
            foreach ($batches as $med) {
                $html .= '<option value=' . (($med->Entry->fldbatch) ? $med->Entry->fldbatch : '') . '>' . (($med->Entry->fldbatch) ? $med->Entry->fldbatch : '') . '</option>';
            }
        }
        return response()->json($html);
    }


    public function getExpiry(Request $request)
    {
        if (!$request->get('medicine') || !$request->get('batch')) {
            return \response()->json(['error' => 'Please select medicine and batch']);
        }

        $expiry = "";
        $qty = 0;
        $bulksales = BulkSale::select('fldstockno',DB::raw('sum(fldqtydisp) as fldqtydisp'),DB::raw('sum(fldqtyret) as fldqtyret'))->where([
            'fldstockid' => $request->get('medicine'),
            'fldtarget' => $request->get('target'),
            'fldcategory' => $request->get('route'),
            'fldreference' => $request->get('reference'),
            'fldcomp' => Helpers::getCompName()
        ])
        ->whereRaw('fldqtydisp - fldqtyret > 0')
        ->whereHas('Entry', function($q) use ($request){
            $q->where('fldbatch', $request->get('batch'));
        })
        ->groupBy('fldstockno')
        ->get();

        foreach($bulksales as $bulksale){
            $expiry = Carbon::parse($bulksale->Entry->fldexpiry)->format('Y-m-d');
            $qty += ($bulksale->fldqtydisp - $bulksale->fldqtyret);
            if(count($bulksale->pendingConsumeReturn) > 0){
                $qty -= $bulksale->pendingConsumeReturn->sum('fldqty');
            }
        }

        return response([
            'expiry' => $expiry,
            'qty' => $qty
        ]);

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


    public function saveConsumeReturns(Request $request)
    {
        if (!$request->get('fldtarget') || !$request->get('medicine') || !$request->get('reference')) {
            return \response()->json(['error', 'Please select Target,Medicine and batch']);
        }

        DB::beginTransaction();
        try {
            $bulksales = BulkSale::where([
                                        'fldstockid' => $request->get('medicine'),
                                        'fldtarget' => $request->get('fldtarget'),
                                        'fldcategory' => $request->get('route'),
                                        'fldreference' => $request->get('reference'),
                                        'fldcomp' => Helpers::getCompName()
                                    ])
                                    ->whereRaw('fldqtydisp - fldqtyret > 0')
                                    ->whereHas('Entry', function($q) use ($request){
                                        $q->where('fldbatch', $request->get('batch'));
                                    })
                                    ->orderBy('fldqtydisp','asc')
                                    ->get();

            $qty = $request->retqty;
            foreach($bulksales as $bulksale){
                $tempqty = $bulksale->fldqtydisp - $bulksale->fldqtyret;
                if(count($bulksale->pendingConsumeReturn) > 0){
                    $tempqty = $tempqty - $bulksale->pendingConsumeReturn->sum('fldqtydisp');
                }
                if($tempqty > 0){
                    if($qty > $tempqty){
                        $retQty = $tempqty;
                    }else{
                        $retQty = $qty;
                    }
                    $qty = $qty - $retQty;
                    if($retQty > 0){
                        $data = [
                            'fldstockno' => $bulksale->Entry->fldstockno,
                            'fldstockid' => $request->get('medicine'),
                            'fldbatch' => $request->get('batch'),
                            'fldqty' => $retQty,
                            'fldcost' => $bulksale->Entry->fldsellpr * $retQty,
                            'fldtarget' => $request->get('fldtarget'),
                            'fldreference' => $request->get('reference'),
                            'fldcategory' => $request->get('route'),
                            'flduserid' => Helpers::getCurrentUserName(),
                            'fldtime' => Carbon::now(),
                            'fldcomp' => Helpers::getCompName(),
                            'xyz' => 0,
                            'fldsave' => 0,
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                        ];
                        ConsumeReturn::create($data);
                    }
                }
            }

            $pendingStockReturns = ConsumeReturn::select(\DB::raw('GROUP_CONCAT(fldid) as fldids'),'fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldtarget','fldreference')
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
                $html .= '<td><input type="checkbox" class="js-stockconsumereturn-select-class-checkbox" data-fldid="'.$pendingStockReturn->fldids.'"></td>';
                $html .='<td>'.$pendingStockReturn->Entry->fldcategory.'</td>';
                $html .='<td>'.$pendingStockReturn->fldstockid.'</td>';
                $html .='<td>'.$pendingStockReturn->fldbatch.'</td>';
                $html .='<td>'.$pendingStockReturn->Entry->fldexpiry.'</td>';
                $html .='<td>'.$pendingStockReturn->fldqty.'</td>';
                $html .='<td>'.$pendingStockReturn->fldcost.'</td>';
            }
            DB::commit();
            return \response()->json($html);
        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
        }
    }

    public  function finalSave(Request $request){
        if(!$request->get('returnids'))
        {
            return \response()->json(['error','Please select row first!']);
        }

        try {
            DB::beginTransaction();
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $consumeReturnReference = 'CRE-'.$fiscalYear."-".Helpers::getNextAutoId('ReferenceNo',TRUE);
            if($request->returnids){
                foreach ($request->returnids as $stockreturn){
                    $stk = ConsumeReturn::where('fldid',$stockreturn)->first();
                    $bulksales = BulkSale::where([
                                            'fldstockno' => $stk->fldstockno,
                                            'fldtarget' => $stk->fldtarget,
                                            'fldcategory' => $stk->fldcategory,
                                            'fldreference' => $stk->fldreference,
                                            'fldsave' => 1,
                                            'fldcomp' => Helpers::getCompName()
                                        ])
                                        ->whereRaw('fldqtydisp - fldqtyret > 0')
                                        ->whereHas('Entry', function($q) use ($stk){
                                            $q->where('fldbatch', $stk->fldbatch);
                                        })
                                        ->orderBy('fldqtydisp','asc')
                                        ->get();

                    $qty = $stk->fldqty;
                    foreach($bulksales as $bulksale){
                        $tempqty = $bulksale->fldqtydisp - $bulksale->fldqtyret;
                        if(count($bulksale->pendingConsumeReturn) > 0){
                            $tempqty = $tempqty - $bulksale->pendingConsumeReturn->sum('fldqtydisp');
                        }
                        if($tempqty > 0){
                            if($qty > $tempqty){
                                $retQty = $tempqty;
                            }else{
                                $retQty = $qty;
                            }
                            $qty = $qty - $retQty;
                            if($retQty > 0){
                                BulkSale::where('fldid',$bulksale->fldid)->update([
                                    'fldqtyret'=>$retQty
                                ]);
                            }
                        }
                    }

                    $entry = Entry::where('fldstockno',$stk->fldstockno)->first();
                    if($entry) {
                        $quantity = ($entry->fldqty)+($stk->fldqty);
                        $entry->where('fldstockno',$entry->fldstockno)->update(['fldqty'=>$quantity]);
                    }

                    $stk->where('fldid',$stk->fldid)->update([
                                                                'fldsave'=>1,
                                                                'fldnewreference'=>$consumeReturnReference
                                                            ]);
                }
            }
            $html = $this->getPendingStockconsumereturns($request);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
        }

        return response([
            'status' => true,
            'fldnewreference' => $consumeReturnReference,
            'html' => $html
        ]);
    }

    public function getPendingStockconsumereturns(Request $request){
        $pendingStockReturns = ConsumeReturn::select(\DB::raw('GROUP_CONCAT(fldid) as fldids'),'fldstockno','fldcategory','fldstockid',\DB::raw('sum(fldqty) as fldqty'),\DB::raw('sum(fldcost) as fldcost'),'fldbatch','fldtarget','fldreference')
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
            $html .= '<td><input type="checkbox" class="js-stockconsumereturn-select-class-checkbox" data-fldid="'.$pendingStockReturn->fldids.'"></td>';
            $html .='<td>'.$pendingStockReturn->Entry->fldcategory.'</td>';
            $html .='<td>'.$pendingStockReturn->fldstockid.'</td>';
            $html .='<td>'.$pendingStockReturn->fldbatch.'</td>';
            $html .='<td>'.$pendingStockReturn->Entry->fldexpiry.'</td>';
            $html .='<td>'.$pendingStockReturn->fldqty.'</td>';
            $html .='<td>'.$pendingStockReturn->fldcost.'</td>';
        }
        return $html;
    }

}
