<?php

namespace Modules\Inventory\Http\Controllers;

use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class InventoryController extends Controller
{

    public function purchaseEntry()
    {
        return view('inventory::purchase-entry');
    }


    public function stockTransfer()
    {
        return view('inventory::stock-transfer');
    }


    public function stockReturn()
    {
        //
        if (Permission::checkPermissionFrontendAdmin('stock-return')) {
            return view('inventory::stock-return');
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }


    public function stockAdjustment()
    {
        //
       // if (Permission::checkPermissionFrontendAdmin('')) {
            return view('inventory::stock-adjustment');
        // } else {
        //     Session::flash('display_popup_error_success', true);
        //     Session::flash('error_message', 'You are not authorized for this action.');
        //     return redirect()->route('admin.dashboard');
        // }
    }
}
