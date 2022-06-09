<?php

namespace Modules\Billing\Http\Controllers;

use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\User;
use App\Utils\Helpers;
use Auth;
use Carbon\Carbon;
use App\Utils\Options;
use DB;
use App\Year;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Session;
use Throwable;

/**
 * Class BillingController
 * @package Modules\Billing\Http\Controllers
 */
class BillStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Application|Factory|RedirectResponse|View
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin_frontend')->user();

        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        $data['payableUsers'] = User::where('fldpayable',1)->get();
        $data['referralUsers'] = User::where('fldreferral',1)->get();

        return view('billing::billstatus.index', $data);
    }

    public function searchBillStatus(Request $request)
    {
        try{
            $billno = '';
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateToday = Carbon::now();
            $querytobuilt = $request->invoiceno;
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
            ->first();
            $billNumberGenerate = "%-{$year->fldname}-{$querytobuilt}" . Options::get('hospital_code').'%';

            $patBilling = PatBilling::select('fldbillno', 'fldencounterval')->where('fldbillno','LIKE',$billNumberGenerate)->first();

            if($patBilling)
                $billno = $patBilling->fldbillno;



            $datas = PatBilling::select('fldencounterval','flditemname','flditemrate','flditemqty','fldtaxper','flddiscper','fldditemamt as tot','fldtime','fldbillno','fldid','flditemtype','fldsample','fldpayto','fldrefer')
                                ->whereIn('fldsample',['Waiting','Sampled','Verified','Reported','Not Done'])
                                ->where('fldsave',1)
                                ->where('fldtime','>=',$finalfrom.' 00:00:00')
                                ->where('fldtime','<=',$finalto.' 23:59:59')
                                ->when($request->category != "%", function ($q) use ($request){
                                    return $q->where('flditemtype',$request->category);
                                })
                                ->where('fldcomp','like',$request->comp)
                                ->when($request->encounter != "", function ($q) use ($request){
                                    return $q->where('fldencounterval','like',$request->encounter);
                                })
                                ->when($billno != "", function ($q) use ($billno){
                                    return $q->where('fldbillno','like',$billno);
                                })
                                ->paginate(15, ['*'], '1pagination');
            $cancelledDatas = PatBilling::select('fldencounterval','flditemname','flditemrate','flditemqty','fldtaxper','flddiscper','fldditemamt as tot','fldtime','fldbillno','fldid','flditemtype','fldsample','fldpayto','fldrefer')
                                ->where('fldsample','Removed')
                                ->where('fldsave',1)
                                ->where('fldtime','>=',$finalfrom.' 00:00:00')
                                ->where('fldtime','<=',$finalto.' 23:59:59')
                                ->when($request->category != "%", function ($q) use ($request){
                                    return $q->where('flditemtype',$request->category);
                                })
                                ->where('fldcomp','like',$request->comp)
                                ->when($request->encounter != "", function ($q) use ($request){
                                    return $q->where('fldencounterval','like',$request->encounter);
                                })
                                ->when($billno != "", function ($q) use ($billno){
                                    return $q->where('fldbillno','like',$billno);
                                })
                                ->paginate(5, ['*'], '2pagination');
            $cancelledDatas->setPageName('cancellation_per');
            $cancellationCurrentPage = $cancelledDatas->currentPage();
            $html = '';
            foreach($datas as $key=>$data){
                $html .= '<tr data-fldid="'.$data->fldid.'">
                            <td>'.++$key.'</td>
                            <td>'.$data->fldencounterval.'</td>';
                if(isset($data->encounter->patientInfo)){
                    $html .= '<td>'.$data->encounter->patientInfo->getFldrankfullnameAttribute().'</td>';
                }else{
                    $html .= '<td></td>';
                }
                $html .= '<td>'.$data->flditemname.'</td>
                            <td>'.Helpers::numberFormat($data->flditemrate).'</td>
                            <td>'.$data->flditemqty.'</td>
                            <td>'.$data->fldtaxper.'</td>
                            <td>'.$data->flddiscper.'</td>
                            <td>'.Helpers::numberFormat($data->tot).'</td>
                            <td>'.$data->fldtime.'</td>
                            <td>'.$data->fldbillno.'</td>
                            <td>'.$data->fldsample.'</td>';

                $html .= '<td>

                            <a href="#" class="text-danger cancelPatbill" data-fldid="'.$data->fldid.'" title="Cancel"> <i class="fas fa-times"></i></a>
                        </td>';

                $html .= '</tr>';
            }
            $html .='<tr><td colspan="15">'.$datas->appends(request()->all())->links().'</td></tr>';

            // Cancelled patbillings
            $cancelledHtml = '';
            foreach($cancelledDatas as $key=>$cdata){
                $cancelledHtml .= '<tr data-fldid="'.$cdata->fldid.'">
                            <td>'.++$key.'</td>
                            <td>'.$cdata->fldencounterval.'</td>';
                if(isset($cdata->encounter->patientInfo)){
                    $cancelledHtml .= '<td>'.$cdata->encounter->patientInfo->getFldrankfullnameAttribute().'</td>';
                }else{
                    $cancelledHtml .= '<td></td>';
                }
                $cancelledHtml .= '<td>'.$cdata->flditemname.'</td>
                            <td>'.Helpers::numberFormat($cdata->flditemrate).'</td>
                            <td>'.$cdata->flditemqty.'</td>
                            <td>'.$cdata->fldtaxper.'</td>
                            <td>'.$cdata->flddiscper.'</td>
                            <td>'.Helpers::numberFormat($cdata->tot).'</td>
                            <td>'.$cdata->fldtime.'</td>
                            <td>'.$cdata->fldbillno.'</td>
                            <td>'.$cdata->fldsample.'</td>';

                $cancelledHtml .= '</tr>';
            }
            $cancelledHtml .='<tr><td colspan="14">'.$cancelledDatas->appends(request()->all())->links().'</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                    'cancelledHtml' => $cancelledHtml,
                    'cancellationCurrentPage' => $cancellationCurrentPage
                ]
            ]);
        }catch(\Exception $e){
            dd($e);
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function saveReferral(Request $request){
        try{
            $patBilling = PatBilling::where('fldid',$request->patBill)->first();
            if(isset($patBilling)){
                PatBilling::where('fldid',$request->patBill)->update([
                    'fldrefer' => $request->selectedReferral
                ]);
            }
            return response()->json([
                'data' => [
                    'status' => true
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function savePayable(Request $request){
        try{
            $patBilling = PatBilling::where('fldid',$request->patBill)->first();
            if(isset($patBilling)){
                PatBilling::where('fldid',$request->patBill)->update([
                    'fldpayto' => $request->selectedPayable
                ]);
            }
            return response()->json([
                'data' => [
                    'status' => true
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function cancelPatbill(Request $request){
        try{
            $patBilling = PatBilling::where('fldid',$request->patBill)->first();
            if(isset($patBilling)){
                PatBilling::where('fldid',$request->patBill)->update([
                    'fldsample' => "Removed",
                    'xyz' => 0
                ]);
            }
            $decoded = json_decode(json_encode($this->searchCancelledBill($request)->getData()), true);
            $html = $decoded['data']['html'];
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function searchCancelledBill(Request $request){
        try{
            $currentPage = $request->cancellation_per;
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $cancelledDatas = PatBilling::select('fldencounterval','flditemname','flditemrate','flditemqty','fldtaxper','flddiscper','fldditemamt as tot','fldtime','fldbillno','fldid','flditemtype','fldsample','fldpayto','fldrefer')
                                ->where('fldsample','Removed')
                                ->where('fldsave',1)
                                ->where('fldtime','>=',$finalfrom.' 00:00:00')
                                ->where('fldtime','<=',$finalto.' 23:59:59')
                                ->when($request->category != "%", function ($q) use ($request){
                                    return $q->where('flditemtype',$request->category);
                                })
                                ->where('fldcomp','like',$request->comp)
                                ->when($request->encounter != "", function ($q) use ($request){
                                    return $q->where('fldencounterval','like',$request->encounter);
                                })
                                ->when($request->invoiceno != "", function ($q) use ($request){
                                    return $q->where('fldbillno','like',$request->invoiceno);
                                })
                                ->paginate(5, ['*'], '2pagination');
            $cancelledDatas->setPageName('cancellation_per');
            $cancellationCurrentPage = $cancelledDatas->currentPage();
            $cancelledHtml = '';
            foreach($cancelledDatas as $key=>$cdata){
                $cancelledHtml .= '<tr data-fldid="'.$cdata->fldid.'">
                            <td>'.++$key.'</td>
                            <td>'.$cdata->fldencounterval.'</td>';
                if(isset($cdata->encounter->patientInfo)){
                    $cancelledHtml .= '<td>'.$cdata->encounter->patientInfo->getFldrankfullnameAttribute().'</td>';
                }else{
                    $cancelledHtml .= '<td></td>';
                }
                $cancelledHtml .= '<td>'.$cdata->flditemname.'</td>
                            <td>'.Helpers::numberFormat($cdata->flditemrate).'</td>
                            <td>'.$cdata->flditemqty.'</td>
                            <td>'.$cdata->fldtaxper.'</td>
                            <td>'.$cdata->flddiscper.'</td>
                            <td>'.Helpers::numberFormat($cdata->tot).'</td>
                            <td>'.$cdata->fldtime.'</td>
                            <td>'.$cdata->fldbillno.'</td>
                            <td>'.$cdata->fldsample.'</td>';

                $cancelledHtml .= '</tr>';
            }
            $cancelledHtml .='<tr><td colspan="14">'.$cancelledDatas->appends(request()->all())->links().'</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $cancelledHtml,
                    'cancellationCurrentPage' => $cancellationCurrentPage
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function exportPdf(Request $request){
        $from_date = Helpers::dateNepToEng($request->from_date);
        $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
        $to_date = Helpers::dateNepToEng($request->to_date);
        $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
        $alldata['category'] = $category = $request->category;
        $alldata['comp'] = $comp = $request->comp;
        $billno = '';
        $dateToday = Carbon::now();
        $querytobuilt = $request->invoiceno;
        $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
        ->first();
        $billNumberGenerate = "%-{$year->fldname}-{$querytobuilt}" . Options::get('hospital_code').'%';
        //dd($billNumberGenerate);
        $patBilling = PatBilling::select('fldbillno', 'fldencounterval')->where('fldbillno','LIKE',$billNumberGenerate)->first();
        if($patBilling)
            $billno = $patBilling->fldbillno;


        $datas = PatBilling::select('fldencounterval','flditemname','flditemrate','flditemqty','fldtaxper','flddiscper','fldditemamt as tot','fldtime','fldbillno','fldid','flditemtype','fldsample','fldpayto','fldrefer')
                            ->whereIn('fldsample',['Waiting','Sampled'])
                            ->where('fldsave',1)
                            ->where('fldtime','>=',$finalfrom.' 00:00:00')
                            ->where('fldtime','<=',$finalto.' 23:59:59')
                            ->when($request->category != "%", function ($q) use ($request){
                                return $q->where('flditemtype',$request->category);
                            })
                            ->where('fldcomp','like',$request->comp)
                            ->when($request->encounter != "", function ($q) use ($request){
                                return $q->where('fldencounterval','like',$request->encounter);
                            })
                            ->when($billno != "", function ($q) use ($billno){
                                return $q->where('fldbillno','like',$billno);
                            })
                            ->get();
        $html = '';
        foreach($datas as $key=>$data){
            $html .= '<tr data-fldid="'.$data->fldid.'">
                        <td>'.++$key.'</td>
                        <td>'.$data->fldencounterval.'</td>';
            if(isset($data->encounter->patientInfo)){
                $html .= '<td>'.$data->encounter->patientInfo->getFldrankfullnameAttribute().'</td>';
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td>'.$data->flditemname.'</td>
                        <td>'.Helpers::numberFormat($data->flditemrate).'</td>
                        <td>'.$data->flditemqty.'</td>
                        <td>'.$data->fldtaxper.'</td>
                        <td>'.$data->flddiscper.'</td>
                        <td>'.Helpers::numberFormat($data->tot).'</td>
                        <td>'.$data->fldtime.'</td>
                        <td>'.$data->fldbillno.'</td>
                        <td>'.$data->fldsample.'</td>';

            $html .= '</tr>';
        }
        $alldata['html'] = $html;
        return view('billing::billstatus.pdf',$alldata);
    }

}
