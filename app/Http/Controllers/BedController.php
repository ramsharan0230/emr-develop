<?php

namespace App\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Session;

/**
 * Class OutpatientController
 * @package Modules\Outpatient\Http\Controllers
 */
class BedController extends Controller
{
    public function index()
    {
        $html = '';

        try {
            // $bedData = Helpers::getDepartmentBed();
            // $departmentBed = $bedData['departmentBedList'];
            // $departmentBedOrder = $bedData['departmentFloor'];
            $departmentBedOrder = \App\Bedfloor::with('departmentBed')->orderBy('order_by', "ASC")->get();
            $departmentBed = \App\Departmentbed::orderBy('flddept', "DESC")->orderBy('fldfloor', "ASC")->get();
            $floorkeyArray = [];
            $departmentArray = [];

            if($departmentBed){
                    // $html .='<h1>Hello</h1>';
                    $html .= '<div class="row m-0">';
                            foreach($departmentBedOrder as $order){
                                foreach($departmentBed->where('fldfloor', $order->name)->all() as $floorKey){
                                    if(!in_array($floorKey->fldfloor, $floorkeyArray) || !in_array($floorKey->flddept, $departmentArray)){
                                            array_push($floorkeyArray, $floorKey->fldfloor);
                                            array_push($departmentArray, $floorKey->flddept);
                                    $html .='<div class="col-12 p-0">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb iq-bg-primary mb-0 p-2">
                                                    <li class="breadcrumb-item">'
                                                        .$floorKey->fldfloor.
                                                    '</li>
                                                    <li class="breadcrumb-item">'
                                                        .$floorKey->flddept.
                                                    '</li>
                                                </ol>
                                            </nav>
                                        </div>';
                                    }
                                $html .='<div class="col-sm-1 text-center">';
                                        if($floorKey['fldencounterval'] == "")
                                        {
                                            $html  .='<img src="'.asset("new/images/bed-1.png").'" class="img-bed" alt=""/>';
                                        }
                                        else{
                                            $html  .='<img data-toggle="popover" data-trigger="hover" data-html="true" src="'.asset("new/images/bed-occupied.png").'" class="img-bed bedDesc" data-bed="'.$floorKey->fldbed.'" data-encounter-id="'.$floorKey->fldencounterval.'" alt="'.$floorKey['fldencounterval'].'"/>';
                                        }
                                        $html .='<p>'.$floorKey['fldbed'].'</p>';
                                $html .='</div>';
                            }
                        }
                        $html .='</div>';
                }
            
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => [
                    'status' => false,
                    'html' => $html,
                ]
            ]);
        }
    }
}
