<?php

namespace Modules\IrdPurchaseReport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\HospitalDepartmentUsers;
use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;
use App\Exports\PurchaseReportExport;
use Excel;

class IrdPurchaseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('irdpurchasereport::purchaseReport', $data);
    }

    public function searchPurchaseDetail(Request $request){
        try{
            $startTime = $request->eng_from_date;
            $endTime = $request->eng_to_date;
            $department = $request->department;

            $purchaseDatas =   DB::table('tblpurchasebill')
                ->select('tblpurchasebill.fldsuppname as Supplier_Name',
                'tblpurchasebill.fldpurdate as Pur_Date',
                'tblpurchasebill.fldreference as Purchase_Reference',
                DB::raw('sum(IFNULL(tblpurchasebill.vatableamount,0)) as Taxable_Amount'),
                DB::raw('sum(IFNULL(tblpurchasebill.nonvatableamount,0)) as NonTaxable_Amount'),
                DB::raw('sum(IFNULL(tblpurchasebill.fldtotaltax,0)) as Tax'))

                ->join('tblpurchase','tblpurchase.fldreference','=','tblpurchasebill.fldreference')
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '<=', $endTime);
                })
                ->when($department != null, function ($q) use ($department) {
                    return $q->where('tblpurchase.fldcomp', '=', $department);
                })
                ->groupBy('tblpurchase.fldreference')
                ->orderBy('tblpurchasebill.fldpurdate','desc')
                ->get();
            // dd($purchaseDatas);
            $html = '';
            $html .='<div class="iq-card-body">
                        <table id="myTable1" data-show-columns="true"
                        data-search="true"
                        data-show-toggle="true"
                        data-pagination="true"
                        data-resizable="true">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Date</th>
                                <th>Purchase Ref</th>
                                <th>Supplier Name</th>
                                <th>PAN/VAT</th>
                                <th>NonTaxable Amt</th>
                                <th>Taxable Amt</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>';

            if(isset($purchaseDatas) and count($purchaseDatas) > 0){
                foreach($purchaseDatas as $k=>$purchase){
                    $nepalidate = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($purchase->Pur_Date)));
                    $sn = $k+1;
                    $pan_no = Helpers::getSuppliersinfo($purchase->Supplier_Name);
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$nepalidate->year.'-'.$nepalidate->month.'-'.$nepalidate->date.'</td>';
                    $html .='<td>'.$purchase->Purchase_Reference.'</td>';
                    $html .='<td>'.$purchase->Supplier_Name.'</td>';
                    if(isset($pan_no->fldpanno) && isset($pan_no->fldvatno))
                    {
                        $html .='<td>'.$pan_no->fldpanno.'/'.$pan_no->fldvatno.'</td>';
                    }elseif((isset($pan_no->fldpanno))){
                        $html .='<td>'.$pan_no->fldpanno.'</td>';
                    }elseif((isset($pan_no->fldvatno))){
                        $html .='<td>'.$pan_no->fldvatno.'</td>';
                    }else{
                        $html .='<td></td>';
                    }
                    $html .='<td>'.\App\Utils\Helpers::numberFormat(($purchase->NonTaxable_Amount)).'</td>';
                    $html .='<td>'.\App\Utils\Helpers::numberFormat(($purchase->Taxable_Amount)).'</td>';
                    $html .='<td>'.\App\Utils\Helpers::numberFormat(($purchase->Tax)).'</td>';
                    $html .='<td>'.\App\Utils\Helpers::numberFormat(($purchase->NonTaxable_Amount + $purchase->Taxable_Amount + $purchase->Tax)).'</td>';
                    $html .='</tr>';
                }
            }
            $html .='</tbody>
                </table>
            </div>';
           $data['html'] = $html;
           return $data;

        }catch(\Exception $e){
            dd($e);
            \Log::info($e->getMessage());
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function exportPurchaseData(Request $request){
        try{
            $startTime = $request->eng_from_date;
            $endTime = $request->eng_to_date;
            $department = $request->department;

            $purchaseDatas =   DB::table('tblpurchasebill')
                ->select('tblpurchasebill.fldsuppname as Supplier_Name',
                'tblpurchasebill.fldpurdate as Pur_Date',
                'tblpurchasebill.fldreference as Purchase_Reference',
                DB::raw('sum(IFNULL(tblpurchasebill.vatableamount,0)) as Taxable_Amount'),
                DB::raw('sum(IFNULL(tblpurchasebill.nonvatableamount,0)) as NonTaxable_Amount'),
                DB::raw('sum(IFNULL(tblpurchasebill.fldtotaltax,0)) as Tax'))

                ->join('tblpurchase','tblpurchase.fldreference','=','tblpurchasebill.fldreference')
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '<=', $endTime);
                })
                ->when($department != null, function ($q) use ($department) {
                    return $q->where('tblpurchase.fldcomp', '=', $department);
                })
                ->groupBy('tblpurchase.fldreference')
                ->orderBy('tblpurchasebill.fldpurdate','desc')
                ->get();

            $data['purchasedata'] = $purchaseDatas;
            $data['month'] = '';
            $fromdatevalue = Helpers::dateEngToNepdash($request->eng_from_date);
            $nepalifromdate = $fromdatevalue->year . '-' . $fromdatevalue->month . '-' . $fromdatevalue->date;
            $nepalifrommonth = Helpers::getMonthFromNepaliDate($fromdatevalue->month);

            $todatevalue = Helpers::dateEngToNepdash($request->eng_to_date);
            $nepalitodate = $todatevalue->year . '-' . $todatevalue->month . '-' . $todatevalue->date;
            $nepalitomonth = Helpers::getMonthFromNepaliDate($todatevalue->month);

            if($nepalifrommonth == $nepalitomonth){
                $data['month'] = $nepalifrommonth;
            }

            $data['year'] = $fromdatevalue->year;
            return view('irdpurchasereport::purchaseReportPdf', $data);
        }catch(\Exception $e){
            dd($e);
            \Log::info($e->getMessage());
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }


    public function exportPurchaseDataToExcel(Request $request){
        $export = new PurchaseReportExport($request->all());
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'PurchaseReport.xlsx');
    }
}
