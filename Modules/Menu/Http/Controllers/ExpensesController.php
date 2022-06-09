<?php

namespace Modules\Menu\Http\Controllers;

use App\Encounter;
use App\PatBillDetail;
use App\PatBilling;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class ExpensesController extends Controller
{
    public function laboratoryReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate','fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();
          


        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'Diagnostic Tests',
            'fldsave'           => 1
        ])
            ->select('fldid','fldtime','flditemtype','fldcomp','flditemname','flditemno','flditemrate','fldtaxper','flddiscper','flditemqty','fldditemamt as tot','flduserid','fldstatus','fldbillno')
            ->get();
        
        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.laboratory-pdf', $data)/*->setPaper('a4')->stream('expenses-laboratory.pdf')*/;
    }

    public function radiologyReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate','fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

         

        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'Radio Diagnostics',
            'fldsave'           => 1
        ])
            ->select('fldid','fldtime','flditemtype','fldcomp','flditemname','flditemno','flditemrate','fldtaxper','flddiscper','flditemqty','fldditemamt as tot','flduserid','fldstatus','fldbillno')
            ->get();

            $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);

        return view('menu::pdf.expenses.radiology-pdf', $data)/*->setPaper('a4')->stream('expenses-radiology.pdf')*/;
    }

    public function proceduresReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

            

        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'Procedures',
            'fldsave'           => 1
        ])
            ->select('fldid','fldtime','flditemtype','fldcomp','flditemname','flditemno','flditemrate','fldtaxper','flddiscper','flditemqty','fldditemamt as tot','flduserid','fldstatus','fldbillno')
            ->get();

            $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.procedures-pdf', $data)/*->setPaper('a4')->stream('expenses-procedures.pdf')*/;
    }

    public function generalServicesReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

            

        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'General Services',
            'fldsave'           => 1
        ])
        ->select('fldid','fldtime','flditemtype','fldcomp','flditemname','flditemno','flditemrate','fldtaxper','flddiscper','flditemqty','fldditemamt as tot','flduserid','fldstatus','fldbillno')
        ->get();
        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.general-services-pdf', $data)/*->setPaper('a4')->stream('expenses-general-services.pdf')*/;
    }

    public function equipmentReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();



        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'Equipment',
            'fldsave'           => 1
        ])
            ->select('fldid','fldtime','flditemtype','fldcomp','flditemname','flditemno','flditemrate','fldtaxper','flddiscper','flditemqty','fldditemamt as tot','flduserid','fldstatus','fldbillno')
            ->get();
            $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.equipment-pdf', $data)/*->setPaper('a4')->stream('expenses-equipment.pdf')*/;
    }

    public function otherItemsReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

        $data['tblpatbilling'] = PatBilling::where([
            'fldencounterval'   => $encounterId,
            'flditemtype'       => 'Other Items',
            'fldsave'           => 1
        ])
            ->select('fldtime', 'flditemtype', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldditemamt', 'fldbillno')
            ->get();
            $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.other-items-pdf', $data)/*->setPaper('a4')->stream('expenses-other-items.pdf')*/;
    }

    public function summaryReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();



        $distinctbill = PatBilling::where('fldencounterval', $encounterId)->where('fldsave', 1)->select('flditemtype')->distinct()->get();
        $billdata = array();
        if ($distinctbill) {
            foreach ($distinctbill as $k => $bill) {
                $abill = PatBilling::select('fldditemamt as tot', 'fldtime', 'flditemtype', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldstatus', 'fldbillno')
                    ->where('fldencounterval', $encounterId)
                    ->where('fldsave', 1)
                    ->where('flditemtype', $bill->flditemtype)
                    ->get();

                $item = $bill->flditemtype;

                $billdata[$item] = $abill;
            }
        }

        $data['summary'] = $billdata;
        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.summary-pdf', $data)/*->setPaper('a4')->stream('expenses-summary.pdf')*/;
    }

    public function invoiceReportPdf(Request $request, $encounterId = null,$form_signature='opd')
    {

        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }
        // laboratory-pdf
        $data['encounterId'] = $encounterId;

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

        $data['tblpatbilling'] = PatBillDetail::where('fldencounterval', $encounterId)
            ->select('fldtime', 'fldbillno', 'fldprevdeposit', 'flditemamt', 'fldtaxamt', 'flddiscountamt', 'fldchargedamt', 'fldreceivedamt', 'fldcurdeposit', 'fldbilltype', 'fldchequeno', 'fldbankname')
            ->get();
            $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.expenses.invoice-pdf', $data)/*->setPaper('a4')->stream('expenses-invoice.pdf')*/;
            
    }
}
