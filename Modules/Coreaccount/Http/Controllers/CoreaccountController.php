<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountGroup;
use App\Exports\AccountGroupReportExport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CoreaccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('coreaccount::index');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function subgroup()
    {
        $data['groups'] = AccountGroup::where('ParentId', 0)->with('children')->get();
        $data['allgroup'] = AccountGroup::select('GroupName')->get();
        return view('coreaccount::subgroup', $data);
    }

    public function listSubGroup(Request $request)
    {
        $data['parentId'] = $request->groupId;
        $data['groups'] = AccountGroup::where('ParentId', $data['parentId'])->get();

        if (is_countable($data['groups']) && count($data['groups'])) {
            return view('coreaccount::sub-group.sub-group-dynamic', $data)->render();
        }

        return "<small>No Data</small>";

    }

    /**
     * Show the form for creating a new resource.
     * @return array
     */
    public function addGroup(Request $request)
    {
        try {
            $result = AccountGroup::where('GroupName', $request->group_name)->first();

            if (isset($result)) {
                // echo "here"; exit;
                $data['GroupName'] = $request->sub_group_name;
                $data['GroupNameNep'] = $request->nepali_sub_group_name;
                $maxreportid = AccountGroup::max('ReportId');
                $data['ReportId'] = $maxreportid + 1;
                $data['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $data['parentId'] = $result->GroupId;
                $sameparent = AccountGroup::where('parentId', $result->GroupId)->get();

                if (isset($sameparent) and count($sameparent) > 0) {

                    $string = $sameparent->last()->GroupTree;

                    // $remainingstr = substr($string, 0, -1);
                    $remainingstr = substr($string, 0, strrpos( $string, '.'));


                    $toadd = explode('.',$string);

                    $lastchar = end($toadd);
                    $last = $lastchar + 1;
                    $tree = $remainingstr.'.'.$last;
                    // echo $tree; exit;
                } else {
                    // if(strlen($result->GroupTree) == 1){
                    //     $tree = $result->ParentId.'.1';
                    // }else{
                    //     $tree = $result->GroupTree.'.1';
                    // }
                    $tree = $result->GroupTree . '.1';
                }
                // echo $tree; exit;
                $data['GroupTree'] = $tree;

            } else {
                // echo "elsema"; exit;
                $data['GroupName'] = $request->group_name;
                $data['GroupNameNep'] = $request->group_name;
                $maxreportid = AccountGroup::max('ReportId');
                if (isset($maxreportid) and $maxreportid != '') {
                    $data['ReportId'] = $maxreportid + 1;
                } else {
                    $data['ReportId'] = 1;
                }

                $data['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $maxValue = AccountGroup::max('GroupTree');
                // echo $maxValue; exit;
                $data['parentId'] = 0;
                if (isset($maxValue) and $maxValue != '') {
                    // echo "eta"; exit;
                    $data['GroupTree'] = substr($maxValue, 0, 1) + 1;

                } else {
                    // echo "teta"; exit;
                    $data['GroupTree'] = 1;
                }

            }

            AccountGroup::create($data);

            $htmlresult = AccountGroup::all();
            $html = '';
            $grouphtml = '';
//            if (isset($htmlresult) and count($htmlresult) > 0) {
//                foreach ($htmlresult as $k => $r) {
//                    $html .= '<tr>';
//                    $html .= '<td class="text-center">' . ($k + 1) . '</td>';
//                    if ($r->ParentId == 0) {
//                        $html .= '<td class="text-center">' . $r->GroupName . '</td>';
//                        $html .= '<td class="text-center"></td>';
//                        $html .= '<td class="text-center"></td>';
//                        /*$html .='<td class="text-center"><a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
//                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a></td>';*/
//                    } else {
//
//                        $pid = explode('.', $r->GroupTree);
//                        $nature = json_decode(json_encode(AccountGroup::where('GroupTree', $pid[0])->first()), true);
//
//                        $name = json_decode(json_encode(AccountGroup::where('GroupId', $r->GroupId)->first()), true);
//                        $remainingstr = substr($r->GroupTree, 0, -2);
//                        $subgroup = json_decode(json_encode(AccountGroup::where('GroupTree', $remainingstr)->first()), true);
//                        $html .= '<td class="text-center">' . $nature['GroupName'] . '</td>';
//                        $html .= '<td class="text-center">' . $subgroup['GroupName'] . '</td>';
//                        $html .= '<td class="text-center">' . $name['GroupName'] . '</td>';
//                        /*$html .='<td class="text-center"><a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
//                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a></td>';*/
//                    }
//
//
//                }
//
//            }

            if (isset($htmlresult) and count($htmlresult) > 0) {
                foreach ($htmlresult as $hr) {
                    $grouphtml .= '<option value="' . $hr->GroupName . '">' . $hr->GroupName . '</option>';
                }
            }

            $datas['html'] = '';
            $datas['grouphtml'] = $grouphtml;

            return $datas;
        } catch (\Exception $e) {
//            dd($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function exportGroup(Request $request)
    {
        $export = new AccountGroupReportExport();
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'AccountGroup.xlsx');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('coreaccount::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('coreaccount::edit');
    }

}
