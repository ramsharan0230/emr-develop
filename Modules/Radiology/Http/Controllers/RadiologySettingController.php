<?php

namespace Modules\Radiology\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;

use App\Utils\Helpers;
use App\Utils\Options;

class RadiologySettingController extends Controller
{
    public function index(Request $request)
    {
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');
        $category_id = $request->get('category_id');
        $date = $request->get('date');
        $status = $request->get('status');
        $has_date = $request->get('has_date');
        $date = $date ? Helpers::dateNepToEng($date)->full_date : date('Y-m-d');
        $encounterId = $request->get('encounterId');
        $name = $request->get('name');

        $patients = \App\PatRadioTest::select('fldencounterval', 'fldtime_report', 'flduserid_report')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname,flduserid',
            ])->where([
                ['fldsave_report', '=', '1'],
                ['flvisible', '=', 'Visible'],
                ["fldtime_report", ">=", "$fromdate 00:00:00"],
                ["fldtime_report", "<=", "$todate 23:59:59.999"],
            ])->where(function ($query) {
                $query->where('fldprint', '0');
                $query->orWhereNull('fldprint');
            });
        if ($category_id)
            $patients->whereIn('fldtestid', \App\Radio::where('fldcategory', 'like', $category_id)->pluck('fldexamid')->toArray());
        if ($status)
            $patients->where('fldstatus', $status);
        else
            $patients->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        if ($name)
            $patients->whereHas('encounter.patientInfo', function($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        if ($encounterId)
            $patients->where('fldencounterval', $encounterId);

        $data['patients'] = $patients->orderBy('fldtime_report', 'ASC')->get();

        if ($request->ajax())
            return response()->json($data['patients']);

        $data['categories'] = Helpers::getPathoCategory('Radio');
        $data['date'] = Helpers::dateEngToNepdash($date)->full_date;
        $data['selects'] = [];

        if ($request->isMethod('post')) {
            $encounter_id = $request->get('encounter_id');
            $sample_id = $request->get('sample_id');
            // $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
            $field = 'fldencounterval';
            $value = ($encounter_id) ?: $sample_id;

            // select fldid,fldchk,fldsave_report,fldtest_type,fldmethod,fldabnormal,fldstatus,flduserid_report,flduserid_verify,fldsampletype,fldcondition,fldtime_report,fldprint,fldorder,fldtestid from tblpatradiotest where fldsave_report='1' and fldencounterval='E5489' and (fldstatus='Reported' or fldstatus='Verified') and flvisible='Visible'
            $samples = \App\PatRadioTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldmethod', 'fldabnormal', 'fldstatus', 'flduserid_report', 'flduserid_verify', 'fldsampletype', 'fldcondition', 'fldtime_report', 'fldprint', 'fldorder', 'fldtestid', 'fldreportquali')
                ->where([
                    [$field, '=', $value],
                    ['fldsave_report', '=', '1'],
                    // ['fldprint', '=', '0'],
                    ['flvisible', '=', 'Visible'],
                ])->where(function ($query) {
                    $query->where('fldprint', '0');
                    $query->orWhereNull('fldprint');
                })->where(function ($query) {
                    $query->where('fldstatus', 'Reported');
                    $query->orWhere('fldstatus', 'Verified');
                });
            if ($category_id)
                $samples->whereIn('fldtestid', \App\Radio::where('fldcategory', 'like', $category_id)->pluck('fldexamid')->toArray());
            $data['samples'] = $samples->with(['radioSubTest'])->get();

            if (!$encounter_id && $data['samples']->isNotEmpty())
                $encounter_id = $data['samples']->toArray()[0]['fldencounterval'];

            $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank')
                ->where('fldencounterval', $encounter_id)
                ->first();
        }
        return view('radiology::tests.printing', $data);
    }

    public function searchPatient(Request $request)
    {
        $data = \App\PatientInfo::select('fldpatientval', 'fldptnamefir', 'fldptnamelast', 'fldptsex', 'fldptaddvill', 'fldptadddist', 'fldptcontact', 'fldptbirday', 'fldptcode', 'fldmidname', 'fldrank');

        if ($request->get('fldptsex'))
            $data->where('fldptsex', 'like', $request->get('fldptsex') . '%');
        if ($request->get('fldptnamefir'))
            $data->where('fldptnamefir', 'like', $request->get('fldptnamefir') . '%');
        if ($request->get('fldptnamelast'))
            $data->where('fldptnamelast', 'like', $request->get('fldptnamelast') . '%');
        if ($request->get('fldptaddvill'))
            $data->where('fldptaddvill', 'like', $request->get('fldptaddvill') . '%');
        if ($request->get('fldptadddist'))
            $data->where('fldptadddist', 'like', $request->get('fldptadddist') . '%');
        if ($request->get('fldptcontact'))
            $data->where('fldptcontact', 'like', $request->get('fldptcontact') . '%');
        if ($request->get('fldptcode'))
            $data->where('fldptcode', 'like', $request->get('fldptcode') . '%');

        return response()->json($data->get());
    }

    public function verifyReport(Request $request)
    {
        try {
            \App\PatRadioTest::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                'fldstatus' => 'Verified',
                'flduserid_verify' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldcomp_verify' => \App\Utils\Helpers::getCompName(),
                'fldsave_verify' => 1,
                'flduptime_verify' => date('Y-m-d H:i:s'),
                'xyz' => 0,
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function printReport(Request $request)
    {
        if ($request->type == 'encounter') {
            $encounter_id = $request->get('encounter_sample');
        } else {
            $sample_id = $request->get('encounter_sample');
        }

        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;

        $samples = \App\PatRadioTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldmethod', 'fldabnormal', 'fldstatus', 'flduserid_report', 'flduserid_verify', 'fldsampletype', 'fldcondition', 'fldtime_report', 'fldprint', 'fldorder', 'fldtestid', 'fldreportquali')->with([
            'reportedBy:firstname,middlename,lastname,username',
            'verifiedBy:firstname,middlename,lastname,username'
        ])->where([
            [$field, '=', $value],
            ['fldsave_report', '=', '1'],
            // ['fldprint', '=', '0'],
            ['flvisible', '=', 'Visible'],
        ])->where(function ($query) {
            $query->where('fldprint', '0');
            $query->orWhereNull('fldprint');
        });
        if (\App\Utils\Options::get('show_verified') == '1')
            $samples->where('fldstatus', 'Verified');
        else
            $samples->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });

        if ($request->get('category_id'))
            $samples->whereIn('fldtestid', \App\Radio::where('fldcategory', 'like', $request->get('category_id'))->pluck('fldtestid')->toArray());
        $data['samples'] = $samples->with(['radioSubTest'])->get();

        if (!$encounter_id && $data['samples']->isNotEmpty())
            $encounter_id = $data['samples']->toArray()[0]['fldencounterval'];

        $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldrank,fldmidname')
            ->where('fldencounterval', $encounter_id)
            ->first();

        if (isset($request->email_report)) {
            if (!file_exists(storage_path('report/pdf')))
                mkdir(storage_path('report/pdf'), 0777, true);

            $pdfName = $data['encounter_data']->patientInfo->fldptnamefir . '-' . $data['encounter_data']->fldencounterval . '.pdf';

            view('radiology::pdf.lab', $data)->setPaper('a4')->save(storage_path('report/pdf/' . $pdfName));
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($data['encounter_data']) && isset($data['encounter_data']->fldrank)) ? $data['encounter_data']->fldrank : '';
            $emailData = [
                'template_id' => 1,
                'email' => $data['encounter_data']->patientInfo->fldemail,
                'full_name' => $user_rank . ' ' . $data['encounter_data']->patientInfo->fldptnamefir. ' ' . $data['encounter_data']->patientInfo->fldmidname . ' ' . $data['encounter_data']->patientInfo->fldptnamelast
            ];

            $email = new AdminEmailTemplateController();
            $email->sendEmail(storage_path('report/pdf/' . $pdfName), $emailData);
            unlink(storage_path('report/pdf/' . $pdfName));
            return view('radiology::pdf.lab', $data)/*->setPaper('a4')->stream('lab.pdf')*/ ;
        } else {
            /*IF EMAIL IS SELECTED THEN DO NOT DOWNLOAD PDF FILE*/
            return view('radiology::pdf.lab', $data)/*->setPaper('a4')->stream('lab.pdf')*/ ;
        }
    }

    public function saveReport(Request $request)
    {
        if ($request->type == 'encounter') {
            $encounter_id = $request->get('encounter_sample');
        } else {
            $sample_id = $request->get('encounter_sample');
        }

        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;

        $samples = \App\PatRadioTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldmethod', 'fldabnormal', 'fldstatus', 'flduserid_report', 'flduserid_verify', 'fldsampletype', 'fldcondition', 'fldtime_report', 'fldprint', 'fldorder', 'fldtestid')
            ->where([
                [$field, '=', $value],
                ['fldsave_report', '=', '1'],
                // ['fldprint', '=', '0'],
                ['flvisible', '=', 'Visible'],
            ])->where(function ($query) {
                $query->where('fldprint', '0');
                $query->orWhereNull('fldprint');
            })->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        if ($request->get('category_id'))
            $samples->whereIn('fldtestid', \App\Radio::where('fldcategory', 'like', $request->get('category_id'))->pluck('fldtestid')->toArray());
        $data['samples'] = $samples->with(['radioSubTest'])->get();

        if (!$encounter_id && $data['samples']->isNotEmpty())
            $encounter_id = $data['samples']->toArray()[0]['fldencounterval'];

        $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldmidname,fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        if (!file_exists(storage_path('report/pdf')))
            mkdir(storage_path('report/pdf'), 0777, true);

        $pdfName = $data['encounter_data']->patientInfo->fldptnamefir . '-' . $data['encounter_data']->fldencounterval . '.pdf';

        view('radiology::pdf.lab', $data)->setPaper('a4')->save(storage_path('report/pdf/' . $pdfName));
        $fileLocation = storage_path('report/pdf/' . $pdfName);
        $fp = fopen($fileLocation, 'rb');
        $content = fread($fp, filesize($fileLocation));
        $content = addslashes($content);
        fclose($fp);
        unlink(storage_path('report/pdf/' . $pdfName));

        try {
            \App\PatReport::insert([
                'fldencounterval' => $encounter_id,
                'fldcateg' => 'Diagnostic Tests',
                'fldtitle' => $request->get('fldtitle'),
                'flddetail' => NULL,
                'fldpic' => mb_convert_encoding($content, 'UTF-8', 'UTF-8'),
                'fldlink' => NULL,
                'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => date('Y-m-d H:i:s'),
                'fldcomp' => \App\Utils\Helpers::getCompName(),
                'fldsave' => '1',
                'flduptime' => NULL,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save information.',
            ]);
        }
    }
}
