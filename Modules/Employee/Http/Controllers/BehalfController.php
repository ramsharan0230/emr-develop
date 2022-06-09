<?php

namespace Modules\Employee\Http\Controllers;

use App\Family;
use App\StaffList;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BehalfController extends Controller
{

    public function index()
    {

        $data = [
            'billingModes' => Helpers::getBillingModes(),
//            'countries' => Helpers::getCountries(),
            'discounts' => Helpers::getDiscounts(),
            'genders' => Helpers::getGenders(),
            'bloodGroups' => Helpers::getBloodGroups(),
            'services' => Helpers::getService(),
            'todaydate' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
        return view('employee::behalf.behalf', $data);
    }

    public function getEmployeeDetails(Request $request, $id)
    {
//        dd($id);

        $search_ype = $request->get('search_type');

//        if(!$search_ype && $search_ype='')
//        {
//            return \response(['error'=>'Please enter value first!!']);
//        }
        try {
            if ($search_ype == 'code') {
                $codes = StaffList::where('fldptcode', 'like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
                    ->take(200)
                    ->get();
                $html = '';
                if ($codes) {
                    foreach ($codes as $code) {
                        $html .= '<tr class="patientTr" data-code="' . $code->fldptcode . '">';
                        $html .= '<td>' . $code->fldptcode . '</td>';
                        $html .= '<td>' . $code->fldptnamefir . ' ' . $code->fldmidname . '</td>';
                        $html .= '<td>' . $code->fldptnamelast . '</td>';
                        $html .= '</tr>';
                    }
                    return $html;
                }
                return $html .= '<tr><td> No data available</td></tr>';

            } elseif ($search_ype == 'name') {

                $names = StaffList::where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
//                    ->take(200)
                    ->get();
                $html = '';
                if ($names) {
                    foreach ($names as $name) {
                        $html .= '<tr class="patientTr" data-code="' . $name->fldptcode . '">';
                        $html .= '<td>' . $name->fldptcode . '</td>';
                        $html .= '<td>' . $name->fldptnamefir . ' ' . $name->fldmidname . '</td>';
                        $html .= '<td>' . $name->fldptnamelast ?? null . '</td>';
                        $html .= '</tr>';
                    }
                    return $html;
                }
                return $html .= '<tr><td> No data available</td></tr>';

            } elseif ($search_ype == 'sur_name') {

                $sur_names = StaffList::where('fldptnamelast', 'like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
                    ->get();
                $html = '';
                if ($sur_names) {
                    foreach ($sur_names as $sur_name) {
                        $html .= '<tr class="patientTr" data-code="' . $sur_name->fldptcode . '" >';
                        $html .= '<td>' . $sur_name->fldptcode . '</td>';
                        $html .= '<td>' . $sur_name->fldptnamefir . ' ' . $sur_name->fldmidname . '</td>';
                        $html .= '<td>' . $sur_name->fldptnamelast ?? null . '</td>';
                        $html .= '</tr>';
                    }
                    return $html;
                }
                return $html .= '<tr><td> No data available</td></tr>';
            } else {
                $family_html = '';
                $patient = StaffList::where('fldptcode', $id)
                    ->with(['familyDetails:fldparentcode,fldptcode,fldptnamefir,fldmidname,fldptnamelast,fldrelation,fldptbirday,fldcontype,fldregdate',])
                    ->first();

                if ($patient->familyDetails) {
//
                    foreach ($patient->familyDetails as $familyDetail) {
                        $family_html .= '<tr>';
                        $family_html .= '<td align="center">' . $familyDetail->fldptnamefir . ' ' . $familyDetail->fldmidname . ' ' . $familyDetail->fldptnamelast . '</td>';
                        $family_html .= '<td align="center">' . $familyDetail->fldptcode . '</td>';
                        $family_html .= '<td align="center">' . (($familyDetail->fldregdate) ? (Carbon::parse($familyDetail->fldregdate)->format('Y-m-d')) : null) . '</td>';
                        $family_html .= '<td align="center">' . $familyDetail->fldrelation . '</td>';
                        $family_html .= '<td align="center">' . (($familyDetail->fldptbirday) ? (Carbon::parse($familyDetail->fldptbirday)->format('Y-m-d')) : null) . '</td>';
                        $family_html .= '<td align="center">' . (($familyDetail->fldptbirday) ? (Carbon::parse($familyDetail->fldptbirday)->age) : null) . '</td>';
                        $family_html .= '<td align="center">' . $familyDetail->fldcontype . '</td>';
                        $family_html .= '<td align="center">' . $familyDetail->fldparentcode . '</td>';
                        $family_html .= '</tr>';
                    }
                }


                if ($patient) {
                    return ['data' => $patient, 'family_detail' => $family_html];
                }

                return \response(['error' => 'Not Found']);
            }
        } catch (\Exception $exception) {
            dd($exception);
            return \response('Something Went Wrong');
        }
//        data-name="'.$sur_name->fldptnamefir.''.$sur_name->fldmidname.''.$sur_name->fldptnamelast.'"
//        data-surname="'.$sur_name->fldptnamelast.'"

    }

    public function updateStatus(Request $request, $id)
    {
        $type = $request->get('type');
        if (!$type) {
            return \response(['error' => 'Problem with type']);
        }
        try {
            if ($id != '' || $id != null) // because there is an encounter with value 0
            {
                $staff = StaffList::where('fldptcode', $id)
                    ->with(['familyDetails:fldparentcode,fldptcode',])
                    ->first();
                if ($staff) {
                    StaffList::where('fldptcode', $id)->update([
                        'fldoldcondition' => $type,
                        'fldstatus' => 'Inactive',
                        'fldpost' => ($type == 'upadan') ? 'Gratuity' : null,]);
                }
                if ($staff->familyDetails) {
                    foreach ($staff->familyDetails as $family) {
                        Family::where('fldparentcode', $family->fldparentcode)->update([
                            'fldstatus' => 'Inactive'
                        ]);
                    }
                }
                return \response('Updated Successfully');
            } else {
                return \response(['error' => 'Problem with Computer/patta no']);
            }
        } catch (\Exception $exception) {
            dd($exception);
            return \response(['error', 'Something went wrong']);
        }


    }

    public function updatePatta(Request $request, $id)
    {
        $newpatta_no = $request->get('newpatta_no');

        if ($newpatta_no != '' || $newpatta_no != null) {

            $ifExist = StaffList::where('fldptcode', $newpatta_no)->first();
            if ($ifExist) {
                return \response('Computer/Patta No exists please choose another');
            }
        }
        try {

            if ($id != '' || $id != null) // because there is an encounter with value 0
            {
                $staff = StaffList::where('fldptcode', $id)
                    ->with(['familyDetails:fldparentcode,fldptcode',])
                    ->first();
//                if ($staff) {
//                    StaffList::where('fldptcode', $id)->update(['fldparentcode' => $newpatta_no, 'fldptcode' => $newpatta_no]);
//                }
                if ($staff->familyDetails) {
//                    dd($staff->familyDetails);
                    $test = [] ;
                    foreach ($staff->familyDetails as $family) {
                        $test[] = explode('-',$family->fldptcode) ;
//                        Family::where('fldparentcode', $family->fldparentcode)->update([
//                            'fldstatus' => 'Inactive'
//                        ]);
                    }
//                    REPLACE(str, find_string, replace_with)
                    dd($test);

                }
                return \response('Updated Successfully');
            } else {
                return \response(['error' => 'Problem with Computer/patta no']);
            }
        } catch (\Exception $exception) {
            dd($exception);
            return \response(['error', 'Something went wrong']);
        }
    }


}
