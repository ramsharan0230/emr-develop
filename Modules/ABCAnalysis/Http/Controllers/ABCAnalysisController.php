<?php

namespace Modules\ABCAnalysis\Http\Controllers;

use App\Adjustment;
use App\BillingSet;
use App\BulkSale;
use App\ConsumeReturn;
use App\Entry;
use App\Exports\ABCItemClassExport;
use App\Exports\ABCMovingTypeExport;
use App\ExtraBrand;
use App\MedicineBrand;
use App\PatBilling;
use App\Purchase;
use App\StockReturn;
use App\SurgBrand;
use App\Transfer;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Excel;

class ABCAnalysisController extends Controller
{
    public function setups()
    {
        return view('abcanalysis::abc-setup');
    }

    public function saveItemClass(Request $request){
        try{
            Options::update('classA_consumption', $request->consumption['a']);
            Options::update('classA_revenue', $request->revenue['a']);
            Options::update('classB_consumption', $request->consumption['b']);
            Options::update('classB_revenue', $request->revenue['b']);
            Options::update('classC_consumption', $request->consumption['c']);
            Options::update('classC_revenue', $request->revenue['c']);
            return response([
                'status'=>true,
                'msg'=>"Successfully saved!"
            ]);
        } catch (\Exception $e) {
            return response([
                'status'=>false,
                'msg'=>"An error has occured!"
            ]);
        }
    }

    public function saveMovingType(Request $request){
        try{
            Options::update('abc_quan_fast', ($request->quantity['fast']) ? $request->quantity['fast'] : 0);
            Options::update('abc_quan_med', ($request->quantity['medium']) ? $request->quantity['medium'] : 0);
            Options::update('abc_quan_slow', ($request->quantity['slow']) ? $request->quantity['slow'] : 0);
            Options::update('abc_quan_non', ($request->quantity['non']) ? $request->quantity['non'] : 0);
            Options::update('abc_amt_high', ($request->amount['high']) ? $request->amount['high'] : 0);
            Options::update('abc_amt_med', ($request->amount['medium']) ? $request->amount['medium'] : 0);
            Options::update('abc_amt_low', ($request->amount['low']) ? $request->amount['low'] : 0);
            return response([
                'status'=>true,
                'msg'=>"Successfully saved!"
            ]);
        } catch (\Exception $e) {
            return response([
                'status'=>false,
                'msg'=>"An error has occured!"
            ]);
        }
    }

