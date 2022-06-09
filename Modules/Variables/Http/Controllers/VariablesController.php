<?php

namespace Modules\Variables\Http\Controllers;

use App\BodyFluid;
use App\EthnicGroup;
use App\Surname;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class VariablesController extends Controller
{


    /**
     * VariablesController constructor.
     */
    public function __construct()
    {

    }

    public function bodyfluid() {

        return view('variables::body-fluid');
    }

    public function addBodyfluids(Request $request) {
        $response = array();
        try {
            $fldfluid = $request->fldfluid;

            $data=[];
            $data['fldfluid'] = $fldfluid;

            $checkdublicate = BodyFluid::where('fldfluid', $fldfluid)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Body fluid exists already.';
            } else {
                BodyFluid::Insert($data);

                $latestbodyfluid = BodyFluid::orderBy('fldid', 'DESC')->first();

                $response['message'] = 'Success';
                $response['alertmsg'] = $latestbodyfluid->fldfluid." bodyfluid added successfully";
                $response['fldid'] = $latestbodyfluid->fldid;
                $response['fldfluid'] = $latestbodyfluid->fldfluid;
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
//            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteBodyfluids($fldid) {
        $response = array();
        try {

            $bodyfluid = BodyFluid::find($fldid);

            if($bodyfluid) {
                $bodyfluid->delete();
            }

            $response['message'] = 'success';
            $response['successmessage'] = 'Bodyfluid deleted successfully.';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';

            $response['message'] = 'error';
        }

        return  json_encode($response);
    }

    public function ethnicGroup() {
        return view('variables::ethnic-group');
    }

    public function getSurnameFromGroupName(Request $request) {
        $response = array();
        $groupname = $request->fldgroupname;

        try {
            $ethnicgroups = EthnicGroup::where('fldgroupname', $groupname)->orderBy('flditemname', 'ASC')->get();
            $html = '<thead style="background-color: #efebe7;"><tr><td></td><td>Code</td><td>Particulars</td><td class="text-center">Action</td></thead>';
            $html .= '<tbody>';
            if(count($ethnicgroups) > 0) {
              foreach($ethnicgroups as $k=>$ethnicgroup) {
                  $html .= '<tr><td style="background-color: #efebe7;">'.++$k.'</td><td>'.$ethnicgroup->fldgroupname.'</td><td>'.$ethnicgroup->flditemname.'</td><td class="text-center"><button title="delete '. $ethnicgroup->flditemname .'" class="deletethnicgroup" data-href="'. route("variables.ethnicgroup.delete", $ethnicgroup->fldid) .'"><i class="fa fa-trash"></i></button></td></tr>';
              }
            }

            $html .= '</tbody>';

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function surnameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {
            $searchresults = Surname::where('flditem', 'like', '%'. $keyword . '%')->get();
            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<li style="border: 1px solid #ced4da;"><input type="checkbox" value="'. $searchresult->fldid .'" class="flag-check" name="surnames"/>&nbsp;&nbsp; '. $searchresult->flditem .'</li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function addSurname(Request $request) {
        $response = array();
        $surnameids = $request->checked;
        $fldgroupname = $request->fldgroupname;
        try {
            if(count($surnameids) > 0) {
                $surnames = Surname::whereIn('fldid', $surnameids)->get();
                foreach($surnames as $surname) {
                    $checkdublicates = EthnicGroup::where(['fldgroupname' => $fldgroupname, 'flditemname' => $surname->flditem])->get();

                    if(count($checkdublicates) == 0) {
                        $ethnicdata = [];
                        $ethnicdata['fldgroupname'] = $fldgroupname;
                        $ethnicdata['flditemname'] = $surname->flditem;

                        EthnicGroup::insert($ethnicdata);
                    }
                }
            }


            $response['message'] = 'success';
        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);

    }

    public function deleteEthnicgroup($fldid) {
        $response = array();
        try {

            $exam = EthnicGroup::find($fldid);

            if($exam) {
                $exam->delete();
               $response['message'] = 'success';
            }
        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }
}
