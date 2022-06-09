<?php

namespace Modules\Template\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TemplateController extends Controller
{

    public function index()
    {
        return view('template::index');
    }


    public function update(Request $request)
    {

        try {

            $birth_template = $request->birth_template;
            Options::update('birth_certificate_template', $birth_template);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    //function for creating report
    public function createReport()
    {
        try {

            $name = 'Test name';
            $date = 'Test Date';
            $time = 'Test time';
            $place = 'Test place';
            $father = 'Test father';
            $mother = 'Test mother';

            $text = strtr(Options::get('birth_certificate_template'), [
                '{$name}' => $name,
                '{$date}' => $date,
                '{$time}' => $time,
                '{$place}' => $place,
                '{$father}' => $father,
                '{$mother}' => $mother,
            ]);
            dd($text);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

}
