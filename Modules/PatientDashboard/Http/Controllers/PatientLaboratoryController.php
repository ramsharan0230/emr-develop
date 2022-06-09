<?php

namespace Modules\PatientDashboard\Http\Controllers;

use App\Encounter;
use App\PatLabTest;
use Illuminate\Routing\Controller;
use Milon\Barcode\DNS2D;

class PatientLaboratoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $userPatientVal = \Auth::guard('patient_admin')->user()->fldpatientval;
        $encounterList = Encounter::where('fldpatientval', $userPatientVal)->pluck('fldencounterval');
        $data['patLabData'] = PatLabTest::join('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
            ->whereIn('fldencounterval', $encounterList)
            ->where(function($query) {
                $query->where('fldstatus', 'Sampled')
                    ->orWhere('fldstatus', 'Reported');
            })
            ->with([
                'testLimit', 'test:fldtestid,fldoption', 'test.testoptions:fldtestid,fldanswer'
            ])
            ->orderBy('fldtime_sample', 'DESC')
            ->get();

        return view('patientdashboard::lab.lab', $data);
    }

    /**
     * @param $encounter
     * @param $sampleId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function labReport($encounter, $sampleId)
    {
        $data['encounterId'] = $encounter;
        $data['certificate'] = 'Laboratory';
        $data['encounter_data'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounter)
            ->with('patientInfo')
            ->first();

        $data['barcodeData'] = DNS2D::getBarcodeHTML($data['encounter_data']->fldpatientval, 'QRCODE', 3, 3);

        $data['patLabData'] = PatLabTest::join('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
            ->where([
                'fldencounterval' => $encounter,
                'fldsampleid' => $sampleId
            ])
            ->where(function($query) {
                $query->where('fldstatus', 'Sampled')
                    ->orWhere('fldstatus', 'Reported');
            })
            ->with([
                'testLimit', 'test:fldtestid,fldoption', 'test.testoptions:fldtestid,fldanswer'
            ])
            ->orderBy('fldtime_sample', 'DESC')
            ->get();

            return view('patientdashboard::lab.pdf.sampling-A', $data);

    }

    /**
     * @param $encounter
     * @param $sampleId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listSampleData($encounter, $sampleId)
    {
        $data['encounter'] = $encounter;
        $data['sampleId'] = $sampleId;
        $data['patLabData'] = PatLabTest::join('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
            ->where([
                'fldencounterval' => $encounter,
                'fldsampleid' => $sampleId
            ])
            ->where(function($query) {
                $query->where('fldstatus', 'Sampled')
                    ->orWhere('fldstatus', 'Reported');
            })
            ->with([
                'testLimit', 'test:fldtestid,fldoption', 'test.testoptions:fldtestid,fldanswer'
            ])
            ->orderBy('fldtime_sample', 'DESC')
            ->get();
        return view('patientdashboard::lab.sampling-list', $data);
    }
}
