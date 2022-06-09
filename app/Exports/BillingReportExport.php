<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\PatBillDetail;
use App\Utils\Helpers;

class BillingReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(array $filterdata)
    {
        $this->filterdata = $filterdata;
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
        $filterdata = $this->filterdata;


        $finalfromeng = $filterdata['eng_from_date'];
        $finaltoeng = $filterdata['eng_to_date'];

                $finalfrom = $filterdata['from_date'] ?? null;
                $finalto = $filterdata['to_date'] ?? null;

            $department = $filterdata['department'] ?? null;
            $package = $filterdata['package'] ?? null;
            $report_type = $filterdata['report_type'] ?? null;
            $item_type = $filterdata['item_type'] ?? null;
            $doctor_id = $filterdata['doctor'] ?? null;
            $doctor = !is_null($doctor_id) ? CogentUsers::findOrFail($doctor_id) : null ;
            $billtype='';

            //DB::enableQueryLog();

            if($report_type == 'CAS'){
                $billtype='Service Billing';
            }

            if($report_type == 'DEP'){
                $billtype='Deposit Billing';
            }

            if($report_type == 'CRE'){
                $billtype='Credit Billing';
            }

            if($report_type == 'PHM'){
                $billtype='Pharmacy Billing';
            }

            if($report_type == 'RET'){
                $billtype='Return Billing';
            }
            if($report_type == 'DISCLR'){
                $billtype='Discharge Clearance Billing';
            }
            if($report_type == 'Refund'){
                $billtype='Refund Billing';
            }


            $result =  PatBillDetail::where('fldcomp', $department)
            ->when($package != '', function($query) use ($package) {
                $query->whereHas('patBill', function($q) use ($package) {
                    $q->where('package_name', $package);
                });


            })
            ->when($item_type != '', function($query) use ($item_type) {
                $query->whereHas('patBill', function($q) use ($item_type) {
                    $q->where('flditemtype','LIKE', '%'.$item_type.'%');
                });
            })
            ->when($report_type != '' && $report_type=='CAS', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldbillno', 'LIKE', '%CAS%')
                        ->orWhere('fldbillno', 'LIKE', '%REG%');
                    $q->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                });

            })
            ->when($report_type != '' && $report_type=='DEP', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldbillno', 'LIKE', '%DEP%')
                            ->where('fldcurdeposit', '>', 0);


                });
            })
            ->when($report_type != '' && $report_type=='CRE', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldbillno', 'LIKE', '%CRE%')
                    ->where('fldcurdeposit', '<', 0);
                });
            })
            ->when($report_type != '' && $report_type=='PHM', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('flditemtype', 'LIKE', '%Surgicals%')
                    ->orWhere('flditemtype', 'LIKE', '%Medicines%')
                    ->orWhere('flditemtype', 'LIKE', '%Extra Items%');
                });
            })
            ->when($report_type != '' && $report_type=='RET', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldbillno', 'LIKE', '%RET%');
                });
            })
            ->when($report_type != '' && $report_type=='DISCLR', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldpayitemname', 'LIKE', '%Discharge Clearence%');
                });
            })
            ->when($report_type != '' && $report_type=='Refund', function($query) use ($report_type) {
                $query->whereHas('patBill', function($q) use ($report_type) {
                    $q->where('fldpayitemname', 'LIKE', '%Pharmacy Deposit Refund%')
                    ->orwhere('fldpayitemname', 'LIKE', '%Deposit Refund%');
                });
            });



    $result->where('fldtime', '>=', $finalfromeng . ' 00:00:00');
    $result->where('fldtime', '<=', $finaltoeng . ' 23:59:59');
    $result->where('fldcomp', 'LIKE','%'.Helpers::getCompName().'%');
    $result->when(!is_null($doctor_id), function($query) use ($doctor_id){
        return $query->whereHas('patbill.pat_billing_shares.user', function($query) use ($doctor_id){
            return $query->where('id', $doctor_id);
        });
    });

                $results = $result->with(['patbill' => function($query){
                    return $query->has('pat_billing_shares');
                } ,'patbill.pat_billing_shares.user']);
                    $results = $result ?  $result->groupby('tblpatbilldetail.fldbillno')->get():'';



        return view('billing::excel.billing-report-excel', compact('results','finalfrom','finalto'));
    }

}
