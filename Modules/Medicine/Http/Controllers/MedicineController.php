<?php

namespace Modules\Medicine\Http\Controllers;

use App\Chemical;
use App\Code;
use App\DosageForms;
use App\Drug;
use App\MedCategory;
use App\SensitivityDrug;
use App\Utils\Medicinehelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

    }

    public function addGenericName(Request $request) {
        $response = array();
        try {
            $fldcodename = $request->fldcodename;

            $data=[];
            $data['fldcodename'] = $fldcodename;

            $checkdublicate = Code::where('fldcodename',  $fldcodename)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Generic name duplicate.';

            } else {
                Code::Insert($data);

                $latestcode = Code::where('fldcodename', $fldcodename)->first();
                $response['message'] = 'Generic Name added successfully.';
                $response['fldcodename'] = $latestcode->fldcodename;
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
//            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteGeneric($fldcodename) {
        $response = array();
        try {

            $codes = Code::where(['fldcodename' => $fldcodename])->get();
            if(count($codes) > 0) {
                foreach($codes as $code) {
                    DB::table('tblcode')->where(['fldcodename' => $fldcodename])->delete();
                }
            }
            $response['message'] = 'success';
            $response['successmessage'] = 'Generic Info deleted successfully.';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';
            $response['message'] = 'error';
        }

        return  json_encode($response);
    }

    public function genericNameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {
            $searchresults = Code::where('fldcodename', 'like', ''.$keyword . '%')->orderBy('fldcodename', 'ASC')->get();
            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<li class="generic-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="generic_item" data-href="'. route('medicines.deletegeneric', $searchresult->fldcodename) .'" data-id="{{ $code->fldcodename }}">'. $searchresult->fldcodename .'</a></li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function addMedCategory(Request $request) {
        $response = array();
        try {
            $flclass = $request->flclass;

            $data=[];
            $data['flclass'] = $flclass;

            $checkdublicate = MedCategory::where('flclass',  $flclass)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Category exists already.';
            } else {
                MedCategory::Insert($data);

                $latestcategory = MedCategory::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Category added successfully.';
                $response['fldid'] = $latestcategory->fldid;
                $response['flclass'] = $latestcategory->flclass;
                $response['medcategories'] = \App\Utils\Medicinehelpers::getMedCategory();
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteMedCategory($fldid) {
        $response = array();
        try {

            $medcategory = MedCategory::find($fldid);

            if($medcategory) {
                $medcategory->delete();
            }

            $response['message'] = 'success';
            $response['successmessage'] = 'Category deleted successfully.';
            $response['medcategories'] = \App\Utils\Medicinehelpers::getMedCategory();
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';

            $response['message'] = 'error';
        }

        return json_encode($response);
    }

    public function medcategoryNameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {

            $searchresults = MedCategory::where('flclass', 'like', ''.$keyword . '%')->orderBy('flclass', 'ASC')->get();

            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<li class="category-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="category_item" data-href="'. route('medicines.deletemedcategory', $searchresult->fldid) .'" data-id="'. $searchresult->fldid .'">'. $searchresult->flclass .'</a></li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function addDosageForm(Request $request) {
        $response = array();
        try {
            $flforms = $request->flforms;

            $data=[];
            $data['flforms'] = $flforms;

            $checkdublicate = DosageForms::where('flforms',  $flforms)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Dosage form exists already.';
            } else {
                DosageForms::Insert($data);

                $latestdosage = DosageForms::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Dosage Form added successfully.';
                $response['fldid'] = $latestdosage->fldid;
                $response['flforms'] = $latestdosage->flforms;
                $response['dosageforms'] = \App\Utils\Medicinehelpers::getAllDosageForms();
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
//            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteDosageForm($fldid) {
    $response = array();
    try {

        $DosageForms = DosageForms::find($fldid);

        if($DosageForms) {
            $DosageForms->delete();
        }

        $response['message'] = 'success';
        $response['successmessage'] = 'Dosage deleted successfully.';
    } catch(\Exception $e) {

        $response['errormessage'] = $e->getMessage();

        $response['errormessage'] = 'something went wrong while deleting category';

        $response['message'] = 'error';
    }

    return  json_encode($response);
}

    public function dosageNameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {

            $searchresults = DosageForms::where('flforms', 'like', ''.$keyword . '%')->orderBy('flforms', 'ASC')->get();

            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'. route('medicines.deletedosageform', $searchresult->fldid) .'" data-id="'. $searchresult->fldid .'">'. $searchresult->flforms .'</a></li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function addChemicals(Request $request) {
        $response = array();
        try {
            $flclass = $request->flclass;

            $data=[];
            $data['flclass'] = $flclass;

            $checkdublicate = Chemical::where('flclass',  $flclass)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Chemical already exist successfully.';
            } else {
                Chemical::Insert($data);

                $latestchemical = Chemical::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Chemical added successfully.';
                $response['fldid'] = $latestchemical->fldid;
                $response['flclass'] = $latestchemical->flclass;
                $response['chemicals'] = \App\Utils\Medicinehelpers::getChemicals();
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteChemicals($fldid) {
        $response = array();
        try {

            $chemicals = Chemical::find($fldid);

            if($chemicals) {
                $chemicals->delete();
            }
            $response['message'] = 'success';
            $response['successmessage'] = 'Chemical deleted successfully.';
            $response['chemicals'] = \App\Utils\Medicinehelpers::getChemicals();
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';

            $response['message'] = 'error';
        }

        return json_encode($response);
    }

    public function chemicalNameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {

            $searchresults = Chemical::where('flclass', 'like', ''.$keyword . '%')->orderBy('flclass', 'ASC')->get();

            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= ' <li class="chemical-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="chemical_item" data-href="'. route('medicines.deletechecmials', $searchresult->fldid) .'" data-id="'. $searchresult->fldid .'">'. $searchresult->flclass .'</a></li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function addSensitivity(Request $request) {
        $response = array();
        try {
            $flclass = $request->flclass;

            $data=[];
            $data['flclass'] = $flclass;

            $checkdublicate = SensitivityDrug::where('flclass',  $flclass)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Sensitivity already exist successfully.';
            } else {
                SensitivityDrug::Insert($data);

                $latestsensitivity = SensitivityDrug::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Sensitivity added successfully.';
                $response['fldid'] = $latestsensitivity->fldid;
                $response['flclass'] = $latestsensitivity->flclass;
                $response['sensitivitydrugs'] = \App\Utils\Medicinehelpers::getSensitivityDrug();
            }


        } catch(\Exception $e) {

            $response['message'] = $e->getMessage();
            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteSensitivity($fldid) {
        $response = array();
        try {

            $chemicals = SensitivityDrug::find($fldid);

            if($chemicals) {
                $chemicals->delete();
            }

            $response['message'] = 'success';
            $response['successmessage'] = 'Sensitivity deleted successfully.';
            $response['sensitivitydrugs'] = \App\Utils\Medicinehelpers::getSensitivityDrug();
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';

            $response['message'] = 'error';
        }

        return json_encode($response);
    }

    public function sensitivityNameFilter(Request $request) {
        $response = array();
        $keyword = $request->keyword;

        try {

            $searchresults = SensitivityDrug::where('flclass', 'like', ''.$keyword . '%')->orderBy('flclass', 'ASC')->get();


            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<li class="sensitivity-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sensitivity_item" data-href="'. route('medicines.deletesensitivity', $searchresult->fldid) .'" data-id="'. $searchresult->fldid .'">'. $searchresult->flclass .'</a></li>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);


    }

    public function searchMedicine(Request $request) {
        $response = array();
        $searchkeyword = $request->searchkeyword;

        try {

            $codes = Drug::select('fldcodename')->where('fldcodename', 'like', ''.$searchkeyword . '%')->groupBy('fldcodename')->orderBy('fldcodename', 'ASC')->paginate(100);;

            $html = '';
            if(count($codes) > 0) {
                foreach($codes as $k=>$code) {
                    $html .= '<li class="table-menu" type="button" data-toggle="collapse" data-target="#collapse_'. $k .'" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-list"></i>'. $code->fldcodename .'</li>';
                    $drugs = Medicinehelpers::getDrugsFromCode($code->fldcodename);
                    $html .= '<div class="collapse" id="collapse_'. $k .'" style="padding: 0px 25px;"><ul style="list-style-type: none;">';
                    if(count($drugs) > 0) {
                        foreach($drugs as $i=>$drug) {
                            $collapsableornot = (count($drug->MedicineBrand) > 0) ? 'type="button" data-toggle="collapse" data-target="#collapsedrug_'. $drug->flddrug.'_'.$i .'" aria-expanded="false" aria-controls="collapseExample"' : '';
                            $html .= '<li class="table-menu" '. $collapsableornot .'>
                                        <i class="fa fa-list-ol"></i> <label for="">'. $drug->flddrug .'</label>
                                        <a type="button" href="'. route("medicines.medicineinfo.editdrug", encrypt($drug->flddrug)) .'" style="margin-left: 15px;" title="edit '. $drug->flddrug .'">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a type="button" href="'. route("medicines.medicineinfo.brandinfo", encrypt($drug->flddrug)) .'" style="margin-left: 15px;" title="show '. $drug->flddrug .'brands">
                                            <i class="fa fa-arrow-alt-circle-right"></i>
                                        </a>
                                        <a type="button" href="'. route("medicines.medicineinfo.labels", encrypt($drug->flddrug)) .'" style="margin-left: 15px;" title="show '. $drug->flddrug .' labels">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                        <button title="delete '. $drug->flddrug .'" class="deletedrug" data-href="'. route("medicines.medicineinfo.deletedrug", encrypt($drug->flddrug)) .'"><i class="fa fa-trash"></i></button>
                                    </li>';
                        }
                    }
                    $html .= '</ul></div>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);

    }

}
