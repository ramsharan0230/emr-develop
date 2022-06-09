<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountGroup;
use App\AccountServiceCostMap;
use App\Entry;
use App\ServiceCost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GroupMapController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function subgroup()
    {
        $data['groups'] = AccountGroup::where('ParentId', 0)->with('children')->get();
        $data['serviceCost'] = ServiceCost::select('flditemtype')->distinct('flditemtype')->get();
        return view('coreaccount::account-group-map.subgroup', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function listSubGroup(Request $request)
    {
        $data['parentId'] = $request->groupId;
        $data['groups'] = AccountGroup::where('ParentId', $data['parentId'])->get();

        if (is_countable($data['groups']) && count($data['groups'])) {
            return view('coreaccount::account-group-map.sub-group-dynamic', $data)->render();
        }

        return "<small>No Data</small>";

    }

    public function getItemType(Request $request)
    {
        $type = $request->mapType;

        $dataFinal = $this->renderView($type);
        if ($type != 'service') {
            $addedData = $this->renderViewAdded($type);
        } else {
            $addedData = [];
        }

        return response()->json([
            'html' => $dataFinal,
            'existing' => $addedData,
            'status' => TRUE,
        ]);
    }

    private function renderView($type)
    {
        if ($type === 'medicine') {
            $data['data'] = Entry::select('fldstockid')
                ->distinct('fldstockid')
                ->where('fldcategory', 'Medicines')
                ->doesnthave('accountServiceMap')
                ->get();
            $data['type'] = 'medicine';

            $dataFinal = view('coreaccount::account-group-map.dynamic-lists', $data)->render();
        } elseif ($type === 'surgical') {
            $data['data'] = Entry::select('fldstockid')
                ->distinct('fldstockid')
                ->where('fldcategory', 'Surgicals')
                ->doesnthave('accountServiceMap')
                ->get();
            $data['type'] = 'Surgicals';
            $dataFinal = view('coreaccount::account-group-map.dynamic-lists', $data)->render();
        } elseif ($type === 'extra') {
            $data['data'] = Entry::select('fldstockid')
                ->distinct('fldstockid')
                ->where('fldcategory', 'Extra Items')
                ->doesnthave('accountServiceMap')
                ->get();
            $data['type'] = 'Extra';
            $dataFinal = view('coreaccount::account-group-map.dynamic-lists', $data)->render();
        } else {
            $data['data'] = ServiceCost::select('flditemtype')
                ->distinct('flditemtype')
//                ->doesnthave('accountServiceMap')
                ->get();
            $data['type'] = 'service';
            $dataFinal = view('coreaccount::account-group-map.dynamic-lists', $data)->render();
        }
        return $dataFinal;
    }

    public function renderViewAdded($type, $dataType = NULL)
    {
        if ($type === 'medicine') {
            $data['data'] = AccountServiceCostMap::where('type', 'like', 'Medicines')
                ->where('item_type', 'like', 'Medicines')
                ->get();

            $dataFinal = view('coreaccount::account-group-map.existing-list', $data)->render();
        } elseif ($type === 'surgical') {
            $data['data'] = AccountServiceCostMap::where('type', 'like', 'Surgicals')
                ->where('item_type', 'like', 'Surgicals')
                ->get();
            $dataFinal = view('coreaccount::account-group-map.existing-list', $data)->render();
        } elseif ($type === 'extra') {
            $data['data'] = AccountServiceCostMap::where('type', 'like', 'Extra Items')
                ->where('item_type', 'like', 'Extra Items')
                ->get();
            $dataFinal = view('coreaccount::account-group-map.existing-list', $data)->render();
        } else {
            $data['data'] = AccountServiceCostMap::where('type', 'like', 'Service')
                ->where('item_type', 'like', $dataType)
                ->get();
            $dataFinal = view('coreaccount::account-group-map.existing-list', $data)->render();
        }
        return $dataFinal;
    }

    public function getItemService(Request $request)
    {
        $type = $request->testItem;

        $data['data'] = ServiceCost::select('flditemname')
            ->where('flditemtype', 'like', $type)
            ->distinct('flditemname')
            ->doesnthave('accountServiceMap')
            ->get();
        $data['type'] = 'service';
        $dataFinal = view('coreaccount::account-group-map.service-map-list', $data)->render();
        $addedData = $this->renderViewAdded('service', $type);
        return response()->json([
            'html' => $dataFinal,
            'existing' => $addedData,
            'status' => TRUE,
        ]);
    }

    public function addMapData(Request $request)
    {
        $request->validate([
            'itemMap' => 'required',
            'itemMap.*' => 'required',
        ]);

        $dataInsert['sub_group_id'] = $request->get('account_group_id');

        if ($request->get('service-medicine-select') === 'service') {
            $dataInsert['type'] = "Service";
            $dataInsert['item_type'] = $request->get('service-medicine');
        } elseif ($request->get('service-medicine-select') === 'medicine') {
            $dataInsert['type'] = "Medicines";
            $dataInsert['item_type'] = "Medicines";
        } elseif ($request->get('service-medicine-select') === 'surgical') {
            $dataInsert['type'] = "Surgicals";
            $dataInsert['item_type'] = "Surgicals";
        } elseif ($request->get('service-medicine-select') === 'extra') {
            $dataInsert['type'] = "Extra Items";
            $dataInsert['item_type'] = "Extra Items";
        }

        foreach ($request->get('itemMap') as $iValue) {
            $dataInsert['flditemname'] = $iValue;
            AccountServiceCostMap::insert($dataInsert);
        }

        $dataFinal = $this->renderView($request->get('service-medicine-select'));
        $addedData = $this->renderViewAdded($request->get('service-medicine-select'), $request->get('service-medicine'));
        return response()->json([
            'html' => $dataFinal,
            'existing' => $addedData,
            'status' => TRUE,
        ]);
    }
}
