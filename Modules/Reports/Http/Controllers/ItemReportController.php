<?php

namespace Modules\Reports\Http\Controllers;

use App\BillingSet;
use App\Department;
use App\Exports\ItemReport\ItemCategorywiseExport;
use App\Exports\ItemReport\ItemDatesExport;
use App\Exports\ItemReport\ItemDatewiseExport;
use App\Exports\ItemReport\ItemDetailsExport;
use App\Exports\ItemReport\ItemParticularwiseExport;
use App\Exports\ItemReport\ItemVisitsExport;
use App\Exports\ItemReportExport;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\Utils\Helpers;
use Auth;
use Carbon\CarbonPeriod;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ItemReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['departments'] = Department::all();
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('reports::itemreport.item-report', $data);
    }

    public function loadData(Request $request)
    {
        try {
            $items = PatBilling::select('flditemname')
                ->when($request->category != "%", function ($q) use ($request) {
                    return $q->where('flditemtype', $request->category);
                })
                ->when($request->billingmode != "%", function ($q) use ($request) {
                    return $q->where('fldbillingmode', $request->billingmode);
                })
                ->when(($request->from_date == $request->to_date) && $request->from_date != "" && $request->to_date != "", function ($q) use ($request) {
                    return $q->where(DB::raw("(STR_TO_DATE(fldordtime,'%Y-%m-%d'))"),$request->from_date);
                })
                ->when(($request->from_date != $request->to_date) && $request->from_date != "", function ($q) use ($request) {
                    return $q->where('fldordtime', '>=', $request->from_date);
                })
                ->when(($request->from_date != $request->to_date) && $request->to_date != "", function ($q) use ($request) {
                    return $q->where('fldordtime', "<=", $request->to_date);
                })
                ->distinct('flditemname')
                ->orderBy('flditemname', 'asc')
                ->get();

            $packages = PatBilling::select('package_name')
                ->when($request->billingmode != "%", function ($q) use ($request) {
                    return $q->where('fldbillingmode', $request->billingmode);
                })
                ->when(($request->from_date == $request->to_date) && $request->from_date != "" && $request->to_date != "", function ($q) use ($request) {
                    return $q->where(DB::raw("(STR_TO_DATE(fldordtime,'%Y-%m-%d'))"),$request->from_date);
                })
                ->when(($request->from_date != $request->to_date) && $request->from_date != "", function ($q) use ($request) {
                    return $q->where('fldordtime', '>=', $request->from_date);
                })
                ->when(($request->from_date != $request->to_date) && $request->to_date != "", function ($q) use ($request) {
                    return $q->where('fldordtime', "<=", $request->to_date);
                })
                ->distinct('package_name') //package_name
                ->orderBy('package_name', 'asc')
                ->get();

            $itemHtml = "";
            $packageHtml = "";
            foreach ($items as $item) {
                $itemHtml .= '<tr>
                            <td class="item-td" data-itemname="' . $item->flditemname . '"><i class="fas fa-angle-right mr-2"></i>' . $item->flditemname . '</td>
                        </tr>';
            }
            foreach ($packages as $package) {
                if($package->package_name) {
                    $packageHtml .= '<tr>
                                        <td class="item-td" data-itemname="' . $package->package_name . '"><i class="fas fa-angle-right mr-2"></i>' . $package->package_name ?: "N/A" . '</td>
                                    </tr>';
                }
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'itemHtml' => $itemHtml,
                    'packageHtml' => $packageHtml
                ]
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getRefreshData(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $dateType = $request->dateType;
            $itemRadio = $request->itemRadio;
            $category = $request->category;
            $billingmode = $request->billingmode;
            $comp = $request->comp;
            $selectedItem = $request->selectedItem;
            $datas = PatBilling::select('fldencounterval', 'flditemname', 'flditemrate', 'flditemqty', 'flddiscamt', 'fldtaxamt', 'fldditemamt as tot', 'fldtime as entrytime', 'fldbillno', 'fldtempbillno', 'fldtempbilltransfer', 'fldid', 'fldpayto', 'fldrefer', 'package_name')
                ->where('fldsave', 1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('fldtime', '>=', $finalfrom)
                        ->where('fldtime', '<=', $finalto);
                })
                ->when($category != "%" && $itemRadio != "packages", function ($q) use ($category) {
                    return $q->where('flditemtype', 'like', $category);
                })
                ->when($comp != "%", function ($q) use ($comp) {
                    return $q->where('fldcomp', 'like', $comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode) {
                    return $q->where('fldbillingmode', 'like', $billingmode);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('flditemname', 'like', $selectedItem);
                })
                ->when($itemRadio == "packages", function ($q) use ($selectedItem) {
                    $q->when($selectedItem != 'N/A', function ($query) use ($selectedItem) {
                        return $query->where('package_name', 'like', $selectedItem );
                    })->when($selectedItem == 'N/A', function ($query) {
                        return $query->whereNull('package_name');
                    });
                })
                ->when($dateType == "entry_date", function ($q) {
                    return $q->orderBy('fldtime', 'asc');
                })
                ->paginate(10);
            $html = '';
            foreach ($datas as $data) {
                $html .= '<tr>
                            <td>' . $data->fldencounterval . '</td>';
                            if($itemRadio == 'packages') {
                                $html .= '  <td>' . $data->package_name . '</td>';
                            }
                if($data->encounter){
                    if($data->encounter->patientInfo){
                        $html .= '<td>' . $data->encounter->patientInfo->getFldrankfullnameAttribute() . '</td>';
                    }else{
                        $html .= '<td></td>';
                    }
                }else{
                    $html .= '<td></td>';
                }
                $html .= '  <td>' . $data->flditemname . '</td>';
                $html .= "<td>" . Helpers::numberFormat($data->flditemrate) ?? '0' . "</td>";
                $html .= "<td>" . $data->flditemqty ?? '0' . "</td>";
                $html .= "<td>" . Helpers::numberFormat($data->flddiscamt) ?? '0' . "</td>";
                $html .= "<td>" . Helpers::numberFormat($data->fldtaxamt) ?? '0' . "</td>";
                $html .= "<td>" . Helpers::numberFormat($data->tot, 2) ?? '0' . "</td>";
                $html .= '<td>' . (( isset($data->entrytime) ? Helpers::dateToNepali($data->entrytime) :'')) . '</td>';
                $html .= '<td>' . $data->fldbillno . '</td>';
                $html .= '</tr>';
            }
            $html .= '<tr><td colspan="12">' . $datas->appends(request()->all())->links() . '</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'type' => $itemRadio,
                    'html' => $html
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date. " 23:59:59";
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.flditemname', 'tblpatbilling.flditemrate', 'tblpatbilling.flditemqty', 'tblpatbilling.flddiscamt', 'tblpatbilling.fldtaxamt', 'tblpatbilling.fldditemamt as tot', 'tblpatbilling.fldtime as entrytime', 'tblpatbilling.fldbillno', 'tblpatbilling.fldtempbillno', 'tblpatbilling.fldtempbilltransfer', 'tblpatbilling.fldid', 'tblpatbilling.fldpayto', 'tblpatbilling.fldrefer', 'tblpatbilling.fldditemamt','tblpatbilling.fldsave', 'package_name')
                ->where('tblpatbilling.fldsave', 1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })

                ->when($category != "%" && $itemRadio != "packages", function ($q) use ($category) {
                    return $q->where('tblpatbilling.flditemtype', 'like', $category);
                })
                ->when($comp != "%", function ($q) use ($comp) {
                    return $q->where('tblpatbilling.fldcomp', 'like', $comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode) {
                    return $q->where('tblpatbilling.fldbillingmode', 'like', $billingmode);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when($itemRadio == "packages", function ($q) use ($selectedItem) {
                    $q->when($selectedItem != 'N/A', function ($query) use ($selectedItem) {
                        return $query->where('package_name', 'like', $selectedItem );
                    })->when($selectedItem == 'N/A', function ($query) {
                        return $query->whereNull('package_name');
                    });
                })
                ->when($dateType == "entry_date", function ($q) {
                    return $q->orderBy('tblpatbilling.fldtime', 'asc');
                })
                ->get()
                ->groupBy(['fldbillno']);
            $alldata['certificate'] = "ITEM";
            $alldata['itemRadio'] = $itemRadio;
            return view('reports::itemreport.export-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportExcel(Request $request)
    {
        $category = $data['category'] = ($request->has('category')) ? $request->category : "";
        $billingmode = $data['billingmode'] = ($request->has('billingmode')) ? $request->billingmode : "";
        $comp = $data['comp'] = ($request->has('comp')) ? $request->comp : "%";
        // $departments = $data['departments'] = ($request->has('departments')) ? $request->departments : "%";
        $selectedItem = $data['selectedItem'] = ($request->has('selectedItem')) ? $request->selectedItem : "";
        $export = new ItemReportExport($request->from_date, $request->to_date, $request->dateType, $request->itemRadio,$category,$billingmode,$comp,$selectedItem);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'ItemReport.xlsx');
    }

    public function exportDatewisePdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'), \DB::raw('SUM(tblpatbilling.flditemqty) as qnty'), \DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'), \DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'), \DB::raw('SUM(tblpatbilling.fldditemamt) as totl'), \DB::raw('DATE(tblpatbilling.fldtime) as entry_date'), 'flditemname', 'package_name')
                ->where('tblpatbilling.fldsave', 1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->when($category != "%" && $itemRadio != "packages", function ($q) use ($category) {
                    return $q->where('tblpatbilling.flditemtype', 'like', $category);
                })
                ->when($comp != "%", function ($q) use ($comp) {
                    return $q->where('tblpatbilling.fldcomp', 'like', $comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode) {
                    return $q->where('tblpatbilling.fldbillingmode', 'like', $billingmode);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when($itemRadio == "packages", function ($q) use ($selectedItem) {
                    $q->when($selectedItem != 'N/A', function ($query) use ($selectedItem) {
                        return $query->where('package_name', 'like', $selectedItem )->groupBy('package_name');
                    })->when($selectedItem == 'N/A', function ($query) {
                        return $query->whereNull('package_name')->groupBy('package_name');
                    });
                })
                ->when($itemRadio != "packages", function ($q) {
                    $q->groupBy('flditemname');
                })
                ->groupBy('entry_date')
                ->get();

            $alldata['certificate'] = "ITEM DATEWISE";
            return view('reports::itemreport.export-datewise-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportDatewiseExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemDatewiseExport($request->all()), 'Item-Datewise-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportCategorywisePdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select(\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'), \DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'), \DB::raw('SUM(tblpatbilling.fldditemamt) as totl'), 'tblpatbilling.flditemtype as flditemtype')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->groupBy('flditemtype')
                ->get();
            $alldata['certificate'] = "ITEM CATEGORY WISE";
            return view('reports::itemreport.export-categorywise-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportCategorywiseExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemCategorywiseExport($request->all()), 'Item-Categorywise-Report.xlsx');
        }catch(\Exception $e){
            dd($e);
            return redirect()->back();
        }
    }

    public function exportParticularwisePdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'), \DB::raw('SUM(tblpatbilling.flditemqty) as qnty'), \DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'), \DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'), \DB::raw('SUM(tblpatbilling.fldditemamt) as totl'), 'tblpatbilling.flditemtype as flditemtype', 'tblpatbilling.flditemname as flditemname')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->groupBy('flditemname')
                ->get()
                ->groupBy('flditemtype');
            $alldata['certificate'] = "ITEM PARTICULARWISE";
            return view('reports::itemreport.export-particularwise-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportParticularwiseExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemParticularwiseExport($request->all()), 'Item-Particularwise-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportItemDetailsPdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.flditemrate as rate', 'tblpatbilling.flditemqty as qnty', 'tblpatbilling.flddiscamt as dsc', 'tblpatbilling.fldtaxamt as tax', 'tblpatbilling.fldditemamt as totl', 'tblpatbilling.flditemtype as flditemtype', 'tblpatbilling.flditemname as flditemname', 'tblpatbilling.fldtime as entrytime')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->get()
                ->groupBy('flditemtype');
            $alldata['certificate'] = "ITEM DETAIL";
            return view('reports::itemreport.export-details-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportItemDetailsExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemDetailsExport($request->all()), 'Item-Details-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportItemDatesPdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            if ($dateType == "invoice_date") {
                $datefield = "invoice_date";
            } else {
                $datefield = "entry_date";
            }
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'), \DB::raw('SUM(tblpatbilling.flditemqty) as qnty'), \DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'), \DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'), \DB::raw('SUM(tblpatbilling.fldditemamt) as totl'), 'tblpatbilling.flditemname as flditemname', 'tblpatbilling.flditemtype as flditemtype', \DB::raw('DATE(tblpatbilling.fldtime) as entry_date'))
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                    // ->groupBy('entry_date');
                })
                ->groupBy('flditemname')
                ->get()
                ->groupBy(['flditemtype', $datefield]);
            $alldata['certificate'] = "ITEM DATES";
            return view('reports::itemreport.export-dates-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportItemDatesExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemDatesExport($request->all()), 'Item-Dates-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportItemVisitsPdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval as fldencounterval')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto);
                })
                ->groupBy('fldencounterval')
                ->get();
            $alldata['certificate'] = "VISITS";
            return view('reports::itemreport.export-visits-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function exportItemVisitsExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new ItemVisitsExport($request->all()), 'Item-Visits-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportItemCutOffAmountPdf(Request $request)
    {
        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['category'] = $category = $request->category;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $departments = $request->departments;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['cutOffAmount'] = $cutOffAmount = $request->cut_off_amount;
            $alldata['dateRange'] = $dateRange = CarbonPeriod::create($finalfrom, $finalto);
            $alldata['allCategories'] = ["Diagnostic Tests", "Equipment", "Extra Items", "General Services", "Medicines", "Other Items", "Radio Diagnostics", "Surgicals"];
            $alldata['markDatas'] = PatBilling::select(\DB::raw('COUNT(tblpatbilling.fldencounterval) as ptcount'), \DB::raw('SUM(tblpatbilling.fldditemamt) as patsum'), \DB::raw('DATE(tblpatbilling.fldtime) as entry_date'), \DB::raw('DATE(tblpatbilldetail.fldtime) as invoice_date'), 'tblpatbilling.flditemtype as flditemtype')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->where('tblpatbilling.fldditemamt', '<=', $cutOffAmount)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom)
                            ->groupBy('flditemtype')
                            ->groupBy('entry_date');
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto)
                        ->groupBy('flditemtype')
                        ->groupBy('entry_date');
                })
                ->get()
                ->when($dateType == "invoice_date", function ($q) {
                    return $q->groupBy(['invoice_date', 'flditemtype']);
                })
                ->when($dateType == "entry_date", function ($q) {
                    return $q->groupBy(['entry_date', 'flditemtype']);
                })
                ->toArray();
            $alldata['totalDatas'] = PatBilling::select(\DB::raw('COUNT(tblpatbilling.fldencounterval) as ptcount'), \DB::raw('SUM(tblpatbilling.fldditemamt) as patsum'), \DB::raw('DATE(tblpatbilling.fldtime) as entry_date'), \DB::raw('DATE(tblpatbilldetail.fldtime) as invoice_date'), 'tblpatbilling.flditemtype as flditemtype')
                ->where('tblpatbilling.fldsave', 1)
                ->where('tblpatbilling.flditemtype', 'like', $category)
                ->where('tblpatbilling.fldcomp', 'like', $comp)
                ->where('tblpatbilling.fldbillingmode', 'like', $billingmode)
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem) {
                    return $q->where('tblpatbilling.flditemname', 'like', $selectedItem);
                })
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom)
                            ->groupBy('flditemtype')
                            ->groupBy('entry_date');
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom, $finalto) {
                    return $q->where('tblpatbilling.fldtime', '>=', $finalfrom)
                        ->where('tblpatbilling.fldtime', '<=', $finalto)
                        ->groupBy('flditemtype')
                        ->groupBy('entry_date');
                })
                ->get()
                ->when($dateType == "invoice_date", function ($q) {
                    return $q->groupBy(['invoice_date', 'flditemtype']);
                })
                ->when($dateType == "entry_date", function ($q) {
                    return $q->groupBy(['entry_date', 'flditemtype']);
                })
                ->toArray();

            $alldata['certificate'] = "PATIENTS";
            return view('reports::itemreport.export-patient-pdf', $alldata);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}
