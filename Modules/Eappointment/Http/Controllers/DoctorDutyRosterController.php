<?php

namespace Modules\Eappointment\Http\Controllers;

use App\Encounter;
use App\PatientInfo;
use App\Eappointment;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DoctorDutyRosterController extends Controller
{
    private $api_key;
    private $eurl;

    public function __construct(){
        $this->api_key = Options::get('e_appointment_hmac_key') ? Options::get('e_appointment_hmac_key') : Options::get('e_appointment_hmac_key');
        $this->eurl = Options::get('e_appointment_url') ? Options::get('e_appointment_url') : Options::get('e_appointment_url');
    }

    private function _apiCall($url, $method, $data = null)
    {
        try {
            \Log::info(json_encode([$url, $method, $data ? json_decode($data) : ""]));

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: '.$this->api_key,
            ];

            $curl_connection = curl_init($url);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
            if($method == "PUT") curl_setopt($curl_connection, CURLOPT_CUSTOMREQUEST, "PUT");

            if($method == "DELETE") curl_setopt($curl_connection, CURLOPT_CUSTOMREQUEST, "DELETE");

            //set data to be posted
            if($method != "GET") curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $data);

            // set headers
            curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $headers);

            //perform our request
            $result = curl_exec($curl_connection);

            \Log::info($result);

            //close the connection
            curl_close($curl_connection);

            return json_decode($result);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public function getSpecializations()
    {
       $args = [];
        $emrurl = $this->eurl . 'specialization/active/min';
        $response = $this->_apiCall($emrurl, 'GET', $args);
        return $response;

    }

    public function getSpecializationDoctor(Request  $request){
        try{
            $args = [];
            $emrurl = $this->eurl . 'doctor/specialization-wise/'.$request->specialization_id;
            $response = $this->_apiCall($emrurl, 'GET', $args);
            return $response;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function doctorDutyRoster()
    {
        $specializations = $this->getSpecializations();

        return view('eappointment::doctor-duty-roster',compact('specializations'));
    }

    public function addDoctorDutyRoster(Request $request){
        try{
            $data = json_encode($request->all());
        $emrurl = $this->eurl . 'doctorDutyRoster';
        $response = $this->_apiCall($emrurl, 'POST', $data);
        return $response;
    }catch (\Exception $e){
        \Log::info($e->getMessage());
        return $e;
    }
    }

    public function getDoctorDutyRoster(Request $request){
        try{
        if(isset($request->from_date)){
            $from_date = $request->from_date;
        }else{
            $from_date = Carbon::now();
        }

        if(isset($request->to_date)){
            $to_date = $request->from_date;
        }else{
            $to_date = Carbon::now();
        }

        if(isset($request->doctorId)){
            $doctorId = $request->doctorId;
        }else{
            $doctorId = '';
        }


        if(isset($request->specializationId)){
            $specializationId = $request->specializationId;
        }else{
            $specializationId = '';
        }
        $data = json_encode([
            'fromDate' => $from_date,
            'toDate' => $to_date,
            'doctorId' => $doctorId,
            'specializationId' => $specializationId,
            'status' => 'Y'
        ]);
        $emrurl = $this->eurl . 'doctorDutyRoster/search?page=1&size=10';
        $response = $this->_apiCall($emrurl, 'PUT', $data);
        $html = '';
		    $html .='<table id="myTable1"
            data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
        >
            <thead class="thead-light">
                <tr>
                <th>SN</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Time Duration (minutes)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
                        <tbody >';
                        if(is_array($response)){
                            if(isset($response) and count($response) > 0){
                                foreach($response as $k=>$data){
                                    if($data->status == 'Y'){
                                        $status = ' <i class="fa fa-circle" style="color: green"></i>&nbsp;Active';
                                    }else{
                                        $status = '<i class="fa fa-circle" style="color: lightgrey"></i>&nbsp;Inactive';
                                    }
                                   
                                    $sn = $k+1;
                                    $html .='<tr>';
                                    $html .='<td>'.$sn.'</td>';
                                    // $html .='<td><input type="checkbox" class="autobilling" value="'.$data->fldid.'">'.$sn.'</td>';
                                    $html .='<td>'.$data->doctorName.'</td>';
                                    $html .='<td>'.$data->specializationName.'</td>';
                                    $html .='<td>'.$data->fromDate.'</td>';
                                    $html .='<td>'.$data->toDate.'</td>';
                                    $html .='<td>'.$data->rosterGapDuration.'</td>';
                                    $html .='<td>'.$status.'</td>';
                                    $html .='<td><a href="javascript:void(0);" class="iq-bg-danger" onclick="deleteDDR('.$data->id.')"><i class="ri-delete-bin-5-fill"></i></a>&nbsp;&nbsp;
                                    </td>';
                                    $html .='</tr>';
                                }
                                $html .=' </tbody></table>';
                                return $html;
                            }
                        }
                       
    }catch (\Exception $e){
        \Log::info($e->getMessage());
        return $e;
    }
    }

    public function deleteDoctorDutyRoster(Request $request){
        try{

            $data = json_encode([
                'id' => $request->id,
                'remarks' => 'done',
                'status' => 'D',
            ]);
            $emrurl = $this->eurl . 'doctorDutyRoster';
            $response = $this->_apiCall($emrurl, 'DELETE', $data);
        if(isset($request->from_date)){
            $from_date = $request->from_date;
        }else{
            $from_date = Carbon::now();
        }

        if(isset($request->to_date)){
            $to_date = $request->from_date;
        }else{
            $to_date = Carbon::now();
        }
        $data = json_encode([
            'fromDate' => $from_date,
            'toDate' => $to_date,
            'doctorId' => '',
            'specializationId' => '',
            'status' => ''
        ]);
        $emrurl = $this->eurl . 'doctorDutyRoster/search?page=1&size=10';
        $response = $this->_apiCall($emrurl, 'PUT', $data);
        $html = '';
		    $html .='<table id="myTable1"
            data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
        >
            <thead class="thead-light">
                <tr>
                <th>SN</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Time Duration (minutes)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
                        <tbody >';
                        if(isset($response) and count($response) > 0){
                            foreach($response as $k=>$data){
                                if($data->status == 'Y'){
                                    $status = ' <i class="fa fa-circle" style="color: green"></i>&nbsp;Active';
                                }else{
                                    $status = '<i class="fa fa-circle" style="color: lightgrey"></i>&nbsp;Inactive';
                                }
                               
                                $sn = $k+1;
                                $html .='<tr>';
                                $html .='<td>'.$sn.'</td>';
                                // $html .='<td><input type="checkbox" class="autobilling" value="'.$data->fldid.'">'.$sn.'</td>';
                                $html .='<td>'.$data->doctorName.'</td>';
                                $html .='<td>'.$data->specializationName.'</td>';
                                $html .='<td>'.$data->fromDate.'</td>';
                                $html .='<td>'.$data->toDate.'</td>';
                                $html .='<td>'.$data->rosterGapDuration.'</td>';
                                $html .='<td>'.$status.'</td>';
                                $html .='<td><a href="javascript:void(0);" class="iq-bg-danger" onclick="deleteDDR('.$data->id.')"><i class="ri-delete-bin-5-fill"></i></a>&nbsp;&nbsp;
                                </td>';
                                $html .='</tr>';
                            }
                            $html .=' </tbody></table>';
                            return $html;
                        }
    }catch (\Exception $e){
        \Log::info($e->getMessage());
        return $e;
    }
    }

}
