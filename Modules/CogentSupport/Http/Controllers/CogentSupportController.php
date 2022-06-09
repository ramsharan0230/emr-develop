<?php

namespace Modules\CogentSupport\Http\Controllers;

use App\ConLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CogentSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['logData'] = ConLog::all()->groupBy(['fldhospname','flddepartment']);
        // dd($data['logData']->toArray());
        return view('cogentsupport::index',$data);
    }

    public function getLog(Request $request)
    {
        $hospitalName = $request->hospitalName;
        $departmentName = $request->departmentName;
        $logData = ConLog::where([['fldhospname',$hospitalName],['flddepartment',$departmentName]])->get();
        $html = '<table class="table table-bordered table-hover table-striped" id="table_'.$hospitalName.'_'.$departmentName.'">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date Time</th>
                            <th class="text-center">Computer Name</th>
                            <th class="text-center">Hospital Name</th>
                            <th class="text-center">Hospital Address</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">User Name</th>
                        </tr>
                    </thead>';
        foreach($logData as $key=>$item){
            $html .= '<tr>
                        <td>'.++$key.'</td>
                        <td>'.$item->fldtime.'</td>
                        <td>'.$item->fldhostname.'</td>
                        <td>'.$item->fldhospname.'</td>
                        <td>'.$item->fldhospadd.'</td>
                        <td>'.$item->flddepartment.'</td>
                        <td>'.$item->fldappuser.'</td>
                    </tr>';
        }
        $html .= '</table>';
        
        return response()->json([
            'success' => [
                'logData' => $html,
                'hospitalName' => $hospitalName,
                'departmentName' => $departmentName
            ]
        ]);
    }
}
