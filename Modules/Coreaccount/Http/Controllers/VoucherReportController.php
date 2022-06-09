<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\TransactionMaster;
use App\Utils\Helpers;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Excel;

class VoucherReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        return view('coreaccount::voucher-report.index',$data);
    }

    public function filter(Request $request){
        try{
            $data['voucher_no'] = $voucher_no = $request->voucherNo;
            $voucher = explode("-",$voucher_no);
            $data['voucherDatas'] = $voucherDatas = TransactionMaster::where([['VoucherNo',$voucher[1]],['VoucherCode',$voucher[0]]])->orderBy('TranDate','asc')->get();
            if(count($voucherDatas)>0){
                $loggedInUser = \Auth::guard('admin_frontend')->user()->username;
                $html = '';
                $enteredByUser = $voucherDatas[0]->CreatedBy;
                foreach ($voucherDatas as $key=>$voucherData){
                    $html .= '<tr>
                                <td>'.++$key.'</td>';
                    if(isset($voucherData->branch)){
                        $html .= '<td>{{$voucherData->branch->name}}</td>';
                    }else{ 
                        $html .= '<td></td>';
                    }
                        $html .= '<td>'.$voucherData->AccountNo.'</td>
                                    <td>'.$voucherData->accountLedger->AccountName.'</td>
                                    <td>'.$voucherData->Remarks.'</td>';
                    if ($voucherData->TranAmount > 0){
                        $html .= '<td>'.$voucherData->TranAmount.'</td>
                                <td>0</td>';
                    }else{
                        $transAmount = $voucherData->TranAmount * (-1);
                        $html .= '<td>0</td>
                                <td>'.$transAmount.'</td>';
                    }
                        $html .= '</tr>';
                }
                return response()->json([
                    'data' => [
                        'status' => true,
                        'voucherDatas' => $voucherDatas,
                        'loggedInUser' => $loggedInUser,
                        'enteredByUser' => $enteredByUser,
                        'html' => $html,
                        'voucher_no' => $voucher_no
                    ]
                ]);
            }else{
                return response()->json([
                    'data' => [
                        'status' => false,
                        'msg' => "Invalid Voucher number"
                    ]
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false,
                    'msg' => "Invalid Voucher number"
                ]
            ]);
        }
    }

    public function checkVoucher(Request $request){
        $data['voucher_no'] = $voucher_no = $request->voucherNo;
        $voucher = explode("-",$voucher_no);
        $data['voucherDatas'] = $voucherDatas = TransactionMaster::where([['VoucherNo',$voucher[1]],['VoucherCode',$voucher[0]]])->orderBy('TranDate','asc')->get();
        if(count($voucherDatas)>0){
            return response()->json([
                'data' => [
                    'status' => true,
                    'msg' => "Voucher number found"
                ]
            ]);
        }else{
            return response()->json([
                'data' => [
                    'status' => false,
                    'msg' => "Invalid Voucher number"
                ]
            ]);
        }
    }
}