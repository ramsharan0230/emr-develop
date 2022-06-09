<?php

namespace Modules\Log\Http\Controllers;

use App\ActivityLog;
use App\CogentUsers;
use App\Utils\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $data['logTraces'] = [];
        $data['users'] = CogentUsers::get();
        $data['method'] = $request->get('method');
        $data['formName'] = $request->get('formName');
        $data['date'] = $request->get('date') ? \Carbon\Carbon::parse($request->get('date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? \Auth::guard("admin_frontend")->id();
        try {
            if ($data['date'] != null || $data['user_id'] != null) {
                $logFileName = "Cogent-" . $data['date'] . ".log";
                $logPath = storage_path() . '/logs/cogent/user-' . $data['user_id'] . '/' . $logFileName;
                if (File::exists($logPath)) {
                    $data['logTraces'] = file($logPath);
                    // foreach($data['logTraces'] as $log) {
                    //     dump(explode(" : ", $log));
                    // }
                    // die;
                }
            }

            return view('log::index', $data);
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', $e->getMessage());
            return redirect()->route('logs');
        }
    }

    public function access(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id', 'user_agent')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'Access')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::access', $data);
    }

    public function event(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id', 'context')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'Event')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::event', $data);
    }

    public function labOperation(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id', 'context')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'Lab')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::lab', $data);
    }

    public function sms(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id', 'context')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'SMS')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::sms', $data);
    }

    public function patInfo(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'Pat-Info')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::pat-info', $data);
    }

    public function error(Request $request)
    {
        $data['users'] = CogentUsers::get();
        $data['from_date'] = $request->get('from_date') ? \Carbon\Carbon::parse($request->get('from_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['to_date'] = $request->get('to_date') ? \Carbon\Carbon::parse($request->get('to_date'))->format("Y-m-d") :  \Carbon\Carbon::now()->format("Y-m-d");
        $data['user_id'] = $request->get('user') ?? null;
        $data['logs'] = ActivityLog::select('id', 'message', 'remote_addr', 'record_datetime', 'user_id')
            ->whereRaw("DATE(record_datetime) >= '".$data['from_date']."'")
            ->whereRaw("DATE(record_datetime) <= '".$data['to_date']."'")
            ->when($data['user_id'], function($query) use ($data) {
                $query->where('user_id', $data['user_id']);
            })
            ->where('type', 'Error')
            ->with('user:id,username')
            ->orderBy('id', 'desc')
            ->get();
        return view('log::error', $data);
    }
}
