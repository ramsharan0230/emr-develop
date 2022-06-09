<?php

namespace Modules\Reports\Http\Controllers;

use App\HospitalDepartment;
use App\Exports\InventoryTransactionReportExport;
use App\ExtraBrand;
use App\Supplier;
use App\SurgBrand;
use App\MedicineBrand;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use DB;
use Exception;

class InventoryReportController extends Controller
{
    public $is_suuplier = false;
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['hospital_departments'] = [];
        $user = Auth::guard('admin_frontend')->user();
        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            $data['hospital_departments'] = HospitalDepartment::all();
        } else {
            // fetch hospital_departments accroding to user
            $hospitalDepartments = DB::table('hospital_department_users')->where('user_id', $user->id)->pluck('hospital_department_id');
            $data['hospital_departments'] = HospitalDepartment::whereIn('id', $hospitalDepartments)->get();
        }
        
        $data['supplierName'] = Supplier::where('fldactive', 'like', '%')->get();
        $data['medicines'] = MedicineBrand::select('flddrug')->groupBy("flddrug")->get();

        return view('reports::inventory.index', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getInventoryData(Request $request)
    {
        $data['request'] = $request->all();
        if($request->supplier && $request->supplier=='%'){
           $this->is_suuplier = true;
        }
        $data['is_supplier'] = $this->is_suuplier;
        if ($request->buyType === "purchase") {
            $data['medicines'] = $this->getPurchasePaginate($request);
            return view('reports::inventory.inventory-list', $data)->render();
        }
        if ($request->buyType === "dispensing") {
            $data['medicines'] = $this->getDispensingData($request);
            return view('reports::inventory.inventory-list-dispensing', $data)->render();
        }
        if ($request->buyType === "used") {
            $data['medicines'] = $this->getUsedData($request);
            return view('reports::inventory.inventory-list-used', $data)->render();
        }

        if ($request->buyType === "transfer") {
            $data['medicines'] = $this->getTransferData($request);
            return view('reports::inventory.inventory-list-transfer', $data)->render();
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function generateInventoryReport(Request $request)
    {
        $data['request'] = $request->all();
        if($request->supplier && $request->supplier=='%'){
            $this->is_suuplier = true;
        }
        $data['is_supplier'] = $this->is_suuplier;
        if ($request->buyType === "purchase") {
            $data['medicines'] = $this->getPurchaseReportData($request);
            return view('reports::inventory.inventory-report', $data)->render();
        }
        if ($request->buyType === "dispensing") {
            $data['medicines'] = $this->getDispensingDataReport($request);
            return view('reports::inventory.inventory-list-dispensing-report', $data)->render();
        }
        if ($request->buyType === "used") {
            $data['medicines'] = $this->getUsedDataReport($request);
            return view('reports::inventory.inventory-list-used-report', $data)->render();
        }
        if ($request->buyType === "transfer") {
            $data['medicines'] = $this->getTransferDataReport($request);
            return view('reports::inventory.inventory-list-transfer-report', $data)->render();
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getPurchasePaginate($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('fldtime,fldbillno,fldreference,fldvolunit,flddrug as generic,fldbrand,fldsuppname,fldstockno,(tblpurchase.fldtotalqty - tblpurchase.fldreturnqty) as qty,flsuppcost,fldsellprice,(tblpurchase.fldtotalqty - tblpurchase.fldreturnqty) * fldsellprice as tot,flddosageform')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Medicines")
                ->where('fldsav', 0)
                ->paginate(10);

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('tblpurchase.fldstockid,fldtime,fldbillno,fldreference,fldvolunit,tblsurgbrand.fldsurgid as generic,fldbrand,fldsuppname,fldstockno,fldstockno,(fldtotalqty-fldreturnqty) as qty,flsuppcost,fldsellprice,(fldtotalqty-fldreturnqty)*fldsellprice as tot,tblsurgicals.fldsurgcateg')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->join('tblsurgicals', 'tblsurgicals.fldsurgid', '=', 'tblsurgbrand.fldsurgid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Surgicals")
                ->where('fldsav', 0)
                ->paginate(10);
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('fldtime,fldbillno,fldreference,fldvolunit,fldextraid as generic,fldbrand,fldsuppname,fldstockno,fldstockno,(fldtotalqty-fldreturnqty) as qty,flsuppcost,fldsellprice,(fldtotalqty-fldreturnqty)*fldsellprice as tot,flddepart')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Extra Items")
                ->where('fldsav', 0)
                ->paginate(10);
        }

        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getPurchaseReportData($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('fldtime,fldbillno,fldreference,fldvolunit,flddrug as generic,fldbrand,fldsuppname,fldstockno,(tblpurchase.fldtotalqty - tblpurchase.fldreturnqty) as qty,flsuppcost,fldsellprice,(tblpurchase.fldtotalqty - tblpurchase.fldreturnqty) * fldsellprice as tot,flddosageform')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Medicines")
                ->where('fldsav', 0)
                ->get();


        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('fldtime,fldbillno,fldreference,fldvolunit,tblsurgbrand.fldsurgid as generic,fldbrand,fldsuppname,fldstockno,fldstockno,(fldtotalqty-fldreturnqty) as qty,flsuppcost,fldsellprice,(fldtotalqty-fldreturnqty)*fldsellprice as tot,tblsurgicals.fldsurgcateg')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->join('tblsurgicals', 'tblsurgicals.fldsurgid', '=', 'tblsurgbrand.fldsurgid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Surgicals")
                ->where('fldsav', 0)
                ->get();


        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblpurchase')
                ->selectRaw('fldtime,fldbillno,fldreference,fldvolunit,fldextraid as generic,fldbrand,fldsuppname,fldstockno,fldstockno,(fldtotalqty-fldreturnqty) as qty,flsuppcost,fldsellprice,(fldtotalqty-fldreturnqty)*fldsellprice as tot,flddepart')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblpurchase.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->when($request->supplier != "%", function ($query) use ($request) {
                    $query->where('fldsuppname', 'LIKE', $request->supplier);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->where('fldcategory', 'LIKE', "Extra Items")
                ->where('fldsav', 0)
                ->get();
        }

        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getDispensingData($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblmedbrand.flddrug as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Medicines")
                ->where('fldsave', 1)
                ->paginate(10);

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Surgicals")
                ->where('fldsave', 1)
                ->paginate(10);
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblextrabrand.fldextraid as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Extra Items")
                ->where('fldsave', 1)
                ->paginate(10);
        }
        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getDispensingDataReport($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblmedbrand.flddrug as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Medicines")
                ->where('fldsave', 1)
                ->get();

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Surgicals")
                ->where('fldsave', 1)
                ->get();
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblpatbilling')
                ->selectRaw('fldencounterval,tblextrabrand.fldextraid as generic,fldbrand,fldvolunit,flditemrate,flditemqty as qty,flddiscper,fldtaxper,fldditemamt as tot,fldtime,fldbillno,fldid')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblpatbilling.flditemname')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('flditemtype', 'LIKE', "Extra Items")
                ->where('fldsave', 1)
                ->get();
        }

        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getUsedData($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,flddrug as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid ')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Medicines")
                ->where('fldsave', 1)
                ->paginate(10);

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Surgicals")
                ->where('fldsave', 1)
                ->paginate(10);
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,fldextraid as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Extra Items")
                ->where('fldsave', 1)
                ->paginate(10);
        }
        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getUsedDataReport($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,flddrug as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid ')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Medicines")
                ->where('fldsave', 1)
                ->get();

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Surgicals")
                ->where('fldsave', 1)
                ->get();
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tblbulksale')
                ->selectRaw('fldtarget,tblbulksale.fldcategory,fldextraid as generic,fldbrand,fldvolunit,fldnetcost,(fldqtydisp-fldqtyret) as qty,fldnetcost*(fldqtydisp-fldqtyret) as tot,fldtime,fldreference,fldid')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tblbulksale.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tblbulksale.fldcategory', 'LIKE', "Extra Items")
                ->where('fldsave', 1)
                ->get();
        }

        return $data['medicines'];
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getTransferData($request)
    {
        try {
            if ($request->medType === "med") {
                $data['medicines'] = DB::table('tbltransfer')
                    ->selectRaw('tbltransfer.fldcategory,flddrug as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid ')
                    ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                    ->when($request->comp != "%", function ($query) use ($request) {
                        $query->where('fldcomp', 'LIKE', $request->comp);
                    })
                    ->when($request->eng_from_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                    })
                    ->when($request->eng_to_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                    })
                    ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                        $query->when($request->brand == "generic", function ($query) use ($request) {
                            $query->where('tblmedbrand.flddrug', $request->item_name);
                        })
                        ->when($request->brand == "brand", function ($query) use ($request) {
                            $query->where('tblmedbrand.fldbrandid', $request->item_name);
                        });
                    })
                    ->where('tbltransfer.fldcategory', 'LIKE', "Medicines")
                    ->where('fldtosav', 1)
                    ->paginate(10);
    
            } elseif ($request->medType === "surg") {
                $data['medicines'] = DB::table('tbltransfer')
                    ->selectRaw('tbltransfer.fldcategory,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid')
                    ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                    ->when($request->comp != "%", function ($query) use ($request) {
                        $query->where('fldcomp', 'LIKE', $request->comp);
                    })
                    ->when($request->eng_from_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                    })
                    ->when($request->eng_to_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                    })
                    ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                        $query->when($request->brand == "generic", function ($query) use ($request) {
                            $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                        })
                        ->when($request->brand == "brand", function ($query) use ($request) {
                            $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                        });
                    })
                    ->where('tbltransfer.fldcategory', 'LIKE', "Surgicals")
                    ->where('fldtosav', 1)
                    ->paginate(10);
            } elseif ($request->medType === "extra") {
                $data['medicines'] = DB::table('tbltransfer')
                    ->selectRaw('tbltransfer.fldcategory,fldextraid as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid')
                    ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                    ->when($request->comp != "%", function ($query) use ($request) {
                        $query->where('fldcomp', 'LIKE', $request->comp);
                    })
                    ->when($request->eng_from_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                    })
                    ->when($request->eng_to_date, function ($query) use ($request) {
                        $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                    })
                    ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                        $query->when($request->brand == "generic", function ($query) use ($request) {
                            $query->where('tblextrabrand.fldextraid', $request->item_name);
                        })
                        ->when($request->brand == "brand", function ($query) use ($request) {
                            $query->where('tblextrabrand.fldbrandid', $request->item_name);
                        });
                    })
                    ->where('tbltransfer.fldcategory', 'LIKE', "Extra Items")
                    ->where('fldtosav', 1)
                    ->paginate(10);
            }
            return $data['medicines'];
        } catch (\Exception $e) {
            // dd($e);
            return false;
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getTransferDataReport($request)
    {
        if ($request->medType === "med") {
            $data['medicines'] = DB::table('tbltransfer')
                ->selectRaw('tbltransfer.fldcategory,flddrug as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid ')
                ->join('tblmedbrand', 'tblmedbrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblmedbrand.flddrug', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblmedbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tbltransfer.fldcategory', 'LIKE', "Medicines")
                ->where('fldtosav', 1)
                ->get();

        } elseif ($request->medType === "surg") {
            $data['medicines'] = DB::table('tbltransfer')
                ->selectRaw('tbltransfer.fldcategory,tblsurgbrand.fldsurgid as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid')
                ->join('tblsurgbrand', 'tblsurgbrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldsurgid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblsurgbrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tbltransfer.fldcategory', 'LIKE', "Surgicals")
                ->where('fldtosav', 1)
                ->get();
        } elseif ($request->medType === "extra") {
            $data['medicines'] = DB::table('tbltransfer')
                ->selectRaw('tbltransfer.fldcategory,fldextraid as generic,fldbrand,fldvolunit,fldnetcost,fldqty as qty,fldnetcost*fldqty as tot,fldtoentrytime,fldreference,fldid')
                ->join('tblextrabrand', 'tblextrabrand.fldbrandid', '=', 'tbltransfer.fldstockid')
                ->when($request->comp != "%", function ($query) use ($request) {
                    $query->where('fldcomp', 'LIKE', $request->comp);
                })
                ->when($request->eng_from_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '>=', $request->eng_from_date . " 00:00:00");
                })
                ->when($request->eng_to_date, function ($query) use ($request) {
                    $query->where('fldtoentrytime', '<=', $request->eng_to_date . " 23:59:59.999");
                })
                ->when($request->itemRadio == "select_item", function ($query) use ($request) {
                    $query->when($request->brand == "generic", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldextraid', $request->item_name);
                    })
                    ->when($request->brand == "brand", function ($query) use ($request) {
                        $query->where('tblextrabrand.fldbrandid', $request->item_name);
                    });
                })
                ->where('tbltransfer.fldcategory', 'LIKE', "Extra Items")
                ->where('fldtosav', 1)
                ->get();
        }

        return $data['medicines'];
    }

    /**
     * @param Request $request
     */
    public function transaction(Request $request)
    {
        $transaction = new InventoryTransactionReportExport($request->all());
        ob_end_clean();
        ob_start();
        return \Excel::download($transaction, 'transaction.xlsx');
    }

    public  function filterData(Request $request){
        $html='';
        $count =1;
        if($request->type=='med') {
            if($request->brand=='generic') {
                $medicines = MedicineBrand::select('flddrug')->groupBy("flddrug")->get();
            } else {
                $medicines = MedicineBrand::select('fldbrandid AS flddrug')->get();
            }
            if($medicines){
                foreach ($medicines as $medicine){
                    $count++;
                    $html.='<tr>';
                    $html.='<td class="p-2">';
                    $html.='<div class="form-check form-check-inline" style="width: 100%;">';
                    $html.='<input class="form-check-input" type="radio" name="item_name" id="'.$count.'-item-id" value="'.$medicine->flddrug.'">';
                    $html.='<label class="form-check-label" for="'.$count.'-item-id" style="width: 100%;">'.$medicine->flddrug.'</label>';
                    $html.='</div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
            }
        }elseif ($request->type=='surg') {
            if($request->brand=='generic') {
                $medicines = SurgBrand::select('fldsurgid')->groupBy("fldsurgid")->get();
            } else {
                $medicines = SurgBrand::select('fldbrandid AS fldsurgid')->get();
            }
            if($medicines){
                foreach ($medicines as $medicine){
                    $count++;
                    $html.='<tr>';
                    $html.='<td class="p-2">';
                    $html.='<div class="form-check form-check-inline" style="width: 100%;">';
                    $html.='<input class="form-check-input" type="radio" name="item_name" id="'.$count.'-item-id" value="'.$medicine->fldsurgid.'">';
                    $html.='<label class="form-check-label" for="'.$count.'-item-id" style="width: 100%;">'.$medicine->fldsurgid.'</label>';
                    $html.='</div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
            }
        }elseif ($request->type=='extra') {
            if($request->brand=='generic') {
                $medicines = ExtraBrand::select('fldextraid')->groupBy("fldextraid")->get();
            } else {
                $medicines = ExtraBrand::select('fldbrandid AS fldextraid')->get();
            }
            if($medicines){
                foreach ($medicines as $medicine){
                    $count++;
                    $html.='<tr>';
                    $html.='<td class="p-2">';
                    $html.='<div class="form-check form-check-inline" style="width: 100%;">';
                    $html.='<input class="form-check-input" type="radio" name="item_name" id="'.$count.'-item-id" value="'.$medicine->fldextraid.'">';
                    $html.='<label class="form-check-label" for="'.$count.'-item-id" style="width: 100%;">'.$medicine->fldextraid.'</label>';
                    $html.='</div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
            }
        }
        return  \response()->json(['html' => $html]);
    }
}
