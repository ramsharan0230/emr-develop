<?php

namespace App\Imports;

use App\Entry;
use App\ExtraBrand;
use App\MedicineBrand;
use App\Purchase;
use App\SurgBrand;
use App\Utils\Helpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class PurchaseEntryImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $html = '';
        $i = 0;
        $user_id = Helpers::getCurrentUserName();
        $subtotal = 0;
        $totaldisc = 0;
        $totaltax = 0;
        $totalamt = 0;
        $vatableAmt = 0;
        $countvatableAmt = 0;
        $nonvatableAmt = 0;
        $countnonvatableAmt = 0;
        $totalCarryCost = 0;
        $fldtotalcost = 0;
        $fldcasdisc = 0;
        $err_message =0;
        $count_rows =0;

        //check Pending stocks
        $prevPendingPurchaseEntries = Purchase::leftJoin('tblentry', 'tblpurchase.fldstockno', '=', 'tblentry.fldstockno')
            ->where('tblentry.fldsav', 0)
            ->where('tblpurchase.fldisopening', 1)
            ->get();

        //delete pending stocks
        if($prevPendingPurchaseEntries){
            $prevPendingPurchaseEntries = Purchase::leftJoin('tblentry', 'tblpurchase.fldstockno', '=', 'tblentry.fldstockno')
                ->where('tblentry.fldsav', 0)
                ->where('tblpurchase.fldisopening', 1)
                ->delete();
        }

        foreach ($rows as $key => $row) {
            if ($key != 0) {
                $count_rows +=1;
                ++$i;
                if(($row[0]== '' && $row[0] !== 0) || ($row[1]== '' && $row[1] !== 0) || ($row[2]== '' && $row[2] !== 0) || ($row[3]== '' && $row[3] !== 0) || ($row[4]== '' && $row[4] !== 0)
                   || ($row[9]== '' && $row[9] !== 0)
                    || ($row[10]== '' && $row[10] !== 0) ){
                    $err_message =1;
                }
                $is_valid = 1;
                if(strtolower($row[0]) == 'medicines'){
                    $is_in_med = MedicineBrand::where('fldbrandid',$row[1])->first();
                    if(!$is_in_med){
                        $is_valid = 0;
                    }
                }

                if(strtolower($row[0]) == 'surgicals'){
                    $is_in_med = SurgBrand::where('fldbrandid',$row[1])->first();
                    if(!$is_in_med){
                        $is_valid = 0;
                    }
                }

                if(strtolower($row[0]) == 'extra item'){
                    $is_in_med = ExtraBrand::where('fldbrandid',$row[1])->first();
                    if(!$is_in_med){
                        $is_valid = 0;
                    }
                }

                if($is_valid == 0) {
                continue;
                }
                    $fldcategory = $row[0];
                    $fldstockid = $row[1];
                    $fldbatch = $row[2];
                    $fldexpiry = $row[3];
                    $fldqty = (isset($row[4])) ? $row[4] : 0;
                    $fldcasdisc = (isset($row[5]) && $row[5] != '') ? $row[5] : 0;
                    $fldcasbonus = (isset($row[6]) && $row[6] != '') ? $row[6] : 0;
                    $fldqtybonus = (isset($row[7]) &&  $row[7] != '') ? $row[7] : 0;
                    $fldcarcost = (isset($row[8]) &&  $row[8] != '') ? $row[8] : 0;
                    $flsuppcost = (isset($row[9]) &&  $row[9] != '') ? $row[9] : 0;
                    $fldnetcost = (isset($row[10]) &&  $row[10] != '') ? $row[10] : 0;
                    $fldsellprice = (isset($row[11]) &&  $row[11] != '') ? $row[11] : 0;
                    $fldtotalcost = (isset($row[12]) &&  $row[12] != '') ? $row[12] : 0;
                    $fldbarcode = (isset($row[13]) &&  $row[13] != '') ? $row[13] : "";
                    $fldvatamt = (isset($row[14]) &&  $row[14] != '') ? $row[14] : 0;

                    $fldstockno = Helpers::getNextAutoId('StockNo', TRUE);
                    $computer = Helpers::getCompName();
                    $expiryDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($fldexpiry))->format('Y-m-d H:i:s');
                    $fldstockno = Helpers::getNextAutoId('StockNo', TRUE);
                    $fldtotalcost +=$fldvatamt-$fldcasdisc;
                    $computer = Helpers::getCompName();

                    $userid = Helpers::getCurrentUserName();
                    $hospitalDepartSession = Helpers::getUserSelectedHospitalDepartmentSession();
                    $fiscalYear = Helpers::getFiscalYear();
                    $time = date('Y-m-d H:i:s');
                    // $countFiscalEntry = Entry::where([['fldfiscalyear', $fiscalYear->fldname],['fldisopening',1]])->count();
                    // if($countFiscalEntry == 0){
                    //     ++$countFiscalEntry;
                    // }
                    if ($hospitalDepartSession !== null) {
                        $supplyName = $fiscalYear->fldname . " OPENING STOCK - " . $hospitalDepartSession->name;
                    } else {
                        $supplyName = $fiscalYear->fldname . " OPENING STOCK";
                    }

                    $fldstatus = \App\Entry::where([
                        ['fldstockid', $fldstockid],
                        ['fldcomp', $computer],
                        ['fldqty', '>', '0'],
                    ])->max('fldstatus');

                    \App\Entry::insert([
                        'fldstockno' => $fldstockno,
                        'fldstockid' => $fldstockid,
                        'fldcategory' => $fldcategory,
                        'fldbatch' => $fldbatch,
                        'fldexpiry' => $expiryDate,
                        'fldqty' => $fldqty,
                        'fldstatus' => $fldstatus,
                        'fldsellpr' => Helpers::numberFormat($fldsellprice,'insert'),
                        'fldsav' => '0',
                        'fldcomp' => $computer,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        'fldbarcode' => $fldbarcode
                    ]);

                    $data = [];
                    $data = [
                        'fldcategory' => $fldcategory,
                        'fldstockno' => $fldstockno,
                        'fldstockid' => $fldstockid,
                        'fldmrp' => 0,
                        'flsuppcost' => Helpers::numberFormat($flsuppcost,'insert'),
                        'fldcasdisc' => Helpers::numberFormat($fldcasdisc,'insert'),
                        'fldcasbonus' => Helpers::numberFormat($fldcasbonus,'insert'),
                        'fldqtybonus' => $fldqtybonus,
                        'fldcarcost' => Helpers::numberFormat($fldcarcost,'insert'),
                        'fldnetcost' => Helpers::numberFormat($fldnetcost,'insert'),
                        'fldmargin' => Helpers::numberFormat(0,'insert'),
                        'fldsellprice' => Helpers::numberFormat($fldsellprice,'insert'),
                        'fldtotalqty' => $fldqty,
                        'fldreturnqty' => Helpers::numberFormat(0,'insert'),
                        'fldtotalcost' => Helpers::numberFormat($fldtotalcost,'insert'),
                        'fldpurdate' => $time,
                        'flduserid' => $userid,
                        'fldtime' => $time,
                        'fldcomp' => $computer,
                        'fldsav' => '1',
                        'fldchk' => '0',
                        'xyz' => '0',
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                        'fldisopening' => 1,
                        'fldfiscalyear' => Helpers::getFiscalYear()->fldname,
                        // 'fldreference' => $fiscalYear->fldname." OPENING STOCK - ". $countFiscalEntry,
                        'fldreference' => null,
                        'fldbillno' => "000",
                        'fldpurtype' => "Cash Payment",
                        "fldsuppname" => $supplyName,
                        'fldbarcode' => $fldbarcode,
                        'fldbatch' => $fldbatch,
                        'fldvat' => $fldvatamt && $fldvatamt != 0 ? 'Yes' : 'No',
                        'fldvatamt' => Helpers::numberFormat($fldvatamt,'insert')
                    ];

                    $fldid = \App\Purchase::insertGetId($data);
                    $subtotal += ($fldnetcost * $fldqty);

                    if($fldvatamt == 0){
                        $nonvatableAmt += ($fldnetcost * $fldqty);
                        $unitCost = $fldnetcost;
                    }else{
                        $vatableAmt += ($fldnetcost * $fldqty);
                        $unitCost = $fldnetcost + ($fldvatamt/$fldqty);
                    }
                    $totaldisc = $totaldisc + $fldcasdisc;
                    $totaltax = $totaltax + $fldvatamt;
                    $totalamt = $totalamt + $fldtotalcost;
                    $totalCarryCost += $fldcarcost;

                    $html .= '<tr data-fldid="' . $fldid . '">';
                    $html .= '<td>' . $i . '</td>';
                    $html .= '<input type="hidden" value="' . $fldid . '" name="purchaseid[]">';
                    $html .= '<td>' . $fldcategory . '</td>';
                    $html .= '<td>' . $fldbatch . '</td>';
                    $html .= '<td>' . $expiryDate . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($fldnetcost) . '</td>';
                    $html .= '<td>' .\App\Utils\Helpers::numberFormat($unitCost)  . '</td>';
                    if ($fldvatamt != null) {
                        $html .= '<td class="vat-amt-td">' . \App\Utils\Helpers::numberFormat($fldvatamt) . '</td>';
                    } else {
                        $html .= '<td>0.00</td>';
                    }
                    $html .= '<td>' . $fldqty . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($fldcasdisc) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($fldcasbonus) . '</td>';
                    $html .= '<td>' . $fldqtybonus . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($fldcarcost) . '</td>';
                    $html .= '<td>'.\App\Utils\Helpers::numberFormat($flsuppcost).'</td>';
                    $html .= '<td>0.00</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($fldsellprice) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat(($fldnetcost * $fldqty)) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat(($fldnetcost * $fldqty + $fldcarcost - $fldcasdisc)) . '</td>';
                    $html .= '<td><button class="btn btn-danger" onclick="deleteentry(' . $fldid . ',' . $fldnetcost * $fldqty . ',' . $fldcasdisc . ','.$fldvatamt.',' . $fldnetcost * $fldqty . ',' . $fldnetcost * $fldqty . ','.$fldcarcost.','.$fldnetcost.','.$fldqty.','.$fldqtybonus.')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    $html .= '</tr>';

                    $data['html'] = $html;



            }

        }



        $data['count_rows'] = $count_rows;
        $data['err_message'] = $err_message;
        $data['subtotal'] = $subtotal;
        $data['vatableAmt'] = $vatableAmt;
        $data['nonvatableAmt'] = $nonvatableAmt;
        $data['totaltax'] = $totaltax;
        $data['totalamt'] = $subtotal + $totaltax + $totalCarryCost - $totaldisc;
        $data['totaldisc'] = $totaldisc ;
        $data['totalCarryCost'] = $totalCarryCost ;
        $this->data = $data;

    }


}
