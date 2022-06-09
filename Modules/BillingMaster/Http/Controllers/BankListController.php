<?php

namespace Modules\BillingMaster\Http\Controllers;

use App\Banks;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BankListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    { 
        $data['bank_list'] = $this->generateBankList();
        return view('billingmaster::bank.list', $data);
    }

    public function bankStore(Request $request)
    {
        $validatedData = $request->validate([
            'bank' => 'required',
        ]);
        $html = '';
        try {
            $result = Banks::where('fldbankname',$request->bank)->first();
            if(isset($result) and !empty($result)){
                return response()->json([
                    'success' => [
                        'status' => true,
                        'html' => 'Duplicate Data',
                    ]
                ]);
            }else{
                // Banks::create(['fldbankname' => $request->bank]);
                Banks::insertGetId([
                    'fldbankname' => $request->bank,
                    'hospital_department_id' =>Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
                $html = $this->generateBankList();
                return response()->json([
                    'success' => [
                        'status' => true,
                        'html' => $html,
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'success' => [
                    'status' => false,
                    'html' => $html,
                ]
            ]);
        }
    }

    public function searchBank(Request $request)
    {
        

        try {
            $result = Banks::where('fldbankname','LIKE',$request->key.'%')->get();
            $html = '';
            if(isset($result) and count($result) > 0){
                foreach($result as $key=>$b){
                    $serial = $key+1;
                    $html .='<tr>';
                    $html .='<td>'.$serial.'</td>';
                    $html .='<td>'.$b->fldbankname.'</td>';
                    $html .='<td><a href="javascript:;" onclick="bankList.deleteBank('.$b->fldid.')"><i class="fas fa-trash text-danger"></i></a></td>';
                }
                
            }
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
                    'html' => $html,
                ]
            ]);
        }
    }

    

    public function bankDelete(Request $request)
    {
        try {
            Banks::where('fldid', $request->fldid)->delete();

            $html = $this->generateBankList();
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
                    'html' => $html,
                ]
            ]);
        }
    }

    public function generateBankList()
    {
        $tax = Banks::all();
        $html = '';
        if ($tax) {
            foreach ($tax as $key => $type) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$type->fldbankname</td>";
                $html .= "<td><a href='javascript:;' onclick='bankList.deleteBank(" . $type->fldid .")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
