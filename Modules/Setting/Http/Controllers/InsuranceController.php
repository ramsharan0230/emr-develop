<?php

namespace Modules\Setting\Http\Controllers;

use App\Insurancetype;
use App\Claim;
use App\EthnicGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class BedController
 * @package Modules\Setting\Http\Controllers
 */
class InsuranceController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['insurance_list'] = Insurancetype::all();
        $data['claims'] = Claim::paginate(10);
        return view('setting::insurancelist',$data);
    }

    public function insuranceStore(Request $request)
    {
        $validatedData = $request->validate([
            'insurance' => 'required',
            'is_nep_govt' => 'required',
            'claim_code_from' => 'required',
            'claim_code_to' => 'required',
        ]);
        // $claimcodes = $request->claim_code;
        $claimcodefrom = $request->claim_code_from;
        $claimcodeto = $request->claim_code_to;
        try {
            $html = '';
            $insuranceexist = Insurancetype::where('insurancetype',$request->insurance)->first();
            if($insuranceexist){
                $currentid = $insuranceexist->id;
            }else{
                $idata['insurancetype'] = $request->insurance;
                $idata['is_nep_govt'] = $request->is_nep_govt;
                $insurance = Insurancetype::create($idata);
                $currentid = $insurance->id;
            }
            // if(isset($claimcodes) and count($claimcodes) > 0){
            if(isset($claimcodefrom) and isset($claimcodeto)){
                for($i = $claimcodefrom;$i<=$claimcodeto;$i++)
                {
                    if($i != ""){
                        $claimexist = Claim::where('claim_code',$i)->where('insurance_type_id',$currentid)->first();
                        if(!$claimexist){
                            $cdata['claim_code'] = $i;
                            $cdata['insurance_type_id'] = $currentid;
                            Claim::create($cdata);
                        }
                    }
                }
                // foreach($claimcodes as $c){
                //     if($c != ""){
                //         $claimexist = Claim::where('claim_code',$c)->where('insurance_type_id',$currentid)->first();
                //         if(!$claimexist){
                //             $cdata['claim_code'] = $c;
                //             $cdata['insurance_type_id'] = $currentid;
                //             Claim::create($cdata);
                //         }
                //     }
                // }
            }
            
            $insurancehtml = '';
            $insuranceresult = Insurancetype::all();
            if(isset($insuranceresult) and count($insuranceresult) > 0){
                foreach($insuranceresult as $ir){
                    $insurancehtml .='<option value="'.$ir->id.'">'.$ir->insurancetype.'</option>';
                }
            }
            $claimhtml = '';
            $claimresult = Claim::where('insurance_type_id',$currentid)->where('has_used',0)->get();
            // $claimresult = Claim::all();
            if(isset($claimresult) and count($claimresult) > 0){
                foreach($claimresult as $key=>$cr){
                    $sn = $key+1;
                    $claimhtml .='<tr>';
                    $claimhtml .='<td>'.$sn.'</td>';
                    $claimhtml .='<td>'.$cr->claim_code.'</td>';
                    $claimhtml .='<td>'.$cr->insurancetype->insurancetype.'</td>';
                    if($cr->fldstatus == "active"){
                        $claimhtml .= '<td><span data-id="'.$cr->id.'" class="changeStatus badge badge-success">active</span></td>';
                    }else{
                        $claimhtml .= '<td><span data-id="'.$cr->id.'" class="changeStatus badge badge-danger">inactive</span></td>';
                    }
                    $claimhtml .='</tr>';
                }
            }
            return response()->json([
                'success' => [
                    'status' => true,
                    'insurancehtml' => $insurancehtml,
                    'claimhtml'=> $claimhtml,
                ]
            ]);
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

    public function ethnicUpdate(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'ethnic' => 'required',
            'updatevalue' => 'required'
        ]);

        try {
            $data['flditemname'] = $request->ethnic;
            EthnicGroup::find($request->updatevalue)->update($data);
            
            $html = $this->generateEthnicList();
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEthnic(Request $request)
    {
        try {
            $result = EthnicGroup::orderBy('order_by','asc')->where('flditemname','LIKE',$request->key.'%')->get();
            $html = '';
            if(isset($result) and count($result) > 0){
                foreach($result as $key=>$b){
                    $serial = $key+1;
                    $html .='<tr>';
                    $html .='<td>'.$serial.'</td>';
                    $html .='<td>'.$b->flditemname.'</td>';
                    $html .='<td><a href="javascript:;" onclick="ethnicList.deleteEthnic('.$b->fldid.')"><i class="fas fa-trash text-danger"></i></a></td>';
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

    public function generateEthnicList(){
        $ethnics = EthnicGroup::orderBy('order_by','asc')->get();
        $html = '';
        if ($ethnics) {
            foreach ($ethnics as $key => $e) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$e->flditemname</td>";
                $html .= "<td><a href='javascript:;' onclick='ethnicList.deleteEthnic(" . $e->fldid .")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }

    public function saveOrder(Request $request){
        try {
            $ord = explode('&',$request->order);
            // dd($ord);
            $index=1; // 0 reserved for default
            foreach ($ord as $o) {
                
                    $data['order_by'] = $index;
                    EthnicGroup::find($o)->update($data);
                    
                    $index = $index+1;
           }
            

            session()->flash('Order Saved Successfully', __('alerts.update_success'));
            return redirect()->route('ethnic');

        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('alerts.delete_error'));
            return redirect()->route('ethnic');
        }
    }

    public function ethnicDelete(Request $request)
    {
        try {
            EthnicGroup::where('fldid', $request->fldid)->delete();

            $html = $this->generateEthnicList();
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

    public function changeStatus(Request $request)
    {
        $claim = Claim::find($request->claim_id);
        $claim->fldstatus = $claim->fldstatus == "active" ? "inactive" : "active";
        $claim->save();
  
        if($claim->fldstatus == "active"){
            $html = '<span data-id="'.$claim->id.'" class="changeStatus badge badge-success">active</span>';
        }else{
            $html = '<span data-id="'.$claim->id.'" class="changeStatus badge badge-danger">inactive</span>';
        }
        
        return response()->json(['status'=>$html]);
    }

}
