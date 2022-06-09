<?php

namespace Modules\SmsSetting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Exception;
use Session;
use App\SmsSetting;
use Illuminate\Support\Facades\DB;
use App\Utils\Options;
use App\Utils\Helpers;
use Carbon\Carbon;

class SmsSettingController extends Controller
{
    public function index(Request $request)
    {
        $data['sms_type'] = [
            'Follow Up' => 'Follow Up',
            'Deposit' => 'Deposit',
            'Events' => 'Events',
            'Lab' => 'Lab',
        ];

        $data['sms_name'] = json_encode(SmsSetting::select('sms_type','sms_name')->get());
        $data['test'] = \App\Test::select('fldtestid','fldtype')->get();
        $sms_type_search = $request->get('sms_type_search') ?? '';
        $sms_name_search = $request->get('sms_name_search') ?? '';
        $status_search = $request->get('status_search') ?? '';
        $data['sms_type_search'] = $sms_type_search;
        $data['sms_name_search'] = $sms_name_search;
        $data['status_search'] = $status_search;

        $resultData = SmsSetting::when($sms_type_search != '',function($query) use ($sms_type_search){
            $query->where('sms_type', $sms_type_search);
        })
        ->when($sms_name_search != '',function($query) use ($sms_name_search){
            $query->where('sms_name',$sms_name_search);
        })
        ->when($status_search != '',function($query) use ($status_search){
            $query->where('status',$status_search);
        });
        $data['resultData'] = $resultData->get();
        $data['html'] = view('smssetting::dynamic-smssetting', $data)->render();
        return view('smssetting::index',$data);
    }

    public function searchname(Request $request)
    {
        $sms_type_search = $request->get('sms_type_search');

        $resultData = SmsSetting::select('sms_name')->where('sms_type',$sms_type_search)->get();

        return response()->json([
            'status' => true,
            'smsName' => $resultData,
        ]);   
    }

