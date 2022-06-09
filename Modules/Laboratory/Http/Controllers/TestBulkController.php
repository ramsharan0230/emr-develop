<?php

namespace Modules\Laboratory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class TestBulkController extends Controller
{
    public function verify(Request $request)
    {
        $to = $from = date('Y-m-d');
        $from = $request->get('from') ?: Helpers::dateEngToNepdash($from)->full_date;
        $to = $request->get('to') ?: Helpers::dateEngToNepdash($to)->full_date;

        return view('laboratory::tests.bulkverify', [
            'categories' => Helpers::getPathoCategory('Test'),
            'from' => $from,
            'to' => $to,
            'tests' => $this->_getPatient($request),
        ]);
    }

    public function print(Request $request)
    {
        $to = $from = date('Y-m-d');
        $from = $request->get('from') ?: Helpers::dateEngToNepdash($from)->full_date;
        $to = $request->get('to') ?: Helpers::dateEngToNepdash($to)->full_date;

        $rawpatients = $this->_getPatient($request);
        $patients = [];
        foreach ($rawpatients as $patient) {
            $sampleid = $patient->fldsampleid;
            if (!isset($patients[$sampleid])) {
                $patients[$sampleid] = [
                    'fldsampleid' => $sampleid,
                    'fldencounterval' => $patient->fldencounterval,
                    'fldrankfullname' => ($patient->patientEncounter && $patient->patientEncounter->patientInfo) ? $patient->patientEncounter->patientInfo->fldrankfullname : '',
                    'fldage' => ($patient->patientEncounter && $patient->patientEncounter->patientInfo) ? $patient->patientEncounter->patientInfo->fldagestyle : '',
                    'fldptsex' => ($patient->patientEncounter && $patient->patientEncounter->patientInfo) ? $patient->patientEncounter->patientInfo->fldptsex : '',
                    'fldptcontact' => ($patient->patientEncounter && $patient->patientEncounter->patientInfo) ? $patient->patientEncounter->patientInfo->fldptcontact : '',
                    'fldaddress' => ($patient->patientEncounter && $patient->patientEncounter->patientInfo) ? $patient->patientEncounter->patientInfo->fldptaddvill . ', ' . $patient->patientEncounter->patientInfo->fldptadddist : '',
                ];
            }
            $test = $patient->fldtestid;
            if ($test == 'Culture & Sensitivity')
                $test .= " [{$patient->fldsampletype}]";

            $patients[$sampleid]['tests'][] = [
                'fldid' => $patient->fldid,
                'fldtestid' => $test,
                'fldstatus' => $patient->fldstatus,
            ];
            $patients[$sampleid]['fldsampletype'][] = $patient->fldsampletype;
            $patients[$sampleid]['fldtime_sample'][] = explode(' ', $patient->fldtime_sample)[0];
            $patients[$sampleid]['fldtime_report'][] = explode(' ', $patient->fldtime_report)[0];
            $patients[$sampleid]['flduserid_verify'][] = $patient->flduserid_verify;
        }

        return view('laboratory::tests.bulkprint', [
            'categories' => Helpers::getPathoCategory('Test'),
            'from' => $from,
            'to' => $to,
            'tests' => $patients,
            'rawpatients' => $rawpatients,
        ]);
    }

    public function getPatients(Request $request)
    {
        return response()->json(
            $this->_getPatient($request)
        );
    }

