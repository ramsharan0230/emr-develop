<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReferrableDoctorListExport implements  FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date,string $eng_from_date, string $eng_to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->eng_from_date = $eng_from_date;
        $this->eng_to_date = $eng_to_date;
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
        $data['from_date'] = $from_date = $this->from_date;
        $data['to_date'] = $to_date= $this->to_date;
        $data['eng_from_date'] = $eng_from_date= $this->eng_from_date;
        $data['eng_to_date'] = $eng_to_date= $this->eng_to_date;
        $html ='';

        $doctors = DB::select(DB::raw("select CONCAT_WS(' ', users.firstname, users.middlename, users.lastname) as name, pat_billing_shares.created_at, tblpatbilling.flditemname as nameitem, pat_billing_shares.type,  pat_billing_shares.share, pat_billing_shares.tax_amt, users.firstname, users.middlename, users.lastname
                                         from pat_billing_shares
                                         JOIN users on pat_billing_shares.user_id = users.id
                                         Join tblpatbilling on pat_billing_shares.pat_billing_id = tblpatbilling.fldid
                                         where pat_billing_shares.type ='referable'
                                         AND pat_billing_shares.created_at >='$eng_from_date'
                                         AND pat_billing_shares.created_at <='$eng_to_date'"));


        if($doctors){
            $count=1;
            foreach ($doctors as $doctor){
                if(!empty($doctor->nameitem))
                {
                    $itemname = $doctor->nameitem;
                }else{
                    $itemname = '';
                }
                $html.='<tr>';
                $html.='<td align="center">'. ($count++) .'</td>';
                $html.='<td align="center">'. (isset($doctor->name) ? $doctor->name :'') .'</td>';
                $html.='<td align="center">'. ((isset($doctor->created_at) ? Helpers::dateEngToNepdash(Carbon::parse($doctor->created_at)->format('Y-m-d'))->full_date :'')) .'</td>';
                $html.='<td></td>';
                $html.='<td align="center">'. (isset($doctor->type) ? $doctor->type :'') .'</td>';
                $html.='<td align="center">'. (isset($doctor->share) ? Helpers::numberFormat($doctor->share) :'') .'</td>';
                $html.='<td align="center">'. (isset($doctor->tax_amt) ? Helpers::numberFormat($doctor->tax_amt) :'') .'</td></tr>';
            }

        }
        $data['html'] = $html;


        return view('patbillingshare::excell.referable-doctor-list-excel',$data);
    }

}
