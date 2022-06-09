<?php

namespace Modules\Reports\Http\Controllers;

use App\AutogroupDoctor;
use App\BillingSet;
use App\Department;
use App\Events\StockLive;
use App\Purchase;
use App\PurchaseBill;
use App\Transfer;
use App\StockReturn;
use App\BulkSale;
use App\Year;
use App\Adjustment;
use App\Encounter;
use App\Exports\DepositReportExport;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\Utils\Options;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Exports\ItemLedgerReportExport;
use Excel;
use Illuminate\Support\Facades\Cache;
use Auth;
use Carbon\CarbonPeriod;

class ItemledgerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['departments'] = Department::all();
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $user = Auth::guard('admin_frontend')->user();
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        // if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
        //     $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        // } else {
        //     $data['hospital_department'] =HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        // }
        $data['medicines'] = $this->getMedicineList($request);
        return view('reports::itemledger.index', $data);
    }

    public function getMedicineList(Request $request)
    {
        $dispensing_medicine_stock = Options::get('dispensing_medicine_stock');
        $orderBy = $request->get('orderBy', 'brand');
        $medcategory = $request->get('medcategory', 'Medicines');
        $billingmode = (isset($_GET['billingmode']) && $_GET['billingmode']) ? $_GET['billingmode'] : 'General';
        $compname = Helpers::getCompName();

        $table = "tblmedbrand";
        $fldnarcotic = "tblmedbrand.fldnarcotic";
        $drugJoin = "INNER JOIN tbldrug ON tblmedbrand.flddrug=tbldrug.flddrug";
        $routeCol = "tbldrug.fldroute";
        $fldpackvol = "$table.fldpackvol";
        if ($medcategory == 'Surgicals') {
            $table = "tblsurgbrand";
            $fldnarcotic = "'No' AS fldnarcotic";
            $drugJoin = "INNER JOIN tblsurgicals ON $table.fldsurgid=tblsurgicals.fldsurgid";
            $routeCol = "tblsurgicals.fldsurgcateg AS fldroute";
            $fldpackvol = "'1' AS fldpackvol";
        } elseif ($medcategory == 'Extra Items') {
            $table = "tblextrabrand";
            $fldnarcotic = "'No' AS fldnarcotic";
            $drugJoin = "";
            $routeCol = "'extra' AS fldroute";
            $fldpackvol = "$table.fldpackvol";
        }

        // $route = $request->get('route');
        $is_expired = $request->get('is_expired');
        $expiry = date('Y-m-d H:i:s');
        if ($is_expired)
            $expiry = $expiry;
        // $expiry = date('Y-m-d H:i:s', strtotime('-20 years', strtotime($expiry)));

        $orderString = "tblmedbrand.fldbrand ASC";
        if ($dispensing_medicine_stock == 'FIFO')
            $orderString = "tblentry.fldstatus DESC";
        elseif ($dispensing_medicine_stock == 'LIFO')
            $orderString = "tblentry.fldstatus ASC";
        elseif ($dispensing_medicine_stock == 'Expiry') {
            $days = Options::get('dispensing_expiry_limit');
            if ($days)
                $expiry = date('Y-m-d H:i:s', strtotime("+{$days} days", strtotime($expiry)));
            $orderString = "tblentry.fldexpiry ASC";
        }


        $whereParams = [
            0,
            $medcategory,
            $compname,
            'Active',
            $expiry,
        ];

        $additionalJoin = "";
        $ratecol = "tblentry.fldsellpr";
        if ($billingmode != 'General') {
            $additionalJoin = "INNER JOIN tblstockrate ON tblentry.fldstockid=tblstockrate.flditemname";
            $ratecol = "tblstockrate.fldrate AS fldsellpr";
        }

        $sql = "";
        if ($orderBy == 'brand') {
            $sql = "
                SELECT tblentry.fldstockno, tblentry.fldstatus, $table.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, $ratecol, tblentry.fldcategory, $routeCol, tblentry.fldbatch, $fldnarcotic, $fldpackvol, $table.fldvolunit
                FROM $table
                INNER JOIN tblentry ON tblentry.fldstockid=$table.fldbrandid
                $additionalJoin
                $drugJoin
                WHERE
                    tblentry.fldqty>? AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory=? AND
                    tblentry.fldcomp=? AND
                    $table.fldactive=? AND
                    tblentry.fldexpiry>= ?
                GROUP BY tblentry.fldstockid
                ORDER BY $orderString";
        } else {
            $sql = "
                SELECT tblentry.fldstockno, tblentry.fldstockid, tblentry.fldexpiry, tblentry.fldqty, $ratecol, tblentry.fldstatus, tblentry.fldcategory, $routeCol, tblentry.fldbatch, $fldnarcotic, $fldpackvol, $table.fldvolunit
                FROM tblentry
                INNER JOIN $table ON tblentry.fldstockid=$table.fldbrandid
                $additionalJoin
                $drugJoin
                WHERE
                    tblentry.fldqty>? AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory=? AND
                    tblentry.fldcomp=? AND
                    tblentry.fldstockid IN (
                        SELECT $table.fldbrandid
                        FROM $table
                        WHERE
                            $table.fldactive=?
                        ) AND
                    tblentry.fldexpiry>=?
                GROUP BY tblentry.fldstockid
                ORDER BY $orderString";
        }

        $data = \DB::select($sql, $whereParams);
        $data = Helpers::appendExpiryStatus($data);

        if ($request->ajax())
            return response()->json($data);

        return $data;
    }

    public function searchLedgerReport(Request $request)
    {
        // dd($request->all());
        try {
            $today_date = Carbon::now()->format('Y-m-d');

            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $request->eng_from_date)->where('fldlast', '>=', $request->eng_from_date)->first();

            $day_before = date('Y-m-d', strtotime($request->eng_from_date . ' -1 day'));

            /* Closing */
            $openingpurchase2sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0)as balamt,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname='OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $fiscal_year->fldfirst . "' and '" . $request->eng_from_date . "') and fldcomp='" . $request->department . "' and fldsav=0 ) union ALL ";
            // echo $openingpurchase2sql; exit;
            // $openingpurchase2 = \DB::select($openingpurchase2sql);

            $purchase2sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0) as balamt,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $fiscal_year->fldfirst . "' and '" . $request->eng_from_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";
            // $purchase2 = \DB::select($purchase2sql);

            $stockrecieved2sql = "(select ifnull(sum(fldqty),0) as purqty,ifnull(sum(fldqty-fldqty),0) as isqty,ifnull(sum(fldqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldqty*fldnetcost),0)as puramt,ifnull((fldnetcost-fldnetcost),0) as isamt,ifnull(sum(fldqty*fldnetcost),0) as balamt,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $fiscal_year->fldfirst . "') and fldtocomp='" . $request->department . "' and fldtosav=1)";
            // $stockreceived2 = \DB::select($stockrecieved2sql);

            $closingsql = "(select '" . $day_before . "' as e,'Closing' as f,'**' as g,sum(purqty)as h,sum(isqty)as i,sum(balqty)as j,sum(rate)as k,sum(puramt)as l,sum(isamt)as m,sum(balamt)as n,o as o,p as p from(" . $purchase2sql . " " . $openingpurchase2sql . " " . $stockrecieved2sql . ")as total) union all";

            $openingpurchasesql = "(select date_format(fldtime,'%Y-%m-%d') As e, concat( 'Opening Stock: ',fldbillno) as f,fldreference as g,fldtotalqty as h,(fldtotalqty-fldtotalqty) as i,fldtotalqty as j,fldnetcost as k,fldtotalcost as l,(fldtotalcost-fldtotalcost) as m,fldtotalcost as n,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname='OPENING STOCK' and fldcomp='" . $request->department . "' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldsav=0) union";
            // $openingpurchase = \DB::select($openingpurchasesql);

            $purchasesql = "(select date_format(fldtime,'%Y-%m-%d') As e,concat('Pur from ',fldsuppname,':',fldbillno) as f,fldreference as g,fldtotalqty as h,(fldtotalqty-fldtotalqty) as i,fldtotalqty as j,fldnetcost as k,fldtotalcost as l,(fldtotalcost-fldtotalcost) as m,fldtotalcost as n,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union";
            // $purchase = \DB::select($purchasesql);

            $stockrecievedsql = "(select date_format(fldtoentrytime,'%Y-%m-%d') As e,concat('Recvd from:',fldfromcomp)as f,fldreference as g,fldqty as h,(fldqty-fldqty)as i,fldqty as j,fldnetcost as k,(fldqty*fldnetcost)as l,((fldqty*fldnetcost)-(fldqty*fldnetcost))as m,(fldqty*fldnetcost)as n,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' and fldtosav=1) union";
            // $stockreceived = \DB::select($stockrecievedsql);

            $stocktransferredsql = "(select date_format(fldfromentrytime,'%Y-%m-%d') As e,concat('Transfer to:',fldtocomp)as f,fldreference as g,(fldqty-fldqty) as h,fldqty as i,(0-fldqty) as j,fldnetcost as k,((fldqty*fldnetcost)-(fldqty*fldnetcost))as l,(fldqty*fldnetcost) as m,(0-(fldqty*fldnetcost))as n,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' and fldfromsav=1) union";
            // $stocktransferred = \DB::select($stocktransferredsql);

            $stockreturnsql = "(select date_format(a.fldtime,'%Y-%m-%d') As e,concat('Return to ',b.fldsuppname,':',a.fldreference) as f,a.fldnewreference as g,(a.fldqty-a.fldqty) as h,a.fldqty as i,(0-a.fldqty) as j,a.fldcost as k,((a.fldqty*a.fldcost)-(a.fldqty*a.fldcost)) as l,(a.fldqty*a.fldcost) as m,(0-(a.fldqty*a.fldcost)) as n,fldstockno as o,fldstockno as p from tblstockreturn a join tblpurchasebill b on a.fldreference=b.fldreference where a.fldstockid='" . $request->search_medecine . "' and (cast(a.fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and a.fldsave=1) union";
            // $stockreturn = \DB::select($stockreturnsql);

            $consumesql = "(select date_format(fldtime,'%Y-%m-%d') As e,concat('Bulk sale to ',fldtarget)as f,fldreference as g,(fldqtydisp-fldqtydisp) as h,fldqtydisp as i,(0-fldqtydisp) as j,fldnetcost as k,((fldqtydisp*fldnetcost)-(fldqtydisp*fldnetcost)) as l,(fldqtydisp*fldnetcost) as m,(0-(fldqtydisp*fldnetcost))as n,fldstockno as o,fldstockno as p from tblbulksale where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1) union";
            // $consume = \DB::select($consumesql);

            $adjustsql = "(select date_format(fldtime,'%Y-%m-%d') As e,'Adjustment' as f,fldreference as g,fldcurrqty as h,fldcompqty as i,(fldcurrqty-fldcompqty)as j,fldnetcost as k,(fldcurrqty*fldnetcost)as l,(fldcompqty*fldnetcost)as m,((fldcurrqty*fldnetcost)-(fldcompqty*fldnetcost))as n,fldstockno as o,fldstockno as p from tbladjustment where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=1) union";
            // $adjust = \DB::select($adjustsql);

            $dispensesql = "(select date_format(fldtime,'%Y-%m-%d') As e,fldbillno as f, flduserid as g,(flditemqty-flditemqty) as h, flditemqty as i,(0-flditemqty) as j,flditemrate as k,(fldditemamt-fldditemamt) as l,fldditemamt as m,(0-fldditemamt) as n,flditemno as o,flditemno as p from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'PHM%') union";
            // $dispense = \DB::select($dispensesql);

            $cancelsql = "(select date_format(fldtime,'%Y-%m-%d') As e,fldbillno as f, flduserid as g,abs(flditemqty) as h, (flditemqty-flditemqty) as i,abs(flditemqty) as j,flditemrate as k,abs(fldditemamt) as l,(fldditemamt-fldditemamt) as m,abs(fldditemamt) as n,flditemno as o,flditemno as p from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'RET%')";
            // $cancel = \DB::select($cancelsql);


            $resultDataSql = "select e,f,g,h,i,j,k,l,m,n,o,p from(" . $closingsql . "(select e, f, g, h, i, j, k, l, m, n,o,p from(" . $openingpurchasesql . $purchasesql . $stockrecievedsql . $stocktransferredsql . $stockreturnsql . $consumesql . $adjustsql . $dispensesql . $cancelsql . ") As Tbl order by e desc)) As Ta order by e";
            // echo $resultDataSql; exit;
            $finalresult = \DB::select($resultDataSql);
            // dd($finalresult);


            /*TOTAL SUM QTY*/

            $openingpurchase1sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0)as balamt from tblpurchase where fldsuppname='OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";

            $purchase1sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0) as balamt from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";

            $stockreceived1sql = "(select ifnull(sum(fldqty),0) as purqty,ifnull(sum(fldqty-fldqty),0) as isqty,ifnull(sum(fldqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldqty*fldnetcost),0)as puramt,ifnull((fldnetcost-fldnetcost),0) as isamt,ifnull(sum(fldqty*fldnetcost),0) as balamt from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' and fldtosav=1) union all";

            $stocktransferred1sql = "(select  ifnull(sum(fldqty-fldqty),0) as purqty,ifnull(sum(fldqty),0) as isqty,ifnull((0-sum(fldqty)),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull((fldnetcost-fldnetcost),0) as puramt,ifnull(sum(fldqty*fldnetcost),0) as isamt,ifnull((0-sum(fldqty*fldnetcost)),0)as balamt from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' and fldfromsav=1) union all";

            $stockreturn1sql = "(select ifnull(sum(a.fldqty-a.fldqty),0) as purqty,ifnull(sum(a.fldqty),0) as isqty,ifnull((0-sum(a.fldqty)),0) as balqty,ifnull(sum(a.fldcost),0) as rate,ifnull((a.fldqty-a.fldqty),0) as puramt,ifnull(sum(a.fldqty*a.fldcost),0) as isamt,ifnull(0-sum(a.fldqty*a.fldcost),0) as balamt from tblstockreturn a where a.fldstockid='" . $request->search_medecine . "' and (cast(a.fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and a.fldsave=1) union all";

            $consume1sql = "(select ifnull(sum(fldqtydisp-fldqtydisp),0) as purqty,ifnull(sum(fldqtydisp),0) as isqty,ifnull((0-sum(fldqtydisp)),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull((fldnetcost-fldnetcost),0) as puramt,ifnull(sum(fldqtydisp*fldnetcost),0) as isamt,ifnull(0-sum(fldqtydisp*fldnetcost),0)as balamt from tblbulksale where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1) union all";

            $adjust1sql = "(select ifnull(sum(fldcurrqty),0) as purqty,ifnull(sum(fldcompqty),0) as isqty,ifnull(sum(fldcurrqty-fldcompqty),0)as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldcurrqty*fldnetcost),0)as puramt,ifnull(sum(fldcompqty*fldnetcost),0)as isamt,ifnull(sum((fldcurrqty-fldcompqty)*fldnetcost),0)as balamt from tbladjustment where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=1) union all";

            $dispense1sql = "(select ifnull(sum(flditemqty-flditemqty),0) as purqty,ifnull(sum(flditemqty),0) as isqty,ifnull(sum(0-flditemqty),0) as balqty,ifnull(sum(flditemrate),0) as rate,ifnull(sum(fldditemamt-fldditemamt),0) as puramt,ifnull(sum(fldditemamt),0) as isamt,ifnull(sum(0-fldditemamt),0) as balamt from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'PHM%') union all";

            $cancel1sql = "(select ifnull(sum(abs(flditemqty)),0) as purqty,ifnull(sum(flditemqty-flditemqty),0) as isqty,ifnull(abs(sum(flditemqty)),0) as balqty,ifnull(sum(flditemrate),0) as rate,ifnull(sum(abs(fldditemamt)),0) as puramt,ifnull((fldditemamt-fldditemamt),0) as isamt,ifnull(sum(abs(fldditemamt)),0) as balamt from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'RET%')";


            $closing1sql = "(select sum(purqty)as purqty,sum(isqty)as isqty,sum(balqty)as balqty,sum(rate)as rate,sum(puramt)as puramt,sum(isamt)as isamt,sum(balamt)as balamt from(" . $purchase2sql . " " . $openingpurchase2sql . " " . $stockrecieved2sql . ") as totl) union all";

            $total = "select sum(purqty)as h,sum(isqty)as i,sum(balqty)as j,sum(rate)as k,sum(puramt)as l,sum(isamt)as m,sum(balamt)as n from (" . $closing1sql . " " . $purchase1sql . " " . $openingpurchase1sql . " " . $stockreceived1sql . " " . $stocktransferred1sql . " " . $stockreturn1sql . " " . $adjust1sql . " " . $consume1sql . " " . $dispense1sql . " " . $cancel1sql . ") as total";

            $html = '';
            if (isset($finalresult) and count($finalresult) > 0) {
                foreach ($finalresult as $resultd) {
                    $expiry = \DB::table('tblentry')->select('fldexpiry')->where('fldstockno', $resultd->o)->first();
                    $expirydate = (isset($expiry) and !is_null($expiry)) ? $expiry->fldexpiry : "";

                    $html .= '<tr>';
                    $html .= '<td>' . $resultd->e . '</td>';
                    $html .= '<td>' . $resultd->f . '</td>';
                    $html .= '<td>' . $resultd->g . '</td>';
                    $html .= '<td>' . $resultd->f . '</td>';
                    $html .= '<td>' . $resultd->i . '</td>';
                    $html .= '<td>' . $resultd->j . '</td>';
                    $html .= '<td>' . $resultd->k . '</td>';
                    $html .= '<td>' . $resultd->l . '</td>';
                    $html .= '<td>' . $resultd->m . '</td>';
                    $html .= '<td>' . $resultd->n . '</td>';
                    $html .= '<td>' . $expirydate . '</td>';
                    $html .= '<td>' . $resultd->p . '</td>';
                    $html .= '</tr>';
                }
            }
            echo $html;

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function searchNewLedgerReport(Request $request)
    {
        try {
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $request->eng_from_date)->where('fldlast', '>=', $request->eng_from_date)->first();

            $day_before = date('Y-m-d', strtotime($request->eng_from_date . ' -1 day'));

            $opening_sql = "with cte as (
select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
            select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockid= '" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_from_date . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
            select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockid= '" . $request->search_medecine . "' and (cast(fldtime as date) >= '" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_from_date . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
        select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemname='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)
        ) as T,  (SELECT @a:= 0) AS a
    JOIN (SELECT @running_total:=0) r
    ORDER BY T.datetime  ASC)
