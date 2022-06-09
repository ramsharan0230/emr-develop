<?php

namespace Modules\Options\Http\Controllers;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class OptionsController extends Controller
{
    public function saveSelectionsOptions()
    {
        Helpers::contentsForOPDPDF();
    }
}
