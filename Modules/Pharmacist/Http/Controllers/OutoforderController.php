<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\Drug;
use App\Entry;
use App\MedicineBrand;
use App\Utils\Helpers;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OutoforderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        return view('pharmacist::index');
    }

    public function outOfOrder()
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'out-of-order', 'out-of-order-view'  ])  ) ?
            abort(403, config('unauthorize-message.pharmacy_master.out-of-order.view')) : true ;
        if (Permission::checkPermissionFrontendAdmin('out-of-order')) {
            return view('pharmacist::outoforder.out-of-order');
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function getMedicinesFromFldroute(Request $request)
    {
        $comp = $request->comp;
        $fldroute = $request->fldroute;

        try {
            $entrys = Entry::select('fldstockid')
                            ->when($comp != "", function ($q) use ($comp){
                                return $q->where('fldcomp','like',$comp);
                            })
                            ->when($fldroute != "", function ($q) use ($fldroute){
                                return $q->where('fldcategory','like',$fldroute);
                            })
                            ->where('fldqty','>',0)
                            ->where('fldsav',1)
                            ->distinct('fldstockid')
                            ->orderBy('fldstockid')
                            ->get();
            // $entrys =  DB::select(DB::raw("select tblentry.fldstockid as fldstockid from tblentry where lower(tblentry.fldstockid) like '%%' and tblentry.fldcomp=:comp and tblentry.fldqty>0 and tblentry.fldstockid in(select tblmedbrand.fldbrandid From tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute=:fldroute)) ORDER BY tblentry.fldstockid ASC"), array('comp' => $comp, 'fldroute' => $fldroute));

            $html = '<option value="">--Select--</option>';
            if (count($entrys) > 0) {
                foreach ($entrys as $entry) {
                    $html .= '<option value="' . $entry->fldstockid . '">' . $entry->fldstockid . '</option>';
                }
            }

            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {


            $response['messagedetail'] = $e->getMessage();
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function loadEntries(Request $request)
    {
        $fldstockid = $request->fldstockid;
        $fldcomp = $request->fldcomp;

        $response = array();
        try {
            if (isset($request->showall)) {
                $entries = Entry::where('fldstockid', $fldstockid)->where('fldcomp', $fldcomp)->where('fldqty', '>', 0)->get();
            } else {
                $entries = Entry::where('fldstockid', $fldstockid)->where('fldcomp', $fldcomp)->where('fldqty', '>', 0)->where('fldstatus', '>', 0)->get();
            }

            $html = '<thead><th>No</th><th>Particulars</th><th>Batch</th><th>Expiry</th><th>QTY</th><th>Sellpr</th><th>Order</th></thead>';
            $html .= '<tbody>';
            if (count($entries) > 0) {
                foreach ($entries as $entry) {
                    $html .= '<tr class="entryrow" data-fldstockno="' . $entry->fldstockno . '"><td>' . $entry->fldstockno . '</td><td>'.$entry->fldstockid.'</td><td>' . $entry->fldbatch . '</td><td>' . Carbon::parse($entry->fldexpiry)->format('m/d/Y') . '</td><td>' . $entry->fldqty . '</td><td>' . $entry->fldsellpr . '</td><td>' . $entry->fldstatus . '</td></tr>';
                }
            }

            $html .= '<tbody>';

            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";

            //            $response = 'something went wrong while deleting category';
        }

        return json_encode($response);
    }

    public function populateEntryForUpdate(Request $request)
    {
        $fldstockno = $request->fldstockno;
        $response = array();
        try {

            $entry = Entry::where('fldstockno', $fldstockno)->first();

            $response['fldbatch'] = '';
            $response['fldexpiry'] = '';
            $response['fldstatus'] = '';
            $response['fldsellpr'] = '';
            $response['fldstockno'] = '';
            if ($entry) {
                $response['fldbatch'] = $entry->fldbatch;
                $response['fldexpiry'] = Carbon::parse($entry->fldexpiry)->format('Y-m-d');
                $response['fldstatus'] = $entry->fldstatus;
                $response['fldsellpr'] = $entry->fldsellpr;
                $response['fldstockno'] = $entry->fldstockno;
            }
            $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";

            //            $response = 'something went wrong while deleting category';
        }

        return json_encode($response);
    }

    public function updateEntry(Request $request)
    {

        $requestdata = $request->all();
        $requestdata['xyz'] = 0;
        unset($requestdata['_token']);
        unset($requestdata['fldstockno']);
        $response = array();
        try {

            Entry::where([['fldstockno', $request->fldstockno]])->update($requestdata, ['timestamps' => false]);

            $response['message'] = 'success';
        } catch (\Exception $e) {

            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";

            //            $response = 'something went wrong while deleting category';
        }

        return json_encode($response);
    }
}
