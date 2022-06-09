<?php

namespace App\Exports;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OFFTimeReportExport implements FromView,WithDrawings,ShouldAutoSize,ShouldQueue
{
    use Exportable;
    public function __construct( $request )
    {
        $this->request = $request;
        // $this->additionalRequest = $additionalRequest ;
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
        $request = $this->request ;
        $finalfrom = $request->eng_from_date;
        $finalto = $request->eng_to_date;
        $department = $request->department;
        $search_type = $request->search_type ;
        $item_type = $request->item_type ;
        $search_text = $request->search_type_text ;

        $result = PatBilling::
        // where('fldcomp', $department)
                when(!is_null($department),function($query) use ($department){
                    $query->where('fldcomp', $department);
                }, function($q){
                    $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                })
                ->when($item_type != '', function($query) use ($item_type) {
                    $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                })
                ->when($search_type != '', function($query) use ($search_type , $search_text) {
                     if ($search_type == 'enc' and $search_text != '') {
                        $query->where('fldencounterval', 'LIKE', $search_text);
                    } else if ($search_type == 'user' and $search_text != '') {
                        $query->where('flduserid', 'LIKE', $search_text);
                    } else if ($search_type == 'invoice' and $search_text != '') {
                        $query->where('fldbillno', 'LIKE', $search_text);
                    } else {
                        //nothing
                    }
                })
                ->select('fldtime',
                    'fldbillno',
                    'fldencounterval',
                    'flditemname',
                    'flditemqty',
                    'flditemrate',
                    DB::raw('(flditemrate * flditemqty) AS subtot'),
                    'fldtaxamt',
                    'flddiscamt',
                    'fldditemamt',
                    'flduserid',
                    'fldcomp',
                    'flditemtype'
                )
                // ->where('fldtime', '>=', $finalfrom . ' 00:00:00')
                // ->where('fldtime', '<=', $finalto . ' 23:59:59') 
                
                // ->where(function($query) use ($finalfrom, $request) {
                //     $query->whereDate('fldtime', '>=', $finalfrom)
                //     ->whereTime('fldtime', '>=', $request->from_time )
                //     ->whereTime('fldtime', '<=', $request->to_time );
                // })
                // ->where(function($query) use ($finalto, $request) {
                //     $query->whereDate('fldtime', '<=', $finalto)
                //     ->whereTime('fldtime', '>=', $request->from_time )
                //     ->whereTime('fldtime', '<=', $request->to_time );
                // })
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    ->whereDate('fldtime', '>=', $finalfrom)
                    ->whereDate('fldtime', '<=', $finalto)
                    ;
                })
                ->where(function($query) use ($finalfrom, $finalto, $request) {
                    $query
                    ->whereTime('fldtime', '>=', $request->from_time )
                    ->orWhereTime('fldtime', '<=', $request->to_time )
                    ;
                })
                ->get();

            $summary  = PatBilling::
            // where('fldcomp', $department)
                        when(!is_null($department),function($query) use ($department){
                            $query->where('fldcomp', $department);
                        }, function($q){
                            $q->where('fldcomp', 'LIKE', '%'.Helpers::getCompName().'%');
                        })
                        ->when($item_type != '', function($query) use ($item_type) {
                            // $query->whereHas('patBill', function($q) use ($item_type) {
                            //     $q->where('flditemtype','LIKE', '%'.$item_type.'%');
                            // });
                            $query->where('flditemtype','LIKE', '%'.$item_type.'%');
                        })
                        ->when($search_type != '', function($query) use ($search_type , $search_text) {
                            if ($search_type == 'enc' and $search_text != '') {
                                $query->where('fldencounterval', 'LIKE', $search_text );
                            } else if ($search_type == 'user' and $search_text != '') {
                                $query->where('flduserid', 'LIKE', $search_text);
                            } else if ($search_type == 'invoice' and $search_text != '') {
                                $query->where('fldbillno', 'LIKE', $search_text);
                            } else {
                                //nothing
                            }
                        })
                        ->select('fldtime',
                            'fldbillno',
                            'fldencounterval',
                            'flditemname',
                            'flditemqty',
                            'flditemrate',
                            DB::raw('(flditemrate * flditemqty) AS subtot'),
                            'fldtaxamt',
                            'flddiscamt',
                            'fldditemamt',
                            'flduserid',
                            'fldcomp',
                            'flditemtype',
                            'flduserid'
                        )
                        // ->where('fldtime', '>=', $finalfrom . ' 00:00:00')
                        // ->where('fldtime', '<=', $finalto . ' 23:59:59') 
                        // ->where(function($query) use ($finalfrom, $request) {
                        //     $query->whereDate('fldtime', '>=', $finalfrom)
                        //     ->whereTime('fldtime', '>=', $request->from_time )
                        //     ->whereTime('fldtime', '<=', $request->to_time );
                        // })
                        // ->where(function($query) use ($finalto, $request) {
                        //     $query->whereDate('fldtime', '<=', $finalto)
                        //     ->whereTime('fldtime', '>=', $request->from_time )
                        //     ->whereTime('fldtime', '<=', $request->to_time );
                        // })
                        ->where(function($query) use ($finalfrom, $finalto, $request) {
                            $query
                            ->whereDate('fldtime', '>=', $finalfrom)
                            ->whereDate('fldtime', '<=', $finalto)
                            // ->whereTime('fldtime', '>=', $request->from_time )
                            // ->whereTime('fldtime', '<=', $request->to_time )
                            ;
                        })
                        ->where(function($query) use ($finalfrom, $finalto, $request) {
                            $query
                            // ->whereDate('fldtime', '<=', $finalto)
                            ->whereTime('fldtime', '>=', $request->from_time )
                            ->orWhereTime('fldtime', '<=', $request->to_time )
                            ;
                        })
                        ->select( DB::raw( 'SUM(flditemrate * flditemqty) as itemamt' ),
                                DB::raw('SUM(fldtaxamt) as taxamt'),
                                DB::raw('SUM(flddiscamt) as dscamt'),
                                DB::raw('SUM(fldditemamt) as recvamt')
                        )
                        ->get();            
            $data['results'] = $result ;
            $data['userid'] = \Auth::guard('admin_frontend')->user()->flduserid;
   
            $data['summary'] = $summary ;
            $data['request'] = $request;
           return view('billing::offtimereport.excel.offtimereport', $data);
    }

}
