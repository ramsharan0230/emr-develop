<?php

namespace Modules\Reports\Http\Controllers;

use App\Exports\ServiceGroupExportMultiSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ServiceCostReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['serviceData'] = [];
        return view('reports::service.index', $data);
    }

    public function search(Request $request)
    {
        $data['eng_from_date'] =$request->eng_from_date;
        $data['eng_to_date'] =$request->eng_to_date;

        /*$data['serviceData'] = PatBilling::select( 'tblpatbilling.fldbillingmode', 'tblpatbilling.fldbillno', 'tblpatbilling.fldtime', 'tblpatbilling.fldcategory', 'tblpatbilling.flddiscamt', 'tblpatbilling.fldditemamt', 'tblpatbilling.flditemqty', 'tblpatbilling.flditemrate', 'tblpatbilling.flditemtype', 'tblpatbilling.flduserid', 't4.fldmidname', 't4.fldptnamefir', 't4.fldptnamelast', 't4.fldpatientval', 't3.fldencounterval', 'tblpatbilling.fldid')
            ->join("tblservicecost", 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')
            ->join('tblencounter as t3', 't3.fldencounterval', '=', 'tblpatbilling.fldencounterval')
            ->join('tblpatientinfo as t4', 't4.fldoldpatientid', '=', 't3.fldpatientval')
            ->where('tblpatbilling.fldtime', '>=', $request->eng_from_date . ' 00:00:00')
            ->where('tblpatbilling.fldtime', '<=', $request->eng_to_date . ' 23:59:59')
            ->where('tblpatbilling.fldsave', 1)
            ->groupBy(['tblpatbilling.fldid'])
            ->orderBy('tblpatbilling.fldtime', 'DESC')
            ->paginate(25);*/
        $data['serviceData'] = \DB::table('tblpatbilling as pb')->select('sc.fldreport', 'pb.fldid', 'pb.fldencounterval AS PATIENTID', 'pi.fldptnamefir', 'pi.fldptnamelast', 'pb.fldtime as BILLDATETIME', 'pb.fldbillno AS BILLNO', 'pb.flditemname AS SERVICETYPE', 'pb.flduserid AS USERNAME', 'pb.flditemrate AS AMOUNT', 'pb.flditemqty AS QTY', 'pb.flddiscamt AS DISCOUNT', 'pb.fldtaxamt AS VATAMT', 'pb.fldditemamt AS Total_Amount')
            ->join("tblservicecost as sc", 'sc.flditemname', '=', 'pb.flditemname')
            ->join('tblencounter as en', 'en.fldencounterval', '=', 'pb.fldencounterval')
            ->join('tblpatientinfo as pi', 'pi.fldpatientval', '=', 'en.fldpatientval')
            ->where('pb.fldtime', '>=', $request->eng_from_date . ' 00:00:00')
            ->where('pb.fldtime', '<=', $request->eng_to_date . ' 23:59:59')
            ->where('pb.fldsave', 1)
            ->groupBy(['pb.fldid'])
            ->orderBy('pb.fldtime', 'DESC')
            ->paginate(25);


        /*$data['serviceData'] = \DB::select(\DB::raw("SELECT sc.fldreport, pb.fldid, pb.fldencounterval AS PATIENTID, UPPER ( concat( pi.fldptnamefir, ' ', pi.fldptnamelast )) AS PATIENTNAME, pb.fldtime BILLDATETIME, pb.fldbillno AS BILLNO, pb.flditemname AS SERVICETYPE, pb.flduserid AS USERNAME, pb.flditemrate AS AMOUNT, pb.flditemqty AS QTY, pb.flddiscamt AS DISCOUNT, pb.fldtaxamt AS VATAMT, pb.fldditemamt AS Total_Amount FROM tblpatbilling pb JOIN tblservicecost sc ON sc.flditemname = pb.flditemname JOIN tblencounter en ON en.fldencounterval = pb.fldencounterval JOIN tblpatientinfo pi ON pi.fldpatientval = en.fldpatientval WHERE pb.fldsave = '1' and `pb`.`fldtime` >= '" . $request->eng_from_date . " 00:00:00' and `pb`.`fldtime` <= '" . $request->eng_to_date . " 23:59:59' group by `pb`.`fldid`"));*/
//            ->paginate(25);

        return view('reports::service.index', $data);
    }

    public function excelExport(Request $request)
    {
        ob_end_clean();
        ob_start();
        return \Maatwebsite\Excel\Facades\Excel::download(new ServiceGroupExportMultiSheet($request->all()), 'Service-Group-Report.xlsx');
    }

}
