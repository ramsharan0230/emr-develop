<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;

class DepartmentCollectionExport implements FromView
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

    public function view() : View
    {

        return view('billing::excel.department-collection-excel',  [
            'result' => $this->_filterdata['result'],
            'fromdate' =>$this->_filterdata['fromdate'],
            'todate' => $this->_filterdata['todate'],
            'eng_from_date' => $this->_filterdata['eng_from_date'],
            'eng_to_date' => $this->_filterdata['eng_to_date'],
            ]);
    }
}
