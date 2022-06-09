<?php

namespace App\Exports;

use App\Adjustment;
use App\BulkSale;
use App\ConsumeReturn;
use App\Encounter;
use App\Entry;
use App\ExtraBrand;
use App\MedicineBrand;
use App\PatBilling;
use App\Purchase;
use App\StockReturn;
use App\SurgBrand;
use App\Transfer;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class ABCItemClassExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $comp, string $billing_mode)
    {
        $this->from_date = $from_date;
        // $this->to_date = $to_date;
        $this->comp = $comp;
        $this->billing_mode = $billing_mode;
    }

    public function drawings()
    {
        if(Options::get('brand_image')){
            if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
                $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            }else{
                $drawing = [];
            }
        }else{
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        $from_date = Helpers::dateNepToEng($this->from_date);
        $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
        // $to_date = Helpers::dateNepToEng($this->to_date);
        // $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
        $from_date = $this->from_date;
        // $to_date = $this->to_date;
        $comp = $this->comp;
        $billing_mode = $this->billing_mode;
        $html = '';
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
        $particulars = collect($particulars->sortBy('fldbrandid'));
        $html = "";
        $i = 1;
        foreach($particulars as $particular){
            // For Closing Stocks
            $total_stock = $this->getClosingData($finalfrom,$comp);

            $entryData = Entry::select(DB::raw("flsuppcost AS purchase_amt,fldsellprice AS sales_amt,SUM(tblentry.fldqty) AS total_stock,tblentry.fldbatch"))
                            ->leftjoin('tblpurchase','tblpurchase.fldstockno','=','tblentry.fldstockno')
                            ->when($comp != "%", function ($q) use ($comp) {
                                return $q->where('fldcomp', $comp);
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
                                        ->when($billing_mode != "%", function ($q) use ($billing_mode) {
                                            return $q->where('tblpatbilling.fldbillingmode', $billing_mode);
                                        })
                                        ->when($comp != "%", function ($q) use ($comp) {
                                            return $q->where('tblpatbilling.fldcomp', $comp);
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
                $html .= '<td>'.$sales.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($amount).'</td>';
                $html .= '</tr>';
            }
            $i++;
        }
        $data = [];
        $data['html'] = $html;
        $data['from_date'] = $finalfrom;
        // $data['to_date'] = $finalto;
        return view('abcanalysis::excel.item-class-excel', $data);
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
}
