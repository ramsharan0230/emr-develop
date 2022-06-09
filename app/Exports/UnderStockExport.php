<?php

namespace App\Exports;

use App\Entry;
use App\ExtraBrand;
use App\MedicineBrand;
use App\SurgBrand;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;

class UnderStockExport implements FromView, WithDrawings, ShouldAutoSize
{

    public function __construct(string $from_date, string $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
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
                $drawing->setCoordinates('B2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }


    public function view(): View
    {
        $data['meds'] = MedicineBrand::where('fldactive','Active')->get();
        $data['surgeries'] = SurgBrand::where('fldactive','Active')->get();
        $data['extras'] = ExtraBrand::where('fldactive','Active')->get();
//        $html = '';
//        if($data){
//            if ($data['meds']) {
//                $html.='<tr><td align="center" colspan="8"><b>Medicine</b></td></tr>';
//                foreach ($data['meds'] as $datum) {
//
//                    $html .= '<tr>';
//                    $html .= '<td>' . $datum->fldbrandid . '</td>';
//                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
//                    $html .= '<td>' . $datum->fldstandard . '</td>';
//                    $html .= '<td>' . $datum->fldminqty . '</td>';
//                    $html .= '<td>' . $datum->fldleadtime . '</td>';
////                    $html .= '<td>' . '' . '</td>';
//                }
//            }
//            if($data['surgeries']){
//
//                $html.='<tr><td align="center" colspan="8"><b>Surgical</b></td></tr>';
//                foreach ($data['surgeries'] as $datum) {
//                    $html .= '<tr>';
//                    $html .= '<td>' . $datum->fldbrandid . '</td>';
//                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
//                    $html .= '<td>' . $datum->fldstandard . '</td>';
//                    $html .= '<td>' . $datum->fldminqty . '</td>';
//                    $html .= '<td>' . $datum->fldleadtime . '</td>';
////                    $html .= '<td>' . '' . '</td>';
//                }
//            }
//            if($data['extras']){
//
//                $html.='<tr><td align="center" colspan="8"><b>Extras</b></td></tr>';
//                foreach ($data['extras'] as $datum) {
//                    $html .= '<tr>';
//                    $html .= '<td>' . $datum->fldbrandid . '</td>';
//                    $html .= '<td>' . $datum->fldmanufacturer . '</td>';
//                    $html .= '<td>' . $datum->fldstandard . '</td>';
//                    $html .= '<td>' . $datum->fldminqty . '</td>';
//                    $html .= '<td>' . $datum->fldleadtime . '</td>';
////                    $html .= '<td>' . '' . '</td>';
//                }
//            }
////            return response()->json($html);
//        }
//        $data['html'] = $html;
        return view('reports::under-stock.under-stock-excel',$data);
    }


}
