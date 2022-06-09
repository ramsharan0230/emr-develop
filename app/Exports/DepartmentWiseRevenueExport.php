<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DepartmentWiseRevenueExport implements FromView
{

    private $_filterdata;

    public function __construct($_filterdata = NULL) {
        $this->_filterdata = $_filterdata;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function view() : view
    {
        
        return view('billing::excel.departmentwise-revenue-excel', [
            'result' => $this->_filterdata['result'], 
            'fromdate' =>$this->_filterdata['fromdate'], 
            'todate' => $this->_filterdata['todate']
            ]);

    }
}
