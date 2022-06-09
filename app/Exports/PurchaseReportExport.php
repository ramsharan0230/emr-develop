<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\DB;

class PurchaseReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(array $filterdata)
    {
        $this->filterdata = $filterdata;
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
        
        $filterdata = $this->filterdata;
        $startTime = $filterdata['eng_from_date'];
        $endTime = $filterdata['eng_to_date'];
        $department = $filterdata['department'];

        $purchaseDatas =   DB::table('tblpurchasebill')
                    ->select('tblpurchasebill.fldsuppname as Supplier_Name',
                    'tblpurchasebill.fldpurdate as Pur_Date',
                    'tblpurchasebill.fldreference as Purchase_Reference',
                    DB::raw('sum(IFNULL(tblpurchasebill.vatableamount,0)) as Taxable_Amount'),
                    DB::raw('sum(IFNULL(tblpurchasebill.nonvatableamount,0)) as NonTaxable_Amount'),
                    DB::raw('sum(IFNULL(tblpurchasebill.fldtotaltax,0)) as Tax'))
    
                    ->join('tblpurchase','tblpurchase.fldreference','=','tblpurchasebill.fldreference')
                    ->when($startTime != null, function ($q) use ($startTime) {
                        return $q->where('tblpurchasebill.fldpurdate', '>=', $startTime);
                    })
                    ->when($endTime != null, function ($q) use ($endTime) {
                        return $q->where('tblpurchasebill.fldpurdate', '<=', $endTime);
                    })
                    ->when($department != null, function ($q) use ($department) {
                        return $q->where('tblpurchase.fldcomp', '=', $department);
                    })
                    ->groupBy('tblpurchase.fldreference')
                    ->orderBy('tblpurchasebill.fldpurdate','desc')
                    ->get();

        $fromdatevalue = \App\Utils\Helpers::dateEngToNepdash($filterdata['eng_from_date']);
        $nepalifromdate = $fromdatevalue->year . '-' . $fromdatevalue->month . '-' . $fromdatevalue->date;
        $nepalifrommonth = \App\Utils\Helpers::getMonthFromNepaliDate($fromdatevalue->month);

        $todatevalue = \App\Utils\Helpers::dateEngToNepdash($filterdata['eng_to_date']);
        $nepalitodate = $todatevalue->year . '-' . $todatevalue->month . '-' . $todatevalue->date;
        $nepalitomonth = \App\Utils\Helpers::getMonthFromNepaliDate($todatevalue->month);

        $month = '';
        if($nepalifrommonth == $nepalitomonth){
            $month = $nepalifrommonth;
        }
        $year = $fromdatevalue->year;
        $purchasedata = $purchaseDatas;
        
        return view('irdpurchasereport::purchaseReportExcel', compact('year','purchasedata','month'));
    }

}
