<?php

namespace Modules\DiscountMode\Http\Controllers;

use App\Discount;
use App\Exports\DiscountModeExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PatientDiscountModeExportReportController extends Controller
{

    public function __construct(Discount $discount)
    {
        $this->discount = $discount ;
    }
    /**
     * exporting Discount Model Data
     * @param Request
     * return Download Excel File
     */
    public function exportToExcel(Request $request = null)
    {
        try{
            // $discountModes = $this->discount->get();
            $export = new DiscountModeExport();
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'DiscountModeReport.xlsx');
        }catch(Exception $e)
        {
            throw new \Exception(__('messages.error'));
            // dd($e->getMessage());
        }
    }

        /**
     * exporting Discount Model Data
     * @param Request nullable
     * return PDF file
     */
    public function exportToPdf(Request $request = null)
    {
        try{
            $discountMode = new Discount();

            $data['discountData'] = $discountMode->select('fldtype', 'fldmode', 'fldyear', 'fldamount', 'fldcredit', 'fldpercent', 'fldbillingmode', 'flduserid', 'fldtime', 'updated_by')->with('cogentUser')->get();
            return view("discountmode::pdf.patient-mode-pdf",$data);
        }catch(Exception $e)
        {
            throw new \Exception(__('messages.error'));
            // dd($e->getMessage());
        }
    }
}
