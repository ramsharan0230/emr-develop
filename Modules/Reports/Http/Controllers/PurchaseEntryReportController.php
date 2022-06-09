<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use App\Entry;
use App\Exports\PurchaseEntryExport;
use App\HospitalDepartment;
use App\PurchaseBill;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseEntryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'suppliers' => \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get(),
            'departments' => HospitalDepartment::select('name', 'fldcomp')->distinct()->where('status', 'Active')->get(),
        ];
        return view('reports::purchase-entry.index', $data);
    }

    public function getList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $bill = $request->get('fldbill');
        $department = $request->get('department');
        $opening = $request->get('opening');
        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please enter date']);
        }

        $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
            });

        if ($department) {
            $query->where('fldcomp', $department);
        }

        if ($opening) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldisopening', 1);
            });
        }

        if ($supplier) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
            });
        }
        if ($bill) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldreference', $bill);
            });
        }
        if($supplier && $opening){
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldisopening', 1);
            });
        }


        if ($supplier != null && $bill != null && $department != null && $opening != null) {

            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldreference', $bill);
                $q->where('fldisopening', 1);
            })->where([
                ['fldcomp', '<=', $department],
            ]);
        }

        $data = $query->latest('fldexpiry')->paginate(10);
        $html = '';
        if ($data) {
            $i = 1;
            $total_dsc = 0;
            $total_totl = 0;
            $total_vat = 0;
            $total_cc = 0;
            $total_subtotal = 0;
            foreach ($data as $datum) {
                foreach ($datum->purchase as $purchase) {
                    $html .= '<tr>';
                    $html .= '<td>' . $i . '</td>';
                    $html .= '<td>' . $purchase->fldsuppname . '</td>';
                    $html .= '<td>' . \Carbon\Carbon::parse($purchase->fldpurdate)->format('Y-m-d') ?? null . '</td>';
                    $html .= '<td>' . $datum->fldcategory . '</td>';
                    $html .= '<td>' . $datum->fldstockid . '</td>';
                    $html .= '<td>' . $datum->fldbatch . '</td>';
                    $html .= '<td>' . \Carbon\Carbon::parse($datum->fldexpiry)->format('Y-m-d') . '</td>';
                    $html .= '<td>' . $purchase->fldreference ?? null . '</td>';
                    $html .= '<td>' . $purchase->fldgrnno ?? null . '</td>';
                    // $html .= '<td>' . $datum->fldqty . '</td>';
                    $html .= '<td>' . $purchase->fldtotalqty . '</td>';
                    $qtybonus = ($purchase->fldqtybonus) ? $purchase->fldqtybonus : "0";
                    $html .= '<td>' . $qtybonus . '</td>';
                    $totalqty = $purchase->fldtotalqty + (($purchase->fldqtybonus) ? $purchase->fldqtybonus : 0);
                    $html .= '<td>' . $totalqty . '</td>';
                    // $html .= '<td>' . $datum->fldsellpr . '</td>';
                    $netcost = ($purchase->fldnetcost) ? (($purchase->fldnetcost)) : "0.00";
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::numberFormat($netcost) . '</td>';
                    $vamt = ($purchase->fldvatamt) ? (($purchase->fldvatamt)) : 0.00;
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::numberFormat($vamt) . '</td>';
                    $carcost = ($purchase->fldcarcost) ? (($purchase->fldcarcost)) : 0.00;
                    $totcost = ($purchase->fldtotalcost) ? (($purchase->fldtotalcost)) : 0.00;
                    $total = (($purchase->fldnetcost) ? (($purchase->fldnetcost)) : 0) * $purchase->fldtotalqty;
                    $subtotal = $totcost - $vamt;
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::numberFormat($carcost) . '</td>';
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::numberFormat(($subtotal)) . '</td>';
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::numberFormat(($totcost + $carcost)) . '</td>';
                    $html .= '<td>Rs. ' . \App\Utils\Helpers::getDepartmentFromComp($datum->fldcomp) . '</td>';
                    $total_dsc += ($purchase->fldcasdisc) ? (($purchase->fldcasdisc)) : 0.00;
                    $total_totl += ($totcost + $carcost);
                    $total_cc += $carcost;
                    $total_vat += ($purchase->fldvatamt) ? $purchase->fldvatamt : 0.00;
                    $total_subtotal += $subtotal;
                    ++$i;
                }
            }
            $html .='<tr><td colspan="19">'.$data->appends(request()->all())->links().'</td></tr>';
            return response()->json(['status'=>true,'html'=>$html]);
        }
        return response()->json($html = '<tr><td  colspan="18" align="center"> No data available</td></tr>');
    }

    public function report(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $bill = $request->get('fldbill');
        $opening = $request->get('opening');
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }
        $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
            $q->whereDate('fldpurdate', '>=', $from_date);
            $q->whereDate('fldpurdate', '<=', $to_date);
        });

        if ($department) {
            $query->where('fldcomp', $department);
        }

        if ($opening) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldisopening', 1);
            });
        }

        if ($supplier) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
            });
        }
        if ($bill) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldreference', $bill);
            });
        }
        if($supplier && $opening){
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldisopening', 1);
            });
        }


        if ($supplier != null && $bill != null && $department != null && $opening != null) {

            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldreference', $bill);
                $q->where('fldisopening', 1);
            })->where([
                ['fldcomp', '<=', $department],
            ]);
        }

        $data['entries'] = $query->latest('fldexpiry')->get();
        return view('reports::purchase-entry.purchase-entry-report', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier') ?? '';
        $department = $request->get('department') ?? '';
        $bill = $request->get('fldbill') ?? '';
        $opening = $request->get('opening') ?? '';
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new PurchaseEntryExport($from_date, $to_date, $supplier, $department,$opening,$bill), 'Purchase-Entry-Report.xlsx');
    }

    public function getBillNo(Request $request)
    {


        $supplier = $request->get('supplier');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if (!$supplier || !$from_date || !$to_date) {
            return \response(['error' => 'Please check from date, to date and supplier name']);
        }
        $bills = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
            $q->whereDate('fldpurdate', '>=', $from_date);
            $q->whereDate('fldpurdate', '<=', $to_date);
            $q->where('fldsuppname', $supplier);
            $q->where('fldreference', '!=', null);
        })->latest('fldexpiry')->get();
        $references = [];
        $html = '';
        if ($bills) {
            foreach ($bills as $bill) {
                if ($bill->purchase) {
                    foreach ($bill->purchase as $purchase) {
                        if(!in_array($purchase->fldreference,$references)){
                            array_push($references,$purchase->fldreference);
                        }
                    }
                }
            }
            $html .= "<option value=''>-- Select --</option>";
            foreach ($references as $reference) {
                $html .= "<option value=" . $reference . ">$reference</option>";
            }
            return \response()->json($html);

        } else {
            return \response("<option value=''>--Select--</option>");
        }

    }

    public function PurchaseEntrySuppliersWise(Request $request){

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $bill = $request->get('fldbill');
        $department = $request->get('department');

        $supplierpurchase = PurchaseBill::select('fldsuppname','fldbillno',\DB::raw('SUM(flddebit) as sumdebit'),\DB::raw('SUM(fldcredit)  as sumcredit'),\DB::raw('SUM(fldtotaltax)  as sumtax'),\DB::raw('SUM(fldlastdisc)  as sumdis'),'fldpurdate','fldreference','fldtotalvat')
        ->where('fldsav',1)
        ->groupBy('fldbillno')->get();

        //return Excel::download(new PurchaseEntryExport($from_date, $to_date, $supplier, $department,$opening,$bill), 'Purchase-Entry-Report.xlsx');

    }

}
