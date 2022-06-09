<?php

namespace Modules\HI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\PatFindings;
use App\PatBillDetail;
use App\PatBillCount;
use App\PatBilling;
use App\Encounter;
use App\ClaimUpload;
use App\Utils\Helpers;
use Illuminate\Support\Str;
use Storage;

use App\Utils\Options;
use App\DiagnoGroup;
use App\Exports\TotalvsConsumeExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class HIController extends Controller
{
       /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // dd($request);
        if($request->fromdate){
            try{

                $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
                $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
                $txtsearch = $request->txtsearch;
                if($request->billingmode != ''){
                    $billingmode = $request->billingmode;
                }else{
                    $billingmode = '';
                }
                

                $html = "";

                if($request->txtsearch){
                    if($request->searchtype == '0'){

                        

                        $result = DB::table('tblpatbilldetail as pd')
                                ->selectRaw('*,sum(pd.fldchargedamt) as fldusedmoney')
                                ->join('tblencounter as e','e.fldencounterval','pd.fldencounterval')
                                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                                ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')  
                                ->orwhere(function($query) use($txtsearch){
                                    $query->where('p.fldptnamefir','like','%'.$txtsearch.'%')
                                        ->orWhere('p.fldptnamelast','like','%'.$txtsearch.'%');

                                })
                                ->where('pd.fldtime','>=',$fromdateeng. ' 00:00:00')
                                ->where('pd.fldtime','<=',$todateeng. ' 23:59:59.99')
                                ->whereRaw('lower(e.fldbillingmode) like "' . $billingmode .'%"')
                                ->groupBy('e.fldencounterval')
                                ->get();

                            

                    }elseif($request->searchtype == '1'){

                        $result = DB::table('tblpatbilldetail as pd')
                                ->selectRaw('*,sum(pd.fldchargedamt) as fldusedmoney')
                                ->join('tblencounter as e','e.fldencounterval','pd.fldencounterval')
                                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                                ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')  
                                ->where('p.fldnhsiid','like','%'.$txtsearch.'%')                               
                                ->where('pd.fldtime','>=',$fromdateeng. ' 00:00:00')
                                ->where('pd.fldtime','<=',$todateeng. ' 23:59:59.99')
                                ->whereRaw('lower(e.fldbillingmode) like "' . $billingmode .'%"')
                                ->groupBy('e.fldencounterval')
                                ->get();
                    
                    }elseif($request->searchtype == '2'){

                        $result = DB::table('tblpatbilldetail as pd')
                                ->selectRaw('*,sum(pd.fldchargedamt) as fldusedmoney')
                                ->join('tblencounter as e','e.fldencounterval','pd.fldencounterval')
                                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                                ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')  
                                ->where('e.fldencounterval','like','%'.$txtsearch.'%')                               
                                ->where('pd.fldtime','>=',$fromdateeng. ' 00:00:00')
                                ->where('pd.fldtime','<=',$todateeng. ' 23:59:59.99')
                                ->whereRaw('lower(e.fldbillingmode) like "' . $billingmode .'%"')
                                ->groupBy('e.fldencounterval')
                                ->get();
                    
                    }else{

                        $result = DB::table('tblpatbilldetail as pd')
                                ->selectRaw('*,sum(pd.fldchargedamt) as fldusedmoney')
                                ->join('tblencounter as e','e.fldencounterval','pd.fldencounterval')
                                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                                ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')  
                                ->where('p.fldpatientval','like','%'.$txtsearch.'%')                               
                                ->where('pd.fldtime','>=',$fromdateeng. ' 00:00:00')
                                ->where('pd.fldtime','<=',$todateeng. ' 23:59:59.99')
                                ->whereRaw('lower(e.fldbillingmode) like "' . $billingmode .'%"')
                                ->groupBy('e.fldencounterval')
                                ->get();
                    }

                }else{

               
                
                    $result = DB::table('tblpatbilldetail as pd')
                                ->selectRaw('*,sum(pd.fldchargedamt) as fldusedmoney')
                                ->join('tblencounter as e','e.fldencounterval','pd.fldencounterval')
                                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                                ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')                                
                                ->where('pd.fldtime','>=',$fromdateeng . ' 00:00:00')
                                ->where('pd.fldtime','<=',$todateeng . ' 23:59:59.99')
                                ->whereRaw('lower(e.fldbillingmode) like "' . $billingmode .'%"')
                                ->groupBy('e.fldencounterval')
                                ->get();

                    

                }

                if($result->isNotEmpty()){

                    foreach ($result as $key => $results){


                        $patdiagnosis = \DB::table('tblpatfindings')->where('fldencounterval',$results->fldencounterval)->where('fldsave',1)->pluck('fldcodeid')->first();
                        $html .= "<tr fldencounterval='" . $results->fldencounterval ."'>";
                        // $html .= "<td style='text-align: center; vertical-align: middle;'><input type='checkbox' name='claim_check' id='claim_check'></td>";
                        $html .= "<td>" . ++$key . "</td>";
                        $html .= "<td>" . $results->fldtime . "</td>";
                        $html .= "<td>" . $results->fldpatientval . "</td>";
                        $html .= "<td>" . $results->fldnhsiid . "</td>";
                        $html .= "<td name='fldencounterval' id='fldencounterval' class='fldencounterval'>" . $results->fldencounterval . "</td>";
                        $html .= "<td>" . $results->fldptnamefir . " " . $results->fldmidname . " " . $results->fldptnamelast . "</td>";
                        $html .= "<td>" . $results->fldallowedamt . "</td>";
                        $html .= "<td>" . $results->fldusedmoney . "</td>";

                        

                        if(Str::contains($results->fldbillno , 'CAS') && (Str::contains($results->fldbillno , 'PHM'))){
                            $html .= "<td>" . "Cash/Pharmacy" . "</td>";
                        }elseif(Str::contains($results->fldbillno , 'CAS') || Str::contains($results->fldbillno , 'REG') || Str::contains($results->fldbillno , 'CRE')){
                            $html .= "<td>" . "Cash" . "</td>";
                        }elseif(Str::contains($results->fldbillno , 'PHM')){
                            $html .= "<td>" . "Pharmacy" . "</td>";
                        }else{
                            $html .= "<td>" . " " . "</td>";
                        }

                        $enc = $results->fldnhsiid;

                        if($patdiagnosis){
                            $html .= "<td>" . $patdiagnosis . "</td>";
                        }else{
                            $html .= "<td ><button class='btn btn-primary' id='diagnosisdata'>Add</button>" . '' . "</td>";
                        }

                        // $html .= "<td><select id='action' class='form-control select2 action'><option value=''>--Select--</option><option value='view'>View Bill</option><option value='claim'>Claim Bill</option><option value='noninsbill'>Non Insurance Bill</option><option value='billupload'>Bill Upload</option></select></td>";

                        $html .= "   <td>  <div class='dropdown'>
                        <button class='btn btn-primary dropdown-toggle dropdown-toggle' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            Action
                        </button>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' data-value='view' value='view' target='_blank'><i class='ri-eye-fill'></i>&nbsp;View Bill</a>
                            <a class='dropdown-item' data-value='claim' target='_blank'><i class='ri-ticket-2-line'></i>&nbsp;Claim Bill</a>
                            <a class='dropdown-item' data-value='noninsbill' target='_blank'><i class='ri-ticket-2-line'></i>&nbsp;Non Insurance Bill</a>
                            <a class='dropdown-item' data-value='billupload' target='_blank'><i class='ri-ticket-2-line'></i>&nbsp;Bill Upload</a>
                        </div>
                    </div> </td>";

                        $html .= "</tr>";
   
                    }
                    

                }else{

                    $html .= '<tr>';
                    $html .= '<td colspan="10"> No Data Found. </td>';
                    $html .= '</tr>';

                }

                return response()->json([
                    'data'=> [
                        'html' => $html,
                        'status' => true
                    ]
                ]);

            }catch(\Exception $e){
                dd($e);
            }
        }
        $diagnosisgroup = DiagnoGroup::select('fldgroupname')->distinct()->get();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $diagnocat = $this->getInitialDiagnosisCategory();
        $diagnosiscategory = $diagnocat;
                        
        $billingmode = Helpers::getBillingModes();

        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('hi::reports.claim',compact('date','billingmode','diagnosisgroup','diagnosiscategory'));
    }

    public function getInitialDiagnosisCategory()
    {
        try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $data = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[] = [
                        'code' => trim($csvLine[1]),
                        'name' => trim($csvLine[3]),
                    ];
                }
            }
            //sort($data);
            usort($data, function ($a, $b) {
                return $a['name'] <=> $b['name'];
            });
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }



    public function claimbill(Request $request){

        $enc = $request->enc;
        $html = "";
        $header = "";

        $result = DB::table('tblpatbilling as pb')
                // ->join('tblservicecost as sc','sc.flditemname','pb.flditemname')
                ->where('pb.fldencounterval',$enc)
                ->where('pb.fldsave','1')
                ->whereRaw('(lower(pb.fldbillingmode) = "health insurance" or lower(pb.fldbillingmode) = "healthinsurance" or lower(pb.fldbillingmode) = "hi")')
                ->groupBy('pb.flditemname')
                ->get();

        // dd($result);

        $header .= "<tr>";
        $header .= "<td>Category</td>";
        $header .= "<td>Item Name</td>";
        $header .= "<td>Rate</td>";
        $header .= "<td>Quantity</td>";
        $header .= "<td>Total</td>";
        $header .= "<td>Bill No.</td>";
        $header .= "<td>HI Code</td>";
        $header .= "</tr>";


        if($result->isNotEmpty()){

            
            foreach ($result as $key => $results){

                $hi_code = '';

                if($results->flditemtype == 'Medicines' || $results->flditemtype == 'Surgicals' || $results->flditemtype == 'Extra Items'){

                    $hi_code_result = \DB::table('tblstockrate')->where('flddrug',$results->flditemname)->pluck('fldhicode')->first();
                   
                    if(isset($hi_code_result)){
                      $hi_code = $hi_code_result;
                    }

                }else{
                    $hi_code_result = \DB::table('tblservicecost')->where('flditemname',$results->flditemname)->pluck('hi_code')->first();

                    if(isset($hi_code_result)){
                        $hi_code = $hi_code_result;
                    }
                }

                $html .= "<tr>";
                $html .= "<td>" . $results->flditemtype ."</td>";
                $html .= "<td>" . $results->flditemname . "</td>";
                $html .= "<td>" . $results->flditemrate ."</td>";
                $html .= "<td>" . $results->flditemqty ."</td>";
                $html .= "<td>" . $results->fldditemamt ."</td>";
                $html .= "<td>" . $results->fldbillno ."</td>";
                $html .= "<td>" . $hi_code ."</td>";
                
                $html .= "</tr>";

            }

            return response()->json([
                'data'=> [
                    'html' => $html,
                    'header' => $header,
                    'status' => true
                ]
            ]);

        }else{

            $html .= '<tr>';
            $html .= '<td colspan="7"> No Data Found. </td>';
            $html .= '</tr>';
            
            return response()->json([
                'data'=> [
                    'html' => $html,
                    'header' => $header,
                    'status' => true
                ]
            ]);

        }

    }

    public function claimbills(Request $request){

        $enc = $request->enc;

        $bill = DB::table('tblpatbilling as pb')
                // ->selectRaw('*,sum(flditemqty) as qty')
                ->join('tblservicecost as sc','sc.flditemname','pb.flditemname')
                ->join('tblencounter as e','e.fldencounterval','pb.fldencounterval')
                ->where('pb.fldencounterval',$enc)
                ->where('pb.fldsave','1')
                ->whereRaw('(lower(pb.fldbillingmode) = "health insurance" or lower(pb.fldbillingmode) = "healthinsurance" or lower(pb.fldbillingmode) = "hi")')
                ->groupby('sc.flditemname')
                ->get();  //hi_code,fldclaimcode

        // DB::enableQueryLog();

        $respat = DB::table('tblencounter as e')
                ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                ->where('e.fldencounterval',$enc)
                ->get();  

        $patientid = $respat[0]->fldnhsiid;
        $claim_code = $bill[0]->fldclaimcode;

        $patdiagno = DB::table('tblpatfindings')
                    ->where('fldencounterval',$enc)
                    ->where('fldsave','1')
                    // ->selectRaw('fldcodeid')
                    ->first();


        if(isset($patdiagno)){
            $diagno = $patdiagno->fldcodeid;
        }else{
            return response()->json([
                'data'=> [
                    'msg' => "Diagnosis Not Found!",
                    'status' => false
                ]
            ]);
        }

        $url = Options::get('hi_settings')['hi_url'] ?? '';
        $username = Options::get('hi_settings')['hi_username'] ?? '';
        $password = Options::get('hi_settings')['hi_password'] ?? '';
        $locationUUid = Options::get('hi_settings')['hi_location'] ?? '';
        $practitionerUUid = Options::get('hi_settings')['hi_practitioner'] ?? '';

        $urlel = $url . 'Claim/';

        $remote_user = 'remote-user:' . Options::get('hi_settings')['hi_remote_user'] ?? '';

        $ch = curl_init(); 

        $urlpat = $url . 'Patient/?identifier=' . $patientid;
    
        curl_setopt($ch, CURLOPT_URL, $urlpat);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
            'Content-Type: application/json',  
            $remote_user)                                                           
        );  

        $result = curl_exec($ch);

        $responsePatDetail = json_decode($result, true);


        $identity = $responsePatDetail["entry"][0]["resource"]["id"];

        if($bill->isNotEmpty() and $identity){

            $total = 0;
            $item = [];

            foreach($bill as $key => $bills){

                $total = $total + floatval($bills->flditemrate) * floatval($bills->flditemqty);
                
                if($bills->flditemtype == 'Medicines' or $bills->flditemtype == 'Surgicals'){

                    $hi_code = \DB::table('tblstockrate')
                            ->where('flddrug',$bills->flditemname)
                            ->get();

                    $item[$key] = 
                    [
                        "category" => [
                            "text" => "service"
                        ],
                        "quantity" => [
                            "value" => $bills->flditemqty
                        ],
                        "sequence" => ++$key,
                        "service" => [
                            "text" => $hi_code->hi_code
                            
                        ],
                        "unitPrice" => [
                            "value" => $bills->flditemrate
                        ]
                    

                ];


                }else{

                    $item[$key] = 
                        [
                            "category" => [
                                "text" => "service"
                            ],
                            "quantity" => [
                                "value" => $bills->flditemqty
                            ],
                            "sequence" => ++$key,
                            "service" => [
                                "text" => $bills->hi_code
                                
                            ],
                            "unitPrice" => [
                                "value" => $bills->flditemrate
                            ]
                        

                    ];


                }


            }

            $request_body_json = array_values($item);

            $request_main = [
                "resourceType" => "Claim",
                "billablePeriod" => [
                    "end" => \Carbon\Carbon::today()->toDateString(),
                    "start" => \Carbon\Carbon::today()->toDateString()
                ],
                "created" => \Carbon\Carbon::today()->toDateString(),
                "diagnosis" => [
                    [
                        "diagnosisCodeableConcept" => [
                            "coding" => [
                                [
                                    "code" => $diagno
                                ]
                            ]
                        ],
                        "sequence" => "1",
                        "type" => [
                            [
                                "text" => "icd_0"
                            ]
                        ]
                    ]
                ],
                "enterer" => [
                    "reference" => "Practitioner/" . $practitionerUUid
                ],
                "facility" => [
                    "reference" => "Location/" . $locationUUid
                ],
                "id" => $claim_code,
                "identifier" => [
                    [
                        "type" => [
                            "coding" => [
                                [
                                    "code" => "ASCN",
                                    "system" => "https://hl7.org/fhir/valueset-identifier-type.html"
                                ]
                            ]
                        ],
                        "use" => "usual",
                        "value" => $claim_code
                    ],
                [
                    "type" => [
                        "coding" => [
                            [
                                "code" => "MR",
                                "system"=> "https://hl7.org/fhir/valueset-identifier-type.html"
                            ]
                        ]
                    ],

                    "use" => "usual",
                    "value" => $claim_code

                ]
                ],
                "item" => $request_body_json,
                "total" => [
                    "value" => $total
                ],
                "patient" => [
                    "reference" => "Patient/".$identity
                ],
                "type" => [
                    "text" => "O"
                ]
                   
            ];

            $request_main_json = json_encode($request_main);

            $ch = curl_init(); 
      
            curl_setopt($ch, CURLOPT_URL, $urlel);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
            curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                'Content-Type: application/json',  
                $remote_user)                                                           
            );  
            curl_setopt($ch, CURLOPT_POSTFIELDS,$request_main_json);

            $result = curl_exec($ch);

            

            $result_json = (json_decode($result));


            if((Str::contains($result , 'error')) && Str::contains($result , 'Duplicate') ){

                return response()->json([
                    'data'=> [
                        'msg' => 'Duplicate Claim Code',
                        'status' => false
                    ]
                ]);
                
            }elseif(Str::contains($result , 'error')){

                return response()->json([
                    'data'=> [
                        'msg' => 'Something Went Wrong',
                        'status' => false
                    ]
                ]);
            
            }elseif(Str::contains($result , '')){

                return response()->json([
                    'data'=> [
                        'msg' => 'Something Went Wrong',
                        'status' => false
                    ]
                ]);


            }else{


                if($bill->isNotEmpty() && $identity){

                    $claimvalue =  $result_json->id;

                    // $claimref = explode("/",$claimvalue);

                   

                    foreach($bill as $key => $bills){

                    $status = Patbilling::where('fldbillno', $bills->fldbillno)->update(array('fldclaimstatus' => $result_json->item[0]->adjudication[0]->reason->text,
                                                                            'fldclaimref' => $claimvalue,
                                                                            'fldclaimtime' => \Carbon\Carbon::now(),
                                                                            'fldclaimuser' => Helpers::getCurrentUserName()));

                    }

                    // dd($status);
                
                }


                return response()->json([
                    'data'=> [
                        'msg' => "Item Claimed Successfully!",
                        'status' => true
                    ]
                ]);

                



            }

        }

    }

    public function viewbills(Request $request){

        $enc = $request->enc;
        $html = "";

        $header = "";

        // $result = DB::table('tblpatbilling')
        //         ->where('fldencounterval',$enc)
        //         ->where('fldsave','1')
        //         ->get();

        $header .= "<tr>";
        $header .= "<td>Category</td>";
        $header .= "<td>Item Name</td>";
        $header .= "<td>Rate</td>";
        $header .= "<td>Quantity</td>";
        $header .= "<td>Total</td>";
        $header .= "<td>Bill No.</td>";
        $header .= "</tr>";

        $result = DB::table('tblpatbilling as pb')
            // ->join('tblservicecost as sc','sc.flditemname','pb.flditemname')
            ->where('pb.fldencounterval',$enc)
            ->where('pb.fldsave','1')
            // ->whereRaw('(lower(pb.fldbillingmode) != "health insurance" or lower(pb.fldbillingmode) != "healthinsurance" or lower(pb.fldbillingmode) != "hi")')
            ->get();

        // dd($result);

        if($result->isNotEmpty()){

            foreach ($result as $key => $results){

                $html .= "<tr>";
                $html .= "<td>" . $results->flditemtype ."</td>";
                $html .= "<td>" . $results->flditemname . "</td>";
                $html .= "<td>" . $results->flditemrate ."</td>";
                $html .= "<td>" . $results->flditemqty ."</td>";
                $html .= "<td>" . $results->fldditemamt ."</td>";
                $html .= "<td>" . $results->fldbillno ."</td>";
                
                $html .= "</tr>";

            }

            
        }else{

            $html .= '<tr>';
            $html .= '<td colspan="6"> No Data Found. </td>';
            $html .= '</tr>';

        }

        return response()->json([
            'data'=> [
                'html' => $html,
                'header' => $header,
                'status' => true
            ]
        ]);

       

    }


    public function noninsbill(Request $request){

        $enc = $request->enc;
        
        $html = "";
        $header = "";

        $result = DB::table('tblpatbilling as pb')
            // ->join('tblservicecost as sc','sc.flditemname','pb.flditemname')
            ->join('tblencounter as e','e.fldencounterval','pb.fldencounterval')
            ->where('pb.fldencounterval',$enc)
            ->where('pb.fldsave','1')
            ->whereRaw('(lower(pb.fldbillingmode) != "health insurance" or lower(pb.fldbillingmode) != "healthinsurance" or lower(pb.fldbillingmode) != "hi")')
            ->groupby('pb.flditemname')
            ->get();  

        $header .= "<tr>";
        $header .= "<td>Category</td>";
        $header .= "<td>Item Name</td>";
        $header .= "<td>Rate</td>";
        $header .= "<td>Quantity</td>";
        $header .= "<td>Total</td>";
        $header .= "<td>Bill No.</td>";
        $header .= "</tr>";


          if($result->isNotEmpty()){

            

            foreach ($result as $key => $results){

                $html .= "<tr>";
                $html .= "<td>" . $results->flditemtype ."</td>";
                $html .= "<td>" . $results->flditemname . "</td>";
                $html .= "<td>" . $results->flditemrate ."</td>";
                $html .= "<td>" . $results->flditemqty ."</td>";
                $html .= "<td>" . $results->fldditemamt ."</td>";
                $html .= "<td>" . $results->fldbillno ."</td>";
                
                $html .= "</tr>";

            }

            
        }else{

            $html .= '<tr>';
            $html .= '<td colspan="6"> No Data Found. </td>';
            $html .= '</tr>';

        }

        return response()->json([
            'data'=> [
                'html' => $html,
                'header' => $header,
                'status' => true
            ]
        ]);



    }


    public function totalvsconsume(Request $request){

        if($request->fromdate){
            try{

                $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
                $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
                
                $html = "";

                $result = DB::table('tblencounter as e')
                        ->selectRaw('*,sum(fldchargedamt) as total,count(fldbillno) as bills')
                        ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                        ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')
                        ->join('tblpatbilldetail as pb','pb.fldencounterval','e.fldencounterval')
                        ->where('e.fldregdate','>=',$fromdateeng . ' 00:00:00')
                        ->where('e.fldregdate','<=',$todateeng . ' 23:59:59.99')
                        ->groupby('e.fldencounterval')
                        ->get();

                // dd($result);

                if($result->isNotEmpty()){

                    foreach($result as $key => $results){

                        $html .= "<tr>";
                        $html .= "<td>" . ++$key . "</td>";
                        $html .= "<td>" . $results->fldpatientval . "</td>";
                        $html .= "<td>" . $results->fldpatinsurance_id . "</td>";
                        $html .= "<td>" . $results->fldptnamefir . ' ' . $results->fldptnamelast . "</td>";
                        $html .= "<td>" . $results->fldallowedamt . "</td>";
                        $html .= "<td>" . $results->total . "</td>";
                        $html .= "<td>" . $results->bills . "</td>";
                        $html .= "</tr>";

                    }

                }else{
                    
                    $html .= "<tr><td colspan='7'>No Data Found!</td></tr>";

                }

                return response()->json([
                    'data'=> [
                        'html' => $html,
                        'status' => true
                    ]
                ]);


            }catch(\Execption $e){

                dd($e);

            }
        }else{
            return view('hi::reports.totalconsumevshi');
        }


    }

    public function totalvsconsumeexport(Request $request){
        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;

        try{

            $result = DB::table('tblencounter as e')
            ->selectRaw('*,sum(fldchargedamt) as total,count(fldbillno) as bills')
            ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
            ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')
            ->join('tblpatbilldetail as pb','pb.fldencounterval','e.fldencounterval')
            ->where('e.fldregdate','>=',$fromdateeng . ' 00:00:00')
            ->where('e.fldregdate','<=',$todateeng . ' 23:59:59.99')
            ->groupby('e.fldencounterval')
            ->get();

            return view('hi::pdf.totalvsconsume-report-export', array('result'=>$result,'userid'=>$userid,'fromdateeng'=>$fromdateeng,'todateeng'=>$todateeng));
        }catch(\Exception $e){
            dd($e);
        }

    }

    public function exportExcel(Request $request){

        $fromdateeng = Helpers::dateNepToEng($request->fromdate)->full_date;
        $todateeng = Helpers::dateNepToEng($request->todate)->full_date;

        $export = new TotalvsConsumeExport($fromdateeng,$todateeng);
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'TotalvsConsumeExport.xlsx');

    }

    public function billupload(Request $request){

        $enc = $request->enc;
        $html = "";

        $header = "";

        // $result = DB::table('tblpatbilling')
        //         ->where('fldencounterval',$enc)
        //         ->where('fldsave','1')
        //         ->get();

        $header .= "<tr>";
        $header .= "<td>Bill No.</td>";
        $header .= "<td>Upload Status</td>";
        $header .= "<td>Action</td>";
        $header .= "</tr>";

        $result = DB::table('tblpatbilldetail as pb')
            ->where('pb.fldencounterval',$enc)
            ->where('pb.fldsave','1')
            ->get();

        // dd($result);

        if($result->isNotEmpty()){
    
            foreach ($result as $key => $results){

                $uploadstatus = ClaimUpload::where('fldbillno',$results->fldbillno)->get();

                if($uploadstatus->isNotEmpty()){
                    $status = 'Uploaded';
                }else{
                    $status = 'Not Uploaded';
                }


                // if($results->upload_status == '1'){ $status = 'Uploaded';}else{ $status = 'Not Uploaded';}

                $html .= "<tr>";
                $html .= "<td class='fldbill'>" . $results->fldbillno ."</td>";
                $html .= "<td>" . $status  ."</td>";
                if($status == 'Uploaded'){
                    $html .= "<td><button type='button' disabled class='btn btn-primary rounded-pill btnupload' id='btnupload'>Upload</button></td>";
                }else{
                    $html .= "<td><button type='button' class='btn btn-primary rounded-pill btnupload' id='btnupload'>Upload</button></td>";
                }
                
                
                $html .= "</tr>";

            }

            
        }else{

            $html .= '<tr>';
            $html .= '<td colspan="2"> No Data Found. </td>';
            $html .= '</tr>';

        }

        return response()->json([
            'data'=> [
                'html' => $html,
                'header' => $header,
                'status' => true
            ]
        ]);

    }


    public function billuploadstatus(Request $request){

        $enc = $request->enc;
        $billno = $request->bill;

        $url = Options::get('claim_settings')['claim_url'] ?? '';
        $username = Options::get('claim_settings')['claim_username'] ?? '';
        $password = Options::get('claim_settings')['claim_password'] ?? '';
        $access_code = Options::get('claim_settings')['claim_access_code'] ?? '';

        // $url="https://claimdoc.hib.gov.np/user/check.php";

        $claim_code = PatBillDetail::where('fldbillno',$billno)->pluck('claim_code');

        $uploadstatus = ClaimUpload::where('fldbillno',$billno)->get();

        if(isset($url) || isset($username) || isset($password) || isset($access_code) ){
            return response()->json([
                'data'=> [
                    'status' => false,
                    'message' => 'Upload Setting Not Found.'
                ]
            ]);
        }else{


        if($uploadstatus->isNotEmpty()){

            return response()->json([
                'data'=> [
                    'status' => false,
                    'message' => 'Bill Already Uploaded!'
                ]
            ]);
        }else{
            if($claim_code->isNotEmpty()){

                $request_body = [
                    
                    "username" => $username,
                    "password" => $password
                ];
    
                $request_body_json = json_encode($request_body);
    
                $ch = curl_init(); 
          
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                    'Content-Type: application/json')                                                           
                );  
                curl_setopt($ch, CURLOPT_POSTFIELDS,$request_body_json);
    
                $result = curl_exec($ch);
    
                $responseBody = json_decode($result, true);
    
                $access_code = $responseBody['data']['access_code'];
               
    
                $request_body = [
                    
                    "claim_code" => $claim_code,
                    "access_code" => $access_code
                ];
    
                $request_body_json = json_encode($request_body);
    
                $ch = curl_init(); 
    
                $url="https://claimdoc.hib.gov.np/";
                $url=$url."claim/create.php";
          
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                    'Content-Type: application/json')                                                           
                );  
                curl_setopt($ch, CURLOPT_POSTFIELDS,$request_body_json);
    
                $result = curl_exec($ch);
    
                $responseBody = json_decode($result, true);
    
                // dd($responseBody);
    
                if(in_array('fail',$responseBody)){
    
                    return response()->json([
                        'data'=> [
                            'status' => false,
                            'message' => 'Invalid Claim Code!'
                        ]
                    ]);
                    
                    
                }else{
    
                    $claimid = $responseBody['data']['id'];
    
                    $file = $this->generateInvoice($billno);
    
                    $request_body = [
                    
                        "claim_id" => $claimid,
                        "name" => $billno,
                        "access_code" => $access_code,
                        "file" => $file
                    ];
    
    
                    $request_body_json = json_encode($request_body);
    
                    $ch = curl_init(); 
        
                    $url="https://claimdoc.hib.gov.np/";
                    $url=$url."claim/upload.php";
              
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                        'Content-Type: application/json')                                                           
                    );  
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$request_body_json);
        
                    // $result = curl_exec($ch);
        
                    // $responseBody = json_decode($result, true);
    
                    if(in_array('success',$responseBody)){
    
                        ClaimUpload::insert([
    
                            'fldbillno' => $billno,
                            'fldpatbillid' => PatBillDetail::where('fldbillno',$billno)->pluck('fldid'),
                            'fldencounterval' => $enc,
                            'flduploaddate' => \Carbon\Carbon::now(),
                            'flduser' => \Auth::guard('admin_frontend')->user()->flduserid,
                            'fldstatus' => 'Uploaded',
                            'fldclaimid' => $claimid,
                            'fldclaimcode' => $claim_code
    
                        ]);
    
    
                        return response()->json([
                            'data'=> [
                                'status' => true,
                                'message' => 'File Uploaded!'
                            ]
                        ]);
    
                    }else{
    
                    
                        return response()->json([
                            'data'=> [
                                'status' => false,
                                'message' => 'Invalid Token!'
                            ]
                        ]);
    
                    }
     
                }
    
            }
        }
        
        

        

        }


        // return response()->json([
        //     'data'=> [
        //         'status' => true
        //     ]
        // ]);

    }

    public function getDiagnosisByGroup(Request $request)
    {
        $html = '';
        if ($request->get('term')) {
            $groupname = $request->get('term');
            $diagnosiscategories = DiagnoGroup::select('flditemname', 'fldcodeid')->where('fldgroupname', $groupname)->get();
            // dd($diagnosiscategories);
            if (isset($diagnosiscategories) and count($diagnosiscategories) > 0) {
                foreach ($diagnosiscategories as $dc) {
                    $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $dc['fldcodeid'] . '"/></td><td>' . $dc['fldcodeid'] . '</td><td>' . $dc['flditemname'] . '</td></tr>';
                }

            } else {
                $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
        exit;
    }

    public function getInitialDiagnosisCategoryAjax()
    {
        $html = '';

        $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
        $data = [];
        while ($csvLine = fgetcsv($handle, 1000, ";")) {
            if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                $data[] = [
                    'code' => trim($csvLine[1]),
                    'name' => trim($csvLine[3]),
                ];
            }
        }
        //sort($data);
        usort($data, function ($a, $b) {
            return $a['name'] <=> $b['name'];
        });
        // dd($data);
        if (isset($data) and count($data) > 0) {
            foreach ($data as $d) {
                $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $d['code'] . '"/></td><td>' . $d['code'] . '</td><td>' . $d['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
    }

    public function getDiagnosisByCode(Request $request)
    {
        $html = '';
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $key = $request->get('term');
            $data = [];
            $parent_category = "";
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (substr($csvLine[1], 0, strlen($key)) == $key) {
                    if (strlen($csvLine[1]) == 3) {
                        $parent_category = $csvLine[3];
                    } else {
                        $data[$csvLine[1]] = $csvLine[3];
                    }
                }
            }
            if (count($data) < 1) {
                $data[$key] = $parent_category;
            }

            sort($data);
            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {
                    $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }


    function diagnosisStore(Request $request)
    {
        // dd($request->all());
        // echo "here store"; exit;
        try {
            $mytime = \Carbon\Carbon::now();
            $data['fldencounterval'] = $request->patient_id;
            $data['fldtype'] = 'Provisional Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $fldid = PatFindings::insertGetId($data);

            // $patdiago = PatFindings::where('fldencounterval', $request->patient_id)
            //     ->where(function ($queryNested) {
            //         $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
            //             ->orWhere('fldtype', 'Final Diagnosis');
            //     })
            //     ->where('fldsave', 1)
            //     ->get();
            // $html = '';
            // if(isset($patdiago) and count($patdiago) > 0){
            //     foreach ($patdiago as $key => $value) {
            //         $html.='<option value="'.$value->fldid.'">'.$value->fldcode.'</option>';
            //     }
            // }
            // echo $html; exit;

        } catch (\Exception $e) {
           // dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->back();
        }
    }

    public function getDiagnosisByCodeSearch(Request $request)
    {
        $html = '';
        // echo $request->get('term').'-'.$request->get('query'); exit;
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $key = $request->get('term');
            $data = [];
            $parent_category = "";
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (substr($csvLine[1], 0, strlen($key)) == $key) {
                    if (strlen($csvLine[1]) == 3) {
                        $parent_category = $csvLine[3];
                    } else {
                        $data[$csvLine[1]] = $csvLine[3];
                    }
                }
            }
            if (count($data) < 1) {
                $data[$key] = $parent_category;
            }

            sort($data);
            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {
                    if($request->get('query') !=''){
                        $searchtextlength = strlen($request->get('query'));
                        $compare  = substr($d, 0, $searchtextlength);
                        // echo $compare; exit;
                        if(ucfirst($request->get('query')) == $compare){
                            
                            $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                        }
                    }else{
                        $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                    }
                    
                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }



    public function generateInvoice($billno)
    {
        try {
            $countdata = PatBillCount::where('fldbillno', $billno)->pluck('fldcount')->first();
            // dd($countdata);
            // echo $countdata['fldcount']; exit;

            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != ' ') ? $countdata + 1 : 1;

            // $updatedata['fldcount'] = $countdata->fldcount + 1;
            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $billno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $billno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            // $countdata->update($updatedata);
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $billno)->first();
            //            dd($data['patbillingDetails']);
            $data['itemdata'] = PatBilling::where('fldbillno', $billno)->with('referUserdetail')->get();

            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;

            $pdf = PDF::loadView('hi::pdf.billing-invoice', $data);


            $location = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

            Storage::disk('local')->put($billno.".pdf",  $pdf->output());

            $cFile = curl_file_create(($location.$billno.".pdf"));

            $filelocat = $cFile->name; 

            // dd(($filelocat));

            $file = (base64_encode(file_get_contents($filelocat))); //file content with path

            // dd($file);

            Storage::delete($billno.".pdf");

            return $file;

        } catch (\Exception $e) {
            throw new \Exception(__('messages.error'));
        }

    }

}
