<?php

namespace App\Exports;

use App\AccountGroup;
use App\TransactionMaster;
use App\TransactionView;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DayBookExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function __construct(string $from_date, string $to_date, string $voucher_type, string $user, $voucher_number)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->voucher_type = $voucher_type;
        $this->voucher_number = $voucher_number;
        $this->user = $user;
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
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date = $this->to_date;
        $data['voucher_type'] = $voucher_type = $this->voucher_type;
        $data['voucher_number'] = $voucher_number = $this->voucher_number;
        $data['user'] = $user = $this->user;

        try {

            $from_date = Helpers::dateNepToEng($from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
            $to_date = Helpers::dateNepToEng($to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
            $daybooks = TransactionMaster::select('TranId', 'AccountNo', 'GroupId', 'BranchId', 'VoucherNo', 'VoucherCode', 'TranDate', 'Remarks', 'CreatedBy', \DB::raw('sum(TranAmount) as Amount'), 'BranchId')
            ->where('TranDate', '>=', $finalfrom)
            ->where('TranDate', '<=', $finalto)
            ->where('TranAmount', '>', 0)
            ->when($voucher_type != "%", function ($q) use ($voucher_type) {
                return $q->where('VoucherCode', $voucher_type);
            })
            ->when($voucher_number != "%", function ($q) use ($voucher_number) {
                return $q->where('VoucherNo', $voucher_number);
            })
            ->when($user != "%", function ($q) use ($user) {
                return $q->where('CreatedBy', $user);
            })
            ->groupBy('VoucherNo')
            ->limit(20)
            ->get();
        $html = '';
        if (isset($daybooks) and count($daybooks) > 0) {
            foreach ($daybooks as $key => $daybook) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>" . ((isset($daybook->TranDate) ? Helpers::dateToNepali($daybook->TranDate) : '')) . "</td>";
                $html .= "<td>" . $daybook->TranDate . "</td>";
                $html .= "<td class='voucher_details'>" . $daybook->VoucherNo . "</td>";
                $html .= "<td>" . $daybook->VoucherCode . "</td>";
                $html .= "<td>" . ((isset($daybook->TranDate) ? Helpers::dateToNepali($daybook->TranDate) : '')) . "</td>";
                $html .= "<td>" . Helpers::numberFormat($daybook->Amount) . "</td>";
                $html .= "<td>" . $daybook->CreatedBy . "</td>";
                $html .= "<td></td>";
                $html .= "</tr>";
            }
        }

        $data['html'] = $html;
            return view('coreaccount::accountdaybook.accountdaybook-excel', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
//            dd($e);
        }

    }

}
