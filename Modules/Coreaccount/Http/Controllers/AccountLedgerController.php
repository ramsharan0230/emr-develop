<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AccountLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['groups'] = DB::table('account_group')->get();
        $data['ledgerLists'] = $this->getLedgers();
        return view('coreaccount::accountledger.index',$data);
    }

    public function getLedgers(){
        $ledgers = AccountLedger::get();
        $html = '';
        foreach($ledgers as $key=>$ledger){
            $html .= "<tr>";
            $html .= "<td>" . ++$key . "</td>";
            if(isset($ledger->account_group->GroupName) and $ledger->account_group->GroupName !=''){
                $html .= "<td>".$ledger->account_group->GroupName."</td>";
            }else{
                $html .= "<td></td>";
            }
            // $html .= "<td>".$ledger->account_group->GroupName."</td>";
            $html .= "<td>".$ledger->AccountNo."</td>";
            $html .= "<td>".$ledger->AccountName."</td>";
            $html .= "<td>".$ledger->AccountNameNep."</td>";
            if($ledger->fldstatus == 1){
                $html .= '<td class="text-center"><button type="button" data-accountid="'.$ledger->AccountId.'" class="btn btn-outline-success btn-action changeStatus">Active</button></td>';
            }else{
                $html .= '<td class="text-center"><button type="button" data-accountid="'.$ledger->AccountId.'" class="btn btn-outline-danger btn-action changeStatus">Inactive</button></td>';
            }
            $html .= '<td class="text-center">
                            <a href="#!" class="btn btn-primary editLedger" data-accountid="'.$ledger->AccountId.'" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                            <a href="#!" class="btn btn-danger deleteLedger" data-accountid="'.$ledger->AccountId.'"><i class="ri-delete-bin-fill"></i></a>
                        </td>';
            $html .= "</tr>";
        }
        // $html .='<tr><td colspan="7">'.$ledgers->appends(request()->all())->links().'</td></tr>';
        return $html;
    }

    public function ledgerLists(Request $request){
        $ledgers = AccountLedger::paginate(15);
        $html = '';
        foreach($ledgers as $key=>$ledger){
            $html .= "<tr>";
            $html .= "<td>" . ++$key . "</td>";
            $html .= "<td>".$ledger->account_group->GroupName."</td>";
            $html .= "<td>".$ledger->AccountNo."</td>";
            $html .= "<td>".$ledger->AccountName."</td>";
            $html .= "<td>".$ledger->AccountNameNep."</td>";
            if($ledger->fldstatus == 1){
                $html .= '<td class="text-center"><button type="button" data-accountid="'.$ledger->AccountId.'" class="btn btn-outline-success btn-action changeStatus">Active</button></td>';
            }else{
                $html .= '<td class="text-center"><button type="button" data-accountid="'.$ledger->AccountId.'" class="btn btn-outline-danger btn-action changeStatus">Inactive</button></td>';
            }
            $html .= '<td class="text-center">
                            <a href="#!" class="btn btn-primary editLedger" data-accountid="'.$ledger->AccountId.'" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                            <a href="#!" class="btn btn-danger deleteLedger" data-accountid="'.$ledger->AccountId.'"><i class="ri-delete-bin-fill"></i></a>
                        </td>';
            $html .= "</tr>";
        }
        $html .='<tr><td colspan="7">'.$ledgers->appends(request()->all())->links().'</td></tr>';
        return $html;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $html = '';
            $chkLedger = AccountLedger::where('AccountId', $request->AccountId)->first();
            $rules = [
                'AccountNo' => 'required',
                'AccountName' => 'required|unique:account_ledger,AccountName',
                'GroupId' => 'required'
            ];
            $request->validate($rules, [
                'AccountNo.required' => 'Account No. is required',
                'AccountName.required' => 'Account Name is required',
                'GroupId.required' => 'Account Group is required'
            ]);
            $requestdata = $request->all();
            unset($requestdata['_token']);
            if(isset($chkLedger)){
                $requestdata['ModifiedBy'] = \Auth::guard('admin_frontend')->user()->flduserid;
                $requestdata['ModifiedDate'] = date('Y-m-d H:i:s');
                AccountLedger::where('AccountId', $request->AccountId)->update($requestdata);
            }else{
                $requestdata['CreatedBy'] = \Auth::guard('admin_frontend')->user()->flduserid;
                AccountLedger::insert($requestdata);
            }
            $html = $this->getLedgers();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Account Ledger saved successfully',
                'html' => $html,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $error_message = $e->getMessage();
            return response()->json([
                'status' => false,
                'message' => $error_message
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('coreaccount::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        try {
            $ledgerData = AccountLedger::where('AccountId',$request->AccountId)->with('account_group')->first();
            return response()->json([
                'status' => true,
                'messDB::rollBack();age' => 'Account Ledger saved successfully',
                'ledgerData' => $ledgerData
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'status' => false,
                'message' => $error_message
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $html = '';
            $chkLedger = AccountLedger::where('AccountId', $request->AccountId)->first();
            $requestdata = $request->all();
            unset($requestdata['_token']);
            if(isset($chkLedger)){
                $requestdata['ModifiedBy'] = \Auth::guard('admin_frontend')->user()->flduserid;
                $requestdata['ModifiedDate'] = date('Y-m-d H:i:s');
                AccountLedger::where('AccountId', $request->AccountId)->update($requestdata);
                DB::commit();
                return response()->json([
                    'status' => true,
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $error_message = $e->getMessage();
            return response()->json([
                'status' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $html = '';
            $ledgerData = AccountLedger::where('AccountId',$request->AccountId)->first();
            if(isset($ledgerData)){
                AccountLedger::where('AccountId',$request->AccountId)->delete();
            }
            $html = $this->getLedgers();
            return response()->json([
                'status' => true,
                'message' => 'Account Ledger deleted successfully',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'status' => false,
                'message' => $error_message
            ]);
        }
    }

    public function getAccountNumber(Request $request){
        // dd($request->all());
        try{
            $accountgroup = \App\AccountGroup::where('GroupId',$request->accountcode)->first();

            $group = $accountgroup->GroupTree;
            // echo $group[0]; exit;
            $existingdata = \App\AccountLedger::where('AccountNo','LIKE',$group[0].'%')->get();
            if(isset($existingdata) and count($existingdata) > 0){
                $number = \App\AccountLedger::where('AccountNo','LIKE',$group[0].'%')->max('AccountNo');
                $accountnumber = $number+1;
                // echo $accountnumber; exit;
            }else{
                $accountnumber = $group[0].'000000001';
            }
            return $accountnumber;
        }catch(\Exception $e){
            dd($e);
        }
    }
}
