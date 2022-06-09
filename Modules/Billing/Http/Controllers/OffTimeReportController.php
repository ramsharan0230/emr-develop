<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\Department;
use App\Exports\OffTimeReportExport;
use App\HospitalDepartmentUsers;
use App\PatBillDetail;
use App\PatBilling;
use App\ServiceGroup;
use App\Services\UserService;
use App\Utils\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class OffTimeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
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
        return view('billing::offtimereport.index', $data);
    }

    public function filterOffitmeReport(Request $request)
    {
        
                // ->when()
        // SELECT
    //     fldtime,
    //     fldtime,
    //     fldbillno,
    //     fldencounterval,
    //     fldencounterval,
    //     flditemname,
    //     flditemqty,
    //     flditemrate,
    //     (flditemrate * flditemqty) AS subtot,
    //     fldtaxamt,
    //     flddiscamt,
    //     fldditemamt,
    //     flduserid,
    //     fldcomp
    // FROM
    //     tblpatbilling
    // WHERE
    //     fldcomp LIKE '%%' AND fldencounterval LIKE '%%' AND flduserid LIKE '%%' AND fldbillno LIKE '%%' AND fldbillno LIKE '%%' AND fldtime >= '2022-02-03 00:00:00' AND fldtime <= '2022-02-03 23:59:59';
    }

    public function filterOfftimeReport(Request $request)
    {
        $finalfrom = $request->eng_from_date;
        $finalto = $request->eng_to_date;
        $department = $request->department;
        $search_type = $request->search_type ;
        $item_type = $request->item_type ;
        $search_text = $request->search_type_text ;

        $result = PatBilling::
        // where('fldcomp', $department)
                when(!is_null($department),function($query) use ($department){
                    $query->where('fldcomp', $department);
                }, function($q){
                    $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                })
                ->when($item_type != '', function($query) use ($item_type) {
                    // $query->whereHas('patBill', function($q) use ($item_type) {
                    //     $q->where('flditemtype','LIKE', '%'.$item_type.'%');
                    // });
                    $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                })
                ->when($search_type != '', function($query) use ($search_type , $search_text) {
                     if ($search_type == 'enc' and $search_text != '') {
                        $query->where('fldencounterval', 'LIKE', $search_text );
                    } else if ($search_type == 'user' and $search_text != '') {
                        $query->where('flduserid', 'LIKE', $search_text);
                    } else if ($search_type == 'invoice' and $search_text != '') {
                        $query->where('fldbillno', 'LIKE', $search_text);
                    } else {
                        //nothing
                    }
                })
                ->select('fldtime',
                    'fldbillno',
                    'fldencounterval',
                    'flditemname',
                    'flditemqty',
                    'flditemrate',
                    DB::raw('(flditemrate * flditemqty) AS subtot'),
                    'fldtaxamt',
                    'flddiscamt',
                    'fldditemamt',
                    'flduserid',
                    'fldcomp',
                    'flditemtype'
                )
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    ->whereDate('fldtime', '>=', $finalfrom)
                    ->whereDate('fldtime', '<=', $finalto)
                    // ->whereTime('fldtime', '>=', $request->from_time )
                    // ->whereTime('fldtime', '<=', $request->to_time )
                    ;
                })
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    // ->whereDate('fldtime', '<=', $finalto)
                    ->whereTime('fldtime', '>=', $request->from_time )
                    ->orWhereTime('fldtime', '<=', $request->to_time )
                    ;
                })
                // ->whereBetween(DB::raw('Time(fldtime)'), [$request->from_time, $request->to_time])
                // ->where('fldtime', '>=', $finalfrom .' ' .$request->from_time )
                // ->where('fldtime', '<=', $finalto .' ' . $request->to_time ) 
                // ->orderBy('fldtime', 'desc')
                ->paginate(50);
                // ->get();
                ;

            $summary  = PatBilling::when(!is_null($department),function($query) use ($department){
                            $query->where('fldcomp', $department);
                        }, function($q){
                            $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                        })
                        ->when($item_type != '', function($query) use ($item_type) {
                            $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                        })
                        ->when($search_type != '', function($query) use ($search_type , $search_text) {
                            if ($search_type == 'enc' and $search_text != '') {
                                $query->where('fldencounterval', 'LIKE', $search_text);
                            } else if ($search_type == 'user' and $search_text != '') {
                                $query->where('flduserid', 'LIKE', $search_text);
                            } else if ($search_type == 'invoice' and $search_text != '') {
                                $query->where('fldbillno', 'LIKE', $search_text);
                            } else {
                                //nothing
                            }
                        })
                        ->select('fldtime',
                            'fldbillno',
                            'fldencounterval',
                            'flditemname',
                            'flditemqty',
                            'flditemrate',
                            DB::raw('(flditemrate * flditemqty) AS subtot'),
                            'fldtaxamt',
                            'flddiscamt',
                            'fldditemamt',
                            'flduserid',
                            'fldcomp',
                            'flditemtype'
                        )
                        // ->where('fldtime', '>=', $finalfrom .' ' .$request->from_time )
                        // ->where(function($query) use ($finalfrom, $request) {
                        //     $query->whereDate('fldtime', '>=', $finalfrom)
                        //     ->whereTime('fldtime', '>=', $request->from_time )
                        //     // ->whereTime('fldtime', '<=', $request->to_time )
                        //     ;
                        //     // $query->orWhere('upd $request->to_timeated_at', $lastUpdate);
                        // })
                        // ->where(function($query) use ($finalto, $request) {
                        //     $query->whereDate('fldtime', '<=', $finalto)
                        //     // ->whereFldtimeBetween('fldtime', [$request->from_time,  $request->to_time ] );
                        //     // ->whereTime('fldtime', '>=', $request->from_time )
                        //     ->whereTime('fldtime', '<=', $request->to_time )
                        //     ;
                        // })
                        // ->where('fldtime', '<=', $finalto . $request->to_time ) 
                        ->select( DB::raw( 'SUM(flditemrate * flditemqty) as itemamt' ),
                                DB::raw('SUM(fldtaxamt) as taxamt'),
                                DB::raw('SUM(flddiscamt) as dscamt'),
                                DB::raw('SUM(fldditemamt) as recvamt')
                        )
                        ->get();            
            $data['results'] = $result ;
   
            $data['summary'] = $summary ;
            $data['html'] = view('billing::offtimereport.ajax-views.ajax-offline-report', $data)->render();
            $data['summary'] = view('billing::offtimereport.ajax-views.ajax-report-summary', $data)->render();
            return $data;
                
            
    }

    public function filterOfftimeReportPDF(Request $request)
    {
        $finalfrom = $request->eng_from_date;
        $finalto = $request->eng_to_date;
        $department = $request->department;
        $search_type = $request->search_type ;
        $item_type = $request->item_type ;
        $search_text = $request->search_type_text ;

        $result = PatBilling::
                    when(!is_null($department),function($query) use ($department){
                        $query->where('fldcomp', $department);
                    }, function($q){
                        $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                    })
                ->when($item_type != '', function($query) use ($item_type) {
                 
                    $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                })
                ->when($search_type != '', function($query) use ($search_type , $search_text) {
                     if ($search_type == 'enc' and $search_text != '') {
                        $query->where('fldencounterval', 'LIKE', $search_text );
                    } else if ($search_type == 'user' and $search_text != '') {
                        $query->where('flduserid', 'LIKE', $search_text);
                    } else if ($search_type == 'invoice' and $search_text != '') {
                        $query->where('fldbillno', 'LIKE', $search_text);
                    } else {
                        //nothing
                    }
                })
                ->select('fldtime',
                    'fldbillno',
                    'fldencounterval',
                    'flditemname',
                    'flditemqty',
                    'flditemrate',
                    DB::raw('(flditemrate * flditemqty) AS subtot'),
                    'fldtaxamt',
                    'flddiscamt',
                    'fldditemamt',
                    'flduserid',
                    'fldcomp',
                    'flditemtype'
                )
                // ->where('fldtime', '>=', $finalfrom . ' 00:00:00')
                // ->where('fldtime', '<=', $finalto . ' 23:59:59') 
                // ->where(function($query) use ($finalfrom, $request) {
                //     // dd($request->all());
                //     $query->whereDate('fldtime', '>=', $finalfrom)
                //     ->whereTime('fldtime', '>=', $request->from_time )
                //     ->whereTime('fldtime', '<=', $request->to_time );
                // })
                // ->where(function($query) use ($finalto, $request) {
                //     $query->whereDate('fldtime', '<=', $finalto)
                //     ->whereTime('fldtime', '>=', $request->from_time )
                //     ->whereTime('fldtime', '<=', $request->to_time );
                // })
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    ->whereDate('fldtime', '>=', $finalfrom)
                    ->whereDate('fldtime', '<=', $finalto)
                    ;
                })
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    // ->whereDate('fldtime', '<=', $finalto)
                    ->whereTime('fldtime', '>=', $request->from_time )
                    ->orWhereTime('fldtime', '<=', $request->to_time )
                    ;
                })
                ->get();

            $summary  = PatBilling::
                        when(!is_null($department),function($query) use ($department){
                            $query->where('fldcomp', $department);
                        }, function($q){
                            $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                        })
            // where('fldcomp', $department)
                        ->when($item_type != '', function($query) use ($item_type) {
                            // $query->whereHas('patBill', function($q) use ($item_type) {
                            //     $q->where('flditemtype','LIKE', '%'.$item_type.'%');
                            // });
                            $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                        })
                        ->when($search_type != '', function($query) use ($search_type , $search_text) {
                            if ($search_type == 'enc' and $search_text != '') {
                                $query->where('fldencounterval', 'LIKE', $search_text);
                            } else if ($search_type == 'user' and $search_text != '') {
                                $query->where('flduserid', 'LIKE', $search_text);
                            } else if ($search_type == 'invoice' and $search_text != '') {
                                $query->where('fldbillno', 'LIKE', $search_text);
                            } else {
                                //nothing
                            }
                        })
                        // ->where('fldtime', '>=', $finalfrom . ' 00:00:00')
                        // ->where('fldtime', '<=', $finalto . ' 23:59:59') 
                        // ->where(function($query) use ($finalfrom, $request) {
                        //     $query->whereDate('fldtime', '>=', $finalfrom)
                        //     ->whereTime('fldtime', '>=', $request->from_time )
                        //     ->whereTime('fldtime', '<=', $request->to_time );
                        // })
                        // ->where(function($query) use ($finalto, $request) {
                        //     $query->whereDate('fldtime', '<=', $finalto)
                        //     ->whereTime('fldtime', '>=', $request->from_time )
                        //     ->whereTime('fldtime', '<=', $request->to_time );
                        // })
                        ->where(function($query) use ($finalfrom, $finalto, $request) {
                            $query
                            ->whereDate('fldtime', '>=', $finalfrom)
                            ->whereDate('fldtime', '<=', $finalto)
                            // ->whereTime('fldtime', '>=', $request->from_time )
                            // ->whereTime('fldtime', '<=', $request->to_time )
                            ;
                        })
                        ->where(function($query) use ($finalfrom, $finalto, $request) {
                            $query
                            // ->whereDate('fldtime', '<=', $finalto)
                            ->whereTime('fldtime', '>=', $request->from_time )
                            ->orWhereTime('fldtime', '<=', $request->to_time )
                            ;
                        })
                        ->select( DB::raw( 'SUM(flditemrate * flditemqty) as itemamt' ),
                                DB::raw('SUM(fldtaxamt) as taxamt'),
                                DB::raw('SUM(flddiscamt) as dscamt'),
                                DB::raw('SUM(fldditemamt) as recvamt')
                        )
                        ->get();            
            $data['results'] = $result ;
            $data['userid'] = \Auth::guard('admin_frontend')->user()->flduserid;
   
            $data['summary'] = $summary ;
            // dd($data);
           return view('billing::offtimereport.pdf.offtimereport', $data);
            $data['summary'] = view('billing::offtimereport.ajax-views.ajax-report-summary', $data)->render();
            return $data;

    }

    public function filterOfftimeReportExcel(Request $request)
    {


        try{
            $export = new OffTimeReportExport($request);
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'BillingReport.xlsx');
        }catch(Exception $e)
        {
             dd($e);
            throw new \Exception(__('messages.error'));

        }
    }

}
