<?php

namespace Modules\Nutrition\Http\Controllers;

use App\Foodgroup;
use App\FoodType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FoodmixtureController extends Controller
{

    public function index()
    {
        return view('nutrition::foodmixtures');
    }

    public function FoodContentfromType(Request $request)
    {

        $foodtypeid = $request->foodtypeid;

        $foodtype = FoodType::find($foodtypeid);

        $foodcontents = $foodtype->FoodContent;

        $foodcontentoptions = '<option value=""></option>>';

        foreach ($foodcontents as $k => $foodcontent) {

            $foodcontentoptions .= '<option value="' . $foodcontent->fldfoodid . '">' . $foodcontent->fldfoodid . '</option>';
        }

        echo $foodcontentoptions;

    }

    public function FoodGroupSubmit(Request $request)
    {

        $request->validate([
            'fldgroup' => 'required',
            'flditemname' => 'required',
            'flditemamt' => 'required'
        ]);

        try {
            $foodgroupdata = [];
            $foodgroupdata['fldgroup'] = $request->fldgroup;
            $foodgroupdata['flditemname'] = $request->flditemname;
            $foodgroupdata['flditemamt'] = $request->flditemamt;
            $foodgroupdata['fldprep'] = $request->fldprep;


            Foodgroup::insert($foodgroupdata);

            $foodgroups = Foodgroup::where('fldgroup', $request->fldgroup)->orderBy('flditemname', 'ASC')->get();

            $this->foodmixturetable($foodgroups);


        } catch (\Exception $e) {
            $errormessage = $e->getMessage();

            echo $errormessage;
        }

    }

    public function loadfoodmixturetablefrombutton(Request $request)
    {
        $fldgroup = $request->fldgroup;

        $foodgroups = Foodgroup::where('fldgroup', $fldgroup)->orderBy('flditemname', 'ASC')->get();

        $this->foodmixturetable($foodgroups);

    }

    public function foodmixturetable($foodgroups)
    {
        $html = '<table class="table table-hovered table-bordered table-striped">';
        $html .= '<thead>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="th-food-mixture">&nbsp;</th>';
        $html .= '<th class="th-food-mixture">particular</th>';
        $html .= '<th class="th-food-mixture">Amount(gm)</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        if (count($foodgroups) > 0) {
            foreach ($foodgroups as $k => $foodgroup) {
                $html .= '<tr>';
                $html .= '<td class="td-food-mixture">' . ++$k . '</td>';
                $html .= '<td class="td-food-mixture">' . $foodgroup->flditemname . '</td>';
                $html .= '<td class="td-food-mixture">' . $foodgroup->flditemamt . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';


        echo $html;
    }

}