    public function itemClassReport()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['billingset'] = Cache::rememberForever('billing-set', function () {
            return BillingSet::get();
        });
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('abcanalysis::item-class-report',$data);
    }

    public function movingTypeReport()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['billingset'] = Cache::rememberForever('billing-set', function () {
            return BillingSet::get();
        });
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('abcanalysis::moving-type-analysis-report',$data);
    }

    public function getItemClassReport(Request $request){
        $from_date = Helpers::dateNepToEng($request->from_date);
        $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
        // $to_date = Helpers::dateNepToEng($request->to_date);
        // $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
        $classA_revenue = (Options::get('classA_revenue') != false) ? Options::get('classA_revenue') : 0;
        $classA_consumption = (Options::get('classA_consumption') != false) ? Options::get('classA_consumption') : 0;
        $classB_revenue = (Options::get('classB_revenue') != false) ? Options::get('classB_revenue') : 0;
        $classB_consumption = (Options::get('classB_consumption') != false) ? Options::get('classB_consumption') : 0;
        $classC_revenue = (Options::get('classC_revenue') != false) ? Options::get('classC_revenue') : 0;
        $classC_consumption = (Options::get('classC_consumption') != false) ? Options::get('classC_consumption') : 0;
        $medbrands = MedicineBrand::select('tblmedbrand.fldbrandid','tblmedbrand.flddrug as generic_name','tblmedbrand.fldbrand as brand_name')
                                ->leftJoin('tblentry','tblentry.fldstockid','=','tblmedbrand.fldbrandid')
                                ->where('tblentry.fldstockid','!=',null)
                                ->distinct('tblmedbrand.fldbrandid')
                                ->get()
                                ->map(function ($item) {
                                    $item['category'] = "Medicines";
                                    return $item;
                                });
        $surgbrands = SurgBrand::select('tblsurgbrand.fldbrandid','tblsurgbrand.fldsurgid as generic_name','tblsurgbrand.fldbrand as brand_name')
                                ->leftJoin('tblentry','tblentry.fldstockid','=','tblsurgbrand.fldbrandid')
                                ->where('tblentry.fldstockid','!=',null)
                                ->distinct('tblmedbrand.fldbrandid')
                                ->get()
                                ->map(function ($item) {
                                    $item['category'] = "Surgicals";
                                    return $item;
                                });
        $extrabrands = ExtraBrand::select('tblextrabrand.fldbrandid','tblextrabrand.fldextraid as generic_name','tblextrabrand.fldbrand as brand_name')
                                ->leftJoin('tblentry','tblentry.fldstockid','=','tblextrabrand.fldbrandid')
                                ->where('tblentry.fldstockid','!=',null)
                                ->distinct('tblmedbrand.fldbrandid')
                                ->get()
                                ->map(function ($item) {
                                    $item['category'] = "Extra Items";
                                    return $item;
                                });
        $particulars = collect($medbrands)->merge(collect($surgbrands));
        $particulars = collect($particulars)->merge(collect($extrabrands));
        if($request->has('typePdf')){
            $particulars = collect($particulars->sortBy('fldbrandid'));
        }else{
            $particulars = collect($particulars->sortBy('fldbrandid'))->paginate(50);
        }
        $html = "";
        $i = 1;
        foreach($particulars as $particular){
            // For Closing Stocks
            $total_stock = $this->getClosingData($finalfrom,$request->comp);

            $entryData = Entry::select(DB::raw("flsuppcost AS purchase_amt,fldsellprice AS sales_amt,SUM(tblentry.fldqty) AS total_stock,tblentry.fldbatch"))
                            ->leftjoin('tblpurchase','tblpurchase.fldstockno','=','tblentry.fldstockno')
                            ->when($request->comp != "%", function ($q) use ($request) {
                                return $q->where('fldcomp', $request->comp);
                            })
                            ->where('tblentry.fldstockid',$particular->fldbrandid)
                            ->where('tblentry.fldsav',1)
                            ->groupBy('tblentry.fldbatch')
                            ->get();
            $maxRow = count($entryData);
            foreach($entryData as $entry_key => $entry){
                $patbilldata = PatBilling::select(DB::raw("SUM(tblpatbilling.flditemqty) AS sales,SUM(tblpatbilling.fldditemamt) AS amt"))
                                        ->leftJoin('tblentry','tblentry.fldstockno','=','tblpatbilling.flditemno')
                                        ->where('tblpatbilling.flditemname',$particular->fldbrandid)
                                        ->where('tblpatbilling.fldsave',1)
                                        ->where('tblentry.fldbatch',$entry->fldbatch)
                                        ->when($request->billing_mode != "%", function ($q) use ($request) {
                                            return $q->where('tblpatbilling.fldbillingmode', $request->billing_mode);
                                        })
                                        ->when($request->comp != "%", function ($q) use ($request) {
                                            return $q->where('tblpatbilling.fldcomp', $request->comp);
                                        })
                                        ->first();
                $sales = ($patbilldata->sales) ? $patbilldata->sales : 0;
                $amount = ($patbilldata->amt) ? $patbilldata->amt : 0;

                if($total_stock != 0){
                    $consumption_percent = 100 - ((($total_stock - $sales) / $total_stock) * 100);
                }else{
                    $consumption_percent = 0;
                }

                $sales_amt = ($entry->sales_amt) ? ($entry->sales_amt * $entry->total_stock) : 0;
                $purchase_amt = ($entry->purchase_amt) ? ($entry->purchase_amt * $entry->total_stock) : 0;
                if($purchase_amt != 0){
                    $revenue_percent = (($sales_amt - $purchase_amt) / $purchase_amt) * 100;
                }else{
                    $revenue_percent = 0;
                }
                $itemclass = "Class 'C'";
                if($revenue_percent != 0 && $consumption_percent != 0){
                    $division = $revenue_percent / $consumption_percent;
                    $chkClassA = $division * $classA_consumption;
                    if($chkClassA >= $classA_revenue){
                        $itemclass = "Class 'A'";
                    }else{
                        $chkClassB = $division * $classB_consumption;
                        if($chkClassB >= $classB_revenue){
                            $itemclass = "Class 'B'";
                        }
                    }
                }
                $html .= '<tr>';
                if($entry_key == 0){
                    $html .= '<td rowspan="'.$maxRow.'">'.$i.'</td>';
                }
                if($entry_key == 0){
                    if($particular != null){
                        $html .= '<td rowspan="'.$maxRow.'">'.$particular->generic_name.'</td>';
                    }else{
                        $html .= '<td rowspan="'.$maxRow.'"></td>';
                    }
                }
                if($entry_key == 0){
                    if($particular != null){
                        $html .= '<td rowspan="'.$maxRow.'">'.$particular->brand_name.'</td>';
                    }else{
                        $html .= '<td rowspan="'.$maxRow.'"></td>';
                    }
                }
                $html .= '<td>'.$particular->category.'</td>';
                $html .= '<td>'.$entry->fldbatch.'</td>';
                // $html .= '<td>'.$total_stock.'</td>';
                $html .= '<td>'.$itemclass.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($sales).'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($amount).'</td>';
                $html .= '</tr>';
            }
            $i++;
        }
        if(!$request->has('typePdf')){
            $html .= '<tr><td colspan="8">' . $particulars->appends(request()->all())->render() . '</td></tr>';
        }
        if($request->has('typePdf')){
            $data = [];
            $data['html'] = $html;
            $data['from_date'] = $finalfrom;
            // $data['to_date'] = $finalto;
            $data['analysis_type'] = $request->analysis_type;
            $data['certificate'] = "ABC Analysis Item Type ";
            return view('abcanalysis::pdf.item-class-pdf', $data);
        }else{
            return response()->json([
                'status' => true,
                'html' => $html,
            ]);
        }
    }

    function getClosingData($date,$comp){
        $purchaseClose = Purchase::select(DB::raw("SUM(fldtotalqty) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsav',1)
                        ->first();

        $stockreceivedClose = Transfer::select(DB::raw("SUM(fldqty) AS balqty"))
                        ->where('fldtoentrytime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldtocomp', $comp);
                        })
                        ->where('fldtosav',1)
                        ->first();

        $stocktransferredClose = Transfer::select(DB::raw("(0 - SUM(fldqty)) AS balqty"))
                        ->where('fldfromentrytime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldfromcomp', $comp);
                        })
                        ->where('fldfromsav',1)
                        ->first();

        $stockreturnClose = StockReturn::select(DB::raw("(0 - SUM(fldqty)) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsave',1)
                        ->first();

        $consumeClose = BulkSale::select(DB::raw("(0 - SUM(fldqtydisp)) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsave',1)
                        ->first();

        $consumereturnClose = ConsumeReturn::select(DB::raw("SUM(fldqty) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsave',1)
                        ->first();

        $exportadjustClose = Adjustment::select(DB::raw("SUM(fldcurrqty-fldcompqty) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsav',1)
                        ->first();

        $dispenseClose = PatBilling::select(DB::raw("SUM(0 - flditemqty) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsave',1)
                        ->where('fldbillno',"LIKE",'PHM%')
                        ->first();

        $cancelClose = PatBilling::select(DB::raw("SUM(flditemqty) AS balqty"))
                        ->where('fldtime','<=',$date)
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('fldcomp', $comp);
                        })
                        ->where('fldsave',1)
                        ->where('fldbillno',"LIKE",'RET%')
                        ->first();

        $closing = $purchaseClose->balqty + $stockreceivedClose->balqty + $stocktransferredClose->balqty + $stockreturnClose->balqty + $consumeClose->balqty + $consumereturnClose->balqty + $exportadjustClose->balqty + $dispenseClose->balqty + $cancelClose->balqty;
        return $closing;
    }

    public function getMovingTypeReport(Request $request){
        $from_date = Helpers::dateNepToEng($request->from_date);
        $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
        $to_date = Helpers::dateNepToEng($request->to_date);
        $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
        $html = '';
        $patbilldatas = PatBilling::select(\DB::raw('SUM(flditemqty) as sold_qty'),'flditemrate as unit_price',\DB::raw('SUM(fldditemamt) as tot_amt'),'fldcomp','flduserid','flditemname','flditemtype','flditemno','fldbillingmode')
                                ->whereIn('fldstatus',['Done','Cleared'])
                                ->when($request->billing_mode != "%", function ($q) use ($request) {
                                    return $q->where('fldbillingmode', $request->billing_mode);
                                })
                                ->when($request->comp != "%", function ($q) use ($request) {
                                    return $q->where('fldcomp', $request->comp);
                                })
                                ->whereIn('flditemtype',['Medicines','Surgicals','Extra Items'])
                                ->when(($request->from_date == $request->to_date) && $request->from_date != "" && $request->to_date != "", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($request->from_date != $request->to_date) && $request->from_date != "", function ($q) use ($finalfrom) {
                                    return $q->where('fldtime', '>=', $finalfrom);
                                })
                                ->when(($request->from_date != $request->to_date) && $request->to_date != "", function ($q) use ($finalto) {
                                    return $q->where('fldtime', "<=", $finalto);
                                })
                                ->groupBy(['flditemname','flditemrate']);
        if($request->has('typePdf')){
            $patbilldatas = $patbilldatas->get();
        }else{
            $patbilldatas = $patbilldatas->paginate(50);
        }
        if($request->analysis_type == 'quantity'){
            $abc_quan_fast = (Options::get('abc_quan_fast') != false) ? Options::get('abc_quan_fast') : 0;
            $abc_quan_med = (Options::get('abc_quan_med') != false) ? Options::get('abc_quan_med') : 0;
            $abc_quan_slow = (Options::get('abc_quan_slow') != false) ? Options::get('abc_quan_slow') : 0;
            $abc_quan_non = (Options::get('abc_quan_non') != false) ? Options::get('abc_quan_non') : 0;
            foreach($patbilldatas as $key=>$patbilldata){
                $html .= '<tr>';
                if($patbilldata->flditemtype == "Medicines"){
                    $item = MedicineBrand::select('flddrug as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }elseif($patbilldata->flditemtype == "Surgicals"){
                    $item = SurgBrand::select('fldsurgid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }else{
                    $item = ExtraBrand::select('fldextraid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }
                $html .= '<td>'.++$key.'</td>';
                if($item != null){
                    $html .= '<td>'.$item->generic.'</td>';
                }else{
                    $html .= '<td></td>';
                }
                if($item != null){
                    $html .= '<td>'.$item->brand.'</td>';
                }else{
                    $html .= '<td></td>';
                }
                $html .= '<td>'.$patbilldata->flditemtype.'</td>';
                $html .= '<td>'.$patbilldata->sold_qty.'</td>';

                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->unit_price).'</td>';
                $moving_type = "";
                if($patbilldata->sold_qty >= $abc_quan_fast){
                    $moving_type .= "Fast";
                }else{
                    if($patbilldata->sold_qty >= $abc_quan_med){
                        $moving_type .= "Medium";
                    }else{
                        if($patbilldata->sold_qty >= $abc_quan_slow){
                            $moving_type .= "Slow";
                        }else{
                            if($patbilldata->sold_qty >= $abc_quan_non){
                                $moving_type .= "Non";
                            }else{
                                $moving_type .= "-";
                            }
                        }
                    }
                }
                $html .= '<td>'.$moving_type.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->tot_amt).'</td>';
                $html .= '</tr>';
            }
            if(!$request->has('typePdf')){
                $html .= '<tr><td colspan="8">' . $patbilldatas->appends(request()->all())->render() . '</td></tr>';
            }
        }else{
            $abc_amt_high = (Options::get('abc_amt_high') != false) ? Options::get('abc_amt_high') : 0;
            $abc_amt_med = (Options::get('abc_amt_med') != false) ? Options::get('abc_amt_med') : 0;
            $abc_amt_low = (Options::get('abc_amt_low') != false) ? Options::get('abc_amt_low') : 0;
            foreach($patbilldatas as $key=>$patbilldata){
                $html .= '<tr>';
                if($patbilldata->flditemtype == "Medicines"){
                    $item = MedicineBrand::select('flddrug as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }elseif($patbilldata->flditemtype == "Surgicals"){
                    $item = SurgBrand::select('fldsurgid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }else{
                    $item = ExtraBrand::select('fldextraid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }
                $html .= '<td>'.++$key.'</td>';
                $html .= '<td>'.$item->generic.'</td>';
                $html .= '<td>'.$item->brand.'</td>';
                $html .= '<td>'.$patbilldata->flditemtype.'</td>';
                $html .= '<td>'.$patbilldata->sold_qty.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->unit_price).'</td>';
                $value_type = "";
                if($patbilldata->sold_qty >= $abc_amt_high){
                    $value_type .= "High";
                }else{
                    if($patbilldata->sold_qty >= $abc_amt_med){
                        $value_type .= "Medium";
                    }else{
                        if($patbilldata->sold_qty >= $abc_amt_low){
                            $value_type .= "Low";
                        }else{
                            $value_type .= "-";
                        }
                    }
                }
                $html .= '<td>'.$value_type.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->tot_amt).'</td>';
                $html .= '</tr>';
            }
            if(!$request->has('typePdf')){
                $html .= '<tr><td colspan="8">' . $patbilldatas->appends(request()->all())->render() . '</td></tr>';
            }
        }
        if($request->has('typePdf')){
            $data = [];
            $data['html'] = $html;
            $data['from_date'] = $finalfrom;
            $data['to_date'] = $finalto;
            $data['analysis_type'] = $request->analysis_type;
            $data['certificate'] = "ABC Analysis Moving Type ";
            return view('abcanalysis::pdf.moving-type-pdf', $data);
        }else{
            return response()->json([
                'status' => true,
                'html' => $html,
            ]);
        }
    }

    public function exportMovingTypeReportCsv(Request $request)
    {
        $export = new ABCMovingTypeExport($request->from_date, $request->to_date, $request->analysis_type, $request->comp,$request->billing_mode);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'ABCMovingTypeReport.xlsx');
    }

    public function exportItemClassReportCsv(Request $request)
    {
        $export = new ABCItemClassExport($request->from_date,$request->comp,$request->billing_mode);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'ABCItemClassReport.xlsx');
    }

}
