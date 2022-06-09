<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\BillingSet;
use App\PatBillDetail;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Year;
use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CreditClearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $data['discount'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('coreaccount::creditclearance.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function searchBill(Request $request)
    {
        try {
            $html = '';
            $result = DB::table('tblpatbilldetail as pbd');
            if ($request->type == 'paid') {
                $result->where('pbd.fldcurdeposit', '=', '0');
            } else if ($request->type == 'nonpaid') {
                $result->where('pbd.fldcurdeposit', '<', '0');
            } /*else if ($request->type == 'all') {
                $result->where('pbd.fldcurdeposit', '<=', '0');
            }*/

            if ($request->eng_from_date != '' and $request->eng_to_date != '') {
                $result->where('pbd.fldtime', '>=', $request->eng_from_date . ' 00:00:00');
                $result->where('pbd.fldtime', '<=', $request->eng_to_date . ' 23:59:59');
            }

            $result->join('tblencounter as e', 'e.fldencounterval', '=', 'pbd.fldencounterval');
            if ($request->billingmode != '') {
                $result->where('e.fldbillingmode', $request->billingmode);
            }

            if ($request->discount_scheme != '') {
                $result->where('pbd.flddiscountgroup', $request->discount_scheme);
            }

            if ($request->encounter_id != '') {
                $result->where('pbd.fldencounterval', $request->encounter_id);
            }

            if ($request->bill_number != '') {
                $result->where('pbd.fldbillno', $request->bill_number);
            }

            if ($request->bill_type == 'pharmacy') {
                $result->where('pbd.fldbillno', 'LIKE', '%PHM%');
            } else if ($request->bill_type == 'services') {
                $result->where('pbd.fldbillno', 'LIKE', '%CAS%');
            }

            $rowdata = $result->where('pbd.fldbilltype', 'Credit')
                ->where(function ($encounterQuery) {
                    $encounterQuery
                        ->orWhere('e.fldadmission', 'like', 'Discharged')
                        ->orWhere('e.fldadmission', 'like', 'Death')
                        ->orWhere('e.fldadmission', 'like', 'Absconder')
                        ->orWhere('e.fldadmission', 'like', 'LAMA')
                        ->orWhere('e.fldadmission', 'like', 'Refer');
                })
                ->groupBy('pbd.fldencounterval')->get();

            if (is_countable($rowdata) && count($rowdata)) {
                foreach ($rowdata as $data) {
                    $patbilldata = $rowdata;
                    $itemamount = 0;
                    $discountamount = 0;
                    $chargedamount = 0;
                    if (is_countable($patbilldata) && count($patbilldata)) {
                        // foreach ($patbilldata as $pbdata) {
                        //     $itemamount += $pbdata->where('fldencounterval',$data->fldencounterval)->flditemamt;
                        //     $discountamount += $pbdata->where('fldencounterval',$data->fldencounterval)->flddiscountamt;
                        //     $chargedamount += $pbdata->where('fldencounterval',$data->fldencounterval)->fldchargedamt;
                        // }
                        $itemamount = $patbilldata->where('fldencounterval', $data->fldencounterval)->sum('flditemamt');
                        $discountamount = $patbilldata->where('fldencounterval', $data->fldencounterval)->sum('flddiscountamt');
                        $chargedamount = $patbilldata->where('fldencounterval', $data->fldencounterval)->sum('fldchargedamt');

                    }
                    $enpatient = \App\Encounter::where('fldencounterval', $data->fldencounterval)->with('patientInfo')->first();
                    $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
                    if ($data->fldtempbilltransfer == '1') {
                        $receivedamt = $data->flditemamt;
                    } else {
                        $receivedamt = 0;
                    }

                    $deposit = \App\PatBillDetail::where('fldencounterval', $data->fldencounterval)->whereNotNull('fldcurdeposit')->orderBy('fldid', 'DESC')->get();

                    if (is_countable($deposit) && count($deposit)) {
                        $depamt = $deposit[0]->fldcurdeposit;
                    } else {
                        $depamt = 0;
                    }

                    $html .= '<tr>';
                    $html .= '<td><input type="checkbox" class="creditbill" value="' . $data->fldbillno . '"></td>';
                    $html .= '<td>' . $data->fldencounterval . '</td>';
                    $html .= '<td>' . $fullname . '</td>';
                    $html .= '<td>' . $data->fldbilltype . '</td>';
                    $html .= '<td>' . $itemamount . '</td>';
                    $html .= '<td>' . $discountamount . '</td>';
                    $html .= '<td>' . $chargedamount . '</td>';
                    $html .= '<td>' . $depamt . '</td>';
                    $html .= '<td><a href="javascript:void(0)" class="btn btn-primary creditbill" data-encounter="' . $data->fldencounterval . '">Credit Payment</a></td>';
                    $html .= '</tr>';
                }
            }
            echo $html;

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function getGroupedBillData($request, $encounter)
    {
        $result = DB::table('tblpatbilldetail as pbd');
        if ($request->type == 'paid') {
            $result->where('pbd.fldcurdeposit', '=', '0');
        } else if ($request->type == 'nonpaid') {
            $result->where('pbd.fldcurdeposit', '<', '0');
        } /*else if ($request->type == 'all') {
            $result->where('pbd.fldcurdeposit', '<=', '0');
        }*/
        if ($request->eng_from_date != '' and $request->eng_to_date != '') {
            $result->where('pbd.fldtime', '>=', $request->eng_from_date . ' 00:00:00');
            $result->where('pbd.fldtime', '<=', $request->eng_to_date . ' 23:59:59');
        }
        $result->join('tblencounter as e', 'e.fldencounterval', '=', 'pbd.fldencounterval');
        if ($request->billingmode != '') {
            $result->where('e.fldbillingmode', $request->billingmode);
        }

        if ($request->discount_scheme != '') {
            $result->where('pbd.flddiscountgroup', $request->discount_scheme);
        }

        if ($encounter != '') {
            $result->where('pbd.fldencounterval', $encounter);
        }
        if ($request->bill_number != '') {
            $result->where('pbd.fldbillno', $request->bill_number);
        }

        if ($request->bill_type == 'pharmacy') {
            $result->where('pbd.fldbillno', 'LIKE', '%PHM%');
        } else if ($request->bill_type == 'services') {
            $result->where('pbd.fldbillno', 'LIKE', '%CAS%');
        }

        $rowdata = $result->where('pbd.fldbilltype', 'Credit')
            ->where(function ($encounterQuery) {
                $encounterQuery
                    ->orWhere('e.fldadmission', 'like', 'Discharged')
                    ->orWhere('e.fldadmission', 'like', 'Death')
                    ->orWhere('e.fldadmission', 'like', 'Absconder')
                    ->orWhere('e.fldadmission', 'like', 'LAMA')
                    ->orWhere('e.fldadmission', 'like', 'Refer');
            })
            ->get();
        return $rowdata;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function listBillingItems(Request $request)
    {
        try {
            $patbilldata = \App\PatBillDetail::select('fldbillno')
                ->where('fldencounterval', $request->encounter_id)
                ->where('fldbilltype', 'Credit')
                ->pluck('fldbillno');
            $data['patbilldata'] = \App\PatBillDetail::where('fldencounterval', $request->encounter_id)
                ->where('fldbilltype', 'Credit')
                ->first();

            $data['billitems'] = \App\PatBilling::whereIn('fldbillno', $patbilldata)
                ->where('fldstatus', 'Cleared')
                ->get();

            $depositdata = \App\PatBillDetail::where('fldencounterval', $request->encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->where('fldcomp',Helpers::getCompName())->get();
            $data['previousDeposit'] = (isset($depositdata) and $depositdata->isNotEmpty()) ? $depositdata[0]->fldcurdeposit : 0;

            $data['encounter_id'] = $request->encounter_id;
            $html = view('coreaccount::dynamic-views.list-bill-item', $data)->render();
            return $html;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function saveCreditClerance(Request $request)
    {
        $patbilldata = \App\PatBillDetail::select('fldbillno')
            ->where('fldencounterval', $request->encounter_id)
            ->where('fldbilltype', 'Credit')
            ->pluck('fldbillno');

        return $billitems = \App\PatBilling::whereIn('fldbillno', $patbilldata)->where('fldsample', 'Waiting')->get();
        $previousDeposit = \App\PatBillDetail::where('fldencounterval', $request->encounter_id)
            ->where('fldbillno', 'like', '%DEP%')->sum('flditemamt');

        // total deposit - credit amount
        $totalPayable = \App\PatBillDetail::where('fldencounterval', $request->encounter_id)->where('fldbilltype', 'Credit')->where('fldbillno', 'not like', '%DEP%')->sum('flditemamt');

        $totalPayable = $previousDeposit - $totalPayable;
        try {
            DB::beginTransaction();

            $dateToday = Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                ->first();

            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
            $billNumberGeneratedString = "CAS-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');

            $depositdata = \App\PatBillDetail::where('fldencounterval', $request->encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->where('fldcomp',Helpers::getCompName())->get();
            $previousDeposit = (isset($depositdata) and $depositdata->isNotEmpty()) ? $depositdata[0]->fldcurdeposit : 0;

            $insertDataPatDetail = [
                'fldprevdeposit' => $request->received_amount + $previousDeposit, // receive + previous
                'fldencounterval' => $request->encounter_id,
                'fldbillno' => $billNumberGeneratedString,
                'flditemamt' => $billitems->sum('fldditemamt'), //actual price without discount and tax
                'fldtaxamt' => $billitems->sum('fldtaxamt'),
                'flddiscountamt' => $billitems->sum('flddiscamt'),
                'fldreceivedamt' => $request->received_amount,
                'fldbilltype' => "DP BIll",
                'fldpayitemname' => "Credit Clearance",
                'fldchargedamt' => 0,
                'fldcurdeposit' => 0,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => date("Y-m-d H:i:s"),
                'flddiscountgroup' => $billitems[0]->discount_mode ?? "",
                'fldbill' => 'INVOICE',
                'fldsave' => 1,
                'xyz' => 0,
                'fldcomp' => Helpers::getCompName(),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
        //    dd($billitems);
        MaternalisedService::insertMaternalisedFiscal($request->encounter_id,$billNumberGeneratedString,'cash');
            if ($billitems) {
                foreach ($billitems as $bill) {
                    $updateDataPatBilling = [
                        //                        'fldbillno' => $billNumberGeneratedString,
                        'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => date("Y-m-d H:i:s"),
                        'fldsave' => 1,
                        'xyz' => 0,
                    ];
                    $bill->update($updateDataPatBilling);
                }
            }

            PatBillDetail::create($insertDataPatDetail);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e->getMessage());
            dd($e);
        }
    }

}
