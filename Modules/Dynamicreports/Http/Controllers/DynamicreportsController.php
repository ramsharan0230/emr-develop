<?php

namespace Modules\Dynamicreports\Http\Controllers;

use App\CogentUsers;
use App\Dynamicreport;
use App\Exports\DynamicReportExport;
use App\PermissionModule;
use App\PermissionReference;
use App\SidebarMenu;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Excel;
use Illuminate\Support\Facades\Auth;

class DynamicreportsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = CogentUsers::where('id', Auth::guard('admin_frontend')->user()->id)->first();
        if(isset($user->user_is_superadmin) && count($user->user_is_superadmin) > 0){
            $data['html'] = $this->getDynamicReportLists();
            $data['mainmenus'] = SidebarMenu::select('mainmenu')->where('status', 0)->distinct()->get();
            return view('dynamicreports::index',$data);
        }else{
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing a new resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['reportData'] = $reportData = Dynamicreport::where('id',$id)->first();
        $data['labels'] = ($reportData->fldlabels) ? json_decode($reportData->fldlabels,true) : [];
        $data['conditions'] = ($reportData->fldconditions) ? json_decode($reportData->fldconditions,true) : [];
        $data['html'] = $this->getDynamicReportLists();
        $data['mainmenus'] = SidebarMenu::select('mainmenu')->where('status', 0)->distinct()->get();
        return view('dynamicreports::edit',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = [
                'mainmenu' => $request->sidebarmenu,
                'status' => 0,
                'submenu' => $request->reportname,
                'route' => "/dynamicreports/".Str::slug($request->reportname, '-'),
                'order_by' => 1
            ];
            $id = SidebarMenu::insertGetId($data);
            $module = [
                'name' => $request->reportname,
                'order_by' => 1,
            ];
            $permissionModule = \App\PermissionModule::create($module);
            $reference = [
                'permission_modules_id' => $permissionModule->id,
                'code' => str_slug($permissionModule->name ?? null, '-'),
                'short_desc' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module,
                'description' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module
            ];
            \App\PermissionReference::create($reference);
            $labels = ($request->labels) ? json_encode($request->labels) : null;
            $conditions = ($request->conditions) ? json_encode($request->conditions) : null;
            $reportdata = [
                'fldreportname' => $request->reportname,
                'fldreportslug' => Str::slug($request->reportname, '-'),
                'fldsidebarmodule' => $request->sidebarmenu,
                'fldquery' => $request->fldquery,
                'fldlabels' => $labels,
                'fldconditions' => $conditions,
                'fldsidebarmenuid' => $id
            ];
            Dynamicreport::insert($reportdata);
            DB::commit();
            Session::flash('success_message','Data saved!!!');
            return redirect()->route('dynamic.report.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error_message', 'Error while saving!!!');
            return redirect()->route('dynamic.report.index');
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $oldmenu = SidebarMenu::where('id', $request->sidebar_menu_id)->first();
            $data = [
                'mainmenu' => $request->sidebarmenu,
                'status' => 0,
                'submenu' => $request->reportname,
                'route' => "/dynamicreports/".Str::slug($request->reportname, '-'),
                'order_by' => 1
            ];
            SidebarMenu::where('id',$request->sidebar_menu_id)->update($data);
            $module = [
                'name' => $request->reportname,
                'order_by' => 1,
            ];
            $permissionModule = \App\PermissionModule::where('name', $oldmenu->submenu)->first();
            \App\PermissionModule::where('name', $oldmenu->submenu)->update($module);
            $reference = [
                'permission_modules_id' => $permissionModule->id,
                'code' => str_slug($permissionModule->name ?? null, '-'),
                'short_desc' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module,
                'description' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module
            ];
            \App\PermissionReference::where('permission_modules_id', $permissionModule->id)->update($reference);
            $labels = ($request->labels) ? json_encode($request->labels) : null;
            $conditions = ($request->conditions) ? json_encode($request->conditions) : null;
            $reportdata = [
                'fldreportname' => $request->reportname,
                'fldreportslug' => Str::slug($request->reportname, '-'),
                'fldsidebarmodule' => $request->sidebarmenu,
                'fldquery' => $request->fldquery,
                'fldlabels' => $labels,
                'fldconditions' => $conditions,
                'fldsidebarmenuid' => $request->sidebar_menu_id
            ];
            Dynamicreport::where('id',$request->dynamic_report_id)->update($reportdata);
            DB::commit();
            Session::flash('success_message','Data updated!!!');
            return redirect()->route('dynamic.report.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error_message', 'Error while updating!!!');
            return redirect()->route('dynamic.report.index');
        }
    }

    public function getDynamicReportLists(){
        $dynamicReports = Dynamicreport::get();
        $html = "";
        foreach($dynamicReports as $key=>$dynamicReport){
            $editRoute = route('dynamic.report.edit',[$dynamicReport->id]);
            $deleteRoute = route('dynamic.report.delete',[$dynamicReport->id]);
            $html .= '<tr>
                        <td>' . ++$key . '</td>
                        <td>' . $dynamicReport->fldreportname . '</td>
                        <td>' . $dynamicReport->fldreportslug . '</td>
                        <td>' . $dynamicReport->fldsidebarmodule . '</td>
                        <td>' . $dynamicReport->fldquery . '</td>
                        <td><a href="'.$editRoute.'" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-edit"></i></a><a href="'.$deleteRoute.'" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></a></td>
                    </tr>';
        }
        return $html;
    }

    public function dynamicReport($reportname)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['reportData'] = $reportData = Dynamicreport::where('fldreportslug',$reportname)->first();
        return view('dynamicreports::dynamic-report',$data);
    }


    public function filterReport(Request $request){
        try {
            $keygen ='';
            $valuegen ='';
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $reportslug = $request->reportslug;
            $reportData = Dynamicreport::where('fldreportslug',$reportslug)->first();
            $query = $reportData->fldquery;
            $query = $this->str_replace_first("?", '"'.$finalfrom.'"', $query);
            $query = str_replace ("?", '"'.$finalto.'"', $query);
            $result = collect(DB::select(DB::raw($query)));
            if(!$request->has('typePdf')){
                $result = $result->paginate(50);
            }
            $labels = collect(json_decode($reportData->fldlabels,true))->where('fieldSelected',"1");
            $fieldArray = [];
            $alignArray = [];
            $thead = "";
            if(count($labels)>0){
                $thead .= "<tr><th>SNo.</th>";
            }
            foreach($labels as $key=>$label){
                $colname = str_replace (" AS ", " as ", $label['colname']);
                $explodeAsArr = explode(" as ",$colname);
                if(count($explodeAsArr)>1){
                    $fieldname = preg_replace("/\s+/", "", $explodeAsArr[1]);
                }else{
                    $explodeDotArr = explode(".",$explodeAsArr[0]);
                    if(count($explodeDotArr)>1){
                        $fieldname = preg_replace("/\s+/", "", $explodeDotArr[1]);
                    }else{
                        $fieldname = preg_replace("/\s+/", "", $explodeDotArr[0]);
                    }
                }
                array_push($fieldArray,$fieldname);
                array_push($alignArray,$label['alignType']);
                $thead .= "<th>".$label['assignedName']."</th>";
            }
            if(count($labels)>0){
                $thead .= "</tr>";
            }
            $tbody = "";
            foreach($result as $key=>$res){
                $tbody .= "<tr>";
                $tbody .= "<td>".++$key."</td>";

                foreach($fieldArray as $fieldKey => $field){
                    if(!empty($alignArray[$fieldKey])){
                        $keygen = $alignArray[$fieldKey];
                    }

                    if(!empty($res->$field)){
                        $valuegen = htmlspecialchars($res->$field);
                    }
                    $tbody .= "<td style='text-align: ".$keygen.";'>".$valuegen."</td>";
                }
                $tbody .= "</tr>";
            }
            if(!$request->has('typePdf')){
                $colspan = count($fieldArray) + 1;
                $tbody .= '<tr><td colspan="'.$colspan.'">' . $result->appends(request()->all())->links() . '</td></tr>';
                return response()->json([
                    'status' => true,
                    'thead' => $thead,
                    'tbody' => $tbody
                ]);
            }else{
                $data['from_date'] = $finalfrom;
                $data['to_date'] = $finalto;
                $data['reportData'] = $reportData;
                $data['thead'] = $thead;
                $data['tbody'] = $tbody;
                return view('dynamicreports::dynamic-report-pdf',$data);
            }
        } catch (\Exception $e) {
            if(!$request->has('typePdf')){
                return response()->json([
                    'status' => false
                ]);
            }else{
                Session::flash('error_message', 'Something went wrong!!!');
                return redirect()->route('dynamic.report.index');
            }
        }
    }

    public function str_replace_first($search, $replace, $subject) {
        return implode($replace, explode($search, $subject, 2));
    }

    public function excelReport(Request $request){
        $export = new DynamicReportExport($request->from_date, $request->to_date, $request->reportslug);
        ob_end_clean();
        ob_start();
        return Excel::download($export, $request->reportslug.'.xlsx');
    }

    public function lists(Request $request){
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['reports'] = Dynamicreport::select('fldreportslug','fldreportname')->distinct('fldreportslug')->get();
        return view('dynamicreports::dynamic-report-lists',$data);
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $reportData = Dynamicreport::where('id',$id)->first();
            $oldmenu = SidebarMenu::where('id', $reportData->fldsidebarmenuid)->first();
            $permissionModule = PermissionModule::where('name', $oldmenu->submenu)->first();
            PermissionReference::where('permission_modules_id', $permissionModule->id)->delete();
            $permissionModule->delete();
            $oldmenu->delete();
            $reportData->delete();
            DB::commit();
            Session::flash('success_message', 'Successfully Deleted!!!');
            return redirect()->route('dynamic.report.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', 'Something went wrong!!!');
            return redirect()->route('dynamic.report.index');
        }
    }
}
