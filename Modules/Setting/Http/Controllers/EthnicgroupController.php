<?php

namespace Modules\Setting\Http\Controllers;

use App\EthnicGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class BedController
 * @package Modules\Setting\Http\Controllers
 */
class EthnicgroupController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['ethnic_list'] = EthnicGroup::orderBy('order_by','asc')->distinct()->paginate(500);
        return view('setting::ethniclist',$data);
    }

    public function ethnicStore(Request $request)
    {
        $validatedData = $request->validate([
            'ethnic' => 'required',
        ]);

        try {
            $result = EthnicGroup::where('flditemname',$request->ethnic)->first();
            if(isset($result) and !empty($result)){
                return response()->json([
                    'success' => [
                        'status' => true,
                        'html' => 'Duplicate Data',
                    ]
                ]);
            }else{
                EthnicGroup::create(['flditemname' => $request->ethnic]);
                $html = $this->generateEthnicList();
                return response()->json([
                    'success' => [
                        'status' => true,
                        'html' => $html,
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
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
       
}
