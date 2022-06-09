<?php

namespace Modules\Purchase\Http\Controllers;

use App\Entry;
use App\Events\StockLive;
use App\PatBillCount;
use App\Purchase;
use App\StockReturn;
use App\Supplier;
use App\Utils\Helpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{

	public function index()
	{
		$data['suppliers'] = Purchase::select('fldsuppname')->distinct('fldsuppname')->groupBy('fldsuppname')->get();
//        $data['references'] =Purchase::select('fldreference')->distinct('fldreference')->groupBy('fldreference')->get();
        $data['routes'] = array_keys(array_slice(Helpers::getDispenserRoute(), 0, 12));
        return view('purchase::purchase-return.index', $data);
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

    public function getRefrence(Request $request)
    {
        if (!$request->get('supplier')) {
            return \response()->json(['error' => 'Please select supplier']);
        }
        $references = \App\Purchase::select('fldreference')->distinct('fldreference')->where([
            'fldsuppname' => $request->get('supplier'),
        ])->whereRaw('fldtotalqty - fldreturnqty > 0')->whereNotNull('fldreference')->groupBy('fldreference')->get();
        $html = '';
        if ($references) {
            $html .= '<option value="">--Select--</option>';
            foreach ($references as $ref) {
                $html .= '<option value="' . $ref->fldreference . '">' . $ref->fldreference . '</option>';
            }
        }
        return response()->json($html);
    }


    public function getMedicine(Request $request)
    {

        if (!$request->get('supplier') || !$request->get('reference')) {
            return \response()->json(['error' => 'Please select supplier and reference']);
        }
        $category ='';
        if($request->get('route')){
            $category = $request->get('route');
            // $category = $this->getParticularCategory($request->get('route'));
        }
        $medicines = \App\Purchase::select('fldstockid')->distinct('fldbatch')->where([
            'fldsuppname' => $request->get('supplier'),
            'fldcategory' => $category,
            'fldreference' => $request->get('reference'),
        ])->whereRaw('(fldtotalqty + fldqtybonus) - fldreturnqty > 0')->whereNotNull('fldstockid')->groupBy('fldbatch')->get();
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
        if (!$request->get('supplier')) {
            return \response()->json(['error' => 'Please select supplier']);
        }
        $medicines = \App\Purchase::select('fldstockid','fldreference','fldcategory')->distinct('fldbatch')->where([
            'fldsuppname' => $request->get('supplier')
        ])->whereRaw('(fldtotalqty + fldqtybonus) - fldreturnqty > 0')->whereNotNull('fldstockid')->whereNotNull('fldreference')->groupBy('fldbatch')->get();
        $html = '';
        if ($medicines) {
            $html .= '<option value="">--Select--</option>';
            foreach ($medicines as $med) {
                $html .= '<option data-ref="'.$med->fldreference.'" data-categ="'.$med->fldcategory.'" value="' . $med->fldstockid . '">' . $med->fldstockid . '('.$med->fldreference.')</option>';
            }
        }

        $pendingStockReturns = StockReturn::where('fldsuppname',$request->supplier)
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
                $pendingReturnsHtml .='<td>'.Helpers::numberFormat(($stock->fldcarcost)).'</td>';
	            $pendingReturnsHtml .='<td>'.Helpers::numberFormat(($stock->fldcashdisc)).'</td>';
	            $pendingReturnsHtml .='<td>'.Helpers::numberFormat(($stock->fldvatamt)).'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldqty.'</td>';
                $pendingReturnsHtml .='<td>'.$stock->fldbonusretqty.'</td>';
                $pendingReturnsHtml .='<td>'.Helpers::numberFormat(($stock->fldcost)).'</td>';
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

        $category ='';
        if($request->get('route')){
            $category = $request->get('route');
            // $category = $this->getParticularCategory($request->get('route'));
        }
        $batches = \App\Purchase::with('Entry')->select('fldstockid','fldstockno')->distinct('fldbatch')->where([
            'fldstockid' => $request->get('medicine'),
            'fldcategory' => $category,
            'fldsuppname' => $request->get('supplier'),
            'fldreference' => $request->get('reference'),
        ])->whereNotNull('fldstockid')->groupBy('fldbatch')->get();

        $html = '';
        if ($batches) {
            $html .= '<option value="">--Select--</option>';
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
		                    ->with('hasPurchase:fldstockno,fldcarcost,fldstockid,fldreference,fldnetcost,fldvatamt,fldqtybonus,fldtotalqty','pendingStockReturn','stockReturn','hasPurchase.purchaseBill')
		                    ->first();

		$updatedQty = $entryDetail->fldqty;

		$entry_count = Entry::
		                    whereHas('hasPurchase', function($q) use ($reference){
			                    $q->where('fldreference', $reference);
		                    })->get()->count();

		if ($entryDetail) {
			$qty = 0;
			$carryCost = 0;
			$fldcost = 0;
			$disAmt = 0;
			if($entryDetail->hasPurchase){
				$carryCost = $entryDetail->hasPurchase->fldcarcost;
				$bonusQty = $entryDetail->hasPurchase->fldqtybonus;
				$fldcost = $entryDetail->hasPurchase->fldnetcost;
				$individualDiscountAmt = $entryDetail->hasPurchase->purchaseBill->flddiscounted;
				$disAmt = $entryDetail->hasPurchase->purchaseBill->fldlastdisc/ $entry_count;
				if($entryDetail->hasPurchase->purchaseBill->fldtotalvat != 0){
					$vatAmt = $entryDetail->hasPurchase->purchaseBill->fldtotalvat / $entry_count;

				}else{
					$vatAmt = $entryDetail->hasPurchase->fldvatamt;
				}

			}
			$qty += $entryDetail->hasPurchase->fldtotalqty;
			if(count($entryDetail->pendingStockReturn) > 0){
				$qty = $qty - $entryDetail->pendingStockReturn->sum('fldqty');
				$bonusQty = $bonusQty - $entryDetail->pendingStockReturn->sum('fldbonusretqty');
				$vatAmt -= $entryDetail->pendingStockReturn->sum('fldvatamt');
				$carryCost = $carryCost - $entryDetail->pendingStockReturn->sum('fldcarcost');
				$disAmt =  $disAmt - $entryDetail->pendingStockReturn->sum('flddisamt');
				$individualDiscountAmt =  $individualDiscountAmt - $entryDetail->pendingStockReturn->sum('fldcashdisc');

			}
			if(count($entryDetail->stockReturn) > 0){
				$qty = $qty - $entryDetail->stockReturn->sum('fldqty');
				$bonusQty = $bonusQty - $entryDetail->stockReturn->sum('fldbonusretqty');
				$carryCost = $carryCost - $entryDetail->stockReturn->sum('fldcarcost');
				$vatAmt =  $vatAmt - $entryDetail->stockReturn->sum('fldvatamt');
				$disAmt =  $disAmt - $entryDetail->stockReturn->sum('flddisamt');
				$individualDiscountAmt =  $individualDiscountAmt - $entryDetail->stockReturn->sum('fldcashdisc');
			}

			if($updatedQty > 0){
				$diffQty = $qty+ $bonusQty - $updatedQty;
				$updatedBonusQty = $bonusQty - $diffQty;
				if($updatedBonusQty > 0 ){
					$bonusQty = $updatedBonusQty;
				}else{
					$bonusQty = 0;
					$qty = $qty + $updatedBonusQty;
				}
			}

			if($vatAmt < 0){
				$vatAmt = 0;
			}

			$expiry = $entryDetail->fldexpiry;

			return \response()->json([
				'expiry' => Carbon::parse($expiry)->format('Y-m-d'),
				'qty' => $qty,
				'bonusqty' => $bonusQty,
				'vatamt' => $vatAmt,
				'disamt' => $disAmt,
				'carrycost' => $carryCost,
				'fldstockno' => $entryDetail->fldstockno,
				'fldcost' => $fldcost,
				'fldcashdisc' => $individualDiscountAmt
			]);
		}

		return \response()->json('');

		// return response()->json($expiry);

	}

	public function insertStockReturn(Request $request)
	{
		try{
			if (!$request->get('supplier') || !$request->get('medicine') || !$request->get('reference')) {
				return \response()->json(['error', 'Please select Supplier,Medicine and batch']);
			}
			$category ='';
			if($request->get('route')){
				$category = $request->get('route');
				// $category = $this->getParticularCategory($request->get('route'));
			}
			$bonusretqty = $request->get('bonusretqty');
			$vatamt = $request->get('vatamt');
			$disamt = $request->get('disamt');
			$cashdisamt = $request->get('cashdisamt');


			$data = [
				'fldstockno' => $request->get('stockNo'),
				'fldstockid' => $request->get('medicine'),
				'fldbatch' => $request->get('batch'),
				'fldqty' => $request->get('retqty'),
				'fldbonusretqty' => isset($bonusretqty)?$request->get('bonusretqty'):0,
				'fldvatamt' => isset($vatamt)?$request->get('vatamt'):0,
				'flddisamt' => isset($disamt)?$request->get('disamt'):0,
				'fldcashdisc' => isset($cashdisamt)?$request->get('cashdisamt'):0,
				'fldsuppname' => $request->get('supplier'),
				'fldreference' => $request->get('reference'),
				'fldcategory' => $category,
				'flduserid' => Helpers::getCurrentUserName(),
				'fldtime' => Carbon::now(),
				'fldcomp' => Helpers::getCompName(),
				'fldcost' =>(($request->get('retqty')) * $request->get('netcost')),
				// 'fldnewreference' => 'SRE-'.Helpers::getNextAutoId('ReferenceNo',TRUE),
				'fldsave' => 0,
				'fldcarcost' => $request->get('carcost'),
			];
			$stock =StockReturn::create($data);
			$html ='';
			if($stock){
				$html .= '<tr data-fldid="'.$stock->fldid.'">';
				$html .='<td><input type="hidden" name="stockreturnid[]" class="stockreturnid" value="'.$stock->fldid.'">'.$stock->fldtime.'</td>';
				$html .='<td>'.$stock->fldstockno.'</td>';
				$html .='<td>'.$stock->fldbatch.'</td>';
				$html .='<td>'.$stock->fldsuppname.'</td>';
				$html .='<td>'.$stock->fldstockid.'</td>';
				$html .='<td>'.Helpers::numberFormat(($stock->fldcarcost)).'</td>';
				$html .='<td>'.Helpers::numberFormat(($stock->fldcashdisc)).'</td>';
				$html .='<td>'.Helpers::numberFormat(($stock->fldvatamt)).'</td>';
				$html .='<td>'.$stock->fldqty.'</td>';
				$html .='<td>'.$stock->fldbonusretqty.'</td>';
				$html .='<td>'.Helpers::numberFormat(($stock->fldcost)).'</td>';
				//    $html .='<td>'.$stock->fldnewreference.'</td>';
				$html .='<td>'.$stock->fldreference.'</td>';
				$html .='<td>'.$stock->flduserid.'</td>';
				$html .='<td><button class="btn btn-danger" onclick="deleteentry('.$stock->fldid.')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';

			}
			return \response()->json($html);
		}catch (\Exception $e){
			return \response()->json(['error', $e->getMessage()]);
		}

	}

    public  function finalSave(Request $request){
        try {
            DB::beginTransaction();
            $stockreturn = StockReturn::whereIn('fldid', $request->returnIds)->get();
            if($stockreturn){
                $stockReturnReference = 'SRE-'.Helpers::getNextAutoId('ReferenceNo',TRUE);
                foreach ($stockreturn as $stk){

                    $purchase = Purchase::where('fldstockno', $stk->fldstockno)->first();


                    if ($purchase) {
                        $returnQty = ($purchase->fldreturnqty + $stk->fldqty + $stk->fldbonusretqty);
                        $purchase->where('fldid', $purchase->fldid)->update(['fldreturnqty'=>$returnQty]);
                    }

                    $entry = Entry::where('fldstockno',$stk->fldstockno)->first();


                    if($entry) {
                        $quantity = ($entry->fldqty)-($stk->fldqty);
                        $entry->where('fldstockno',$entry->fldstockno)->update(['fldqty'=>$quantity]);
                    }

                    $stk->where('fldid',$stk->fldid)->update([
                        'fldsave'=>1,
                        'fldnewreference' => $stockReturnReference,
                    ]);
                }
                //event trigger for live stock
                event(new StockLive(1));
                DB::commit();
                return response([
                    'status'=>true,
                    'stockReturnReference'=>$stockReturnReference
                ]);
            }

        }catch (\Exception $exception){
            DB::rollBack();
	        return response()->json([
		        'status' => FALSE,
		        'message' => __('messages.error'),
	        ]);
        }

        return \response()->json('Saved successfully');
    }


    public  function exportReport(Request $request){
        $data = [];
        $stockreturns = StockReturn::select('fldbonusretqty','fldstockno','fldcategory','fldstockid','fldqty','fldcost','fldcarcost','fldbatch','fldsuppname','fldreference','fldvatamt','flddisamt','fldcashdisc')
	        ->with('entry.hasPurchase')
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
        $data['certificate'] = "PURCHASE RETURN (CREDIT NOTE)";
        $countdata = PatBillCount::where('fldbillno', $request->fldnewreference)->pluck('fldcount')->first();

        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;

        // $updatedata['fldcount'] = $countdata->fldcount + 1;
        if (isset($countdata) and $countdata != '') {
            PatBillCount::where('fldbillno', $request->fldnewreference)->update($updatedata);
        } else {
            $insertdata['fldbillno'] = $request->fldnewreference;
            $insertdata['fldcount'] = 1;
            PatBillCount::insert($insertdata);
        }
        $data['billCount'] = $count;

        return view('purchase::purchase-return.purchase-return-pdf',$data);
    }

    public function deleteEntry(Request $request){
        try {
            StockReturn::where('fldid', $request->get('fldid'))->delete();
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

    public function getPendingStockReturns(Request $request){
        try {
            $pendingStockReturns = StockReturn::where('fldsuppname',$request->supplier)
                                            ->where('fldreference',$request->reference)
                                            ->where('fldsave',0)
                                            ->get();
            $html ='';
            if(count($pendingStockReturns) > 0){
                foreach($pendingStockReturns as $stock){
                    $html .= '<tr data-fldid="'.$stock->fldid.'">';
                    $html .='<td><input type="hidden" name="stockreturnid[]" class="stockreturnid" value="'.$stock->fldid.'">'.$stock->fldtime.'</td>';
                    $html .='<td>'.$stock->fldstockno.'</td>';
                    $html .='<td>'.$stock->fldbatch.'</td>';
                    $html .='<td>'.$stock->fldsuppname.'</td>';
                    $html .='<td>'.$stock->fldstockid.'</td>';
                    $html .='<td>'.Helpers::numberFormat(($stock->fldcarcost)).'</td>';
                    $html .='<td>'.$stock->fldqty.'</td>';
                    $html .='<td>'.$stock->fldreference.'</td>';
                    $html .='<td>'.$stock->flduserid.'</td>';
                    $html .='<td><button class="btn btn-danger" onclick="deleteentry('.$stock->fldid.')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                }
            }
            return response()->json([
                'status'=> TRUE,
                'message' => 'Fetched reference data.',
                'pendingStockReturns' => $html
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to get reference data.',
            ]);
        }
    }
}
