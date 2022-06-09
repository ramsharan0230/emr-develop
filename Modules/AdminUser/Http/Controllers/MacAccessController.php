<?php

namespace Modules\AdminUser\Http\Controllers;

use App\AccessComp;
use App\GroupMac;
use App\Http\Controllers\GetMacAddress;
use App\MacAccess;
use App\MacAccessByComp;
use App\RequestMacAccess;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class MacAccessController extends Controller
{
    public function compAccessListMac($comp)
    {
        //active user that are not in the group
        $group = AccessComp::where('name', $comp)->first();

        $macINgroup = GroupMac::select('requestid')->where('group_id', $group->id)->pluck('requestid');

        $macNOTgroup = GroupMac::select('requestid')->pluck('requestid');

        $data['mac'] = RequestMacAccess::where('status', 'active')
            ->whereNotIn('id', $macNOTgroup)
            ->get();


        $data['groupmac'] = GroupMac::whereIn('requestid', $macINgroup)->with('request')
            ->get();

        // $data['old_mac'] = MacAccess::where('fldaccess', 'active')
        //     ->whereNotIn('id', )
        //     ->get();

        $data['group_id'] = $comp;


        return view('adminuser::access.group-mac-list', $data);
    }

    public function macAccessAdd($comp, GetMacAddress $getMac)
    {
        $data['comp'] = $comp;
        $data['getmac'] = $getMac->GetMacAddr(PHP_OS);
        return view('adminuser::access.add-mac', $data);
    }

    public function macAccessStore(Request $request)
    {
        $request->validate([
            '_access_name' => 'required|max:255',
            'fldhostmac' => 'required',
            'status' => 'required',
        ]);

        try {
            $compData['fldcomp'] = $request->_access_name;
            $compData['fldhostmac'] = $request->fldhostmac;
            $compData['fldaccess'] = $request->status;
            $compData['fldhostname'] = $request->fldhostname;
            MacAccessByComp::create($compData);

            Session::flash('success_message', "Record created successfully.");
            return redirect()->route('admin.user.comp.access.list.mac', $request->_access_name);
        } catch (\GearmanException $e) {
            Session::flash('error_message', "Something went wrong.");
            return redirect()->route('admin.user.comp.access');
        }
    }

    public function macAccessEdit($id)
    {
        $data['MacAccess'] = MacAccessByComp::find($id);
        return view('adminuser::access.edit-mac', $data);
    }

    public function macAccessUpdate(Request $request)
    {
        $request->validate([
            '_access_name' => 'required|max:255',
            'fldhostmac' => 'required',
            'status' => 'required',
        ]);

        try {
            $compData['fldcomp'] = $request->_access_name;
            $compData['fldhostmac'] = $request->fldhostmac;
            $compData['fldaccess'] = $request->status;
            $compData['fldhostname'] = $request->fldhostname;
            MacAccessByComp::where('id', $request->_id)->update($compData);

            Session::flash('success_message', "Record updated successfully.");
            return redirect()->route('admin.user.comp.access.list.mac', $request->_access_name);
        } catch (\GearmanException $e) {
            Session::flash('error_message', "Something went wrong.");
            return redirect()->route('admin.user.comp.access');
        }
    }

    public function inactiveMacAddress()
    {
        $data['mac'] = RequestMacAccess::where('status', 'inactive')->get();
        return view('adminuser::access.inactive-mac-list', $data);
    }

    public function approveMacAddress(Request $request)
    {
        $request->validate([
            'useraccess' => 'required',
        ]);

        try {
            if (isset($request->useraccess)) {
                $compData['status'] = 'active';
                RequestMacAccess::whereIn('id', $request->useraccess)->update($compData);

                Session::flash('success_message', "Record updated successfully.");
                return redirect()->back();
            }
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back();
        } catch (\GearmanException $e) {
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back();
        }
    }

    public function activeMacAccess($group_id)
    {


        //active user that are not in the group
        $group = AccessComp::where('name', $group_id)->first();

        $macINgroup = GroupMac::select('requestid')
        ->where('group_id', $group->id)->pluck('requestid');
dd($macINgroup);

        $macNOTgroup = GroupMac::select('requestid')->pluck('requestid');

        $data['mac'] = RequestMacAccess::where('status', 'active')
            ->whereNotIn('id', $macNOTgroup)
            ->get();


        $data['groupmac'] = GroupMac::where('status', 'active')
            ->whereIn('requestid', $macINgroup)
            ->get();

        // $data['old_mac'] = MacAccess::where('fldaccess', 'active')
        //     ->whereNotIn('id', )
        //     ->get();

        $data['group_id'] = $group_id;


        return view('adminuser::access.group-mac-list', $data);
    }

    public function removeMacAddress(Request $request)
    {
        $group_id = $request->group_id;
        $group = AccessComp::where('name', $group_id)->first();

        if (isset($request->useraccess)) {
            foreach ($request->useraccess as $re) {

                GroupMac::where('requestid', $re->requestid)

                    ->where('group_id', $group->id)
                    ->delete();
            }
        }
        Session::flash('success_message', "Record updated successfully.");
        return redirect()->back();
    }

    public function addInGroup(Request $request)
    {
        try {
            $group_id = $request->group_id;
            $group = AccessComp::where('name', $group_id)->first();

            if (isset($request->requseraccess)) {
                foreach ($request->requseraccess as $re) {


                    $user_data = [
                        'group_id' => $group->id,
                        'requestid' =>$re

//                        'hostmac' => $re
                    ];
                    GroupMac::insert($user_data);
                }
            }
            Session::flash('success_message', "Record updated successfully.");
            return redirect()->back();
        } catch (\Exception $th) {
            throw $th;
        }
    }


}
