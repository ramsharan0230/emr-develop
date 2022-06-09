<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SalesReportExport implements FromView,WithDrawings,ShouldAutoSize
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
        $salesData = \DB::table('tblpatbilling as pb')
                    ->select('pb.fldtime', 'pb.fldbillno', 'p.fldpannumber', 'pb.flditemname', 'pb.flditemqty', 'pb.flditemrate', 'pb.fldditemamt', 'pb.fldtaxamt', 'pb.flddiscamt', 'pb.fldcomp',\DB::raw('CONCAT(p.fldptnamefir," " ,p.fldptnamelast) as patientname'))
                    ->join('tblencounter as e','e.fldencounterval','pb.fldencounterval')
                    ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                    ->where('pb.fldtime','>',$filterdata['eng_from_date'].' 00:00:00')
                    ->where('pb.fldtime','<',$filterdata['eng_to_date'].' 23:59:59')
                    ->where('pb.fldcomp',$filterdata['department'])
                    ->where('pb.fldsave','1')
                    ->whereNotNull('pb.fldbillno')
                    ->orderBy('pb.fldtime','ASC')
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
        $taxduration = \DB::table('tblyear')->select('fldname')->where('fldfirst','<=',$filterdata['eng_from_date'])->where('fldlast','>=', $filterdata['eng_from_date'])->first();
       // dd($taxduration);
        $year = $fromdatevalue->year;
        $salesdata = $salesData;
        return view('billing::excel.sales-report-excel', compact('taxduration','year','salesdata','month'));
    }

}
