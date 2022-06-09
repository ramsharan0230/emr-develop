<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MedicineExpiryReport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct()
    {
    }

    public function drawings()
    {
        $drawing = [];
        if(Options::get('brand_image') && file_exists(public_path('uploads/config/'.Options::get('brand_image')))) {
            $drawing = new Drawing(); 
            $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
            $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
            $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
            $drawing->setHeight(80);
            $drawing->setCoordinates('B2');
        }
        return $drawing;
    }

    public function view(): View
    {
        $medicines = \App\Entry::select('fldstockno','fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
        ->where('fldexpiry','!=','NULL')
        ->where([
            ['fldexpiry', '<=', date('Y-m-d') . ' 00:00:00'],
            ['fldsav', '!=', '0'],
            ['fldqty', '!=', '0'],
        ])->orderBy('fldexpiry')->with(['hasTransfer'])->get();
        
        
        return view('dispensar::reports.expiry-excel', [
            'medicines' => $medicines,
        ]);
    }

}
