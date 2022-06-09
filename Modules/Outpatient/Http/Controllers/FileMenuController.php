<?php

namespace Modules\Outpatient\Http\Controllers;

use App\Department;
use Illuminate\Routing\Controller;

class FileMenuController extends Controller
{
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayWaitingForm()
    {
        $data['flddept'] = Department::select('flddept')->where('fldcateg', 'like', 'Consultation')->get();

        $html = view('menu::menu-dynamic-views.waiting-form', $data)->render();
        return $html;
    }

    public function displaySearchForm()
    {
        $html = view('menu::menu-dynamic-views.search-form')->render();
        return $html;
    }
}
