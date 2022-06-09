<?php

namespace Modules\Eappointment\Http\Controllers;

use App\Encounter;
use App\ServiceCost;
use App\AppointmentCharge;
use App\PatientInfo;
use App\Eappointment;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServiceSetupController extends Controller
{
    private $api_key;
    private $eurl;

    public function __construct(){
        $this->api_key = Options::get('e_appointment_hmac_key') ? Options::get('e_appointment_hmac_key') : Options::get('e_appointment_hmac_key');
        $this->eurl = Options::get('e_appointment_url') ? Options::get('e_appointment_url') : Options::get('e_appointment_url');
    }

    public function addService(Request $request){
        try{
            $has_charge =  AppointmentCharge::where('eapp_service_id',$request->service_id)
            ->where('eapp_service_name',$request->service_name)
            ->first();

          
            if(!$has_charge){
                $appointment_charge = new AppointmentCharge();
                $appointment_charge->eapp_service_name = $request->new_service_name;
                $appointment_charge->eapp_service_id = 0;
                $appointment_charge->tblservicecost_id = $request->item_id;
                $appointment_charge->save();
                
                $this->addEditService($request,'POST',$appointment_charge->id);
                return redirect()->back(); 
            }else{
                $request->session()->flash( 'error_message', 'Already Added' );
                    return redirect()->back();
            }
        }catch(\Exception $e){
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'statusCode' => 500
            ], 500);
        }
    }

    private function addEditService($request,$method,$id)
    {
        try{
            if($method == 'POST'){
                $args = json_encode([
                    "billingModeInfo" =>	[],
                    "code"=>$request->new_service_code,
                    "description"	=> $request->new_service_name,
                    "discountPercentage" =>	0,
                    "enableVatBilling" =>	"Y",
                    "followUpCharge" =>	$request->follow_up_charge,
                    "hasBillingMode" =>	"N",
                    "hospitalAppointmentServiceTypeId" =>	$request->service_type,
                    "isDiscountAllowed" =>	"N",
                    "isVatAllowed" =>	"N",
                    "name" =>	$request->new_service_name,
                    "refundPercentage" =>	0,
                    "registeredPatientCharge"=>$request->registered_patient_charge,
                    "remarks" =>	"",
                    "status" =>	$request->service_status == 1?"Y":"N",
                    "unregisteredPatientCharge"=>$request->new_registration_charge,
                    "vatPercentage"=>0
                ]);
            }else{
                $appointment_charge  = AppointmentCharge::where('id',$id)->first();
                $args = json_encode([
                    "billingModeInfo" =>	[],
                    "id"=>$appointment_charge->eapp_service_id,
                    "code"=>$request->new_service_code,
                    "description"	=> $request->new_service_name,
                    "discountPercentage" =>	0,
                    "enableVatBilling" =>	"Y",
                    "followUpCharge" =>	$request->follow_up_charge,
                    "hasBillingMode" =>	"N",
                    "hospitalAppointmentServiceTypeId" =>	$request->service_type,
                    "isDiscountAllowed" =>	"N",
                    "isVatAllowed" =>	"N",
                    "name" =>	$request->new_service_name,
                    "refundPercentage" =>	0,
                    "registeredPatientCharge"=>$request->registered_patient_charge,
                    "remarks" =>	"done",
                    "isChargeUpdated"=>"N",
                    "status" =>	$request->service_status == 1?"Y":"N",
                    "unregisteredPatientCharge"=>$request->new_registration_charge,
                    "vatPercentage"=>0
                ]);
            }
        
        $emrurl = $this->eurl . 'serviceInfo';
        $response =  $this->_apiCall($emrurl, $method, $args);

        if(isset($response) && isset($response->responseCode)){
            if($response->responseCode){
                $request->session()->flash( 'error_message', $response->errorMessage );
                return redirect()->back();
            }
           
        }else if(isset($response) && isset($response->error)){
            $request->session()->flash( 'error_message', $response->error );
            return redirect()->back();
    }else{
        $eapp_service_id = $response->serviceId;
        $eapp_service_charge_id = $response->serviceInfoChargeId;
        AppointmentCharge::where('id',$id)->update(
            [
                'eapp_service_id' => $eapp_service_id,
                'eapp_service_charge_id' => $eapp_service_charge_id,

            ]
            );
            $request->session()->flash( 'success_message', 'service added' );
            return redirect()->back();
        }
    } catch (\Exception $e) {
        \Log::info($e->getMessage());
        return $e;
    }
}

    public function deleteService(Request $request){
        try{
            $appointment_charge  = AppointmentCharge::where('id',$request->id)->first();
            $emrurl = $this->eurl . 'serviceInfo';
            $method = "DELETE";
            $args = json_encode([
                'id' => $appointment_charge->eapp_service_id,
                'serviceInfoChargeId' => $appointment_charge->eapp_service_charge_id,
                "remarks"=> "done",
                'status' => 'D'
            ]);
            $response = $this->_apiCall($emrurl, $method, $args);

        if(isset($response) && isset($response->responseCode)){
            if($response->responseCode){
                $request->session()->flash( 'error_message', $response->errorMessage );
                return redirect()->back();
            }
           
        }else if(isset($response) && isset($response->error)){
            $request->session()->flash( 'error_message', $response->error );
            return redirect()->back();
    }else{
        AppointmentCharge::where('id',$request->id)
             ->delete();
            $request->session()->flash( 'success_message', 'service deleted' );
            return redirect()->back();
        }
        }catch(\Exception $e){
            \Log::info($e->getMessage());
        return $e;
        }
     }

    public function index(Request $request)
    {
        try{
        $emrurl = $this->eurl . 'serviceInfo/min';
        $method = 'GET';
      
        $services = $this->_apiCall($emrurl, $method);
        $emrurl = $this->eurl . 'hospitalAppointmentServiceType';
        $method = 'GET';
        $service_types = $this->_apiCall($emrurl, $method);
        $item_names = ServiceCost::select( 'flditemname','fldid' )->distinct('flditemname')->where('flditemtype','General Services')->groupBy( 'flditemname' )->get();
        $appointment_charges = AppointmentCharge::get();
        foreach($appointment_charges as &$charge){
            $emrurl = $this->eurl . 'serviceInfo/detail';
            $method = 'PUT';
            $args = json_encode([
                'id' => $charge->eapp_service_id,
                'serviceInfoChargeId' => $charge->eapp_service_charge_id
            ]);
            $service_response = $this->_apiCall($emrurl, $method, $args);
            $charge->followup_charge = $service_response->followUpCharge;
            $charge->new_registration_charge = $service_response->unregisteredPatientCharge;
            $charge->registered_patient_charge = $service_response->registeredPatientCharge;
        }

        return view('eappointment::appointment-service-setup', compact('services','item_names','appointment_charges','service_types'));
    }catch(\Exception $e){
        return response()->json([
            'responseMessage' => $e->getMessage(),
            'statusCode' => 500
        ], 500);
    }
    }

    public function editService(Request $request){
        $appointment_charge = AppointmentCharge::where('id',$request->id)->first();

        $emrurl = $this->eurl . 'serviceInfo/detail';
        $method = 'PUT';
        $args = json_encode([
            'id' => $appointment_charge->eapp_service_id,
            'serviceInfoChargeId' => $appointment_charge->eapp_service_charge_id
        ]);
        $service_response = $this->_apiCall($emrurl, $method, $args);
        $appointment_charge->code = $service_response->code;
        $appointment_charge->followup_charge = $service_response->followUpCharge;
        $appointment_charge->new_registration_charge = $service_response->unregisteredPatientCharge;
        $appointment_charge->registered_patient_charge = $service_response->registeredPatientCharge;
        $appointment_charge->service_type = $service_response->hospitalAppointmentServiceTypeId;
        $appointment_charge->service_status = $service_response->status;

        

        return $appointment_charge;
    }

    public function updateService(Request $request)
    {
        $appointment_charge = AppointmentCharge::where('id',$request->id)->first();
        $appointment_charge->eapp_service_name = $request->new_service_name;
        $appointment_charge->tblservicecost_id = $request->item_id;
        $appointment_charge->update();
        
        $this->addEditService($request,'PUT',$request->id);
        return redirect()->back(); 
    }

    public function getServiceBillingMode(Request $request){
        $emrurl = $this->eurl . 'billingMode/service-wise/active/'.$request->serviceId;
        $method = 'GET';
        $args = [];
        $service_response = $this->_apiCall($emrurl, $method, $args);
        // dd($service_response);
        return $service_response->billingModeDetails;
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
}
