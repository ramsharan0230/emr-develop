<?php

namespace App\Exports;

use App\Encounter;
use App\ExtraBrand;
use App\MedicineBrand;
use App\PatBilling;
use App\SurgBrand;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ABCMovingTypeExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date, string $analysis_type, string $comp, string $billing_mode)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->analysis_type = $analysis_type;
        $this->comp = $comp;
        $this->billing_mode = $billing_mode;
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
        $from_date = Helpers::dateNepToEng($this->from_date);
        $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
        $to_date = Helpers::dateNepToEng($this->to_date);
        $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $analysis_type = $this->analysis_type;
        $comp = $this->comp;
        $billing_mode = $this->billing_mode;
        $html = '';
        $patbilldatas = PatBilling::select(\DB::raw('SUM(flditemqty) as sold_qty'),'flditemrate as unit_price',\DB::raw('SUM(fldditemamt) as tot_amt'),'fldcomp','flduserid','flditemname','flditemtype','flditemno','fldbillingmode')
                                ->whereIn('fldstatus',['Done','Cleared'])
                                ->when($billing_mode != "%", function ($q) use ($billing_mode) {
                                    return $q->where('fldbillingmode', $billing_mode);
                                })
                                ->when($comp != "%", function ($q) use ($comp) {
                                    return $q->where('fldcomp', $comp);
                                })
                                ->whereIn('flditemtype',['Medicines','Surgicals','Extra Items'])
                                ->when(($this->from_date == $this->to_date) && $this->from_date != "" && $this->to_date != "", function ($q) use ($finalfrom) {
                                    return $q->where(DB::raw("(STR_TO_DATE(fldtime,'%Y-%m-%d'))"),$finalfrom);
                                })
                                ->when(($this->from_date != $this->to_date) && $this->from_date != "", function ($q) use ($finalfrom) {
                                    return $q->where('fldtime', '>=', $finalfrom);
                                })
                                ->when(($this->from_date != $this->to_date) && $this->to_date != "", function ($q) use ($finalto) {
                                    return $q->where('fldtime', "<=", $finalto);
                                })
                                ->groupBy(['flditemname','flditemrate'])
                                ->get();
        if($analysis_type == 'quantity'){
            $abc_quan_fast = (Options::get('abc_quan_fast') != false) ? Options::get('abc_quan_fast') : 0;
            $abc_quan_med = (Options::get('abc_quan_med') != false) ? Options::get('abc_quan_med') : 0;
            $abc_quan_slow = (Options::get('abc_quan_slow') != false) ? Options::get('abc_quan_slow') : 0;
            $abc_quan_non = (Options::get('abc_quan_non') != false) ? Options::get('abc_quan_non') : 0;
            foreach($patbilldatas as $key=>$patbilldata){
                $html .= '<tr>';
                if($patbilldata->flditemtype == "Medicines"){
                    $item = MedicineBrand::select('flddrug as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }elseif($patbilldata->flditemtype == "Medicines"){
                    $item = SurgBrand::select('fldsurgid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }else{
                    $item = ExtraBrand::select('fldextraid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }
                $html .= '<td>'.++$key.'</td>';
                if($item != null){
                    $html .= '<td>'.$item->generic.'</td>';
                }else{
                    $html .= '<td></td>';
                }
                if($item != null){
                    $html .= '<td>'.$item->brand.'</td>';
                }else{
                    $html .= '<td></td>';
                }
                $html .= '<td>'.$patbilldata->flditemtype.'</td>';
                $html .= '<td>'.$patbilldata->sold_qty.'</td>';

                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->unit_price).'</td>';
                $moving_type = "";
                if($patbilldata->sold_qty >= $abc_quan_fast){
                    $moving_type .= "Fast";
                }else{
                    if($patbilldata->sold_qty >= $abc_quan_med){
                        $moving_type .= "Medium";
                    }else{
                        if($patbilldata->sold_qty >= $abc_quan_slow){
                            $moving_type .= "Slow";
                        }else{
                            if($patbilldata->sold_qty >= $abc_quan_non){
                                $moving_type .= "Non";
                            }else{
                                $moving_type .= "-";
                            }
                        }
                    }
                }
                $html .= '<td>'.$moving_type.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->tot_amt).'</td>';
                $html .= '</tr>';
            }
        }else{
            $abc_amt_high = (Options::get('abc_amt_high') != false) ? Options::get('abc_amt_high') : 0;
            $abc_amt_med = (Options::get('abc_amt_med') != false) ? Options::get('abc_amt_med') : 0;
            $abc_amt_low = (Options::get('abc_amt_low') != false) ? Options::get('abc_amt_low') : 0;
            foreach($patbilldatas as $key=>$patbilldata){
                $html .= '<tr>';
                if($patbilldata->flditemtype == "Medicines"){
                    $item = MedicineBrand::select('flddrug as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }elseif($patbilldata->flditemtype == "Medicines"){
                    $item = SurgBrand::select('fldsurgid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }else{
                    $item = ExtraBrand::select('fldextraid as generic','fldbrand as brand')->where('fldbrandid',$patbilldata->flditemname)->first();
                }
                $html .= '<td>'.++$key.'</td>';
                $html .= '<td>'.htmlspecialchars($item->generic).'</td>';
                $html .= '<td>'.htmlspecialchars($item->brand).'</td>';
                $html .= '<td>'.$patbilldata->flditemtype.'</td>';
                $html .= '<td>'.$patbilldata->sold_qty.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->unit_price).'</td>';
                $value_type = "";
                if($patbilldata->sold_qty >= $abc_amt_high){
                    $value_type .= "High";
                }else{
                    if($patbilldata->sold_qty >= $abc_amt_med){
                        $value_type .= "Medium";
                    }else{
                        if($patbilldata->sold_qty >= $abc_amt_low){
                            $value_type .= "Low";
                        }else{
                            $value_type .= "-";
                        }
                    }
                }
                $html .= '<td>'.$value_type.'</td>';
                $html .= '<td>'.\App\Utils\Helpers::numberFormat($patbilldata->tot_amt).'</td>';
                $html .= '</tr>';
            }
        }
        $data = [];
        $data['html'] = $html;
        $data['from_date'] = $finalfrom;
        $data['to_date'] = $finalto;
        $data['analysis_type'] = $analysis_type;
        return view('abcanalysis::excel.moving-type-excel', $data);
    }

}
