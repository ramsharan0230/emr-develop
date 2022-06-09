<?php

namespace App\Exports;

use App\Entry;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InventoryTransactionReportExport implements FromView, WithDrawings, ShouldAutoSize
{
    /**
     * @var array
     */
    private $filterdata;

    public function __construct(array $filterdata)
    {
        $this->filterdata = $filterdata;
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

    /**
     * @return View
     */
    public function view(): View
    {
        $filterdatanew = $this->filterdata;

        $resultData = Entry::whereHas('medbrand')
            ->where(function ($query) use ($filterdatanew) {
                if ($filterdatanew['comp']) {
                    $query->where('fldcomp', 'LIKE', $filterdatanew['comp']);
                }
            })
            ->with('medbrand')
            ->with(['multiplePurchase' => function ($multiplePurchasequery) use ($filterdatanew) {
                $multiplePurchasequery->where('fldsav', False);
                $multiplePurchasequery->where(function ($multiplePurchasequeryNested) {
                    $multiplePurchasequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
            }])
            ->with(['patBillingByName' => function ($query) use ($filterdatanew) {
                $query->where('fldsave', True);
                $query->where(function ($queryNested) {
                    $queryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                if ($filterdatanew['comp']) {
                    $query->whereRaw('LOWER(`fldcomp`) LIKE ? ', [trim(strtolower($filterdatanew['comp']))]);
                }
            }])
            ->with(['transfer' => function ($transferquery) use ($filterdatanew) {
                $transferquery->where('fldtosav', True);
                $transferquery->where(function ($transferqueryNested) {
                    $transferqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                if ($filterdatanew['comp']) {
                    $transferquery->where(function ($transferqueryNested2) use ($filterdatanew) {
                        $transferqueryNested2->where('fldfromcomp', 'like', $filterdatanew['comp']);
                        $transferqueryNested2->where('fldtocomp', 'like', $filterdatanew['comp']);
                    });
                }
            }])
            ->with(['bulkSale' => function ($bulkSalequery) use ($filterdatanew) {
                $bulkSalequery->where('fldsave', True);
                $bulkSalequery->where(function ($bulkSalequeryNested) {
                    $bulkSalequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                if ($filterdatanew['comp']) {
                    $bulkSalequery->whereRaw('LOWER(`fldcomp`) LIKE ? ', [trim(strtolower($filterdatanew['comp']))]);
                }
            }])
            ->with(['adjustment' => function ($adjustmentquery) use ($filterdatanew) {
                $adjustmentquery->where('fldsav', True);
                $adjustmentquery->where(function ($adjustmentqueryNested) {
                    $adjustmentqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });

                if ($filterdatanew['comp']) {
                    $adjustmentquery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($filterdatanew['comp']))]);
                }
            }])
            ->limit(200)->get();

        return view('reports::inventory.inventory-excel', compact('resultData'));
    }

}
