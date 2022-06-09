<?php

namespace Modules\Reports\Http\Controllers;

use App\BulkSale;
use App\Exports\StockConsumeExport;
use App\Target;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StockConsumeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'items'=>Target::select('flditem')->distinct()->get(),
            'consume_references' =>BulkSale::select('fldreference')->distinct()->get(),
        ];
        return view('reports::stock-consume.index',$data);
    }

    public function getList(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $reference = $request->get('reference');
        $item = $request->get('item');
        if (!$from_date || !$to_date || !$reference) {
            return \response()->json(['error' => 'Please enter date and reference']);
        }
        $query = BulkSale::with('stock')->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if ($reference){
            $query->where('fldreference',$reference);
        }
        $data = $query->latest('fldtime')->paginate(10);
//            BulkSale::with('stock')
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
//                ->where('fldsave','=','1')
//            ->get();
        $html = '';
        if ($data) {
            foreach ($data as $datum) {
                $html .= '<tr>';
                $html .= '<td>' . $datum->fldid . '</td>';
                $html .= '<td>' . $datum->fldbulktime . '</td>';
                $html .= '<td>' . $datum->fldtarget . '</td>';
                $html .= '<td>' . $datum->fldcategory . '</td>';
                $html .= '<td>' . $datum->fldstockid . '</td>';
                $html .= '<td>' . (($datum->stock->fldbatch) ? $datum->stock->fldbatch : '' ) . '</td>';
                $html .= '<td>' . (($datum->stock->fldexpiry) ? $datum->stock->fldexpiry : '' ) . '</td>';
                $html .= '<td>' . $datum->fldqtydisp . '</td>';

            }
            $html .='<tr><td colspan="10">'.$data->appends(request()->all())->links().'</td></tr>';
            return response()->json(['status'=>true,'html'=>$html]);
        }
        return response()->json($html='<tr><td  colspan="8" align="center"> No data available</td></tr>');

    }

    public function report(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $reference = $request->get('reference');
        $item = $request->get('item');
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }

        $query = BulkSale::with('stock')->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if ($reference){
            $query->where('fldreference',$reference);
        }
        $data['references'] = $query->latest('fldtime')->get();
//        $data['references'] = BulkSale::with('stock')->where('fldreference',$reference)
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
////                ->where('fldsave','=','1')
//            ->get();
        return view('reports::stock-consume.stock-consume-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $reference = $request->get('reference') ?? '';
        $item = $request->get('item') ?? '';
        if (!$from_date || !$to_date || !$reference) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new StockConsumeExport($from_date, $to_date,$reference,$item), 'Stock-Consume-Report.xlsx');
    }

    public function getReference(Request $request){

        $item = $request->get('item');
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if(!$item || !$from_date || !$to_date){
            return \response(['error'=>'Please check date and item']);
        }

        $references = BulkSale::select('fldreference')->distinct('fldreference')->where([
            ['fldtime','>=',$from_date],
            ['fldtime','<=',$to_date],
            ['fldtarget','=',$item]
        ])->whereNotNull('fldreference')->latest('fldreference')->get();
        $html ='';
        if($references){
            foreach ($references as $reference){
                $html.='<option value="'.$reference->fldreference.'">'.$reference->fldreference.'</option>';
            }
            return  \response()->json($html);
        }else{
            return  \response()->json("<option value=''>--Select--</option>");
        }



    }
}
