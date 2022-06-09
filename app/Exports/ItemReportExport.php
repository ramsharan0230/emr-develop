<?php

namespace App\Exports;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date = null,string $to_date = null, string $dateType = null, string $itemRadio = null, string $category = null, string $billingmode = null, string $comp = null, string $selectedItem = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->dateType = $dateType;
        $this->itemRadio = $itemRadio;
        $this->category = $category;
        $this->billingmode = $billingmode;
        $this->comp = $comp;
        $this->selectedItem = $selectedItem;
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
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date. " 00:00:00";
            $to_date = Helpers::dateNepToEng($this->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date. " 23:59:59";
            $dateType = $this->dateType;
            $alldata['itemRadio'] = $itemRadio = $this->itemRadio;
            $alldata['category'] = $category = $this->category;
            $alldata['billingmode'] = $billingmode = $this->billingmode;
            $alldata['comp'] = $comp = $this->comp;
            $alldata['selectedItem'] = $selectedItem = $this->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.flditemname', 'tblpatbilling.flditemrate', 'tblpatbilling.flditemqty', 'tblpatbilling.flddiscamt', 'tblpatbilling.fldtaxamt', 'tblpatbilling.fldditemamt as tot', 'tblpatbilling.fldtime as entrytime', 'tblpatbilling.fldbillno', 'tblpatbilling.fldtempbillno', 'tblpatbilling.fldtempbilltransfer', 'tblpatbilling.fldid', 'tblpatbilling.fldpayto', 'tblpatbilling.fldrefer', 'tblpatbilling.fldditemamt', 'package_name')
                ->where('tblpatbilling.fldsave', 1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->when($category != "%" && $itemRadio != "packages", function ($q) use ($category) {
                    return $q->where('tblpatbilling.flditemtype', 'like', $category);
                })
                ->when($comp != "%", function ($q) use ($comp) {
                    return $q->where('tblpatbilling.fldcomp', 'like', $comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode) {
                    return $q->where('tblpatbilling.fldbillingmode', 'like', $billingmode);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when($itemRadio == "packages", function ($q) use ($selectedItem) {
                    $q->when($selectedItem != 'N/A', function ($query) use ($selectedItem) {
                        return $query->where('package_name', 'like', $selectedItem );
                    })->when($selectedItem == 'N/A', function ($query) {
                        return $query->whereNull('package_name');
                    });
                })
                // ->when($dateType == "invoice_date", function ($q) {
                //     return $q->orderBy('tblpatbilldetail.fldtime', 'asc');
                // })
                ->when($dateType == "entry_date", function ($q) {
                    return $q->orderBy('tblpatbilling.fldtime', 'asc');
                })
                ->get()
                ->groupBy(['fldbillno']);

        $alldata['itemRadio'] = $itemRadio;
        return view('reports::itemreport.export-excel', $alldata);
    }

}