    public function search(Request $request)
    {
        $sms_type_search = $request->get('sms_type_search');
        $sms_name_search = $request->get('sms_name_search');
        $status_search = $request->get('status_search');

        $resultData = SmsSetting::when($sms_type_search != '',function($query) use ($sms_type_search){
            $query->where('sms_type', $sms_type_search);
        })
        ->when($sms_name_search != '',function($query) use ($sms_name_search){
            $query->where('sms_name',$sms_name_search);
        })
        ->when($status_search != '',function($query) use ($status_search){
            $query->where('status',$status_search);
        });
        $data['resultData'] = $resultData->get();
        // return redirect(route('smssetting.index'));
        // $html = '';
        // $count = 1;
        // $html .='<div class="iq-card-body">
        //                 <table id="myTable1" data-show-columns="true"
        //                 data-search="true"
        //                 data-show-toggle="true"
        //                 data-pagination="true"
        //                 data-resizable="true">
        //                     <thead>
        //                     <tr>
        //                     <th class="text-center">S.N</th>
        //                     <th class="text-center">SMS Type</th>
        //                     <th class="text-center">SMS Name</th>
        //                     <th class="text-center">Status</th>
        //                     <th class="text-center">SMS Condition Details</th>
        //                     <th class="text-center">SMS Message</th>
        //                     <th class="text-center"></th>
        //                     </tr>
        //                     </thead>
        //                     <tbody>';
        // foreach($data as $d)
        // {
        //     $html .= '<tr>';
        //     $html .= '<td>' . $count . '</td>';
        //     $html .= '<td>' . $d->sms_type . '</td>';
        //     $html .= '<td>' . $d->sms_name . '</td>';
        //     $html .= '<td>' . $d->status . '</td>';

        //     if($d->sms_type == "Follow Up")
        //         $sms_type = "Free Followup Remaining Days:".' '.$d->free_follow_up_day;
        //     elseif($d->sms_type == "Deposit" && $d->deposit_condition == "Deposit")
        //         $sms_type = $d->deposit_condition.' '.$d->deposit_mode.' '.$d->deposit_percentage;
        //     elseif($d->sms_type == "Deposit" && $d->deposit_condition == "Expenses")
        //         $sms_type = $d->deposit_condition.' '.$d->deposit_mode.' '.$d->deposit_amount;
        //     elseif($d->sms_type == "Events")
        //         $sms_type = "Patient Visits Frequency".' '.$d->events_condition.' '.$d->visit_per_year;
        //     else
        //         $sms_type = $d->test_status;
            
        //     $html .= '<td>' . $sms_type . '</td>';
        //     $html .= '<td>' . $d->sms_details . '</td>';
        //     $html .= '<td>
        //                 <div class="dropdown">
        //                     <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        //                         Action
        //                     </button>
        //                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

        //                     if ($d->sms_type == 'Follow Up'){
        //                         $html.='<a class="dropdown-item sms_view" data-smstype="'.(isset($d->sms_type) ? $d->sms_type : '').'" 
        //                         data-smsname="'.(isset($d->sms_name) ? $d->sms_name : '').'"
        //                         data-status="'.(isset($d->status) ? $d->status : '').'"
        //                         data-freefollowupday="'.(isset($d->free_follow_up_day) ? $d->free_follow_up_day : '').'"
        //                         data-depositcondition="'.(isset($d->deposit_condition) ? $d->deposit_condition : '').'"
        //                         data-depositmode="'.(isset($d->deposit_mode) ? $d->deposit_mode : '').'"
        //                         data-depositamount="'.(isset($d->deposit_amount) ? $d->deposit_amount : '').'"
        //                         data-depositpercentage="'.(isset($d->deposit_percentage) ? $d->deposit_percentage : '').'"
        //                         data-eventscondition="'.(isset($d->events_condition) ? $d->events_condition : '').'"
        //                         data-visitperyear="'.(isset($d->visit_per_year) ? $d->visit_per_year : '').'"
        //                         data-testname="'.(isset($d->test_name) ? $d->test_name : '').'"
        //                         >Undo Discharge</a>';
        //                     }

        //                     $html .='<a class="dropdown-item sms_view" ><i class="fas fa-eye"></i></a>
        //                     <a class="dropdown-item" id="discharge_billing_btn"><i class="fas fa-edit"></i></a>
        //                     <a class="dropdown-item" id="creadit_btn"><i class="far fa-trash-alt"></i></a>
        //                     <a class="dropdown-item" id="deposit_billing_btn"><i class="fa-solid fa-clone"></i></a>';

        //     $html .= '</div></td></tr>';
        //     $count++;
        // }
        // return \response()->json($html);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try{
            $testname = $request->get('test_name');
            if($testname)
            {
                $test_name = json_encode($testname);
            }else{
                $test_name = null;
            }
            
            SmsSetting::create([
                'sms_type' => $request->get('sms_type'),
                'sms_name' => $request->get('sms_name'),
                'status' => $request->get('status'),
                'free_follow_up_day' => $request->get('free_follow_up_day'),
                'deposit_condition' => $request->get('deposit_condition'),
                'deposit_mode' => $request->get('deposit_mode'),
                'deposit_percentage' => $request->get('deposit_percentage'),
                'deposit_amount' => $request->get('deposit_amount'),
                'events_condition' => $request->get('events_condition'),
                'visit_per_year' => $request->get('visit_per_year'),
                'test_name' => $test_name,
                'test_status' => $request->get('test_status'),
                'test_details' => $request->get('test_details'),
                'sms_details' => $request->get('sms_details')
            ]);
            \DB::commit();

            $data['sms_type'] = [
                'Follow Up' => 'Follow Up',
                'Deposit' => 'Deposit',
                'Events' => 'Events',
                'Lab' => 'Lab',
            ];
    
            $data['sms_name'] = json_encode(SmsSetting::select('sms_type','sms_name')->get());
            $data['test'] = \App\Test::select('fldtestid','fldtype')->get();
            $sms_type_search = $request->get('sms_type_search') ?? '';
            $sms_name_search = $request->get('sms_name_search') ?? '';
            $status_search = $request->get('status_search') ?? '';
            $data['sms_type_search'] = $sms_type_search;
            $data['sms_name_search'] = $sms_name_search;
            $data['status_search'] = $status_search;
    
            $resultData = SmsSetting::when($sms_type_search != '',function($query) use ($sms_type_search){
                $query->where('sms_type', $sms_type_search);
            })
            ->when($sms_name_search != '',function($query) use ($sms_name_search){
                $query->where('sms_name',$sms_name_search);
            })
            ->when($status_search != '',function($query) use ($status_search){
                $query->where('status',$status_search);
            });
            $data['resultData'] = $resultData->get();
            $data['html'] = view('smssetting::dynamic-smssetting', $data)->render();

            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Inserted',
                'view' => $data
            ]);   
        }catch(\Exception $e)
        {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();
        try{
            $testname = $request->get('test_name_edit');
            if($testname)
            {
                $test_name = json_encode($testname);
            }else{
                $test_name = null;
            }
            
            SmsSetting::where('id',$request->get('id'))->update([
                'sms_type' => $request->get('sms_type_edit'),
                'sms_name' => $request->get('sms_name_edit'),
                'status' => $request->get('status_edit'),
                'free_follow_up_day' => $request->get('free_follow_up_day_edit'),
                'deposit_condition' => $request->get('deposit_condition_edit'),
                'deposit_mode' => $request->get('deposit_mode_edit'),
                'deposit_percentage' => $request->get('deposit_percentage_edit'),
                'deposit_amount' => $request->get('deposit_amount_edit'),
                'events_condition' => $request->get('events_condition_edit'),
                'visit_per_year' => $request->get('visit_per_year_edit'),
                'test_name' => $test_name,
                'test_status' => $request->get('test_status_edit'),
                'test_details' => $request->get('test_details_edit'),
                'sms_details' => $request->get('sms_details_edit')
            ]);
            \DB::commit();
            $data['sms_type'] = [
                'Follow Up' => 'Follow Up',
                'Deposit' => 'Deposit',
                'Events' => 'Events',
                'Lab' => 'Lab',
            ];
    
            $data['sms_name'] = json_encode(SmsSetting::select('sms_type','sms_name')->get());
            $data['test'] = \App\Test::select('fldtestid','fldtype')->get();
            $sms_type_search = $request->get('sms_type_search') ?? '';
            $sms_name_search = $request->get('sms_name_search') ?? '';
            $status_search = $request->get('status_search') ?? '';
            $data['sms_type_search'] = $sms_type_search;
            $data['sms_name_search'] = $sms_name_search;
            $data['status_search'] = $status_search;
    
            $resultData = SmsSetting::when($sms_type_search != '',function($query) use ($sms_type_search){
                $query->where('sms_type', $sms_type_search);
            })
            ->when($sms_name_search != '',function($query) use ($sms_name_search){
                $query->where('sms_name',$sms_name_search);
            })
            ->when($status_search != '',function($query) use ($status_search){
                $query->where('status',$status_search);
            });
            $data['resultData'] = $resultData->get();
            $data['html'] = view('smssetting::dynamic-smssetting', $data)->render();

            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Updated',
                'view' => $data
            ]);   
        }catch(\Exception $e)
        {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function delete($id)
    {
        try{
            $smssetting = SmsSetting::find($id);
            $smssetting->delete();
            Helpers::logStack(["SMS deleted", "Event"], ['previous_data' => $smssetting]);
            Session::flash('success_message', 'SMS Deleted Successfully.');
            return redirect(route('smssetting.index'));
        }catch(\Exception $e){
            Helpers::logStack([$e->getMessage() . ' in SMS delete', "Error"]);
            Session::flash('error_message', 'Something went wrong');
            return redirect(route('smssetting.index'));
        }
    }

    public function clone(Request $request)
    {
        \DB::beginTransaction();
        try{
            $testname = $request->get('test_name_clone');
            if($testname)
            {
                $test_name = json_encode($testname);
            }else{
                $test_name = null;
            }
            
            SmsSetting::create([
                'sms_type' => $request->get('sms_type_clone'),
                'sms_name' => $request->get('sms_name_clone'),
                'status' => $request->get('status_clone'),
                'free_follow_up_day' => $request->get('free_follow_up_day_clone'),
                'deposit_condition' => $request->get('deposit_condition_clone'),
                'deposit_mode' => $request->get('deposit_mode_clone'),
                'deposit_percentage' => $request->get('deposit_percentage_clone'),
                'deposit_amount' => $request->get('deposit_amount_clone'),
                'events_condition' => $request->get('events_condition_clone'),
                'visit_per_year' => $request->get('visit_per_year_clone'),
                'test_name' => $test_name,
                'test_status' => $request->get('test_status_clone'),
                'test_details' => $request->get('test_details_clone'),
                'sms_details' => $request->get('sms_details_clone')
            ]);
            \DB::commit();

            $data['sms_name'] = json_encode(SmsSetting::select('sms_type','sms_name')->get());
            $data['test'] = \App\Test::select('fldtestid','fldtype')->get();
            $sms_type_search = $request->get('sms_type_search') ?? '';
            $sms_name_search = $request->get('sms_name_search') ?? '';
            $status_search = $request->get('status_search') ?? '';
            $data['sms_type_search'] = $sms_type_search;
            $data['sms_name_search'] = $sms_name_search;
            $data['status_search'] = $status_search;
    
            $resultData = SmsSetting::when($sms_type_search != '',function($query) use ($sms_type_search){
                $query->where('sms_type', $sms_type_search);
            })
            ->when($sms_name_search != '',function($query) use ($sms_name_search){
                $query->where('sms_name',$sms_name_search);
            })
            ->when($status_search != '',function($query) use ($status_search){
                $query->where('status',$status_search);
            });
            $data['resultData'] = $resultData->get();
            $data['html'] = view('smssetting::dynamic-smssetting', $data)->render();

            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Inserted',
                'view' => $data
            ]);   
        }catch(\Exception $e)
        {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function reset()
    {
        return redirect()->route('smssetting.index');
    }
}
