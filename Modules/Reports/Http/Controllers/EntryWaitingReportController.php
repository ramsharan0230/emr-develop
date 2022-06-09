<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\EntryWaitingExport;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\Utils\Helpers;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EntryWaitingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('reports::entrywaiting.entry-waiting-report', $data);
    }

    public function getRefreshData(Request $request)
    {
        $data['type'] = $type = ($request->type == 'notSaved') ? 0 : 1;
        $data['comp'] = $comp = $request->comp;
        $data['user'] = $user = $request->username;
        $result = PatBilling::select('fldid', 'fldencounterval', 'flditemtype', 'flditemname', 'flditemrate', 'flditemqty', 'fldorduserid', 'fldordcomp', 'fldordtime', 'flduserid', 'fldcomp', 'fldtime', 'fldbillno')
            ->when($request->type == "notSaved", function ($q) use ($user) {
                return $q->where('fldprint', 0);
            })
            ->when($request->type == "notBilled", function ($q) use ($user) {
                return $q->where('fldbillno', NULL);
            })
            ->where('fldsave', $type)
            ->when($user != "" && $request->type == "notSaved", function ($q) use ($user) {
                return $q->whereRaw('LOWER(`fldorduserid`) LIKE ? ', [trim(strtolower($user)) . '%']);
            })
            ->when($user != "" && $request->type == "notBilled", function ($q) use ($user) {
                return $q->whereRaw('LOWER(`flduserid`) LIKE ? ', [trim(strtolower($user)) . '%']);
            })
            ->when($comp != "%", function ($q) use ($comp) {
                return $q->where('fldcomp', 'like', $comp);
            });
        $result = ($request->has('isExport')) ? $result->get() : $result->paginate(15);
        $data['result'] = $result;
        $html = "";
        foreach ($result as $key => $r) {
            $user = ($request->type == 'notSaved') ? $r->fldorduserid : $r->flduserid;
            $user_comp = ($request->type == 'notSaved') ? $r->fldordcomp : $r->fldcomp;
            $date = ($request->type == 'notSaved') ? $r->fldordtime : $r->fldtime;
            $html .= '<tr>
                        <td>' . ++$key . '</td>
                        <td>' . $r->fldencounterval . '</td>
                        <td>' . $r->flditemtype . '</td>
                        <td>' . $r->flditemname . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($r->flditemrate) . '</td>
                        <td>' . $r->flditemqty . '</td>
                        <td>' . $user . '</td>';
            if($user_comp){
                $html .= '<td>' . Helpers::getDepartmentFromCompID($user_comp) . '</td>';
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td>' . $date . '</td>
                    </tr>';
        }
        $data['html'] = $html;
        if (!$request->has('isExport')) {
            $html .= '<tr><td colspan="9">' . $result->appends(request()->all())->links() . '</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);
        } else {
            return view('reports::entrywaiting.entry-waiting-pdf', $data);
        }
    }

    public function exportExcel(Request $request)
    {
        // try {
            $user = ($request->username != null) ? $request->username : "";
            $export = new EntryWaitingExport($request->type, $request->comp, $user);
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'EntryWaitingReport.xlsx');
        // }catch (\Exception $e){
        //     \Log::info($e->getMessage());
        // }

    }
}
