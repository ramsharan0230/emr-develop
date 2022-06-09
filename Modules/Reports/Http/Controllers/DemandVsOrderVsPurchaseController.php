<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\DemandVsOrderVsPurchaseExport;
use App\Exports\OrderVsReciveExport;
use App\HospitalDepartment;
use App\Order;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DemandVsOrderVsPurchaseController extends Controller
{

    public function index()
    {

        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'suppliers' => \App\Supplier::select('fldsuppname')->where('fldactive', 'Active')->get(),
            'references' => Order::select('fldreference')->distinct('fldreference')->get(),
            'departments' => HospitalDepartment::select('name', 'fldcomp')->distinct()->where('status', 'Active')->get(),
        ];
        return view('reports::demand-vs-order-vs-purchase.index', $data);
    }

    public function getList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $reference = $request->get('reference');
        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please enter date']);
        }
        $query = Order::with('purchase', 'demand')
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date);
        if ($department != null && $supplier != null && $reference != null) {
            $query->where('fldcomp', $department)->where('fldsuppname', $supplier)->where('fldreference', $reference);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($reference) {
            $query->where('fldreference', $reference);
        }
        $data = $query->latest('fldorddate')->paginate(10);
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldorddate . '</td>';
                $html .= '<td>' . $datum->fldsuppname . '</td>';
                $html .= '<td>' . $datum->flditemname . '</td>';
                $html .= '<td>' . (($datum->demand) ? $datum->demand->fldquantity : '') . '</td>';
                $html .= '<td>' . $datum->fldqty . '</td>';
                $html .= '<td>' . (($datum->purchase) ? $datum->purchase->fldsubtotal : '') . '</td>';
                $html .= '<td>' . (($datum->demand) ? $datum->demand->fldtime_order : ''). '</td>';
                $html .= '<td>' .$datum->fldorddate  . '</td>';
                $html .= '<td>' .(($datum->purchase) ? $datum->purchase->fldpurdate : '') .  '</td>';
                $html .= '<td>' . $datum->fldroute . '</td>';
                $html .= '<td>' . $datum->fldreference . '</td>';
                $html .= '<td>' . $datum->fldlocat . '</td>';
                $html .= '<td>' . $datum->fldcomp . '</td></tr>';
            }
            $html .= '<tr><td colspan="14">' . $data->appends(request()->all())->links() . '</td></tr>';
            return response()->json(['status' => true, 'html' => $html]);
        }
        return response()->json($html = '<tr><td  colspan="13" align="center"> No data available</td></tr>');
    }

    public function report(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $reference = $request->get('reference');
        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please enter date']);
        }
        $query = Order::with('purchase', 'demand')
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date);
        if ($department != null && $supplier != null && $reference != null) {
            $query->where('fldcomp', $department)->where('fldsuppname', $supplier)->where('fldreference', $reference);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($reference) {
            $query->where('fldreference', $reference);
        }
        $data['orders'] = $query->latest('fldorddate')->get();
//        dd($data);
        return view('reports::demand-vs-order-vs-purchase.demand-vs-order-vs-purchase-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier') ?? '';
        $department = $request->get('department') ?? '';
        $reference = $request->get('reference') ?? '';
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new DemandVsOrderVsPurchaseExport($from_date, $to_date, $supplier, $department, $reference), 'Demand-vs-Order-vs-purchase-Report.xlsx');
    }

    public function getReferences(Request $request)
    {

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier');
        $department = $request->get('department');
        $query = Order::select('fldreference')->distinct('fldreference')->with('purchase')
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date);
        if ($department != null && $supplier != null) {
            $query->where('fldcomp', $department)->where('fldsuppname', $supplier);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        $references = $query->latest('fldorddate')->get();
        $html = '';
        if ($references) {
            foreach ($references as $reference) {
                $html .= '<option value="' . $reference->fldreference . '">' . $reference->fldreference . '</option>';
            }
            return \response()->json($html);
        } else {
            return \response()->json(['<option value="">--Select--</option>']);
        }


    }

}
