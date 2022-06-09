<?php

namespace Modules\Reports\Http\Controllers;

use App\BillingSet;
use App\Exports\EntryWaitingExport;
use App\Exports\GroupReport\GroupCategorywiseExport;
use App\Exports\GroupReport\GroupDatesExport;
use App\Exports\GroupReport\GroupDatewiseExport;
use App\Exports\GroupReport\GroupDetailsExport;
use App\Exports\GroupReport\GroupParticularwiseExport;
use App\Exports\GroupReport\GroupPatientExport;
use App\Exports\GroupReport\GroupSummaryExport;
use App\Exports\GroupReport\GroupVisitsExport;
use App\Exports\GroupReportExport;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\ReportGroup;
use App\ServiceCost;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GroupReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        return view('reports::groupreport.group-report',$data);
    }

    public function getGroups(Request $request){
        try{
            $groups = ReportGroup::select('fldgroup')->distinct('fldgroup')->get();
            return response()->json([
                'data' => [
                    'status' => true,
                    'groups' => $groups,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getGroupData(Request $request){
        try{
            $result = ReportGroup::select('fldid','flditemtype','flditemname')
                                ->where('fldgroup',$request->group_name)
                                ->orderBy('flditemname','asc')
                                ->get();
            $html = '';
            foreach($result as $r){
                $html .= '<tr data-fldid="'.$r->fldid.'">
                            <td>'.$r->flditemtype.'</td>
                            <td>'.$r->flditemname.'</td>
                            <td data-fldid="'.$r->fldid.'" class="deleteParticular text-danger"><i class="fa fa-trash"></i></td>
                        </tr>';
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getGroupCategoryData(Request $request){
        try{
            $itemnames = ReportGroup::select('flditemname')->pluck('flditemname')->toArray();
            $result = ServiceCost::select('flditemname')
                                ->where('flditemtype','like',$request->category)
                                ->whereNotIn('flditemname',$itemnames)
                                ->get();
            $html = '';
            foreach($result as $r){
                $html .= '<tr>
                            <td data-itemname="'.$r->flditemname.'" class="item-td">'.$r->flditemname.'</td>
                        </tr>';
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function selectGroupItemname(Request $request){
        DB::beginTransaction();
        try{
            $html = '';
            if(count($request->selectedItemArray) > 0){
                foreach($request->selectedItemArray as $item){
                    ReportGroup::insert([
                        'fldgroup' => $request->groupName,
                        'flditemtype' => $request->groupcategory,
                        'flditemname' => $item,
                        'fldactive' => 'Active',
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]);
                    $html .= '<tr>
                                <td data-itemname="'.$item.'">'.$item.'</td>
                            </tr>';
                }
            }
            DB::commit();
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getGroupSelectedItems(Request $request){
        try{
            $html = '';
            $result = ReportGroup::select('flditemname')->where('fldgroup',$request->groupName)->get();
            foreach($result as $item){
                $html .= '<tr>
                            <td data-itemname="'.$item->flditemname.'">'.$item->flditemname.'</td>
                        </tr>';
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getGroupReport(Request $request){
        $data['groups'] = ReportGroup::select('fldgroup')->orderBy('fldgroup','asc')->distinct('fldgroup')->get();
        return view('reports::groupreport.group-lists-pdf',$data);
    }

    public function getRefreshedData(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $itemRadio = $request->itemRadio;
            $billingmode = $request->billingmode;
            $comp = $request->comp;
            $selectedItem = $request->selectedItem;
            $datas = PatBilling::select('tblpatbilling.fldencounterval','tblpatbilling.flditemname','tblpatbilling.flditemrate','tblpatbilling.flditemqty','tblpatbilling.flddiscamt','tblpatbilling.fldtaxamt','tblpatbilling.fldditemamt as tot','tblpatbilling.fldtime as entrytime','tblpatbilldetail.fldtime as invoicetime','tblpatbilling.fldbillno','tblpatbilling.fldid','tblpatbilling.fldpayto','tblpatbilling.fldrefer')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->paginate(10);
            $html = '';
            foreach($datas as $data){
                $html .= '<tr>
                            <td>'.$data->fldencounterval.'</td>
                            <td>'.$data->encounter->patientInfo->getFldrankfullnameAttribute().'</td>
                            <td>'.$data->flditemname.'</td>';
                if($data->flditemrate){
                    $html .= "<td>" . $data->flditemrate . "</td>";
                }else{
                    $html .= "<td> 0 </td>";
                }
                if($data->flditemqty){
                    $html .= "<td>" . $data->flditemqty . "</td>";
                }else{
                    $html .= "<td> 0 </td>";
                }
                if($data->flddiscamt){
                    $html .= "<td>" . $data->flddiscamt . "</td>";
                }else{
                    $html .= "<td> 0 </td>";
                }
                if($data->fldtaxamt){
                    $html .= "<td>" . $data->fldtaxamt . "</td>";
                }else{
                    $html .= "<td> 0 </td>";
                }
                if($data->tot){
                    $html .= "<td>" . $data->tot . "</td>";
                }else{
                    $html .= "<td> 0 </td>";
                }
                if($data->entrytime){
                    $html .= "<td>" . $data->entrytime . "</td>";
                }else{
                    $html .= "<td> - </td>";
                }
                if($data->fldbillno){
                    $html .= "<td>" . $data->fldbillno . "</td>";
                }else{
                    $html .= "<td> - </td>";
                }
                if($data->fldpayto){
                    $html .= "<td>" . $data->fldpayto . "</td>";
                }else{
                    $html .= "<td> - </td>";
                }
                if($data->fldrefer){
                    $html .= "<td>" . $data->fldrefer . "</td>";
                }else{
                    $html .= "<td> - </td>";
                }
            }
            $html .='<tr><td colspan="12">'.$datas->appends(request()->all())->links().'</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function exportReport(Request $request){
        try{
            /*$from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval','tblpatbilling.flditemname','tblpatbilling.flditemrate','tblpatbilling.flditemqty','tblpatbilling.flddiscamt','tblpatbilling.fldtaxamt','tblpatbilling.fldditemamt as tot','tblpatbilling.fldtime as entrytime','tblpatbilldetail.fldtime as invoicetime','tblpatbilling.fldbillno','tblpatbilling.fldid','tblpatbilling.fldpayto','tblpatbilling.fldrefer')
                                ->leftJoin('tblpatbilldetail','tblpatbilldetail.fldbillno','=','tblpatbilling.fldbillno')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "invoice_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilldetail.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "invoice_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilldetail.fldtime','<=',$finalto);
                                })
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                            ->get();
            $alldata['certificate'] = "GROUP";*/
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupReportExport($request->all()), 'Group-Report.xlsx');

        }catch(\Exception $e){
            dd($e);
            return redirect()->back();
        }
    }

    public function exportSummaryReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            // $itemnames = ReportGroup::where('fldgroup',$selectedItem)->distinct('flditemname')->orderBy('flditemname','asc')->pluck('flditemname')->toArray();
            $alldata['datas'] = PatBilling::select('tblpatbilling.flditemname',\DB::raw('avg(tblpatbilling.flditemrate) as rate'),\DB::raw('SUM(tblpatbilling.flditemqty) as qnty'),\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as tot'))
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                // ->whereIn('tblpatbilling.flditemname',$itemnames)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->groupBy('flditemname')
                            ->get();
            $alldata['certificate'] = "GROUP SUMMARY";
            return view('reports::groupreport.export-summary-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportSummaryExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupSummaryExport($request->all()), 'Group-Summary-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDatewiseReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            // $itemnames = ReportGroup::where('fldgroup',$selectedItem)->distinct('flditemname')->orderBy('flditemname','asc')->pluck('flditemname')->toArray();
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'),\DB::raw('SUM(tblpatbilling.flditemqty) as qnty'),\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as totl'),\DB::raw('DATE(tblpatbilling.fldtime) as entry_date'))
                            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                            ->where('tblpatbilling.fldsave',1)
                            // ->whereIn('tblpatbilling.flditemname',$itemnames)
                            ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                            })
                            ->when($comp != "%", function ($q) use ($comp){
                                return $q->where('tblpatbilling.fldcomp','like',$comp);
                            })
                            ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                            ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom)
                                        ->orderBy('tblpatbilling.fldtime','asc')
                                        ->groupBy('entry_date');
                            })
                            ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                        ->where('tblpatbilling.fldtime','<=',$finalto)
                                        ->orderBy('tblpatbilling.fldtime','asc')
                                        ->groupBy('entry_date');
                            })
                            ->get();
            $alldata['certificate'] = "DATEWISE GROUP";
            return view('reports::groupreport.export-datewise-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDatewiseExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupDatewiseExport($request->all()), 'Group-Datewise-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportCategorywiseReport(Request $request){
        try{
            if(isset($request->eng_from_date) && isset($request->eng_to_date)){
                $alldata['finalfrom'] = $finalfrom = $request->eng_from_date;
                $alldata['finalto'] = $finalto = $request->eng_to_date;
            }
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date ;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            //
            $reports = [];
            $alldata['CasResults'] = $CasResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)

                ->when($finalfrom, function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom. ' 00:00:00')
                            ->where('tblpatbilling.fldtime','<=',$finalto.' 23:59:59');
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode){
                    return $q->where('tblpatbilling.fldbillingmode','like',$billingmode);
                })
                ->where('tblreportgroup.fldgroup','!=',null)
                ->where(function ($query) {
                    $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CAS%');
                    $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%REG%');
                })
                ->where("tblpatbilling.fldcomp", Helpers::getCompName())
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($CasResults->toArray()));

            $alldata['CreResults'] = $CreResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->where("tblpatbilling.fldcomp", Helpers::getCompName())

                ->when(($finalfrom) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom. ' 00:00:00')
                            ->where('tblpatbilling.fldtime','<=',$finalto.' 23:59:59');
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode){
                    return $q->where('tblpatbilling.fldbillingmode','like',$billingmode);
                })

                ->where('tblreportgroup.fldgroup','!=',null)
                ->where('tblpatbilling.fldbillno', 'like',  '%CRE%')
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($CreResults->toArray()));

            $alldata['RetResults'] = $RetResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->where("tblpatbilling.fldcomp", Helpers::getCompName())

                ->when(($finalfrom) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom. ' 00:00:00')
                            ->where('tblpatbilling.fldtime','<=',$finalto.' 23:59:59');
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode){
                    return $q->where('tblpatbilling.fldbillingmode','like',$billingmode);
                })

                ->where('tblreportgroup.fldgroup','!=',null)
                ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($RetResults->toArray()));

            $alldata['ForNetResults'] = $ForNetResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->where("tblpatbilling.fldcomp", Helpers::getCompName())

                ->when(($finalfrom) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom. ' 00:00:00')
                            ->where('tblpatbilling.fldtime','<=',$finalto.' 23:59:59');
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode){
                    return $q->where('tblpatbilling.fldbillingmode','like',$billingmode);
                })

                ->where('tblreportgroup.fldgroup','!=',null)
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
                $comp = Helpers::getCompName();
            $reports = array_merge($reports, array_keys($ForNetResults->toArray()));

                $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
                where fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldsave = 1
                and (fldencounterval  NOT LIKE '%IP%')
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = '".$comp."' ";


                $opitemamoutsumData = DB::select(
                    $opitemamoutsumsql
                );
                //op collection
                $alldata['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


                $opitemamoutdetailsumsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                where fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldsave = 1
                and (fldencounterval  NOT LIKE '%IP%')
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = '".$comp."' ";


                $opitemamoutsumDatadetail = DB::select(
                    $opitemamoutdetailsumsql
                );
                //OP_collection_patbilling
                $alldata['OP_collection_patbilling'] =   $opitemamoutsumDatadetail ? $opitemamoutsumDatadetail[0]->totaldepo : 0;



                $ipitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
                where fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldsave = 1
                and (fldencounterval  LIKE 'IP%')
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = '".$comp."' ";


                $ipitemamoutsumData = DB::select(
                        $ipitemamoutsumsql
                    );
                    //ipcolleciton
                $alldata['IP_Patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;

                $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                                    where fldbillno like '%dep%'
                                    and fldtime >= '".$finalfrom. ' 00:00:00'."'
                                    and fldtime <='".$finalto.' 23:59:59'."'
                                    and (fldpayitemname like '%admission deposit%'
                                    or fldpayitemname like 'op deposit'
                                    or fldpayitemname like '%re deposit%'
                                    or fldpayitemname like '%blood bank%'
                                    or fldpayitemname like '%gate pass%'
                                    or fldpayitemname like '%post-up%')

                                    and fldcomp = '".$comp."'
                                ";


                $opdepositData = DB::select(
                        $opdepositDataSql
                    );


                $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

                $alldata['deposit'] =  $totalopdeposit;


                $previousdepositDataSql = "select sum(fldprevdeposit) as prevamt from tblpatbilldetail
                where  fldbillno like '%CAS%'
                 and fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                 and fldtime <='" . $finalto.' 23:59:59' . "'
                 and fldpayitemname like '%Discharge Clearence%'
                 and fldreceivedamt > '0'
                 and fldcomp = '".$comp."'
                ";


                $previousdepositData = DB::select(
                $previousdepositDataSql
                    );
                $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
                $alldata['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;


                $dischargeclearanceDataSql = "select sum(fldreceivedamt) as dischargeamt from tblpatbilldetail
                where  fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldpayitemname like '%Discharge Clearence%'
                and fldreceivedamt >= '0'
                and fldcomp = '".$comp."'
                 ";


                $dischargeclearanceData = DB::select(
                        $dischargeclearanceDataSql
                );
                $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
                //Discharge Garda Leko amount
                $alldata['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;


                $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
                where fldbillno like '%dep%'
                and fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldpayitemname like '%deposit refund%'
                and fldcomp = '".$comp."'
                ";


                $opdepositrefData = DB::select(
                    $opdepositrefDataSql
                );
                $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
                $alldata['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;


                $patbilling_fldditemamtsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
                where fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldsave = 1
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = '".$comp."' ";

                $patbilling_fldditemamtData = DB::select(
                    $patbilling_fldditemamtsql
                );
                $patbilling_fldditemamt = $patbilling_fldditemamtData ? $patbilling_fldditemamtData[0]->totaldepo : 0;
                $alldata['patbilling_fldditemamt'] = ($patbilling_fldditemamt != NULL) ? $patbilling_fldditemamt : 0;

                $rev_amount_sumSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
                where  fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldcomp = '".$comp."'
                ";

                $rev_amount_sumData = DB::select(
                    $rev_amount_sumSql
                );
                $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
                // /Detail ko sum:
                $alldata['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;


                $ipreturnsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                where fldtime >= '" . $finalfrom. ' 00:00:00' . "'
                and fldtime <='" . $finalto.' 23:59:59' . "'
                and fldsave = 1
                and (fldencounterval LIKE '%IP%')
                and  (fldbillno LIKE '%RET%')
                and fldcomp = '".$comp."' ";


                $ipreturnsqlData = DB::select(
                    $ipreturnsql
                );
                //ip returns
                $alldata['ipreturns'] =   $ipreturnsqlData ? $ipreturnsqlData[0]->totaldepo : 0;

            $alldata['reports'] = $reports = array_unique($reports);

            $alldata['certificate'] = "GROUP CATEGORY";
            return view('reports::groupreport.export-categorywise-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportCategorywiseExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupCategorywiseExport($request->all()), 'Group-Categorywise-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportParticularReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'),\DB::raw('SUM(tblpatbilling.flditemqty) as qnty'),\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as totl'),'tblpatbilling.flditemtype as flditemtype','tblpatbilling.flditemname as flditemname','tblreportgroup.fldgroup as fldgroup')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->groupBy('flditemname')
                                ->get()
                                ->groupBy('fldgroup');
            $alldata['certificate'] = "GROUP ITEM";
            return view('reports::groupreport.export-particularwise-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportParticularExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupParticularwiseExport($request->all()), 'Group-Particularwise-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDetailReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval','tblpatbilling.flditemrate as rate','tblpatbilling.flditemqty as qnty','tblpatbilling.flddiscamt as dsc','tblpatbilling.fldtaxamt as tax','tblpatbilling.fldditemamt as totl','tblpatbilling.flditemtype as flditemtype','tblpatbilling.flditemname as flditemname','tblpatbilling.fldtime as entrytime','tblreportgroup.fldgroup as fldgroup')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->get()
                                ->groupBy('fldgroup');
            $alldata['certificate'] = "GROUP DETAIL";
            return view('reports::groupreport.export-details-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDetailExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupDetailsExport($request->all()), 'Group-Details-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDatesReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $alldata['dateType'] = $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select(\DB::raw('avg(tblpatbilling.flditemrate) as rate'),\DB::raw('SUM(tblpatbilling.flditemqty) as qnty'),\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as totl'),'tblpatbilling.flditemtype as flditemtype',\DB::raw('DATE(tblpatbilling.fldtime) as entry_date'),'tblreportgroup.fldgroup as fldgroup')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom)
                                            ->groupBy('entry_date');
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto)
                                            ->groupBy('entry_date');
                                })
                                ->get()
                                ->groupBy('fldgroup');
                $alldata['certificate'] = "DATE";
            return view('reports::groupreport.export-dates-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportDatesExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupDatesExport($request->all()), 'Group-Dates-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportPatientReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval as fldencounterval',\DB::raw('SUM(tblpatbilling.flddiscamt) as dsc'),\DB::raw('SUM(tblpatbilling.fldtaxamt) as tax'),\DB::raw('SUM(tblpatbilling.fldditemamt) as tot'))
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->groupBy('fldencounterval')
                                ->get();
            $alldata['certificate'] = "PATIENT";
            return view('reports::groupreport.export-patient-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportPatientExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupPatientExport($request->all()), 'Group-Patient-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportVisitsReport(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $request->dateType;
            $alldata['itemRadio'] = $itemRadio = $request->itemRadio;
            $alldata['billingmode'] = $billingmode = $request->billingmode;
            $alldata['comp'] = $comp = $request->comp;
            $alldata['selectedItem'] = $selectedItem = $request->selectedItem;
            $alldata['datas'] = PatBilling::select('tblpatbilling.fldencounterval as fldencounterval')
                                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                                ->where('tblpatbilling.fldsave',1)
                                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                                            ->where('tblpatbilling.fldtime','<=',$finalto);
                                })
                                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                                })
                                ->when($comp != "%", function ($q) use ($comp){
                                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                                })
                                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                                ->groupBy('fldencounterval')
                                ->get();
            $alldata['certificate'] = "VISIT";
            return view('reports::groupreport.export-visits-pdf',$alldata);
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function exportVisitsExcel(Request $request){
        try{
            ob_end_clean();
            ob_start();
            return \Maatwebsite\Excel\Facades\Excel::download(new GroupVisitsExport($request->all()), 'Group-Visits-Report.xlsx');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function removeGroupParticular(Request $request){
        try{
            $group = ReportGroup::where('fldid',$request->fldid)->first();
            if(isset($group)){
                $group->delete();
                return response()->json([
                    'data' => [
                        'status' => true
                    ]
                ]);
            }else{
                return response()->json([
                    'data' => [
                        'status' => false
                    ]
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }
}
