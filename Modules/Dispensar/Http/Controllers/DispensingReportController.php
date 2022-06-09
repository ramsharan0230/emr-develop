<?php

namespace Modules\Dispensar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DispensingReportController extends Controller
{
    private function _getExpiryMedicine(Request $request, $paginate = FALSE)
    {
        $medicines = \App\Entry::select('fldstockno','fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
        ->where('fldexpiry','!=','NULL')
        ->where([
            ['fldexpiry', '<=', date('Y-m-d') . ' 00:00:00'],
            ['fldsav', '!=', '0'],
            ['fldqty', '!=', '0'],
        ])->orderBy('fldexpiry')->with(['hasTransfer']);
        // if ($paginate)
        //     return $medicines->paginate(50);
        return $medicines->get();
    }

    public function expiry(Request $request)
    {
        return view('dispensar::reports.expiry', [
            'medicines' => $this->_getExpiryMedicine($request, TRUE),
        ]);
    }

    public function expiryPdf(Request $request)
    {
        return view('dispensar::reports.expiry-pdf', [
            'medicines' => $this->_getExpiryMedicine($request),
        ]);
    }

    public function expiryExcel(Request $request)
    {
        $export = new \App\Exports\MedicineExpiryReport();
        ob_end_clean();
        ob_start();
        return \Excel::download($export, 'MedicineExpiryExport.xlsx');
    }


    private function _getNearExpiryMedicine(Request $request, $paginate = FALSE)
    {
        $date = $request->get('date');
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit .  ' 00:00:00';

        $medicines = \App\Entry::select('fldstockno','fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', $expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldsav', '!=', '0'],
                ['fldqty', '!=', '0'],
            ])->orderBy('fldexpiry')->with(['hasTransfer']);
        // if ($paginate)
        //     return $medicines->paginate(50);

        return $medicines->get();
    }

    public function nearexpiry(Request $request)
    {
        return view('dispensar::reports.near-expiry', [
            'medicines' => $this->_getNearExpiryMedicine($request, TRUE),
        ]);
    }

    public function nearexpiryPdf(Request $request)
    {
        return view('dispensar::reports.near-expiry-pdf', [
            'medicines' => $this->_getNearExpiryMedicine($request),
        ]);
    }

    public function nearexpiryExcel(Request $request)
    {

        $date = $request->get('date');
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit .  ' 00:00:00';

        $export = new \App\Exports\MedicineNearExpiryReport($expiry_limit);
        ob_end_clean();
        ob_start();
        return \Excel::download($export, 'MedicineNearExpiryReport.xlsx');
    }
}
