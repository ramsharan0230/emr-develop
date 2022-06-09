<?php

namespace Modules\Laboratory\Http\Controllers;

use App\CogentUsers;
use App\Consult;
use App\GroupTest;
use App\PcrForm;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;
use App\PatLabTest;
use App\PatBilling;
use App\ServiceCost;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class IMUController extends Controller {


	public function syncIMU( Request $request ) {
//    	return 'Imy sync';
		try {
			\DB::beginTransaction();
			$pcr_form                         = new PcrForm();
			$pcr_form->fldname                = $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;
			$pcr_form->fldtoken               = uniqid();
			$pcr_form->fldage                 = $request->year;
			$pcr_form->fldsex                 = $request->gender;
			$pcr_form->fldcaste               = $request->caste;
			$pcr_form->fldprovince            = $request->province;
			$pcr_form->flddistrict            = $request->district;
			$pcr_form->fldmunicipality        = $request->municipality;
			$pcr_form->fldward                = $request->wardno;
			$pcr_form->fldtole                = $request->tole;
			$pcr_form->fldcontact             = $request->contact;
			$pcr_form->fldsampletoken         = uniqid();
			$pcr_form->fldtravelled           = $request->travelled;
			$pcr_form->fldinfectiontype       = $request->infection_type;
			$pcr_form->fldoccupation          = $request->occupation;
			// $pcr_form->fldlabresult           = $request->lab_result;
			$pcr_form->fldsampletype          = $request->sample_type;
			$pcr_form->fldservicetype         = $request->service_type;
			$pcr_form->fldservicefor          = $request->service_for;
			$pcr_form->fldregisteredate       = $request->register_date;
			// $pcr_form->fldlabreceivedate      = $request->lab_receive_date;
			// $pcr_form->fldlabtestdate         = $request->lab_test_date;
			// $pcr_form->fldsamplecollecteddate = $request->sample_collected_date;
			// $pcr_form->fldlabtesttime         = $request->lab_test_time;
			$pcr_form->fldencounterval        = $request->encounter_id;
			$pcr_form->save();
			\DB::commit();

			// $apiURL    = config( 'app.minio_url' );
			// $imuUsername    = config( 'app.minio_username' );
			// $imuPasaword    = config( 'app.minio_password' );
			// $postInput = array(
			// 	'name'                  => $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name,
			// 	'age'                   => $request->year,
			// 	'age_unit'              => $request->year,
			// 	'sex'                   => $request->gender,
			// 	'token'                 => $pcr_form->fldtoken,
			// 	'sample_token'          => $pcr_form->fldsampletoken,
			// 	'emergency_contact_one' => $request->contact,
			// 	'sample_collected_date' => $request->sample_collected_date,
			// 	'province_id'           => $request->province,
			// 	'district_id'           => $request->district,
			// 	'municipality_id'       => $request->municipality,
			// 	'occupation'            => $request->occupation,
			// 	'caste'                 => $request->caste,
			// 	'ward'                  => $request->wardno,
			// 	'tole'                  => $request->tole,
			// 	'sample_type'           => $request->sample_type,
			// 	'service_for'           => $request->service_for,
			// 	'service_type'          => $request->service_type,
			// 	'infection_type'        => $request->infection_type,
			// 	'travelled'             => $request->travelled,
			// 	'registered_at'         => $request->register_date,
			// 	'lab_result'            => $request->lab_result,
			// 	'lab_test_date'         => $request->lab_test_date,
			// 	'lab_test_time'         => $request->lab_test_time,
			// 	'lab_received_date'     => $request->lab_receive_date
			// );
			// $client    = new \GuzzleHttp\Client();
			// $response  = $client->request( 'POST', $apiURL, [
			// 	'auth'        => [ $imuUsername, $imuPasaword ],
			// 	'form_params' => $postInput
			// ] );

			return response()->json(['status'=>true,'message'=>'message successfully']
				// [ $response->getBody()->getContents() ], $response->getStatusCode()
			);

		} catch ( \Exception $e ) {
			dd( $e->getMessage() );
			\DB::rollBack();

			return false;
		}

	}

}
