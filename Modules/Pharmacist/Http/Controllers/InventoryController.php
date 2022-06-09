<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\Extra;
use App\ExtraBrand;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class InventoryController extends Controller
{

    public function index()
    {
        // echo "hserfdef"; exit;
        Helpers::jobRecord('fmmedreport', 'Inventory Report');
        return view('pharmacist::inventory.inventory');
    }
   
    public function surgical()
    {
        return view('pharmacist::surgical');
    }

    

    public function getVariables()
    {
        $get_all_variables = Extra::select('fldid', 'fldextraid as col')->orderBy('fldextraid')->get();
        return response()->json($get_all_variables);
    }

    
}