, cte2 as (
    select BalanceQty , Rate from cte order by serial_number desc limit 1
   )

, cte3 as (
	select sum(PurQty) as PurQty , sum(QtyIssue) as QtyIssue  from cte
)

select *, cte2.BalanceQty*cte2.Rate as BalAmt from cte2, cte3
";

            $opening_sql = \DB::select($opening_sql);
            $initialBalQty = 0;
            $initialPurQty = 0;
            $initialQtyIssue = 0;
            $initialRate = 0;
            $initialBalAmt = 0;

            if (isset($opening_sql)) {
                if (isset($opening_sql[0])) {
                    $initialBalQty = $opening_sql[0]->BalanceQty;
                    $initialPurQty = $opening_sql[0]->PurQty;
                    $initialQtyIssue = $opening_sql[0]->QtyIssue;
                    $initialRate = $opening_sql[0]->Rate;
                    $initialBalAmt = $opening_sql[0]->BalAmt;
                } else {
                    $initialBalQty = 0;
                    $initialPurQty = 0;
                    $initialQtyIssue = 0;
                    $initialRate = 0;
                    $initialBalAmt = 0;
                }
                $html = '';
                $html .= '<tr>';
                $html .= '<td></td>';
                $html .= '<td>Opening</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td>' . $initialBalQty . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($initialRate) . '</td>';
                $html .= '<td>' . $initialPurQty . '</td>';
                $html .= '<td>' . $initialQtyIssue . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat(($initialBalAmt)) . '</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '</tr>';
            } else {
                if (isset($opening_sql)) {
                    $html = '';
                    $html .= '<tr>';
                    $html .= '<td></td>';
                    $html .= '<td>Opening</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td>0</td>';
                    $html .= '<td>0</td>';
                    $html .= '<td>0</td>';
                    $html .= '<td>0</td>';
                    $html .= '<td>0</td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '</tr>';
                }
            }

            $calculation_sql = "select T.fldbatch as batch,T.description as description,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
        select fldbatch,concat('Pur from ',fldsuppname,':',fldbillno) as description,fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty+IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where fldtotalqty is not null and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0 Group by fldreference,cast(fldtime as date) union ALL
        select fldbatch,concat('Returned from:',hospital_departments.name)as description, fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldnewreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn join hospital_departments   on hospital_departments.fldcomp=tblstockreturn.fldcomp where fldqty is not null and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tblstockreturn.fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)  union ALL
        select tblentry.fldbatch as fldbatch,concat('Transfer to:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(tbltransfer.fldqty) as retQty,-sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldtocomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
         select tblentry.fldbatch as fldbatch,concat('Transfer From:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(tbltransfer.fldqty)  as purqty,0 as retQty,+sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldfromcomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
         select  tblentry.fldbatch as fldbatch,concat('Dispense From:',hospital_departments.name) as description,fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling join tblentry   on tblentry.fldstockno=tblpatbilling.flditemno join hospital_departments   on hospital_departments.fldcomp=tblpatbilling.fldcomp where flditemqty is not null and flditemname='" . $request->search_medecine . "'and (cast(fldtime as date) between '" . $request->eng_from_date . "'  and '" . $request->eng_to_date . "') and tblpatbilling.fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL
        select tblentry.fldbatch as fldbatch,concat('Bulk sale to ',fldtarget) as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale  join tblentry   on tblentry.fldstockno=tblbulksale.fldstockno where fldqtydisp is not null and tblbulksale.fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tblbulksale.fldcomp='" . $request->department . "' Group by tblbulksale.fldreference,cast(tblbulksale.fldtime as date) union ALL
        select tblentry.fldbatch as fldbatch,'Adjustment' as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment join tblentry   on tblentry.fldstockno=tbladjustment.fldstockno where fldcurrqty is not null and tbladjustment.fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tbladjustment.fldcomp='" . $request->department . "'   Group by tbladjustment.fldreference,cast(tbladjustment.fldtime as date)
    ) as T
JOIN (SELECT @running_total:='" . $initialBalQty . "') r
ORDER BY T.datetime ASC";

            $calculation_sql = \DB::select($calculation_sql);

            if (isset($calculation_sql) and count($calculation_sql) > 0) {
                $totalIssueAmt = 0;
                $totalPurAmt = 0;
                $lastRate = 0;
                $balanceQty = 0;
                foreach ($calculation_sql as $resultd) {
                    $expiry = \DB::table('tblentry')->select('fldexpiry')->where('fldstockid', $request->search_medecine)->first();

                    $expirydate = (isset($expiry) and !is_null($expiry)) ? $expiry->fldexpiry : "";
                    $expirydate = Carbon::createFromFormat('Y-m-d H:i:s', $expirydate)->format('Y-m-d');
                    $html .= '<tr>';
                    $html .= '<td>' . $resultd->datetime . '</td>';
                    $html .= '<td>' . $resultd->description . '</td>';
                    $html .= '<td>' . $resultd->reference . '</td>';
                    $html .= '<td>' . $resultd->PurQty . '</td>';
                    $html .= '<td>' . $resultd->QtyIssue . '</td>';
                    $html .= '<td>' . $resultd->BalanceQty . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($resultd->Rate) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat(($resultd->PurAmt)) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat(($resultd->IssueAmt)) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat(($resultd->BalAmt)) . '</td>';
                    $html .= '<td>' . $expirydate . '</td>';
                    $html .= '<td>' . $resultd->batch . '</td>';
                    $html .= '</tr>';
                    $totalIssueAmt += $resultd->IssueAmt;
                    $totalPurAmt += $resultd->PurAmt;
                    $lastRate = $resultd->Rate;
                    $balanceQty = $resultd->BalanceQty;
                }
                $totalPurAmt = $totalPurAmt + $initialPurQty;
                $totalIssueAmt = $totalIssueAmt + $initialQtyIssue;
                $html .= '<tr>';
                $html .= '<td></td>';
                $html .= '<td>Closing</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '<td>' . $balanceQty . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($lastRate) . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat(($totalPurAmt)) . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat(($totalIssueAmt)) . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat(($lastRate * $balanceQty)) . '</td>';
                $html .= '<td></td>';
                $html .= '<td></td>';
                $html .= '</tr>';
            }
            echo $html;

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage() . ' ' . $e->getLine(),
            ]);
        }
    }

    public function exportItemLedgerPdf(Request $request)
    {
        try {
            $today_date = Carbon::now()->format('Y-m-d');

            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $request->eng_from_date)->where('fldlast', '>=', $request->eng_from_date)->first();

            $day_before = date('Y-m-d', strtotime($request->eng_from_date . ' -1 day'));

            /* Closing */
            $openingpurchase2sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0)as balamt,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname='OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $fiscal_year->fldfirst . "' and '" . $request->eng_from_date . "') and fldcomp='" . $request->department . "' and fldsav=0 ) union ALL ";
            // echo $openingpurchase2sql; exit;
            // $openingpurchase2 = \DB::select($openingpurchase2sql);

            $purchase2sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0) as balamt,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $fiscal_year->fldfirst . "' and '" . $request->eng_from_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";
            // $purchase2 = \DB::select($purchase2sql);

            $stockrecieved2sql = "(select ifnull(sum(fldqty),0) as purqty,ifnull(sum(fldqty-fldqty),0) as isqty,ifnull(sum(fldqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldqty*fldnetcost),0)as puramt,ifnull((fldnetcost-fldnetcost),0) as isamt,ifnull(sum(fldqty*fldnetcost),0) as balamt,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $fiscal_year->fldfirst . "') and fldtocomp='" . $request->department . "' and fldtosav=1)";
            // $stockreceived2 = \DB::select($stockrecieved2sql);

            $closingsql = "(select '" . $day_before . "' as e,'Closing' as f,'**' as g,sum(purqty)as h,sum(isqty)as i,sum(balqty)as j,sum(rate)as k,sum(puramt)as l,sum(isamt)as m,sum(balamt)as n,o as o,p as p from(" . $purchase2sql . " " . $openingpurchase2sql . " " . $stockrecieved2sql . ")as total) union all";

            $openingpurchasesql = "(select date_format(fldtime,'%Y-%m-%d') As e, concat( 'Opening Stock: ',fldbillno) as f,fldreference as g,fldtotalqty as h,(fldtotalqty-fldtotalqty) as i,fldtotalqty as j,fldnetcost as k,fldtotalcost as l,(fldtotalcost-fldtotalcost) as m,fldtotalcost as n,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname='OPENING STOCK' and fldcomp='" . $request->department . "' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldsav=0) union";
            // $openingpurchase = \DB::select($openingpurchasesql);

            $purchasesql = "(select date_format(fldtime,'%Y-%m-%d') As e,concat('Pur from ',fldsuppname,':',fldbillno) as f,fldreference as g,fldtotalqty as h,(fldtotalqty-fldtotalqty) as i,fldtotalqty as j,fldnetcost as k,fldtotalcost as l,(fldtotalcost-fldtotalcost) as m,fldtotalcost as n,fldstockno as o,fldstockno as p from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union";
            // $purchase = \DB::select($purchasesql);

            $stockrecievedsql = "(select date_format(fldtoentrytime,'%Y-%m-%d') As e,concat('Recvd from:',fldfromcomp)as f,fldreference as g,fldqty as h,(fldqty-fldqty)as i,fldqty as j,fldnetcost as k,(fldqty*fldnetcost)as l,((fldqty*fldnetcost)-(fldqty*fldnetcost))as m,(fldqty*fldnetcost)as n,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' and fldtosav=1) union";
            // $stockreceived = \DB::select($stockrecievedsql);

            $stocktransferredsql = "(select date_format(fldfromentrytime,'%Y-%m-%d') As e,concat('Transfer to:',fldtocomp)as f,fldreference as g,(fldqty-fldqty) as h,fldqty as i,(0-fldqty) as j,fldnetcost as k,((fldqty*fldnetcost)-(fldqty*fldnetcost))as l,(fldqty*fldnetcost) as m,(0-(fldqty*fldnetcost))as n,fldstockno as o,fldstockno as p from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' and fldfromsav=1) union";
            // $stocktransferred = \DB::select($stocktransferredsql);

            $stockreturnsql = "(select date_format(a.fldtime,'%Y-%m-%d') As e,concat('Return to ',b.fldsuppname,':',a.fldreference) as f,a.fldnewreference as g,(a.fldqty-a.fldqty) as h,a.fldqty as i,(0-a.fldqty) as j,a.fldcost as k,((a.fldqty*a.fldcost)-(a.fldqty*a.fldcost)) as l,(a.fldqty*a.fldcost) as m,(0-(a.fldqty*a.fldcost)) as n,fldstockno as o,fldstockno as p from tblstockreturn a join tblpurchasebill b on a.fldreference=b.fldreference where a.fldstockid='" . $request->search_medecine . "' and (cast(a.fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and a.fldsave=1) union";
            // $stockreturn = \DB::select($stockreturnsql);

            $consumesql = "(select date_format(fldtime,'%Y-%m-%d') As e,concat('Bulk sale to ',fldtarget)as f,fldreference as g,(fldqtydisp-fldqtydisp) as h,fldqtydisp as i,(0-fldqtydisp) as j,fldnetcost as k,((fldqtydisp*fldnetcost)-(fldqtydisp*fldnetcost)) as l,(fldqtydisp*fldnetcost) as m,(0-(fldqtydisp*fldnetcost))as n,fldstockno as o,fldstockno as p from tblbulksale where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1) union";
            // $consume = \DB::select($consumesql);

            $adjustsql = "(select date_format(fldtime,'%Y-%m-%d') As e,'Adjustment' as f,fldreference as g,fldcurrqty as h,fldcompqty as i,(fldcurrqty-fldcompqty)as j,fldnetcost as k,(fldcurrqty*fldnetcost)as l,(fldcompqty*fldnetcost)as m,((fldcurrqty*fldnetcost)-(fldcompqty*fldnetcost))as n,fldstockno as o,fldstockno as p from tbladjustment where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=1) union";
            // $adjust = \DB::select($adjustsql);

            $dispensesql = "(select date_format(fldtime,'%Y-%m-%d') As e,fldbillno as f, flduserid as g,(flditemqty-flditemqty) as h, flditemqty as i,(0-flditemqty) as j,flditemrate as k,(fldditemamt-fldditemamt) as l,fldditemamt as m,(0-fldditemamt) as n,flditemno as o,flditemno as p from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'PHM%') union";
            // $dispense = \DB::select($dispensesql);

            $cancelsql = "(select date_format(fldtime,'%Y-%m-%d') As e,fldbillno as f, flduserid as g,abs(flditemqty) as h, (flditemqty-flditemqty) as i,abs(flditemqty) as j,flditemrate as k,abs(fldditemamt) as l,(fldditemamt-fldditemamt) as m,abs(fldditemamt) as n,flditemno as o,flditemno as p from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'RET%')";
            // $cancel = \DB::select($cancelsql);


            $resultDataSql = "select e,f,g,h,i,j,k,l,m,n,o,p from(" . $closingsql . "(select e, f, g, h, i, j, k, l, m, n,o,p from(" . $openingpurchasesql . $purchasesql . $stockrecievedsql . $stocktransferredsql . $stockreturnsql . $consumesql . $adjustsql . $dispensesql . $cancelsql . ") As Tbl order by e desc)) As Ta order by e";
            // echo $resultDataSql; exit;
            $finalresult = \DB::select($resultDataSql);
            // dd($finalresult);

            /*TOTAL SUM QTY*/

            $openingpurchase1sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0)as balamt from tblpurchase where fldsuppname='OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";

            $purchase1sql = "(select ifnull(sum(fldtotalqty),0) as purqty,ifnull(sum(fldtotalqty-fldtotalqty),0) as isqty,ifnull(sum(fldtotalqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldtotalcost),0) as puramt,ifnull((fldtotalcost-fldtotalcost),0) as isamt,ifnull(sum(fldtotalcost),0) as balamt from tblpurchase where fldsuppname<>'OPENING STOCK' and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0) union all";

            $stockreceived1sql = "(select ifnull(sum(fldqty),0) as purqty,ifnull(sum(fldqty-fldqty),0) as isqty,ifnull(sum(fldqty),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldqty*fldnetcost),0)as puramt,ifnull((fldnetcost-fldnetcost),0) as isamt,ifnull(sum(fldqty*fldnetcost),0) as balamt from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' and fldtosav=1) union all";

            $stocktransferred1sql = "(select  ifnull(sum(fldqty-fldqty),0) as purqty,ifnull(sum(fldqty),0) as isqty,ifnull((0-sum(fldqty)),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull((fldnetcost-fldnetcost),0) as puramt,ifnull(sum(fldqty*fldnetcost),0) as isamt,ifnull((0-sum(fldqty*fldnetcost)),0)as balamt from tbltransfer where fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' and fldfromsav=1) union all";

            $stockreturn1sql = "(select ifnull(sum(a.fldqty-a.fldqty),0) as purqty,ifnull(sum(a.fldqty),0) as isqty,ifnull((0-sum(a.fldqty)),0) as balqty,ifnull(sum(a.fldcost),0) as rate,ifnull((a.fldqty-a.fldqty),0) as puramt,ifnull(sum(a.fldqty*a.fldcost),0) as isamt,ifnull(0-sum(a.fldqty*a.fldcost),0) as balamt from tblstockreturn a where a.fldstockid='" . $request->search_medecine . "' and (cast(a.fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and a.fldsave=1) union all";

            $consume1sql = "(select ifnull(sum(fldqtydisp-fldqtydisp),0) as purqty,ifnull(sum(fldqtydisp),0) as isqty,ifnull((0-sum(fldqtydisp)),0) as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull((fldnetcost-fldnetcost),0) as puramt,ifnull(sum(fldqtydisp*fldnetcost),0) as isamt,ifnull(0-sum(fldqtydisp*fldnetcost),0)as balamt from tblbulksale where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1) union all";

            $adjust1sql = "(select ifnull(sum(fldcurrqty),0) as purqty,ifnull(sum(fldcompqty),0) as isqty,ifnull(sum(fldcurrqty-fldcompqty),0)as balqty,ifnull(sum(fldnetcost),0) as rate,ifnull(sum(fldcurrqty*fldnetcost),0)as puramt,ifnull(sum(fldcompqty*fldnetcost),0)as isamt,ifnull(sum((fldcurrqty-fldcompqty)*fldnetcost),0)as balamt from tbladjustment where fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=1) union all";

            $dispense1sql = "(select ifnull(sum(flditemqty-flditemqty),0) as purqty,ifnull(sum(flditemqty),0) as isqty,ifnull(sum(0-flditemqty),0) as balqty,ifnull(sum(flditemrate),0) as rate,ifnull(sum(fldditemamt-fldditemamt),0) as puramt,ifnull(sum(fldditemamt),0) as isamt,ifnull(sum(0-fldditemamt),0) as balamt from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'PHM%') union all";

            $cancel1sql = "(select ifnull(sum(abs(flditemqty)),0) as purqty,ifnull(sum(flditemqty-flditemqty),0) as isqty,ifnull(abs(sum(flditemqty)),0) as balqty,ifnull(sum(flditemrate),0) as rate,ifnull(sum(abs(fldditemamt)),0) as puramt,ifnull((fldditemamt-fldditemamt),0) as isamt,ifnull(sum(abs(fldditemamt)),0) as balamt from tblpatbilling where flditemname='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsave=1 and fldbillno like 'RET%')";


            $closing1sql = "(select sum(purqty)as purqty,sum(isqty)as isqty,sum(balqty)as balqty,sum(rate)as rate,sum(puramt)as puramt,sum(isamt)as isamt,sum(balamt)as balamt from(" . $purchase2sql . " " . $openingpurchase2sql . " " . $stockrecieved2sql . ") as totl) union all";

            $totalsql = "select sum(purqty)as h,sum(isqty)as i,sum(balqty)as j,sum(rate)as k,sum(puramt)as l,sum(isamt)as m,sum(balamt)as n from (" . $closing1sql . " " . $purchase1sql . " " . $openingpurchase1sql . " " . $stockreceived1sql . " " . $stocktransferred1sql . " " . $stockreturn1sql . " " . $adjust1sql . " " . $consume1sql . " " . $dispense1sql . " " . $cancel1sql . ") as total";

            $data['finalresult'] = $finalresult;
            $data['medicine_name'] = $request->search_medecine;
            $data['total'] = \DB::select($totalsql);
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;

            return view('reports::pdf.item-ledger-report', $data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function exportNewItemLedgerPdf(Request $request)
    {
        try {
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $request->eng_from_date)->where('fldlast', '>=', $request->eng_from_date)->first();

            $day_before = date('Y-m-d', strtotime($request->eng_from_date . ' -1 day'));

            $opening_sql = "with cte as (
select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
           select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockid= '" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_from_date . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
            select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockid= '" . $request->search_medecine . "' and (cast(fldtime as date) >= '" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_from_date . "') and fldcomp= '" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date) union ALL
                select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemname='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockid='" . $request->search_medecine . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)
        ) as T,  (SELECT @a:= 0) AS a
    JOIN (SELECT @running_total:=0) r
    ORDER BY T.datetime  ASC)
