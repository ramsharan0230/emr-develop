<?php

namespace Modules\AccountList\Http\Controllers;

use App\BillingSet;
use App\ServiceCost;
use App\ServiceGroup;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class CashierPacksController extends Controller
{

    public function __construct(ServiceCost $serviceCost)
    {
        $this->serviceCost = $serviceCost ;
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $groups = ServiceGroup::select('fldgroup')->distinct()->get();
        $billingmode = BillingSet::get();

        $data['groups'] = $groups;
        $data['billingmode'] = $billingmode;
        return view('accountlist::cashier-package', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listItems(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->type == 'Test')
                $comparecolumn = 'Diagnostic Tests';

            if ($request->type == 'Service')
                $comparecolumn = 'General Services';

            if ($request->type == 'Procedures')
                $comparecolumn = 'Procedures';

            if ($request->type == 'Equipment')
                $comparecolumn = 'Equipment';

            if ($request->type == 'Radio')
                $comparecolumn = 'Radio Diagnostics';

            if ($request->type == 'Others')
                $comparecolumn = 'Other Items';

            $result = ServiceCost::select('flditemname')->where('flditemtype', $comparecolumn)->where('fldgroup', 'LIKE', $request->billingmode)->where('fldstatus', 'Active')->get();
            $html = '';
            if (isset($result) and count($result) > 0) {
                foreach ($result as $key => $value) {
                    $html .= '<option value="' . $value->flditemname . '">' . $value->flditemname . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function listPackages(Request $request)
    {
        try {
            $group = $request->group;
            $result = ServiceGroup::select('fldid', 'flditemtype', 'flditemname', 'flditemqty', 'discount_per')->where('fldgroup', $group)->get();

            echo $this->html($result);
            exit;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function deletePackage(Request $request)
    {
        try {

            $group = $request->group;
            $fldid = $request->fldid;
            ServiceGroup::where('fldid', $fldid)->delete();

            $groupresult = ServiceGroup::select('fldgroup')->distinct()->get();
            $grouphtml = '';
            if (isset($groupresult) and count($groupresult) > 0) {
                foreach ($groupresult as $gresult) {
                    $grouphtml .= '<option value="' . $gresult->fldgroup . '">' . $gresult->fldgroup . '</option>';
                }
            }

            $result = ServiceGroup::select('fldid', 'flditemtype', 'flditemname', 'flditemqty', 'discount_per')->where('fldgroup', $group)->get();

            $data['ghtml'] = $grouphtml;
            $data['html'] = $this->html($result);
            return $data;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function addPackage(Request $request)
    {
        // dd($request->all());
        try {
            $groupName = explode('(', $request->group);
            $data['fldgroup'] = $groupName[0] . '(' . $request->billingmode . ')';
            $data['billingmode'] = $request->billingmode;
            $data['flditemtype'] = $request->itemtype;
            $data['flditemname'] = $request->itemname;
            $data['flditemqty'] = $request->qty;
            $data['discount_per'] = $request->discount;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $data['price_editable'] = ( $request->editable == "true") ? true : false ;
            // dd($data);
            ServiceGroup::create($data);

            $groupresult = ServiceGroup::select('fldgroup')->distinct()->get();
            $grouphtml = '';
            if (isset($groupresult) and count($groupresult) > 0) {
                foreach ($groupresult as $gresult) {
                    $grouphtml .= '<option value="' . $gresult->fldgroup . '">' . $gresult->fldgroup . '</option>';
                }
            }

            $result = ServiceGroup::select('fldid', 'flditemtype', 'flditemname', 'flditemqty', 'discount_per', 'price_editable')->where('fldgroup', 'LIKE', $data['fldgroup'])->with('serviceCost')->get();


            $data['ghtml'] = $grouphtml;
            $data['html'] = $this->html($result);
            return $data;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function exportGroup(Request $request)
    {
        // dd($request);
        $group = $request->group;
        $result = ServiceGroup::where('fldgroup', $group)->get();
        $data['result'] = $result;
        $data['group'] = $group;
        return view('accountlist::pdf.cashier-group-pdf', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function exportAll(Request $request)
    {
        $result = ServiceGroup::select('fldgroup')->distinct()->get();
        $data['result'] = $result;

        return view('accountlist::pdf.cashier-all-pdf', $data);
    }

    public function html($result)
    {
        $data['result'] = $result;
        return view('accountlist::item-list', $data)->render();
    }

    public function editPackage(Request $request)
    {
        try {
            $data['flditemqty'] = $request->qty;
            $data['discount_per'] = $request->discount;
            $serviceGroup = ServiceGroup::where('fldid', $request->fldid)->first();
            ServiceGroup::where('fldid', $request->fldid)->update($data);

            $result = ServiceGroup::select('fldid', 'flditemtype', 'flditemname', 'flditemqty', 'discount_per')->where('fldgroup', 'LIKE', $serviceGroup->fldgroup)->get();
            $data['html'] = $this->html($result);
            return $data;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function getServiceCost(Request $request)
    {
        // dd($request->all());
        $serviceCost = $this->serviceCost->where([
            'flditemname' => $request->flditemname,
        ])->first();
        return response($serviceCost, 200);
    }
}
