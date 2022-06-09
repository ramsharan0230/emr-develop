<?php

namespace Modules\AdminUser\Http\Controllers;

use App\AccessComp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class AccessComputerController extends Controller
{
    public function compAccess()
    {
        $data['compAccess'] = AccessComp::all();
        return view('adminuser::access.access-list', $data);
    }

    public function compAccessAdd()
    {
        return view('adminuser::access.access-add');
    }

    public function compAccessStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:access_comp|max:255',
            'description' => 'required',
            'status' => 'required',
        ]);

        try {
            $compData['name'] = $request->name;
            $compData['description'] = $request->description;
            $compData['status'] = $request->status;
            $compData['map_comp'] = $request->map_comp;

            if($request->map_comp!= "" && AccessComp::where('map_comp', $request->map_comp)->exists()){
                Session::flash('error_message', "Mac Comp already exists.");
                return redirect()->route('admin.user.comp.access');
            }

            AccessComp::create($compData);

            Session::flash('success_message', "Record created successfully.");
            return redirect()->route('admin.user.comp.access');
        } catch (\GearmanException $e) {
            Session::flash('error_message', "Something went wrong.");
            return redirect()->route('admin.user.comp.access');
        }
    }

    public function compAccessEdit($id)
    {
        $data['compAccess'] = AccessComp::find($id);
        return view('adminuser::access.access-edit', $data);
    }

    public function compAccessUpdate(Request $request)
    {
        $request->validate([
//            'name' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
        ]);

        /*NAME IS COMMENTED BECAUSE IT WILL BE USED AS FOREIGN KEY*/
        try {
//            $compData['name'] = $request->name;
            $compData['description'] = $request->description;
            $compData['status'] = $request->status;
            $compData['map_comp'] = $request->map_comp;
            if($request->map_comp!= "" && AccessComp::where('id', '!=', $request->_id)->where('map_comp', $request->map_comp)->exists()){
                Session::flash('error_message', "Mac Comp already exists.");
                return redirect()->route('admin.user.comp.access');
            }

            AccessComp::where('id', $request->_id)->update($compData);

            Session::flash('success_message', "Record updated successfully.");
            return redirect()->route('admin.user.comp.access');
        } catch (\GearmanException $e) {
            Session::flash('error_message', "Something went wrong.");
            return redirect()->route('admin.user.comp.access');
        }
    }
}
