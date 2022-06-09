<?php

namespace App\Exports;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EntryWaitingExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $type,string $comp, string $user)
    {
        $this->type = $type;
        $this->comp = $comp;
        $this->user = $user;
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
        $data['type'] = $type = ($this->type == 'notSaved') ? 0 : 1;
        $data['comp'] = $comp = $this->comp;
        $data['user'] = $user = $this->user;
        $result = PatBilling::select('fldid','fldencounterval','flditemtype','flditemname','flditemrate','flditemqty','fldorduserid','fldordcomp','fldordtime','flduserid','fldcomp','fldtime', 'fldbillno')
                            ->when($this->type == "notSaved", function ($q) use ($user) {
                                return $q->where('fldprint', 0);
                            })
                            ->when($this->type == "notBilled", function ($q) use ($user) {
                                return $q->where('fldbillno', NULL);
                            })
                            ->where('fldsave',$type)
                            ->when($user != "" && $this->type == "notSaved", function ($q) use ($user){
                                return $q->whereRaw('LOWER(`fldorduserid`) LIKE ? ',[trim(strtolower($user)).'%']);
                            })
                            ->when($user != "" && $this->type == "notBilled", function ($q) use ($user){
                                return $q->whereRaw('LOWER(`flduserid`) LIKE ? ',[trim(strtolower($user)).'%']);
                            })
                            ->when($comp != "%", function ($q) use ($comp) {
                                return $q->where('fldcomp', 'like', $comp);
                            })
                            ->get();
        $data['result'] = $result;
        $html = "";
        foreach($result as $key=>$r){
            $user = ($this->type == 'notSaved') ? $r->fldorduserid : $r->flduserid;
            $user_comp = ($this->type == 'notSaved') ? $r->fldordcomp : $r->fldcomp;
            $date = ($this->type == 'notSaved') ? $r->fldordtime : $r->fldtime;
            $html .= '<tr>
                        <td>'.++$key.'</td>
                        <td>'.$r->fldencounterval.'</td>
                        <td>'.$r->flditemtype.'</td>
                        <td>'.htmlspecialchars($r->flditemname).'</td>
                        <td>'.\App\Utils\Helpers::numberFormat($r->flditemrate).'</td>
                        <td>'.$r->flditemqty.'</td>
                        <td>'.$user.'</td>
                        <td>'.$user_comp.'</td>
                        <td>'.$date.'</td>
                    </tr>';
        }
        $data['html'] = $html;
        return view('reports::entrywaiting.entry-waiting-excel',$data);
    }

}
