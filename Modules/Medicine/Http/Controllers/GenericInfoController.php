<?php

namespace Modules\Medicine\Http\Controllers;

use App\Code;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Utils\Helpers;
use Session;

class GenericInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-view'  ])  ) ?
            abort(403) : true ;

        if (Permission::checkPermissionFrontendAdmin('generic-information')) {
            $data = [];

            return view('medicine::genericinfo.genericinfo', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }


    public function searchGenericinfo(Request $request){

        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-view'  ])  ) ?
            abort(403) : true ;
        $html = '';
        $searchtext = $_GET['term'];
            // echo $searchtext; exit;
        if($searchtext !=''){
            $result = Code::orderBy('fldcodename', 'ASC')->where('fldcodename','LIKE',$searchtext.'%')->get();
        }else{
            $result = Code::orderBy('fldcodename', 'ASC')->get();
        }

        if(isset($result) and count($result) > 0){
            foreach($result as $data){
                $code = encrypt($data->fldcodename);
                $html .='<tr data-generic="'.$data->fldcodename.'">';
                $html .='<td>'.$data->fldcodename.'</td>';
                $html .='<td class="d-flex">
                            <a href="'.URL::to('/medicines/genericinfo/edit/'.$code).'"  title="edit '.$data->fldcodename.'" class="text-primary"><i class="fa fa-edit"></i></a>&nbsp;
                        </td>';
                        // <a  title="delete '.$data->fldcodename.'" class="deletegenericinfo text-danger" data-href="'.URL::to('/medicines/genericinfo/delete/'.$code).'"><i class="ri-delete-bin-5-fill"></i></a>
                $html .='</tr>';
            }
        }
        echo $html; exit;

    }

    public function addGenericInfo(Request $request)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status view page  and boolean|mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-create'  ])  ) ?
            abort(403) : true ;
       $fldcodename = $request->fldcodename;
        $request->validate([
            // 'fldcodename' => 'required|unique:tblcode',
            'fldcodename' => 'required|unique:tblcode,fldcodename,' . $fldcodename . ',fldcodename',
            // 'fldrecaddose' => 'numeric',
            // 'fldrecpeddose' => 'numeric',
            // 'fldrecadfreq' => 'integer',
            // 'fldrecpedfreq' => 'integer',
            // 'fldeliminhepatic' => 'numeric',
            // 'fldplasmaprotein' => 'numeric',
            // 'fldeliminrenal' => 'numeric',
            // 'fldeliminhalflife' => 'numeric'

        ], [
            'fldcodename.required' => 'Generic Name field is required',
            // 'fldrecaddose.numeric' => 'Adult Dose field must be number',
            // 'fldrecpeddose.numeric' => 'Paed Dose field must be number',
            // 'fldrecadfreq.integer' => 'Adult Freq fiedld must be an integer',
            // 'fldrecpedfreq.integer' => 'Paed Freq fiedld must be an integer',
            // 'fldeliminhepatic.numeric' => 'Elimination fiedld field must be an number',
            // 'fldplasmaprotein.numeric' => 'Plasma Protein Binding field must be an number',
            // 'fldeliminrenal.numeric' => 'Renal field must be an number',
            // 'fldeliminhalflife.numeric' => 'Elimination Half life must be an number',

        ]);

        try {
            Helpers::logStack(["[POST][Generic Info][Add]", "Request Data"],[json_encode($request->all())]);
            $generic_data = $request->all();
            unset($generic_data['_token']);
            unset($generic_data['_method']);

            $code = Code::where('fldcodename', $fldcodename)->first();
            Helpers::logStack(["[POST][Generic Info][Add]", "Previous Data"], [json_encode($code)]);
            $code->update($generic_data, ['timestamps' => false]);
            // Code::insert($generic_data);
            Helpers::logStack(["[POST][Generic Info][Add]", "Response Data"], [json_encode($generic_data)]);

            Session::flash('success_message', 'Generic Info added sucessfully');
            return redirect()->route('medicines.generic.list');
        } catch (\Exception $e) {
            Helpers::logStack(["[POST][Generic Info][Add]", "Error Response"], [$e->getMessage()]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('medicines.generic.list');
        }
    }


    public function editgenericInfo($fldcodename)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-edit', 'generic-information-update'  ])  ) ?
            abort(403) : true ;

        $fldcodename = decrypt($fldcodename);
        $data = [];
        $code = Code::where('fldcodename', $fldcodename)->first();

        $data['code'] = $code;

        return view('medicine::genericinfo.genericinfoedit', $data);
    }

    public function updateGenericInfo(Request $request, $fldcodename)
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-edit', 'generic-information-update'  ])  ) ?
            abort(403) : true ;
        $fldcodename = decrypt($fldcodename);
        $request->validate([
            'fldcodename' => 'required|unique:tblcode,fldcodename,' . $fldcodename . ',fldcodename',
            // 'fldrecaddose' => 'numeric',
            // 'fldrecpeddose' => 'numeric',
            // 'fldrecadfreq' => 'integer',
            // 'fldrecpedfreq' => 'integer',
            // 'fldeliminhepatic' => 'numeric',
            // 'fldplasmaprotein' => 'numeric',
            // 'fldeliminrenal' => 'numeric',
            // 'fldeliminhalflife' => 'numeric'
        ], [
            'fldcodename.required' => 'Generic Name field is required',
            // 'fldrecaddose.numeric' => 'Adult Dose field must be number',
            // 'fldrecpeddose.numeric' => 'Paed Dose field must be number',
            // 'fldrecadfreq.integer' => 'Adult Freq fiedld must be an integer',
            // 'fldrecpedfreq.integer' => 'Paed Freq fiedld must be an integer',
            // 'fldeliminhepatic.numeric' => 'Elimination fiedld field must be an number',
            // 'fldplasmaprotein.numeric' => 'Plasma Protein Binding field must be an number',
            // 'fldeliminrenal.numeric' => 'Renal field must be an number',
            // 'fldeliminhalflife.numeric' => 'Elimination Half life must be an number',
        ]);
        try {
            Helpers::logStack(["[POST][Generic Info][Edit]", "Request Data"], [json_encode($request->all())]);
            $generic_data = $request->all();

            unset($generic_data['_token']);
            unset($generic_data['_method']);
            // dd($generic_data);
            $code = Code::where('fldcodename', $fldcodename)->first();
            Helpers::logStack(["[POST][Generic Info][Edit]", "Previous Data"],[json_encode($code)]);
            Code::where('fldcodename', $fldcodename)->update($generic_data);
            Helpers::logStack(["[POST][Generic Info][Edit]", "Response Data"], [json_encode($generic_data)]);
            Session::flash('success_message', 'Generic Info updated sucessfully');
            return redirect()->route('medicines.generic.list');
        } catch (\Exception $e) {
            Helpers::logStack(["[POST][Generic Info][Edit]", "Error Response"], [$e->getMessage()]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('medicines.generic.list');
        }
    }

    public function deleteGenericInfo($fldcodename)
    {
        try {
                /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'generic-information', 'generic-information-delete'  ])  ) ?
            abort(403) : true ;
            Helpers::logStack(["[POST][Generic Info][Delete]", "Request Data"],[decrypt($fldcodename)]);
            $fldcodename = decrypt($fldcodename);
            $code = Code::where('fldcodename', $fldcodename)->first();

            if (!$code) {
                Helpers::logStack(["[POST][Generic Info][Delete]", "Response Data"], [$code->fldcodename . 'item not found']);
                Session::flash('error_message', $code->fldcodename . 'item not found');
                return redirect()->route('medicines.generic.list');
            }

            $data = DB::table('tblcode')->where('fldcodename', $fldcodename)->update(['fldstatus' => 0]);
            Helpers::logStack(["[POST][Generic Info][Delete]", "Response Data"], [json_encode($data)]);
            Session::flash('success_message', $code->fldcodename . ' deleted sucessfully');
            return redirect()->route('medicines.generic.list');
        } catch (\Exception $e) {
            Helpers::logStack(["[POST][Generic Info][Delete]", "Response Data"], [$e->getMessage()]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('medicines.generic.list');
        }
    }
}
