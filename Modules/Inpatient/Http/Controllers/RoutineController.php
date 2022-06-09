<?php

namespace Modules\Inpatient\Http\Controllers;

use App\NurseDosing;
use App\Pathdosing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use Exception;

class RoutineController extends Controller
{
    public function onclick(Request $request) {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
            'fldendtime' => NULL,
        ])->where('fldroute', '!=', 'fluid')
        ->where('fldfreq', '!=', 'stat')
        ->where('fldfreq', '!=', 'PRN')
        ->with('medBrand:fldvolunit,fldbrandid')
        ->orderBy('fldid', 'DESC')
        ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
        ->get();

        if ($request->get('value') == 'label') {
          foreach ($get_patdosing as &$list_detail) {
              $temp_data = \DB::select('select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid=?)', [$list_detail->flditem]);
              $list_detail->flddose = $list_detail->flddose/$temp_data[0]->fldstrength . ' ' . $list_detail->medBrand->fldvolunit;
          }
        }

        return response()->json($get_patdosing);
    }

    public function listall() {
       $get_patdosing = Pathdosing::where([
           'fldencounterval' => Input::get('fldencounterval'),
           'fldsave_order' => 1,
           'flditemtype' => 'Medicines',
           'flddispmode' => 'IPD',
       ])->where('fldroute', '!=', 'fluid')
       ->where('fldfreq', '!=', 'stat')
       ->where('fldfreq', '!=', 'PRN')
       ->orderBy('fldid', 'DESC')
       ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
       ->get();
       return response()->json($get_patdosing);
    }

    public function showMedicineRoutine() {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
        ->where('fldfreq', '!=', 'stat')
        ->where('fldfreq', '!=', 'PRN')
        ->orderBy('fldid', 'DESC')
        ->select('flditem', 'fldid')
        ->get();
        return response()->json($get_patdosing);
    }

    public function showMedicineDetails() {
        $get_patdosing = Pathdosing::where([
            'fldid' => Input::get('fldid'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
        ->where('fldfreq', '!=', 'stat')
        ->where('fldfreq', '!=', 'PRN')
        ->orderBy('fldid', 'DESC')
        ->select('flditem', 'flddose', 'fldroute')
        ->get();
        return response()->json($get_patdosing);
    }

    // When input:value clicked
    public function showValue() {
        $data = Input::get('value');
        $date = Input::get('list_date') ?: '2020-04-01';
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $from = $date . ' 00:00:00';
        $to = $date . ' 23:59:59.99';
        $get_list_detail = Pathdosing::whereBetween('fldstarttime', [$from, $to])->where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
        ->where('fldfreq', '!=', 'stat')
        ->where('fldfreq', '!=', 'PRN')
        ->with('medBrand:fldvolunit,fldbrandid')
        ->orderBy('fldid', 'DESC')
        ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
        ->get();

        if ($data == 'label') {
            /*
            select flditemtype,fldroute,flditem,flddose from tblpatdosing where fldid=50368
            select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid='Diclofenac Na -75mg/ml (DICINAC 75 MG)')
            select fldvolunit from tblmedbrand where fldbrandid='Diclofenac Na -75mg/ml (DICINAC 75 MG)'
            */
            foreach ($get_list_detail as &$list_detail) {
                $temp_data = \DB::select('select fldstrength from tbldrug where flddrug in(select flddrug from tblmedbrand where fldbrandid=?)', [$list_detail->flditem]);
                $list_detail->flddose = $list_detail->flddose/$temp_data[0]->fldstrength . ' ' . $list_detail->medBrand->fldvolunit;
            }
        }

        return response()->json($get_list_detail);
    }

    public function listByDate() {
        $related_list = Input::get('list_date');
        $from = $related_list . ' 00:00:00';
        $to = $related_list . ' 23:59:59.99';
        $get_list_detail = Pathdosing::whereBetween('fldstarttime', [$from, $to])->where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
        ->where('fldfreq', '!=', '%stat%')
        ->where('fldfreq', '!=', '%PRN%')
        ->orderBy('fldid', 'DESC')
        ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
        ->get();
        return response()->json($get_list_detail);
    }

    public function getStatus() {
      $fldid = Input::get('fldid');
      $get_list_detail = Pathdosing::where('fldid', $fldid)->select('fldid', 'flditem', 'flddays')->get();
      return response()->json($get_list_detail);
    }

    public function changeStatus(Request $request) {
       try {
          $data = array(
            'fldcurval' => $request->fldcurval,
          );
          $table = Pathdosing::where([
              'fldid' => $request->fldid,
              'fldencounterval' => $request->fldencounterval
          ])->first();
          $latest_id = $table->update($data);
          if ($latest_id) {
              Session::flash('display_popup_error_success', true);
              Session::flash('success_message', 'Complaint update Successfully.');
              return response()->json([
                   'success' => [
                       'id'   => $latest_id
                   ]
              ]);
          }else {
              Session::flash('display_popup_error_success', true);
              Session::flash('error_message', 'Sorry! something went wrong');
              return response()->json([
                  'error' => [
                      'message' => __('messages.error')
                  ]
              ]);
          }
      } catch (\GearmanException $e) {
          Session::flash('display_popup_error_success', true);
          Session::flash('error_message', 'Sorry! something went wrong');
          return redirect()->route('patient');
      }
    }

    public function changeDays(Request $request) {
       try {
          $datas = array(
            'flddays' => $request->flddays,
          );
          $table = Pathdosing::where([
              'fldid' => $request->fldid,
              'fldencounterval' => $request->fldencounterval
          ])->first();
          $table->flddays = $request->flddays;
          $table->update();
          if ($table) {
              Session::flash('display_popup_error_success', true);
              Session::flash('success_message', 'Complaint update Successfully.');
              return response()->json([
                   'success' => [
                       'id'   => $table
                   ]
              ]);
          }else {
              Session::flash('display_popup_error_success', true);
              Session::flash('error_message', 'Sorry! something went wrong');
              return response()->json([
                  'error' => [
                      'message' => __('messages.error')
                  ]
              ]);
          }
      } catch (\GearmanException $e) {
          Session::flash('display_popup_error_success', true);
          Session::flash('error_message', 'Sorry! something went wrong');
          return redirect()->route('patient');
      }
    }

    public function getChangeDays()
    {
      $fldid = Input::get('fldid');
      $get_change_day = Pathdosing::where('fldid', $fldid)
      ->select('flddays')
      ->first();
      return response()->json($get_change_day);
    }

    public function getChangeStatus()
    {
      $fldid = Input::get('fldid');
      $get_change_day = Pathdosing::where('fldid', $fldid)
      ->select('fldcurval')
      ->first();
      return response()->json($get_change_day);
    }
}
