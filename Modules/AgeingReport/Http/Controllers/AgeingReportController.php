<?php

namespace Modules\AgeingReport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Options;
use App\Exports\AgeingReportExport;
use Maatwebsite\Excel\Facades\Excel;




use App\Utils\Helpers;

use Auth;
use Carbon\Carbon;

use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Log;
use Session;
use Throwable;




use App\GeneralLedgerMap;
use App\AutoId;
use App\Entry;
use App\EntryAccountLedgerView;
use App\HospitalDepartmentUsers;
use App\TempTransaction;
use App\TransactionMaster;
use App\TempDepositTransaction;
use App\AccountLedger;
use App\AgeingAccountLedgerMap;




use App\PatBillDetail;
use App\PatBilling;



class AgeingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['date'] = date('Y-m-d');
        $page =AgeingAccountLedgerMap::get();
        $data['page'] = $page;
        return view('ageingreport::index',$data);
    }

    public function setting(){
        $data['ledgers'] = AccountLedger::get();
        $data['intervals'] = Options::get('ageing_interval');

        //json_encode($myObj);
        return view('ageingreport::setting.mapping',$data);
    }

    public function settingadd(Request $request){
        $page = $request->type;
        $ledger = $request->accountledger;
        $accountLeger = json_encode($request->accountledger);


        $insertarray = [
            'page' => $page,
            'value' => $accountLeger,
            'accountType' => $request->accountType
        ];

        $post = AgeingAccountLedgerMap::updateOrCreate([
            'page' => $page
        ], $insertarray);


        Session::flash('success_message', 'Inserted Successfully.');
        //ageing.setting.mapping
        // return response()->json([
        //     'success' => [
        //         'page' =>  $page,
        //         'selectledger' => $ledger
        //     ]
        // ]);


    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getPageAccountLedger(Request $request)
    {
        $page = $request->page;
        $aging = AgeingAccountLedgerMap::where('page',$page)->first();
        $selectledger = $aging->value;
        return response()->json([
            'success' => [
                'page' =>  $page,
                'selectledger' => $selectledger,
                'transaction' => $aging->accountType
            ]
        ]);


    }



    public function setinterval(Request $request)
    {

        //json_encode($myObj);
        return view('ageingreport::setting.mapping',$data);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function savesetinterval(Request $request)
    {

            Options::update('ageing_interval', $request->post('ageing_interval'));
            Session::flash('success', 'Records updated successfully.');
            return response()->json([
                'success' => [
                    'ageing_interval' =>  $request->ageing_interval,

                ]
            ]);


    }




    public function report(Request $request)
    {



        $data['from_date'] = $from_date = $request->get('from_date') ?? '';
        $data['eng_from_date'] = $eng_from_date = $request->get('eng_from_date') ? $request->get('eng_from_date') : '';
        $data['page'] = $page = $request->get('page') ?? '';
        $data['transactionType'] = $transactionType = $request->get('transactionType') ?? '';

        ob_end_clean();
        ob_start();
        return Excel::download(new AgeingReportExport($from_date, $eng_from_date,$page,$transactionType), 'Ageing-Report-'.$eng_from_date.'('.$from_date.')'.'.xlsx');






    }


}
