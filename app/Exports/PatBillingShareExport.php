<?php

namespace App\Exports;

use App\PatBillingShare;
use App\ServiceCost;
use App\StockReturn;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PatBillingShareExport implements  FromView,WithDrawings,ShouldAutoSize
{
    public function __construct( string $from_date,  string $to_date,  string $bill_no,  string $eng_from_date, string $eng_to_date, string $flditemname, string $doc_name, string $doc_id, string $doc_user_name)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->bill_no = $bill_no;
        $this->eng_from_date = $eng_from_date;
        $this->eng_to_date = $eng_to_date;
        $this->flditemname = $flditemname;
        $this->doc_name = $doc_name;
        $this->doc_id = $doc_id;
        $this->doc_user_name = $doc_user_name;
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
        $data['to_date'] = $to_date = $this->to_date;
        $data['bill_no'] = $bill_no = $this->bill_no ;
        $data['eng_from_date']= $eng_from_date = $this->eng_from_date ;
        $data['eng_to_date']= $eng_to_date = $this->eng_to_date;
        $data['flditemname']=$flditemname = $this->flditemname ;
        $data['doc_name'] = $doc_name = $this->doc_name ;
       $data['doc_id'] = $doc_id =$this->doc_id;
       $data['doc_user_name'] = $doc_user_name =$this->doc_user_name;

        $result = DB::table('tblpatbilling AS pb')
            ->where(function ($query) {
                if (isset($bill_no)) {
                    $query->where('pb.fldbillno', 'LIKE', '%' . $bill_no . '%');
                }

                if (isset($eng_from_date)) {
                    $query->whereDate('pb.fldordtime', '>=', $eng_from_date . ' 00:00:00');
                } else {
                    $query->whereDate('pb.fldordtime', '>=', date('Y-m-d') . ' 00:00:00');
                }

                if (isset($eng_to_date)) {
                    $query->whereDate('pb.fldordtime', '<=', $eng_to_date . " 23:59:59");
                } else {
                    $query->whereDate('pb.fldordtime', '<=', date('Y-m-d') . ' 23:59:59');
                }

                if (isset($itemname) && $itemname != null) {
                    $query->where('pb.flditemname', 'LIKE', '%' . $itemname . '%');
                }

            })
            
            ->join('pat_billing_shares AS pbs', 'pb.fldid', '=', 'pbs.pat_billing_id')
           
            ->join('users as usr', function ($join) use ($doc_id,$doc_name,$doc_user_name) {

                // if ($request->doctor_username != null) {
                $join->on('pbs.user_id', '=', 'usr.id')
                    // $join->on('usr.username', '=', 'pb.fldrefer')
                    ->when($doc_id != "" && $doc_id != null, function ($q) use ($doc_id) {
                        return $q->where('usr.id', $doc_id);
                    })
                    ->when($doc_user_name != "" && $doc_user_name != null, function ($q) use ($doc_user_name) {
                        return $q->where('usr.username', $doc_user_name);
                    })
                    ->when($doc_name != "" && $doc_name != null, function ($q) use ($doc_name) {
                        return $q->where(DB::raw("CONCAT_WS(' ', usr.firstname, usr.middlename, usr.lastname)"), 'LIKE', '%' . $doc_name . '%');
                    });
               
            })
            ->where('pbs.share', '>', 0)
            ->where('pbs.status', 1)
            ->where('pbs.is_returned',0)
            ->select(DB::raw("usr.firstname, usr.middlename,
            usr.lastname, usr.id as user_id, pb.fldid, 
            pb.fldencounterval, pb.fldbillno , pb.fldbillingmode, 
            pb.flditemtype, pb.flditemname, pb.fldditemamt, 
            pb.fldorduserid, pb.fldordtime, pb.fldstatus, pbs.id
                       AS pat_billing_share_id, pbs.type, pbs.user_id, 
                       pbs.share, pbs.ot_group_sub_category_id, 
                       pbs.is_returned,
                       pbs.total_amount as item_amount,
                       pbs.usersharepercent,
                       pbs.hospitalshare,
                       pbs.share,
                       pbs.tax_amt,
                       pbs.shareqty
                      
                     
                   "))
         
            ->whereRaw("pb.fldsave = 1")
            ->groupBy('pb.fldid')
            ->limit(100)->get();


        // $data['total'] = collect($result)->sum('amount_after_share_tax');
        $data['itemnames'] = ServiceCost::select('flditemname')->get();
        $data['billing_share_reports'] = $result;
        return view('patbillingshare::excell.patbill-share-excel',$data);
    }
}
