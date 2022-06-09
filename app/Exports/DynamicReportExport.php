<?php

namespace App\Exports;

use App\Dynamicreport;
use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DynamicReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date = null,string $to_date = null, string $reportslug = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->reportslug = $reportslug;
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
        $valuegen = '';
        $keygen = '';
        $from_date = Helpers::dateNepToEng($this->from_date);
        $alldata['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date. " 00:00:00";
        $to_date = Helpers::dateNepToEng($this->to_date);
        $alldata['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date. " 23:59:59";
        $reportslug = $this->reportslug;
        $reportData = Dynamicreport::where('fldreportslug',$reportslug)->first();
        $query = $reportData->fldquery;
        $query = $this->str_replace_first("?", '"'.$finalfrom.'"', $query);
        $query = str_replace ("?", '"'.$finalto.'"', $query);
        $result = collect(DB::select(DB::raw($query)));
        $labels = collect(json_decode($reportData->fldlabels,true))->where('fieldSelected',"1");
        $fieldArray = [];
        $alignArray = [];
        $thead = "";
        if(count($labels)>0){
            $thead .= "<tr><th>SNo.</th>";
        }
        foreach($labels as $key=>$label){
            $colname = str_replace (" AS ", " as ", $label['colname']);
            $explodeAsArr = explode(" as ",$colname);
            if(count($explodeAsArr)>1){
                $fieldname = preg_replace("/\s+/", "", $explodeAsArr[1]);
            }else{
                $explodeDotArr = explode(".",$explodeAsArr[0]);
                if(count($explodeDotArr)>1){
                    $fieldname = preg_replace("/\s+/", "", $explodeDotArr[1]);
                }else{
                    $fieldname = preg_replace("/\s+/", "", $explodeDotArr[0]);
                }
            }
            array_push($fieldArray,$fieldname);
            array_push($alignArray,$label['alignType']);
            $thead .= "<th>".$label['assignedName']."</th>";
        }
        if(count($labels)>0){
            $thead .= "</tr>";
        }
        $tbody = "";
        foreach($result as $key=>$res){
            $tbody .= "<tr>";
            $tbody .= "<td>".++$key."</td>";
            foreach($fieldArray as $fieldKey => $field){
                if(!empty($alignArray[$fieldKey])){
                    $keygen = $alignArray[$fieldKey];
                }

                if(!empty($res->$field)){
                    $valuegen = htmlspecialchars($res->$field);
                }
                $tbody .= "<td style='text-align: ".$keygen.";'>".$valuegen."</td>";
            }
            $tbody .= "</tr>";
        }
        $alldata['reportData'] = $reportData;
        $alldata['thead'] = $thead;
        $alldata['tbody'] = $tbody;
        return view('dynamicreports::dynamic-report-excel', $alldata);
    }

    public function str_replace_first($search, $replace, $subject) {
        return implode($replace, explode($search, $subject, 2));
    }

}
