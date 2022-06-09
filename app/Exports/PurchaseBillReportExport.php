<?php

namespace App\Exports;

use App\PurchaseBill;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PurchaseBillReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $fldreference)
    {
        $this->fldreference = $fldreference;
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
        $data['purchaseEntries'] = \App\Purchase::where('fldreference', $this->fldreference)->get();
        $data['purchaseBillDetails'] = PurchaseBill::where('fldreference', $this->fldreference)->first();

        return view('purchase::purchasereport-excel', $data);
    }

}
