<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\Discount;
use App\DiscountLedgerMap;
use App\TaxGroup;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\GeneralLedgerMap;

use Lang;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $data['ledgers'] = AccountLedger::select('AccountName', 'AccountId')->whereDoesntHave('discount_ledger_map')->get();
        $data['discounts'] = Discount::select('fldtype')->get();
        $data['taxs'] = TaxGroup::get();

        $data['mapped_data'] = DiscountLedgerMap::with(['ledger', 'discount'])->get();

        return view('coreaccount::discount-map.map', $data);
    }

    public function createMap(Request $request)
    {
        try {
            DiscountLedgerMap::create($request->except('_token'));
            return redirect()->back()->with('success', __('messages.success', ['name' => 'Discount map']));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', __('messages.error'));
        }
    }

    public function createMapGeneral(Request $request)
    {
        try {
            $data = [
                'type' => $request->get('type') ? $request->get('type') : 0,
                'name' => $request->get('discount_name') ? $request->get('discount_name') : 0 ,
                'ledger_id' => $request->get('ledger_id') ? $request->get('ledger_id') : 0,

            ];
             GeneralLedgerMap::create($data);
            return redirect()->back()->with('success', __('messages.success', ['name' => 'Map']));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', __('messages.error'));
        }
    }

}
