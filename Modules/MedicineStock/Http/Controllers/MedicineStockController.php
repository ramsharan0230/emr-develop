<?php

namespace Modules\MedicineStock\Http\Controllers;

use App\Entry;
use App\PatBilling;
use App\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MedicineStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('medicinestock::index');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function displayStock(Request $request)
    {
        if (isset($request->department)) {
            $department = $request->department;
        } else {
            $department = 'comp01';
        }

        if ($request->stockType && $request->stockType == "purchase") {
            return $this->purchaseData($request->department, $request->itemStockName);
        } else {
            return $this->salesData($department, $request->itemStockName);
        }
    }

    /**
     * @param $department
     * @param $itemStockName
     * @return array|string
     * @throws \Throwable
     */
    public function salesData($department, $itemStockName)
    {
        if ($department) {
            $data['departmentComp'] = strtolower($department);
        }

        $last_night = date('Y-m-d H:i:s', strtotime('today midnight'));
        $data['tableEntry'] = PatBilling::selectRaw('*, sum(flditemqty) as qty')
            ->where('fldsave', True)
            ->where(function ($queryNested) {
                $queryNested->orWhere('flditemtype', 'Surgicals')
                    ->orWhere('flditemtype', 'Medicines')
                    ->orWhere('flditemtype', 'Extra Items');
            })
            ->where(function ($queryNested) use ($itemStockName) {
                if ($itemStockName != "") {
                    $queryNested->where('flditemname', 'LIKE', '%' . $itemStockName . '%');
                }
            })
            ->where('fldtime', '>', $last_night)
            ->where('fldtime', '<', date('Y-m-d H:i:s'))
            ->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))])
            ->with(['medicine' => function ($query) use ($department) {
                $query->where('fldsav', 1);
                $query->where(function ($queryNested) {
                    $queryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $query->where('fldtime', '>', $last_night);
//                $query->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department != "") {
                    $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);

                }
            }])
            ->with(['medicine.multiplePurchase' => function ($multiplePurchasequery) use ($last_night) {
                $multiplePurchasequery->where('fldsav', False);
                $multiplePurchasequery->where(function ($multiplePurchasequeryNested) {
                    $multiplePurchasequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                $multiplePurchasequery->where('fldtime', '>', $last_night);
                $multiplePurchasequery->where('fldtime', '<', date('Y-m-d H:i:s'));
            }])
            ->with(['medicine.bulkSale' => function ($bulkSalequery) use ($last_night, $department) {
                $bulkSalequery->where('fldsave', True);
                $bulkSalequery->where(function ($bulkSalequeryNested) {
                    $bulkSalequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                $bulkSalequery->where('fldtime', '>', $last_night);
                $bulkSalequery->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $bulkSalequery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            }])
            ->with(['medicine.transfer' => function ($transferquery) use ($last_night, $department) {
                $transferquery->where('fldtosav', True);
                $transferquery->where(function ($transferqueryNested) {
                    $transferqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                $transferquery->where('fldtoentrytime', '>', $last_night);
                $transferquery->where('fldtoentrytime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $transferquery->where(function ($transferqueryNested2) use ($department) {
                        $transferqueryNested2->where('fldfromcomp', 'like', $department);
                        $transferqueryNested2->where('fldtocomp', 'like', $department);
                    });
                }
            }])
            ->with(['medicine.adjustment' => function ($adjustmentquery) use ($last_night, $department) {
                $adjustmentquery->where('fldsav', True);
                $adjustmentquery->where(function ($adjustmentqueryNested) {
                    $adjustmentqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
                $adjustmentquery->where('fldtime', '>', $last_night);
                $adjustmentquery->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $adjustmentquery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            }])
            ->orderBy('fldtime', 'Desc')
            ->groupBy('flditemname')
            ->get();


        $html = view('medicinestock::stock-list', $data)->render();
        return $html;
    }

    /**
     * @param $department
     * @param $itemStockName
     * @return array|string
     * @throws \Throwable
     */
    public function purchaseData($department, $itemStockName)
    {
        try {
            if ($department) {
                $data['departmentComp'] = strtolower($department);
            }

            $last_night = date('Y-m-d H:i:s', strtotime('today midnight'));
            $data['tableEntry'] = Purchase::selectRaw('*, sum(fldtotalqty) as qty')
                ->where(function ($multiplePurchasequeryNested) {
                    $multiplePurchasequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                })
                ->where(function ($queryNested) use ($itemStockName) {
                    if ($itemStockName != "") {
                        $queryNested->where('fldstockid', 'LIKE', '%' . $itemStockName . '%');
                    }
                })
                ->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))])
                ->where('fldtime', '>', $last_night)
                ->where('fldtime', '<', date('Y-m-d H:i:s'))
                //entry table
                ->with(['EntryByStockName' => function ($query) use ($department) {
                    $query->where('fldsav', True);
                    $query->where(function ($queryNested) {
                        $queryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    if ($department) {
                        $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->with(['EntryByStockName.patBillingByName' => function ($query) use ($department) {
                    $query->where('fldsave', True);
                    $query->selectRaw('*, sum(flditemqty) as qty');
                    $query->where(function ($queryNested) {
                        $queryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    if ($department) {
                        $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->with(['EntryByStockName.transfer' => function ($transferquery) use ($last_night, $department) {
                    $transferquery->where('fldtosav', True);
                    $transferquery->where(function ($transferqueryNested) {
                        $transferqueryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    $transferquery->where('fldtoentrytime', '>', $last_night);
                    $transferquery->where('fldtoentrytime', '<', date('Y-m-d H:i:s'));
                    if ($department) {
                        $transferquery->where(function ($transferqueryNested2) use ($department) {
                            $transferqueryNested2->where('fldfromcomp', 'like', $department);
                            $transferqueryNested2->where('fldtocomp', 'like', $department);
                        });
                    }
                }])
                ->with(['EntryByStockName.bulkSale' => function ($bulkSalequery) use ($last_night, $department) {
                    $bulkSalequery->where('fldsave', True);
                    $bulkSalequery->where(function ($bulkSalequeryNested) {
                        $bulkSalequeryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    $bulkSalequery->where('fldtime', '>', $last_night);
                    $bulkSalequery->where('fldtime', '<', date('Y-m-d H:i:s'));
                    if ($department) {
                        $bulkSalequery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->where('fldsav', False)
                ->orderBy('fldtime', 'Desc')
                ->groupBy('fldstockid')
                ->get();

            $html = view('medicinestock::stock-list-purchase', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function salesDataBatch(Request $request)
    {
        $data['itemStockName'] = $itemStockName = '';
        $data['departmentComp'] = $department = '';
        if ($request->has('department')) {
            $data['departmentComp'] = $department = $request->department;
        }

        if ($request->has('itemStockName')) {
            $data['itemStockName'] = $itemStockName = $request->itemStockName;
        }
        $last_night = date('Y-m-d H:i:s', strtotime('today midnight'));
        $data['tableEntry'] = PatBilling::where('fldsave', True)
            ->where(function ($queryNested) {
                $queryNested->orWhere('flditemtype', 'Surgicals')
                    ->orWhere('flditemtype', 'Medicines')
                    ->orWhere('flditemtype', 'Extra Items');
            })
            ->where(function ($queryNested) use ($itemStockName) {
                if ($itemStockName != "") {
                    $queryNested->where('flditemname', 'LIKE', '%' . $itemStockName . '%');
                }
            })
            ->where(function ($queryNested) use ($department) {
                if ($department != "") {
                    $queryNested->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            })
//            ->where('fldtime', '>', $last_night)
//            ->where('fldtime', '<', date('Y-m-d H:i:s'))
            ->with(['medicine' => function ($query) use ($department) {
                $query->where('fldsav', 1);
                $query->where(function ($queryNested) {
                    $queryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $query->where('fldtime', '>', $last_night);
//                $query->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department != "") {
                    $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            }])
            ->with(['medicine.multiplePurchase' => function ($multiplePurchasequery) use ($last_night) {
                $multiplePurchasequery->where('fldsav', False);
                $multiplePurchasequery->where(function ($multiplePurchasequeryNested) {
                    $multiplePurchasequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $multiplePurchasequery->where('fldtime', '>', $last_night);
//                $multiplePurchasequery->where('fldtime', '<', date('Y-m-d H:i:s'));
            }])
            ->with(['medicine.bulkSale' => function ($bulkSalequery) use ($last_night, $department) {
                $bulkSalequery->where('fldsave', True);
                $bulkSalequery->where(function ($bulkSalequeryNested) {
                    $bulkSalequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $bulkSalequery->where('fldtime', '>', $last_night);
//                $bulkSalequery->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $bulkSalequery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            }])
            ->with(['medicine.transfer' => function ($transferquery) use ($last_night, $department) {
                $transferquery->where('fldtosav', True);
                $transferquery->where(function ($transferqueryNested) {
                    $transferqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $transferquery->where('fldtoentrytime', '>', $last_night);
//                $transferquery->where('fldtoentrytime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $transferquery->where(function ($transferqueryNested2) use ($department) {
                        $transferqueryNested2->where('fldfromcomp', 'like', $department);
                        $transferqueryNested2->where('fldtocomp', 'like', $department);
                    });
                }
            }])
            ->with(['medicine.adjustment' => function ($adjustmentquery) use ($last_night, $department) {
                $adjustmentquery->where('fldsav', True);
                $adjustmentquery->where(function ($adjustmentqueryNested) {
                    $adjustmentqueryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                });
//                $adjustmentquery->where('fldtime', '>', $last_night);
//                $adjustmentquery->where('fldtime', '<', date('Y-m-d H:i:s'));
                if ($department) {
                    $adjustmentquery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                }
            }])
            ->paginate(100);

        return view('medicinestock::stock-list-batch', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function purchaseDataBatch(Request $request)
    {
        $data['itemStockName'] = $itemStockName = '';
        $data['departmentComp'] = $department = 'comp01';
        if ($request->has('department')) {
            $data['departmentComp'] = $department = $request->department;
        }

        if ($request->has('itemStockName')) {
            $data['itemStockName'] = $itemStockName = $request->itemStockName;
        }
        try {
            if ($department) {
                $data['departmentComp'] = strtolower($department);
            }

            $last_night = date('Y-m-d H:i:s', strtotime('today midnight'));
            $data['tableEntry'] = Purchase::selectRaw('*, sum(fldtotalqty) as qty')
                ->where(function ($multiplePurchasequeryNested) {
                    $multiplePurchasequeryNested->orWhere('fldcategory', 'Surgicals')
                        ->orWhere('fldcategory', 'Medicines')
                        ->orWhere('fldcategory', 'Extra Items');
                })
                ->where(function ($queryNested) use ($itemStockName) {
                    if ($itemStockName != "") {
                        $queryNested->where('fldstockid', 'LIKE', '%' . $itemStockName . '%');
                    }
                })
                ->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))])
                ->where('fldtime', '>', $last_night)
                ->where('fldtime', '<', date('Y-m-d H:i:s'))
                //entry table
                ->with(['EntryByStockName' => function ($query) use ($department) {
                    $query->where('fldsav', True);
                    $query->where(function ($queryNested) {
                        $queryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    if ($department) {
                        $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->with(['EntryByStockName.patBillingByName' => function ($query) use ($department) {
                    $query->where('fldsave', True);
                    $query->selectRaw('*, sum(flditemqty) as qty');
                    $query->where(function ($queryNested) {
                        $queryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    if ($department) {
                        $query->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->with(['EntryByStockName.transfer' => function ($transferquery) use ($last_night, $department) {
                    $transferquery->where('fldtosav', True);
                    $transferquery->where(function ($transferqueryNested) {
                        $transferqueryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    $transferquery->where('fldtoentrytime', '>', $last_night);
                    $transferquery->where('fldtoentrytime', '<', date('Y-m-d H:i:s'));
                    if ($department) {
                        $transferquery->where(function ($transferqueryNested2) use ($department) {
                            $transferqueryNested2->where('fldfromcomp', 'like', $department);
                            $transferqueryNested2->where('fldtocomp', 'like', $department);
                        });
                    }
                }])
                ->with(['EntryByStockName.bulkSale' => function ($bulkSalequery) use ($last_night, $department) {
                    $bulkSalequery->where('fldsave', True);
                    $bulkSalequery->where(function ($bulkSalequeryNested) {
                        $bulkSalequeryNested->orWhere('fldcategory', 'Surgicals')
                            ->orWhere('fldcategory', 'Medicines')
                            ->orWhere('fldcategory', 'Extra Items');
                    });
                    $bulkSalequery->where('fldtime', '>', $last_night);
                    $bulkSalequery->where('fldtime', '<', date('Y-m-d H:i:s'));
                    if ($department) {
                        $bulkSalequery->whereRaw('LOWER(`fldcomp`) LIKE ? ',[trim(strtolower($department))]);
                    }
                }])
                ->where('fldsav', False)
                ->orderBy('fldtime', 'Desc')
                ->groupBy('fldstockid')
                ->get();

            return view('medicinestock::stock-list-purchase-batch', $data);
        } catch (\Exception $e) {

        }

    }

}
