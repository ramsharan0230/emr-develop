<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\PurchaseExport;
use App\HospitalDepartment;
use App\Order;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReportController extends Controller
{
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'suppliers' => \App\Supplier::select('fldsuppname')->where('fldactive', 'Active')->get(),
            'departments' => HospitalDepartment::select('name', 'fldcomp')->distinct()->where('status', 'Active')->get(),
        ];
        return view('reports::purchasereport.index', $data);
    }

    public function getMedicineList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $bill = $request->get('fldbill');
        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please check date']);
        }
        $query = Order::whereDate('fldorddate', '>=', $from_date)->whereDate('fldorddate', '<=', $to_date);

        if ($supplier != null && $department != null && $bill != null) {
            $query->where('fldsuppname', $supplier)->where('fldcomp', $department)->where('fldreference', $bill);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        if ($bill) {
            $query->where('fldreference', $bill);
        }
        $data = $query->latest('fldorddate')->paginate(10);
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldorddate . '</td>';
                $html .= '<td>' . $datum->fldsuppname . '</td>';
                $html .= '<td>' . $datum->flditemname . '</td>';
                $html .= '<td>' . $datum->fldreference . '</td>';
                $html .= '<td>' . $datum->fldqty . '</td>';
                $html .= '<td>' . $datum->fldrate . '</td>';
                $html .= '<td>' . $datum->fldamt . '</td>';
                $html .= '<td>' . $datum->flduserid . '</td>';
                $html .= '<td>' . $datum->fldlocat . '</td>';
                $html .= '<td>' . $datum->fldcomp . '</td></tr>';
            }
            $html .= '<tr><td colspan="10">' . $data->appends(request()->all())->links() . '</td></tr>';
            return response()->json(['status' => true, 'html' => $html]);
        }
        return response()->json($html);
    }

    public function report(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $bill = $request->get('fldbill');
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }

        $query = Order::whereDate('fldorddate', '>=', $from_date)->whereDate('fldorddate', '<=', $to_date);
        if ($supplier != null && $department != null && $bill != null) {
            $query->where('fldsuppname', $supplier)->where('fldcomp', $department)->where('fldreference', $bill);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($bill) {
            $query->where('fldreference', $bill);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        $data['orders'] = $query->latest('fldorddate')->get();
        return view('reports::purchasereport.purchasereport', $data);
    }

    public function export(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier') ?? '';
        $department = $request->get('department') ?? '';
        $bill = $request->get('fldbill') ?? '';
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new PurchaseExport($from_date, $to_date, $supplier, $department, $bill), 'Purchase-Report.xlsx');
    }

    public function getBillNo(Request $request)
    {
        $supplier = $request->get('supplier');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if (!$supplier || !$from_date || !$to_date) {
            return \response(['error' => 'Please check from date, to date and supplier name']);
        }
        $bills = Order::select('fldreference')->where('fldsuppname', $supplier)
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date)
            ->distinct('fldreference')->latest('fldorddate')->get();
        $html = '';
        if ($bills) {
            foreach ($bills as $bill) {
                $html .= "<option value=" . $bill->fldreference . ">$bill->fldreference</option>";
            }

            return \response()->json($html);

        } else {
            return \response("<option value=''>--Select--</option>");
        }

    }
}
