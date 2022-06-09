<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = [
            'date' => date('Y-m-d'),
            'delivery_date' => (\Carbon\Carbon::now())->addMonth(1)->format('Y-m-d'),
            'suppliers' => \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get(),
            'routes' => array_keys(array_slice(Helpers::getDispenserRoute(), 0, 12)),
            'orders' => \App\Order::where([
                    'fldsav' => '0',
                    'fldcomp' => Helpers::getCompName(),
                ])->get(),
            'locations' => \App\Orderlocation::select('flditem')->distinct()->get()
        ];

        return view('store::purchaseorder', $data);
    }

    public function getRefrence(Request $request)
    {
        // select distinct(fldreference) from tblorder where fldsuppname='OPENING STOCK' and fldstatus='Requested' and fldcomp='comp01'
        return \App\Order::select('fldreference')
            ->where([
                'fldsuppname' => $request->get('fldsuppname'),
                'fldstatus' => 'Requested',
                'fldcomp' => Helpers::getCompName(),
            ])->distinct()
            ->get();
    }

    public function getLocation(Request $request)
    {
        // select distinct(fldlocat) from tblorder where fldreference='ORD77/78-1LDH4'
        return \App\Order::select('fldlocat')
            ->where([
                'fldreference' => $request->get('fldreference'),
            ])->distinct()
            ->get();
    }

    public function getMedicineList(Request $request)
    {
        // dd($request->all());
        $fldroute = $request->route;
        $genericbrand = $request->orderBy;
        $response = array();

        try {
            $html = '';

            if ($fldroute == 'oral' || $fldroute == 'liquid' || $fldroute == 'fluid' || $fldroute == 'injection' || $fldroute == 'resp' || $fldroute == 'topical' || $fldroute == 'eye/ear' || $fldroute == 'anal/vaginal') {
                if ($genericbrand == 'generic') {
                    $medbrandgenerics =  DB::select(DB::raw("select fldbrandid from tblmedbrand where lower(tblmedbrand.fldbrandid) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute=:fldroute) ORDER BY tblmedbrand.fldbrandid ASC"), array('fldroute' => $fldroute));
                    // $medbrandgenerics =  DB::select(DB::raw("select * from tblmedbrand where lower(tblmedbrand.fldbrandid) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.fldmaxqty<>-1 and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute=:fldroute) ORDER BY tblmedbrand.fldbrandid ASC"), array('fldroute' => $fldroute));
                    if (count($medbrandgenerics) > 0) {
                        foreach ($medbrandgenerics as $medbrandcgeneric) {
                            $dataAttributes =  "data-fldstockid='" . $medbrandcgeneric->fldbrandid . "'";
                            $html .= '<tr ' . $dataAttributes . '>';
                            $html .= '<td>' . $medbrandcgeneric->fldbrandid . '</td>';
                            $html .= '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                            $html .= '</tr>';
                            // $html .= '<option value="' . $medbrandcgeneric->fldbrandid . '">' . $medbrandcgeneric->fldbrandid . '</option>';
                        }
                    }
                } elseif ($genericbrand == 'brand') {
                    // and tblmedbrand.fldmaxqty<>-1
                    $medbrandbrands = DB::select(DB::raw("select fldbrandid from tblmedbrand where lower(tblmedbrand.fldbrand) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute=:fldroute) ORDER BY tblmedbrand.fldbrand ASC"), array('fldroute' => $fldroute));
                    if (count($medbrandbrands) > 0) {
                        foreach ($medbrandbrands as $medbrandbrand) {
                            $dataAttributes =  "data-fldstockid='" . $medbrandbrand->fldbrandid . "'";
                            $html .= '<tr ' . $dataAttributes . '>';
                            $html .= '<td>' . $medbrandbrand->fldbrandid . '</td>';
                            $html .= '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                            $html .= '</tr>';
                            // $html .= '<option value="' . $medbrandbrand->fldbrandid . '">' . $medbrandbrand->fldbrand . '</option>';
                        }
                    }
                }
            } else if ($fldroute == 'suture' || $fldroute == 'msurg' || $fldroute == 'ortho') {
                // and tblsurgbrand.fldmaxqty<>-1
                $surgbrands = DB::select(DB::raw("select fldbrandid from tblsurgbrand where lower(tblsurgbrand.fldbrandid) like '%' and tblsurgbrand.fldactive='Active' and tblsurgbrand.fldsurgid in(select tblsurgicals.fldsurgid from tblsurgicals where tblsurgicals.fldsurgcateg=:fldroute) ORDER BY tblsurgbrand.fldbrandid ASC"), array('fldroute' => $fldroute));
                if (count($surgbrands) > 0) {
                    foreach ($surgbrands as $surgbrand) {
                        $dataAttributes =  "data-fldstockid='" . $surgbrand->fldbrandid . "'";
                        $html .= '<tr ' . $dataAttributes . '>';
                        $html .= '<td>' . $surgbrand->fldbrandid . '</td>';
                        $html .= '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                        $html .= '</tr>';
                        // $html .= '<option value="' . $surgbrand->fldbrandid . '">' . $surgbrand->fldbrandid . '</option>';
                    }
                }
            } else if ($fldroute == 'extra') {
                // and tblextrabrand.fldmaxqty<>-1
                $extrabrands = DB::select(DB::raw("select fldbrandid from tblextrabrand where lower(tblextrabrand.fldbrandid) like '%' and tblextrabrand.fldactive='Active' ORDER BY tblextrabrand.fldbrandid ASC"));
                if (count($extrabrands) > 0) {
                    foreach ($extrabrands as $extrabrand) {
                        $dataAttributes =  "data-fldstockid='" . $extrabrand->fldbrandid . "'";
                        $html .= '<tr ' . $dataAttributes . '>';
                        $html .= '<td>' . $extrabrand->fldbrandid . '</td>';
                        $html .= '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                        $html .= '</tr>';
                        // $html .= '<option value="' . $extrabrand->fldbrandid . '">' . $extrabrand->fldbrandid . '</option>';
                    }
                }
            }else{
                $medbrandgenerics =  DB::select(DB::raw("select fldbrandid from tblmedbrand where lower(tblmedbrand.fldbrandid) like '%' and tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug) ORDER BY tblmedbrand.fldbrandid ASC"));
                $surgbrands = DB::select(DB::raw("select fldbrandid from tblsurgbrand where lower(tblsurgbrand.fldbrandid) like '%' and tblsurgbrand.fldactive='Active' and tblsurgbrand.fldsurgid in(select tblsurgicals.fldsurgid from tblsurgicals) ORDER BY tblsurgbrand.fldbrandid ASC"));
                $extrabrands = DB::select(DB::raw("select fldbrandid from tblextrabrand where lower(tblextrabrand.fldbrandid) like '%' and tblextrabrand.fldactive='Active' ORDER BY tblextrabrand.fldbrandid ASC"));
                $medicineslist = array_merge($medbrandgenerics,$surgbrands,$extrabrands);

                if (count($medicineslist) > 0) {
                    foreach ($medicineslist as $medicines) {
                        if($medicines->fldbrandid !=''){
                            $dataAttributes =  "data-fldstockid='" . $medicines->fldbrandid . "'";
                            $html .= '<tr ' . $dataAttributes . '>';
                            $html .= '<td>' . $medicines->fldbrandid . '</td>';
                            $html .= '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                            $html .= '</tr>';
                            // $html .= '<option value="' . $extrabrand->fldbrandid . '">' . $extrabrand->fldbrandid . '</option>';
                        }

                    }
                }
            }
            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {
            dd($e);
            $response['errormessage'] = $e->getMessage();
            $response['message'] = "error";
        }

        return json_encode($response);

        // $orderBy = $request->get('orderBy');
        // $route = $request->get('route');

        // // generic: select tblmedbrand.fldbrandid as col from tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.fldmaxqty=0 and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute='oral') ORDER BY tblmedbrand.fldbrandid ASC
        // // brand: select tblmedbrand.fldbrand as col from tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.fldmaxqty=0 and tblmedbrand.flddrug in(select tbldrug.flddrug from tbldrug where tbldrug.fldroute='oral') ORDER BY tblmedbrand.fldbrand ASC

        // $col = ($orderBy == 'brand') ? 'fldbrand' : 'fldbrandid';
        // $data = \DB::select("
        //     SELECT tblmedbrand.{$col} AS col
        //     FROM tblmedbrand
        //     WHERE
        //         tblmedbrand.fldactive=? AND
        //         tblmedbrand.fldmaxqty=? AND
        //         tblmedbrand.flddrug IN (SELECT tbldrug.flddrug FROM tbldrug WHERE tbldrug.fldroute=?)
        //     ORDER BY tblmedbrand.{$col} ASC", [
        //         'Active',
        //         0,
        //         $route,
        //     ]);

        // return response()->json($data);
    }

    public function getMedicineDetail(Request $request)
    {
        /*
            select SUM(fldqty) as col from tblentry where fldstockid='AASMA 150 XR' and fldcomp='comp07'
            select fldrate from tblstockrate where flddrug='AASMA 150 XR' and fldcomp='comp07'
        */
        $medicine = $request->get('fldstockid');
        $computer = Helpers::getCompName();
        $quantity = \App\Entry::select('fldqty')->where([
                'fldstockid' => $medicine,
                'fldcomp' => $computer,
            ])->sum('fldqty');
        $rate = \App\StockRate::select('fldrate')
            ->where([
                'flddrug' => $medicine,
                'fldcomp' => $computer,
            ])->first();
        $rate = ($rate) ? $rate->fldrate : 0;

        return response()->json(compact('rate', 'quantity'));
    }

    public function saveOrder(Request $request)
    {
        try {
            // INSERT INTO `tblorder` ( `fldsuppname`, `fldroute`, `flditemname`, `fldqty`, `fldrate`, `fldamt`, `fldsav`, `flduserid`, `fldorddate`, `fldcomp`, `flddrug`, `fldlocat`, `fldactualorddt`, `flddelvdate`, `fldpsno`, `fldremqty` ) VALUES ( 'OPENING STOCK', 'oral', 'AASMA 150 XR', 12, 12, 144, '0', 'admin', '2020-10-16 14:52:13.861', 'comp07', NULL, 'Main store', '2020-10-16 00:00:00', '2020-11-15 00:00:00', NULL, 12 )

            $fldqty = $request->get('fldqty');
            $fldrate = $request->get('fldrate');
            $data = [
                'fldsuppname' => $request->get('fldsuppname'),
                'fldroute' => $request->get('fldroute'),
                'flditemname' => $request->get('flditemname'),
                'fldqty' => $fldqty,
                'fldrate' => $fldrate,
                'fldamt' => $fldqty*$fldrate,
                'fldsav' => 0,
                'flduserid' => Helpers::getCurrentUserName(),
                'fldorddate' => date('Y-m-d H:i:s'),
                'fldcomp' => Helpers::getCompName(),
                'flddrug' => NULL,
                'fldlocat' => $request->get('fldlocat'),
                'fldactualorddt' => $request->get('fldactualorddt'),
                'flddelvdate' => $request->get('flddelvdate'),
                'fldpsno' => NULL,
                'fldremqty' => $request->get('fldqty'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $data['fldid'] = \App\Order::insertGetId($data);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully added data.',
                'data' => $data
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function finalupdate(Request $request)
    {
        try {
            $orders = \App\Order::where([
                'fldsav' => '0',
                'fldcomp' => Helpers::getCompName(),
            ])->with('purchase')->get();

            \App\Order::where([
                'fldsav' => '0',
                'fldcomp' => Helpers::getCompName()
            ])->update([
                'fldsav' => '1',
                'fldreference' => $request->get('fldreference'),
                'fldstatus' => 'Requested',
                // 'fldtax' => 0,
            ]);
            return view('store::layouts.pdf.orders', compact('orders'));
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function delete(Request $request)
    {
        try {
            \App\Order::where('fldid', $request->get('fldid'))->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function addVariable(Request $request)
    {
        try {
            $flditem = $request->get('flditem');
            $fldid = \App\Orderlocation::insertGetId([
                'flditem' => $flditem,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return [
                'status' => TRUE,
                'data' => compact('fldid', 'flditem'),
                'message' => 'Successfully saved data.'
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    public function deleteVariable(Request $request)
    {
        try {
            \App\Orderlocation::where([
                'flditem' => $request->get('flditem'),
            ])->delete();

            return [
                'status' => TRUE,
                'message' => 'Successfully deleted data.'
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to delete data.'
            ];
        }
    }
}
