<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\StockTransferExport;
use App\StockReturn;
use App\Transfer;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

//        dd(Helpers::getCompName());
        $data = [
            'comps' =>Transfer::select('fldfromcomp')->distinct()->get(),
            'references' => Transfer::select('fldreference')->distinct('fldreference')
                ->where('fldreference','!=','')
//                ->groupBy('fldreference')
                ->get(),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
//        dd($data);
        return view('reports::stock-transfer.index',$data);
    }


    public function getList(Request $request)
    {

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $reference = $request->get('reference');
        if (!$from_date || !$to_date || !$reference) {
            return \response()->json(['error' => 'Please enter date and reference']);
        }
        $data = Transfer::with('entry')->where('fldreference',$reference)
            ->whereDate('fldfromentrytime', '>=', $from_date)->whereDate('fldfromentrytime', '<=', $to_date)
//                ->where('fldsave','=','1')
            ->paginate(10);
//        dd($data);
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldstockid . '</td>';
                $html .= '<td>' . (($datum->entry->fldbatch) ? $datum->entry->fldbatch : '' ) . '</td>';
                $html .= '<td>' . (($datum->entry->fldexpiry) ? $datum->entry->fldexpiry : '' ) . '</td>';
                $html .= '<td>' . $datum->fldcategory . '</td>';
                $html .= '<td>' . $datum->fldqty . '</td>';
                $html .= '<td>' . $datum->fldsellpr . '</td>';
                $html .= '<td>' . $datum->fldfromcomp . '</td>';
                $html .= '<td>' . $datum->fldreference . '</td>';
                $html .= '<td>' . $datum->fldremark . '</td></tr>';
            }
            $html .='<tr><td colspan="10">'.$data->appends(request()->all())->links().'</td></tr>';
            return response()->json(['status'=>true ,'html'=>$html]);
        }
        return response()->json($html='<tr><td  colspan="8" align="center"> No data available</td></tr>');

    }

    public function report(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $reference = $request->get('reference');
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }

        $data['references'] = Transfer::with('entry','brand','extraBrand','surgicalBrand')
            ->where('fldreference',$reference)
            ->whereDate('fldfromentrytime', '>=', $from_date)->whereDate('fldfromentrytime', '<=', $to_date)
//                ->where('fldsave','=','1')
//                ->take(100)
            ->get();
//        dd($data);
        return view('reports::stock-transfer.stock-transfer-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $reference = $request->get('reference');
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new StockTransferExport($from_date, $to_date,$reference), 'Stock-Transfer-Report.xlsx');
    }


}
