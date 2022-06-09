<?php

namespace Modules\Servicedata\Http\Controllers;

use App\Encounter;
use App\PatientInfo;
use App\Districts;
use App\Department;
use App\BillingSet;
use App\Consult;
use App\EthnicGroup;
use Carbon\Carbon;
use App\Utils\Helpers;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function summarizeReport(Request $request)
    {
        $type = $request->type;
        $from = $request->from_date;
        $to = $request->to_date;
        if($type == 'department'){
            $field = 'fldconsultname';
            $result = Consult::select('fldconsultname',DB::raw("COUNT(fldconsultname) as total"))->whereBetween('fldtime', array($from, $to))->groupBy('fldconsultname')->get();
        }elseif($type == 'billing'){
            $field = 'fldbillingmode';
            $result = Consult::select('fldbillingmode',DB::raw("COUNT(fldbillingmode) as total"))->whereBetween('fldtime', array($from, $to))->groupBy('fldbillingmode')->get();
        }elseif($type == 'consultant'){
            $field = 'flduserid';
            $result = Consult::select('flduserid',DB::raw("COUNT(flduserid) as total"))->whereBetween('fldtime', array($from, $to))->groupBy('flduserid')->get();
        }elseif($type == 'location'){
            $field = 'fldcomp';
            $result = Consult::select('fldcomp',DB::raw("COUNT(fldcomp) as total"))->whereBetween('fldtime', array($from, $to))->groupBy('fldcomp')->get();
        }elseif($type == 'status'){
            $field = 'fldstatus';
            $result = Consult::select('fldstatus',DB::raw("COUNT(fldstatus) as total"))->whereBetween('fldtime', array($from, $to))->groupBy('fldstatus')->get();
        }else{
            $field = '';
            $result = array();
        }
        // dd($result);
        $totaldata = 0;
        if(isset($result) and count($result) > 0 ){
            foreach($result as $r){
                $t =  $r->total;
                $totaldata += $t;
            }
        }
        // echo $totaldata; exit;
        $data['field'] = $field;
        $data['total'] = $totaldata;
        $data['date_from'] = date('m/d/Y',strtotime($from));
        $data['date_to'] = date('m/d/Y',strtotime($to));
        $data['result'] = $result;
        // dd($data);
        return view('servicedata::pdf.summarize', $data)/*->setPaper('a4')->stream('consultation_summary_'.$type.'.pdf')*/;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function datewiseReport(Request $request)
    {
        $type = $request->type;
        $from = $request->from_date;
        $to = $request->to_date;
        if($type == 'department'){
            $field = 'fldconsultname';
            $result = Consult::select('fldconsultname',DB::raw("COUNT(fldconsultname) as total"))->whereBetween('fldconsulttime', array($from, $to))->groupBy('fldconsultname')->get();
        }elseif($type == 'billing'){
            $field = 'fldbillingmode';
            $result = Consult::select('fldbillingmode',DB::raw("COUNT(fldbillingmode) as total"))->whereBetween('fldconsulttime', array($from, $to))->groupBy('fldbillingmode')->get();
        }elseif($type == 'consultant'){
            $field = 'flduserid';
            $result = Consult::select('flduserid',DB::raw("COUNT(flduserid) as total"))->whereBetween('fldconsulttime', array($from, $to))->groupBy('flduserid')->get();
        }elseif($type == 'location'){
            $field = 'fldcomp';
            $result = Consult::select('fldcomp',DB::raw("COUNT(fldcomp) as total"))->whereBetween('fldconsulttime', array($from, $to))->groupBy('fldcomp')->get();
        }elseif($type == 'status'){
            $field = 'fldstatus';
            $result = Consult::select('fldstatus',DB::raw("COUNT(fldstatus) as total"))->whereBetween('fldconsulttime', array($from, $to))->groupBy('fldstatus')->get();
        }else{
            $field = '';
            $result = array();
        }
        // dd($result);
        $totaldata = 0;
        if(isset($result) and count($result) > 0 ){
            foreach($result as $r){
                $t =  $r->total;
                $totaldata += $t;
            }
        }
        // echo $totaldata; exit;
        $data['field'] = $field;
        $data['total'] = $totaldata;
        $data['date_from'] = date('m/d/Y',strtotime($from));
        $data['date_to'] = date('m/d/Y',strtotime($to));
        $data['result'] = $result;
        // dd($data);
        return view('servicedata::pdf.datewise', $data)/*->setPaper('a4')->stream('consultation_datwise_'.$type.'.pdf')*/;
    }

   
}
