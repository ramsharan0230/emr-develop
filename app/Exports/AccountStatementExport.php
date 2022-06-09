<?php

namespace App\Exports;

use App\TransactionView;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AccountStatementExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function __construct(string $finalfrom, string $finalto, $voucher_code, $voucher_no, $accountnumber)
    {
        $this->finalfrom = $finalfrom;
        $this->finalto = $finalto;
        $this->voucher_code = $voucher_code;
        $this->voucher_no = $voucher_no;
        $this->accountnumber = $accountnumber;
    }

    public function drawings()
    {
        if (Options::get('brand_image')) {
            if (file_exists(public_path('uploads/config/' . Options::get('brand_image')))) {
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan']) ? Options::get('siteconfig')['system_slogan'] : '');
                $drawing->setPath(public_path('uploads/config/' . Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        try{
            $voucherCode = $this->voucher_code;
            $voucherNo = $this->voucher_no;
            $accountnumber = $this->accountnumber;
            $data['from_date'] = $finalfrom = $this->finalfrom;
            $data['to_date'] = $finalto = $this->finalto;
            $statements = TransactionView::where('TranDate', '>=', $finalfrom)
                    ->where('TranDate', '<=', $finalto)
                    ->where(function ($query) use ($voucherCode) {
                        if ($voucherCode != "")
                            $query->where('VoucherCode', 'LIKE', $voucherCode);
                    })
                    ->where(function ($query) use ($voucherNo) {
                        if ($voucherNo != "")
                            $query->where('VoucherNo', strtoupper($voucherNo));
                    })
                    ->where(function ($query) use ($accountnumber) {
                        if ($accountnumber!= "")
                            $query->where('AccountNo', $accountnumber);
                    })
                    // ->where('AccountNo',$request->account_no)
                    ->orderBy('TranDate', 'asc')
                    ->get();
                    // echo $statements; exit;
            $opening = TransactionView::select(\DB::raw('sum(TranAmount) as Amount'))
            ->where('TranDate', '<', $finalfrom)

            ->where('AccountNo',$accountnumber)
           ->first();
            $balance = 0;
            $html = "";
            $html .= "<tr>
            <td>1</td>

            <td></td>
            <td>Opening Balance</td>
            <td></td>
            <td></td>";
            if (isset($opening)) {
                $balance = $opening->Amount;
                if ($opening->Amount > 0) {
                    $opening_balance = $opening->Amount;

                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                } else {
                    $opening_balance = ($opening->Amount) * (-1);
                    $html .= "<td></td>";
                    $html .= "<td>" . Helpers::numberFormat($opening_balance) . "</td>";
                    $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                }
            } else {
                $html .= "<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>";
            }
            $html .= "</tr>";
            $i = 1;
            foreach ($statements as $key => $statement) {
                    // dd($statement);
                    $html .= "<tr>";
                    $html .= "<td>" . ++$i . "</td>";
                    $html .= "<td>" . ((isset($statement->TranDate) ? Helpers::dateToNepali($statement->TranDate) : '')) . "</td>";

                    $html .= "<td>" . str_replace('&','AND',$statement->Narration) . "</td>";
                    $html .= "<td>" . $statement->VoucherCode . "</td>";
                    $html .= "<td>" . $statement->VoucherCode . "-" . $statement->VoucherNo . "</td>";
                    if ($statement->TranAmount > 0) {
                        $balance += $statement->TranAmount;
                        $html .= "<td>" . Helpers::numberFormat($statement->TranAmount) . "</td>";
                        $html .= "<td>0</td>";
                        $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";

                    } else {
                        $balance += $statement->TranAmount;
                        $html .= "<td>0</td>";
                        $html .= "<td>" . Helpers::numberFormat($statement->TranAmount) . "</td>";
                        $html .= "<td>" . Helpers::numberFormat($balance) . "</td>";
                    }
                    $html .= "<td>".((isset($statement->ChequeNo) and $statement->ChequeNo !='') ?$statement->ChequeNo:'') ."</td>";
                    $html .= "<td>".((isset($statement->Remarks)) ? str_replace('&','AND',$statement->Remarks)  : '')."</td>";
                    $html .= "</tr>";


            }

            $data['html'] = $html;
            return view('coreaccount::accountstatement.export', $data);
        }catch(\Exception $e){
            dd($e);
        }

    }

}
