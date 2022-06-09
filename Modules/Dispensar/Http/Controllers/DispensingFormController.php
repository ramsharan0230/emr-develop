<?php

namespace Modules\Dispensar\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Department;
use App\Departmentbed;
use App\DepartmentRevenue;
use App\Discount;
use App\Encounter;
use App\Events\StockLive;
use App\PatBilling;
use App\PatientExam;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use App\AutoId;
use Auth;
use Session;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\MaternalisedService;
use App\Services\TpBillService;

class DispensingFormController extends Controller
{
    public function resetEncounter()
    {
        \Session::forget('dispensing_form_encounter_id');
        return redirect()->route('dispensingForm');
    }

    public function index(Request $request)
    {
        
        try{
            $data = [
                'allRoutes' => Helpers::getDispenserRoute(),
                'frequencies' => Helpers::getFrequencies(),

                'computers' => \App\Department::distinct('flddept')->pluck('flddept'),
                'today' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,

            ];
            $data['banks'] = \App\Banks::all();
            $data['billingModes'] = Helpers::getBillingModes();

            $data['surnames'] = Helpers::getSurnames();
            $data['countries'] = Helpers::getCountries();
            $data['discounts'] = Helpers::getDiscounts();

            $session_key = 'dispensing_form_encounter_id';
            $encounter_id_session = \Session::get($session_key);
            $data['total'] = $data['discount'] = $dataList['serviceData'] = 0;
            $data['discountMode'] = '';
            if ($request->has('patient_details') && $request->get('patient_details') != "") {
                $enpatient = Encounter::select('fldpatientval', 'fldencounterval','fldregdate')->where('fldpatientval', $request->get('patient_details'))->with('patientInfo')->first();


                if (!$enpatient) {
                    \Session::forget($session_key);
                    return redirect()->back()->with('error', 'Patient/Encounter not found.');
                }
                $regdate = $enpatient->fldregdate;
                $registerdate = strtotime($regdate);
                $withfollowup = strtotime("+". Options::get('followup_days')." day", $registerdate);
                $followup_check = Options::get('followup_check');
                if(is_array($followup_check) and in_array('Dispensing',$followup_check)){
                    if(date('Y-m-d') > date('Y-m-d', $withfollowup) && ($enpatient->fldadmission !='Discharged'  && $enpatient->fldadmission !='Recorded')  && substr($enpatient->fldencounterval, 0, 2) != 'IP'){
                        \Session::forget($session_key);
                        return redirect()->back()->with('error_message', "Followup Date exceed please generate new encounter");

                    }
                }
                if ($enpatient) {
                    session([$session_key => $enpatient->fldencounterval]);
                    $encounter_id_session = $enpatient->fldencounterval;
                }
            }

            if ($request->has('encounter_id') || $encounter_id_session) {

                if ($request->has('encounter_id') && $request->get('encounter_id') != "") {
                    $encounter_id = $request->get('encounter_id');
                } else {
                    $encounter_id = $encounter_id_session;
                }


                session([$session_key => $encounter_id]);

                /*create last encounter id*/
                Helpers::moduleEncounterQueue($session_key, $encounter_id);
                /*$encounterIds = Options::get('dispensing_form_last_encounter_id');
                $arrayEncounter = unserialize($encounterIds);*/
                /*create last encounter id*/

                $data['enpatient'] = $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();
                if (!$enpatient) {
                    \Session::forget($session_key);
                    return redirect()->back()->with('error', 'Patient/Encounter not found.');
                }
                $enpatientinfo = Encounter::where('fldencounterval', $encounter_id)->first();
                $regdate = $enpatientinfo->fldregdate;
                $registerdate = strtotime($regdate);
                $withfollowup = strtotime("+". Options::get('followup_days')." day", $registerdate);
                $followup_check = Options::get('followup_check');
                if(is_array($followup_check) and in_array('Dispensing',$followup_check)){
                    if(date('Y-m-d') > date('Y-m-d', $withfollowup) && ($enpatient->fldadmission !='Discharged'  && $enpatient->fldadmission !='Recorded')  && substr($enpatient->fldencounterval, 0, 2) != 'IP'){
                        \Session::forget($session_key);
                        return redirect()->back()->with('error_message', "Followup Date exceed please generate new encounter");

                    }
                }




                $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

                $patient_id = $enpatient->fldpatientval;
                $data['enable_freetext'] = Options::get('free_text');
                $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();

                $data['patient_id'] = $patient_id;
                //            $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();
                $end = Carbon::parse($patient->fldptbirday);
                $now = Carbon::now();

                $length = $end->diffInDays($now);
                if ($length < 1) {
                    $data['years'] = 'Hours';
                    $data['hours'] = $end->diffInHours($now);
                }

                if ($length > 0 && $length <= 30)
                    $data['years'] = 'Days';
                if ($length > 30 && $length <= 365)
                    $data['years'] = 'Months';
                if ($length > 365)
                    $data['years'] = 'Years';

                $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->orderBy('fldid', 'desc')->first();
                $data['body_height'] = $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

                if (isset($body_height)) {
                    if ($body_height->fldrepquali <= 100) {
                        $data['heightrate'] = 'cm';
                        $data['height'] = $body_height->fldrepquali;
                    } else {
                        $data['heightrate'] = 'm';
                        $data['height'] = $body_height->fldrepquali / 100;
                    }
                } else {
                    $data['heightrate'] = 'cm';
                    $data['height'] = '';
                }


                $data['bmi'] = '';

                if (isset($body_height) && isset($body_weight)) {
                    $hei = ($body_height->fldrepquali / 100); //changing in meter
                    $divide_bmi = ($hei * $hei);
                    if ($divide_bmi > 0) {

                        $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    }
                }
                $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

                if (isset($body_height) && isset($body_weight)) {
                    $hei = ($body_height->fldrepquali / 100); //changing in meter
                    $divide_bmi = ($hei * $hei);
                    if ($divide_bmi > 0) {

                        $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    }
                }

                /*
                    medlist: select fldid,fldroute,flddose,fldfreq,flddays,fldqtydisp,fldlabel,flditem,flduserid_order,flddiscper,fldtaxper,fldstarttime, fldstock,fldvatamt,fldvatper from tblpatdosing  where fldencounterval='1' and fldsave_order='0' and fldcurval='Continue' ORDER BY fldid DESC
                */
                //$request->get('type')
                if (isset($data['enpatient']) && (strtolower($data['enpatient']->fldbillingmode) == 'healthinsurance' || strtolower($data['enpatient']->fldbillingmode) == 'health insurance' || strtolower($data['enpatient']->fldbillingmode) == 'hi')){
                    $data['allMedicines'] = $this->_getAllMedicine('hibill',$encounter_id);
                    
                }else{
                    $data['allMedicines'] = $this->_getAllMedicine('ordered',$encounter_id);
                }
                
                // dd($data['allMedicines']);
                $data['addGroup'] = \App\ServiceGroup::groupBy('fldgroup')->get();
                $data['patDosingData'] = \App\PatDosing::where('fldlevel','Requested')->whereNotNull('discountmode')->where('fldencounterval',$encounter_id)->get();
                // dd($dataList['patDosingData']);
                $dataList['total'] = PatBilling::where('fldencounterval', $encounter_id)->where('fldstatus', 'Punched')->sum('fldditemamt');
                $dataList['discount'] = PatBilling::where('fldencounterval', $encounter_id)->where('fldstatus', 'Punched')->sum('flddiscamt');
                $data['discountMode'] = $dataList['discountMode'] = $dataList['serviceData'][0]->discount_mode ?? '';
                // dd($data['discountMode']);
                $discount = Discount::where('fldtype', $data['enpatient']->flddisctype)->first();
                // dd($discount);
                if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                    $data['meddiscount'] = $discount->fldpercent;
                    $data['surgicaldiscount'] = $discount->fldpercent;
                    $data['extradiscount'] = $discount->fldpercent;
                } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                    $data['meddiscount'] = $discount->fldmedicine;
                    $data['surgicaldiscount'] = $discount->fldsurgical;
                    $data['extradiscount'] = $discount->fldextra;
                } else {
                    $data['meddiscount'] = 0;
                    $data['surgicaldiscount'] = 0;
                    $data['extradiscount'] = 0;
                }
                $encounterIdsForTpBill = Encounter::where('fldpatientval', $patient_id)->pluck('fldencounterval');
                $data['totalTPAmountReceived'] = Helpers::getTpAmount($encounter_id);
                $data['totalDepositAmountReceived'] =  Helpers::totalDepositAmountReceived($encounter_id);

                $data['remaining_deposit'] = Helpers::numberFormat(($data['totalDepositAmountReceived']-$data['totalTPAmountReceived']),'insert');

            }
            if (isset($data['enpatient']) && $data['enpatient']) {
                $_GET['billingmode'] = $data['enpatient']->fldbillingmode;
            }
            // dd($data['patDosingData']);
            $data['countPatbillData'] = isset($data['patDosingData']) ? count(is_countable($data['patDosingData']) ? $data['patDosingData'] : []) : 0;
            // dd($data['countPatbillData']);
            $data['billingset'] = $billingset = BillingSet::get();
            $data['medicines'] = $this->getMedicineList($request);
            // dd($data);
            return view('dispensar::dispensingForm', $data);
        }catch(\Exception $e){
            dd($e);

        }

        return view('dispensar::dispensingForm', $data);


    }

    public function getPatientMedicine(Request $request)
    {
        return response()->json(
            $this->_getAllMedicine($request->get('type'),$request->fldencounterval)
        );
    }

    public function getTPBillList(Request $request){
        try{
            $tpbills = \App\PatBilling::where('fldencounterval',$request->fldencounterval)
                ->whereIn('flditemtype',['Surgicals','Medicines','Extra Items'])
                ->whereNull('fldbillno')
                ->where(function ($query) {
                    $query->whereNotNull('fldtempbillno')
                        ->orWhere('fldtempbillno','!=','');
                })
                ->where('fldcomp',\App\Utils\Helpers::getCompName())
                ->get();
            $html = '';
            if(isset($tpbills) and count($tpbills) > 0){
                $total = 0;
                foreach($tpbills as $k=>$bill){
                    $sn = $k+1;
                    $total += $bill->fldditemamt;
                    $html .='<tr data-fldid="' . $bill->fldid . '" data-fldencounterval="' . $bill->fldencounterval . '">';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$bill->flditemtype.'</td>';
                    $html .='<td>'.$bill->flditemname.'</td>';
                    $html .='<td>'.$bill->flditemqty.'</td>';
                    $html .='<td>'.Helpers::numberFormat($bill->flditemrate).'</td>';
                    $html .='<td>'.$bill->fldorduserid.'</td>';
                    $html .='<td>'.Helpers::numberFormat(($bill->flditemrate * $bill->flditemqty)).'</td>';
                    $html .='<td>'.Helpers::numberFormat($bill->flddiscamt).'</td>';
                    $html .='<td>'.Helpers::numberFormat($bill->fldtaxamt).'</td>';
                    $html .='<td>'.Helpers::numberFormat($bill->fldditemamt).'</td>';
                    $html .='<td>'.$bill->fldordtime.'</td>';
                    // $html .='<td><a href="javascript:void(0);" onclick="editTPItem(' . $bill->fldid . ')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-danger tpdelete"><i class="fa fa-times"></i></a></td>';
                    $html .='<td><a href="javascript:void(0);" onclick="editTPItem(' . $bill->fldid . ')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-danger tpdelete"><i class="fa fa-times"></i></a></td>';
                    $html .='</tr>';
                }
                $html .='<tr>';
                $html .='<td colspan="10" class="text-right"> <b>Total : </b>'.$total.'</td>';
                $html .='<td></td>';
                $html .='<td></td>';
                $html .='</tr>';
            }else{
                $html .='<td colspan="11">Data Not Available.</td>';
            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }

    private function _getAllMedicine($type = 'ordered',$encounterId)
    {
        // echo $type; exit;
        // echo $encounterId; exit;\
        // echo $type; exit;
        $encounter_id = $encounterId;
        $allMedicines = \App\PatDosing::select('fldid', 'fldroute', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'flditem', 'flduserid_order', 'flddiscper', 'fldtaxper', 'fldstarttime', 'fldstock', 'fldvatamt', 'fldvatper', 'flditemtype', 'fldstockno')
            ->with('medicineBySetting', 'medicineBySetting.medbrand', 'medicineBySetting.medbrand.label')
            ->where('fldencounterval', $encounter_id);
        if ($type == 'ordered') {
            $allMedicines->where([
                'fldsave_order' => '0',
                'fldcurval' => 'Continue',
            ]);
        }else if($type == 'hibill'){

            $allMedicines='';
            $allMedicines = \App\PatDosing::select('fldid', 'fldroute', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'flditem', 'flduserid_order', 'flddiscper', 'fldtaxper', 'fldstarttime', 'fldstock', 'fldvatamt', 'fldvatper', 'flditemtype', 'fldstockno')
            // ->with('medicineByStockRate', 'medicineBySetting.medbrand', 'medicineBySetting.medbrand.label')
            ->with('medicineByStockRate', 'medicineBySetting.medbrand', 'medicineBySetting.medbrand.label')
            ->where('fldencounterval', $encounter_id);

            $allMedicines->where([
                'fldsave_order' => '0',
                'fldcurval' => 'Continue',
            ]);


        } else {
            $allMedicines->where([
                'fldsave_order' => '1',
            ])
                ->where(function ($query) {
                    $query->orWhere('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                });
        }



        return $allMedicines->get();


    }

    public function getMedicineList(Request $request)
    {
        
        $compname = Helpers::getCompName();
        $is_expired = $request->get('is_expired');
        $billtype = $request->billtype;
        $expiry = date('Y-m-d H:i:s');
        if ($is_expired)
            $expiry = $expiry;

        $orderString = "tblentry.fldstatus ASC";
        $dispensing_medicine_stock = Options::get('dispensing_medicine_stock');
        if ($dispensing_medicine_stock == 'FIFO')
            $orderString = "tblentry.fldstatus DESC";
        elseif ($dispensing_medicine_stock == 'LIFO')
            $orderString = "tblentry.fldstatus ASC";
        elseif ($dispensing_medicine_stock == 'Expiry') {
            $days = Options::get('dispensing_expiry_limit');
            if ($days)
                $expiry = date('Y-m-d H:i:s', strtotime("+{$days} days", strtotime($expiry)));
            $orderString = "tblentry.fldexpiry ASC";
        }

        if (Options::get('medicine_by_category') == 'No') {
            $whereParams = [
                $compname,
                $expiry,
                $compname,
                $expiry,
                $compname,
                $expiry,
            ];


            if($billtype == 'hibill'){

                // dd('test');

                //select tblstockrate.flddrug as col,tblentry.fldexpiry as expiry,tblstockrate.fldrate as sellpr,tblentry.fldqty as qty,
                // tblentry.fldstatus as status,tblentry.fldbatch as batch,tblentry.fldsav,tbldrug.fldroute as route,
                //tblmedbrand.fldbrand,tblentry.fldsellpr as rate,fldmedcodeno as code
                //from tblstockrate right join tblentry on
                //tblstockrate.flddrug = tblentry.fldstockid
                //join tblmedbrand on tblentry.fldstockid=tblmedbrand.fldbrandid join
                //tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where tblentry.fldstatus=&1 and tblentry.fldcomp=&2 and
                //tblentry.fldqty>&3 and tblstockrate.flddrug in(select tblmedbrand.fldbrandid From tblmedbrand where
                //tblmedbrand.fldactive=&4 and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute=&5))
                //ORDER BY tblstockrate.flddrug ASC

                // \DB::enableQueryLog();

                $sql = "
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblmedbrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblstockrate.fldrate as fldsellpr, tblentry.fldcategory, tbldrug.fldroute, tblentry.fldbatch, tblmedbrand.fldnarcotic, tblmedbrand.fldpackvol, tblmedbrand.fldvolunit
                    FROM tblstockrate
                    inner JOIN tblentry ON tblstockrate.flddrug = tblentry.fldstockid
                    INNER JOIN tblmedbrand ON tblentry.fldstockid=tblmedbrand.fldbrandid
                    inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Medicines' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblmedbrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?

                UNION
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblsurgbrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblstockrate.fldrate as fldsellpr, tblentry.fldcategory, tblsurgicals.fldsurgcateg AS fldroute, tblentry.fldbatch, 'No' AS fldnarcotic, '1' AS fldpackvol, tblsurgbrand.fldvolunit
                    From tblstockrate
                    inner join tblentry on tblstockrate.flddrug = tblentry.fldstockid
                    INNER JOIN tblsurgbrand ON tblentry.fldstockid=tblsurgbrand.fldbrandid
                    INNER JOIN tblsurgicals ON tblsurgbrand.fldsurgid=tblsurgicals.fldsurgid
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Surgicals' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblsurgbrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?
                UNION
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblextrabrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblentry.fldsellpr, tblentry.fldcategory, 'extra' AS fldroute, tblentry.fldbatch, 'No' AS fldnarcotic, tblextrabrand.fldpackvol, tblextrabrand.fldvolunit
                    from tblstockrate
                    INNER JOIN tblentry ON tblstockrate.flddrug=tblentry.fldstockid
                    INNER JOIN tblextrabrand ON tblentry.fldstockid=tblextrabrand.fldbrandid
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Extra Items' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblextrabrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?
                ORDER BY fldstatus ASC
            ";

            }else{


            $sql = "
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblmedbrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblentry.fldsellpr, tblentry.fldcategory, tbldrug.fldroute, tblentry.fldbatch, tblmedbrand.fldnarcotic, tblmedbrand.fldpackvol, tblmedbrand.fldvolunit
                    FROM tblmedbrand
                    INNER JOIN tblentry ON tblentry.fldstockid=tblmedbrand.fldbrandid
                    INNER JOIN tbldrug ON tblmedbrand.flddrug=tbldrug.flddrug
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Medicines' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblmedbrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?
                UNION
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblsurgbrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblentry.fldsellpr, tblentry.fldcategory, tblsurgicals.fldsurgcateg AS fldroute, tblentry.fldbatch, 'No' AS fldnarcotic, '1' AS fldpackvol, tblsurgbrand.fldvolunit
                    FROM tblsurgbrand
                    INNER JOIN tblentry ON tblentry.fldstockid=tblsurgbrand.fldbrandid
                    INNER JOIN tblsurgicals ON tblsurgbrand.fldsurgid=tblsurgicals.fldsurgid
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Surgicals' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblsurgbrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?
                UNION
                    SELECT tblentry.fldstockno, tblentry.fldstatus, tblextrabrand.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, tblentry.fldsellpr, tblentry.fldcategory, 'extra' AS fldroute, tblentry.fldbatch, 'No' AS fldnarcotic, tblextrabrand.fldpackvol, tblextrabrand.fldvolunit
                    FROM tblextrabrand
                    INNER JOIN tblentry ON tblentry.fldstockid=tblextrabrand.fldbrandid
                    WHERE
                    tblentry.fldqty>0 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='Extra Items' AND
                    tblentry.fldcomp=? AND
                    tblentry.fldsav='1' AND
                    tblextrabrand.fldactive='Active' AND
                    tblentry.fldexpiry>=?
                ORDER BY fldstatus ASC
            ";
            }
        } else {
            /*
                Expiry checked: select tblentry.fldstatus, tblmedbrand.fldbrand as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr from (tblmedbrand inner join tblentry on tblentry.fldstockid=tblmedbrand.fldbrandid) inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where  tblentry.fldcomp='comp01' and tblentry.fldqty>0 and tblmedbrand.fldactive='Active' and tbldrug.fldroute='oral' ORDER BY tblmedbrand.fldbrand ASC
                -------------------------------------------------------------------------------------------------------------------
                oral,brand: select tblentry.fldstatus, tblmedbrand.fldbrand as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr from (tblmedbrand inner join tblentry on tblentry.fldstockid=tblmedbrand.fldbrandid) inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where  tblentry.fldcomp='comp01' and tblentry.fldqty>0 and tblmedbrand.fldactive='Active' and tbldrug.fldroute='oral' and tblentry.fldexpiry>= '2020-10-12 17:33:33.842' ORDER BY tblmedbrand.fldbrand ASC

                oral,generic: select tblentry.fldstockid as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr, tblentry.fldstatus from tblentry where tblentry.fldcomp='comp01' and tblentry.fldqty>0 and tblentry.fldstockid in(select tblmedbrand.fldbrandid From tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute='oral')) and tblentry.fldexpiry>='2020-10-12 17:40:42.398' ORDER BY tblentry.fldstockid ASC

                liquid,generic: select tblentry.fldstockid as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr, tblentry.fldstatus from tblentry where tblentry.fldcomp='comp01' and tblentry.fldqty>0 and tblentry.fldstockid in(select tblmedbrand.fldbrandid From tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute='liquid')) and tblentry.fldexpiry>='2020-10-12 17:41:42.223' ORDER BY tblentry.fldstockid ASC

                liquid,brand: select tblentry.fldstatus, tblmedbrand.fldbrand as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr from (tblmedbrand inner join tblentry on tblentry.fldstockid=tblmedbrand.fldbrandid) inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where  tblentry.fldcomp='comp01' and tblentry.fldqty>0 and tblmedbrand.fldactive='Active' and tbldrug.fldroute='liquid' and tblentry.fldexpiry>= '2020-10-12 17:42:34.155' ORDER BY tblmedbrand.fldbrand ASC
            */
            $orderBy = $request->get('orderBy', 'generic');
            // echo $orderBy; exit;
            $medcategory = $request->get('medcategory', 'Medicines');
            $billingmode = (isset($_GET['billingmode']) && $_GET['billingmode']) ? $_GET['billingmode'] : 'General';

            $table = "tblmedbrand";
            $fldnarcotic = "tblmedbrand.fldnarcotic";
            $drugJoin = "INNER JOIN tbldrug ON tblmedbrand.flddrug=tbldrug.flddrug";
            $routeCol = "tbldrug.fldroute";
            $fldpackvol = "$table.fldpackvol";
            if ($medcategory == 'Surgicals') {
                $table = "tblsurgbrand";
                $fldnarcotic = "'No' AS fldnarcotic";
                $drugJoin = "INNER JOIN tblsurgicals ON $table.fldsurgid=tblsurgicals.fldsurgid";
                $routeCol = "tblsurgicals.fldsurgcateg AS fldroute";
                $fldpackvol = "'1' AS fldpackvol";
            } elseif ($medcategory == 'Extra Items') {
                $table = "tblextrabrand";
                $fldnarcotic = "'No' AS fldnarcotic";
                $drugJoin = "";
                $routeCol = "'extra' AS fldroute";
                $fldpackvol = "$table.fldpackvol";
            }

            $whereParams = [
                0,
                $medcategory,
                $compname,
                '1',
                'Active',
                $expiry,
            ];

            $additionalJoin = "";
            $ratecol = "tblentry.fldsellpr";


            $sql = "";
            if ($orderBy == 'brand') {
                $sql = "
                    SELECT tblentry.fldstockno, tblentry.fldstatus, $table.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, $ratecol, tblentry.fldcategory, $routeCol, tblentry.fldbatch, $fldnarcotic, $fldpackvol, $table.fldvolunit
                    FROM $table
                    INNER JOIN tblentry ON tblentry.fldstockid=$table.fldbrandid
                    $additionalJoin
                    $drugJoin
                    WHERE
                        tblentry.fldqty>? AND
                        tblentry.fldstatus <> 0 AND
                        tblentry.fldcategory=? AND
                        tblentry.fldcomp=? AND
                        tblentry.fldsav=? AND
                        $table.fldactive=? AND
                        tblentry.fldexpiry>= ?
                    ORDER BY $orderString";
            } else {
                $sql = "
                    SELECT tblentry.fldstockno, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, $ratecol, tblentry.fldstatus, tblentry.fldcategory, $routeCol, tblentry.fldbatch, $fldnarcotic, $fldpackvol, $table.fldvolunit
                    FROM tblentry
                    INNER JOIN $table ON tblentry.fldstockid=$table.fldbrandid
                    $additionalJoin
                    $drugJoin
                    WHERE
                        tblentry.fldqty>? AND
                        tblentry.fldstatus <> 0 AND
                        tblentry.fldcategory=? AND
                        tblentry.fldcomp=? AND
                        tblentry.fldsav=? AND
                        tblentry.fldstockid IN (
                            SELECT $table.fldbrandid
                            FROM $table
                            WHERE
                                $table.fldactive=?
                            ) AND
                        tblentry.fldexpiry>=?
                    ORDER BY $orderString";
            }
        }

        $data = \DB::select($sql, $whereParams);
        $data = Helpers::appendExpiryStatus($data);



        if ($request->ajax())
            return response()->json($data);

        return $data;
    }

    public function saveMedicine(Request $request)
    {
        // dd($request->all());
        /*
            INSERT INTO `tblpatdosing` ( `fldencounterval`, `flditemtype`, `fldroute`, `flditem`, `flddose`, `fldfreq`, `flddays`, `fldqtydisp`, `fldqtyret`, `fldprescriber`, `fldregno`, `fldlevel`, `flddispmode`, `fldorder`, `fldcurval`, `fldstarttime`, `fldendtime`, `fldtaxper`, `flddiscper`, `flduserid_order`, `fldtime_order`, `fldcomp_order`, `fldsave_order`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldlabel`, `fldstatus`, `flduptime`, `xyz`, `fldstock`, `fldvatamt`, `fldvatper` ) VALUES ( '1', 'Medicines', 'liquid', 'Albendazole- 40 mg/mL(WORMGO)', 5, 'PRN', 10, 1, 0, NULL, NULL, 'Requested', 'IPD', 'UseOwn', 'Continue', '2020-10-13 18:00:13.741', NULL, 0, 0, 'admin', '2020-10-13 18:00:13.745', 'comp07', '0', NULL, NULL, NULL, '1', '0', 'Admitted', NULL, '0', 1, 0, 0 )
        */
        try {
            $encounter_id = $request->get('fldencounterval');
            $route = $request->get('route');
            $medicine = $request->get('medicine');
            $doseunit = $request->get('doseunit');
            $frequency = $request->get('frequency');
            $duration = $request->get('duration', '1');
            $quantity = $request->get('quantity');
            $consultant = $request->get('consultant');
            $fldopip = $request->get('department');
            $mode = $request->get('mode');
            $flditemtype = $request->get('flditemtype');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();
            $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();
            $discountper = 0;
            if($request->discountmode !=''){
                $discounttype = $request->discountmode;
            }else{
                $discounttype = $enpatient->flddisctype;
            }
            $discount = Discount::where('fldtype', $discounttype)->first();
            if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                $meddiscount = $discount->fldpercent;
                $surgicaldiscount = $discount->fldpercent;
                $extradiscount = $discount->fldpercent;
            } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                $meddiscount = $discount->fldmedicine;
                $surgicaldiscount = $discount->fldsurgical;
                $extradiscount = $discount->fldextra;
            } else {
                $meddiscount = 0;
                $surgicaldiscount = 0;
                $extradiscount = 0;
            }

            $discountper = 0;
            $taxper = 0;
            $taxcodeObject = NULL;
            if ($flditemtype == 'Medicines') {
                $taxcodeObject = \App\MedicineBrand::select('fldtaxcode')->where('fldbrandid', $medicine)->where('fldtaxable','LIKE','yes')->first();
                $discountper = $meddiscount;
            } else if ($flditemtype == 'Surgicals') {
                $taxcodeObject = \App\SurgBrand::select('fldtaxcode')->where('fldbrandid', $medicine)->where('fldtaxable','LIKE','yes')->first();
                $discountper = $surgicaldiscount;
            } else if ($flditemtype == 'Extra Items') {
                $taxcodeObject = \App\ExtraBrand::select('fldtaxable')->where('fldbrandid', $medicine)->where('fldtaxable','LIKE','yes')->first();
                $taxper = ($taxcodeObject && strtoupper($taxcodeObject->fldtaxable) == 'YES') ? '13' : '0';
                $discountper = $extradiscount;
            }
            if ($flditemtype !== 'Extra Items' && $taxcodeObject && $taxcodeObject->fldtaxcode) {
                $taxcode = \App\TaxGroup::where('fldgroup', $taxcodeObject->fldtaxcode)->select('fldtaxper')->first();
                $taxper = ($taxcode && $taxcode->fldtaxper) ? $taxcode->fldtaxper : '0';
            }

            if ($consultant != '') {
                $nmcnumber = CogentUsers::select('nmc')->where('username', $consultant)->first();
                if(isset($nmcnumber) and !empty($nmcnumber)){
                    $regnumber = $nmcnumber->nmc;
                }else{
                    $regnumber = $request->newconsultnmc;
                }

            } else {
                $regnumber = NULL;
            }
            // echo $consultant.'-'.$regnumber; exit;

            $fldid = \App\PatDosing::insertGetId([
                'fldencounterval' => $encounter_id,
                'flditemtype' => $flditemtype,
                'fldroute' => $route,
                'flditem' => $medicine,
                'flddose' => $doseunit,
                'fldfreq' => $frequency,
                'flddays' => $duration,
                'fldqtydisp' => $quantity,
                'fldqtyret' => 0,
                'fldprescriber' => NULL,
                'fldregno' => $regnumber,
                'fldlevel' => 'Requested',
                'flddispmode' => $mode,
                'fldorder' => 'UseOwn',
                'fldcurval' => 'Continue',
                'fldstarttime' => $time,
                'fldendtime' => NULL,
                'fldtaxper' => Helpers::numberFormat($taxper,'insert'),
                'flddiscper' => Helpers::numberFormat($discountper,'insert'),
                'flduserid_order' => $userid,
                'fldtime_order' => $time,
                'fldcomp_order' => $computer,
                'fldconsultant' => $consultant,
                'fldsave_order' => '0',
                'flduserid' => NULL,
                'fldtime' => NULL,
                'fldcomp' => NULL,
                'fldsave' => '1',
                'fldlabel' => '0',
                'fldstatus' => $enpatient->fldadmission,
                // 'fldstatus' => 'Admitted',
                'flduptime' => NULL,
                'fldopip' => $fldopip,
                'xyz' => '0',
                'fldstock' => 1,
                'fldvatamt' => Helpers::numberFormat(0,'insert'),
                'fldvatper' => Helpers::numberFormat(0,'insert'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldstockno' => $request->fldstockno,
                'discountmode' => $request->discountmode
            ]);


            $addedmedicine = \App\PatDosing::where('fldencounterval', $encounter_id)->where('fldlevel','Requested')->where('fldorder','UseOwn')->with('medicineBySetting')->get();
            // dd($addedmedicine);
            if(isset($addedmedicine) and count($addedmedicine) > 0){
                $subtotal = 0;
                $totaldiscount = 0;
                $totaltax = 0;
                $billingmode = \DB::table('tblencounter')->selectRaw('fldbillingmode')->where('fldencounterval',$encounter_id)->get();
                foreach($addedmedicine as $med){

                    if(strtolower($billingmode[0]->fldbillingmode) == 'health insurance' or strtolower($billingmode[0]->fldbillingmode) == 'healthinsurance' or strtolower($billingmode[0]->fldbillingmode) == 'hi'){

                        $fldsellpr = \DB::table('tblstockrate')->where('flddrug',$medicine)->pluck('fldrate');


                        $subtotal += isset($fldsellpr) ? $fldsellpr[0]*$med->fldqtydisp : 0;

                        // dd($subtotal);
                        $totaldiscount += isset($med->flddiscper) ? $med->flddiscper : 0;
                        $totaltax +=$med->fldtaxamt;

                    }else{

                    $subtotal += isset($med->medicineBySetting->fldsellpr) ? $med->medicineBySetting->fldsellpr*$med->fldqtydisp : 0;
                    $totaldiscount += isset($med->flddiscper) ? $med->flddiscper : 0;
                    $totaltax +=$med->fldtaxamt;
                    }
                }
            }


            if(strtolower($billingmode[0]->fldbillingmode) == 'health insurance' or strtolower($billingmode[0]->fldbillingmode) == 'healthinsurance' or strtolower($billingmode[0]->fldbillingmode) == 'hi'){
                $data = \App\PatDosing::where('fldid', $fldid)->with('medicineByStockRate')->first();
            }else{
                $data = \App\PatDosing::where('fldid', $fldid)->with('medicineBySetting')->first();
            }

            return response()->json([
                'status' => TRUE,
                'message' => 'Data saved.',
                // 'data' => \App\PatDosing::where('fldid', $fldid)->with('medicineBySetting')->first(),
                'data' => $data,
                'subtotal' =>  Helpers::numberFormat($subtotal,'insert'),
                'totaldiscount'=> Helpers::numberFormat($totaldiscount,'insert'),
                'totaltax' =>  Helpers::numberFormat($totaltax,'insert'),
                'nettotal' =>  Helpers::numberFormat(($subtotal+$totaltax-$totaldiscount),'insert'),
                'stocknumber' => $request->fldstockno,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Filed to process data.',
            ]);
        }

        return response()->json([
            'status' => FALSE,
            'message' => 'Invalid medicine selected.',
        ]);
    }

    public function saveMedicines(Request $request)
    {
        // dd($request->all());
        /*
            INSERT INTO `tblpatdosing` ( `fldencounterval`, `flditemtype`, `fldroute`, `flditem`, `flddose`, `fldfreq`, `flddays`, `fldqtydisp`, `fldqtyret`, `fldprescriber`, `fldregno`, `fldlevel`, `flddispmode`, `fldorder`, `fldcurval`, `fldstarttime`, `fldendtime`, `fldtaxper`, `flddiscper`, `flduserid_order`, `fldtime_order`, `fldcomp_order`, `fldsave_order`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldlabel`, `fldstatus`, `flduptime`, `xyz`, `fldstock`, `fldvatamt`, `fldvatper` ) VALUES ( '1', 'Medicines', 'liquid', 'Albendazole- 40 mg/mL(WORMGO)', 5, 'PRN', 10, 1, 0, NULL, NULL, 'Requested', 'IPD', 'UseOwn', 'Continue', '2020-10-13 18:00:13.741', NULL, 0, 0, 'admin', '2020-10-13 18:00:13.745', 'comp07', '0', NULL, NULL, NULL, '1', '0', 'Admitted', NULL, '0', 1, 0, 0 )
        */
        try {
            $medicines = $request->ids;
            if (isset($medicines) and count($medicines) > 0) {
                $fldids = array();
                $html = '';
                $outofstockitem = array();
                foreach ($medicines as $med) {
                    $meditem = \App\PatDosing::where('fldid', $med)->with('medicineBySetting')->first();
                    // dd($meditem);
                    $encounter_id = \Session::get('dispensing_form_encounter_id');
                    $route = (isset($meditem) and $meditem->fldroute != '') ? $meditem->fldroute : '';
                    $medicine = (isset($meditem) and $meditem->flditem != '') ? $meditem->flditem : '';
                    $doseunit = (isset($meditem) and $meditem->flddose != '') ? $meditem->flddose : '';
                    $frequency = (isset($meditem) and $meditem->fldfreq != '') ? $meditem->fldfreq : '';
                    $duration = (isset($meditem) and $meditem->flddays != '') ? $meditem->flddays : '';
                    $quantity = (isset($meditem) and $meditem->fldqtydisp != '') ? $meditem->fldqtydisp : '';
                    $consultant = (isset($meditem) and $meditem->fldconsultant != '') ? $meditem->fldconsultant : '';
                    $fldopip = (isset($meditem) and $meditem->fldopip != '') ? $meditem->fldopip : '';
                    $mode = (isset($meditem) and $meditem->flddispmode != '') ? $meditem->flddispmode : '';
                    $flditemtype = (isset($meditem) and $meditem->flditemtype != '') ? $meditem->flditemtype : '';
                    $time = date('Y-m-d H:i:s');
                    $userid = \Auth::guard('admin_frontend')->user()->flduserid;
                    $computer = Helpers::getCompName();
                    if (!is_null($meditem->medicineBySetting) and $meditem->fldqtydisp > $meditem->medicineBySetting->fldqty) {
                        $outofstockitem[] = $meditem->flditem;
                    } else {
                        $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();
                        $discountper = 0;
                        $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                        if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                            $meddiscount = $discount->fldpercent;
                            $surgicaldiscount = $discount->fldpercent;
                            $extradiscount = $discount->fldpercent;
                        } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                            $meddiscount = $discount->fldmedicine;
                            $surgicaldiscount = $discount->fldsurgical;
                            $extradiscount = $discount->fldextra;
                        } else {
                            $meddiscount = 0;
                            $surgicaldiscount = 0;
                            $extradiscount = 0;
                        }
                        if ($flditemtype == 'Medicines') {
                            $discountper = $meddiscount;
                        } else if ($flditemtype == 'Surgicals') {
                            $discountper = $surgicaldiscount;
                        } else if ($flditemtype == 'Extra Items') {
                            $discountper = $extradiscount;
                        } else {
                            $discountper = 0;
                        }
                        $fldid = \App\PatDosing::insertGetId([
                            'fldencounterval' => $encounter_id,
                            'flditemtype' => $flditemtype,
                            'fldroute' => $route,
                            'flditem' => $medicine,
                            'flddose' => $doseunit,
                            'fldfreq' => $frequency,
                            'flddays' => $duration,
                            'fldqtydisp' => $quantity,
                            'fldqtyret' => 0,
                            'fldprescriber' => NULL,
                            'fldregno' => NULL,
                            'fldlevel' => 'Requested',
                            'flddispmode' => $mode,
                            'fldorder' => 'UseOwn',
                            'fldcurval' => 'Continue',
                            'fldstarttime' => $time,
                            'fldendtime' => NULL,
                            'fldtaxper' =>  Helpers::numberFormat(0,'insert'),
                            'flddiscper' =>  Helpers::numberFormat($discountper,'insert'),
                            'flduserid_order' => $userid,
                            'fldtime_order' => $time,
                            'fldcomp_order' => $computer,
                            'fldconsultant' => $consultant,
                            'fldsave_order' => '0',
                            'flduserid' => NULL,
                            'fldtime' => NULL,
                            'fldcomp' => NULL,
                            'fldsave' => '1',
                            'fldlabel' => '0',
                            'fldstatus' => 'Admitted',
                            'flduptime' => NULL,
                            'fldopip' => $fldopip,
                            'xyz' => '0',
                            'fldstock' => 1,
                            'fldvatamt' =>  Helpers::numberFormat(0,'insert'),
                            'fldvatper' =>  Helpers::numberFormat(0,'insert'),
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'fldstockno' => $request->fldstockno
                        ]);
                        $fldids[] = $fldid;
                    }


                }

                $patdosingdata = array();
                $finaltotal = array();
                $finalsubtotal = array();
                $sn = $request->length + 1;
                if (isset($fldids) and !empty($fldids)) {
                    foreach ($fldids as $k => $ids) {
                        $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();
                        $discountper = 0;
                        $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                        if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                            $meddiscount = $discount->fldpercent;
                            $surgicaldiscount = $discount->fldpercent;
                            $extradiscount = $discount->fldpercent;
                        } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                            $meddiscount = $discount->fldmedicine;
                            $surgicaldiscount = $discount->fldsurgical;
                            $extradiscount = $discount->fldextra;
                        } else {
                            $meddiscount = 0;
                            $surgicaldiscount = 0;
                            $extradiscount = 0;
                        }
                        $patdata = \App\PatDosing::where('fldid', $ids)->with('medicineBySetting')->first();
                        $rate = ($patdata->medicineBySetting) ? $patdata->medicineBySetting->fldsellpr : '0';
                        $total = ($patdata->medicineBySetting) ? ($patdata->medicineBySetting->fldsellpr) * ($patdata->fldqtydisp) : '0';
                        if ($flditemtype == 'Medicines') {
                            $ftotal = $total - (($meddiscount / 100) * $total);
                        } else if ($flditemtype == 'Surgicals') {
                            $ftotal = $total - (($surgicaldiscount / 100) * $total);
                        } else if ($flditemtype == 'Extra Items') {
                            $ftotal = $total - (($extradiscount / 100) * $total);
                        } else {
                            $ftotal = $total;
                        }


                        // $total = ($patdata->medicineBySetting) ? ($patdata->medicineBySetting->fldsellpr)*($patdata->fldqtydisp) : '0';

                        $finalsubtotal[] = $total;
                        $finaltotal[] = $ftotal;
                        $html .= '<tr data-fldid="' . $patdata->fldid . '"></tr>';
                        $html .= '<td>' . ($sn++) . '</td>';
                        $html .= '<td>' . $patdata->fldroute . '</td>';
                        $html .= '<td>' . $patdata->flditem . '</td>';
                        $html .= '<td>' . $patdata->flddose . '</td>';
                        $html .= '<td>' . $patdata->fldfreq . '</td>';
                        $html .= '<td>' . $patdata->flddays . '</td>';
                        $html .= '<td>' . $patdata->fldqtydisp . '</td>';
                        // $html .='<td><input type="checkbox" class="js-dispensing-label-checkbox" value="'.$patdata->fldid.'"></td>';
                        $html .= '<td>' . Helpers::numberFormat($rate) . '</td>';
                        $html .= '<td>' . $patdata->flduserid_order . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($total) . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($patdata->flddiscper) . '</td>';
                        $html .= '<td>' . Helpers::numberFormat((($patdata->flddiscper / 100) * $total)) . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($patdata->fldtaxper) . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($ftotal) . '</td>';
                        $html .= '<td><a href="javascript:void(0);" onclick="editMedicine(' . $patdata->fldid . ')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0)"  class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete"><i class="fa fa-times"></i></a></td>';
                        $html .= '</tr>';

                        $patdosingdata[] = $patdata;
                    }
                }
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Data saved.',
                    'data' => $html,
                    'finalsubtotal' => Helpers::numberFormat((($request->subtotal) + array_sum($finalsubtotal)) ,'insert'),
                    'finaltotal' => Helpers::numberFormat((($request->total) + array_sum($finaltotal)) ,'insert'),
                    'outofstockitem' => implode(',', $outofstockitem),
                    'patdata' => $patdosingdata
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Filed to process data.',
            ]);
        }

        return response()->json([
            'status' => FALSE,
            'message' => 'Invalid medicine selected.',
        ]);
    }

    public function showInfo(Request $request)
    {
        // dd($request->all());
        /*
            Pricing/currnet
                select fldbatch,fldexpiry,fldqty,fldsellpr from tblentry where fldstockid='Aceclofenac- 100 mg (CECLO-100)' and fldstatus=1
                select fldsellpr from tblentry where fldstockid='Aceclofenac- 100 mg (CECLO-100)' and fldstatus=1
            Inventory
                select fldstockno,fldstockid,fldbatch,fldexpiry,fldqty,fldsellpr,fldstatus,fldcomp from tblentry where fldstockid='Aceclofenac- 100 mg (CECLO-100)'
                select fldsellpr from tblentry where fldstockid='Aceclofenac- 100 mg (CECLO-100)' and fldstatus=1
            Alternate
                select flddrug from tblmedbrand where fldbrandid='Aceclofenac- 100 mg (CECLO-100)'
                select fldstockid,fldcomp,fldqty from tblentry where fldqty>0 and fldstockid in(select fldbrandid From tblmedbrand where fldactive='Active' and flddrug='Aceclofenac- 100 mg' and flddrug in(select flddrug From tbldrug where fldroute='oral'))
                brand: select fldbrand from tblmedbrand where fldbrandid='Albendazole- 40 mg/mL (A1-10ML)' and fldactive='Active'
        */
        $type = $request->get('type');
        $medicine = $request->get('medicine');
        $status = TRUE;
        $view = '';
        if ($type == 'Pricing' || $type == 'Current') {
            $data = \App\Entry::select('fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr')
                ->where([
                    'fldstockid' => $medicine,
                ])->first();
            $view = (string)view('dispensar::info', compact('data', 'type'));
        } elseif ($type == 'Inventory') {
            $data = \App\Entry::with('medbrand')->where([
                'fldstockid' => $medicine,
            ])->get();
            $view = (string)view('dispensar::info', compact('data', 'type'));
        } elseif ($type == 'Alternate') {
            $data = \DB::select("SELECT fldstockid,fldcomp,fldqty FROM tblentry
                WHERE
                    fldqty>? AND
                    fldstockid IN (
                        SELECT fldbrandid FROM tblmedbrand
                        WHERE
                            fldactive=? AND
                            flddrug=? AND
                            flddrug IN (SELECT flddrug FROM tbldrug WHERE fldroute=?)
                )", [
                0,
                'Active',
                $medicine,
                $request->get('route'),
            ]);

            if (isset($data) and count($data) > 0) {
                $view = (string)view('dispensar::info', compact('data', 'type'));
            } else {
                $status = FALSE;
            }

        } else {
            $status = FALSE;
        }
        // echo $status;
        return response()->json(compact('status', 'view'));
    }

    public function generatePdf(Request $request)
    {
        /*
            drug info: select fldmedinfo from tbllabel where fldroute='oral' and flddrug in(select flddrug from tblmedbrand where fldbrandid='CECLO-100')

            review:
        */
        $type = $request->get('type');
        $reporttype = '';
        $data = \App\PatDosing::select('fldid', 'fldroute', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flditem', 'fldencounterval')
            ->where('fldid', $request->get('fldid'))
            ->first();
        $medicine = $data->flditem;
        $allData = compact('type', 'data', 'reporttype');

        if ($type == 'Review') {
            $allData['reporttype'] = 'MEDICATION REVIEW';
            $allData['patientinfo'] = Helpers::getPatientByEncounterId($data->fldencounterval);;
            $allData['tableData'] = \App\Label::select('fldmedinfo')
                ->whereHas('medbrand', function ($query) use ($medicine) {
                    $query->where('fldbrandid', $medicine);
                })->get();
        } else {
            $allData['tableData'] = [];
        }

        return view('dispensar::pdf.medInfo', $allData);
    }

    public function validateDispense(Request $request)
    {
        $date = date('Y-m-d H:i:s', strtotime('-10 days', strtotime(date('Y-m-d H:i:s'))));
        $encounter_id = \Session::get('dispensing_form_encounter_id');
        $medicine = $request->get('medicine');
        $route = $request->get('route');

        /*
            select fldbrandid from tblmedbrand where fldbrand='ABANA' and fldactive='Active' and flddrug in(select flddrug from tbldrug where fldroute='oral')
            select SUM(fldqty) as col from tblentry where fldstockid='Abana- 1 tab (ABANA)' and fldcomp='comp07' and fldstatus=0
            select fldsellpr from tblentry where fldstockid='Abana- 1 tab (ABANA)' and fldstatus=0 and fldcomp='comp07'
        */

        $drugs = \App\Drug::select('flddrug')->where('fldroute', $route)->get()->pluck('flddrug');
        $medname = \App\MedicineBrand::select('fldbrandid')->where([
            'fldbrand' => $medicine,
            'fldactive' => 'Active',
        ])->whereIn('flddrug', $drugs)
            ->first();
        $medname = $medname->fldbrandid;
        $count = \App\PatDosing::where([
            ['fldencounterval', $encounter_id],
            ['flditem', $medname],
            ['fldroute', $route],
            ['fldsave_order', '1'],
            ['fldstarttime', '>', $date],
        ])->count();

        $meddetail = \App\Entry::select('fldsellpr', \DB::raw('SUM(fldqty) AS fldqty'), 'fldstockid')->where([
            'fldstockid' => $medname,
            // 'fldcomp' => Helpers::getCompName(),
            'fldstatus' => '1',
        ])->groupBy('fldsellpr')
            ->havingRaw('SUM(fldqty) > ?', [0])
            ->first();

        return response()->json(compact('count', 'meddetail'));
    }

    public function print(Request $request)
    {


        if(Options::get('disable_dispensing') == 1){
            return redirect()->back()->with('error_message', "Please wait..Another dispensing transaction is on process ");
        }
        Options::update('disable_dispensing',1);
        $medicines = $this->_getAllMedicine($request->get('type'),$request->get('fldencounterval'));
        $encounter_id = $request->get('fldencounterval');
        $receive_amt = $request->get('receive_amt');
        $discountamt = $request->get('discountamt');
        $discountpercentage = $request->get('discountpercentage');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();
        $departmentId = Helpers::getUserSelectedHospitalDepartmentIdSession();
        $fldremark = $request->get('fldremark');

        $patbillings = [];


        $taxtotal = 0;
        $discounttotal = 0;
        $itemtotal = 0;
        $chargedAmount = 0;
        $outofstockitem = array();
        \DB::beginTransaction();
        $instockmedicineitem = array();
        $billNumberGeneratedString = '';
        $hiid = '';
        $hiitemid = '';
        $hi_code ='';
        $opip = ($request->get('opip') == 'OP') ? 'OP' : 'IP';
        try {

            if (isset($medicines) and count($medicines) > 0) {
                // $medicinesCount = count($medicines);
                $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                $dateToday = \Carbon\Carbon::now();
                $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                $billNumberGeneratedString = "PHM-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                if ($request->get('payment_mode') == 'Credit') {

                    foreach ($medicines as $key => $medicine) {
                        $currentStock = $medicine->medicineBySetting ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {
                            // $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                            // $dateToday = \Carbon\Carbon::now();
                            // $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                            // $billNumberGeneratedString = "PHM-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                            $discount = '0';
                            if($request->get('type')=='hibill'){
                                $itemrate = ($medicine->medicineByStockRate) ? $medicine->medicineByStockRate->fldrate : 0;
                            }else{
                                $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            }
                            // $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                                'flddiscper' => $discountpercentage
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty)-($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);
                            //event trigger for live stock
                            event(new StockLive(1));

                            $tax = $medicine->fldtaxamt;
                            $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;

                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            if($request->get('type')=='hibill'){
                                $hi_code = \DB::table('tblstockrate')->selectraw('flditemname,fldid')->where('flddrug',$medicine->flditem)->get();
                                if($hi_code->isNotEmpty()){
                                    $hiid= $hi_code[0]->fldid;
                                    $hiitemid = $hi_code[0]->flditemname;
                                }else{
                                    $hiid= "";
                                    $hiitemid = "";

                                }
                            }else{
                                $hiid= "";
                                $hiitemid = "";

                            }

                            //dd($hi_code);


                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => $itemrate,
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ?: $medicine->flddiscper,
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'fldbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0 ',
                                'fldstatus' => 'Cleared',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => 0.00,
                                'fldvatper' => 0.00,
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                                'histockid' => $hiid,
                                'hiitemname' => $hiitemid
                            ];
                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    if($billNumberGeneratedString !=''){
                        $depositdata = \App\PatBillDetail::where('fldencounterval', $encounter_id)->orderBy('fldid', 'DESC')->where('fldbilltype','Credit')->whereNotNull('fldcurdeposit')->where('fldcomp',Helpers::getCompName())->get();

                        if (is_countable($depositdata) && count($depositdata)) {
                            $currentdeposit = $depositdata[0]->fldcurdeposit;
                        } else {
                            $currentdeposit = 0;
                        }
                        $taxtotal = $taxtotal ?: 0;
                        $discounttotal = $discounttotal ?: 0;
                        $chargedamt = $request->get('sub_total') + $taxtotal - $request->get('discountamt');
                        $insertDataPatDetail = [
                            'fldencounterval' => $encounter_id,
                            'flditemamt' => Helpers::numberFormat($itemtotal,'insert'),
                            'fldtaxamt' => Helpers::numberFormat($taxtotal,'insert'),
                            'flddiscountamt' => Helpers::numberFormat($discounttotal,'insert'),
                            'fldreceivedamt' => 0.00,
                            'fldchargedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldbilltype' => 'Credit',
                            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                            'fldtime' => date("Y-m-d H:i:s"),
                            'fldbillno' => $billNumberGeneratedString,
                            'fldchequeno' => $request->get('cheque_number'),
                            'fldbankname' => $request->get('bankname'),
                            'fldbill' => 'CREDIT INVOICE',
                            'fldcomp' => $computer,
                            'fldsave' => 1,
                            'remarks' =>$request->get('fldremark'),
                            'xyz' => 0,
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'tblexpecteddate' => $request->get('expecteddate') . " {date('H:i:s)}",
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldcurdeposit' => Helpers::numberFormat(($currentdeposit - $chargedamt),'insert'),
                            'fldprevdeposit' => Helpers::numberFormat($currentdeposit,'insert'),
                            'payment_mode' => "Credit",
                        ];
                        $tempPatBillData = \App\PatBillDetail::create($insertDataPatDetail);

                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounter_id)->first();

                        $flddepartment = null;
                        if($encounterData->fldcurrlocat){
                            $chkbed = Departmentbed::where('fldbed',$encounterData->fldcurrlocat)->first();
                            if($chkbed){
                                $flddepartment = $chkbed->flddept;
                            }else{
                                $chkdepart = Department::where('flddept',$encounterData->fldcurrlocat)->first();
                                if($chkdepart){
                                    $flddepartment = $chkdepart->flddept;
                                }
                            }
                        }

                        $deptRevenueData = [
                            'pat_details_id' => $tempPatBillData->fldid,
                            'fldencounterval' => $encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($request->get('sub_total'),'insert'),
                            'fldtaxamt' => Helpers::numberFormat($request->get('tax_amt'),'insert'),
                            'fldtaxgroup' => NULL,
                            'flddiscountamt' => Helpers::numberFormat($request->get('discountamt'),'insert'),
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldchargedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldreceivedamt' => 0,
                            'tblreason' => NULL,
                            'form_type' => 'Pharmacy Credit Billing',
                            'hospital_department_id' => $departmentId,
                            "location" => $encounterData->fldcurrlocat,
                            'bill_type' => 'Credit',
                            'xyz' => 0,
                            'flddepartment' => $flddepartment
                        ];
                        DepartmentRevenue::insert($deptRevenueData);
                        $encounterDetail = Encounter::select('fldcashcredit')->where('fldencounterval', $encounter_id)->first();



                        if ($fldremark) {
                            \App\Dispenseremark::insert([
                                'fldencounterval' => $encounter_id,
                                'fldbillno' => $billNumberGeneratedString,
                                'fldtime' => $time,
                                'fldremark' => $fldremark,
                                'hospital_department_id' => $departmentId,
                            ]);
                        }
                    }

                } else {

                    foreach ($medicines as $key => $medicine) {
                        $currentStock = isset($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {

                            $discount = '0';
                            if($request->get('type')=='hibill'){
                                $itemrate = ($medicine->medicineByStockRate) ? $medicine->medicineByStockRate->fldrate : 0;

                            }else{
                                $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            }

                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                                'flddiscper' => $discountpercentage
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty)-($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);
                            //event trigger for live stock
                            event(new StockLive(1));

                            $tax = (($itemrate * $medicine->fldqtydisp) - $discount) * ($medicine->fldtaxper/100);
                            // $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;

                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            if($request->get('type')=='hibill'){
                                $hi_code = \DB::table('tblstockrate')->selectraw('flditemname,fldid')->where('flddrug',$medicine->flditem)->get();
                                if($hi_code->isNotEmpty()){
                                    $hiid= $hi_code[0]->fldid;
                                    $hiitemid = $hi_code[0]->flditemname;
                                }else{
                                    $hiid= "";
                                    $hiitemid = "";

                                }
                            }else{
                                $hiid= "";
                                $hiitemid = "";
                            }



                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => Helpers::numberFormat($itemrate,'insert'),
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ?: $medicine->flddiscper,
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'fldbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0',
                                'fldstatus' => 'Cleared',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => 0.00,
                                'fldvatper' => 0.00,
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                                'histockid' => $hiid,
                                'hiitemname' => $hiitemid

                            ];
                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    if($billNumberGeneratedString != '' && $patbillings){
                        $depositdata = \App\PatBillDetail::where('fldencounterval', $encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->where('fldcomp',Helpers::getCompName())->get();
                        if (is_countable($depositdata) and count($depositdata)) {
                            $currentdeposit = $depositdata[0]->fldcurdeposit;
                        } else {
                            $currentdeposit = 0;
                        }
                        $chargedamt = $request->get('sub_total') + $taxtotal - $request->get('discountamt');


                        $patDetailsData = \App\PatBillDetail::create([
                            'fldencounterval' => $encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($itemtotal,'insert'),
                            'fldtaxamt' => Helpers::numberFormat($taxtotal,'insert'),
                            'flddiscountamt' => Helpers::numberFormat($discounttotal,'insert'),
                            'fldreceivedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldchargedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldbilltype' => 'Cash',
                            'payment_mode' => $request->payment_mode,
                            'flduserid' => $userid,
                            'fldchequeno' => $request->get('cheque_number'),
                            'fldbankname' => $request->get('bankname'),
                            'fldtime' => $time,
                            'fldcomp' => $computer,
                            'fldsave' => '1',
                            'remarks' =>$request->get('fldremark'),
                            'xyz' => '0',
                            'hospital_department_id' => $departmentId,
                            'fldbill' => 'Invoice',
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldcurdeposit' => 0.00,
                            'fldprevdeposit' => 0.00,
                            'tblexpecteddate' => $request->get('expecteddate') . " {date('H:i:s)}",
                        ]);
                        $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
                        \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounter_id)->first();

                        $patDetailsData['location'] = $encounterData->fldcurrlocat;
                        $patDetailsData['bill_type'] = 'Cash';
                        \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData, 'Pharmacy Billing');

                        \App\PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);


                        MaternalisedService::insertMaternalisedFiscalPharmacy($encounter_id,$billNumberGeneratedString,'cash');
                        if ($fldremark) {
                            \App\Dispenseremark::insert([
                                'fldencounterval' => $encounter_id,
                                'fldbillno' => $billNumberGeneratedString,
                                'fldtime' => $time,
                                'fldremark' => $fldremark,
                                'hospital_department_id' => $departmentId,
                            ]);
                        }
                    }

                }
                Options::update('disable_dispensing',0);
                \DB::commit();
            } else {
                Options::update('disable_dispensing',0);
                return redirect()->back()->with('error_message', "Please reorder the medicines to create invoice");
            }

            if($billNumberGeneratedString !='' && $patbillings){
                $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();

                $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                    $meddiscount = $discount->fldpercent;
                    $surgicaldiscount = $discount->fldpercent;
                    $extradiscount = $discount->fldpercent;
                } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                    $meddiscount = $discount->fldmedicine;
                    $surgicaldiscount = $discount->fldsurgical;
                    $extradiscount = $discount->fldextra;
                } else {
                    $meddiscount = 0;
                    $surgicaldiscount = 0;
                    $extradiscount = 0;
                }
                $encounterinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
                $trans = Helpers::getTranslationForLabel(strpos($encounterinfo->fldcurrlocat, 'OPD'));
                $printIds = explode(',', $request->get('printIds'));
                $paymentmode = $request->get('payment_mode');
                $discountamount = $request->get('discountamt');
                $subtotal = $request->get('sub_total');
                $taxtotal = $request->get('tax_amt');
                $total = $subtotal - ($discountamount + $taxtotal);
                $dispensemedicine = PatBilling::where('fldbillno', $billNumberGeneratedString)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Cleared')
                    ->where('fldsave', '1')
                    ->get();
                Options::update('disable_dispensing',0);
                return view('dispensar::pdf.dispense-print', compact('encounterinfo', 'medicines', 'receive_amt', 'trans', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'discountamount', 'subtotal', 'taxtotal', 'discounttotal', 'total', 'meddiscount', 'surgicaldiscount', 'extradiscount', 'dispensemedicine'));
            }else{
                Options::update('disable_dispensing',0);
                return redirect()->back()->with('error_message', "Medicine Out Of Stock");
            }

        } catch (\Exception $e) {
            Options::update('disable_dispensing',0);
            \DB::rollBack();
            dd($e);
            die('Someting went wrong. Please try again.');
        }
    }

    public function tpbill(Request $request){
        if(Options::get('disable_dispensing') == 1){
            return redirect()->back()->with('error_message', "Please wait..Another dispensing transaction is on process ");
        }
        Options::update('disable_dispensing',1);
        $medicines = $this->_getAllMedicine($request->get('type'),$request->get('fldencounterval'));
        $encounter_id = $request->get('fldencounterval');
        $receive_amt = $request->get('receive_amt');
        $discountamt = $request->get('discountamt');
        $discountpercentage = $request->get('discountpercentage');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();
        $departmentId = Helpers::getUserSelectedHospitalDepartmentIdSession();
        $fldremark = $request->get('fldremark');

        $patbillings = [];


        $taxtotal = 0;
        $discounttotal = 0;
        $itemtotal = 0;
        $chargedAmount = 0;
        $outofstockitem = array();
        \DB::beginTransaction();
        $instockmedicineitem = array();
        $billNumberGeneratedString = '';
        $opip = ($request->get('opip') == 'OP') ? 'OP' : 'IP';
        try {
            if (isset($medicines) and count($medicines) > 0) {
                // $medicinesCount = count($medicines);

                $dateToday = \Carbon\Carbon::now();
                $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();

                if ($request->get('payment_mode') == 'Credit') {
                    $billNumber = AutoId::where('fldtype', 'TempBillAutoId')->first();
                    $billNumberGeneratedString = "TPPHM-{$year->fldname}-{$billNumber->fldvalue}";
                    $new_bill_number = $billNumber->fldvalue + 1;

                    $billNumber->update(['fldvalue' => $new_bill_number]);
                    foreach ($medicines as $key => $medicine) {
                        $currentStock = $medicine->medicineBySetting ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {

                            $discount = '0';
                            $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                                'flddiscper' => $discountpercentage
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty)-($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);
                            //event trigger for live stock
                            event(new StockLive(1));

                            $tax = $medicine->fldtaxamt;
                            $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;
                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => Helpers::numberFormat($itemrate,'insert'),
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ?: $medicine->flddiscper,
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '0',
                                'fldtempbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0',
                                'fldstatus' => 'Punched',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => 0.00,
                                'fldvatper' => 0.00,
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                            ];

                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    \App\PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);

                    /*insert tblpatbillings details in tbltpbills*/
                        $tpbills = \App\PatBilling::where('fldtempbillno',$billNumberGeneratedString)->get();
                        TpBillService::saveTpBillItems($tpbills);
                    /*End tblpatbillings details in tbltpbills*/




                } else {
                    $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                    $billNumberGeneratedString = "PHM-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                    foreach ($medicines as $key => $medicine) {
                        $currentStock = isset($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {

                            $discount = '0';
                            $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                                'flddiscper' => $discountpercentage
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty)-($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);
                            //event trigger for live stock
                            event(new StockLive(1));

                            $tax = $medicine->fldtaxamt;
                            $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;
                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => Helpers::numberFormat($itemrate,'insert'),
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ?: $medicine->flddiscper,
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'fldbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0',
                                'fldstatus' => 'Cleared',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => 0.00,
                                'fldvatper' => 0.00,
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                            ];
                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    if($billNumberGeneratedString != '' && $patbillings){
                        $depositdata = \App\PatBillDetail::where('fldencounterval', $encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->get();
                        if (is_countable($depositdata) and count($depositdata)) {
                            $currentdeposit = $depositdata[0]->fldcurdeposit;
                        } else {
                            $currentdeposit = 0;
                        }
                        $chargedamt = $itemtotal + $taxtotal - $discounttotal;
                        $patDetailsData = \App\PatBillDetail::create([
                            'fldencounterval' => $encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($itemtotal,'insert'),
                            'fldtaxamt' => Helpers::numberFormat($taxtotal,'insert'),
                            'flddiscountamt' => Helpers::numberFormat($discounttotal,'insert'),
                            'fldreceivedamt' => Helpers::numberFormat($chargedamt,'insert'),
                            'fldchargedamt' => Helpers::numberFormat($chargedamt,'insert'),
                            'fldbilltype' => 'Cash',
                            'flduserid' => $userid,
                            'fldchequeno' => $request->get('cheque_number'),
                            'fldbankname' => $request->get('bankname'),
                            'fldtime' => $time,
                            'fldcomp' => $computer,
                            'fldsave' => '1',
                            'remarks' =>$request->get('fldremark'),
                            'xyz' => '0',
                            'hospital_department_id' => $departmentId,
                            'fldbill' => 'Invoice',
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldcurdeposit' => 0.00,
                            'fldprevdeposit' => 0.00,
                            'tblexpecteddate' => $request->get('expecteddate') . " {date('H:i:s)}",
                        ]);

                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounter_id)->first();

                        $patDetailsData['location'] = $encounterData->fldcurrlocat;
                        $patDetailsData['bill_type'] = 'Cash';
                        \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData, 'Pharmacy Billing');
                        MaternalisedService::insertMaternalisedFiscalPharmacy($encounter_id,$billNumberGeneratedString,'cash');

                        \App\PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);

                        if ($fldremark) {
                            \App\Dispenseremark::insert([
                                'fldencounterval' => $encounter_id,
                                'fldbillno' => $billNumberGeneratedString,
                                'fldtime' => $time,
                                'fldremark' => $fldremark,
                                'hospital_department_id' => $departmentId,
                            ]);
                        }
                    }

                }
                Options::update('disable_dispensing',0);
                \DB::commit();
            }else{
                Options::update('disable_dispensing',0);
                return redirect()->back()->with('error_message', "Please reorder the medicines to create invoice");
            }
            if($billNumberGeneratedString){
                $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();

                $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                    $meddiscount = $discount->fldpercent;
                    $surgicaldiscount = $discount->fldpercent;
                    $extradiscount = $discount->fldpercent;
                } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                    $meddiscount = $discount->fldmedicine;
                    $surgicaldiscount = $discount->fldsurgical;
                    $extradiscount = $discount->fldextra;
                } else {
                    $meddiscount = 0;
                    $surgicaldiscount = 0;
                    $extradiscount = 0;
                }
                $encounterinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
                $trans = Helpers::getTranslationForLabel(strpos($encounterinfo->fldcurrlocat, 'OPD'));
                $printIds = explode(',', $request->get('printIds'));
                $paymentmode = $request->get('payment_mode');
                $discountamount = $request->get('discountamt');
                $subtotal = $request->get('sub_total');
                $taxtotal = $request->get('tax_amt');
                $total = $subtotal - ($discountamount + $taxtotal);
                if($request->get('payment_mode') == 'Credit'){
                    $save = 0;
                    $comparecolumn = 'fldtempbillno';
                }else{
                    $save = 1;
                    $comparecolumn = 'fldbillno';
                }
                $dispensemedicine = PatBilling::where($comparecolumn, $billNumberGeneratedString)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Punched')
                    ->where('fldsave', $save)
                    ->get();

                $remarks = $request->get('fldremark');
                if($request->get('payment_mode') == 'Credit'){
                    Options::update('disable_dispensing',0);
                    return view('dispensar::pdf.dispense-tpprint', compact('encounterinfo', 'medicines', 'receive_amt', 'trans', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'discountamount', 'subtotal', 'taxtotal', 'discounttotal', 'total', 'meddiscount', 'surgicaldiscount', 'extradiscount', 'dispensemedicine','remarks'));
                }else{
                    Options::update('disable_dispensing',0);
                    return view('dispensar::pdf.dispense-print', compact('encounterinfo', 'medicines', 'receive_amt', 'trans', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'discountamount', 'subtotal', 'taxtotal', 'discounttotal', 'total', 'meddiscount', 'surgicaldiscount', 'extradiscount', 'dispensemedicine','remarks'));
                }

            }else{
                Options::update('disable_dispensing',0);
                return redirect()->back()->with('error_message', "Medicine Out Of Stock");
            }
        }catch(\Exception $e){
            Options::update('disable_dispensing',0);
            \DB::rollBack();
            dd($e);
        }
    }

    public function getOnlineRequest(Request $request)
    {
        // select fldencounterval,fldstatus,fldid from tblpatdosing where fldsave_order='0' and fldsave='1' and fldorder='Request' and fldcurval='Continue' and fldcomp_order='comp01' GROUP BY fldencounterval
        $compid = $request->get('compid');
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');
        $fromdate = $fromdate ? Helpers::dateNepToEng($fromdate)->full_date : date('Y-m-d');
        $todate = $todate ? Helpers::dateNepToEng($todate)->full_date : date('Y-m-d');

        $encid = $request->get('encid');
        $name = $request->get('name');


        $patdosing = \App\PatDosing::select('fldencounterval', 'fldstatus', 'fldid')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldcurrlocat',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
            ])->where([
                ['fldsave_order', '0'],
                ['fldsave', '1'],
                ['fldorder', 'Request'],
                ['fldcurval', 'Continue'],
                ['fldtime_order', '>=', "$fromdate 00:00:00"],
                ['fldtime_order', '<=', "$todate 23:59:59"],

            ]);
        if ($compid)
            $patdosing->where('fldcomp_order', $compid);
        if ($encid)
            $patdosing->where('fldencounterval', $encid);
        if ($name)
            $patdosing->whereHas('encounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });

        return response()->json($patdosing->get());
    }

    public function remarkreport(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $remark = $request->get('remark');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $remarks = \App\Dispenseremark::select('fldid', 'fldencounterval', 'fldbillno', 'fldtime', 'fldremark')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptcontact,fldptsex,fldptbirday,fldrank',
            ])->where('fldtime', ">=", "{$from_date} 00:00:00")
            ->where('fldtime', "<=", "{$to_date} 23:59:59.999");

        if ($encounter_id)
            $remarks->where('fldencounterval', $encounter_id);
        if ($remark)
            $remarks->where('fldremark', 'like', "%{$remark}%");
        if ($name)
            $remarks->whereHas('encounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', "%{$name}%");
            });
        if ($phone)
            $remarks->whereHas('encounter.patientInfo', function ($q) use ($phone) {

                $q->where('fldptcontact', 'like', "%{$phone}%");
            });

        $from_date = Helpers::dateEngToNepdash($from_date)->full_date;
        $to_date = Helpers::dateEngToNepdash($to_date)->full_date;

        return view('dispensar::remarkreport', [
            'remarks' => $remarks->get(),
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    public function remarkreportCsv(Request $request)
    {
        $export = new \App\Exports\DispensingRemarkReportExport(
            $request->encounter_id,
            $request->name,
            $request->phone,
            $request->remark,
            $request->from_date,
            $request->to_date
        );
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'DispensingRemarkReport.xlsx');
    }

    public function deleteMedicine(Request $request)
    {
        try {
            // dd($request->all());
            $computer = Helpers::getCompName();

            \App\PatDosing::where('fldid', $request->fldid)->delete();
            $medicines = $this->_getAllMedicine($request->get('type'),$request->fldencounterval);

            $html = '';
            $taxtotal = 0;
            $discounttotal = 0;
            $itemtotal = 0;
            $finaletotal = 0;
            $subtotal = 0;
            $encounter_id = $request->fldencounterval;

            if (isset($medicines) and count($medicines) > 0) {
                foreach ($medicines as $k => $med) {
                    if(isset($med->medicineBySetting)){
                       $taxtotal += $med->fldtaxamt;

                        $itemtotal += $med->fldtotal;

                        if($request->get('type') == 'hibill'){
                            $rate = ($med->medicineByStockRate) ? $med->medicineByStockRate->fldrate : '0';
                            $total = ($med->medicineByStockRate) ? ($med->medicineByStockRate->fldrate) * ($med->fldqtydisp) : '0';
                            $subtotal += $total;

                        }else{
                            $rate = ($med->medicineBySetting) ? $med->medicineBySetting->fldsellpr : '0';
                            $total = ($med->medicineBySetting) ? ($med->medicineBySetting->fldsellpr) * ($med->fldqtydisp) : '0';
                            $subtotal += $total;
                        }

                       

                        $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();

                        $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                        if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                            $meddiscount = $discount->fldpercent;
                            $surgicaldiscount = $discount->fldpercent;
                            $extradiscount = $discount->fldpercent;
                        } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                            $meddiscount = $discount->fldmedicine;
                            $surgicaldiscount = $discount->fldsurgical;
                            $extradiscount = $discount->fldextra;
                        } else {
                            $meddiscount = 0;
                            $surgicaldiscount = 0;
                            $extradiscount = 0;
                        }

                        if ($med->flditemtype == 'Surgicals') {
                            $discountper = $surgicaldiscount;
                        } else if ($med->flditemtype == 'Medicines') {
                            $discountper = $meddiscount;
                        } else if ($med->flditemtype == 'Extra Items') {
                            $discountper = $extradiscount;
                        } else {
                            $discountper = 0;
                        }
                        $discounttotal += $med->flddiscamt;
                        $finaletotal += $total - $med->flddiscamt+$med->fldtaxamt;
                        $html .= '<tr data-fldid="' . $med->fldid . '" data-stocknumber="' . $med->medicineBySetting->fldstockno . '" data-taxpercentage="'.$med->fldtaxper.'">';
                        $html .= '<td>' . ($k + 1) . '</td>';
                        $html .= '<td>' . $med->fldroute . '</td>';
                        $html .= '<td>' . $med->flditem . '</td>';
                        $html .= '<td>' . (($med->medicineBySetting) ? $med->medicineBySetting->fldexpiry : '') . '</td>';
                        $html .= '<td>' . $med->flddose . '</td>';
                        $html .= '<td>' . $med->fldfreq . '</td>';
                        $html .= '<td>' . $med->flddays . '</td>';
                        $html .= '<td>' . $med->fldqtydisp . '</td>';
                        // $html .='<td><input type="checkbox" class="js-dispensing-label-checkbox" value="'.$med->fldid.'"></td>';
                        $html .= '<td>' . $rate . '</td>';
                        $html .= '<td>' . $med->flduserid_order . '</td>';
                        $html .= '<td>' . $total . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($med->fldtaxamt) . '</td>';
                        $html .= '<td>'.Helpers::numberFormat($med->flddiscamt).'</td>';
                        $html .='<td>'.Helpers::numberFormat(($total-$med->flddiscamt+$med->fldtaxamt)) .'</td>';
                        $html .= '<td><a href="javascript:void(0);" onclick="editMedicine(' . $med->fldid . ','.$med->medicineBySetting->fldstockno.')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete"><i class="fa fa-trash"></i></a></td>';
                        $html .= '</tr>';
                    }

                }
            }
            $data['html'] = $html;
            $data['subtotal'] = $subtotal;
            $data['total'] = $finaletotal;
            $data['dsicountetotal'] = $discounttotal;
            if(isset($subtotal) and $subtotal !='0'){
                $data['discountpercent'] = ($discounttotal*100)/$subtotal;

            }else{
                $data['discountpercent'] = 0;
            }
            $data['taxtotal'] = $taxtotal;
            // dd($data['html']);
            return $data;
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function updateDetails(Request $request)
    {
        try {
            $medetails = \App\PatDosing::where('fldid', $request->fldid)->first();
            $html = '';
            $dosehtml = '';


            $value = Options::get('dispensing_freq_dose');
            if (isset($medetails) and $medetails != '') {
                if (Options::get('dispensing_freq_dose') == 'Auto') {
                    $string = 'disabled value="1"';
                } else {
                    $string = 'value="' . $medetails->flddays . '"';
                }


                $dosehtml .= '<label>Dose:</label><input id="updatedose" name="dose" type="text" class="form-control" value="' . $medetails->flddose . '">';

                $html .= '<label>Days:</label><input id="updatedays" name="days" type="text" class="form-control" ' . $string . '>';
                $html .= '<label>Qty:</label><input id="updateqty" name="qty" type="text" class="form-control" value="' . $medetails->fldqtydisp . '" onkeypress="return onlyNumberKey(event)">';
                $html .= '<input type="hidden" name="fldid" id="update_fldid" value="' . $request->fldid . '"/>';
                $html .= '<input type="hidden" name="encounterIds" id="encounterIds" value="' . $request->fldencounterval . '"/>';
                $html .= '<input type="hidden" name="medicine" id="update_medicine" value="' . $medetails->flditem . '"/>';
                $html .= '<input type="hidden" name="stocknumber" id="stocknumber" value="' . $request->fldstockno . '"/>';
            }

            $data['html'] = $html;
            $data['dosehtml'] = $dosehtml;
            // dd($data);
            return $data;
        } catch (\Exception $e) {
        }
    }

     public function updateTPItem(Request $request)
    {
        try {
            $patbilldetail = PatBilling::where('fldid',$request->fldid)->where('fldencounterval',$request->fldencounterval)->first();
            $data['flditemname'] = $patbilldetail->flditemname;
            $data['quantity'] = $patbilldetail->flditemqty;
            $data['fldid'] = $request->fldid;
            $data['fldencounterval'] = $request->fldencounterval;
            return $data;
        } catch (\Exception $e) {
        }
    }

    public function updateEntity(Request $request)
    {
        // dd($request->all());
        try {
            $encounter_id = $request->encounterIds;
            if (isset($request->freq)) {
                $data['fldfreq'] = $request->freq;
            }
            if (isset($request->days)) {
                $data['flddays'] = $request->days;
            }

            $data['flddose'] = $request->dose;
            $data['fldqtydisp'] = $request->qty;

            $existingmed = \App\PatDosing::where('fldid', $request->fldid)->update($data);

            // $medicines = $this->_getAllMedicine('ordered',$encounter_id);
            $medicines = $this->_getAllMedicine($request->get('orderType'),$encounter_id);
            $html = '';
            $taxtotal = 0;
            $discounttotal = 0;
            $itemtotal = 0;
            $subtotal = 0;
             // dd($medicines);
            if (isset($medicines) and count($medicines) > 0) {
                foreach ($medicines as $k => $med) {
                   if(isset($med->medicineBySetting)){
                         $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();

                        $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                        if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                            $meddiscount = $discount->fldpercent;
                            $surgicaldiscount = $discount->fldpercent;
                            $extradiscount = $discount->fldpercent;
                        } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                            $meddiscount = $discount->fldmedicine;
                            $surgicaldiscount = $discount->fldsurgical;
                            $extradiscount = $discount->fldextra;
                        } else {
                            $meddiscount = 0;
                            $surgicaldiscount = 0;
                            $extradiscount = 0;
                        }

                        $taxtotal += $med->fldtaxamt;
                        // $discounttotal += $med->flddiscamt;

                        if($request->get('orderType')=='hibill'){
                            $rate = ($med->medicineByStockRate) ? $med->medicineByStockRate->fldrate : '0';
                            $total = ($med->medicineByStockRate) ? ($med->medicineByStockRate->fldrate) * ($med->fldqtydisp) : '0';
                        }else{
                            $rate = ($med->medicineBySetting) ? $med->medicineBySetting->fldsellpr : '0';
                            $total = ($med->medicineBySetting) ? ($med->medicineBySetting->fldsellpr) * ($med->fldqtydisp) : '0';
                        }



                        if ($med->flditemtype == 'Medicines') {
                            $discountpercent = $meddiscount;
                            $discountamount = ($meddiscount / 100) * $total;
                        } else if ($med->flditemtype == 'Surgicals') {
                            $discountpercent = $surgicaldiscount;
                            $discountamount = ($surgicaldiscount / 100) * $total;
                        } else if ($med->flditemtype == 'Extra Items') {
                            $discountamount = $extradiscount;
                            $discountamount = ($extradiscount / 100) * $total;
                        } else {
                            $discountpercent = 0;
                            $discountamount = 0;
                        }
                        $subtotal += $total;
                        $discounttotal += $discountamount;
                        // $itemtotal += $total - $med->flddiscamt+$med->fldtaxamt;
                        $itemtotal += $total - $discountamount +$med->fldtaxamt;
                        $expiry = $med->medicineBySetting ? $med->medicineBySetting->fldexpirydateonly : '';
                        $html .= '<tr data-fldid="' . $med->fldid . '" data-stocknumber="' . $med->medicineBySetting->fldstockno . '" data-taxpercentage="'.$med->fldtaxper.'">';
                        $html .= '<td>' . ($k + 1) . '</td>';
                        $html .= '<td>' . $med->fldroute . '</td>';
                        $html .= '<td>' . $med->flditem . '</td>';
                        $html .= '<td>' . $expiry .'</td>';
                        $html .= '<td>' . $med->flddose . '</td>';
                        $html .= '<td>' . $med->fldfreq . '</td>';
                        $html .= '<td>' . $med->flddays . '</td>';
                        $html .= '<td>' . $med->fldqtydisp . '</td>';
                        // $html .='<td><input type="checkbox" class="js-dispensing-label-checkbox" value="'.$med->fldid.'"></td>';
                        $html .= '<td>' . $rate . '</td>';
                        $html .= '<td>' . $med->flduserid_order . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($total) . '</td>';
                        $html .= '<td>' . Helpers::numberFormat($med->fldtaxamt) . '</td>';
                        $html .= '<td>'.Helpers::numberFormat($discountamount).'</td>';
                        $html .='<td>'.Helpers::numberFormat(($total-$discountamount+$med->fldtaxamt)).'</td>';

                        $html .= '<td><a href="javascript:void(0);" onclick="editMedicine(' . $med->fldid . ','.$med->medicineBySetting->fldstockno.')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete"><i class="fa fa-times"></i></a></td>';
                        $html .= '</tr>';
                   }
                }
            }

            return response()->json([
                'html' => $html,
                'subtotal' => $subtotal,
                'total' => $itemtotal,
                'dsicountetotal' => $discounttotal,
                'taxtotal' => $taxtotal,
                'discountpercent' => ($discounttotal*100)/$subtotal,
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function updateTPItemQuantity(request $request){
        try{
            // dd($request->all());
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $patbilling = PatBilling::where('fldid',$request->fldid)->where('fldencounterval',$request->encounter_id)->first();

            #Update quantity in Pat Billing
            $discountamt = ($patbilling->flddiscper/100)*($request->new_qty * $patbilling->flditemrate);
            $taxamt = ($patbilling->fldtaxper/100)*($request->new_qty * $patbilling->flditemrate);

            $updatedata['flditemqty'] = $request->new_qty;
            $updatedata['flddiscamt'] = Helpers::numberFormat($discountamt,'insert');
            $updatedata['fldtaxamt'] = Helpers::numberFormat($taxamt,'insert');
            $updatedata['fldditemamt'] = Helpers::numberFormat((($request->new_qty * $patbilling->flditemrate)+$taxamt-$discountamt),'insert');
            $updatedata['fldtime'] = $time;
            $updatedata['fldordtime'] = $time;
            $updatedata['fldorduserid'] = $userid;
            $updatedata['flduserid'] = $userid;
            $updatedata['fldordcomp'] = $computer;
            $updatedata['fldcomp'] = $computer;
            // dd($updatedata);
            PatBilling::where('fldid',$request->fldid)->update($updatedata);
            #End Update quantity in Pat Billing

            #Update in tbltpbills with tempbillnumber
                $tppatbilling = PatBilling::where('fldid',$request->fldid)->first();
                if($tppatbilling->fldtempbillno !='' or !is_null($tppatbilling->fldtempbillno)){
                    TpBillService::updateTpBillItems($tppatbilling);
                }
            #End Update in tbltpbills with tempbillnumber

            #Update quantity in Entry table
            $additionalqty = $request->existing_qty - $request->new_qty;
            $existingentrydata = \App\Entry::select('fldqty')->where('fldstockno',$patbilling->flditemno)->first();
            $updatedvalue = $existingentrydata->fldqty+$additionalqty;
            \App\Entry::where([
                    'fldstockno' => $patbilling->flditemno
                ])->update([
                    'fldqty' => $updatedvalue,
                    'fldsav' => '1',
                    'xyz' => '0',
                ]);
            #End Update quantity in Entry table
            $mainhtml = '';
            $tpbills = \App\PatBilling::where('fldencounterval',$request->encounter_id)
                ->whereNull('fldbillno')
                ->where(function ($query) {
                    $query->whereNotNull('fldtempbillno')
                        ->orWhere('fldtempbillno','!=','');
                })
                ->where('fldcomp',\App\Utils\Helpers::getCompName())
                ->get();

            if(isset($tpbills) and count($tpbills) > 0){
                $total = 0;
                foreach($tpbills as $k=>$bill){
                    $sn = $k+1;
                    $total += $bill->fldditemamt;
                    $mainhtml .='<tr data-fldid="' . $bill->fldid . '" data-fldencounterval="' . $bill->fldencounterval . '">';
                    $mainhtml .='<td>'.$sn.'</td>';
                    $mainhtml .='<td>'.$bill->flditemtype.'</td>';
                    $mainhtml .='<td>'.$bill->flditemname.'</td>';
                    $mainhtml .='<td>'.$bill->flditemqty.'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->flditemrate).'</td>';
                    $mainhtml .='<td>'.$bill->fldorduserid.'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat(($bill->flditemrate * $bill->flditemqty)).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->flddiscamt).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->fldtaxamt).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->fldditemamt).'</td>';
                    $mainhtml .='<td>'.$bill->fldordtime.'</td>';
                    $mainhtml .='<td><a href="javascript:void(0);" onclick="editTPItem(' . $bill->fldid . ')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-danger tpdelete"><i class="fa fa-times"></i></a></td>';
                    $mainhtml .='</tr>';
                }
                $mainhtml .='<tr>';
                $mainhtml .='<td colspan="10" class="text-right"> <b>Total : </b>'.$total.'</td>';
                $mainhtml .='<td></td>';
                $mainhtml .='<td></td>';
                $mainhtml .='</tr>';
            }else{
                $mainhtml .='<td colspan="11">Data Not Available.</td>';
            }
            $data['totalTPAmountReceived'] = Helpers::getTpAmount($request->encounter_id);
            $data['totalDepositAmountReceived'] =  Helpers::totalDepositAmountReceived($request->encounter_id   );

            $data['remaining_deposit'] = Helpers::numberFormat(($data['totalDepositAmountReceived']-$data['totalTPAmountReceived']),'insert');
            return response()->json([
                'mainhtml' => $mainhtml,
                'data' => $data
                
            ]);

        }catch(\Exception$e){
            dd($e);
        }
    }

    public function deleteTPItem(Request $request){
        try{
            $patdata = PatBilling::where('fldid',$request->fldid)->first();
            $entrydata = \App\Entry::where('fldstockno',$patdata->flditemno)->first();
            $updatevalue = $patdata->flditemqty + $entrydata->fldqty;
            \App\Entry::where([
                            'fldstockno' => $patdata->flditemno
                        ])->update([
                            'fldqty' => $updatevalue,
                            'fldsav' => '1',
                            'xyz' => '0',
                        ]);
            PatBilling::where('fldid',$request->fldid)->delete();
            #Update fldstatus in tbltpbills with requested fldid

                TpBillService::updateDeletedTpBillItems($request->fldid);

            #End Update fldstatus in tbltpbills with requested fldid
            $tpbills = \App\PatBilling::where('fldencounterval',$request->fldencounterval)
                ->whereIn('flditemtype',['Surgicals','Medicines','Extra Items'])
                ->whereNull('fldbillno')
                ->where(function ($query) {
                    $query->whereNotNull('fldtempbillno')
                        ->orWhere('fldtempbillno','!=','');
                })
                ->where('fldcomp',\App\Utils\Helpers::getCompName())
                ->get();
            $mainhtml = '';
            if(isset($tpbills) and count($tpbills) > 0){
                $total = 0;
                foreach($tpbills as $k=>$bill){
                    $total += $bill->fldditemamt;
                    $sn = $k+1;
                    $mainhtml .='<tr data-fldid="' . $bill->fldid . '" data-fldencounterval="' . $bill->fldencounterval . '">';
                    $mainhtml .='<td>'.$sn.'</td>';
                    $mainhtml .='<td>'.$bill->flditemtype.'</td>';
                    $mainhtml .='<td>'.$bill->flditemname.'</td>';
                    $mainhtml .='<td>'.$bill->flditemqty.'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->flditemrate).'</td>';
                    $mainhtml .='<td>'.$bill->fldorduserid.'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat(($bill->flditemrate * $bill->flditemqty)).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->flddiscamt).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->fldtaxamt).'</td>';
                    $mainhtml .='<td>'.Helpers::numberFormat($bill->fldditemamt).'</td>';
                    $mainhtml .='<td>'.$bill->fldordtime.'</td>';
                    $mainhtml .='<td><a href="javascript:void(0);" onclick="editTPItem(' . $bill->fldid . ')" class="btn btn-primary"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-danger tpdelete"><i class="fa fa-times"></i></a></td>';
                    $mainhtml .='</tr>';
                }
                $mainhtml .='<tr>';
                $mainhtml .='<td colspan="10" class="text-right">'.$total.'</td>';
                $mainhtml .='<td></td>';
                $mainhtml .='</tr>';
            }else{
                $mainhtml .='<tr><td colspan="11">Data Not Available.</td></tr>';
            }
            $data['mainhtml'] = $mainhtml;

            $data['totalTPAmountReceived'] = Helpers::getTpAmount($patdata->fldencounterval);
            $data['totalDepositAmountReceived'] =  Helpers::totalDepositAmountReceived($patdata->fldencounterval);

            $data['remaining_deposit'] = Helpers::numberFormat(($data['totalDepositAmountReceived']-$data['totalTPAmountReceived']),'insert');
            return response()->json([
                'data' => $data
            ]);
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function enablePharmacy(Request $request){
        try{
            Options::update('disable_dispensing',0);
            return response()->json([
                    'message' => 'Pharmacy Enabled'
                ]);
        }catch(\Exception $e){
            dd($e);
        }
    }
}
