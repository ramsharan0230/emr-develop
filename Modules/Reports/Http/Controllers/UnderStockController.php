<?php

namespace Modules\Reports\Http\Controllers;

use App\Entry;
use App\Exports\UnderStockExport;
use App\ExtraBrand;
use App\MedicineBrand;
use App\SurgBrand;
use App\Transfer;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UnderStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
        return view('reports::under-stock.index',$data);
    }

    public function getList(Request $request)
    {

        $data['meds'] = MedicineBrand::where('fldactive','Active')->paginate(50);
        $data['surgeries'] = SurgBrand::where('fldactive','Active')->paginate(50);
        $data['extras'] = ExtraBrand::where('fldactive','Active')->paginate(50);

        $html = '';
        if($data){
            if ($data['meds']) {
                $html.='<tr><td align="center" colspan="8"><b>Medicine</b></td></tr>';
                foreach ($data['meds'] as $datum) {

                    $html .= '<tr>';
                    $html .= '<td>' . $datum->fldbrandid . '</td>';
                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td>' . $datum->fldstandard . '</td>';
                    $html .= '<td>' . $datum->fldminqty . '</td>';
                    $html .= '<td>' . $datum->fldleadtime . '</td>';
                    $html .= '<td>' . '' . '</td>';
                }
            }
            if($data['surgeries']){

                $html.='<tr><td align="center" colspan="8"><b>Surgical</b></td></tr>';
                foreach ($data['surgeries'] as $datum) {
                    $html .= '<tr>';
                    $html .= '<td>' . $datum->fldbrandid . '</td>';
                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td>' . $datum->fldstandard . '</td>';
                    $html .= '<td>' . $datum->fldminqty . '</td>';
                    $html .= '<td>' . $datum->fldleadtime . '</td>';
                    $html .= '<td>' . '' . '</td>';
                }
            }

            if($data['extras']){

                $html.='<tr><td align="center" colspan="8"><b>Extras</b></td></tr>';
                foreach ($data['extras'] as $datum) {
                    $html .= '<tr>';
                    $html .= '<td>' . $datum->fldbrandid . '</td>';
                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td>' . $datum->fldstandard . '</td>';
                    $html .= '<td>' . $datum->fldminqty . '</td>';
                    $html .= '<td>' . $datum->fldleadtime . '</td>';
                    $html .= '<td>' . '' . '</td>';
                }
            }
            $html .= '<tr><td colspan="14">' . $data['meds']->appends(request()->all())->links() . '</td></tr>';
            return response()->json($html);
        }
        return response()->json($html='<tr><td  colspan="8" align="center"> No data available</td></tr>');

    }

    public function report(Request $request)
    {
//        $from_date = $request->get('from_date');
//        $to_date = $request->get('to_date');
//
//        if (!$from_date || !$to_date ) {
//            return redirect()->back();
//        }
        $data['meds'] = MedicineBrand::where('fldactive','Active')->get();
        $data['surgeries'] = SurgBrand::where('fldactive','Active')->get();
        $data['extras'] = ExtraBrand::where('fldactive','Active')->get();
        $html = '';
        if($data){
            if ($data['meds']) {
                $html.='<tr><td align="center" colspan="8"><b>Medicine</b></td></tr>';
                foreach ($data['meds'] as $datum) {

                    $html .= '<tr>';
                    $html .= '<td >' . $datum->fldbrandid . '</td>';
                    $html .= '<td >' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td >' . $datum->fldstandard . '</td>';
                    $html .= '<td >' . $datum->fldminqty . '</td>';
                    $html .= '<td >' . $datum->fldleadtime . '</td>';
                    $html .= '<td >' . '' . '</td>';
                }
            }
            if($data['surgeries']){

                $html.='<tr><td align="center" colspan="8"><b>Surgical</b></td></tr>';
                foreach ($data['surgeries'] as $datum) {
                    $html .= '<tr>';
                    $html .= '<td >' . $datum->fldbrandid . '</td>';
                    $html .= '<td >' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td >' . $datum->fldstandard . '</td>';
                    $html .= '<td >' . $datum->fldminqty . '</td>';
                    $html .= '<td >' . $datum->fldleadtime . '</td>';
                    $html .= '<td >' . '' . '</td>';
                }
            }

            if($data['extras']){

                $html.='<tr><td align="center" colspan="8"><b>Extras</b></td></tr>';
                foreach ($data['extras'] as $datum) {
                    $html .= '<tr>';
                    $html .= '<td >' . $datum->fldbrandid . '</td>';
                    $html .= '<td >' . $datum->fldmanufacturer . '</td>';
                    $html .= '<td >' . $datum->fldstandard . '</td>';
                    $html .= '<td >' . $datum->fldminqty . '</td>';
                    $html .= '<td >' . $datum->fldleadtime . '</td>';
                    $html .= '<td >' . '' . '</td>';
                }
            }
//            return response()->json($html);
        }
        $data['html'] = $html;

        return view('reports::under-stock.under-stock-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if (!$from_date || !$to_date ) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new UnderStockExport($from_date, $to_date), 'Under-Stock-Report.xlsx');
    }

}