, cte2 as (
    select BalanceQty , Rate from cte order by serial_number desc limit 1
   )

, cte3 as (
	select sum(PurQty) as PurQty , sum(QtyIssue) as QtyIssue  from cte
)

select *, cte2.BalanceQty*cte2.Rate as BalAmt from cte2, cte3
";

            $opening_sql = \DB::select($opening_sql);
            $initialBalQty = 0;
            if (isset($opening_sql[0])) {
                $initialBalQty = $opening_sql[0]->BalanceQty;
            }


            $calculation_sql = "select T.fldbatch as batch,T.description as description,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
        select fldbatch,concat('Pur from ',fldsuppname,':',fldbillno) as description,fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty+IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where fldtotalqty is not null and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldcomp='" . $request->department . "' and fldsav=0 Group by fldreference,cast(fldtime as date) union ALL
        select fldbatch,concat('Returned from:',hospital_departments.name)as description, fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldnewreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn  join hospital_departments   on hospital_departments.fldcomp=tblstockreturn.fldcomp where fldqty is not null and fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tblstockreturn.fldcomp='" . $request->department . "' Group by fldreference,cast(fldtime as date)  union ALL
        select tblentry.fldbatch as fldbatch,concat('Transfer to:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(tbltransfer.fldqty) as retQty,-sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldtocomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldfromcomp='" . $request->department . "' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
        select tblentry.fldbatch as fldbatch,concat('Transfer From:',hospital_departments.name) as description, fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(tbltransfer.fldqty)  as purqty,0 as retQty,+sum(tbltransfer.fldqty) as tempQty from tbltransfer  join tblentry   on tblentry.fldstockno=tbltransfer.fldstockno join hospital_departments   on hospital_departments.fldcomp=tbltransfer.fldfromcomp where tbltransfer.fldqty is not null and tbltransfer.fldstockid='" . $request->search_medecine . "' and (cast(fldtoentrytime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and fldtocomp='" . $request->department . "' Group by tbltransfer.fldreference,cast(tbltransfer.fldtoentrytime as date)  union ALL
        select  tblentry.fldbatch as fldbatch,concat('Dispense From:',hospital_departments.name) as description,fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling join tblentry   on tblentry.fldstockno=tblpatbilling.flditemno join hospital_departments   on hospital_departments.fldcomp=tblpatbilling.fldcomp where flditemqty is not null and flditemname='" . $request->search_medecine . "'and (cast(fldtime as date) between '" . $request->eng_from_date . "'  and '" . $request->eng_to_date . "') and tblpatbilling.fldcomp='" . $request->department . "' Group by fldbillno,cast(fldtime as date) union ALL
        select tblentry.fldbatch as fldbatch,concat('Bulk sale to ',fldtarget) as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale  join tblentry   on tblentry.fldstockno=tblbulksale.fldstockno where fldqtydisp is not null and tblbulksale.fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tblbulksale.fldcomp='" . $request->department . "' Group by tblbulksale.fldreference,cast(tblbulksale.fldtime as date) union ALL
        select tblentry.fldbatch as fldbatch,'Adjustment' as description, fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment join tblentry   on tblentry.fldstockno=tbladjustment.fldstockno where fldcurrqty is not null and tbladjustment.fldstockid='" . $request->search_medecine . "' and (cast(fldtime as date) between '" . $request->eng_from_date . "' and '" . $request->eng_to_date . "') and tbladjustment.fldcomp='" . $request->department . "'   Group by tbladjustment.fldreference,cast(tbladjustment.fldtime as date)
    ) as T
JOIN (SELECT @running_total:='" . $initialBalQty . "') r
ORDER BY T.datetime ASC";

            $calculation_sql = \DB::select($calculation_sql);


            $data['opening_sql'] = $opening_sql;
            $data['calculation_sql'] = $calculation_sql;
            $data['medicine_name'] = $request->search_medecine;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;

            return view('reports::pdf.item-ledger-report', $data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function exportItemLedgerExcel(Request $request)
    {
        $export = new ItemLedgerReportExport($request->all());
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'ItemLedgerReport.xlsx');
    }

    public function exportNewItemLedgerExcel(Request $request)
    {
        $export = new ItemLedgerReportExport($request->all());
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'ItemLedgerReport.xlsx');
    }

    public function getLiveMedicineList(Request $request)
    {
        $data = $this->getMedicineData($request);

        return view('reports::live-stock.index', compact('data'));
    }

    public function getMedicineData($request, $search = '', $department = '')
    {

        $dispensing_medicine_stock = Options::get('dispensing_medicine_stock');
        $orderBy = $request->get('orderBy', 'brand');
        $medcategory = 'Medicines';
        $medcategory1 = 'Surgicals';
        $medcategory2 = 'Extra Items';
        $billingmode = (isset($_GET['billingmode']) && $_GET['billingmode']) ? $_GET['billingmode'] : 'General';
        $compname = Helpers::getCompName();
        if ($department != '') {
            $compname = $department;
        }

        $table = "tblmedbrand";
        $fldnarcotic = "tblmedbrand.fldnarcotic";
        $drugJoin = "INNER JOIN tbldrug ON tblmedbrand.flddrug=tbldrug.flddrug";
        $routeCol = "tbldrug.fldroute";
        $fldpackvol = "$table.fldpackvol";
        $medcategory1 = 'Surgicals';
        $table1 = "tblsurgbrand";
        $fldnarcotic1 = "'No' AS fldnarcotic";
        $drugJoin1 = "INNER JOIN tblsurgicals ON $table1.fldsurgid=tblsurgicals.fldsurgid";
        $routeCol1 = "tblsurgicals.fldsurgcateg AS fldroute";
        $fldpackvol1 = "'1' AS fldpackvol";
        $medcategory2 = 'Extra Items';
        $table2 = "tblextrabrand";
        $fldnarcotic2 = "'No' AS fldnarcotic";
        $drugJoin2 = "";
        $routeCol2 = "'extra' AS fldroute";
        $fldpackvol2 = "$table.fldpackvol";

        // $route = $request->get('route');
        $is_expired = $request->get('is_expired');
        $expiry = date('Y-m-d H:i:s');
        if ($is_expired)
            $expiry = $expiry;
        // $expiry = date('Y-m-d H:i:s', strtotime('-20 years', strtotime($expiry)));

        $orderString = "tblentry.fldstockno DESC";


        $whereParams = [
            0,
            $medcategory,
            $compname,
            'Active',
            $expiry,
        ];

        $additionalJoin = "";
        $ratecol = "tblentry.fldsellpr";
        if ($billingmode != 'General') {
            $additionalJoin = "INNER JOIN tblstockrate ON tblentry.fldstockid=tblstockrate.flditemname";
            $ratecol = "tblstockrate.fldrate AS fldsellpr";
        }

        $sql = "";
        $active_status = 'Active';
        if ($search != '') {
            $sql = "select a.fldbrand,a.fldqty,a.fldbatch,a.fldstockid,a.fldexpiry, a.fldstockno
from (
                SELECT tblentry.fldstockno, tblentry.fldstatus, $table.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, sum(tblentry.fldqty), tblentry.fldcategory, tblentry.fldbatch
                FROM $table
                INNER JOIN tblentry ON tblentry.fldstockid=$table.fldbrandid
                $additionalJoin
                $drugJoin
                WHERE
                    tblentry.fldqty> 0 AND
                    tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory' AND
                    tblentry.fldcomp='$compname' AND
                    $table.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry' AND
                       tblentry.fldstockid like '%$search%'

                GROUP BY tblentry.fldbatch
               UNION
                 SELECT tblentry.fldstockno, tblentry.fldstatus, $table1.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, sum(tblentry.fldqty) as fldqty, tblentry.fldcategory, tblentry.fldbatch
                FROM $table1
                INNER JOIN tblentry ON tblentry.fldstockid=$table1.fldbrandid
                $additionalJoin
                $drugJoin1
                WHERE
                    tblentry.fldqty> 0 AND
                       tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory1' AND
                    tblentry.fldcomp='$compname' AND
                    $table1.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry' AND
                       tblentry.fldstockid like '%$search%'

                GROUP BY tblentry.fldbatch union
 SELECT tblentry.fldstockno, tblentry.fldstatus, $table2.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, sum(tblentry.fldqty) as fldqty, tblentry.fldcategory, tblentry.fldbatch
                FROM $table2
                INNER JOIN tblentry ON tblentry.fldstockid=$table2.fldbrandid
                $additionalJoin
                $drugJoin2
                WHERE
                    tblentry.fldqty> 0 AND
                       tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory2' AND
                    tblentry.fldcomp='$compname' AND
                    $table2.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry' AND
                      tblentry.fldstockid like '%$search%'

                GROUP BY tblentry.fldbatch ) a
                ORDER BY a.fldstockno desc
                limit 10
                ";
        } else {
            $sql = "select a.fldbrand,a.fldqty,a.fldbatch,a.fldstockid,a.fldexpiry, a.fldstockno
from (
                SELECT tblentry.fldstockno, tblentry.fldstatus, $table.fldbrand, tblentry.fldstockid, tblentry.fldexpiry,sum(tblentry.fldqty) as fldqty, tblentry.fldcategory, tblentry.fldbatch
                FROM $table
                INNER JOIN tblentry ON tblentry.fldstockid=$table.fldbrandid
                $additionalJoin
                $drugJoin
                WHERE
                    tblentry.fldqty> 0 AND
                       tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory' AND
                    tblentry.fldcomp='$compname' AND
                    $table.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry'

                GROUP BY tblentry.fldbatch
               UNION
                 SELECT tblentry.fldstockno, tblentry.fldstatus, $table1.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, sum(tblentry.fldqty) as fldqty, tblentry.fldcategory, tblentry.fldbatch
                FROM $table1
                INNER JOIN tblentry ON tblentry.fldstockid=$table1.fldbrandid
                $additionalJoin
                $drugJoin1
                WHERE
                    tblentry.fldqty> 0 AND
                       tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory1' AND
                    tblentry.fldcomp='$compname' AND
                    $table1.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry'

                GROUP BY tblentry.fldbatch union
 SELECT tblentry.fldstockno, tblentry.fldstatus, $table2.fldbrand, tblentry.fldstockid, tblentry.fldexpiry, sum(tblentry.fldqty) as fldqty, tblentry.fldcategory, tblentry.fldbatch
                FROM $table2
                INNER JOIN tblentry ON tblentry.fldstockid=$table2.fldbrandid
                $additionalJoin
                $drugJoin2
                WHERE
                    tblentry.fldqty> 0 AND
                       tblentry.fldsav = 1 AND
                    tblentry.fldstatus <> 0 AND
                    tblentry.fldcategory='$medcategory2' AND
                    tblentry.fldcomp='$compname' AND
                    $table2.fldactive='$active_status' AND
                    tblentry.fldexpiry>= '$expiry'

                GROUP BY tblentry.fldbatch ) a
                ORDER BY a.fldstockno desc
                limit 10
                ";
        }


        $data = \DB::select($sql);
        $data = Helpers::appendExpiryStatus($data);


        $department = $compname;
        $prevQtyIssue = 0;
        $prevPurQty = 0;
        $highestQtyIssueItem = '';
        $highestPurQtyItem = '';
        foreach ($data as &$d) {
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', date('Y-m-d'))->where('fldlast', '>=', date('Y-m-d'))->first();

            $day_before = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
            \DB::enableQueryLog();
            $opening_sql = "with cte as (
select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
            select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty + IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty + IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockid= '" . $d->fldstockid . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . date('Y-m-d') . "') and fldcomp= '" . $department ."' and fldstockno= '" . $d->fldstockno . "' Group by fldreference,cast(fldtime as date) union ALL
            select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockid= '" . $d->fldstockid . "' and (cast(fldtime as date) >= '" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . date('Y-m-d') . "') and fldcomp= '" . $department . "' and fldstockno= '" . $d->fldstockno ."' Group by fldreference,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where  fldqty is not null and fldstockid='" . $d->fldstockid . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . date('Y-m-d') . "') and fldfromcomp='" . $department ."' and fldoldstockno= '" . $d->fldstockno . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where fldqty is not null and fldstockid='" . $d->fldstockid . "'and (cast(fldtoentrytime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtoentrytime as date) < '" . date('Y-m-d') . "') and fldtocomp='" . $department ."' and fldstockno= '" . $d->fldstockno . "' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockid='" . $d->fldstockid . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . date('Y-m-d') . "') and fldcomp='" . $department ."' and fldstockno= '" . $d->fldstockno . "' Group by fldreference,cast(fldtime as date) union ALL
                        select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemname='" . $d->fldstockid. "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) < '" . date('Y-m-d') . "') and fldcomp='" . $department ."' and flditemno= '" . $d->fldstockno . "' Group by fldbillno,cast(fldtime as date) union ALL

        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockid='" . $d->fldstockid . "'and (cast(fldtime as date) >='" . $fiscal_year->fldfirst . "' ) and (cast(fldtime as date) <'" . date('Y-m-d') . "') and fldcomp='" . $department ."' and fldstockno= '" . $d->fldstockno . "' Group by fldreference,cast(fldtime as date)
        ) as T,  (SELECT @a:= 0) AS a
    JOIN (SELECT @running_total:=0) r
    ORDER BY T.datetime  ASC)
