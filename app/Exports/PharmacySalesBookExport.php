<?php

namespace App\Exports;

use App\PatBilling;
use App\Transfer;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PharmacySalesBookExport implements  FromView,WithDrawings,ShouldAutoSize
{

    public function __construct(string $from_date,string $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
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
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date= $this->to_date;

        $data['pharmacy_sales'] = $sales = PatBilling::with('purchase','entry','extraBrand','brand','surgicalBrand','stockReturn')->where(function ($query) {
            $query->orWhere('flditemtype', '=', 'Surgicals')
                ->orWhere('flditemtype', '=', 'Medicines')
                ->orWhere('flditemtype', '=', 'Extra Items');
        })
//            ->where('fldtime','>=',$from_date)
//            ->where('fldtime','<=',$to_date)
            ->groupBy('flditemname')
            ->get();
        return view('pharmacist::pharmacy-sales-book.pharmacy-sales-book-excel',$data);
    }

}
