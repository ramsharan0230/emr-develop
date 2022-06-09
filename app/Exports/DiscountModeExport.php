<?php

namespace App\Exports;

use App\Discount;
use App\Utils\Options;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DiscountModeExport implements FromView,WithDrawings,ShouldAutoSize,ShouldQueue
{
    use Exportable;
    public function __construct()
    {

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
        $discountMode = new Discount();

        $data['discountData'] = $discountMode->select('fldtype', 'fldmode', 'fldyear', 'fldamount', 'fldcredit', 'fldpercent', 'fldbillingmode', 'flduserid', 'fldtime', 'updated_by')->with('cogentUser')->get();
        return view("discountmode::export.patient-mode-excel",$data);
    }

}
