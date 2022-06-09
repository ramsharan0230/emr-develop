<?php

namespace Modules\Setting\Http\Controllers;

use App\PermissionModule;
use App\SidebarMenu;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;


use Illuminate\Support\Facades\Validator;
use Session;

class SidebarSettingController extends Controller
{
    public function index()
    {
        $menus = [];

       // $mainmenu = SidebarMenu::where('status', 0)->distinct()->orderBy('order_by', 'ASC')->pluck('mainmenu');
        $mainmenu = SidebarMenu::where('status', 0)->distinct()->orderBy('order_by', 'ASC')->get();
        // if (!empty($mainmenu)) {
        //     foreach ($mainmenu as $k => $menu) {
        //         $menus[$k]['mainmenu'] = $menu;
        //         $menus[$k]['submenu'] =  SidebarMenu::where('mainmenu', $menu)->where('status', 0)->orderBy('order_by', 'DESC')->get();
        //     }
        // }

        $data['sidebars'] = $mainmenu;
        // dd($data['sidebars']);
        $data['permission_modules'] = PermissionModule::select('module')->distinct()->get();


        return view('setting::sidebar.sidebar-list', $data);
    }
    public function add()
    {
        $data['mainmenus'] = SidebarMenu::select('mainmenu')->where('status', 0)->distinct()->get();
        $data['permission_modules'] = PermissionModule::select('module')->distinct()->get();
        return view('setting::sidebar.sidebar-menu-add', $data);
    }

    public function store(Request $request)
    {

        try {
            $rules = array(

                'status' => 'required',
                'submenu' => 'required',
                'routelink' => 'required'
            );

            $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     return redirect()->route('sidebar.menu.add')->withErrors($validator)->withInput();
            // }

            if (!empty($request->submenu)) {
                $data = [
                    'mainmenu' => $request->mainmenu,
                    'status' => $request->status,
                    'submenu' => $request->submenu,
                    'route' => $request->routelink,
                    'order_by' => $request->order_by,
                    'icon' => $request->icon

                ];
            } else {
                $data = [
                    'mainmenu' => $request->mainmenu,
                    'status' => $request->status,
                    'submenu' => $request->mainmenu,
                    'route' => $request->routelink,
                    'order_by' => $request->order_by,
                    'icon' => $request->icon

                ];
            }



            $id = SidebarMenu::insertGetId($data);

            $module = [
                'module' => $request->permission_module,
                'name' => $request->submenu,
                'order_by' => 1,
            ];

            $permissionModule = \App\PermissionModule::create($module);
            $permissionRefrenceData = ['delete', 'view', 'update', 'add'];
            foreach ($permissionRefrenceData as $permission)
            {
                $reference = [
                    'permission_modules_id' => $permissionModule->id,
                    'code' => str_slug($permissionModule->name ?? null, '-').'-'.$permission,
                    'short_desc' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module,
                    'description' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module
                ];
                \App\PermissionReference::create($reference);
            }



            return response()->json([
                'success' => [
                    'url' => route('sidebar.menu'),
                    'message' => 'Menu added successfully'
                ]
            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }
        //return redirect()->route('sidebar.menu');
    }


    public function updateOrder(Request $request)
    {

        try {
            if ($request->positions) {
                foreach ($request->positions as $sortdata) {
                    SidebarMenu::where('mainmenu', $sortdata[2])
                        ->update(['order_by' => $sortdata[1]]);
                }
            }
            return response()->json(['message' => 'Setting Saved', 'status' => 'Done']);
        } catch (\GearmanException $e) {
            return response()->json(['message' => 'Something went wrong', 'status' => 'Error']);
        }
    }



    public function edit($id)
    {
        $data['sidebarmenu_id'] = $id;
        $data['mainmenus'] = SidebarMenu::select('mainmenu')->where('status', 0)->distinct()->get();
        $data['permission_modules'] = PermissionModule::select('module')->distinct()->get();

        $data['sidebarmenu'] = SidebarMenu::where('id', $id)->first();

        return view('setting::sidebar.sidebar-menu-edit', $data);
    }

    public function update(Request $request, $id)
    {
         try {
        $oldmenu = SidebarMenu::where('id', $id)->first();
        $rules = array(

            'status' => 'required',
            'submenu' => 'required',
            'routelink' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return redirect()->route('sidebar.menu.add')->withErrors($validator)->withInput();
        // }

        if (!empty($request->submenu)) {
            $data = [
                'mainmenu' => $request->mainmenu,
                'status' => $request->status,
                'submenu' => $request->submenu,
                'route' => $request->routelink,
                'order_by' => $request->order_by,
                'icon' => $request->icon

            ];
        } else {
            $data = [
                'mainmenu' => $request->mainmenu,
                'status' => $request->status,
                'submenu' => $request->mainmenu,
                'route' => $request->routelink,
                'order_by' => $request->order_by,
                'icon' => $request->icon

            ];
        }


        SidebarMenu::where('id', $id)->update($data);

        $module = [
            'module' => $request->permission_module,
            'name' => $request->submenu,
            'order_by' => 1,
        ];
        $permissionModule = \App\PermissionModule::where('name', $oldmenu->name)->first();
        \App\PermissionModule::where('name', $oldmenu->name)->update($module);


        $reference = [
            'permission_modules_id' => $permissionModule->id,
            'code' => str_slug($permissionModule->name ?? null, '-'),
            'short_desc' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module,
            'description' => ucfirst($permissionModule->name ?? null) . ' ' . $permissionModule->module
        ];
        \App\PermissionReference::where('permission_modules_id', $permissionModule->id)->update($reference);


        return redirect()->route('sidebar.menu');
        } catch (\GearmanException $e) {
            dd($e);
        }
    }


    public function generateDepartmentData()
    {
        $departments = SidebarMenu::get();
        $html = '';
        if ($departments) {
            foreach ($departments as $key => $department) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$department->name</td>";
                if (isset($department->branchData)) {
                    $html .= "<td>" . $department->branchData->name . "</td>";
                } else {
                    $html .= "<td></td>";
                }
                if ($department->parentDepartment != null) {
                    $html .= "<td>" . $department->parentDepartment->name . "</td>";
                } else {
                    $html .= "<td></td>";
                }
                $html .= "<td>$department->status</td>";
                $html .= "<td>
                            <a href='javascript:;' onclick='department.editDepartment(" . $department->id . ")'><i class='fas fa-pen-square text-primary'></i></a>
                            <a href='javascript:;' onclick='department.deleteDepartment(" . $department->id . ")'><i class='fas fa-trash text-danger'></i></a>
                         </td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
