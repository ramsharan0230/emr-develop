<?php

namespace Modules\PatBillingShare\Http\Controllers;

use App\OtGroupSubCategory;
use App\PatBilling;
use App\PatBillingShare;
use App\PatBillingSharesReport;
use App\ServiceCost;
use App\UserShare;
use App\Utils\Helpers;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Options;


class FractionPaymentController extends Controller
{
    protected $page_limit = 25;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // get billing share report.
        $this->emergencyShareUpdate();
        $result = $this->getLists($request);
        // dd($result[0]->fldbillno);

        $data['itemnames'] = ServiceCost::select('flditemname')->get();
        $data['consultants'] = Helpers::getConsultantList();
        $data['billing_share_reports'] = $result;

        return view('patbillingshare::fractionpayment.index', $data);
    }

    public function getBillDetails(Request $request)
    {

        $patbills = PatBilling::where('fldid', $request->billno)->get();
        $billnoget = $patbills[0]->fldbillno;
        $patbillshare = PatBillingShare::where('pat_billing_id', $request->billno)->first();
        $patientbillings = [];
        foreach ($patbills as $patbill) {
            $tempbills = [];
            $shareamt = 0;
            $tempbills['patbillid'] = $patbill->fldid;
            $tempbills['fldencounterval'] = $patbill->fldencounterval;
            $tempbills['flditemname'] = $patbill->flditemname;
            $tempbills['flditemtype'] = $patbill->flditemtype;
            $tempbills['fldditemamt'] = $patbill->fldditemamt;
            $servcost = ServiceCost::where([
                'flditemname' => $patbill->flditemname,
                'flditemtype' => $patbill->flditemtype,
            ])->first();
            $tempbills['sharetype'] = $servcost->category;
            $tempbills['othersharepercent'] = $servcost->other_share;
            array_push($patientbillings, $tempbills);
        }


//pooja
$patbillings = $patbills->groupBy('flditemname');
            $results = DB::table('tblpatbilling AS pb')
                ->where(function ($query) use ($request) {
                    if (isset($request->billno)) {
                        $query->where('pb.fldid', $request->billno);
                    }
                })

                ->leftJoin('pat_billing_shares AS pbs', 'pb.fldid', '=', 'pbs.pat_billing_id')

                ->leftJoin('users as usr', function ($join) use ($request) {
                    $join->on('pbs.user_id', '=', 'usr.id')
                        ->when($request->doctor_id != "" && $request->doctor_id != null, function ($q) use ($request) {
                            return $q->where('usr.id',$request->doctor_id);
                        })
                        ->when($request->doctor_username != "" && $request->doctor_username != null, function ($q) use ($request) {
                            return $q->where('usr.username',$request->doctor_username);
                        })
                        ->when($request->doctor_name != "" && $request->doctor_name != null, function ($q) use ($request) {
                            return $q->where(DB::raw("CONCAT_WS(' ', usr.firstname, usr.middlename, usr.lastname)"), 'LIKE', '%' . $request->doctor_name . '%');
                        });
                })
                ->where('pbs.is_returned',0)
                //->where('pbs.share', '>', 0)
                ->where('pbs.status', 1)
                ->select(DB::raw("usr.firstname, usr.middlename,
                 usr.lastname, usr.id as user_id, pb.fldid,
                 pb.fldencounterval, pb.fldbillno , pb.fldbillingmode,
                 pb.flditemtype, pb.flditemname, pb.fldditemamt,
                 pb.fldorduserid, pb.fldordtime, pb.fldstatus, pbs.id
                            AS pat_billing_share_id, pbs.type, pbs.user_id,
                            pbs.share, pbs.ot_group_sub_category_id,
                            pbs.is_returned,
                            pbs.total_amount as item_amount,
                            pbs.usersharepercent,
                            pbs.hospitalshare,
                            pbs.share,
                            pbs.shareqty


                        "))

                ->whereRaw("pb.fldsave = 1")
                ->groupBy('usr.id')
                ->get()
                ->groupBy(['fldid','flditemname','type']);
            $data = [];
            foreach($results as $patbillid => $resultdd){
                foreach($resultdd as $item => $result){

                    $tempdata = [];
                    $shareamt = 0;
                    $tempdata['patbillid'] = $patbillings[$item][0]->fldid;
                    $tempdata['itemname'] = $item;
                    $tempdata['itemamt'] = $itemamount = $patbillings[$item][0]->fldditemamt;
                    $tempdata['doctor_id'] = [];
                    $tempdata['doctors'] = [];
                    $tempdata['hospitalsharepercent'] = $hospitalsharepercent = $patbillshare->hospitalshare;
                    $tempdata['hospitalshareamount'] = ($hospitalsharepercent * $itemamount) /100;
                    foreach($result as $category => $res){
                        $doctors = [];
                        foreach($res as $r){
                            $shareamt += $r->share;
                            $shareval = $r->usersharepercent;
                            $name = ucfirst($r->firstname) . " " . ucfirst($r->middlename) . " " . ucfirst($r->lastname);
                            $tempdoctors = [];
                            $arr = [];
                            $arr['userid'] = $r->user_id;
                            $arr['shareval'] = $shareval;
                            $arr['share'] = $r->share;
                            $arr['ot_group_sub_category_id'] = $r->ot_group_sub_category_id;
                            $tempdoctors[$name] = $arr;
                            array_push($doctors,$tempdoctors);
                            $cat = [];
                            $patbillshares = PatBillingShare::where([['pat_billing_id',$patbillings[$item][0]->fldid],['type',$category]])->select('type', 'user_id', 'ot_group_sub_category_id')->get();
                            foreach($patbillshares as $patbillshare){
                                if($patbillshare->type == "OT Dr. Group"){
                                    array_push($cat,$patbillshare->ot_group_sub_category_id);
                                }else{
                                    array_push($cat,$patbillshare->user_id);
                                }
                            }
                            $tempdata['doctor_id'][$category] = $cat;
                        }
                        $tempdata['doctors'][$patbillid][$category] = $doctors;
                    }
                    $tempdata['shareamt'] = $shareamt;
                    array_push($data,$tempdata);
                }
            }
        //pooja end


        $patbillingids = PatBilling::where('fldid', $request->billno)->pluck('fldid')->toArray();
        $patbillOtGroups = PatBillingShare::where('type', 'OT Dr. Group')->whereIn('pat_billing_id', $patbillingids)->with('user:id,firstname,middlename,lastname')->groupBy(['pat_billing_id', 'ot_group_sub_category_id'])->get();
        $sub_category = OtGroupSubCategory::select('ot_group_sub_categories.id as ot_group_sub_category_id', 'ot_group_sub_categories.name as ot_group_sub_category_name', 'tbluserpay.flduserid as userid', 'users.firstname', 'users.middlename', 'users.lastname')
            ->join('tbluserpay', 'tbluserpay.ot_group_sub_category_id', '=', 'ot_group_sub_categories.id')
            ->join('users', 'users.id', '=', 'tbluserpay.flduserid')
            ->get()
            ->groupBy(['ot_group_sub_category_id']);
        return response()->json([
            'status' => true,
              'data' => $data,
            'otgroups' => $sub_category,
            'patbillOtGroups' => $patbillOtGroups,
            'patbills' => $patientbillings,
            'billnoget' => $billnoget,
        ]);
    }

    public function updateDoctorShare(Request $request)
    {
        DB::beginTransaction();
        try {
            $allpatbillids = $request->patbillids;
            $patsharedetail = PatBillingShare::where('pat_billing_id', $allpatbillids[0])->first();
            $itemamount = $patsharedetail->total_amount;
            $hospitalshare = $patsharedetail->hospitalshare;
            $shareqtyof = $patsharedetail->shareqty;
            $created_at =  $patsharedetail->created_at;

            $updatepatbillids = [];
            foreach ($request->shares as $patbillid => $categories) {
                array_push($updatepatbillids, $patbillid);
            }
            $deletepatbillids = $allpatbillids;
            if (count($deletepatbillids) > 0) {
                $deletePatBillingShares = PatBillingShare::whereIn('pat_billing_id', $deletepatbillids)->get();
                foreach ($deletePatBillingShares as $deletePatBillingShare) {
                    $deletePatBillingShare->delete();
                }
            }
            foreach ($request->shares as $patbillid => $categories) {
                $types = [];
                foreach ($categories as $catname => $category) {
                    array_push($types, $catname);
                }
                $deletePatBillingShares = PatBillingShare::where([
                    'pat_billing_id' => $patbillid,
                ])
                    ->when(count($types) > 0, function ($q) use ($types) {
                        return $q->whereNotIn('type', $types);
                    })
                    ->get();
                foreach ($deletePatBillingShares as $deletePatBillingShare) {
                    $deletePatBillingShare->delete();
                }
            }
            foreach ($request->shares as $patbillid => $categories) {
                foreach ($categories as $catname => $category) {
                    $userids = [];
                    $ot_group_sub_category_ids = [];
                    foreach ($category as $userid => $user) {
                        array_push($userids, $userid);
                        array_push($ot_group_sub_category_ids, $user['ot_group_sub_category_id']);
                    }
                    $deletePatBillingShares = PatBillingShare::where([
                        'pat_billing_id' => $patbillid,
                        'type' => $catname
                    ])
                        ->when($catname == "OT Dr. Group", function ($q) use ($ot_group_sub_category_ids) {
                            return $q->where('ot_group_sub_category_id', "!=", null)
                                ->when(count($ot_group_sub_category_ids) > 0, function ($q3) use ($ot_group_sub_category_ids) {
                                    return $q3->whereNotIn('ot_group_sub_category_id', $ot_group_sub_category_ids);
                                });
                        })
                        ->when($catname != "OT Dr. Group", function ($q2) use ($userids) {
                            return $q2->where('ot_group_sub_category_id', null)
                                ->when(count($userids) > 0, function ($q4) use ($userids) {
                                    return $q4->whereNotIn('user_id', $userids);
                                });
                        })
                        ->get();
                    foreach ($deletePatBillingShares as $deletePatBillingShare) {
                        $deletePatBillingShare->delete();
                    }
                }
            }

            //insertion
            $sharesin = $request->shares;
            foreach ($sharesin as $patbillid => $categories) {
                // dd($categories);
                foreach ($categories as $catname => $users) {

                    foreach ($users as $user => $userdetail) {
                        $userid = $userdetail['userid'];
                        $shareamt = ($userdetail['sharevalue'] * $itemamount) / 100;
                        $taxamt = (15 / 100) * $shareamt;
                        $data = [
                            'pat_billing_id' => $patbillid,
                            'type' => $catname,
                            'user_id' => $userid,
                            'share' => \App\Utils\Helpers::numberFormat($shareamt,'insert'),
                            'tax_amt' => \App\Utils\Helpers::numberFormat($taxamt,'insert'),
                            'usersharepercent' => $userdetail['sharevalue'],
                            'total_amount' => \App\Utils\Helpers::numberFormat($itemamount,'insert'),
                            'hospitalshare' => $hospitalshare,
                            'shareqty' => $shareqtyof,
                            //'ot_group_sub_category_id' => $userdetail['ot_group_sub_category_id'],
                            'created_at' =>  $created_at,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'fldupdatedby' => Helpers::getCurrentUserName()
                        ];

                        PatBillingShare::insert($data);
                    }
                }
            }



            $results = $this->getLists($request);
            $html = '';
            //dd($results);
            if ($results) {
                foreach ($results as $k => $report) {
                    $tax_amt = ($report->tax_amt) ? $report->tax_amt : 0;
                    $payment = $report->doctor_share - $tax_amt;
                    $html .= '<tr data-billno="'.$report->fldbillno.'">
                                <td>' . ++$k . '</td>
                                <td>' . $report->fldbillno . '</td>
                                <td>' . $report->fldencounterval . '</td>
                                <td>' .\App\Utils\Helpers::getPatientName($report->fldencounterval) . '</td>
                                <td>' . $report->created_at . '</td>
                                <td>' . $report->flditemname . '</td>
                                <td>' . \App\Utils\Helpers::numberFormat($report->item_amt) . '</td>

                                <td>' . Helpers::getshareamount($report->pat_billing_id) . '</td>
                                <td>' . Helpers::getshareamountDr($report->pat_billing_id) . '</td>
                                <td>
                                    <a href="#" data-bill="' . $report->pat_billing_id . '" class="btn btn-success btn-sm editShare" title="Edit"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>';
                }
            } else {
                $html .= '<tr>
                            <td colspan="10">No data to show.</td>
                        </tr>';
            }
            $paginations = 0;
            DB::commit();
            return response()->json([
                'status' => true,
                'html' => $html,
                'pagination' => $paginations
            ]);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function getLists(Request $request)
    {

	    $parttime = false;
	    if(isset($request->parttime)){
		    $parttime = true;
	    }
        $results = DB::table('pat_billing_shares as pbs')
        ->join('tbluserpay','tbluserpay.flduserid', '=' ,'pbs.user_id')
        ->join('tblpatbilling','tblpatbilling.fldid', '=' ,'pbs.pat_billing_id')
        //->where('pbs.share', '>', 0)
        ->where('pbs.status', '=', 1)
        ->where('pbs.is_returned','=', 0 )
        ->where('pbs.created_at' ,'>=', $request->eng_from_date . ' 00:00:00' )
        ->where('pbs.created_at' ,'<=',  $request->eng_to_date . ' 23:59:59' )
        ->select('pbs.user_id AS user_id',
        'pbs.pat_billing_id AS pat_billing_id',
        'pbs.type AS type',
        'tblpatbilling.fldbillno AS fldbillno',
        'tblpatbilling.flditemtype AS flditemtype',
        'tblpatbilling.flditemname AS flditemname',
        'tblpatbilling.flduserid AS flduserid',
        'pbs.created_at AS created_at',
        'tblpatbilling.fldtime AS fldtime',
        'tblpatbilling.fldditemamt AS fldditemamt',
        'tblpatbilling.flditemqty AS flditemqty',
    'pbs.hospitalshare AS hospital_share',
        'pbs.total_amount AS item_amt',
        'pbs.tax_amt AS tax_amt',
        'pbs.share AS doctor_share',
        'pbs.is_returned AS is_returned',
        'tblpatbilling.fldencounterval AS fldencounterval',
	        'tbluserpay.fldparttime as parttime'
        )
        ->when($request->itemname != "" && $request->itemname != null, function ($q) use ($request) {
            return $q->where('tblpatbilling.flditemname','=' ,$request->itemname)
                     ->where('tbluserpay.flditemname',$request->itemname)
	            ;
        })
        ->when($request->bill_no != "" && $request->bill_no != null, function ($q) use ($request) {
            return $q->where('tblpatbilling.fldbillno','=' ,$request->bill_no);
        })
        ->when($request->encounter_id != "" && $request->encounter_id != null, function ($q) use ($request) {
            return $q->where('tblpatbilling.fldencounterval','=' ,$request->encounter_id);
        })
        ->when($request->type != "" && $request->type != null, function ($q) use ($request) {
            return $q->where('pbs.type','LIKE' , '%'.$request->type.'%');
        })
	        ->where('tbluserpay.fldparttime',$parttime)
	        ->orderBy('pbs.updated_at','desc')
        ->groupBy('pbs.pat_billing_id')
        ->paginate(20);

        return $results;
    }


    public function emergencyShareUpdate(){

        if(Options::get('emergency_drshare_hospital') == 1){
            $emergencyshare = PatBillingShare::select('id','share')->where('emergencyShare',NULL)->get();
            if($emergencyshare){
                $shareupdate = array();
                foreach($emergencyshare as $share){
                    $shareamount = $share->share;
                    $emershare = ((1/100)*$shareamount);
                    $shareupdate = [
                        'emergencyShare' => $emershare ,
                        'share' => $shareamount - $emershare
                    ];

                    PatBillingShare::where('id',$share->id)->update($shareupdate);

                }
            }

        }


    }
}
