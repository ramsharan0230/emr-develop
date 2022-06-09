<?php

namespace Modules\Patient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Consult;
use App\Encounter;
use App\PatientInfo;
use App\PatBillDetail;
use App\User;
use App\Jobs\SendPatientEmailJob;
use App\Jobs\SendPatientSmsJob;
use App\Utils\Helpers;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PatientEmailAndSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
       $record=DB::table('tblpatbilldetail as pbd')
        ->join('tblencounter as ec', 'pbd.fldencounterval', '=', 'ec.fldencounterval')
        ->join('tblpatientinfo as pi', 'ec.fldpatientval', '=', 'pi.fldpatientval')
        ->select(
            'pbd.fldencounterval',
            'pi.fldpatientval',
            'pi.fldptnamefir',
            'pi.fldptnamelast',
            'pi.fldptbirday',
            'pi.fldptsex',
            'pi.fldptcontact',
            'pi.fldptadddist',
            'pi.fldemail'
        )
        ->take(50)
        ->groupBy('pi.fldpatientval')
        ->orderBy('pi.fldpatientval','desc')
        ->get();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('patient::patient-email-sms.patient-email-sms-list',compact('record','date'));
    }

    public function sendEmail(Request $request){
        try {
            $patient=PatientInfo::whereIn('fldpatientval',$request->patlient_id)->get();
            foreach($patient as $list){
                dispatch(new SendPatientEmailJob($list,'custom messages'));
            }
            return response()->json(
                collect([
                    'response' => 'success'
                ])->toJson()
            );
          
          } catch (\Exception $e) {
              return $e->getMessage();
          }
    }

    public function sendSms(Request $request){
        try {
            $patient=PatientInfo::whereIn('fldpatientval',$request->patlient_id)->get();
            foreach($patient as $list){
                dispatch(new SendPatientSmsJob($list,'custom messages'));
            }
            return response()->json(
                collect([
                    'response' => 'success'
                ])->toJson()
            );
          
          } catch (\Exception $e) {
              return $e->getMessage();
          }
    }
    
}
