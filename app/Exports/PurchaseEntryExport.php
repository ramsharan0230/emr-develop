<?php

namespace App\Exports;

use App\Entry;
use App\Order;
use App\Utils\Options;
//use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;

class PurchaseEntryExport implements  FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date,string $supplier, string $department,string $opening,string $bill)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supplier = $supplier;
        $this->department = $department;
        $this->opening = $opening;
        $this->bill = $bill;
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
        $data['bill'] = $bill= $this->bill;
        $data['opening'] = $opening= $this->opening;

        $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
            $q->whereDate('fldpurdate', '>=', $from_date);
            $q->whereDate('fldpurdate', '<=', $to_date);
        });

        if ($department) {
            $query->where('fldcomp', $department);
        }

        if ($opening) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldisopening', 1);
            });
        }

        if ($supplier) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
            });
        }
        if ($bill) {
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldreference', $bill);
            });
        }
        if($supplier && $opening){
            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldisopening', 1);
            });
        }


        if ($supplier != null && $bill != null && $department != null && $opening != null) {

            $query = Entry::whereHas('purchase', function($q) use ($from_date,$to_date,$supplier,$bill){
                $q->whereDate('fldpurdate', '>=', $from_date);
                $q->whereDate('fldpurdate', '<=', $to_date);
                $q->where('fldsuppname', $supplier);
                $q->where('fldreference', $bill);
                $q->where('fldisopening', 1);
            })->where([
                ['fldcomp', '<=', $department],
            ]);
        }

        $data['entries'] = $query->latest('fldexpiry')->get();

        return view('reports::purchase-entry.purchase-entry-excel',$data);
    }
}
