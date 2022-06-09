<?php

namespace Modules\Reports\Http\Controllers;

use App\Demand;
use App\Exports\DemandExport;
use App\HospitalDepartment;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DemandformReportController extends Controller
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
        return view('reports::demandReport.index', $data);
    }

    public function getMedicineList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $bill_no = $request->get('fldbill');

        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please enter date']);
        }
        $query = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date);

        if ($supplier != null && $department != null && $bill_no != null) {
            $query->where('fldsuppname', $supplier)->where('fldcomp_order', $department)->where('fldquotationno', $bill_no);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($department) {
            $query->where('fldcomp_order', $department);
        }
        if ($bill_no) {
            $query->where('fldquotationno', $bill_no);
        }
        $data = $query->paginate(10);
//        dd($data);
//        $data = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date)->get();
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldordersavedtime . '</td>';
                $html .= '<td>' . $datum->fldsuppname . '</td>';
                $html .= '<td>' . $datum->fldstockid . '</td>';
                $html .= '<td>' . $datum->fldquotationno . '</td>';
                $html .= '<td>' . $datum->fldquantity . '</td>';
                $html .= '<td>' . $datum->fldrate . '</td>';
                $html .= '<td>' . $datum->fldtotal . '</td>';
                $html .= '<td>' . $datum->flduserid_order . '</td>';
                $html .= '<td>' . (($datum->hospitalDepartment) ? ($datum->hospitalDepartment->name) : '') . '</td>';
                $html .= '<td>' . (($datum->hospitalDepartment) ? ($datum->hospitalDepartment->fldcomp) : '') . '</td></tr>';
            }
            $html .= '<tr><td colspan="10">' . $data->appends(request()->all())->links() . '</td></tr>';
            return response()->json(['status' => true, 'html' => $html]);
        }
        return response()->json($html = "<tr><td colspan='10' align='center'> No data available<td></tr>");
    }

    public function report(Request $request)
    {

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $bill_no = $request->get('fldbill');
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }

        $query = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date);

        if ($supplier != null && $department != null && $bill_no != null) {
            $query->where('fldsuppname', $supplier)->where('fldcomp_order', $department)->where('fldquotationno', $bill_no);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($department) {
            $query->where('fldcomp_order', $department);
        }
        if ($bill_no) {
            $query->where('fldquotationno', $bill_no);
        }
        $data['demands'] = $query->get();
//        $data['demands'] = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date)->get();

        return view('reports::demandReport.demandformreport', $data);
    }

    public function export(Request $request)
    {
        $request->all();
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier') ?? '';
        $department = $request->get('department') ?? '';
        $bill_no = $request->get('fldbill') ?? '';
        ob_end_clean();
        ob_start();
        return Excel::download(new DemandExport($from_date, $to_date, $supplier, $department, $bill_no), 'demand-report.xlsx');
    }

    public function getBillNo(Request $request)
    {

        $supplier = $request->get('supplier');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if (!$supplier || !$from_date || !$to_date) {
            return \response(['error' => 'Please check from date, to date and supplier name']);
        }
        $bills = Demand::select('fldquotationno')->where([
            ['fldsuppname', $supplier],
            ['fldordersavedtime', '>=', $from_date],
            ['fldordersavedtime', '<=', $to_date],
            ['fldquotationno', '!=', null]
        ])->distinct('fldquotationno')->latest('fldordersavedtime')->get();
        $html = '<option value="">--Select--</option>';
        if ($bills) {
            foreach ($bills as $bill) {
                $html .= "<option value=" . $bill->fldquotationno . ">$bill->fldquotationno</option>";
            }

            return \response()->json($html);

        } else {
            return \response("<option value=''>--Select--</option>");
        }

    }
}


