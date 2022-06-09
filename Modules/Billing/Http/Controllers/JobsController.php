<?php

namespace Modules\Billing\Http\Controllers;

use App\Banks;
use App\BillingSet;
use App\Encounter;
use App\Entry;
use App\Fiscalyear;
use App\Http\Controllers\Controller;
use App\OtGroupSubCategory;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientInfo;
use App\ServiceCost;
use App\ServiceGroup;
use App\Services\DepartmentRevenueService;
use App\Services\PatBillingShareService;
use App\TaxGroup;
use App\Utils\Helpers;
use App\UserShare;
use App\Utils\Options;
use App\Year;
use Auth;
use Carbon\Carbon;
use CogentHealth\Ssf\Claim\Claim;
use CogentHealth\Ssf\Claim\ClaimItem;
use CogentHealth\Ssf\Ssf;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Log;
use Session;
use Throwable;
use App\Services\MaternalisedService;

/**
 * Class BillingController
 * @package Modules\Billing\Http\Controllers
 */
class JobsController extends Controller
{

    public function __construct()
    {

    }

    public function updateformaterialised($token){
        if ($token === "crone-COGENT$$") {
        $bills = PatBilling::select('tblpatbilldetail.fldbillno','tblpatbilldetail.payment_mode','tblpatbilldetail.fldencounterval')
        ->join('tblpatbilldetail','tblpatbilldetail.fldbillno','=','tblpatbilling.fldbillno')
        ->where('tblpatbilldetail.fldbillno','LIKE','%CAS%')
        ->where('tblpatbilldetail.fldcomp','LIKE','%comp01%')
        ->groupBy('tblpatbilldetail.fldbillno')
        ->limit(200)->get();
        //dd($bills);

        if($bills){
            foreach($bills as $k => $bill){
                MaternalisedService::insertMaternalisedFiscal($bill->fldencounterval,$bill->fldbillno,$bill->payment_mode);
                echo $k.'<br>';
            }
        }

    }else{
        echo 'non';
    }


    }

    public function updateformaterialisedpharmacy($token){
        if ($token === "crone-COGENT$$") {
        $bills = PatBilling::select('tblpatbilldetail.fldbillno','tblpatbilldetail.payment_mode','tblpatbilldetail.fldencounterval')
        ->join('tblpatbilldetail','tblpatbilldetail.fldbillno','=','tblpatbilling.fldbillno')
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldcomp','LIKE','%comp01%')
        ->groupBy('tblpatbilldetail.fldbillno')
        ->limit(200)->get();

        if($bills){
            foreach($bills as $k => $bill){
                MaternalisedService::insertMaternalisedFiscalPharmacy($bill->fldencounterval,$bill->fldbillno,$bill->payment_mode);
                echo $k.'<br>';
            }
        }
    }




    }


    public function updatepatbillingforamount(){
        // fldamot n discount
        // patbilldetail ma milcha pat ill ma wrong checkdate

        $bills = PatBilling::select('tblpatbilldetail.fldbillno',DB::raw('sum(tblpatbilldetail.flditemrate*tblpatbilldetail.flditemqty) AS totalamt'),'tblpatbilldetail.fldencounterval')
        ->join('tblpatbilldetail','tblpatbilldetail.fldbillno','=','tblpatbilling.fldbillno')
        ->where('tblpatbilldetail.fldcomp','LIKE','%comp01%')
        ->groupBy('tblpatbilldetail.fldbillno')
        ->paginate(200);

        if($bills){
            foreach($bills as $bill){
                    $data = [
                        'flditemamt' => $bill->totalamt,
                        'fldi' => $bill->totalamt,
                    ];

                    PatBilling::where('fldbillno',$bill->fldbillno)->update($data);
            }
        }

    }


}
