<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\Exports\PharmacySalesBookExport;
use App\PatBilling;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PharmacySalesBookConrollerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;

//         Cp = tblpurchase.fldnetcost

// SP tblpatbilling.flditemrate

// discount = tblpatbilling.flddiscamt

// Sales Qty = tblpatbilling.flditemqty

// Search Tblpatbilling.flditemtype =

// Medicine

// Surgical and Extra

// Group By tblpatbilling.flditemname

        $data['pharmacy_results'] = PatBilling::with('bulkSale','purchase','entry','extraBrand','brand','surgicalBrand','stockReturn')->where(function ($query) {
            $query->orWhere('flditemtype', '=', 'Surgicals')
                ->orWhere('flditemtype', '=', 'Medicines')
                ->orWhere('flditemtype', '=', 'Extra Items');
        })
            ->groupBy('flditemname')
            ->paginate(100);

        return view('pharmacist::pharmacy-sales-book.pharmacy-sales-book', $data);
    }

    public function searchData(Request $request){

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        try {
            $sales = PatBilling::with('bulkSale','purchase','entry','extraBrand','brand','surgicalBrand','stockReturn')->where(function ($query) {
                $query->orWhere('flditemtype', '=', 'Surgicals')
                    ->orWhere('flditemtype', '=', 'Medicines')
                    ->orWhere('flditemtype', '=', 'Extra Items');
            })
                ->where('fldtime','>=',$from_date)
                ->where('fldtime','<=',$to_date)
                ->groupBy('flditemname')
                ->get();
            $html ='';
            $count =1;
            if($sales){

                foreach ($sales as $sale){

                    $html .='<tr>';
                    $html .='<td>'.$count++.'</td>';
//                $html .='<td> </td>';

                    if($sale->flditemtype=='Medicines'){
                        $html .='<td>' . (($sale->brand) ? $sale->brand->fldbrand :'' ) ?? null . '</td>';
                    }

                    if($sale->flditemtype=='Surgicals'){
                        $html .='<td>' . (($sale->surgicalBrand) ? $sale->surgicalBrand->fldbrand :'' ) ?? null . '</td>';
                    }

                    if($sale->flditemtype=='Extra Items'){
                        $html .='<td>' . (($sale->extraBrand) ? $sale->extraBrand->fldbrand :'' ) ?? null . '</td>';
                    }


                    if($sale->flditemtype=='Medicines'){
                        $html .='<td>' . (($sale->brand) ? $sale->brand->flddrug :'' ) ?? null . '</td>';
                    }

                    if($sale->flditemtype=='Surgicals'){
                        $html .='<td>' . (($sale->surgicalBrand) ? $sale->surgicalBrand->fldsurgid :'' ) ?? null . '</td>';
                    }

                    if($sale->flditemtype=='Extra Items'){
                        $html .='<td>' . (($sale->extraBrand) ? $sale->extraBrand->fldextraid :'' ) ?? null . '</td>';
                    }
                    $returnsp = ( $sale->flditemqty  * $sale->fldditemamt );
                    $returncp = $sale->purchase ?  ( $sale->flditemqty * $sale->purchase->fldnetcost) : 0;
                    $valuesp = $sale->flditemrate;
                    $valuecp = ($sale->purchase ? $sale->purchase->fldnetcost :0);
                    $netprofit = (($valuesp -$valuecp) - ( $returnsp + $returncp));


                    $html .='<td>' .( $sale->flditemqty ??'') . '</td>';
                    $html .='<td>' . ( $sale->fldretqty ??''). '</td>';
                    $html .='<td>' .(\App\Utils\Helpers::numberFormat($valuesp) ??'') . '</td>';
                    $html .='<td>' .(\App\Utils\Helpers::numberFormat($valuecp) ?? '') . '</td>';
                    $html .='<td>' .(\App\Utils\Helpers::numberFormat($sale->flddiscamt) ?? '') . '</td>';
                    $html .='<td>'.(\App\Utils\Helpers::numberFormat($returnsp) ?? '').'</td>';
                    $html .='<td>'.(\App\Utils\Helpers::numberFormat($returncp) ?? '').'</td>';
                    $html .='<td>'.(\App\Utils\Helpers::numberFormat(abs($netprofit)) ?? '').'</td>';
                    $html .='</tr>';
                }
                return \response()->json($html);

            }
            return \response()->json($html.='<tr><td colspan="8">No data available </td></tr>');
        }catch (\Exception $exception){

            return \response()->json(['error' =>'Something went wrong']);
        }



    }

    public function exportPdf(Request  $request){
       $data['from_date'] = $from_date = $request->from_date;
        $data['to_date'] = $to_date = $request->to_date;

        $data['pharmacy_sales'] = $sales = PatBilling::with('purchase','entry','extraBrand','brand','surgicalBrand','stockReturn')->where(function ($query) {
            $query->orWhere('flditemtype', '=', 'Surgicals')
                ->orWhere('flditemtype', '=', 'Medicines')
                ->orWhere('flditemtype', '=', 'Extra Items');
        })
            ->where('fldtime','>=',$from_date)
            ->where('fldtime','<=',$to_date)
            ->groupBy('flditemname')
            ->get();
        return view('pharmacist::pharmacy-sales-book.pharmacy-sales-book-pdf',$data);

    }

    public function exportExcel(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if (!$from_date || !$to_date) {
            return redirect()->back();
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new PharmacySalesBookExport($from_date, $to_date), 'Pharmacy-Sales-Book-Report.xlsx');
    }
}