    public function _getPatient(Request $request)
    {
        $module = $request->segment(4);
        $per_page = ($module == 'verify') ? 50 : 100;

        $fromdate = $request->get('from') ? Helpers::dateNepToEng($request->get('from'))->full_date : date('Y-m-d');
        $todate = $request->get('to') ? Helpers::dateNepToEng($request->get('to'))->full_date : date('Y-m-d');
        $encounterId = $request->get('encounterId');
        $category_id = $request->get('category');
        $status = $request->get('status');
        $name = $request->get('name');

        $patients = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldtestid', 'fldabnormal', 'fldstatus', 'flduserid_sample', 'flduserid_report', 'flduserid_verify', 'fldrefername', 'fldcondition', 'fldsampleid', 'fldsampletype', 'fldtime_sample', 'fldtime_report', 'fldprint', 'fldorder', 'fldcomment', 'fldreportquali')
            ->with([
                'patientEncounter:fldencounterval,fldpatientval,fldrank',
                'patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                'patientEncounter.consultant:fldencounterval,fldconsultname',
                'subTest',
                'subTest.subtables',
            ])->where([
                ['fldsave_report', '=', '1'],
                // ['fldprint', '=', '0'],
                ['flvisible', '=', 'Visible'],
                ["fldtime_report", ">=", "$fromdate 00:00:00"],
                ["fldtime_report", "<=", "$todate 23:59:59.999"],
            ]);

        if ($request->get('fldnormal'))
            $patients = $patients->where('fldnormal', 1);

        if ($module == 'verify' && !$status)
            $patients = $patients->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Not Done');
            })->whereNull('flduserid_verify');
        elseif ($status)
            $patients = $patients->where('fldstatus', $status);
        else
            $patients = $patients->where(function ($query) {
                $query->orWhere('fldstatus', 'Verified');
                $query->orWhere('fldstatus', 'Not Done');
            });

        if ($module == 'print') {
            $new = $request->get('new', 'new');
            $printed = $request->get('printed');
            if (!$new && $printed)
                $patients->where('fldprint', '1');
            elseif ($new && !$printed)
                $patients->where('fldprint', '0');
        }

        if ($encounterId)
            $patients = $patients->where('fldencounterval', $encounterId);
        if ($category_id)
            $patients->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category_id)->pluck('fldtestid')->toArray());
        if ($name)
            $patients->whereHas('patientEncounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });

        return $patients->paginate($per_page);
    }

    public function printReport(Request $request)
    {
        $testIds = array_filter(explode(',', $request->get('testIds')));
        $tests = $request->get('test');
        $status = $request->get('status');
        $showall = $request->get('showall');

        if (empty($testIds))
            return redirect()->back()->with('error_message', 'Invalid test selected.');

        $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldtestid', 'fldabnormal', 'fldstatus', 'flduserid_sample', 'flduserid_report', 'flduserid_verify', 'fldrefername', 'fldcondition', 'fldsampleid', 'fldsampletype', 'fldtime_sample', 'fldtime_report', 'fldprint', 'fldorder', 'fldcomment', 'fldreportquali', 'fldtime_verify')
            ->where([
                ['fldsave_report', '=', '1'],
                ['flvisible', '=', 'Visible'],
            ])->whereIn('fldid', $testIds);

        if ($showall != '1') {
            if ($status)
                $samples->where('fldstatus', $status);
            else
                $samples->where(function ($query) {
                    $query->where('fldstatus', 'Reported');
                    $query->orWhere('fldstatus', 'Not Done');
                    $query->orWhere('fldstatus', 'Verified');
                });
        }

        if ($request->get('report_category_id'))
            $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $request->get('report_category_id'))->pluck('fldtestid')->toArray());
        $sample2 = $samples;

        $new = $request->get('new');
        $printed = $request->get('printed');
        if (!$new && $printed)
            $samples->where('fldprint', '1');
        elseif ($new && !$printed)
            $samples->where('fldprint', '0');

        $samples = $samples->with([
            'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
            'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
            'test:fldtestid,fldcategory',
            'subTest.quantity_range',
            'subTest.subtables',
            'patientEncounter:fldencounterval,fldcurrlocat,fldpatientval,fldrank',
            'patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldmidname,fldrank',
            'refrename:username,firstname,middlename,lastname',
        ])->get();

        $markprinted = $request->get('markprinted');
        if ($markprinted)
            $sample2->update([
                'fldprint' => '1',
            ]);

        $rawSamples = [];
        foreach ($samples as $sample) {
            $fldencounterval = $sample->fldencounterval;
            if (!isset($rawSamples[$fldencounterval])) {
                $rawSamples[$fldencounterval]['encounter_data'] = $sample->patientEncounter;
                $rawSamples[$fldencounterval]['samples'] = collect(new \App\PatLabTest);
            }

            unset($sample->patientEncounter);
            if ($sample->test)
                $rawSamples[$fldencounterval]['samples']->push($sample);
        }

        foreach ($rawSamples as &$samples) {
            $samples['fldrefername'] = implode(', ', array_filter($samples['samples']->unique('refrename.fldfullname')->pluck('refrename.fldfullname')->toArray()));
            $samples['sampleid'] = implode(', ', $samples['samples']->unique('fldsampleid')->pluck('fldsampleid')->toArray());
            $samples['reportUsers'] = array_unique($samples['samples']->pluck('flduserid_report')->toArray());
            $samples['verifyUsers'] = array_unique($samples['samples']->pluck('flduserid_verify')->toArray());

            $samples['sampleTime'] = ($samples['samples']->pluck('fldtime_sample')->toArray()) ? max($samples['samples']->pluck('fldtime_sample')->toArray()): '';
            $samples['reportTime'] = ($samples['samples']->pluck('fldtime_report')->toArray()) ? max($samples['samples']->pluck('fldtime_report')->toArray()): '';
            $samples['verifyTime'] = ($samples['samples']->pluck('fldtime_verify')->toArray()) ? max($samples['samples']->pluck('fldtime_verify')->toArray()): '';

            $finalSample = [];
            foreach ($samples['samples'] as $sample) {
                if ($sample->test)
                    $finalSample[$sample->test->fldcategory][$sample->fldsampletype][] = $sample;
            }
            $samples['samples'] = $finalSample;
        }

        return view("laboratory::pdf.bulkPrint", [
            'allData' => $rawSamples
        ]);
    }
}
