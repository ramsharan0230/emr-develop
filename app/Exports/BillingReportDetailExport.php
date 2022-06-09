<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BillingReportDetailExport implements FromView,WithDrawings,ShouldAutoSize,ShouldQueue
{
    use Exportable;
    public function __construct(array $filterdata, $additionalRequest = null )
    {
        $this->filterdata = $filterdata;
        $this->additionalRequest = $additionalRequest ;
    }

    public function drawings()
    {
        if(Options::get('brand_image')){
            if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
                $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            }else{
                $drawing = [];
            }
        }else{
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        /**
         * debugging execution time
         */
        // ini_set('max_execution_time', '-1');
       // dd($this->filterdata);
        $start = now();

        $fromdate = $this->filterdata['eng_from_date'];
        $todate = $this->filterdata['eng_to_date'];
        $data = \App\PatBilling::select( "fldcomp","fldtime", "fldtaxper", "fldtaxamt", "flddiscamt", "fldditemamt", "fldbillno", "fldencounterval", "flduserid", "fldbillingmode", "flditemname", "flditemrate", "flditemqty", "fldid", "fldparent", "fldtaxamt", "fldtempbillno")
            ->where([
                ["fldtime", ">=", "$fromdate 00:00:00"],
                ["fldtime", "<=", "$todate 23:59:59.999"],
                ["package_name", $this->filterdata['package']],
                // ['fldcomp' , Session::get('selected_user_hospital_department')->fldcomp  ],
                ['fldcomp' , $this->additionalRequest->hospital_selected_dept  ],
            ])->with([
                'parentDetail:fldid,fldparent,fldbillno',
                'tempBillDetail:fldbilltype,fldbillno',
                'billDetail:fldbilltype,fldbillno',
                'encounter:fldpatientval,fldencounterval',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank,fldadmitfile'
            ])
            // ->take(10000)
            ->get();

        $package = $this->filterdata['package'] ;
        $depositeItm = \App\PatBillDetail
                ::whereRaw("fldbillno like '%dep%'
                    and cast(fldtime as date) BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59.999'
                    and (
                        fldpayitemname like '%admission deposit%'
                        or fldpayitemname like 'op deposit'
                        or fldpayitemname like '%re deposit%'
                        or fldpayitemname like '%blood bank%'
                        or fldpayitemname like '%gate pass%'
                        or fldpayitemname like '%post-up%'
                        or fldpayitemname like '%Pharmacy Deposit%'
                    )
                    and fldcomp LIKE '%".$this->additionalRequest->comp_name."%'")
                    // and fldcomp LIKE '%".Helpers::getCompName()."%'")
                ->when($package != '', function($query) use ($package) {
                    $query->whereHas('patBill', function($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })
                ->with([
                    'patBill',
                    'encounter:fldpatientval,fldencounterval',
                    'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank,fldadmitfile'
                ])
                ->get();
        $depositeRefundItem = \App\PatBillDetail
                ::whereRaw("fldbillno like '%dep%'
                    and cast(fldtime as date) BETWEEN '" . $fromdate . " 00:00:00' and '" . $todate . " 23:59:59'
                    and fldpayitemname like '%deposit refund%'
                    and fldcomp LIKE '%".Helpers::getCompName()."%'")
                ->when($package != '', function($query) use ($package) {
                    $query->whereHas('patBill', function($q) use ($package) {
                        $q->where('package_name', $package);
                    });
                })->get();
        $time = $start->diffInSeconds(now());

        // $queryExecutionTime = \Carbon\CarbonInterval::seconds($time)->cascade()->forHumans();
        // dump($queryExecutionTime);
        $results = [];
        foreach ($data as $value) {
            $fldbilltyoe = ($value->billDetail) ? $value->billDetail->fldbilltype : NULL;
            $fldbilltyoe = ($fldbilltyoe == NULL && $value->tempBillDetail) ? $value->tempBillDetail->fldbilltype : $fldbilltyoe;
            $is_deposit = in_array($value->flditemname, ['Admission Deposit', 'OP Deposit', 'RE Deposit', 'Deposit Return']);
            $fldgross = $value->fldgross;

            $key = '';
            if ($is_deposit)
                $key = ($fldgross > 0) ? 'Deposit' : 'DepositReturn';
            elseif ($fldbilltyoe == 'Cash')
                $key = ($fldgross > 0) ? 'Cash' : 'CashReturn';
            elseif ($fldbilltyoe == 'Credit')
                $key = ($fldgross > 0) ? 'Credit' : 'CreditReturn';

            if ($key)
                $results[$key][] = $value;
        }
              /**
         * Pabilling model has not Admission Deposite and OP deposite
         * Deposite and Deposite return are Taken From Patbilling Model
         */
        $results['Deposit'] = $depositeItm;
        $results['DepositReturn'] = $depositeRefundItem;
        return view("billing::excel.billing-report-detail-excel", [
            'results' => $results
        ]);
    }

}
