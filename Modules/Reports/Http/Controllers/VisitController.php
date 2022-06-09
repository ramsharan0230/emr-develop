<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class VisitController extends Controller
{
    public function index()
    {
        $all_data = [];


        $departments = \DB::table('tbldepartmentbed')->select('flddept')->distinct()->get();
        return view('reports::visit.index', compact('departments', 'all_data'));
    }
}