, cte2 as (
    select BalanceQty , Rate from cte order by serial_number desc limit 1
   )

, cte3 as (
	select sum(PurQty) as PurQty , sum(QtyIssue) as QtyIssue  from cte
)

select *, cte2.BalanceQty*cte2.Rate as BalAmt from cte2, cte3
";

            $calculation_sql = "with cte as (
select @a:=@a+1 serial_number,T.datetime as datetime, T.fldreference as reference,T.purQty as PurQty,T.retQty as QtyIssue, @running_total:= @running_total + T.tempQty AS BalanceQty, T.cost as Rate,T.amount as PurAmt ,( T.retQty*T.cost) as IssueAmt,(@running_total * T.cost)as BalAmt  from (
            select fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*sum(fldtotalqty + IFNULL(fldqtybonus,0))) as amount,fldreference,sum(fldtotalqty+IFNULL(fldqtybonus,0)) as purqty,0 as retQty, +sum(fldtotalqty+IFNULL(fldqtybonus,0)) as tempQty from tblpurchase where  fldstockid= '" . $d->fldstockid . "'and  (cast(fldtime as date) = '" . date('Y-m-d') . "') and fldcomp= '" . $department . "' and fldstockno= '" . $d->fldstockno."' Group by fldreference,cast(fldtime as date) union ALL
            select fldcost as cost,cast(fldtime as date) as datetime,(fldcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tblstockreturn where fldstockid= '" . $d->fldstockid . "' and  (cast(fldtime as date) = '" . date('Y-m-d') . "') and fldcomp= '" . $department . "' and fldstockno= '" . $d->fldstockno."' Group by fldreference,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqty) as retQty,-sum(fldqty) as tempQty from tbltransfer where  fldqty is not null and fldtosav =1 and fldstockid='" . $d->fldstockid . "' and (cast(fldtoentrytime as date) = '" . date('Y-m-d') . "') and fldfromcomp='" . $department ."' and fldoldstockno= '" .$d->fldstockno."' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtoentrytime as date) as datetime,(fldnetcost*0) as amount,fldreference,sum(fldqty) as purqty,0 as retQty,+sum(fldqty) as tempQty from tbltransfer where  fldqty is not null and fldtosav =1 and fldstockid='" . $d->fldstockid . "' and (cast(fldtoentrytime as date) = '" . date('Y-m-d') . "') and fldtocomp='" . $department ."' and fldstockno= '" .$d->fldstockno."' Group by fldreference,cast(fldtoentrytime as date)  union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldqtydisp) as retQty,-sum(fldqtydisp) as tempQty from tblbulksale where fldqtydisp is not null and fldstockid='" . $d->fldstockid . "'and  (cast(fldtime as date) = '" . date('Y-m-d') . "') and fldcomp='" . $department ."' and fldstockno= '" . $d->fldstockno. "' Group by fldreference,cast(fldtime as date) union ALL
       select  fldditemamt as cost,cast(fldtime as date) as datetime,(fldditemamt*0) as amount,fldbillno,0 as purqty,sum(flditemqty) as retQty,-sum(flditemqty) as tempQty from tblpatbilling where flditemqty is not null and flditemname='" . $d->fldstockid. "' and (cast(fldtime as date) ='" . date('Y-m-d') . "') and fldcomp='" . $department ."' and flditemno= '" . $d->fldstockno. "' Group by fldbillno,cast(fldtime as date) union ALL
        select  fldnetcost as cost,cast(fldtime as date) as datetime,(fldnetcost*0) as amount,fldreference,0 as purqty,sum(fldcurrqty) as retQty,-sum(fldcurrqty) as tempQty from tbladjustment where fldcurrqty is not null and fldstockid='" . $d->fldstockid . "'and  (cast(fldtime as date) ='" . date('Y-m-d') . "') and fldcomp='" . $department ."' and fldstockno= '" . $d->fldstockno. "' Group by fldreference,cast(fldtime as date)
        ) as T,  (SELECT @a:= 0) AS a
    JOIN (SELECT @running_total:=0) r
    ORDER BY T.datetime  ASC)
