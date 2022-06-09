<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReconciliationSummaryReportExport implements FromView,WithDrawings,ShouldAutoSize
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
        
        $fromdatevalue = \App\Utils\Helpers::dateEngToNepdash($filterdata['eng_from_date']);
        $nepalifromdate = $fromdatevalue->year . '-' . $fromdatevalue->month . '-' . $fromdatevalue->date;
        $nepalifrommonth = \App\Utils\Helpers::getMonthFromNepaliDate($fromdatevalue->month);

        $todatevalue = \App\Utils\Helpers::dateEngToNepdash($filterdata['eng_to_date']);
        $nepalitodate = $todatevalue->year . '-' . $todatevalue->month . '-' . $todatevalue->date;
        $nepalitomonth = \App\Utils\Helpers::getMonthFromNepaliDate($todatevalue->month);
        $datesql = "SELECT DISTINCT YEAR(fldtime) AS 'Year', MONTH(fldtime) AS 'Month' FROM tblpatbilldetail where fldtime >= '".$filterdata['eng_from_date']."' and fldtime <= '".$filterdata['eng_to_date']."'";
        $datedata = \DB::select($datesql);
        $department = $filterdata['department'];
        return view('reports::excel.reconciliation-summary-report-excel', compact('nepalifromdate','nepalitodate','filterdata','datedata','department'));
    }

}
