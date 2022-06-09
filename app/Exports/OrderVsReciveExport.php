<?php

namespace App\Exports;

use App\Order;
use App\Utils\Options;
//use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OrderVsReciveExport implements  FromView,WithDrawings,ShouldAutoSize
{

    public function __construct(string $from_date,string $to_date,string $supplier, string $department,string $reference)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supplier = $supplier;
        $this->department = $department;
        $this->reference = $reference;
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
        $data['supplier'] = $supplier= $this->supplier;
        $data['department'] = $department= $this->department;
        $data['reference'] = $reference= $this->reference;
        $query = Order::with('purchase')
            ->whereDate('fldorddate', '>=', $from_date)
            ->whereDate('fldorddate', '<=', $to_date);
        if ($department != null && $supplier != null && $reference != null) {
            $query->where('fldcomp', $department)->where('fldsuppname', $supplier)->where('fldreference', $reference);
        }
        if ($department) {
            $query->where('fldcomp', $department);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($reference) {
            $query->where('fldreference', $reference);
        }
        $data['orders'] = $query->latest('fldorddate')->get();

        return view('reports::order-vs-recieve.order-vs-receive-excel',$data);
    }
}
