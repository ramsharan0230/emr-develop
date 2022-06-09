<?php

namespace Modules\BulkSms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Municipal;
use App\BulkSms;
use App\Encounter;
use App\Jobs\SendSms;
use App\PatientInfo;
use App\Utils\Helpers;
use Exception;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Utils\Options;

class BulkSmsController extends Controller
{
    public function index()
    {
        $bulksms = BulkSms::paginate(25);
        $pagination = $bulksms->appends(request()->all())->links();
        return view('bulksms::index',compact('bulksms','pagination'));
    }

    public function create()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $all_data = Municipal::select('flddistrict','fldprovince')->groupBy('flddistrict')->get();
        $data['addresses'] = json_encode($all_data->groupBy('fldprovince')->toArray());
        
        return view('bulksms::create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fldtype' => 'required',
            'fldsubtype' => 'required',
            'fldmessage' => 'required'
        ]);

        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $result = BulkSms::create([
                'from_date' => $finalfrom,
                'to_date' => $finalto,
                'fldtype' => $request->get('fldtype'),
                'fldsubtype' => $request->get('fldsubtype'),
                'fldmessage' => $request->get('fldmessage')
            ]);
            Helpers::logStack(["Bulk SMS created", "Event"], ['current_data' => $result]);
            Session::flash('success_message', 'SMS Inserted Successfully.');
            return redirect(route('bulksms.index'));
        }
        catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in bulk SMS create', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect(route('bulksms.create'))->withInput($request->all());
        }
    }

    public function edit($id)
    {
        $bulksms = BulkSms::find($id);
        $fromdate = Helpers::dateEngToNepdash(Carbon::parse($bulksms->from_date)->format('Y-m-d'));
        $from_date = $fromdate->year . '-' . $fromdate->month . '-' . $fromdate->date;
        $todate = Helpers::dateEngToNepdash(Carbon::parse($bulksms->to_date)->format('Y-m-d'));
        $to_date = $todate->year . '-' . $todate->month . '-' . $todate->date;
        $all_data = Municipal::select('flddistrict','fldprovince')->groupBy('flddistrict')->get();
        $addresses = json_encode($all_data->groupBy('fldprovince')->toArray());
        return view('bulksms::edit',compact('bulksms','addresses','from_date','to_date'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fldtype' => 'required',
            'fldsubtype' => 'required',
            'fldmessage' => 'required'
        ]);

        try {
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
            $previous = BulkSms::where('fldid',$id)->get();
            $result = BulkSms::where('fldid',$id)->update([
                'from_date' => $finalfrom,
                'to_date' => $finalto,
                'fldtype' => $request->get('fldtype'),
                'fldsubtype' => $request->get('fldsubtype'),
                'fldmessage' => $request->get('fldmessage')
            ]);
            Helpers::logStack(["Bulk SMS updated", "Event"], ['current_data' => $result, 'previous_data' => $previous]);
            Session::flash('success_message', 'SMS Updated Successfully.');
            return redirect(route('bulksms.index'));
        }
        catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in bulk SMS update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect(route('bulksms.update'))->withInput($request->all());
        }
    }

    public function delete($id)
    {
        try{
            $bulksms = BulkSms::find($id);
            $bulksms->delete();
            Helpers::logStack(["Bulk SMS deleted", "Event"], ['previous_data' => $bulksms]);
            Session::flash('success_message', 'SMS Deleted Successfully.');
            return redirect(route('bulksms.index'));
        }catch(\Exception $e){
            Helpers::logStack([$e->getMessage() . ' in bulk SMS delete', "Error"]);
            Session::flash('error_message', 'Something went wrong');
            return redirect(route('bulksms.index'));
        }
    }

    public function send($id)
    {
        try{
            $bulksms = BulkSms::find($id);
            $from_date = $bulksms->from_date;
            $to_date = $bulksms->to_date;
            $fldtype = $bulksms->fldtype;
            $fldsubtype = $bulksms->fldsubtype;
            
            $resultData = Encounter::select('fldencounterval', 'fldpatientval', 'flddoa', 'fldadmission', 'fldregdate')
            ->where('fldadmission',"Registered")
            ->when($from_date && $to_date != '', function($query) use ($from_date,$to_date) {
                $query->where(function($query) use ($from_date,$to_date){
                    $query->whereBetween('flddoa', [$from_date, $to_date]);
                });
            })
            ->when($fldtype == "All_Patient", function($query){
                $query->whereHas('patientInfo');
            })
            ->when($fldtype == "District", function($query) use ($fldsubtype) {
                $query->whereHas('patientInfo', function ($q) use ($fldsubtype) {
                    $q->where('fldptadddist', $fldsubtype);
                });
            })
            ->when($fldtype == "Province", function($query) use ($fldsubtype) {
                $query->whereHas('patientInfo', function ($q) use ($fldsubtype) {
                    $q->where('fldprovince', $fldsubtype);
                });
            });
            
            $resultData->with(['patientInfo'])->chunk(100, function($resultArray) use ($bulksms){
                foreach($resultArray as $resArray)
                {
                    $data['text'] = strtr(Options::get('bulk_sms'), [
                        '{$name}' => $resArray->patientInfo->fldfullname,
                        '{$message}' => $bulksms->fldmessage,
                        '{$system_name}' => isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '',
                    ]);
                    $data['contact'] = $resArray->patientInfo->fldptcontact;
                    SendSms::dispatch($data);
                }
                // dump($resultArray->pluck('patientInfo.fldfullname'));
            });
            // die;
            BulkSms::where('fldid',$id)->update(['status' => 1]);
            Session::flash('success_message', 'SMS Sent Successfully.');
            return redirect(route('bulksms.index'));
            
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong');
            return redirect(route('bulksms.index'));
        }
    }
}
