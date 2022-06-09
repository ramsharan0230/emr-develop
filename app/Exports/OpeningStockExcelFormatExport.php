<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OpeningStockExcelFormatExport implements WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function headings(): array
    {
        return [
            'Category(Medicines/Surgicals/Extra Item)',
            'Stock Id',
            'Batch',
            'Expiry',
            'Total Qty',
            'Cash Discount',
            'Cash Bonus',
            'Qty Bonus',
            'Carry Cost',
            'Supp Cost',
            'Net Cost',
            'Selling Price',
            'Total Price',
            'Bar Code',
            'Vat Amount'
        ];
    }
}
