<?php

namespace App\Exports;

use App\PatBilling;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ServiceGroupExportMultiSheet implements WithMultipleSheets
{
    protected $req;

    public function __construct(array $request)
    {
        $this->req = $request;
    }

    public function sheets(): array
    {
        $data['eng_from_date'] = $this->req['eng_from_date'];
        $data['eng_to_date'] = $this->req['eng_to_date'];
        $data['from_date'] = $this->req['from_date'];
        $data['to_date'] = $this->req['to_date'];

        $data['serviceData'] = \DB::table('tblpatbilling as pb')->select('sc.fldreport', 'pb.fldid', 'pb.fldencounterval AS PATIENTID', 'pi.fldptnamefir', 'pi.fldptnamelast', 'pb.fldtime as BILLDATETIME', 'pb.fldbillno AS BILLNO', 'pb.flditemname AS SERVICETYPE', 'pb.flduserid AS USERNAME', 'pb.flditemrate AS AMOUNT', 'pb.flditemqty AS QTY', 'pb.flddiscamt AS DISCOUNT', 'pb.fldtaxamt AS VATAMT', 'pb.fldditemamt AS Total_Amount')
            ->join("tblservicecost as sc", 'sc.flditemname', '=', 'pb.flditemname')
            ->join('tblencounter as en', 'en.fldencounterval', '=', 'pb.fldencounterval')
            ->join('tblpatientinfo as pi', 'pi.fldpatientval', '=', 'en.fldpatientval')
            ->where('pb.fldtime', '>=', $this->req['eng_from_date'] . ' 00:00:00')
            ->where('pb.fldtime', '<=', $this->req['eng_to_date'] . ' 23:59:59')
            ->where('pb.fldsave', 1)
            ->groupBy(['pb.fldid'])
            ->orderBy('pb.fldtime', 'DESC')
            ->get();

        $flditemtype = $data['serviceData']->unique('fldreport');

        $sheets = [];
        foreach ($flditemtype as $type) {
            $sheets[$type->fldreport] = new ServiceGroupExport($data['serviceData']->where('fldreport', 'like',$type->fldreport ), $this->req, $type->fldreport);
        }

        return $sheets;
    }
}
