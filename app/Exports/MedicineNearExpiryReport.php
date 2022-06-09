<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MedicineNearExpiryReport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $expiry_limit)
    {
        $this->expiry_limit = $expiry_limit;
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
            ->where([
                ['fldexpiry', '<=', $this->expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldsav', '!=', '0'],
                ['fldqty', '!=', '0'],
            ])->orderBy('fldexpiry')->get();

        return view('dispensar::reports.near-expiry-excel', [
            'medicines' => $medicines,
            'expiry_limit' => $this->expiry_limit,
        ]);
    }

}
