<?php

namespace Modules\Purchase\Http\Controllers;

use App\Exports\SupplierReportExport;
use App\Supplier;
use App\SupplierDetails;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\DB;
use Session;

class PurchaseController extends Controller
{

	public function index()
	{
	}


	public function supplierInfo()
	{
		if (Permission::checkPermissionFrontendAdmin('supplier-information')) {
			$data['get_supplier_info'] = Supplier::select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit')
				->paginate(10);
			// Getting Total Debit Credit Sum
			$data['total_debit_sum'] = Supplier::get()->sum('fldpaiddebit');
			$data['total_credit_sum'] = Supplier::get()->sum('fldleftcredit');

			return view('purchase::supplier-info', $data);
		} else {
			Session::flash('display_popup_error_success', true);
			Session::flash('error_message', 'You are not authorized for this action.');
			return redirect()->route('admin.dashboard');
		}
	}

    public function searchSupplier(Request $request)
    {
        try{
            $get_supplier_info = Supplier::select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit')
                                        ->when($request->search != "", function ($q) use ($request) {
                                            return $q->where('fldsuppname', 'like', "%".$request->search."%");
                                        })
                                        ->paginate(10);
            $html = '';
            foreach($get_supplier_info as $key=>$supplier){
                $html .= '<tr>
                            <td>'.++$key.'</td>
                            <td>'.$supplier->fldsuppname.'</td>
                            <td>'.$supplier->fldsuppaddress.'</td>';
                if($supplier->fldactive == "Active"){
                    $html .= '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-success changeStatus" data-supply="'.$supplier->fldsuppname.'">Active</button></td>';
                }else{
                    $html .= '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger changeStatus" data-supply="'.$supplier->fldsuppname.'">Inactive</button></td>';
                }
                $html .= '<td>
                            <button type="button" class="btn btn-primary editsupply" data-supply="'.$supplier->fldsuppname.'"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                            <button type="button" class="btn btn-primary viewsupply" data-supply="'.$supplier->fldsuppname.'"><i class="fas fa-eye"></i>&nbsp;View</button>
                        </td>
                    </tr>';
            }
            $html .='<tr><td colspan="5">'.$get_supplier_info->appends(request()->all())->links().'</td></tr>';
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

	public function insertSupplierInfo(Request $request)
	{
		try {
            if(!$request->has('suppname')) {
                Helpers::logStack(["Supplier name is required in supplier info create", "Error"]);
                return response()->json([
					'status' => FALSE,
					'message' => 'Failed to Insert Supplier Info.',
				]);
            }

            $checkifexist = Supplier::where('fldsuppname', $request->suppname)->first();
            if ($checkifexist) {
                Helpers::logStack(["Supplier name already exist in supplier info create", "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Already Exist Please Search The List Bellow',
                ]);
            }

            $data = [
                'fldsuppname' 	  => strtoupper($request->suppname),
                'fldsuppaddress'  => $request->suppaddress,
                'fldsuppphone' 	  => $request->suppphone,
                'fldcontactname'  => $request->contactname,
                'fldcontactphone' => $request->contactphone,
                'fldstartdate' 	  => $request->startdate,
                'fldpaymentmode'  => $request->paymentmode,
                'fldcreditday'    => $request->creditday,
                'fldactive'       => $request->active,
                'fldpaiddebit'   => 0,
                'fldleftcredit'   => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldpanno'       => $request->pan_no,
                'fldvatno'       => $request->vat_no
            ];
            Supplier::insert($data);
            Helpers::logStack(["Supplier info department created", "Event"], ['current_data' => $data]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Supplier Info Successfully Added',
                'html' => $this->getAllSupplierTableInfo()
            ]);
		} catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . " in supplier info create", "Error"]);
			return response()->json([
				'status' => FALSE,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function updateSupplierInfo(Request $request)
	{
		try {
            if(!$request->has('suppname')) {
                Helpers::logStack(["Supplier name is required in supplier info update", "Error"]);
                return response()->json([
					'status' => FALSE,
					'message' => 'Failed to Insert Supplier Info.',
				]);
            }

            $checkifexist = Supplier::where('fldsuppname', $request->suppname)->first();
            if (!$checkifexist) {
                Helpers::logStack(["Supplier name already exist in supplier info update", "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Supplier Does not exist.',
                ]);
            }

            $data = [
                'fldsuppname' 	  => strtoupper($request->suppname),
                'fldsuppaddress'  => $request->suppaddress,
                'fldsuppphone' 	  => $request->suppphone,
                'fldcontactname'  => $request->contactname,
                'fldcontactphone' => $request->contactphone,
                'fldstartdate' 	  => $request->startdate,
                'fldpaymentmode'  => $request->paymentmode,
                'fldcreditday'    => $request->creditday,
                'fldactive'       => $request->active,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldpanno'       => $request->pan_no,
                'fldvatno'       => $request->vat_no
            ];

            $previousData = Supplier::where('fldsuppname', $request->suppname)->first();
            Supplier::where('fldsuppname', $request->suppname)->update($data);
            Helpers::logStack(["Supplier info department updated", "Event"], ['current_data' => $data, 'previous_data' => $previousData]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'SuUpplier info']),
                'html' => $this->getAllSupplierTableInfo()
            ]);
		} catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . " in supplier info update", "Error"]);
			return response()->json([
				'status' => FALSE,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function deleteSupplierInfo(Request $request)
	{
		try {
			$checkifexist = Supplier::where('fldsuppname', $request->suppname)->first();
			if (!$checkifexist) {
                Helpers::logStack(["Supplier not exist in supplier info delete", "Error"]);
				return response()->json([
					'status' => FALSE,
					'message' => 'Supplier Does not exist',
				]);
			}

			$debit = $request->paiddebit;
			$credit = $request->leftcredit;

			if ($debit > 0 || $credit > 0) {
                Helpers::logStack(["Supplier cannot be deleted in supplier info delete", "Error"]);
				return response()->json([
					'status' => FALSE,
					'message' => 'Supplier Debit Credit Not Clear',
				]);
			}

			Supplier::where(['fldsuppname' => $request->suppname, 'fldpaiddebit' => 0, 'fldleftcredit' => 0])->delete();
            Helpers::logStack(["Supplier info department updated", "Event"], ['previous_data' => $checkifexist]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Supplier Info Successfully Deleted.',
                'html' => $this->getAllSupplierTableInfo()
            ]);
		} catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . " in supplier info delete", "Error"]);
			return response()->json([
				'status' => FALSE,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function getSupplierInfo()
	{
		try{
			$suppname = Input::get('suppname');
			$get_supplier_info = Supplier::where('fldsuppname', $suppname)
				->select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit', 'fldpanno', 'fldvatno')
				->first();
			return response()->json([
				'status' => TRUE,
				'supplierInfo' => $get_supplier_info,
			]);
		}catch(\Exception $e){
			return response()->json([
				'status' => FALSE
			]);
		}
	}

	public function getAllSupplierInfo()
	{
		$get_supplier_info = Supplier::select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit', 'fldpanno', 'fldvatno')
			->get();
		return response()->json($get_supplier_info);
	}

	public function exportAllSupplier()
	{
		$data['get_supplier_info'] = Supplier::select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit', 'fldpanno', 'fldvatno')->get();
		// Getting Total Debit Credit Sum
		$data['total_debit_sum'] = Supplier::get()->sum('fldpaiddebit');
		$data['total_credit_sum'] = Supplier::get()->sum('fldleftcredit');
		return view('purchase::pdf.suppliers-info-pdf', $data)/*->setPaper('a4')->stream('supplier-info.pdf')*/;
	}

	public function getAllSupplierTableInfo()
	{
		$get_supplier_info = Supplier::select('fldsuppname', 'fldsuppaddress', 'fldsuppphone', 'fldcontactname', 'fldcontactphone', 'fldstartdate', 'fldpaymentmode', 'fldcreditday', 'fldactive', 'fldpaiddebit', 'fldleftcredit', 'fldpanno', 'fldvatno')
			->paginate(10);
		$get_supplier_info->setPath(route('supplier-info'));
		$view = view('purchase::supplier-table',compact('get_supplier_info'));
		return $view->render();
	}

	public function exportSupplierExcel(Request $request){
        $export = new SupplierReportExport();
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'SupplierInformation.xlsx');
    }

    public function changeSupplierStatus(Request $request){
        DB::beginTransaction();
        try {
            $chkSupplier = Supplier::where('fldsuppname', $request->supplier)->first();
            $requestdata = $request->all();
            unset($requestdata['_token']);
            if(isset($chkSupplier)){
                Supplier::where('fldsuppname', $request->supplier)->update([
                    'fldactive' => $request->fldactive
                ]);
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

    public function getSupplierDetails($supplier,Request $request){
        $data['supplier'] = $supplier;
        // $fiscalYear = Helpers::getFiscalYear();
        // $from_date = $fiscalYear->fldfirst;
        // $to_date = $fiscalYear->fldlast;
        $fromdatevalue = Helpers::dateEngToNepdash(Carbon::parse(date('Y-m-d'))->addDays(-30)->format('Y-m-d'));
        $todatevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['from_date'] = $from_date = $fromdatevalue->year.'-'.$fromdatevalue->month.'-'.$fromdatevalue->date;
        $data['to_date'] = $to_date = $todatevalue->year.'-'.$todatevalue->month.'-'.$todatevalue->date;
        $fdate = Helpers::dateNepToEng($from_date);
        $finalfrom = $fdate->year.'-'.$fdate->month.'-'.$fdate->date;
        $tdate = Helpers::dateNepToEng($to_date);
        $finalto = $tdate->year.'-'.$tdate->month.'-'.$tdate->date;
        $startTime = Carbon::parse($finalfrom)->setTime(00, 00, 00);
        $endTime = Carbon::parse($finalto)->setTime(23, 59, 59);

        $data['purchaseDatas'] = $purchaseDatas =  DB::table('tblpurchasebill')
                                    ->select('tblpurchasebill.fldsuppname as Supplier_Name',
                                    'tblpurchasebill.fldpurdate as Pur_Date',
                                    'tblpurchasebill.fldbillno as Bill_No',
                                    'tblpurchasebill.fldreference as Purchase_BillNo',
                                    'tblpurchasebill.fldpurtype as Purchase_Type',
                                    DB::raw('IFNULL(tblpurchasebill.fldcredit-tblpurchasebill.fldtotaltax,0) as Total_Amount'),
                                    DB::raw('(IFNULL(tblpurchasebill.fldtotaltax,0)) as Individual_Tax'),
                                    DB::raw('IFNULL(tblpurchasebill.fldtotalvat,0) as Group_Tax'),
                                    DB::raw('sum(IFNULL(tblpurchase.fldcasdisc,0)) as Individual_Discount'),
                                    DB::raw('IFNULL(tblpurchasebill.fldlastdisc,0) as Group_discount'),
                                    DB::raw('IFNULL(tblpurchasebill.fldcredit,0) - IFNULL(tblpurchasebill.fldlastdisc,0) + (IFNULL(tblpurchasebill.fldtotalvat,0)) + sum(IFNULL(tblpurchase.fldcarcost,0)) - sum(IFNULL(tblpurchase.fldcasdisc,0)) as Final_Amount'),
                                    DB::raw('count(tblpurchase.fldreference) as Total_Item'))



                                ->join('tblpurchase','tblpurchase.fldreference','=','tblpurchasebill.fldreference')
                                ->where('tblpurchasebill.fldsuppname',$supplier)
                                ->when($startTime != null, function ($q) use ($startTime) {
                                    return $q->where('tblpurchasebill.fldpurdate', '>=', $startTime);
                                })
                                    ->when($endTime != null, function ($q) use ($endTime) {
                                    return $q->where('tblpurchasebill.fldpurdate', '<=', $endTime);
                                })
                                ->groupBy('tblpurchase.fldreference')
                                ->orderBy('tblpurchasebill.fldpurdate','desc')
                                ->paginate(15);
        //dd($data['purchaseDatas']);
        //totaltax sum removed
        $data['paginations'] = $purchaseDatas->appends(request()->all())->links();
        return view('purchase::supplier-purchase-details',$data);
    }

    public function getSupplierDetailsAjax($supplier,Request $request){
        try{
            $data['supplier'] = $supplier;
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $purreference = $request->purchase_ref;
            $bill_no = $request->bill_no;
            $startTime = Carbon::parse($finalfrom)->setTime(00, 00, 00);
            $endTime = Carbon::parse($finalto)->setTime(23, 59, 59);

            $data['purchaseDatas'] = $purchaseDatas =  DB::table('tblpurchasebill')
                        ->select('tblpurchasebill.fldsuppname as Supplier_Name',
                        'tblpurchasebill.fldpurdate as Pur_Date',
                        'tblpurchasebill.fldbillno as Bill_No',
                        'tblpurchasebill.fldreference as Purchase_BillNo',
                        'tblpurchasebill.fldpurtype as Purchase_Type',
                        DB::raw('IFNULL(tblpurchasebill.fldcredit-tblpurchasebill.fldtotaltax,0) as Total_Amount'),
                        DB::raw('(IFNULL(tblpurchasebill.fldtotaltax,0)) as Individual_Tax'),
                        DB::raw('IFNULL(tblpurchasebill.fldtotalvat,0) as Group_Tax'),
                        DB::raw('sum(IFNULL(tblpurchase.fldcasdisc,0)) as Individual_Discount'),
                        DB::raw('IFNULL(tblpurchasebill.fldlastdisc,0) as Group_discount'),
                        DB::raw('IFNULL(tblpurchasebill.fldcredit,0) - IFNULL(tblpurchasebill.fldlastdisc,0) + (IFNULL(tblpurchasebill.fldtotalvat,0)) + sum(IFNULL(tblpurchase.fldcarcost,0)) - sum(IFNULL(tblpurchase.fldcasdisc,0)) as Final_Amount'),
                        DB::raw('count(tblpurchase.fldreference) as Total_Item'))

                ->join('tblpurchase','tblpurchase.fldreference','=','tblpurchasebill.fldreference')
                ->where('tblpurchasebill.fldsuppname',$supplier)
                ->when($startTime != null, function ($q) use ($startTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '>=', $startTime);
                })
                ->when($endTime != null, function ($q) use ($endTime) {
                    return $q->where('tblpurchasebill.fldpurdate', '<=', $endTime);
                })
                ->when($purreference != "", function ($q) use ($purreference) {
                    return $q->where('tblpurchasebill.fldreference', 'like', $purreference);
                })
                ->when($bill_no != "", function ($q) use ($bill_no) {
                    return $q->where('tblpurchasebill.fldbillno', 'like', $bill_no);
                })
                ->when($finalfrom !=null && $finalto !=null, function ($q) use ($finalfrom,$finalto) {
                    return $q->whereDate('tblpurchasebill.fldpurdate', '>=', $finalfrom)
                            ->whereDate('tblpurchasebill.fldpurdate', '<=', $finalto);
                })
                ->groupBy('tblpurchase.fldreference')
                ->orderBy('tblpurchasebill.fldpurdate','desc')
                ->paginate(15);
            //dd($purchaseDatas);
            $html = '';
            foreach($purchaseDatas as $key=>$purchaseData){
                $html .= '<tr>
                            <td>'.++$key.'</td>
                            <td>'.\Carbon\Carbon::parse($purchaseData->Pur_Date)->format('Y-m-d').'</td>
                            <td>'.$purchaseData->Purchase_BillNo.'</td>
                            <td>'.$purchaseData->Bill_No.'</td>
                            <td>'.$purchaseData->Purchase_Type.'</td>
                            <td>'.$purchaseData->Total_Item.'</td>
                            <td>'.Helpers::numberFormat((($purchaseData->Total_Amount)).'</td>
                            <td>'.Helpers::numberFormat((($purchaseData->Individual_Discount)).'</td>
                            <td>'.Helpers::numberFormat((($purchaseData->Group_discount)).'</td>
                            <td>'.Helpers::numberFormat((($purchaseData->Individual_Tax)).'</td>
                            <td>'.Helpers::numberFormat((($purchaseData->Group_Tax)).'</td>
                            <td>Rs.'.Helpers::numberFormat((($purchaseData->Final_Amount)).'</td>
                            <td>
                                <button type="button" class="btn btn-primary viewpurchasedetails" data-purreference="'.$purchaseData->Purchase_BillNo.'"><i class="fas fa-eye"></i>&nbsp;View</button>
                            </td>
                        </tr>';
            }

            $html .='<tr><td colspan="13">'.$purchaseDatas->appends(request()->all())->links().'</td></tr>';

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
}
