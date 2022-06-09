<?php

namespace App\Services;

use App\Department;
use App\Departmentbed;
use App\DepartmentRevenue;
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

class MaternalisedService
{
    public static function insertMaternalisedFiscal($encounter,$billno,$paymentmode)
    {

          //materlised view fiscal table insert
          if(strtolower($paymentmode) == 'cash' || strtolower($paymentmode) == 'fonepay'  || strtolower($paymentmode) == 'card'){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatBilling::where('fldencounterval', $encounter)
                    ->where('fldditemamt', '>', 0)
                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldditemamt');


                $totalammtfiscal = PatBilling::where('fldencounterval', $encounter)

                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                $discountfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('flddiscamt');
                $taxableamountfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldtaxper', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                $taxfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }


            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billno,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt,'insert'),
                'Discount' => Helpers::numberFormat($discountfiscal,'insert'),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt,'insert'),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal,'insert'),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt,'insert'),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $paymentmode,
                'VAT_Refund_Amount' => 0,
            ];



        }


            // materlised view end

        try {
            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }
        } catch (\Exception $exception) {

        }
    }

    public static function insertMaternalisedFiscalReturn($encounter,$billno,$paymentmode)
    {

          //materlised view fiscal table insert
          if(strtolower($paymentmode) == 'cash' || strtolower($paymentmode) == 'fonepay'  || strtolower($paymentmode) == 'card'){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatBilling::where('fldencounterval', $encounter)
                   // ->where('fldditemamt', '>', 0)
                    ->where('fldretbill','LIKE',$billno)
                    ->sum('fldditemamt');


                $totalammtfiscal = PatBilling::where('fldencounterval', $encounter)

                 //   ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                $discountfiscal = PatBilling::where('fldencounterval', $encounter)


                  //  ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->sum('flddiscamt');
                $taxableamountfiscal = PatBilling::where('fldencounterval', $encounter)


                   // ->where('fldtaxper', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                $taxfiscal = PatBilling::where('fldencounterval', $encounter)


                  //  ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }


            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billno,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt,'insert'),
                'Discount' => Helpers::numberFormat($discountfiscal,'insert'),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt,'insert'),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal,'insert'),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt,'insert'),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $paymentmode,
                'VAT_Refund_Amount' => Helpers::numberFormat($taxableamt,'insert'),
            ];



        }


            // materlised view end

        try {
            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }
        } catch (\Exception $exception) {

        }
    }


    public static function insertMaternalisedFiscalPharmacy($encounter,$billno,$paymentmode)
    {

          //materlised view fiscal table insert
          if(strtolower($paymentmode) == 'cash' || strtolower($paymentmode) == 'fonepay'  || strtolower($paymentmode) == 'card'){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldditemamt');

                $totalammtfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                $discountfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('flddiscamt');
                $taxableamountfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldtaxper', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                $taxfiscal = PatBilling::where('fldencounterval', $encounter)


                    ->where('fldditemamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }

            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billno,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt,'insert'),
                'Discount' => Helpers::numberFormat($discountfiscal,'insert'),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt,'insert'),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal,'insert'),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt,'insert'),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $paymentmode,
                'VAT_Refund_Amount' => 0,
            ];



        }


            // materlised view end

        try {
            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }
        } catch (\Exception $exception) {

        }
    }

    public static function insertMaternalisedFiscalPharmacyReturn($encounter,$billno,$paymentmode)
    {

          //materlised view fiscal table insert
          if(strtolower($paymentmode) == 'cash' || strtolower($paymentmode) == 'fonepay'  || strtolower($paymentmode) == 'card'){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatBilling::where('fldencounterval', $encounter)


                   // ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->sum('fldditemamt');

                $totalammtfiscal = PatBilling::where('fldencounterval', $encounter)


                   // ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                $discountfiscal = PatBilling::where('fldencounterval', $encounter)


                    //->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->sum('flddiscamt');
                $taxableamountfiscal = PatBilling::where('fldencounterval', $encounter)


                   // ->where('fldtaxper', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                $taxfiscal = PatBilling::where('fldencounterval', $encounter)


                 //   ->where('fldditemamt', '>', 0)

                    ->where('fldretbill','LIKE',$billno)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }

            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billno,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt,'insert'),
                'Discount' => Helpers::numberFormat($discountfiscal,'insert'),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt,'insert'),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal,'insert'),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt,'insert'),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $paymentmode,
                'VAT_Refund_Amount' => Helpers::numberFormat($taxableamt,'insert'),
            ];



        }


            // materlised view end

        try {
            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }
        } catch (\Exception $exception) {

        }
    }


    public static function insertMaternalisedFiscalDeposit($encounter,$billno,$paymentmode)
    {

          //materlised view fiscal table insert
          if(strtolower($paymentmode) == 'cash' || strtolower($paymentmode) == 'fonepay'  || strtolower($paymentmode) == 'card'){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatbillDetail::where('fldencounterval', $encounter)
                    ->where('fldreceivedamt', '>', 0)
                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldreceivedamt');


                $totalammtfiscal = PatbillDetail::where('fldencounterval', $encounter)

                    ->where('fldreceivedamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(fldreceivedamt) as total'))->get();

                $discountfiscal = PatbillDetail::where('fldencounterval', $encounter)


                    ->where('fldreceivedamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('flddiscountamt');
                $taxableamountfiscal = PatbillDetail::where('fldencounterval', $encounter)


                    ->where('fldtaxamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->select(DB::raw('sum(fldreceivedamt) as total'))->get();
                $taxfiscal = PatbillDetail::where('fldencounterval', $encounter)


                    ->where('fldreceivedamt', '>', 0)

                    ->where('fldbillno','LIKE',$billno)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }


            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billno,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt,'insert'),
                'Discount' => Helpers::numberFormat($discountfiscal,'insert'),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt,'insert'),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal,'insert'),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt,'insert'),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $paymentmode,
                'VAT_Refund_Amount' => 0,
            ];



        }


            // materlised view end

        try {
            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }
        } catch (\Exception $exception) {

        }
    }

}
