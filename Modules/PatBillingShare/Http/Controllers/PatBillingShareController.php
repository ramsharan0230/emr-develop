<?php

namespace Modules\PatBillingShare\Http\Controllers;

use App\CogentUsers;
use App\Exports\DoctorWiseShareReportPatientExport;
use App\Exports\DoctorWiseShareReportWithoutReferalExport;
use App\Exports\DoctorWiseShareReportWithoutReferalPatientExport;
use App\Exports\PatBillingShareExport;
use App\Exports\ReferalDoctorWiseExport;
use App\Exports\ReferrableDoctorListExport;
use App\PatBilling;
use App\PatBillingShare;
use App\ServiceCost;
use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Utils\Options;


class PatBillingShareController extends Controller
{
    protected $page_limit = 25;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->emergencyShareUpdate();
        $data['billing_share_reports'] = $data['results'] = [];
        // get billing share report.
        if ($request->has('eng_from_date') && $request->has('eng_to_date')) {
            // $data['results'] = PatBillingSharesReport::when(isset($request->bill_no), function ($q) use ($request) {
            //     return $q->where('fldbillno', 'LIKE', '%' . $request->bill_no . '%');
            // })
            //     ->when(isset($request->eng_from_date), function ($q) use ($request) {
            //         return $q->where('created_at', '>=', $request->eng_from_date . ' 00:00:00');
            //     })
            //     ->when(!(isset($request->eng_from_date)), function ($q) use ($request) {
            //         return $q->where('created_at', '>=', date('Y-m-d') . ' 00:00:00');
            //     })
            //     ->when(isset($request->eng_to_date), function ($q) use ($request) {
            //         return $q->where('created_at', '<=', $request->eng_to_date . " 23:59:59");
            //     })
            //     ->when(!(isset($request->eng_to_date)), function ($q) use ($request) {
            //         return $q->where('created_at', '<=', date('Y-m-d') . ' 23:59:59');
            //     })
            //     ->when(isset($request->itemname) && $request->itemname != null, function ($q) use ($request) {
            //         return $q->where('flditemname', 'LIKE', '%' . $request->itemname . '%');
            //     })
            //     ->where('doctor_share', '>', 0)
            //     ->whereHas('user', function ($query) use ($request) {
            //         $query
            //             ->when($request->doctor_id != "" && $request->doctor_id != null, function ($q) use ($request) {
            //                 return $q->where('id', $request->doctor_id);
            //             })
            //             ->when($request->doctor_username != "" && $request->doctor_username != null, function ($q) use ($request) {
            //                 return $q->where('usr.username', $request->doctor_username);
            //             })
            //             ->when($request->doctor_name != "" && $request->doctor_name != null, function ($q) use ($request) {
            //                 return $q->where(DB::raw("CONCAT_WS(' ', usr.firstname, usr.middlename, usr.lastname)"), 'LIKE', '%' . $request->doctor_name . '%');
            //             });
            //     })
            //     ->groupBy('pat_billing_id')
            //     ->with(['user:firstname,middlename,lastname,username,flduserid,id', 'encounter.patientInfo'])

            //     ->paginate($this->page_limit);


            //     $user_with_organization = User::where('id', $user_id)
            //     ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            //     ->select('users.id','organizations.name')->first();

            $data['results'] = PatBillingShare::join('tblpatbilling', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
                ->when(isset($request->bill_no), function ($q) use ($request) {
                    return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $request->bill_no . '%');
                })
                ->when(isset($request->eng_from_date), function ($q) use ($request) {
                    return $q->where('pat_billing_shares.created_at', '>=', $request->eng_from_date . ' 00:00:00');
                })

                ->when(!(isset($request->eng_from_date)), function ($q) use ($request) {
                    return $q->where('pat_billing_shares.created_at', '>=', date('Y-m-d') . ' 00:00:00');
                })
                ->when(isset($request->eng_to_date), function ($q) use ($request) {
                    return $q->where('pat_billing_shares.created_at', '<=', $request->eng_to_date . " 23:59:59");
                })
                ->when(!(isset($request->eng_to_date)), function ($q) use ($request) {
                    return $q->where('pat_billing_shares.created_at', '<=', date('Y-m-d') . ' 23:59:59');
                })
                ->when(isset($request->itemname) && $request->itemname != null, function ($q) use ($request) {
                    return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $request->itemname . '%');
                })
                ->whereHas('user', function ($query) use ($request) {
                    $query
                        ->when($request->doctor_id != "" && $request->doctor_id != null, function ($q) use ($request) {
                            return $q->where('id', $request->doctor_id);
                        })
                        ->when($request->doctor_username != "" && $request->doctor_username != null, function ($q) use ($request) {
                            return $q->where('usr.username', $request->doctor_username);
                        })
                        ->when($request->doctor_name != "" && $request->doctor_name != null, function ($q) use ($request) {
                            return $q->where(DB::raw("CONCAT_WS(' ', usr.firstname, usr.middlename, usr.lastname)"), 'LIKE', '%' . $request->doctor_name . '%');
                        });
                })
                ->groupBy('pat_billing_shares.pat_billing_id')
                ->with(['user:firstname,middlename,lastname,username,flduserid,id'])

