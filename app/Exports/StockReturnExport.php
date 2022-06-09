<?php

namespace App\Exports;

use App\Entry;
use App\StockReturn;
use App\Utils\Options;
//use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;

class StockReturnExport implements  FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date,string $reference, string $category)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->reference = $reference;
        $this->category = $category;
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
        $data['category'] = $category= $this->category;
        $query = StockReturn::with('entry') ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date);
        if($reference!=null && $category!=null){
            $query->where('fldcategory',$category)->where('fldnewreference',$reference);
        }
        if($reference){
            $query->where('fldnewreference',$reference);
        }
        if($category)
        {
            $query->where('fldcategory',$category);
        }
        $data['references'] = $query->latest('fldtime')->get();
//        $data['references'] = StockReturn::with('entry')->where('fldnewreference',$reference)
//            ->whereDate('fldtime', '>=', $from_date)->whereDate('fldtime', '<=', $to_date)
////                ->where('fldsave','=','1')
//            ->get();
        return view('reports::stock-return.stock-return-excel',$data);
    }
}
