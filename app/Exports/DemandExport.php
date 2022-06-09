<?php

namespace App\Exports;

use App\Demand;
use App\Utils\Helpers;
use App\Utils\Options;
//use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DemandExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function __construct(string $from_date, string $to_date, string $supplier, string $department, string $bill)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supplier = $supplier;
        $this->department = $department;
        $this->bill = $bill;
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
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date = $this->to_date;
        $data['supplier'] = $supplier = $this->supplier;
        $data['department'] = $department = $this->department;
        $data['bill'] = $bill = $this->bill;
        $query = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date);

        if ($supplier != null && $department != null && $bill != null) {
            $query->where('fldsuppname', $supplier)->where('fldcomp_order', $department)->where('fldquotationno', $bill);
        }
        if ($supplier) {
            $query->where('fldsuppname', $supplier);
        }
        if ($department) {
            $query->where('fldcomp_order', $department);
        }
        if ($bill) {
            $query->where('fldquotationno', $bill);
        }
        $data['demands'] = $query->get();
//        $data['demands'] = Demand::with('hospitalDepartment')->whereDate('fldordersavedtime', '>=', $from_date)->whereDate('fldordersavedtime', '<=', $to_date)->get();
        return view('reports::demandReport.demand-excel', $data);
    }

}
