<?php

namespace App\Exports;

use App\BulkSale;
use App\StockReturn;
use App\Utils\Options;
//use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;

class StockConsumeExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date,string $reference,string $item)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->reference = $reference;
        $this->item = $item;
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
        $data['reference'] = $reference= $this->reference;
        $query = BulkSale::with('stock')->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if ($reference){
            $query->where('fldreference',$reference);
        }
        $data['references'] = $query->latest('fldtime')->get();
//        $data['references'] = BulkSale::with('stock')->where('fldreference',$reference)
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
////                ->where('fldsave','=','1')
//            ->get();
        return view('reports::stock-consume.stock-consume-excel',$data);
    }
}