                ->paginate($this->page_limit);




            $data['billing_share_reports'] = $data['results'];
        }
        $data['itemnames'] = ServiceCost::select('flditemname')->get();

        $data['consultants'] = Helpers::getConsultantList();
        return view('patbillingshare::index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function exportData(Request $request)
    {

        $result = DB::table('tblpatbilling AS pb')
            ->where(function ($query) use ($request) {
                if (isset($request->bill_no)) {
                    $query->where('pb.fldbillno', 'LIKE', '%' . $request->bill_no . '%');
                }

                if (isset($request->eng_from_date)) {
                    $query->whereDate('pb.fldordtime', '>=', $request->eng_from_date . ' 00:00:00');
                } else {
                    $query->whereDate('pb.fldordtime', '>=', date('Y-m-d') . ' 00:00:00');
                }

                if (isset($request->eng_to_date)) {
                    $query->whereDate('pb.fldordtime', '<=', $request->eng_to_date . " 23:59:59");
                } else {
                    $query->whereDate('pb.fldordtime', '<=', date('Y-m-d') . ' 23:59:59');
                }

                if (isset($request->itemname) && $request->itemname != null) {
                    $query->where('pb.flditemname', 'LIKE', '%' . $request->itemname . '%');
                }
            })

            ->join('pat_billing_shares AS pbs', 'pb.fldid', '=', 'pbs.pat_billing_id')

            ->join('users as usr', function ($join) use ($request) {

                // if ($request->doctor_username != null) {
                $join->on('pbs.user_id', '=', 'usr.id')
                    // $join->on('usr.username', '=', 'pb.fldrefer')
                    ->when($request->doctor_id != "" && $request->doctor_id != null, function ($q) use ($request) {
                        return $q->where('usr.id', $request->doctor_id);
                    })
                    ->when($request->doctor_username != "" && $request->doctor_username != null, function ($q) use ($request) {
                        return $q->where('usr.username', $request->doctor_username);
                    })
                    ->when($request->doctor_name != "" && $request->doctor_name != null, function ($q) use ($request) {
                        return $q->where(DB::raw("CONCAT_WS(' ', usr.firstname, usr.middlename, usr.lastname)"), 'LIKE', '%' . $request->doctor_name . '%');
                    });
            })
            ->where('pbs.share', '>', 0)
            ->where('pbs.status', 1)
            ->where('pbs.is_returned', 0)
            ->select(DB::raw("usr.firstname, usr.middlename,
            usr.lastname, usr.id as user_id, pb.fldid,
            pb.fldencounterval, pb.fldbillno , pb.fldbillingmode,
            pb.flditemtype, pb.flditemname, pb.fldditemamt,
            pb.fldorduserid, pb.fldordtime, pb.fldstatus, pbs.id
                       AS pat_billing_share_id, pbs.type, pbs.user_id,
                       pbs.share, pbs.ot_group_sub_category_id,
                       pbs.is_returned,
                       pbs.total_amount as item_amount,
                       pbs.usersharepercent,
                       pbs.hospitalshare,
                       pbs.share,
                       pbs.tax_amt,
                       pbs.shareqty


                   "))

            ->whereRaw("pb.fldsave = 1")
            ->groupBy('pb.fldid')
            ->limit(100)->get();


        // $data['total'] = collect($result)->sum('amount_after_share_tax');
        $data['itemnames'] = ServiceCost::select('flditemname')->get();
        $data['billing_share_reports'] = $result;
        return view('patbillingshare::pdf', $data);
    }

    public function checkUsername(Request $request)
    {
        try {
            $doc_detail = CogentUsers::select('firstname', 'middlename', 'lastname', 'email', 'username', 'fldcategory')->where('id', $request->doctor_id)->first();
            if ($doc_detail) {
                return response()->json([
                    'data' => [
                        'status' => true,
                        'msg' => "Username available"
                    ]
                ]);
            } else {
                return response()->json([
                    'data' => [
                        'status' => false,
                        'msg' => "Invalid username"
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => false,
                    'msg' => "Invalid username"
                ]
            ]);
        }
    }

    public function doctorwiseShareReport(Request $request)
    {
        $data['bill_no'] = '';
        $data['eng_from_date'] = '';
        $data['eng_to_date'] = '';
        $data['flditemname'] = '';
        $data['from_date'] = $request->eng_from_date;
        $data['to_date'] = $request->eng_to_date;
        $data['doc_name'] = $request->doctor_id;
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;

        $data['doc_detail'] = $doc_detail = CogentUsers::select('firstname', 'middlename', 'lastname', 'email', 'username', 'fldcategory')->where('id', $request->doctor_id)->first();
        $data['eng_from_date'] = $request->eng_from_date . ' 00:00:00';
        $data['eng_to_date'] = $request->eng_to_date . " 23:59:59";
        $data['results'] = PatBillingShare::where('pat_billing_shares.user_id', $request->doctor_id)
            ->leftJoin('tblpatbilling', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
            ->when(isset($request->bill_no), function ($q) use ($request) {
                $data['bill_no'] = $request->bill_no;
                return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $request->bill_no . '%');
            })
            ->when(isset($request->eng_from_date), function ($q) use ($request) {
                return $q->whereDate('pat_billing_shares.created_at', '>=', $request->eng_from_date . ' 00:00:00')->whereDate('pat_billing_shares.created_at', '<=', $request->eng_to_date . " 23:59:59");
            })
            ->when(isset($request->itemname) && $request->itemname != null, function ($q) use ($request) {
                $data['flditemname'] = $request->itemname;
                return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $request->itemname . '%');
            })
            ->when($withoutReferral == true, function ($q) {
                return $q->where('pat_billing_shares.type', '!=', 'referable');
            })
            ->where('pat_billing_shares.share', '>', 0)
            ->where('pat_billing_shares.status', 1)
            //  ->where('pat_billing_shares.is_returned',0)
            ->get()
            ->groupBy(['type', 'flditemname', 'is_returned']);


        return view('patbillingshare::doctorwise-share-report-pdf', $data);
    }

    public function doctorwiseShareReportPatient(Request $request)
    {
        $data['bill_no'] = '';
        $data['eng_from_date'] = '';
        $data['eng_to_date'] = '';
        $data['flditemname'] = '';
        $data['from_date'] = $request->eng_from_date;
        $data['to_date'] = $request->eng_to_date;
        $data['doc_name'] = $request->doctor_id;
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;

        $data['doc_detail'] = $doc_detail = CogentUsers::select('firstname', 'middlename', 'lastname', 'email', 'username', 'fldcategory')->where('id', $request->doctor_id)->first();
        $data['eng_from_date'] = $request->eng_from_date . ' 00:00:00';
        $data['eng_to_date'] = $request->eng_to_date . " 23:59:59";
        $data['results'] = PatBillingShare::where('pat_billing_shares.user_id', $request->doctor_id)
            ->leftJoin('tblpatbilling', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
            ->when(isset($request->bill_no), function ($q) use ($request) {
                $data['bill_no'] = $request->bill_no;
                return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $request->bill_no . '%');
            })
            ->when(isset($request->eng_from_date), function ($q) use ($request) {
                return $q->whereDate('pat_billing_shares.created_at', '>=', $request->eng_from_date . ' 00:00:00')->whereDate('pat_billing_shares.created_at', '<=', $request->eng_to_date . " 23:59:59");
            })
            ->when(isset($request->itemname) && $request->itemname != null, function ($q) use ($request) {
                $data['flditemname'] = $request->itemname;
                return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $request->itemname . '%');
            })
            ->when($withoutReferral == true, function ($q) {
                return $q->where('pat_billing_shares.type', '!=', 'referable');
            })
            ->where('pat_billing_shares.share', '>', 0)
            ->where('pat_billing_shares.status', 1)
            //  ->where('pat_billing_shares.is_returned',0)
            ->get()
            ->groupBy(['type', 'flditemname', 'is_returned']);


        return view('patbillingshare::doctorwise-share-report-pdf-patient', $data);
    }


    public function newdoctorwiseShareReportsummary(Request $request)
    {
        $data['bill_no'] = '';
        $data['eng_from_date'] = '';
        $data['eng_to_date'] = '';
        $data['flditemname'] = '';
        $data['from_date'] = $request->eng_from_date;
        $data['to_date'] = $request->eng_to_date;
        $data['doc_name'] = $request->doctor_id;
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;

        $data['doc_detail'] = $doc_detail = CogentUsers::select('firstname', 'middlename', 'lastname', 'email', 'username', 'fldcategory')->where('id', $request->doctor_id)->first();
        $data['eng_from_date'] = $request->eng_from_date . ' 00:00:00';
        $data['eng_to_date'] = $request->eng_to_date . " 23:59:59";


        $data['results'] = PatBillingShare::select('pat_billing_shares.user_id')
            ->leftJoin('tblpatbilling', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
            ->leftJoin('users', 'users.id', '=', 'pat_billing_shares.user_id')

            ->when(isset($request->eng_from_date), function ($q) use ($request) {
                return $q->whereDate('tblpatbilling.fldordtime', '>=', $request->eng_from_date . ' 00:00:00')->whereDate('tblpatbilling.fldordtime', '<=', $request->eng_to_date . " 23:59:59");
            })

            ->when($withoutReferral == true, function ($q) {
                return $q->where('pat_billing_shares.type', '!=', 'referable');
            })
            ->where('pat_billing_shares.share', '>', 0)
            ->where('pat_billing_shares.status', 1)


            ->groupBy(['user_id'])
            ->orderBy('users.firstname','asc')->get();


        return view('patbillingshare::doctorwise-share-report-pdf-doctor', $data);
    }

    //Excel Export functions
    public function exportExcell(Request $request)
    {
        $from_date = $request->from_date ?? '';
        $to_date = $request->to_date ?? '';
        $bill_no = $request->bill_no ?? '';
        $eng_from_date = $request->eng_from_date ?? '';
        $eng_to_date = $request->eng_to_date ?? '';
        $flditemname = $request->itemname ?? '';
        $doc_id = $request->doctor_id ?? '';
        $doc_user_name = $request->doctor_username ?? '';
        $doc_name = $request->doctor_name ?? '';
        ob_end_clean();
        ob_start();
        return Excel::download(new PatBillingShareExport($from_date, $to_date, $bill_no, $eng_from_date, $eng_to_date, $flditemname, $doc_name, $doc_id, $doc_user_name), 'Pat-Bill-Share.xlsx');
    }

    public function generateReferalDoctorListExcell(Request $request)
    {
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';
        ob_end_clean();
        ob_start();
        return Excel::download(new ReferrableDoctorListExport($from_date, $to_date, $eng_from_date, $eng_to_date), 'Referable-Doctor-List.xlsx');
    }

    public function doctorWiseReferralExcelExport(Request $request)
    {

        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';
        ob_end_clean();
        ob_start();
        return Excel::download(new ReferalDoctorWiseExport($from_date, $to_date, $eng_from_date, $eng_to_date, $doctor_id, $bill_no, $itemname, $withoutReferral), 'Referable-Doctor-Wise-List.xlsx');
    }


    public function doctorWiseShareReportPatientExport(Request $request)
    {
        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';
        ob_end_clean();
        ob_start();
        return Excel::download(new DoctorWiseShareReportPatientExport($from_date, $to_date, $eng_from_date, $eng_to_date, $doctor_id, $bill_no, $itemname, $withoutReferral), 'Doctor-Wise-Share-Patient.xlsx');
    }


    public function doctorWiseShareWithoutReferalExcel(Request $request)
    {
        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';
        ob_end_clean();
        ob_start();
        return Excel::download(new DoctorWiseShareReportWithoutReferalExport($from_date, $to_date, $eng_from_date, $eng_to_date, $doctor_id, $bill_no, $itemname, $withoutReferral), 'Doctor-Wise-Share-Without-Referral.xlsx');
    }

    public function doctorWiseShareWithoutReferalPatientExcel(Request $request)
    {
        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';
        ob_end_clean();
        ob_start();
        return Excel::download(new DoctorWiseShareReportWithoutReferalPatientExport($from_date, $to_date, $eng_from_date, $eng_to_date, $doctor_id, $bill_no, $itemname, $withoutReferral), 'Doctor-Wise-Share-Without-Referral-Patient.xlsx');
    }

    public function doctorreportshare(Request $request)
    {
        $drshare = '';
        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';

        $extrasql = '';
        if (!empty($doctor_id))
            $extrasql .= " and pat_billing_shares.user_id = " . $doctor_id;


        $drsharesql = "SELECT
tblpatbilling.fldbillno, tblpatbilling.fldencounterval,pat_billing_shares.shareqty, pat_billing_shares.type, pat_billing_shares.`share`, pat_billing_shares.tax_amt, pat_billing_shares.user_id as druserid, tblpatbilling.fldtime, pat_billing_shares.returned_at
FROM
pat_billing_shares
join tblpatbilling on tblpatbilling.fldid = pat_billing_shares.pat_billing_id

WHERE

 pat_billing_shares.type = 'OPD Consultation' " . $extrasql . "
AND pat_billing_shares.created_at >= '" . $eng_from_date . " 00:00:00'
AND pat_billing_shares.created_at <= '" . $eng_to_date . " 23:59:59'
and pat_billing_shares.status = 1
         ";



        $drshare = DB::select(
            $drsharesql
        );

        //dd($drshare);
        $data['drshare'] = $drshare;
        return view('patbillingshare::doctorwise-dr-one', $data);
    }

    public function getReportDetail(Request $request)
    {
        $drshare = '';
        $type = $request->get('type');
        $data['bill_no'] = $bill_no = $request->bill_no ?? '';
        $data['flditemname'] = $itemname = $request->itemname ?? '';
        $data['doc_name'] = $doctor_id = $request->doctor_id ?? '';
        $data['withoutReferral'] = $withoutReferral = ($request->has('withoutReferral')) ? true : false;
        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['to_date'] = $to_date = $request->get('to_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') . " 00:00:00" : '';
        $data['eng_to_date'] = $eng_to_date = $request->get('eng_to_date') ? $request->get('eng_to_date') . " 23:59:59" : '';


        $extrasql = '';
        if (!empty($doctor_id))
            $extrasql .= " AND pbs.user_id = " . $doctor_id;


        $drsharesql = "SELECT
        CONCAT( pi.fldptnamefir,' ',pi.fldptnamelast ) as patientname,
        pb.flditemname,
        pb.flditemrate,
        pb.flditemqty,
        pb.flddiscamt,
        pb.fldditemamt,
        pbs.share,
        pbs.tax_amt,
        pbs.usersharepercent,
        pb.fldtime,
        pbs.created_at,
        pbs.is_returned,
        pbs.returned_at,
        pb.fldbillno,
        pbs.user_id as druserid
        FROM
        pat_billing_shares pbs
        JOIN tblpatbilling pb ON pb.fldid = pbs.pat_billing_id
        JOIN tblencounter en on en.fldencounterval = pb.fldencounterval
        join tblpatientinfo pi on pi.fldpatientval = en.fldpatientval
        where pbs.type like '%".$type."%' ".$extrasql." AND pbs.created_at >= '" . $eng_from_date . " 00:00:00'
        AND pbs.created_at <= '" . $eng_to_date . " 23:59:59'
        and pbs.status = 1
        order by pbs.created_at ASC
         ";


        $drshare = DB::select(
            $drsharesql
        );

        $data['drshare'] = $drshare;
        return view('patbillingshare::detailReportshare', $data);
    }

    public function cronreturnedate(){
        $patbillingshare = PatBillingShare::where('returned_at','<','2021-07-16')->where('is_returned',1)->where('status',1)->get();
        $retunfxing = array();
        $notretbill=[];
        if(!empty($patbillingshare)){
            foreach($patbillingshare as $k => $share){
                $retunfxing[$k]['pat_created_at'] = $share->created_at;
                $retunfxing[$k]['pat_returned_at'] = $share->returned_at;
                $retunfxing[$k]['pat_billing_id_pat'] = $share->pat_billing_id;

                $patbilling = PatBilling::where('fldid',$share->pat_billing_id)->first();

                $retunfxing[$k]['return_cashbill'] = $patbilling->fldbillno;
                $return = PatBilling::where('fldretbill',$patbilling->fldbillno)->first();
                if($return)   {
                    $retunfxing[$k]['return_pat_billing_id'] = $return->fldid;
                    $retunfxing[$k]['return_pat_billing_no'] = $return->fldbillno;

                    $retunfxing[$k]['return_time'] = $return->fldtime;
                }



            }

               // dd($retunfxing);
            if(!empty($retunfxing)){
                foreach($retunfxing as $key => $ret){
                  if(isset($ret['return_time'])){
                    $update = [
                        'returned_at' => $ret['return_time']
                    ];

                    //PatBillingShare::where('pat_billing_id', $ret['pat_billing_id_pat'])->update($update);
                  }else{
                      $notretbill[] = $ret['pat_billing_id_pat'];

                  }

                }
            }


        }

        print_r($notretbill);

    }

    public function emergencyShareUpdate(){

        if(Options::get('emergency_drshare_hospital') == 1){
            $emergencyshare = PatBillingShare::select('id','share')->where('emergencyShare',NULL)->get();
            if($emergencyshare){
                $shareupdate = array();
                foreach($emergencyshare as $share){
                    $shareamount = $share->share;
                    $emershare = ((1/100)*$shareamount);
                    $shareupdate = [
                        'emergencyShare' => $emershare ,
                        'share' => $shareamount - $emershare
                    ];

                    PatBillingShare::where('id',$share->id)->update($shareupdate);

                }
            }

        }


    }
}
