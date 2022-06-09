<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\OrderVsReciveExport;
use App\HospitalDepartment;
use App\Order;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OrderVsReciveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'suppliers' => \App\Supplier::select('fldsuppname')->where('fldactive', 'Active')->get(),
            'references'=>Order::select('fldreference')->distinct()->get(),
            'departments' => HospitalDepartment::select('name', 'fldcomp')->distinct()->where('status', 'Active')->get(),
        ];
        return view('reports::order-vs-recieve.index', $data);
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
        $query = Order::with('purchase')
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date)
        ;
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
        $data = $query->latest('fldorddate')->paginate(50);
        $html = '';
        $count = 1;
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . ( $count++) . '</td>';
                $html .= '<td>' . $datum->fldorddate . '</td>';
                $html .= '<td>' . $datum->fldsuppname . '</td>';
                $html .= '<td>' . $datum->flditemname . '</td>';
                $html .= '<td>' . $datum->fldqty . '</td>';
                $html .= '<td>' . (($datum->purchase) ? $datum->purchase->fldtotalqty : '') . '</td>';
                $html .= '<td>' . $datum->fldroute . '</td>';
                $html .= '<td>' . $datum->fldreference . '</td>';
                $html .= '<td>' . $datum->fldlocat . '</td>';
                $html .= '<td>' . $this->getDepartmentName($datum->fldcomp) . '</td></tr>';
            }
            $html .='<tr><td colspan="10">'.$data->appends(request()->all())->links().'</td></tr>';
            return response()->json(['status'=>true,'html'=>$html]);
        }
        return response()->json($html = '<tr><td  colspan="8" align="center"> No data available</td></tr>');
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
        $query = Order::with('purchase')
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
        return view('reports::order-vs-recieve.order-vs-recieve-pdf', $data);
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
        return Excel::download(new OrderVsReciveExport($from_date, $to_date, $supplier, $department,$reference), 'Order-vs-Receive-Report.xlsx');
    }

    public function getReferences(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $supplier = $request->get('supplier') ;
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
        $html ='';
        if($references){
            foreach ($references as $reference){
                $html.='<option value="'.$reference->fldreference.'">'.$reference->fldreference.'</option>';
            }
            return \response()->json($html);
        }else{
           return \response()->json(['<option value="">--Select--</option>']);
        }



    }

    private function getDepartmentName($comp){
        $dept_name = HospitalDepartment::select('name')->where('fldcomp',$comp)->first();
        if($dept_name){
            return $dept_name->name ?? '';
        }else{
            return '';
        }

    }


}
