<?php

namespace Modules\Setting\Http\Controllers;

use App\PermissionModule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PermissionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'permission_modules' => PermissionModule::select('module')->distinct()->get(),
        ];
        return view('setting::permission-setting',$data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'permission_module' => 'required',
            'name' => 'required',
        ]);
        try {
            $module =[
                'module' => $request->permission_module,
                'name' => $request->name,
                'order_by' => 1,
            ];
            $permissionModule =\App\PermissionModule::create($module);
            $reference = ['permission_modules_id' => $permissionModule->id,
                'code'=>str_slug($permissionModule->name ?? null,'-'),
                'short_desc' =>ucfirst($permissionModule->name ?? null).' '.$permissionModule->module,
                'description' => ucfirst($permissionModule->name ?? null).' '.$permissionModule->module ];

            \App\PermissionReference::create($reference);
            Session::flash('success_message', 'Added Successfully.');
            return redirect()->back();
        }catch (\Exception $exception){
            Session::flash('error_message', __('messages.error'));
            return redirect()->back();
        }


    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
