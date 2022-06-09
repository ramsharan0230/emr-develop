<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Department;
use App\Encounter;
use App\Events\ExcelDownloadedEvent;
use App\Exports\BillingReportDetailExport;
use App\Exports\BillingReportExport;
use App\HospitalDepartmentUsers;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\ServiceGroup;
use App\Utils\Helpers;
use Auth;
use Cache;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

use App\Exports\ServiceTaxreport;
use App\HospitalDepartment;
use App\Jobs\NotifyUserOfCompleted;
use Exception;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Services\UserService;
use Carbon\Carbon;

class BillingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $encounter_id_session = Session::get('billing_encounter_id');
        $data['patient_status_disabled'] = 0;
        $data['html'] = '';
        $data['total'] = $data['discount'] = 0;
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });

        $data['packages'] = ServiceGroup::select('fldgroup')->groupBy('fldgroup')->pluck('fldgroup');
        $data['doctors'] = UserService::getDoctors(['firstname', 'lastname', 'id'])->pluck('fldfullname', 'id');
        $user = Auth::guard('admin_frontend')->user();

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }

        $data['departments'] = Department::select('flddept')->where('fldstatus', '1')->where('fldcateg', 'Consultation')->get();

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

        $data['results'] = array();
        return view('billing::report', $data);
    }

    public function indexNew(Request $request)
    {
        $encounter_id_session = Session::get('billing_encounter_id');
        $data['patient_status_disabled'] = 0;
        $data['html'] = '';
        $data['total'] = $data['discount'] = 0;
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });

        $data['packages'] = ServiceGroup::select('fldgroup')->groupBy('fldgroup')->pluck('fldgroup');
        $data['doctors'] = UserService::getDoctors(['firstname', 'lastname', 'id'])->pluck('fldfullname', 'id');
        $user = Auth::guard('admin_frontend')->user();

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }

        $data['departments'] = Department::select('flddept')->where('fldstatus', '1')->where('fldcateg', 'Consultation')->get();

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;

        $data['results'] = array();
        return view('billing::report-new', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getPackage(Request $request)
    {
        $data["--Package--"] = "";
        if ($request->value) {
            $packages = ServiceGroup::select('fldgroup')->where("billingmode", $request->value)->groupBy('fldgroup')->pluck('fldgroup');
            foreach ($packages as $package) {
                $data[$package] = $package;
            }
        } else {
            $packages = ServiceGroup::select('fldgroup')->groupBy('fldgroup')->pluck('fldgroup');
            foreach ($packages as $package) {
                $data[$package] = $package;
            }
        }
        echo json_encode($data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchBillingDetail(Request $request)
    {
        try {
            $finalfrom = $request->eng_from_date;
            $finalto = $request->eng_to_date;
            // echo $finalfrom.'/'.$finalto; exit;
            $search_type = $request->search_type;
            $search_text = $request->search_type_text;
            $department = $request->department;
            $search_name = $request->seach_name;
            $cash_credit = $request->cash_credit;
            $billingmode = $request->billing_mode;
            $paymentmode = $request->payment_mode;
            $package = $request->package;
            $report_type = $request->report_type;
            $item_type = $request->item_type;
            $patient_department = $request->patient_department;
            $doctor_id = $request->doctor;
            $doctor = !is_null($doctor_id) ? CogentUsers::findOrFail($doctor_id) : null;

            if ($search_name != '') {
                // echo "here"; exit;
                $encountersql = 'select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptnamefir like "' . $search_name . '%")';
                $encounters = DB::select($encountersql);
            }

            if ($billingmode != '') {
                $billingencountersql = 'select e.fldencounterval from tblencounter as e WHERE e.fldbillingmode like "' . $billingmode . '"';
                $billingencounter = DB::select($billingencountersql);
            }

            if ($patient_department != '%') {

                $departmentencounterdatasql = "select DISTINCT(e.fldencounterval) from tblencounter as e WHERE e.fldcurrlocat like '" . $patient_department . "'";
                $departmentencounters = DB::select(\DB::Raw($departmentencounterdatasql));
            }

            if ($item_type != '') {
                $itembillnosql = 'select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "' . $item_type . '"';
                $billno = DB::select($itembillnosql);
            }

            $result = PatBillDetail::query();

            if ($report_type != '') {
                if ($report_type == 'RET') {
                    $reporttypesql = 'select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "Medicines" or pb.flditemtype like "Extra Items" or pb.flditemtype like "Surgicals" and cast(fldtime as date) BETWEEN "' . $finalfrom . ' 00:00:00" and "' . $finalto . ' 23:59:59"';
                    $reporttypebillno = DB::select($reporttypesql);
                }
            }


            if ($department != '') {
                $result->where('fldcomp', $department);
            }

            if ($search_type == 'enc' and $search_text != '') {
                $result->where('fldencounterval', 'LIKE', $search_text);
            } else if ($search_type == 'user' and $search_text != '') {
                $result->where('flduserid', 'LIKE', $search_text);
            } else if ($search_type == 'invoice' and $search_text != '') {
                $result->where('fldbillno', 'LIKE', $search_text);
            } else {
                //nothing
            }


            if ($search_name != '') {
                $result->whereIn('fldencounterval', collect($encounters)->pluck('fldencounterval'));
            }
            if ($cash_credit != '') {
                $result->where('fldbilltype', $cash_credit);
            }
            if ($paymentmode != '') {
                $result->where('payment_mode', $paymentmode);
            }

            if ($billingmode != '') {
                $result->whereIn('fldencounterval', collect($billingencounter)->pluck('fldencounterval'));
            }
            if ($package != '') {
                $result->whereHas('patBill', function ($query) use ($package) {
                    $query->where('package_name', $package);
                });
            }
            if ($patient_department != '%') {
                $result->whereIn('fldencounterval', collect($departmentencounters)->pluck('fldencounterval'));
            }

            if ($report_type != '') {
                if ($report_type == 'CRE') {
                    $result->where('fldbillno', 'LIKE', 'CRE%');
                    $result->orWhere('fldbillno', 'LIKE', 'RET%');
                } else if ($report_type == 'RET') {
                    $result->whereIn('fldbillno', collect($reporttypebillno)->pluck('fldbillno'));
                } else {
                    $result->where('fldbillno', 'LIKE', $report_type . '%');
                }
            }

            if ($item_type != '') {
                $result->whereIn('fldbillno', collect($billno)->pluck('fldbillno'));
            }

            $result->where('fldtime', '>=', $finalfrom . ' 00:00:00');
            $result->where('fldtime', '<=', $finalto . ' 23:59:59');
            $result->where('fldcomp', 'LIKE', '%' . Helpers::getCompName() . '%');
            $result->when(!is_null($doctor_id), function ($query) use ($doctor_id) {
                return $query->whereHas('patbill.pat_billing_shares.user', function ($query) use ($doctor_id) {
                    return $query->where('id', $doctor_id);
                });
            });



            $sumresult = $result->get();
            // dd($sumresult);
            $totaldep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
                ->whereRaw("fldbillno like '%dep%'
                    and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                    and (
                        fldpayitemname like '%admission deposit%'
                        or fldpayitemname like 'op deposit'
                        or fldpayitemname like '%re deposit%'
                        or fldpayitemname like '%blood bank%'
                        or fldpayitemname like '%gate pass%'
                        or fldpayitemname like '%post-up%'
                        or fldpayitemname like '%Pharmacy Deposit%'
                    )
                    and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();

            $totalrefdep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
                ->whereRaw("fldbillno like '%dep%'
                    and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                    and fldpayitemname like '%deposit refund%'
                    and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();

            $results = $result->paginate(10);




            $html = '';
            $sumhtml = '';

            if (isset($results) and count($results) > 0) {

                foreach ($results as $k => $r) {

                    $datetime = explode(' ', $r->fldtime);

                    $enpatient = Encounter::where('fldencounterval', $r->fldencounterval)->with('patientInfo')->first();
                    // dd($enpatient);
                    $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
                    $sn = $k + 1;
                    // if((!is_null($r->fldbillno) or $r->fldbillno !='') and $r->fldtem)
                    $html .= '<tr data-billno="' . $r->fldbillno . '" class="bill-list">';
                    $html .= '<td>' . $sn . '</td>';
                    $billtype = explode('-', $r->fldbillno);
                    if ($r->fldpayitemname === "Discharge Clearence" and $billtype[0] == 'CAS') {
                        $html .= '<td><a href="' . route('discharge.clearance.print') . '?encounter_id=' . $r->fldencounterval . '&billno=' . $r->fldbillno . '" class="btn btn-primary bill" target="_blank"><i class="fas fa-print"></i></a></td>';
                    } else if ($r->fldpayitemname === "Discharge Clearence" and $billtype[0] == 'PHM') {
                        $html .= '<td><a href="' . route('discharge.clearance.print.pharmacy') . '?fldencounterval=' . $r->fldencounterval . '&billno=' . $r->fldbillno . '&payment_mode=' . $r->payment_mode . '" class="btn btn-primary bill" target="_blank"><i class="fas fa-print"></i></a></td>';
                    } else {
                        $html .= '<td><a href="javascript:void(0);" class="btn btn-primary bill"  data-bill="' . $r->fldbillno . '" ><i class="fas fa-print"></i></a></td>';
                    }


                    $html .= '<td>' . $datetime[0] . '</td>';
                    $html .= '<td>' . $datetime[1] . '</td>';
                    $html .= '<td>' . $r->fldbillno . '</td>';
                    $html .= '<td>' . $r->fldencounterval . '</td>';
                    $html .= '<td>' . $fullname . '</td>';

                    $html .= '<td>' . Helpers::numberFormat($r->fldprevdeposit) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->flditemamt) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->fldtaxamt) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->flddiscountamt) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->fldchargedamt) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->fldreceivedamt) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($r->fldcurdeposit) . '</td>';
                    $html .= '<td>' . $r->flduserid . '</td>';
                    $html .= '<td>' . $r->payment_mode . '</td>';
                    $html .= '<td>' . Helpers::getBillingModeByBillno($r->fldbillno) . '</td>';

                    $fullname =  null;
                    if (!is_null($doctor)) {
                        $fullname = $doctor->fldfullname;
                    }
                    $html .= '<td>' . $r->flddiscountgroup . '</td>';
                    $html .= '<td>' . $fullname . '</td>';
                    $html .= '<td>' . $r->payment_mode . '</td>';
                    $html .= '</tr>';
                }


                $html .= '<tr><td colspan="20">' . $results->appends(request()->all())->links() . '</td></tr>';
                $chaargedamt = $sumresult->sum('flditemamt') + $sumresult->sum('fldtaxamt') - $sumresult->sum('flddiscountamt');
                $sumhtml .= '<tr><td><b><u>Total</u></b></td>
                        <td> <b>DEPOSIT:</b> ' . Helpers::numberFormat($totaldep[0]->totaldepo) . '</td>
                        <td> <b>DEPOSIT REFUND:</b> ' . Helpers::numberFormat($totalrefdep[0]->totalrefund) . '</td>
                        <td> <b>AMOUNT:</b> ' . Helpers::numberFormat($sumresult->sum('flditemamt')) . '</td>
                        <td> <b>TAX:</b> ' . Helpers::numberFormat($sumresult->sum('fldtaxamt')) . '</td>
                        <td> <b>DISCOUNT:</b> ' . Helpers::numberFormat($sumresult->sum('flddiscountamt')) . '</td>
                        <td> <b>CHARGED AMT:</b> ' . Helpers::numberFormat($chaargedamt) . '</td>
                        <td> <b>RECEIVED:</b> ' . Helpers::numberFormat($sumresult->sum('fldreceivedamt')) . '</td>
                        </tr>';
            }
            $data['doctor'] = $doctor;
            $data['results'] = $results;
            // dd($data);
            // $html =  view('billing::ajax-views.ajax-billing-report-table-view', $data)->render();
            $data['html'] = $html;
            $data['sumhtml'] = $sumhtml;
            return $data;
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    public function newSearchBillingDetail(Request $request)
    {
        // dd(\App\Utils\Helpers::getCompName());
        try {
            $finalfromeng = $request->eng_from_date;
            $finaltoeng = $request->eng_to_date;
            if ($request->search_type == "ad_date") {
                $finalfrom = $request->eng_from_date;
                $finalto = $request->eng_to_date;
            } else {
                $finalfrom = $request->from_date;
                $finalto = $request->to_date;
            }
            $department = $request->department;
            $package = $request->package;
            $report_type = $request->report_type;
            $item_type = $request->item_type;
            $doctor_id = $request->doctor;
            $doctor = !is_null($doctor_id) ? CogentUsers::findOrFail($doctor_id) : null;
            $billtype = Helpers::billType($request->report_type);

            $result =  PatBillDetail::where('fldcomp', $department)
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })
                ->when($item_type != '', function ($query) use ($item_type) {
                    $query->whereHas('patBill', function ($q) use ($item_type) {
                        $q->where('flditemtype', 'LIKE', '%' . $item_type . '%');
                    });
                })
                ->when($report_type != '' && $report_type == 'CAS', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%CAS%')
                            ->orWhere('fldbillno', 'LIKE', '%REG%');
                        $q->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    });
                })
                ->when($report_type != '' && $report_type == 'DEP', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%DEP%')
                            ->where('fldcurdeposit', '>', 0);
                    });
                })
                ->when($report_type != '' && $report_type == 'CRE', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%CRE%')
                            ->where('fldcurdeposit', '<', 0);
                    });
                })
                ->when($report_type != '' && $report_type == 'PHM', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('flditemtype', 'LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'LIKE', '%Extra Items%');
                    });
                })
                ->when($report_type != '' && $report_type == 'RET', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%RET%');
                    });
                })
                ->when($report_type != '' && $report_type == 'DISCLR', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldpayitemname', 'LIKE', '%Discharge Clearence%');
                    });
                })
                ->when($report_type != '' && $report_type == 'Refund', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldpayitemname', 'LIKE', '%Pharmacy Deposit Refund%')
                            ->orwhere('fldpayitemname', 'LIKE', '%Deposit Refund%');
                    });
                });



            $result->where('fldtime', '>=', $finalfromeng . ' 00:00:00');
            $result->where('fldtime', '<=', $finaltoeng . ' 23:59:59');
            $result->where('fldcomp', 'LIKE', '%' . Helpers::getCompName() . '%');
            $result->when(!is_null($doctor_id), function ($query) use ($doctor_id) {
                return $query->whereHas('patbill.pat_billing_shares.user', function ($query) use ($doctor_id) {
                    return $query->where('id', $doctor_id);
                });
            });




            $results = $result->with(['patbill' => function ($query) {
                return $query->has('pat_billing_shares');
            }, 'patbill.pat_billing_shares.user'])->paginate(10);

            //  dd(DB::getQueryLog());



            $sumresult = $result->groupBy('tblpatbilldetail.fldbillno')->paginate(10);



            $totaldep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
                ->whereRaw("fldbillno like '%dep%'
                and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                and (
                    fldpayitemname like '%admission deposit%'
                    or fldpayitemname like 'op deposit'
                    or fldpayitemname like '%re deposit%'
                    or fldpayitemname like '%blood bank%'
                    or fldpayitemname like '%gate pass%'
                    or fldpayitemname like '%post-up%'
                    or fldpayitemname like '%Pharmacy Deposit%'
                )
                and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();

            $totalrefdep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
                ->whereRaw("fldbillno like '%dep%'
                and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                and fldpayitemname like '%deposit refund%'
                and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();



            $html = '';
            $sumhtml = '';
            //  dd($results);

            $summary['totaldep'] = $totaldep;
            $summary['totalrefdep'] = $totalrefdep;
            $summary['sumresult'] = $sumresult;

            $data['billType'] = $billtype;
            $data['doctor'] = $doctor;
            $data['results'] = $results;
            $bredcrumd['finalfrom'] = $finalfrom;
            $bredcrumd['finalto'] = $finalto;
            $bredcrumd['department'] = $department;
            $data['filterMessage'] = view('billing::ajax-views.ajax-billing-bredcumb', $bredcrumd)->render();
            $html =  view('billing::ajax-views.ajax-billing-report-table-view', $data)->render();
            $data['html'] = $html;
            $data['sumhtml'] = view('billing::ajax-views.ajax-billing-summary', $summary)->render();
            return $data;
        } catch (\Exception $e) {
            //    dd($e);
            throw new \Exception($e);
            // throw new \Exception(__('messages.error'));
        }
    }


    /**
     * custom search for laravel pagination
     */
    public function searchBillFromKeyword(Request $request)
    {
        $columns = ['fldencounterval', 'fldbillno', 'fldpayitemname', 'payment_mode', 'flddiscountgroup'];

        if ($request->search_type == "ad_date") {
            $finalfrom = $request->from_date;
            $finalto = $request->to_date;
        } else {
            $finalfrom = $request->eng_from_date;
            $finalto = $request->eng_to_date;
        }



        $department = $request->department;
        $package = $request->package;
        $report_type = $request->report_type;
        $item_type = $request->item_type;
        $doctor_id = $request->doctor;
        $doctor = !is_null($doctor_id) ? CogentUsers::findOrFail($doctor_id) : null;

        if ($item_type != '') {
            $itembillnosql = 'select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "' . $item_type . '"';
            $billno = DB::select($itembillnosql);
        }

        $result = PatBillDetail::query();

        if ($report_type != '') {
            if ($report_type == 'RET') {
                $reporttypesql = 'select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "Medicines" or pb.flditemtype like "Extra Items" or pb.flditemtype like "Surgicals" and cast(fldtime as date) BETWEEN "' . $finalfrom . ' 00:00:00" and "' . $finalto . ' 23:59:59"';
                $reporttypebillno = DB::select($reporttypesql);
            }
        }


        if ($department != '') {
            $result->where('tblpatbilldetail.fldcomp', $department);
        } else {
            $result->where('tblpatbilldetail.fldcomp', 'LIKE', '%' . Helpers::getCompName() . '%');
        }

        if ($package != '') {
            $result->whereHas('patBill', function ($query) use ($package) {
                $query->where('package_name', $package);
            });
        }


        if ($report_type != '') {
            if ($report_type == 'CRE') {
                $result->where('tblpatbilldetail.fldbillno', 'LIKE', 'CRE%');
                $result->orWhere('tblpatbilldetail.fldbillno', 'LIKE', 'RET%');
            } else if ($report_type == 'RET') {
                $result->whereIn('tblpatbilldetail.fldbillno', collect($reporttypebillno)->pluck('fldbillno'));
            } else {
                $result->where('tblpatbilldetail.fldbillno', 'LIKE', $report_type . '%');
            }
        }

        if ($item_type != '') {
            $result->whereIn('tblpatbilldetail.fldbillno', collect($billno)->pluck('fldbillno'));
        }

        $result->where('tblpatbilldetail.fldtime', '>=', $finalfrom . ' 00:00:00');
        $result->where('tblpatbilldetail.fldtime', '<=', $finalto . ' 23:59:59');



        $serviceValue = ['Medicines', 'Extra Items'];
        $result->join('tblpatbilling', 'tblpatbilling.fldbillno', '=', 'tblpatbilldetail.fldbillno')
            ->select(
                'tblpatbilldetail.*',
                'tblpatbilling.flditemtype as shouldnotItemServicebill',
                \DB::raw('(CASE

                    WHEN
                    tblpatbilldetail.fldbillno like "%PHM%"
                    THEN "Pharmacy Bill"

                    WHEN
                        tblpatbilldetail.payment_mode = "Cash"
                        And
                            (tblpatbilling.flditemtype != "Medicines"
                            And
                            tblpatbilling.flditemtype != "Extra Items"
                            )

                        THEN "Service Bill"
                    WHEN
                    tblpatbilldetail.fldbillno like "%RET%"
                    THEN "Return Bill"

                    WHEN
                    tblpatbilldetail.payment_mode = "Cash"
                        And
                    tblpatbilldetail.fldpayitemname = "Discharge Clearence"
                    THEN "Discharge Clearange"

                    WHEN
                    tblpatbilldetail.payment_mode = "Credit"
                    THEN "Credit Billing"

                    WHEN
                    tblpatbilldetail.payment_mode = "Cash"
                    And

                        ( tblpatbilldetail.fldpayitemname like "%Admission Deposit%" or
                            tblpatbilldetail.fldpayitemname like "%Op Deposit%" or
                            tblpatbilldetail.fldpayitemname like "%Re Deposit%" or
                            tblpatbilldetail.fldpayitemname like "%Pharmacy Deposit%"
                        )
                    THEN "Deposit Billing"

                    ELSE "Other Bill"
                    END) AS billType')
            );






        $trimrequest = trim($request->query('keyword'));
        $result->where(function ($querywhere) use ($columns, $trimrequest) {
            foreach ($columns as $colum) {
                $querywhere->orWhere('tblpatbilldetail.' . $colum, 'like', "%{$trimrequest}%");
            }
            // $queryWhere->
        })->whereHas('encounter.patientInfo', function ($query) use ($trimrequest) {
            $query->orWhere('fldptnamefir', 'like', "%{$trimrequest}%");
        });

        // dd($result->first());

        $sumresult = $result->paginate(10);
        $totaldep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
            ->whereRaw("fldbillno like '%dep%'
                and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                and (
                    fldpayitemname like '%admission deposit%'
                    or fldpayitemname like 'op deposit'
                    or fldpayitemname like '%re deposit%'
                    or fldpayitemname like '%blood bank%'
                    or fldpayitemname like '%gate pass%'
                    or fldpayitemname like '%post-up%'
                    or fldpayitemname like '%Pharmacy Deposit%'
                )
                and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
            ->when($package != '', function ($query) use ($package) {
                $query->whereHas('patBill', function ($q) use ($package) {
                    $q->where('package_name', $package);
                });
            })->get();

        $totalrefdep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
            ->whereRaw("fldbillno like '%dep%'
                and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
                and fldpayitemname like '%deposit refund%'
                and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
            ->when($package != '', function ($query) use ($package) {
                $query->whereHas('patBill', function ($q) use ($package) {
                    $q->where('package_name', $package);
                });
            })->get();
        $results = $result->paginate(10);
        $data['doctor'] = $doctor;
        $data['results'] = $results;
        $html =  view('billing::ajax-views.ajax-billing-report-table-view', $data)->render();
        $data['html'] = $html;

        $html = '';
        $sumhtml = '';
        return $data;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getQuantityChartDetail(Request $request)
    {
        try {
            // Helpers::jobRecord('fmSampReport', 'Laboratory Report');
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
            // echo $finalfrom.'/'.$finalto; exit;
            $search_type = $request->search_type;
            $search_text = $request->search_type_text;
            $department = $request->department;
            $search_name = $request->seach_name;
            $cash_credit = $request->cash_credit;
            $paymentmode = $request->payment_mode;
            $billingmode = $request->billing_mode;
            $report_type = $request->report_type;
            $item_type = $request->item_type;

            if ($search_type == 'enc' and $search_text != '') {
                $searchquery = 'and pbd.fldencounterval like "' . $search_text . '"';
            } else if ($search_type == 'user' and $search_text != '') {
                $searchquery = 'and pbd.flduserid like "' . $search_text . '"';
            } else if ($search_type == 'invoice' and $search_text != '') {
                $searchquery = 'and pbd.fldbillno like "' . $search_text . '"';
            } else {
                $searchquery = '';
            }

            if ($department != '') {
                $departmentquery = 'where pbd.hospital_department_id =' . $department;
            } else {
                $departmentquery = '';
            }

            if ($search_name != '') {
                $searchnamequery = 'and pbd.fldencounterval in (select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptnamefir like "' . $search_name . '%"))';
            } else {
                $searchnamequery = '';
            }
            if ($cash_credit != '') {
                $cashquery = 'and pbd.fldbilltype like "' . $cash_credit . '"';
            } else {
                $cashquery = '';
            }
            if ($paymentmode != '') {
                $paymentmode = 'and pbd.payment_mode like "' . $paymentmode . '"';
            } else {
                $paymentmode = '';
            }

            if ($billingmode != '') {
                $billingmodequery = 'and pbd.fldencounterval in(select e.fldencounterval from tblencounter as e WHERE e.fldbillingmode like "' . $billingmode . '")';
            } else {
                $billingmodequery = '';
            }

            if ($report_type != '') {
                $reporttypequery = 'and pbd.fldbillno like "' . $report_type . '%"';
            } else {
                $reporttypequery = '';
            }

            if ($item_type != '') {
                $itemtypequery = 'and pbd.fldbillno in(select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "' . $item_type . '")';
            } else {
                $itemtypequery = '';
            }
            $sql = 'select pbd.fldtime,pbd.fldbillno,pbd.fldencounterval,pbd.fldprevdeposit,pbd.flditemamt,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldchargedamt,pbd.fldreceivedamt,pbd.fldcurdeposit,pbd.flduserid,pbd.fldbilltype,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldbankname,pbd.fldchequeno,pbd.fldtaxgroup,pbd.flddiscountgroup,pbd.hospital_department_id from tblpatbilldetail as pbd where pbd.fldtime>="' . $finalfrom . '" and pbd.fldtime<="' . $finalto . '"' . $departmentquery . $searchquery . $searchnamequery . $reporttypequery . '' . $cashquery . $itemtypequery . $billingmodequery;
            // echo $sql; exit;
            $result = DB::select(
                $sql
            );
            $html = '';
            $encounters = array();
            $quantity = array();
            if (isset($result) and count($result) > 0) {
                foreach ($result as $k => $r) {
                    $encounters[] = $r->fldencounterval;
                    $qty = PatBilling::where('fldbillno', $r->fldbillno)->get()->sum('flditemqty');

                    $quantity[] = $qty;
                }
            }
            // dd($quantity);
            $quantitydata = array(
                'encounters' => $encounters,
                'quantity' => $quantity
            );
            // dd($quantitydata);
            return response()->json($quantitydata);
            // return $data;
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportBillingReport()
    {
        $request = request();
        // dd(\App\Utils\Helpers::getCompName());
        try {
            $finalfromeng = $request->eng_from_date;
            $finaltoeng = $request->eng_to_date;
            if ($request->search_type == "ad_date") {
                $finalfrom = $request->eng_from_date;
                $finalto = $request->eng_to_date;
            } else {
                $finalfrom = $request->from_date;
                $finalto = $request->to_date;
            }
            
            $department = $request->department;
            $package = $request->package;
            $report_type = $request->report_type;
            $item_type = $request->item_type;
            $doctor_id = $request->doctor;
            $doctor = !is_null($doctor_id) ? CogentUsers::findOrFail($doctor_id) : null;
            $billtype = Helpers::billType($request->report_type);

            $result =  PatBillDetail::where('fldcomp', $department)
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })
                ->when($item_type != '', function ($query) use ($item_type) {
                    $query->whereHas('patBill', function ($q) use ($item_type) {
                        $q->where('flditemtype', 'LIKE', '%' . $item_type . '%');
                    });
                })
                ->when($report_type != '' && $report_type == 'CAS', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%CAS%')
                            ->orWhere('fldbillno', 'LIKE', '%REG%');
                        $q->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    });
                })
                ->when($report_type != '' && $report_type == 'DEP', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%DEP%')
                            ->where('fldcurdeposit', '>', 0);
                    });
                })
                ->when($report_type != '' && $report_type == 'CRE', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%CRE%')
                            ->where('fldcurdeposit', '<', 0);
                    });
                })
                ->when($report_type != '' && $report_type == 'PHM', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('flditemtype', 'LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'LIKE', '%Extra Items%');
                    });
                })
                ->when($report_type != '' && $report_type == 'RET', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldbillno', 'LIKE', '%RET%');
                    });
                })
                ->when($report_type != '' && $report_type == 'DISCLR', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldpayitemname', 'LIKE', '%Discharge Clearence%');
                    });
                })
                ->when($report_type != '' && $report_type == 'Refund', function ($query) use ($report_type) {
                    $query->whereHas('patBill', function ($q) use ($report_type) {
                        $q->where('fldpayitemname', 'LIKE', '%Pharmacy Deposit Refund%')
                            ->orwhere('fldpayitemname', 'LIKE', '%Deposit Refund%');
                    });
                });



            $result->where('fldtime', '>=', $finalfromeng . ' 00:00:00');
            $result->where('fldtime', '<=', $finaltoeng . ' 23:59:59');
            $result->where('fldcomp', 'LIKE', '%' . Helpers::getCompName() . '%');
            $result->when(!is_null($doctor_id), function ($query) use ($doctor_id) {
                return $query->whereHas('patbill.pat_billing_shares.user', function ($query) use ($doctor_id) {
                    return $query->where('id', $doctor_id);
                });
            });

            $results = $result->with(['patbill' => function ($query) {
                return $query->has('pat_billing_shares');
            }, 'patbill.pat_billing_shares.user'])->get();
            $sumresult = $result->groupBy('tblpatbilldetail.fldbillno')->get();
            $totaldep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
                ->whereRaw("fldbillno like '%dep%'
            and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
            and (
                fldpayitemname like '%admission deposit%'
                or fldpayitemname like 'op deposit'
                or fldpayitemname like '%re deposit%'
                or fldpayitemname like '%blood bank%'
                or fldpayitemname like '%gate pass%'
                or fldpayitemname like '%post-up%'
                or fldpayitemname like '%Pharmacy Deposit%'
            )
            and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();

            $totalrefdep = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
                ->whereRaw("fldbillno like '%dep%'
            and cast(fldtime as date) BETWEEN '" . $finalfrom . " 00:00:00' and '" . $finalto . " 23:59:59'
            and fldpayitemname like '%deposit refund%'
            and fldcomp LIKE '%" . Helpers::getCompName() . "%'")
                ->when($package != '', function ($query) use ($package) {
                    $query->whereHas('patBill', function ($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();
            $html = '';
            $sumhtml = '';
            //  dd($results);

            $summary['totaldep'] = $totaldep;
            $summary['totalrefdep'] = $totalrefdep;
            $summary['sumresult'] = $sumresult;

            $data['billType'] = $billtype;
            $data['doctor'] = $doctor;
            $data['results'] = $results;
            $bredcrumd['finalfrom'] = $finalfrom;
            $bredcrumd['finalto'] = $finalto;
            $bredcrumd['department'] = $department;
            $data['filterMessage'] = view('billing::ajax-views.ajax-billing-bredcumb', $bredcrumd)->render();
            $html =  view('billing::ajax-views.ajax-billing-report-print-table', $data)->render();
            $data['html'] = $html;
            $data['sumhtml'] = view('billing::ajax-views.ajax-billing-print-summary', $summary)->render();
            $data['date']=[
                'from'=> $finalfrom,
                'to'=> $finalto,
                'now'=> $request->search_type=='bs_date'?Helpers::dateToNepali(Carbon::now()):Carbon::now()->format('Y-m-d g:i A')
            ];
            return view('billing::pdf.billing-report')->with('data', $data);
        } catch (\Exception $e) {
            //    dd($e);
            throw new \Exception($e);
            // throw new \Exception(__('messages.error'));
        }
    }

    public function generateInvoice(Request $request)
    {
        try {
            $countdata = PatBillCount::where('fldbillno', $request->billno)->pluck('fldcount')->first();

            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;

            // $updatedata['fldcount'] = $countdata->fldcount + 1;
            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $request->billno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $request->billno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            // $countdata->update($updatedata);
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $request->billno)->first();
            //            dd($data['patbillingDetails']);
            $data['itemdata'] = PatBilling::where('fldbillno', $request->billno)->with('referUserdetail')->get();

            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;

            return view('billing::pdf.billing-invoice', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }
    /**
     * @param Request from post request
     * @return HTML factory response
     * expected output  is return a html bill invoice
     */
    public function generateInvoiceBill(Request $request)
    {
        // dd($request->all());
        try {
            $countdata = PatBillCount::where('fldbillno', $request->billno)->pluck('fldcount')->first();

            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;

            // $updatedata['fldcount'] = $countdata->fldcount + 1;
            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $request->billno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $request->billno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            // $countdata->update($updatedata);
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $request->billno)->first();
            //            dd($data['patbillingDetails']);
            $data['itemdata'] = PatBilling::where('fldbillno', $request->billno)->with('referUserdetail')->get();

            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;

            $invoicebill =  view('billing::ajax-views.ajax-billing-invoice', $data)->render();

            return response(
                [
                    'invoicebill' => $invoicebill,
                    'route' => route('billing.user.report.print'),
                    'billno' => $request->billno,

                ]
            );
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    public function billingInvoicePrint(Request $request)
    {
        try {
            $countdata = PatBillCount::where('fldbillno', $request->billno)->pluck('fldcount')->first();

            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;


            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $request->billno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $request->billno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }


            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $request->billno)->first();

            $data['itemdata'] = PatBilling::where('fldbillno', $request->billno)->with('referUserdetail')->get();

            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;


            $invoicebill =  view('billing::pdf.billing-invoice', $data)->render();

            return response(
                [
                    'printview' => $invoicebill,

                ]
            );
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }


    public function listUser(Request $request)
    {
        // dd($request->all());
        $fromsql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->frombill . '"';
        $fromid = DB::select($fromsql);

        $tosql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->tobill . '"';
        $toid = DB::select($tosql);
        $users = PatBillDetail::join('users', 'users.flduserid', '=', 'tblpatbilldetail.flduserid')->select('tblpatbilldetail.flduserid')
            ->whereBetween('fldid', [$fromid[0]->fldid, $toid[0]->fldid])
            ->where('users.status', 'active')
            ->where('fldcomp', Helpers::getCompName())
            ->distinct()
            ->orderBy('flduserid', 'desc')
            ->get();
        $html = '';
        if (isset($users) and count($users) > 0) {

            $html .= '<input type="hidden" name="frombill" id="frombill" value="' . $request->frombill . '">';
            $html .= '<input type="hidden" name="tobill" id="tobill" value="' . $request->tobill . '">';

            $html .= '<ul class="list-group" id="allergy-javascript-search">';
            $html .= '<li class="list-group-item p-1 pl-2 d-inline-block"><input type="checkbox" id="selectAll"> Select All</li>';
            foreach ($users as $user) {
                $html .= '<li class="list-group-item p-1 pl-2 d-inline-block"><td><input type="checkbox" class="user-list mr-2" name="users[]" value="' . $user->flduserid . '">' . ucwords(str_replace('.', ' ', $user->flduserid)) . '</td></li>';
            }
            $html .= '</ul>';
        }
        echo $html;
    }

    public function generateUserReport(Request $request)
    {
        // dd($request->all());
        try {
            $userslist = $request->users;
            $resultdata = array();
            $finalfrom = $request->eng_from_date;
            $finalto = $request->eng_to_date;
            if (isset($userslist) and count($userslist) > 0) {
                foreach ($userslist as $ul) {
                    $fromsql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->frombill . '" ';
                    $fromid = DB::select($fromsql);

                    $tosql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->tobill . '"';
                    $toid = DB::select($tosql);

                    $billtypesql = '';
                    /**
                     * in new billing form there is absent of type
                     * @param $request->type in comes from payment type ie. input name cash_credit field name
                     * we comment this section
                     */


                    $sql = 'Select SUM(flditemamt) as itemtot,SUM(fldtaxamt) as tax,SUM(flddiscountamt) as disc,SUM(flditemamt+fldtaxamt-flddiscountamt) as tot,SUM(fldreceivedamt) as recv , SUM(fldcurdeposit+fldprevdeposit ) as depo, fldcomp from tblpatbilldetail where fldid >="' . $fromid[0]->fldid . '" and fldid <="' . $toid[0]->fldid . '" ' . $billtypesql . ' and flduserid like "' . $ul . '" and fldsave = 1 and fldbillno is not null and cast(fldtime as date) BETWEEN "' . $finalfrom . ' 00:00:00" and "' . $finalto . ' 23:59:59"';

                    $results = DB::select($sql);
                    $newsql = 'Select SUM(fldreceivedamt) as depo from tblpatbilldetail where fldid >=' . $fromid[0]->fldid . ' and fldid <=' . $toid[0]->fldid . ' ' . $billtypesql . ' and flduserid like "' . $ul . '" and fldsave = 1 and (fldpayitemname like "%admission deposit%" or fldpayitemname like "op deposit" or fldpayitemname like "%re deposit%"
or fldpayitemname like "%blood bank%"or fldpayitemname like "%gate pass%" or fldpayitemname like "%post-up%")';



                    $refunddepositsql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail where fldbillno like '%dep%' and fldid >=" . $fromid[0]->fldid . " and fldid <=" . $toid[0]->fldid . " " . $billtypesql . " and flduserid like '" . $ul . "' and fldpayitemname like '%deposit refund%'";
                    $totalrefdep = DB::select(
                        $refunddepositsql
                    );

                    $newresult = DB::select($newsql);
                    $resultdata[$ul]['itemtot'] = $results[0]->itemtot;
                    $resultdata[$ul]['tax'] = $results[0]->tax;
                    $resultdata[$ul]['disc'] = $results[0]->disc;
                    $resultdata[$ul]['tot'] = $results[0]->tot;
                    $resultdata[$ul]['recv'] = $results[0]->recv;
                    $resultdata[$ul]['depo'] = $newresult[0]->depo;
                    $resultdata[$ul]['refdepo'] = $totalrefdep[0]->totalrefund;
                    $resultdata[$ul]['fldcomp'] = $results[0]->fldcomp;
                    $resultdata[$ul]['username'] = $ul;
                    // dd($resultdata);
                }
                $data['resultdata'] = $resultdata;
                $data['from_date'] = $request->from;
                $data['to_date'] = $request->todate;
                $data['frombill'] = $request->frombill;
                $data['tobill'] = $request->tobill;
                $data['userslist'] = $userslist;

                return view('billing::pdf.user-collection-report', $data);
            }
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    public function exportBillingReportExcel(Request $request)
    {
        try {
            $export = new BillingReportExport($request->all());
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'BillingReport.xlsx');
        } catch (Exception $e) {
            dd($e->getMessage());
            throw new \Exception(__('messages.error'));
        }
    }
    public function exportBillingReportDetailExcelDownload()
    {
    }

    public function invoicePdf(Request $request)
    {

        // dd($request->all());
        try {
            /**
             * here is not nepali date convert into eng date so that this throwing date range exception
             * @return Excpetion of Date range
             * solution convert nepali date into english date
             *
             */

            $fromsql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->frombill . '"';
            $fromid = DB::select($fromsql);

            $tosql = 'select fldid from tblpatbilldetail where fldbillno="' . $request->tobill . '"';
            $toid = DB::select($tosql);


            $users = PatBillDetail::select('flduserid')
                ->whereBetween('fldid', [$fromid[0]->fldid, $toid[0]->fldid])
                ->distinct()->get();
            $data['users'] = $users;
            $data['fromfldid'] = $fromid[0]->fldid;
            $data['tofldid'] = $toid[0]->fldid;
            $data['from_date'] = $request->fromdate;
            $data['to_date'] = $request->todate;
            $data['frombill'] = $request->frombill;
            $data['tobill'] = $request->tobill;
            return view('billing::pdf.billing-invoice-report', $data);
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    public function groupPdf(Request $request)
    {
        try {
            $data['users'] = $users;
            $data['from_date'] = $request->fromdate;
            $data['to_date'] = $request->todate;
            $data['frombill'] = $request->frombill;
            $data['tobill'] = $request->tobill;
        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }
    }

    public function exportBillingReportDetailExcel(Request $request)
    {

        try {
            $additionalParam = new Request([
                'comp_name' => Helpers::getCompName(),
                'hospital_selected_dept' => FacadesSession::get('selected_user_hospital_department')->fldcomp
            ]);
            $export = new BillingReportDetailExport($request->all(), $additionalParam);

            ob_end_clean();
            ob_start();
            return Excel::download($export, 'BillingReportDetail.xlsx');
        } catch (Exception $e) {
            return response($e);
        }
    }

    public function getSalesReportData(Request $request)
    {
        $date = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $fromdate = $request->eng_from_date ?: $date;
        $todate = $request->eng_to_date ?: $date;

        $salesreports = \App\PatBilling::select('fldencounterval', 'fldditemamt', 'flditemrate', 'flditemqty', 'fldtaxamt', 'flddiscamt', 'fldbillno', "fldtime")
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank,fldpannumber',
            ])->where([
                ["fldtime", ">=", "$fromdate 00:00:00"],
                ["fldtime", "<=", "$todate 23:59:59.999"],
                ["fldsave", 1],
            ])->get();

        $data = [];
        foreach ($salesreports as $report) {
            $key = "{$report->fldencounterval}-{$report->fldbillno}";
            if (!isset($data[$key])) {
                $patientInfo = $report->encounter && $report->encounter->patientInfo ? $report->encounter->patientInfo : NULL;
                $data[$key] = [
                    'fldtime' => Helpers::dateToNepali($report->fldtime),
                    'fldencounterval' => $report->fldencounterval,
                    'fldbillno' => $report->fldbillno,
                    'fldpannumber' => ($patientInfo && $patientInfo->fldpannumber) ? $patientInfo->fldpannumber : '',
                    'fldfullname' => ($patientInfo && $patientInfo->fldfullname) ? $patientInfo->fldfullname : '',
                    'totalsales' => 0.00,
                    'nontaxablesales' => 0.00,
                    'exportsales' => 0.00,
                    'discount' => 0.00,
                    'taxableamount' => 0.00,
                    'tax' => 0.00,
                ];
            }

            $data[$key]['totalsales'] += ($report->fldditemamt);
            $data[$key]['discount'] += ($report->flddiscamt);
            if ($report->fldtaxamt) {
                $data[$key]['tax'] += ($report->fldtaxamt);
                $data[$key]['taxableamount'] += ($report->flditemrate * $report->flditemqty);
            } else
                $data[$key]['nontaxablesales'] += ($report->flditemrate * $report->flditemqty);
        }

        return $data;
    }

    public function salesReport(Request $request)
    {
        $date = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        if ($request->ajax())
            return response()->json($this->getSalesReportData($request));

        return view('billing::sales-report', [
            'date' => $date,
        ]);
    }

    public function salesReportExport(Request $request)
    {
        $date = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $fromdate = $request->eng_from_date ?: $date;
        $todate = $request->eng_to_date ?: $date;

        $ts1 = strtotime($fromdate);
        $ts2 = strtotime($todate);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        return view('billing::sales-report-export', [
            'data' => $this->getSalesReportData($request),
            'yeardiff' => $year2 - $year1,
            'monthdiff' => $month2 - $month1,
        ]);
    }

    public function taxreportpdf(Request $request)
    {

        if ($request->fromdate) {

            $fromdate = Helpers::dateNepToEng($request->fromdate)->full_date;
            $todate = Helpers::dateNepToEng($request->todate)->full_date;
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $deptcomp = $request->deptcomp;
            $deptname = HospitalDepartment::where('fldcomp', $deptcomp)->pluck('name')->first();


            try {

                $html = "";

                $totalgross = 0;
                $totaldiscount = 0;
                $totalsubtotal = 0;
                $totaltaxable = 0;
                $totalnontaxable = 0;
                $totaltaxamt = 0;
                $totalnet_total = 0;


                $begin = new \DateTime($fromdate);
                $end   = new \DateTime($todate);



                for ($i = $begin; $i <= $end; $i->modify('+1 day')) {


                    $result = DB::select(DB::raw("SELECT
                cast(fldtime as date) as date1,
                sum(flditemrate * flditemqty) as gross,
                sum(flddiscamt) as discount,
                sum((fldditemamt)-fldtaxamt) as subtotal,
                sum(case
                    when fldtaxamt > 0 Then
                    (flditemrate * flditemqty) else 0
                    end) as taxable,
                        sum(case
                    when fldtaxamt = 0 Then
                    (flditemrate * flditemqty) else 0
                    end) as nontaxable,
                        sum(fldtaxamt) as taxamt,
                        sum(fldditemamt) as net_total
                    from
                        tblpatbilling t
                    where
                        (fldbillno like 'REG%'
                    or fldbillno  like 'CAS%'
                    or fldbillno like 'PHM%'
                    or fldbillno  like 'CRE%')
                    and fldsave = '1'

                    and fldcomp = '" . $deptcomp . "'
                    and cast(fldtime as date) ='" . $i->format("Y-m-d") . "' and cast(fldtime as date) = '" . $i->format("Y-m-d") . "'
                    group by cast(fldtime as date)"));


                    $resultret = DB::select(DB::raw("SELECT
                    cast(fldtime as date) as date1,
                    sum(flditemrate * flditemqty) as gross,
                    sum(flddiscamt) as discount,
                    sum((flditemrate * flditemqty)-(flddiscamt)) as subtotal,
                    sum(case
                        when fldtaxamt != 0 Then
                        flditemrate * flditemqty else 0
                        end) as taxable,
                            sum(case
                        when fldtaxamt = 0 Then
                        flditemrate * flditemqty else 0
                        end) as nontaxable,
                            sum(fldtaxamt) as taxamt,
                            sum((flditemrate * flditemqty)-(flddiscamt)+(fldtaxamt)) as net_total
                        from
                            tblpatbilling t
                        where
                            (fldbillno like 'RET%')
                        and fldsave = '1'
                        and fldcomp = '" . $deptcomp . "'
                        and cast(fldtime as date) ='" . $i->format("Y-m-d") . "' and cast(fldtime as date) = '" . $i->format("Y-m-d") . "'
                        group by cast(fldtime as date)"));


                    if (($result)) {

                        if ($result) {

                            $nepdate = Helpers::dateEngToNepdash($i->format("Y-m-d"))->full_date;

                            $resbill = \DB::table('tblpatbilling')
                                ->where('fldtime', ">=", $i->format("Y-m-d") . " 00:00:00")
                                ->where('fldtime', "<=", $i->format("Y-m-d") . " 23:59:59.999")
                                ->where(function ($query) {
                                    $query->where('fldbillno', 'like', 'CAS%')
                                        ->orWhere('fldbillno', 'like', 'PHM%');
                                })
                                ->where('fldcomp', $deptcomp)
                                ->selectRaw('min(fldbillno) as billfirst')
                                ->selectRaw('max(fldbillno) as billlast')
                                ->selectRaw('count(distinct(fldbillno)) as qty')
                                ->get();

                            $resbillreg = \DB::table('tblpatbilling')
                                ->where('fldtime', ">=", $i->format("Y-m-d") . " 00:00:00")
                                ->where('fldtime', "<=", $i->format("Y-m-d") . " 23:59:59.999")
                                ->where('fldbillno', 'like', 'REG%')
                                ->where('fldcomp', $deptcomp)
                                ->selectRaw('min(fldbillno) as billfirst')
                                ->selectRaw('max(fldbillno) as billlast')
                                ->selectRaw('count(distinct(fldbillno)) as qty')
                                ->get();

                            if ($resbillreg[0]->billfirst != Null) {

                                $html .= "<tr><td colspan=\"10\">" . $nepdate . " </td></tr>";
                                $html .= "<tr><td rowspan=\"2\">CASH</td>";
                                $html .= "<td>" . $resbill[0]->billfirst . "</td>";
                                $html .= "<td>" . $resbill[0]->billlast . " (" . $resbill[0]->qty . ")</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->gross) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->discount) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->subtotal) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->taxable) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->nontaxable) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->taxamt) . "</td>";
                                $html .= "<td rowspan=\"2\">" . Helpers::numberFormat($result[0]->net_total) . "</td></tr>";

                                $html .= "<tr><td>" . $resbillreg[0]->billfirst . "</td>";
                                $html .= "<td>" . $resbillreg[0]->billlast . " (" . $resbillreg[0]->qty . ")</td></tr>";
                            } else {
                                $html .= "<tr><td colspan=\"10\">" . $nepdate . " </td></tr>";
                                $html .= "<tr><td>CASH</td>";
                                $html .= "<td>" . $resbill[0]->billfirst . "</td>";
                                $html .= "<td>" . $resbill[0]->billlast . " (" . $resbill[0]->qty . ")</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->gross) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->discount) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->subtotal) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->taxable) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->nontaxable) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->taxamt) . "</td>";
                                $html .= "<td>" . Helpers::numberFormat($result[0]->net_total) . "</td></tr>";
                            }



                            $gross = $result[0]->gross;
                            $discount = $result[0]->discount;
                            $subtotal = $result[0]->subtotal;
                            $taxable = $result[0]->taxable;
                            $nontaxable = $result[0]->nontaxable;
                            $taxamt = $result[0]->taxamt;
                            $net_total = $result[0]->net_total;
                        }

                        if ($resultret) {

                            $resbillret = \DB::table('tblpatbilling')
                                ->where('fldtime', ">=", $i->format("Y-m-d") . " 00:00:00")
                                ->where('fldtime', "<=", $i->format("Y-m-d") . " 23:59:59.999")
                                ->where('fldbillno', 'like', 'RET%')
                                ->where('fldcomp', $deptcomp)
                                ->selectRaw('min(fldbillno) as billfirst')
                                ->selectRaw('max(fldbillno) as billlast')
                                ->selectRaw('count(distinct(fldbillno)) as qty')
                                ->get();

                            $html .= "<tr><td>REFUND</td>";
                            $html .= "<td>" . $resbillret[0]->billfirst . "</td>";
                            $html .= "<td>" . $resbillret[0]->billlast . " (" . $resbillret[0]->qty . ")</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->gross) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->discount) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->subtotal) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->taxable) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->nontaxable) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->taxamt) . "</td>";
                            $html .= "<td>" . Helpers::numberFormat($resultret[0]->net_total) . "</td></tr>";

                            $gross += $resultret[0]->gross;
                            $discount += $resultret[0]->discount;
                            $subtotal += $resultret[0]->subtotal;
                            $taxable += $resultret[0]->taxable;
                            $nontaxable += $resultret[0]->nontaxable;
                            $taxamt += $resultret[0]->taxamt;
                            $net_total += $resultret[0]->net_total;
                        }

                        $html .= "<tr><td colspan=\"3\">TOTAL</td>";
                        $html .= "<td>" . Helpers::numberFormat($gross) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($discount) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($subtotal) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($taxable) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($nontaxable) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($taxamt) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($net_total) . "</td></tr>";


                        $totalgross += $gross;
                        $totaldiscount += $discount;
                        $totalsubtotal += $subtotal;
                        $totaltaxable += $taxable;
                        $totalnontaxable += $nontaxable;
                        $totaltaxamt += $taxamt;
                        $totalnet_total += $net_total;
                    }
                }

                $html .= "<tr><td colspan=\"3\"><b>GRAND TOTAL</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totalgross) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totaldiscount) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totalsubtotal) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totaltaxable) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totalnontaxable) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totaltaxamt) . "</b></td>";
                $html .= "<td><b>" . Helpers::numberFormat($totalnet_total) . "</b></td></tr>";
            } catch (\Exception $e) {
                dd($e);
            }

            return view('billing::pdf.service-tax-report-pdf', array('html' => $html, 'fromdateeng' => $fromdate, 'todateeng' => $todate, 'fromdatenep' => $request->fromdate, 'todatenep' => $request->todate, 'userid' => $userid, 'department' => $deptname));
        }
    }

    public function taxexportpdf(Request $request)
    {
        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
        $deptcomp = $request->deptcomp;

        $export = new ServiceTaxreport($fromdateeng, $todateeng, $deptcomp);
        ob_end_clean();
        ob_start();

        return Excel::download($export, 'HealthServiceTaxReport.xlsx');
    }
}
