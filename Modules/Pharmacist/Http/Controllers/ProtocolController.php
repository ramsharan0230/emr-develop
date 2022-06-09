<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\Drug;
use App\MedGroup;
use App\MedicineBrand;
use App\ProductGroup;
use App\Utils\Permission;
use App\Utils\Pharmacisthelpers;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProtocolController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        return view('pharmacist::index');
    }

    public function protocols()
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        // dd(config('unauthorize-message.pharmacy_master.medicine_grouping.view'));
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'medicine-grouping', 'medicine-grouping-view'  ])  ) ?
            abort(403, config('unauthorize-message.pharmacy_master.medicine_grouping.view')) : true ;

//        $drug = 'oral';
//
//        $flddrug              = Drug::where('fldroute', $drug)->pluck('flddrug');
//        $data['newOrderData'] = MedicineBrand::whereRaw('lower(fldbrand) like ?', array('%'))
//            ->where('fldmaxqty', '<>', '-1')
//            ->where('fldactive', 'Active')
//            ->whereIn('flddrug', $flddrug)
//            ->orderby('fldbrand', 'ASC')
//            ->get();
//
//        dd($data['newOrderData']);

        return view('pharmacist::protocol.protocol');
    }

    public function addMedGroup(Request $request) {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'medicine-grouping', 'medicine-grouping-create'  ])  ) ?
            abort(403, config('unauthorize-message.pharmacy_master.medicine_grouping.create')) : true ;
        try {
            $response = array();
            $fldmedgroup = $request->fldmedgroup;

            $data=[];
            $data['fldmedgroup'] = $fldmedgroup;

            $checkdublicate = MedGroup::where('fldmedgroup',  $fldmedgroup)->get();

            if($checkdublicate->count > 0) {
                Helpers::logStack(['Med group already exist in med group create', "Error"]);
                $response['message'] = 'Med Group exists already.';
            } else {
                MedGroup::Insert($data);
                Helpers::logStack(["Med group created", "Event"], ['current_data' => $data]);
                $latestmedgroup = MedGroup::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Med Group added successfully.';
                $response['fldid'] = $latestmedgroup->fldid;
                $response['fldmedgroup'] = $latestmedgroup->fldmedgroup;
            }
            return json_encode($response);
        } catch(\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in med group create', "Error"]);
            $response['message'] = $e->getMessage();
            return json_encode($response);
        }
    }

    public function deleteMedgroup($fldid) {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'medicine-grouping', 'medicine-grouping-delete'  ])  ) ?
        abort(403, config('unauthorize-message.pharmacy_master.medicine_grouping.delete')) : true ;
        try {
            $response = array();
            $MedGroup = MedGroup::find($fldid);
            if($MedGroup) {
                $MedGroup->delete();
                Helpers::logStack(["Med group deleted", "Event"], ['previous_data' => $MedGroup]);
                $response['message'] = 'Med Group Deleted Successfully.';
            } else {
                Helpers::logStack(['Med group not found in med group delete', "Error"]);
                $response['message'] = 'Data not found.';
            }
            return json_encode($response);
        } catch(\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in med group delete', "Error"]);
            $response['message'] = $e->getMessage();
            return json_encode($response);
        }
    }


    public function getMedicinesFromFldroute(Request $request) {
        $fldroute = $request->fldroute;

        $response = array();
        try {
        $flddrug = Drug::where('fldroute', $fldroute)->pluck('flddrug');
        $medicines = MedicineBrand::select('fldbrandid')
            ->whereRaw('lower(fldbrand) like ?', array('%'))
            ->where('fldmaxqty', '<>', '-1')
            ->where('fldactive', 'Active')
            ->whereIn('flddrug', $flddrug)
            ->orderby('fldbrand', 'ASC')
            ->get();

        $html = '<option value=""></option>';
        if(count($medicines) > 0) {
            foreach($medicines as $medicine) {
                $html .= '<option value="'.$medicine->fldbrandid.'">'.$medicine->fldbrandid.'</option>';
            }
        }

        $response['message'] = 'success';
        $response['html'] = $html;

        } catch(\Exception $e) {


            $response['messagedetail'] = $e->getMessage();
            $response['message'] = "error";
        }

        return json_encode($response);
    }


    public function addProductGroup(Request $request) {
        $requestdata = $request->all();
        unset($requestdata['_token']);

        $response = array();
        try {

            ProductGroup::insert($requestdata);
            $productgroups = ProductGroup::where('fldmedgroup', $requestdata['fldmedgroup'])->orderBy('fldid', 'ASC')->get();
            $html = '<thead><th>Route</th><th>Particulars</th><th>Dose</th><th>Unit</th><th>Freq</th><th>Days</th><th>QTY</th><th>Start Hour</th><th>Action</th></thead>';
            $html .= '<tbody>';

            if(count($productgroups) > 0) {
                foreach($productgroups as $productgroup) {
                    $html .= '<tr><td>'.$productgroup->fldroute.'</td><td>'.$productgroup->flditem.'</td><td>'.$productgroup->flddose.'</td><td>'.$productgroup->flddoseunit.'</td><td>'.$productgroup->fldfreq.'</td><td>'.$productgroup->fldday.'</td><td>'.$productgroup->fldqty.'</td><td>'.$productgroup->fldstart.'</td><td><button title="delete '. $productgroup->flditem .'" class="deleteproductgroup" data-href="'. route('pharmacist.protocols.deleteproductgroup',  $productgroup->fldid ) .'"><i class="fa fa-trash"></i></button><td></tr>';
                }
            }

            $html .= '<tbody>';

            $response['message'] = 'success';
            $response['successmessage'] = 'productgroup added successfully';
            $response['html'] = $html;
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";

//            $response = 'something went wrong while deleting category';
        }

        return json_encode($response);


    }

    public function loadproductMedGroup(Request $request) {
        $fldmedgroup = $request->fldmedgroup;
        $response = array();
        try {

            $productgroups = ProductGroup::where('fldmedgroup', $fldmedgroup)->orderBy('fldid', 'ASC')->get();
            $html = '<thead><th>Route</th><th>Particulars</th><th>Dose</th><th>Unit</th><th>Freq</th><th>Days</th><th>QTY</th><th>Start Hour</th><th>Action<th></thead>';
            $html .= '<tbody>';

            if(count($productgroups) > 0) {
                foreach($productgroups as $productgroup) {
                    $html .= '<tr><td>'.$productgroup->fldroute.'</td><td>'.$productgroup->flditem.'</td><td>'.$productgroup->flddose.'</td><td>'.$productgroup->flddoseunit.'</td><td>'.$productgroup->fldfreq.'</td><td>'.$productgroup->fldday.'</td><td>'.$productgroup->fldqty.'</td><td>'.$productgroup->fldstart.'</td><td><button title="delete '. $productgroup->flditem .'" class="deleteproductgroup" data-href="'. route('pharmacist.protocols.deleteproductgroup',  $productgroup->fldid ) .'"><i class="fa fa-trash"></i></button><td></tr>';
                }
            }

            $html .= '<tbody>';

            $response['message'] = 'success';
            $response['html'] = $html;
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";

//            $response = 'something went wrong while deleting category';
        }

        return json_encode($response);
    }

    public function exportToPdfAll() {
        $data = [];
        $medgroups = ProductGroup::select('fldmedgroup')->groupby('fldmedgroup')->get();
        $data['medgroups'] = $medgroups;
        return view('pharmacist::layouts.pdfs.list', $data);
        // $pdf = view('pharmacist::layouts.pdfs.list', $data);
        // // $pdf->setpaper('a4');

        // return $pdf->download('list.pdf');
    }

    public function exportMedicineMedgroup($fldmedgroup) {
        $data = [];
        $productgroups = Pharmacisthelpers::getAllPrdoctgroupsFromMedGroup($fldmedgroup);

        $data['fldmedgroup'] = $fldmedgroup;
        $data['productgroups'] = $productgroups;
        return view('pharmacist::layouts.pdfs.export', $data);
        // $pdf = view('pharmacist::layouts.pdf.export', $data);
        // $pdf->setpaper('a4');

        // return $pdf->download('export_'. $fldmedgroup .' .pdf');
    }

    public function DeleteProductGroup($fldid) {
        $response = array();
        try {

            $productgroup = ProductGroup::find($fldid);

            if($productgroup) {
                $productgroup->delete();
            }
            $response['successmessage'] = 'product group deleted successfully';
            $response['message'] = 'success';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['errormessage'] = 'Something went Wrong';
            $response['message'] = "error";
        }

        return json_encode($response);
    }
}
