<?php

namespace Modules\Employee\Http\Controllers;

use App\Family;
use App\Municipal;
use App\PatientInfo;
use App\StaffList;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Helper;

class EmployeeController extends Controller
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

        return view('employee::employes',$data);
    }

    public  function getEmployeeDetails(Request $request,$id)
    {
//        dd($id);

        $search_ype = $request->get('search_type');

//        if(!$search_ype && $search_ype='')
//        {
//            return \response(['error'=>'Please enter value first!!']);
//        }
        try {
            if($search_ype=='code')
            {
                $codes = StaffList::where('fldptcode', 'like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
//                    ->take(50)
                    ->get();
                $html ='';
                if($codes){
                    foreach ($codes as $code)
                    {
                        $html .='<tr class="patientTr" data-code="'.$code->fldptcode.'" >';
                        $html .= '<td>' . $code->fldptcode . '</td>';
                        $html .= '<td>'.$code->fldptnamefir.' '.$code->fldmidname.'</td>';
                        $html .= '<td>'.$code->fldptnamelast.'</td>';
                        $html .='</tr>';
                    }
                    return $html;
                }
                return $html.= '<tr><td> No data available</td></tr>';

            }elseif ($search_ype=='name')
            {

                $names=  StaffList::where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
//                    ->take(50)
                    ->get();
                $html ='';
                if($names){
                    foreach ($names as $name)
                    {
                        $html .='<tr class="patientTr" data-code="'.$name->fldptcode.'" >';
                        $html .= '<td>' . $name->fldptcode . '</td>';
                        $html .= '<td>'.$name->fldptnamefir.' '.$name->fldmidname.'</td>';
                        $html .= '<td>'.$name->fldptnamelast ?? null .'</td>';
                        $html .='</tr>';
                    }
                    return $html;
                }
                return $html.= '<tr><td> No data available</td></tr>';

            }elseif ($search_ype=='sur_name'){

                $sur_names = StaffList::where('fldptnamelast','like', '%' . $id . '%')
//                    ->where('fldstatus', 'Active')
                    ->get();
                $html ='';
                if($sur_names){
                    foreach ($sur_names as $sur_name)
                    {
                        $html .='<tr class="patientTr" data-code="'.$sur_name->fldptcode.'" >';
                        $html .= '<td>' . $sur_name->fldptcode . '</td>';
                        $html .= '<td>'.$sur_name->fldptnamefir.' '.$sur_name->fldmidname.'</td>';
                        $html .= '<td>'.$sur_name->fldptnamelast ?? null .'</td>';
                        $html .='</tr>';
                    }
                    return $html;
                }
                return $html.= '<tr><td> No data available</td></tr>';
            }else{

                $patient = StaffList::where('fldptcode', $id)->with([
                    'patientInfo:fldptcode,fldpatientval,flddiscount',
                    ])
                    ->first();

                if($patient)
                {
                    return $patient;
                }
                return  \response(['error'=>'Not Found']);
            }
        }catch (\Exception $exception){
            dd($exception);
            return  \response('Something Went Wrong');
        }

//        data-name="'.$code->fldptnamefir.''.$code->fldmidname.''.$code->fldptnamelast.'" data-surname="'.$code->fldptnamelast.'"
//        data-name="'.$sur_name->fldptnamefir.''.$sur_name->fldmidname.''.$sur_name->fldptnamelast.'" data-surname="'.$sur_name->fldptnamelast.'"
//        data-name="'.$name->fldptnamefir.''.$name->fldmidname.''.$name->fldptnamelast.'" data-surname="'.$name->fldptnamelast.'"
    }

    public function saveDetails( Request  $request)
    {
        $data =[];
        $datetime = date('Y-m-d H:i:s');
        $patientNo = $request->get('patient_no');
        //Update discount in tblpatientinfo
        $patientID = $request->get('patientNo');
        if($patientID)
        {
            $ifexist = PatientInfo::where('fldpatientval', $patientID)->first();
            if($ifexist)
            {
                $data = [
                    'flddiscount' => $request->get('discount'),
                ];
               PatientInfo::where('fldpatientval', $patientID)->update($data);
            }
        }
        $patient = StaffList::where('fldptcode', $patientNo)->first();
        if ($patient)
        {
            $data =[
//                'fldptcode' => $request->get('patient_no'),
                'fldcategory' => $request->get('patient_type'),
                'fldptnamefir' => $request->get('first_name'),
                'fldmidname' => $request->get('middle_name'),
                'fldptnamelast' => $request->get('last_name'),
                'fldunit' => $request->get('unit'),
                'fldrank' => $request->get('rank'),
                'fldptsex' => $request->get('gender'),
                'fldptbirday' => $request->get('dob'),
                'fldptcontact' => $request->get('contact'),
                'fldemail' => $request->get('email'),
                'fldptadddist' => $request->get('district'),
                'fldptaddvill' => $request->get('address'),
                'fldcontype' => $request->get('blood_group'),
                'fldcitizen' => $request->get('citizen'),
                'fldidentify' => $request->get('marks'),
                'flddept' =>$request->get('service'),
                'fldstatus' => $request->get('status'),
                'fldremark' => $request->get('remarks'),
                'fldjoindate' => $request->get('join_date'),
                'fldenddate' => $request->get('end_date'),
//                'fldpatienttype' => $request->get('patient_type'),
                'fldpost' => $request->get('patient_status'),
                'fldopdno' => $request->get('opdNo'),
                'flduser' =>Helpers::getCurrentUserName(),
                'fldtime' =>$datetime,

            ];
            StaffList::where('fldptcode',$patientNo)->update($data);
               return redirect()->back();
        }

        if (!$patient)
            {
               $data =[
                   'fldptcode' => $request->get('patient_no'),
                   'fldcategory' => $request->get('patient_type'),
                   'fldptnamefir' => $request->get('first_name'),
                   'fldmidname' => $request->get('middle_name'),
                   'fldptnamelast' => $request->get('last_name'),
                   'fldunit' => $request->get('unit'),
                   'fldrank' => $request->get('rank'),
                   'fldptsex' => $request->get('gender'),
                   'fldptbirday' => $request->get('dob'),
                   'fldptcontact' => $request->get('contact'),
                   'fldemail' => $request->get('email'),
                   'fldptadddist' => $request->get('district'),
                   'fldptaddvill' => $request->get('address'),
                   'fldcontype' => $request->get('blood_group'),
                   'fldcitizen' => $request->get('citizen'),
                   'fldidentify' => $request->get('marks'),
                   'flddept' =>$request->get('service'),
                   'fldstatus' => $request->get('status'),
                   'fldremark' => $request->get('remarks'),
                   'fldjoindate' => $request->get('join_date'),
                   'fldenddate' => $request->get('end_date'),
//                'fldpatienttype' => $request->get('patient_type'),
                   'fldpost' => $request->get('patient_status'),
                   'fldopdno' => $request->get('opdNo'),
                   'flduser' =>Helpers::getCurrentUserName(),
                   'fldtime' =>$datetime,

               ];
                StaffList::create($data);
                return redirect()->back();
            }


    }

    public  function delete($id)
    {
        if(!$id)
        {
            return \response('Something Went Wrong');
        }
        $patient = StaffList::where('fldptcode',$id)->first();
        if($patient){
            StaffList::where('fldptcode',$id)->delete();
            return  \response('Deleted SUccessfully');
        }
        return  \response('Patient No Not Found');

    }
}
