<?php

namespace App\Exports\ItemReport;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemDetailsExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $req;

    public function __construct(array $request)
    {
        $this->req = $request;
    }

    public function view(): View
    {
        $from_date = Helpers::dateNepToEng($this->req['from_date']);
        $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
        $to_date = Helpers::dateNepToEng($this->req['to_date']);
        $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
        $alldata['dateType'] = $dateType = $this->req['dateType'];
        $alldata['itemRadio'] = $itemRadio = $this->req['itemRadio'];
        $alldata['category'] = $category = $this->req['category'];
        $alldata['billingmode'] = $billingmode = $this->req['billingmode'];
        $alldata['comp'] = $comp = $this->req['comp'];
        $departments = $this->req['departments'];
        $alldata['selectedItem'] = $selectedItem = $this->req['selectedItem'];
        $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.flditemrate as rate', 'tblpatbilling.flditemqty as qnty', 'tblpatbilling.flddiscamt as dsc', 'tblpatbilling.fldtaxamt as tax', 'tblpatbilling.fldditemamt as totl', 'tblpatbilling.flditemtype as flditemtype', 'tblpatbilling.flditemname as flditemname', 'tblpatbilling.fldtime as entrytime')
            ->where('tblpatbilling.fldsave', 1)
            ->where('tblpatbilling.flditemtype', 'like', $category)
            ->where('tblpatbilling.fldcomp', 'like', $comp)
            ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
            ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
            })
            ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
            })
            ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                    ->where('tblpatbilling.fldtime', '<=', $finalto);
            })
            ->get()
            ->groupBy('flditemtype');
        $alldata['certificate'] = "ITEM DETAIL";
        return view('reports::itemreport.excel.export-details-excel', $alldata);
    }

    public function drawings()
    {
        if (Options::get('brand_image')) {
            if (file_exists(public_path('uploads/config/' . Options::get('brand_image')))) {
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan']) ? Options::get('siteconfig')['system_slogan'] : '');
                $drawing->setPath(public_path('uploads/config/' . Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('A2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }
}