, cte2 as (
    select BalanceQty , Rate from cte order by serial_number desc limit 1
   )

, cte3 as (
	select sum(PurQty) as PurQty , sum(QtyIssue) as QtyIssue  from cte
)

select *, cte2.BalanceQty*cte2.Rate as BalAmt from cte2, cte3
";

            $opening_sql = \DB::select($opening_sql);
            $calculation_sql = \DB::select($calculation_sql);

            $initialBalQty = 0;
            $initialPurQty = 0;


            $initialQtyIssue = 0;
            if (isset($opening_sql[0])) {
                $initialBalQty = $opening_sql[0]->BalanceQty;
            }
            if (isset($calculation_sql[0])) {
                $initialPurQty = $calculation_sql[0]->PurQty;
                $initialQtyIssue = $calculation_sql[0]->QtyIssue;
            }


            $d->initialBalQty = $initialBalQty;
            $d->initialPurQty = $initialPurQty;
            $d->initialQtyIssue = $initialQtyIssue;
            if ($initialQtyIssue >= $prevQtyIssue) {
                if (isset($d->fldbrand)) {
                    $highestQtyIssueItem = $d;
                    $prevQtyIssue = $initialQtyIssue;
                }
            }

            if ($initialPurQty >= $prevPurQty) {
                if (isset($d->fldbrand)) {
                    $highestPurQtyItem = $d;
                    $prevPurQty = $initialPurQty;
                }

            }


        }

        return [
            'result' => $data,
            'hospital_department' => Helpers::getDepartmentAndComp(),
            'highestQtyIssueItem' => $highestQtyIssueItem,
            'highestPurQtyItem' => $highestPurQtyItem,
        ];
    }

    public function getLiveMedicineListChange(Request $request)
    {
        try {
            if (isset($request->search)) {
                $search = $request->search;
            } else {
                $search = '';
            }

            if (isset($request->department)) {
                $department = $request->department;
            } else {
                $department = '';
            }
            $result = $this->getMedicineData($request, $search, $department);
            if (isset($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function getEvent(){
        event(new StockLive(1));

    }
}
