<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\ExtraBrand;
use App\Locallabel;
use App\MedicineBrand;
use App\SurgBrand;
use App\Utils\Helpers;
use App\Utils\Permission;
use App\Utils\Pharmacisthelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;

class ActivityController extends Controller
{

    public function labelling()
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'labeling', 'labeling-create'  ])  ) ?
            abort(403, 'Unauthorized action.') : true ;
        return view('pharmacist::labelling.labelling');
    }

    public function getVolunitfromBrand(Request $request)
    {
        $selctedfldengcode = $request->selectedfldendcode;
        $response = array();
        try {
            $fldvolunits = MedicineBrand::select('fldvolunit')->groupBy('fldvolunit')->get();

            $html = '<option></option>';

            foreach ($fldvolunits as $fldvolunit) {
                $selected = ($selctedfldengcode == $fldvolunit->fldvolunit) ? 'selected' : '';
                if ($fldvolunit->fldvolunit != '') {
                    $html .= '<option value="' . $fldvolunit->fldvolunit . '" ' . $selected . '>' . $fldvolunit->fldvolunit . '</option>';
                }
            }

            $response['message'] = 'success';
            $response['html'] = $html;
            $response['labelListing'] = $this->getLabelListings($request);

        } catch (\Exception $e) {
            //            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function getByLabelType(Request $request)
    {
        $response = array();
        try {
            $response['message'] = 'success';
            $response['labelListing'] = $this->getLabelListings($request);
        } catch (\Exception $e) {
            //            $response['error'] = $e->getMessage();
            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function addLocalLabels(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'fldengcode' => 'required',
            'fldlocaldire' => 'required'
        ], [
            'label.required' => 'Label field is required',
            'fldengcode.required' => 'Word field is required',
            'fldlocaldire.required' => 'Local field is required'
        ]);

        try {
            $data = [
                'fldlabeltype' => $request->label,
                'fldengcode' => $request->fldengcode,
                'fldengdire' => $request->fldlocaldire,
                'fldlocaldire' => $request->fldlocaldire,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            Locallabel::insert($data);
            Helpers::logStack(["Local label created", "Event"], ['current_data' => $data]);
            Session::flash('success_message', 'local label added sucessfully');
            return redirect()->route('pharmacist.labelling.index');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in local label create', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('pharmacist.labelling.index');
        }
    }

    public function editLocalLabels($fldid)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'labeling', 'labeling-create'  ])  ) ?
        abort(403, 'Unauthorized action.') : true ;
        $data = [];
        $locallabel = Locallabel::find($fldid);
        if (isset($locallabel)) {
            return response()->json([
                'success' => [
                    'message' => 'true',
                    'locallabel' => $locallabel
                ]
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => 'An Error has occured!'
                ]
            ]);
        }

        // $data['locallabel'] = $locallabel;

        // return view('pharmacist::labelling.labellingedit', $data);
    }

    public function updateLocalLabels(Request $request, $fldid)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'labeling', 'labeling-update', 'labeling-update'  ])  ) ?
            abort(403, 'Unauthorized action.') : true ;
        $request->validate([
            'labeltype' => 'required',
            'fldengcode' => 'required',
            'fldlocaldire' => 'required'
        ], [
            'labeltype.required' => 'Label field is required',
            'fldengcode.required' => 'Word field is required',
            'fldlocaldire.required' => 'local field is required'
        ]);

        try {
            $data = [
                'fldlabeltype' => $request->labeltype,
                'fldengcode' => $request->fldengcode,
                'fldengdire' => $request->fldengdire,
                'fldlocaldire' => $request->fldlocaldire
            ];
            $localLabel = Locallabel::where('fldid', $fldid)->first();
            Locallabel::where('fldid', $fldid)->update($data, ['timestamps' => false]);
            Helpers::logStack(["Local label updated", "Event"], ['current_data' => $data, 'previous_data' => $localLabel]);
            // Session::flash('success_message', 'local label edited sucessfully');
            return response()->json([
                'success' => [
                    'message' => 'Local label edited sucessfully',
                    'labelListing' => $this->getLabelListings($request)
                ]
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in local label update', "Error"]);
            return response()->json([
                'success' => [
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }

    public function getLabelListings($request)
    {
        $locallabels = \App\Utils\Pharmacisthelpers::getLocalLabel()->where('fldlabeltype', $request->labeltype);
        $html = "";
        foreach ($locallabels as $locallabel) {
            $html .= "<tr>";
            $html .= "<td>" . $locallabel->fldengcode . "</td>";
            $html .= "<td>" . $locallabel->fldengdire . "</td>";
            $html .= "<td>" . $locallabel->fldlocaldire . "</td>";
            $route = route('pharmacist.labelling.deletelocallabels', $locallabel->fldid);
            $html .= "<td class='text-center'>
                        <a type='button' href='#' data-fldid='" . $locallabel->fldid . "' style='margin-left: 15px;' title='edit " . $locallabel->fldengcode . "' class='btn btn-warning btn-sm-in editLabel'>
                            <i class='ri-edit-fill'></i>
                        </a>
                        <button title='delete ".$locallabel->fldengcode."'  data-href='".$route."' class='btn btn-danger btn-sm-in deletelabel'><i class='fa fa-trash'></i></button>
                    </td>";
            $html .= "</tr>";
        }
        return $html;
    }

    public function deleteLocalLabels($fldid)
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'labeling', 'labeling-delete'  ])  ) ?
            abort(403, 'Unauthorized action.') : true ;
        try {
            $locallabel = Locallabel::find($fldid);
            if ($locallabel) {
                $locallabel->delete();
                Helpers::logStack(["Local label deleted", "Event"], ['previous_data' => $locallabel]);
                Session::flash('success_message', $locallabel->fldengcode . ' label deleted sucessfully');
                return redirect()->route('pharmacist.labelling.index');
            } else {
                Helpers::logStack(['Local label not found in local label delete', "Error"]);
                Session::flash('error_message', "Data not found.");
                return redirect()->route('pharmacist.labelling.index');
            }
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in local label delete', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('pharmacist.labelling.index');
        }

    }

    public function activation()
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'pharmacy-item-activation', 'pharmacy-item-activation-view'  ])  ) ?
            abort(403, config('unauthorize-message.pharmacy_master.pharmacy-item-activation.view')) : true ;
        //        $medbrands = MedicineBrand::with('Drug')->get();
        //        foreach($medbrands as $k=>$medbrand) {
        //            $type = ($medbrand->Drug) ? $medbrand->Drug->fldroute : '';
        //
        //            if($medbrand->flddrug == 'Albendazole- 40 mg/mL') {
        //                dd($medbrand);
        //            }
        //
        //        }
        return view('pharmacist::activation.activation');
    }

    public function getMedbrands(Request $request)
    {
        $response = array();
        try {
            if (isset($request->keyword)) {
                $keyword = $request->keyword;
                $medbrands = MedicineBrand::with('Drug')->where('fldbrandid', 'like', '%' . $keyword . '%')->orWhere('fldbrand', 'like', '%' . $keyword . '%')->paginate(20);
            } else {
                $medbrands = MedicineBrand::with('Drug')->paginate(20);
            }


            $html = '';
            foreach ($medbrands as $k => $medbrand) {
                //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                $type = ($medbrand->Drug) ? $medbrand->Drug->fldroute : '';
                $actionclass = (strtolower($medbrand->fldactive) == 'active') ? 'success' : 'warning';
                $action = (strtolower($medbrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                $html .= '<tr>
                            <td>' . ++$k . '</td>
                            <td>' . $type . '</td>
                            <td>' . $medbrand->fldbrandid . '</td>
                            <td>' . $medbrand->fldbrand . '</td>
                            <td><a href="javascript:void(0)" data-id="' . $medbrand->fldbrandid . '" data-status="' . $medbrand->fldactive . '" data-brand="medicines" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $medbrand->fldactive . '</span></a></td>
                        </tr>';
            }
            $html .= '<tr><td colspan="5">' . $medbrands->appends(request()->all())->links() . '</td></tr>';
            $response['message'] = "success";
            $response['html'] = $html;


        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            //            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function getSurgbrands(Request $request)
    {
        $response = array();
        try {

            if (isset($request->keyword)) {
                $keyword = $request->keyword;
                $surgicalbrands = SurgBrand::with('Surgical')->where('fldbrandid', 'like', '%' . $keyword . '%')->orWhere('fldbrand', 'like', '%' . $keyword . '%')->paginate(20);
            } else {
                $surgicalbrands = SurgBrand::with('Surgical')->paginate(20);
            }

            $html = '';

            foreach ($surgicalbrands as $k => $surgicalbrand) {
                //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                $type = ($surgicalbrand->Surgical) ? $surgicalbrand->Surgical->fldsurgcateg : '';
                $actionclass = (strtolower($surgicalbrand->fldactive) == 'active') ? 'success' : 'warning';
                $action = (strtolower($surgicalbrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                $html .= '<tr><td>' . ++$k . '</td><td>' . $type . '</td><td>' . $surgicalbrand->fldbrandid . '</td><td>' . $surgicalbrand->fldbrand . '</td><td><a href="javascript:void(0)" data-id="' . $surgicalbrand->fldbrandid . '" data-status="' . $surgicalbrand->fldactive . '" data-brand="surgical" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $surgicalbrand->fldactive . '</span></a></td></tr>';
            }
            $html .= '<tr><td colspan="5">' . $surgicalbrands->appends(request()->all())->links() . '</td></tr>';
            $response['message'] = "success";
            $response['html'] = $html;


        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            //            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function getExtraBrands(Request $request)
    {
        $response = array();
        try {
            if (isset($request->keyword)) {
                $keyword = $request->keyword;
                $extrabrands = ExtraBrand::where('fldbrandid', 'like', '%' . $keyword . '%')->orWhere('fldbrand', 'like', '%' . $keyword . '%')->paginate(20);
            } else {
                $extrabrands = ExtraBrand::paginate(20);
            }

            $html = '';

            foreach ($extrabrands as $k => $extrabrand) {
                //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                $actionclass = (strtolower($extrabrand->fldactive) == 'active') ? 'success' : 'warning';
                $action = (strtolower($extrabrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                $html .= '<tr><td>' . ++$k . '</td><td>extra</td><td>' . $extrabrand->fldbrandid . '</td><td>' . $extrabrand->fldbrand . '</td><td><a href="javascript:void(0)" data-id="' . $extrabrand->fldbrandid . '" data-status="' . $extrabrand->fldactive . '" data-brand="extra" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $extrabrand->fldactive . '</span></a></td></tr>';
            }
            $html .= '<tr><td colspan="5">' . $extrabrands->appends(request()->all())->links() . '</td></tr>';
            $response['message'] = "success";
            $response['html'] = $html;


        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            //            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function enableDisableAll(Request $request)
    {
        $activationof = $request->activationof;
        $enabledisable = $request->enabledisable;
        $response = array();
        try {
            if ($activationof == 'medicines') {
                MedicineBrand::query()->update(['fldactive' => $enabledisable]);
                $medbrands = MedicineBrand::with('Drug')->paginate(20);

                $html = '';

                foreach ($medbrands as $k => $medbrand) {
                    //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                    $type = ($medbrand->Drug) ? $medbrand->Drug->fldroute : '';
                    $actionclass = (strtolower($medbrand->fldactive) == 'active') ? 'success' : 'warning';
                    $action = (strtolower($medbrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                    $html .= '<tr><td>' . ++$k . '</td><td>' . $type . '</td><td>' . $medbrand->fldbrandid . '</td><td>' . $medbrand->fldbrand . '</td><td><a href="javascript:void(0)" data-id="' . $medbrand->fldbrandid . '" data-status="' . $medbrand->fldactive . '" data-brand="medicines" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $medbrand->fldactive . '</span></a></td></tr>';
                }
                $html .='<tr><td colspan="5">'.$medbrands->appends(request()->all())->links().'</td></tr>';
                $response['message'] = "success";
                $response['html'] = $html;
            } elseif ($activationof == 'surgical') {
                SurgBrand::query()->update(['fldactive' => $enabledisable]);
                $surgicalbrands = SurgBrand::with('Surgical')->paginate(20);

                $html = '';

                foreach ($surgicalbrands as $k => $surgicalbrand) {
                    //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                    $type = ($surgicalbrand->Surgical) ? $surgicalbrand->Surgical->fldsurgcateg : '';
                    $actionclass = (strtolower($surgicalbrand->fldactive) == 'active') ? 'success' : 'warning';
                    $action = (strtolower($surgicalbrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                    $html .= '<tr><td>' . ++$k . '</td><td>' . $type . '</td><td>' . $surgicalbrand->fldbrandid . '</td><td>' . $surgicalbrand->fldbrand . '</td><td><a href="javascript:void(0)" data-id="' . $surgicalbrand->fldbrandid . '" data-status="' . $surgicalbrand->fldactive . '" data-brand="surgical" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $surgicalbrand->fldactive . '</span></a></td></tr>';
                }
                $html .='<tr><td colspan="5">'.$surgicalbrands->appends(request()->all())->links().'</td></tr>';
                $response['message'] = "success";
                $response['html'] = $html;
            } elseif ($activationof == 'extra') {

                ExtraBrand::query()->update(['fldactive' => $enabledisable]);
                $extrabrands = ExtraBrand::paginate(20);

                $html = '';

                foreach ($extrabrands as $k => $extrabrand) {
                    //                $type = Pharmacisthelpers::getFldroutefromDrug($medbrand->flddrug);
                    $actionclass = (strtolower($extrabrand->fldactive) == 'active') ? 'success' : 'warning';
                    $action = (strtolower($extrabrand->fldactive) == 'active') ? 'deactivate' : 'activate';
                    $html .= '<tr><td>' . ++$k . '</td><td>extra</td><td>' . $extrabrand->fldbrandid . '</td><td>' . $extrabrand->fldbrand . '</td><td><a href="javascript:void(0)" data-id="' . $extrabrand->fldbrandid . '" data-status="' . $extrabrand->fldactive . '" data-brand="extra" class="togglestatus" title="' . $action . '"><span class="badge badge-' . $actionclass . '">' . $extrabrand->fldactive . '</span></a></td></tr>';
                }
                $html .='<tr><td colspan="5">'.$extrabrands->appends(request()->all())->links().'</td></tr>';
                $response['message'] = "success";
                $response['html'] = $html;
            }


        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            //            $response['error'] = "something went wrong";
            $response['message'] = "error";

        }

        return json_encode($response);


    }

    public function toggleStatus(Request $request)
    {
        $brand = $request->brand;
        $id = $request->id;
        $status = $request->status;
        $response = array();
        try {

            if ($brand == 'medicines') {
                $medicine = MedicineBrand::where('fldbrandid', $id)->first();
            } elseif ($brand == 'surgical') {
                $medicine = SurgBrand::where('fldbrandid', $id)->first();
            } elseif ($brand == 'extra') {
                $medicine = ExtraBrand::where('fldbrandid', $id)->first();
            }

            if (strtolower($medicine->fldactive) == 'active') {
                $status = 'Inactive';
                $class = "badge badge-warning";
                $title = "activate";
            } elseif (strtolower($medicine->fldactive) == 'inactive') {
                $status = 'Active';
                $class = "badge badge-success";
                $title = "deactivate";
            }

            if ($brand == 'medicines') {
                MedicineBrand::where('fldbrandid', $id)->update(['fldactive' => $status], ['timestamps' => false]);
            } elseif ($brand == 'surgical') {
                SurgBrand::where('fldbrandid', $id)->update(['fldactive' => $status], ['timestamps' => false]);
            } elseif ($brand == 'extra') {
                ExtraBrand::where('fldbrandid', $id)->update(['fldactive' => $status], ['timestamps' => false]);
            }


            $response['message'] = "success";
            $response['status'] = $status;
            $response['class'] = $class;
            $response['title'] = $title;
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            //            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function exportAllLabellingToPdf()
    {
        $data = [];
        $labels = Pharmacisthelpers::getLocalLabel();
        $data['labels'] = $labels;

        return view('pharmacist::layouts.pdfs.labelpdf', $data);

        // $pdf = PDF::loadView('pharmacist::layouts.pdfs.labelpdf', $data);
        // $pdf->setpaper('a4');

        // return $pdf->stream('medicinelabelling.pdf');
    }


}
