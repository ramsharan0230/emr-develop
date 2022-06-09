<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ServiceGroupExport implements FromView, WithDrawings, ShouldAutoSize, WithTitle
{
    protected $req, $dataService, $title;

    public function __construct($dataService, array $request, $title)
    {
        $this->req = $request;
        $this->dataService = $dataService;
        $this->title = $title;
    }

    public function view(): View
    {
        $data['eng_from_date'] = $this->req['eng_from_date'];
        $data['eng_to_date'] = $this->req['eng_to_date'];
        $data['from_date'] = $this->req['from_date'];
        $data['to_date'] = $this->req['to_date'];
        $data['title'] = $this->title;

        $data['serviceData'] = $this->dataService;
        return view('reports::service.service-group-report-excel', $data);
    }

    public function drawings()
    {
        if (Options::get('brand_image')) {
            if (file_exists(public_path('uploads/config/' . Options::get('brand_image')))) {
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan']) ? Options::get('siteconfig')['system_slogan'] : '');
                $drawing->setPath(public_path('uploads/config/' . Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }

    public function title(): string
    {
        $title = '';
        if ($this->title == "") {
            $title = 'Miscellaneous';
        } else {
            $title = ucwords($this->title);
        }
        return $title;
    }
}
