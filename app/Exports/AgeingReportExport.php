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
use App\AccountLedger;
use App\AgeingAccountLedgerMap;
use App\TransactionMaster;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use DB;
use Exception;


class AgeingReportExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function __construct(string $from_date, string $eng_from_date, string $selected_page,string $transactionType)
    {
        $this->from_date = $from_date;

        $this->eng_from_date = $eng_from_date;
        $this->selected_page = $selected_page;
        $this->transactionType = $transactionType;

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
        $data['eng_from_date'] = $eng_from_date =  $startTime = $this->eng_from_date;
        $data['selected_page'] = $selected_page  = $this->selected_page;
        $data['transactionType'] = $transactionType  = $this->transactionType;
        $data['startTime']  = $startTime;

        $interval = 25;//Options::get('ageing_interval');
        $pages =AgeingAccountLedgerMap::where('page',$selected_page)->first();




         $in = $interval;
         $x = $in + $in;
         $y = $x + $in;
         $z = $y+ $in;


                $accountDetail = array();
                $detail = array();
                $accarray = array();

                   // $account = $pages->page;

                    $AccountNos = json_decode($pages->value);
                    if($AccountNos){
                        foreach($AccountNos as $acckey => $account){

                            $datelimitin = date('Y-m-d',strtotime('+'.$in.' day', strtotime($startTime)));

                            $accarray[$account]['0days'] = TransactionMaster::where('transaction_master.TranDate','>=',$startTime)
                                ->where('transaction_master.TranDate','<=',$startTime)
                                ->where(function ($query) use ($pages) {
                                if ($pages->accountType === 'Dr') {
                                    $query->where('transaction_master.TranAmount','>', '0');
                                } else {
                                    $query->where('transaction_master.TranAmount','<', '0');
                                }
                                })
                            ->where('transaction_master.AccountNo',$account)
                            ->sum('TranAmount');

                            $accarray[$account][$in.'_days'] = TransactionMaster::where('transaction_master.TranDate','>=',$startTime)
                            ->where('transaction_master.TranDate','<=',$datelimitin)
                            ->where(function ($query) use ($pages) {
                                if ($pages->accountType === 'Dr') {
                                    $query->where('transaction_master.TranAmount','>', '0');
                                } else {
                                    $query->where('transaction_master.TranAmount','<', '0');
                                }
                            })
                            ->where('transaction_master.AccountNo',$account)
                            ->sum('TranAmount');

                            $datelimitx = date('Y-m-d',strtotime('+'.$x.' day', strtotime($startTime)));

                        $accarray[$account][$x.'_days'] = TransactionMaster::where('transaction_master.TranDate','>=',$datelimitin)
                        ->where(function ($query) use ($pages) {
                            if ($pages->accountType === 'Dr') {
                                $query->where('transaction_master.TranAmount','>', '0');
                            } else {
                                $query->where('transaction_master.TranAmount','<', '0');
                            }
                        })
                        ->where('transaction_master.AccountNo',$account)
                        ->sum('TranAmount');

                        $datelimity = date('Y-m-d',strtotime('+'.$y.' day', strtotime($startTime)));
                        $accarray[$account][$y.'_days'] = TransactionMaster::where('transaction_master.TranDate','>=',$datelimitx)
                        ->where('transaction_master.TranDate','<=',$datelimity)
                        ->where(function ($query) use ($pages) {
                            if ($pages->accountType === 'Dr') {
                                $query->where('transaction_master.TranAmount','>', '0');
                            } else {
                                $query->where('transaction_master.TranAmount','<', '0');
                            }
                        })
                        ->where('transaction_master.AccountNo',$account)
                        ->sum('TranAmount');

                        $datelimitz = date('Y-m-d',strtotime('+'.$z.' day', strtotime($startTime)));
                        $accarray[$account][$z.'_days'] = TransactionMaster::where('transaction_master.TranDate','>=',$datelimity)
                        ->where('transaction_master.TranDate','<=',$datelimitz)
                        ->where(function ($query) use ($pages) {
                            if ($pages->accountType === 'Dr') {
                                $query->where('transaction_master.TranAmount','>', '0');
                            } else {
                                $query->where('transaction_master.TranAmount','<', '0');
                            }
                        })
                        ->where('transaction_master.AccountNo',$account)
                        ->sum('TranAmount');

                        $xyz =$z.'+';
                        $accarray[$account][$xyz.'_days'] = TransactionMaster::where('transaction_master.TranDate','>',$datelimitz)
                        ->where(function ($query) use ($pages) {
                            if ($pages->accountType === 'Dr') {
                                $query->where('transaction_master.TranAmount','>', '0');
                            } else {
                                $query->where('transaction_master.TranAmount','<', '0');
                            }
                        })
                        ->where('transaction_master.AccountNo',$account)
                        ->sum('TranAmount');



                        }
                    }
                //    dd($accarray);
            // foreach($accarray as $keys => $aa){
            //     echo $keys;
            //     foreach($aa as $kk => $a){
            //        echo  $a.' '.$kk.'<br>';
            //     }
            // }
//dd('dd');










        $data['results'] = $accarray;
        $data['acouunt_to'] = $datelimitz;
        //dd($data['results']);

        return view('ageingreport::excel.ageingReport', $data);

    }


}
