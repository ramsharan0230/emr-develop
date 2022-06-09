<?php

namespace Modules\Setting\Http\Controllers;

use App\Municipal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class MunicipalityController
 * @package Modules\Setting\Http\Controllers
 */
class MunicipalityController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function municipalitySetting()
    {
        $data['municipalities'] = $this->generateMunicipalityData();
        return view('setting::municipality-setting',$data);
    }

    public function add()
    {
        $data['provinces'] = Municipal::select('fldprovince')->groupBy('fldprovince')->get();
        return view('setting::municipality-add',$data);
    }

    public function store(Request $request)
    {
        $rules = array(
            'province' => 'required',
            'district' => 'required',
            'municipality' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('municipality.add')->withErrors($validator)->withInput();
        }

        Municipal::create(['fldprovince' => $request->province, 'flddistrict' => $request->district, 'fldpality' => $request->municipality]);
        return redirect()->route('municipality');

    }

    public function edit($id)
    {
        $data['provinces'] = Municipal::select('fldprovince')->groupBy('fldprovince')->get();
        $data['municipality_id'] = encrypt($id);
        $data['municipality_data'] = Municipal::select('fldprovince','flddistrict','fldpality')->where('fldid',$id)->first();
        return view('setting::municipality-edit',$data);
    }

    public function update(Request $request,$id)
    {
        $rules = array(
            'province' => 'required',
            'district' => 'required',
            'municipality' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        $municipality_id = decrypt($id);

        if ($validator->fails()) {
            return redirect()->route('municipality.edit',decrypt($municipality_id))->withErrors($validator)->withInput();
        }

        Municipal::where('fldid',$municipality_id)->update(['fldprovince' => $request->province, 'flddistrict' => $request->district, 'fldpality' => $request->municipality]);
        return redirect()->route('municipality');

    }

    public function delete(Request $request)
    {
        try {
            Municipal::where('fldid', $request->id)->delete();
            $html = $this->generateMunicipalityData();
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => [
                    'status' => false,
                    'html' => $html
                ]
            ]);
        }
    }

    public function generateMunicipalityData()
    {
        $municipalities = Municipal::all();
        $html = '';
        if ($municipalities) {
            foreach ($municipalities as $key => $municipality) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$municipality->fldprovince</td>";
                $html .= "<td>$municipality->flddistrict</td>";
                $html .= "<td>$municipality->fldpality</td>";
                $html .= "<td>
                            <a href='javascript:;' onclick='municipality.editMunicipality(".$municipality->fldid.")'><i class='fas fa-pen-square text-primary'></i></a>
                            <a href='javascript:;' onclick='municipality.deleteMunicipality(".$municipality->fldid.")'><i class='fas fa-trash text-danger'></i></a>
                         </td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
