<?php

namespace Modules\Department\Http\Controllers;

use App\Bedfloor;
use App\Bedgroup;
use App\Bedtype;
use App\CogentUsers;
use App\Department;
use App\Departmentbed;
use App\EappDept;
use App\Hmismapping;
use App\HospitalDepartment;
use App\PatBilling;
use App\ServiceCost;
use App\User;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Notification;
use App\Notifications\NewMessage;
use App\Utils\Options;

class DepartmentController extends Controller
{

	private $api_key;
    private $eurl;

    public function __construct(){
        $this->api_key = Options::get('e_appointment_hmac_key') ? Options::get('e_appointment_hmac_key') : Options::get('e_appointment_hmac_key');
        $this->eurl = Options::get('e_appointment_url') ? Options::get('e_appointment_url') : Options::get('e_appointment_url');
    }

	
	/**
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data['departments'] = Department::get();
		$data['bedtype'] = Bedtype::get();
		$data['bedgroup'] = Bedgroup::get();
		$data['bedfloor'] = Bedfloor::get();
		//select flditemname as col from tblservicecost where flditemtype='General Services' and (fldgroup like '%' or fldgroup='%')
		$data['autobilling'] = ServiceCost::where('flditemtype', 'General Services')->get();
		$emrurl = $this->eurl . 'serviceInfo/active/min/DEP';
       
		$method = 'GET';
        $data['services'] = $this->_apiCall($emrurl, $method);
		$data['inchargeUser'] = CogentUsers::select('firstname', 'middlename', 'lastname', 'username')->where('fldopconsult', 1)->where('status', 'active')->get();
		// ->where('fldgroup', 'like', '%')
		//->orwhere('fldgroup', '%');

		return view('department::index', $data);
	}

	function adddepartement(Request $request)
	{
		try{
		\DB::beginTransaction();
		$user = User::first();
		$data = array(
			'flddept' => $request->department_name,
			'fldroom' => $request->room,
			'fldblock' => $request->fldblock,
			'flddeptfloor' => $request->flddeptfloor,
			'fldhead' => $request->autobilling,
			'fldcateg' => $request->category,
			'fldactive' => $request->incharge,
			'fldstatus' => $request->department_status,
			'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
		);
		$id = Department::insertGetId($data);
		
		$data = $this->addUpdateDepartment('POST',$request,$id);
		if(isset(json_decode($data->getContent())->error)){
			\DB::rollBack();
			return $data;
		}
		
		
		\DB::commit();

		// Notification::send($toUser, new NewMessage($fromUser));

		Notification::send($user, new NewMessage($data));

		$html = '';
		if ($id) {
			$html = $this->getDepartmentTableData();
			return response()->json([
				'success' => [
					'html' => $html,
				]
			]);
		}
	} catch (\Exception $e) {
		\DB::rollBack();
		\Session::flash('error_message', $e->getMessage());
		Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
	}
	}


	function updatedepartment(Request $request)
	{
		$data = array(
			'flddept' => $request->department_name,
			'fldroom' => $request->room,
			'fldblock' => $request->fldblock,
			'flddeptfloor' => $request->flddeptfloor,
			'fldhead' => $request->autobilling,
			'fldcateg' => $request->category,
			'fldactive' => $request->incharge,
			'fldstatus' => $request->fldstatus,
		);
		$id = Department::where([['fldid', $request->fldid]])->update($data);
		$data = $this->addUpdateDepartment('PUT',$request,$request->fldid);

		$html = '';
		if ($id) {
			$html = $this->getDepartmentTableData();
			return response()->json([
				'success' => [
					'html' => $html,
				]
			]);
		}
	}

	public function categorysearch(Request $request)
	{
		$category = Department::where('fldcateg', $request->category)->get();
		$html = '';

		if ($category) {

			foreach ($category as $dept) {
				$status = 'Active';
				if($dept->fldstatus == 0){
					$status = 'InActive';
				}
				$html .= '<tr>
                <td><a   href="javascript:;" class="deptname" dept="' . $dept->flddept . '" >' . $dept->flddept . '</a></td>
                <td>' . $dept->fldcateg . '</td>
                <td>' . $dept->fldblock . '</td>
                <td>' . $dept->flddeptfloor . '</td>
                <td>' . $dept->fldroom . '</td>

                <td>' . $dept->fldhead . '</td>
                <td>' . $dept->fldactive . '</td>
                <td>' . $status . '</td>
            </tr>';
			}
		}

		return response()->json([
			'success' => [
				'html' => $html,


			]
		]);
	}

	public function getbedbydept(Request $request)
	{
		try{
			$category = Departmentbed::where('flddept', $request->dept)->get();
		$department = Department::where('flddept', $request->dept)->first();
		if(isset($department->eapp)){
			$eapp_dept_id = $department->eapp->eapp_dept_id;
			$url = $this->eurl . 'hospitalDepartment/detail/'.$eapp_dept_id;
        $response = $this->_apiCall($url,'GET');
		$department->department_name_nepali = $response->departmentNameInNepali;
		$department->department_description = $response->description;
		$department->department_code  = $response->code;
		$department->service  = $response->appointmentChargeInfo[0]->serviceInfoId;
		}
		$html = '';
		if ($category) {
			foreach ($category as $dept) {
				if (empty($dept->fldencounterval)) {
					$status = 'Available';
				} else {
					$status = 'Alloted';
				}

				if($dept->bedstatus == 0){
					$bed_status = 'InActive';
				}else{
					$bed_status = 'Active';
				}
				if ($dept->is_oxygen == 1) {
					$is_oxygen = 'Available';
				} else {
					$is_oxygen = '';
				}
				$html .= '<tr>
                <td><a  href="javascript:;" class="bedn" dept="' . $dept->flddept . '" floor="' . $dept->fldfloor . '" bedtype="' . $dept->fldbedtype . '"  fldbedgroup="' . $dept->fldbedtype . '" is_oxygen="' . $dept->fldbedtype . '" fldhead="'.$dept->fldhead.'" >' . $dept->fldbed . '</a></td>
                <td>' . $dept->fldbedtype . '</td>
                <td>' . $dept->fldbedgroup . '</td>
                <td>' . $dept->fldfloor . '</td>
                <td>' . $is_oxygen . '</td>
                <td>' . $dept->fldhead . '</td>
                <td>' . $status . '</td>
                <td>


                    <a href="javascript:;" class="delete-bed" url="' . route('deletebed') . '"fldbed="' . $dept->fldbed . '" billingid="' . $dept->flddept . '" bedstatus="' . $bed_status . '">'.$bed_status.'</a>
                </td>
            </tr>';
			}
		}

		return response()->json([
			'success' => [
				'html' => $html,
				'departmentData' => $department
				// 'fldid' => $department->fldid,
				// 'autobilling' => $department->fldhead,
				// 'department_name' => $department->flddept,
				// 'room' => $department->fldroom,
				// 'incharge' => $department->fldactive,
			]
		]);

		} catch(\Exception $e){
			dd($e->getMessage());
		}
		
	}

	function addUpdateDepartment($method,$request,$id){
		try{
		$service = $request->service;
		
		if($method == 'POST') {
			$url = $this->eurl . 'serviceInfo/charge/'.$service;
			$response = $this->_apiCall($url, 'GET');
			if(isset($response) && isset($response->responseCode)){
				if($response->responseCode){
					return response()->json([
						'error' => true,
						'message' => $response->errorMessage
					]);
				
				}
			   
			}else if(isset($response) && isset($response->error)){
				return response()->json([
					'error' => true,
					'message' => $response->error
				]);
			}
			$args =  json_encode([
				"appointmentChargeInfo"=> [
					[
					  "serviceInfoChargeId"=> $response->serviceChargeWithBillingModeInfo[0]->value,
					  "status"=> "Y"
					]
				  ],
				  "code"=> $request->department_code,
				  "departmentNameInNepali"=> $request->department_name_nepali,
				  "description"=> $request->department_description,
				  "doctorId"=> [
				  ],
				  "name"=> $request->department_name,
				  "roomId"=> [
					
				  ],
				  "status"=> "Y"]);
		} else{
			$args =  json_encode([
				"appointmentChargeInfo"=> [
					
				  ],
				  'hasServiceInfoForApptChargeUpdated'=>false,
				  "code"=> $request->department_code,
				  "departmentNameInNepali"=> $request->department_name_nepali,
				  "description"=> $request->department_description,
				  "doctorUpdateList"=> [
				  ],
				  "name"=> $request->department_name,
				  "roomUpdateList"=> [
					
				  ],
				  "id"=> EappDept::where('dept_id',$id)->first()->eapp_dept_id,
				  "remarks"=> "done",
				  "status"=> "Y"]);

		}
			$url = $this->eurl . 'hospitalDepartment';
		 	$response = $this->_apiCall($url, $method, $args);
		 if(isset($response) && isset($response->responseCode)){
			if($response->responseCode){
				return response()->json([
					'error' => true,
					'message' => $response->errorMessage
				]);
			
			}
		   
		}else if(isset($response) && isset($response->error)){
			return response()->json([
				'error' => true,
				'message' => $response->error
			]);
	}else{
		if($method == 'POST'){
			$this->addToTable($response,$id);
		}
		return response()->json([
			'success' => [
				'message' => 'Dept added successful',
			]
		]);
		}
	} catch (\Exception $e) {
				

		\Log::info($e->getMessage());
		return response()->json([
			'error' => true,
			'message' => $e->getMessage()
		]);
		}
	}

	function addbed(Request $request)
	{
		try{
			$has_bed = Departmentbed::where('fldbed',$request->fldbed)->first();
			if($has_bed){
				return response()->json([
					'error' => true,
					'message' => "Duplicate entry for bed"
				]);
			}

			$data = array(
				'flddept' => $request->flddept,
				'fldbed' => $request->fldbed,
				'fldbedtype' => $request->bedtype,
				'fldbedgroup' => $request->flddept,
				'fldfloor' => $request->floor,
				'fldhead' => $request->bed_autobilling,
				'is_oxygen' => $request->is_oxygen,
				'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
			);
			$id = Departmentbed::insertGetId($data);
			$html = '';
			// $mapping = Hmismapping::where('service_name',$request->flddept)->first();
			// if($mapping){
			//    $map_data = [
			//        'category' =>$mapping->category,
			//        'sub_category' => $mapping->sub_category ?? null,
			//        'service_name' =>$request->fldbed,
			//        'service_value' =>$request->fldbed,
			//    ];
			//    Hmismapping::create($map_data);
			// }


			$category = Departmentbed::where('flddept', $request->flddept)->get();
			$department = Department::where('flddept', $request->flddept)->first();

			$html = '';
			$html = $this->getDepartmentBedData($category);

			return response()->json([
				'success' => [
					'html' => $html,
				]
			]);
		} catch (\Exception $e){
			return response()->json([
				'error' => true,
				'message' => $e->getMessage()
			]);
		}

	}

	protected function addToTable($eapp_dept_id,$dept_id)
    {
        \DB::table('eapp_depts')->insert(
            ['eapp_dept_id' => $eapp_dept_id,
             'dept_id' => $dept_id,
            "created_at" =>  \Carbon\Carbon::now(), 
            "updated_at" => \Carbon\Carbon::now() ]
        );
    }

	function editbed(Request $request)
	{
		$bed = Departmentbed::where('fldbed', $request->fldbed)->first();
		return response()->json([
			'success' => [
				'bedData' => $bed,
			]
		]);
	}

	function updatebed(Request $request)
	{
		try{
			$data = array(
				'fldbed' => $request->fldbed,
				'flddept' => $request->flddept,
				'is_oxygen' => $request->is_oxygen,
				'fldfloor' => $request->fldfloor,
				'fldbedgroup' => $request->fldbedgroup,
				'fldbedtype' => $request->fldbedtype,
				'fldhead' => $request->bed_autobilling
			);
			$id = Departmentbed::where([['fldbed', $request->fldbed]])->update($data);
			$html = '';
			$category = Departmentbed::where('flddept', $request->flddept)->get();
			$html = $this->getDepartmentBedData($category);
			return response()->json([
				'success' => [
					'html' => $html,
				]
			]);
		} catch (\Exception $e){
			return response()->json([
				'error' => true,
				'message' => $e->getMessage()
			]);
		}
	}

	function getDepartmentBedData($category)
	{
		$html = '';
		if ($category) {
			foreach ($category as $dept) {
				if (empty($dept->fldencounterval)) {
					$status = 'Available';
				} else {
					$status = 'Alloted';
				}
				if ($dept->is_oxygen == 1) {
					$is_oxygen = 'Available';
				} else {
					$is_oxygen = '';
				}
				if($dept->bedstatus == 0){
					$bed_status = 'InActive';
				}else{
					$bed_status = 'Active';
				}
				$html .= '<tr>
                <td><a  href="javascript:;" class="bedn" dept="' . $dept->flddept . '" floor="' . $dept->fldfloor . '" bedtype="' . $dept->fldbedtype . '"  fldbedgroup="' . $dept->fldbedtype . '" is_oxygen="' . $dept->fldbedtype . '" fldhead="'.$dept->fldhead.'">' . $dept->fldbed . '</a></td>
                <td>' . $dept->fldbedtype . '</td>
                <td>' . $dept->fldbedgroup . '</td>
                <td>' . $dept->fldfloor . '</td>
                <td>' . $is_oxygen . '</td>
                <td>' . $dept->fldhead . '</td>
                <td>' . $status . '</td>
                <td>

                    <a href="javascript:;" class="delete-bed" url="' . route('deletebed') . '"fldbed="' . $dept->fldbed . '" billingid="' . $dept->flddept . '" bedstatus="' . $bed_status . '">'.$bed_status.'</a>
                </td>
            </tr>';
			}
		}
		return $html;
	}

	function deletedepartment(Request $request)
	{
		$data['fldstatus'] = '0';
		Department::where('fldid', $request->fldid)->update($data);
		$html = $this->getDepartmentTableData();

		return response()->json([
			'success' => [
				'html' => $html,
			]
		]);
	}

	function getDepartmentTableData()
	{
		$category = Department::where('fldstatus','1')->get();
		$html = '';
		if ($category) {
			foreach ($category as $dept) {
				$html .= '<tr>
                <td><a  href="javascript:;" class="deptname" dept="' . $dept->flddept . '" >' . $dept->flddept . '</a></td>
                <td>' . $dept->fldcateg . '</td>
                <td>' . $dept->fldblock . '</td>
                <td>' . $dept->flddeptfloor . '</td>
                <td>' . $dept->fldroom . '</td>';
				if ($dept->fldhead != "0") {
					$html .= '<td>' . $dept->fldhead . '</td>';
				} else {
					$html .= '<td></td>';
				}
				$html .= '<td>' . $dept->fldactive . '</td>';
				if ($dept->fldstatus == 1) {
					$html .= '<td>Active</td>';
				} else {
					$html .= '<td>Inactive</td>';
				}
				$html .= '
            </tr>';
			}
		}
		return $html;
	}

	function deletebed(Request $request)
	{

		$bed = Departmentbed::where('fldbed', $request->fldbed)->first();
		if ($bed ) {
			$status = 0;
			if($bed -> bedstatus == 0){
				$status = 1;
			}
			Departmentbed::where('fldbed', $request->fldbed)->update(['bedstatus' => $status]);

		}

		$html = '';

		$category = Departmentbed::where('flddept', $request->flddept)->get();

		$html = '';

		if ($category) {

			foreach ($category as $dept) {
				if (empty($dept->fldencounterval)) {
					$status = 'Available';
				} else {
					$status = 'Alloted';
				}

				if ($dept->is_oxygen == 1) {
					$is_oxygen = 'Available';
				} else {
					$is_oxygen = '';
				}

				if($dept->bedstatus == 0){
					$bed_status = 'InActive';
				}else{
					$bed_status = 'Active';
				}

				$html .= '<tr>
                <td><a href="javascript:;"  class="bedn" dept="' . $dept->flddept . '" floor="' . $dept->fldfloor . '" bedtype="' . $dept->fldbedtype . '"  fldbedgroup="' . $dept->fldbedtype . '" is_oxygen="' . $dept->fldbedtype . '" fldhead="'.$dept->fldhead.'">' . $dept->fldbed . '</a></td>
                <td>' . $dept->fldbedtype . '</td>
                <td>' . $dept->fldbedgroup . '</td>
                <td>' . $dept->fldfloor . '</td>
                <td>' . $is_oxygen . '</td>
                <td>' . $dept->fldhead . '</td>
                <td>' . $status . '</td>
                <td><a href="javascript:;" class="delete-bed" url="' . route('deletebed') . '"fldbed="' . $dept->fldbed . '" billingid="' . $dept->flddept . '" bedstatus="' . $bed_status . '">'.$bed_status.'</a></td>

            </tr>';
			}
		}


		return response()->json([
			'success' => [
				'html' => $html,


			]
		]);
	}

	function exportdepartment()
	{
		$data['departments'] = Department::get();
		return view('department::departmentpdf', $data);

	}

	function getDepartmentByCategory($category)
	{
		$depts = Department::where('fldcateg', $category)->get();
		return response()->json([
			'data' => $depts,
			'success' => true,
			'message' => "Departments fetched."
		]);
	}

    function getAvgRevenuePerDept(Request  $request)
    {
        try{
            $eng_from_date=$request->eng_from_date;
            $eng_to_date=$request->eng_to_date;
            $avg_rev_dept=collect();
            if($eng_from_date){
                $avg_rev_dept=HospitalDepartment::
                select(
                    'hospital_departments.id','name','hospital_departments.fldcomp', \DB::raw('count(tblpatbilling.hospital_department_id) as test_count'),\DB::raw('sum(tblpatbilling.fldditemamt) as itemAmt')
                )
                    ->join('tblpatbilling','tblpatbilling.hospital_department_id','hospital_departments.id')
                    ->whereDate('tblpatbilling.fldtime','>=', $eng_from_date)->whereDate('tblpatbilling.fldtime','<=', $eng_to_date)
                    ->groupBy('hospital_departments.id')
                    ->orderBy('itemAmt','desc')
                    ->get();

            }
            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
            $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
            return view('department::avg-rev.index',compact('avg_rev_dept','date'));
        }catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message'=>$e->getMessage()
            ]);
        }
    }

    function getAvgRevenuePerDeptDetail(Request  $request,$dept_id)
    {
        try{
            $eng_from_date=$request->eng_from_date;
            $eng_to_date=$request->eng_to_date;
            $department_comp = HospitalDepartment::where('id',$dept_id)->first()->fldcomp;
            $avg_rev_dept=collect();
            if($eng_from_date){
                $avg_rev_dept = PatBilling::select('tblpatbilling.fldencounterval','fldditemamt',\DB::raw('sum(fldditemamt) as totalAmt'))
                    ->where('tblpatbilling.hospital_department_id',$dept_id)
                    ->whereDate('tblpatbilling.fldtime','>=', $eng_from_date)->whereDate('tblpatbilling.fldtime','<=', $eng_to_date)
                    ->groupBy('tblpatbilling.fldencounterval')
                    ->orderBy('totalAmt','desc')
                    ->get();
            }
            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
            $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
            return view('department::avg-rev.detail',compact('avg_rev_dept','date','department_comp'));
        }catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message'=>$e->getMessage()
            ]);
        }
    }

    function getAvgRevenuePerPerson(Request  $request)
    {
        try{
            $eng_from_date=$request->eng_from_date;
            $eng_to_date=$request->eng_to_date;
            $avg_rev_person=collect();
            if($eng_from_date) {
                $avg_rev_person = PatBilling::
                select('fldencounterval',\DB::raw('sum(tblpatbilling.fldditemamt) as totalAmt'))
                    ->whereDate('fldtime','>=', $eng_from_date)->whereDate('fldtime','<=', $eng_to_date)
                    ->whereNotNull('hospital_department_id')
                    ->groupBy('fldencounterval')
                    ->orderBy('totalAmt','desc')
                    ->get();
            }
            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
            $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
            return view('department::avg-rev-pat.index',compact('avg_rev_person','date'));
        }catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message'=>$e->getMessage()
            ]);
        }
    }

    function getAvgRevenuePerPersonDetail(Request  $request,$encounter_id)
    {
        try{
            $eng_from_date=$request->eng_from_date;
            $eng_to_date=$request->eng_to_date;
            $avg_rev_person=collect();
            if($eng_from_date) {
                $avg_rev_person=PatBilling::
                select(
                    'tblpatbilling.hospital_department_id', \DB::raw('count(tblpatbilling.hospital_department_id) as test_count'),\DB::raw('sum(tblpatbilling.fldditemamt) as itemAmt')
                )
                    ->where('tblpatbilling.fldencounterval',$encounter_id)
                    ->whereDate('tblpatbilling.fldtime','>=', $eng_from_date)->whereDate('tblpatbilling.fldtime','<=', $eng_to_date)
                    ->whereNotNull('hospital_department_id')
                    ->groupBy('tblpatbilling.hospital_department_id')
                    ->orderBy('itemAmt','desc')
                    ->get();
            }
            $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
            $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
            return view('department::avg-rev-pat.detail',compact('avg_rev_person','date','encounter_id'));
        }catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message'=>$e->getMessage()
            ]);
        }
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
