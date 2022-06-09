<?php

namespace App\Exports;

use App\CogentUsers;
use App\PatBillingShare;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DoctorWiseShareReportWithoutReferalPatientExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function __construct(string $from_date, string $to_date, string $eng_from_date, string $eng_to_date, string $doctor_id, string $bill_no, string $itemname, string $withoutReferral)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->eng_from_date = $eng_from_date;
        $this->eng_to_date = $eng_to_date;
        $this->doctor_id = $doctor_id;
        $this->bill_no = $bill_no;
        $this->itemname = $itemname;
        $this->withoutReferral = $withoutReferral;
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
        $data['doctor_id'] = $doctor_id = $this->doctor_id;
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date = $this->to_date;
        $data['eng_from_date'] = $eng_from_date = $this->eng_from_date;
        $data['eng_to_date'] = $eng_to_date = $this->eng_to_date;
        $data['bill_no'] = $bill_no = $this->bill_no;
        $data['itemname'] = $itemname = $this->itemname;
        $data['flditemname'] = '';
        $data['doc_name'] = $doctor_id = $this->doctor_id;
        $data['withoutReferral'] = $withoutReferral = $this->withoutReferral;

        $data['doc_detail'] = $doc_detail = CogentUsers::select('firstname', 'middlename', 'lastname', 'email', 'username', 'fldcategory')->where('id', $doctor_id)->first();
        $data['results'] = PatBillingShare::where('pat_billing_shares.user_id', $doctor_id)
            ->leftJoin('tblpatbilling', 'tblpatbilling.fldid', '=', 'pat_billing_shares.pat_billing_id')
            ->when(isset($bill_no), function ($q) use ($bill_no) {
                $data['bill_no'] = $bill_no;
                return $q->where('tblpatbilling.fldbillno', 'LIKE', '%' . $bill_no . '%');
            })
            ->when(isset($eng_from_date), function ($q) use ($eng_from_date,$eng_to_date) {
                return $q->whereDate('tblpatbilling.fldordtime', '>=', $eng_from_date . ' 00:00:00')->whereDate('tblpatbilling.fldordtime', '<=', $eng_to_date . " 23:59:59");
            })
            ->when(isset($itemname) && $itemname != null, function ($q) use ($itemname) {
                $data['flditemname'] = $itemname;
                return $q->where('tblpatbilling.flditemname', 'LIKE', '%' . $itemname . '%');
            })
            ->when($withoutReferral == true, function ($q) {
                return $q->where('pat_billing_shares.type', '!=', 'referable');
            })
            ->where('pat_billing_shares.share', '>', 0)
            ->where('pat_billing_shares.status', 1)
            ->get()
            ->groupBy(['type', 'flditemname', 'is_returned']);

        return view('patbillingshare::excell.doctor-wise-share-without-referral-patient', $data);
    }
}
