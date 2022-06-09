<?php

namespace Modules\Billing\Http\Controllers;

use App\Fiscalyear;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;

class FiscalDataController extends Controller
{

    private $_api_context_ird = [];

    /**
     * ConvergentPaymentController constructor.
     */
    public function __construct()
    {
        $this->_api_context_ird['mode'] = \Options::get('ird_mode');

        if ($this->_api_context_ird['mode'] === 'test') {
            $this->_api_context_ird['ird_url'] = \Options::get('ird_test_server_url');
            $this->_api_context_ird['username'] = \Options::get('ird_test_username');
            $this->_api_context_ird['password'] = \Options::get('ird_test_password');
        } else {
            $this->_api_context_ird['ird_url'] = \Options::get('ird_live_server_url');
            $this->_api_context_ird['username'] = \Options::get('ird_live_username');
            $this->_api_context_ird['password'] = \Options::get('ird_live_password');
        }
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            if ($request->exists('from_date') && $request->exists('to_date')) {
                $from = $request->from_date . ' 00:00:00';
                $to = $request->to_date . ' 23:59:59';
            } else {
                $from = date('Y-m-d', strtotime(now())) . ' 00:00:00';
                $to = date('Y-m-d', strtotime(now())) . ' 23:59:59';
            }

            $data['fiscal'] = Fiscalyear::select('*')
                ->where('Sync_with_IRD', 0)
                ->orderBy('Bill_Date', 'Desc')
                ->whereBetween('Bill_Date', [$from, $to])
                ->paginate(50);

            return view('billing::fiscal-list', $data);
        } catch (\Exception $e) {

        }

    }

    public function syncFiscalWithIRD()
    {
        $fiscalData = Fiscalyear::where('Sync_with_IRD', 0)->get();

        foreach ($fiscalData as $datum) {
            $postData = [
                'username' => $this->_api_context_ird['username'],
                'password' => $this->_api_context_ird['password'],
                'seller_pan' => \Options::get('hospital_pan') ?? '',
                'buyer_pan' => $datum->Customer_pan ?? '',
                'buyer_name' => $datum->Customer_name,
                'fiscal_year' => $datum->Fiscal_Year,
                'invoice_number' => $datum->Bill_no,
                'invoice_date' => $datum->Bill_Date,
                'total_sales' => Helpers::numberFormat($datum->Total_Amount,"insert"),
                'taxable_sales_vat' => Helpers::numberFormat($datum->Total_Amount,"insert"),
                'vat' => Helpers::numberFormat($datum->Tax_Amount,"insert"),
                'excisable_amount' => 0,
                'excise' => 0,
                'taxable_sales_hst' => 0,
                'hst' => 0,
                'amount_for_esf' => 0,
                'esf' => 0,
                'export_sales' => 0,
                'tax_exempted_sales' => 0,
                'isrealtime' => false,
                'datetimeclient' => date("Y-m-d H:i:s")
            ];

            $client = new Client();
            $res = $client->request('POST', $this->_api_context_ird['ird_url'], ['json' => json_encode($postData)]);
            $responseIRD = $res->getBody()->getContents();
            if ($responseIRD->IsSuccessStatusCode) {
                Fiscalyear::where('field', $datum->field)->update(['Sync_with_IRD' => 1]);
            }
        }

        return "IRD sync Done";
    }

    public function syncIndividualIRD($fiscalId)
    {
        //loop ma garnu paryo
        $fiscalData = Fiscalyear::where('Sync_with_IRD', 0)->where('field', $fiscalId)->get();

        foreach ($fiscalData as $datum) {
            $postData = [
                'username' => $this->_api_context_ird['username'],
                'password' => $this->_api_context_ird['password'],
                'seller_pan' => \Options::get('hospital_pan') ?? '',
                'buyer_pan' => $datum->Customer_pan ?? '',
                'buyer_name' => $datum->Customer_name,
                'fiscal_year' => $datum->Fiscal_Year,
                'invoice_number' => $datum->Bill_no,
                'invoice_date' => $datum->Bill_Date,
                'total_sales' => Helpers::numberFormat( $datum->Total_Amount,"insert"),
                'taxable_sales_vat' => Helpers::numberFormat($datum->Total_Amount,"insert"),
                'vat' => Helpers::numberFormat($datum->Tax_Amount,"insert"),
                'excisable_amount' => 0,
                'excise' => 0,
                'taxable_sales_hst' => 0,
                'hst' => 0,
                'amount_for_esf' => 0,
                'esf' => 0,
                'export_sales' => 0,
                'tax_exempted_sales' => 0,
                'isrealtime' => true,
                'datetimeclient' => date("Y-m-d H:i:s")
            ];

            $client = new Client();
            $res = $client->request('POST', $this->_api_context_ird['ird_url'], ['json' => json_encode($postData)]);

            $responseIRD = $res->getBody()->getContents();
            if ($responseIRD->IsSuccessStatusCode) {
                Fiscalyear::where('field', $datum->field)->update(['Sync_with_IRD' => 1]);
            }

        }

        return "IRD sync Done";
    }
}
