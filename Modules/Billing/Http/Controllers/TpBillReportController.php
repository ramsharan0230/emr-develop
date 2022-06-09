<?php

namespace Modules\Billing\Http\Controllers;

use App\PatBilling;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TpBillReportController extends Controller
{
    public function exportInvoice(Request $request)
    {
        $requestData['toDate'] = date('Y-m-d') . ' 23:59:59';
        $requestData['fromDate'] = date('Y-m-d') . ' 00:00:00';
        $requestData['billno'] = '';
        $requestData['encounter'] = '';

        if ($request->has('eng_from_date') && $request->has('eng_to_date') && $request->get('eng_from_date') != '' && $request->get('eng_to_date') != '') {
            $requestData['fromDate'] = $request->eng_from_date . ' 00:00:00';
            $requestData['toDate'] = $request->eng_to_date . ' 23:59:59';
        }

        if ($request->has('billno')) {
            $requestData['billno'] = $request->billno;
        }
        if ($request->has('encounter')) {
            $requestData['encounter'] = $request->encounter;
        }

        $requestData['tpBilling'] = PatBilling::select('fldencounterval', 'fldtempbillno', 'flditemqty', 'flditemrate', 'flddiscamt', 'fldtaxamt', 'fldditemamt', 'fldordtime', 'flditemname')
            ->where('fldditemamt', '>=', 0)
            ->where('fldstatus', 'like', 'Punched')
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where(function ($query) use ($requestData) {

                $query->where('fldtime', '>', $requestData['fromDate'])
                    ->where('fldtime', '<', $requestData['toDate']);

                if ($requestData['billno'] != '') {
                    $query->where('fldtempbillno', 'like', $requestData['billno']);
                }

                if ($requestData['encounter'] != '') {
                    $query->where('fldencounterval', '=', $requestData['encounter']);
                }
            })
            ->groupBy('fldtempbillno')
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();

        return view('billing::tp-bill.invoice-tp-export', $requestData);
    }
}
