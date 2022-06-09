<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\StockReturnExport;
use App\StockReturn;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StockReturnController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => StockReturn::select('fldcategory')->distinct()->get(),
//            'references'=>StockReturn::select('fldnewreference')->distinct()->get(),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
//        dd($data);
        return view('reports::stock-return.index', $data);
    }

    public function getList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $reference = $request->get('reference');
        $category = $request->get('category');
        if (!$from_date || !$to_date || !$reference) {
            return \response()->json(['error' => 'Please enter date and reference']);
        }
        $query = StockReturn::with('entry')->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if ($reference != null && $category != null) {
            $query->where('fldcategory', $category)->where('fldnewreference', $reference);
        }
        if ($reference) {
            $query->where('fldnewreference', $reference);
        }
        if ($category) {
            $query->where('fldcategory', $category);
        }
        $data = $query->latest('fldtime')->paginate(10);
//        $data = StockReturn::with('entry')->where('fldnewreference',$reference)
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
////                ->where('fldsave','=','1')
//            ->get();
//        dd($data);
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldcategory . '</td>';
                $html .= '<td>' . $datum->fldstockid . '</td>';
                $html .= '<td>' . (($datum->entry->fldbatch) ? $datum->entry->fldbatch : '') . '</td>';
                $html .= '<td>' . (($datum->entry->fldexpiry) ? $datum->entry->fldexpiry : '') . '</td>';
                $html .= '<td>' . $datum->fldqty . '</td>';
                $html .= '<td>' . $datum->fldcost . '</td>';
                $html .= '<td>' . $datum->fldsuppname . '</td>';
                $html .= '<td>' . $datum->fldreference . '</td>';
//                $html .= '<td>' . $datum->fldcomp . '</td></tr>';
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
        $reference = $request->get('reference');
        $category = $request->get('category');
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }

        $query = StockReturn::with('entry')->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if ($reference != null && $category != null) {
            $query->where('fldcategory', $category)->where('fldnewreference', $reference);
        }
        if ($reference) {
            $query->where('fldnewreference', $reference);
        }
        if ($category) {
            $query->where('fldcategory', $category);
        }
        $data['references'] = $query->latest('fldtime')->get();

//        $data['references'] = StockReturn::with('entry')->where('fldnewreference',$reference)
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
////                ->where('fldsave','=','1')
//            ->get();
        return view('reports::stock-return.stock-return-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $reference = $request->get('reference') ?? '';
        $category = $request->get('category') ?? '';
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new StockReturnExport($from_date, $to_date, $reference, $category), 'Stock-Return-Report.xlsx');
    }

    public function getReference(Request $request)
    {

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $category = $request->get('category');
        if (!$from_date || !$to_date || !$category) {
            return \response()->json(['error' => 'Please check date and category']);
        }
        $references = StockReturn::select('fldnewreference')
            ->whereDate('fldtime', '>=', $from_date)
            ->whereDate('fldtime', '<=', $to_date)
            ->where('fldcategory', $category)->distinct('fldnewreference')->get();
        $html = '';
        if ($references) {
            foreach ($references as $reference) {
                $html .= '<option value=' . $reference->fldnewreference . '>' . $reference->fldnewreference . '</option>';
            }
            return \response()->json($html);
        } else {
            return \response()->json('<option value="">--Select--</option>');
        }

    }


}
