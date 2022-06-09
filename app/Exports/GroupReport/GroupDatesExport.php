<?php

namespace App\Exports\GroupReport;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class GroupDatesExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $req;

    public function __construct(array $request)
    {
        $this->req = $request;
    }

    public function view(): View
    {
        $from_date = Helpers::dateNepToEng($this->req['from_date']);
        $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
        $to_date = Helpers::dateNepToEng($this->req['to_date']);
        $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
        $alldata['dateType'] = $dateType = $this->req['dateType'];
        $alldata['itemRadio'] = $itemRadio = $this->req['itemRadio'];
        $alldata['billingmode'] = $billingmode = $this->req['billingmode'];
        $alldata['comp'] = $comp = $this->req['comp'];
        $alldata['selectedItem'] = $selectedItem = $this->req['selectedItem'];
        $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'),\DB::raw('SUM(tblpatbilling.flditemqty) as qnty'),\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as totl'),'tblpatbilling.flditemtype as flditemtype',\DB::raw('DATE(tblpatbilling.fldtime) as entry_date'),'tblreportgroup.fldgroup as fldgroup')
                            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                            ->where('tblpatbilling.fldsave',1)
                            ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                            })
                            ->when($comp != "%", function ($q) use ($comp){
                                return $q->where('tblpatbilling.fldcomp','like',$comp);
                            })
                            ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                            ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom)
                                        ->groupBy('entry_date');
                            })
                            ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                        ->where('tblpatbilling.fldtime','<=',$finalto)
                                        ->groupBy('entry_date');
                            })
                            ->get()
                            ->groupBy('fldgroup');
            $alldata['certificate'] = "DATE";
        return view('reports::groupreport.excel.export-dates-excel',$alldata);
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
