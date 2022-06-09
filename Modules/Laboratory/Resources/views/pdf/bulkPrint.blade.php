@php
    $view = "laboratory::pdf.lab";
    if (\App\Utils\Options::get('lab_page_break') == '1')
        $view = "laboratory::pdf.lab-one-page";
@endphp

@foreach ($allData as $data)
    @include($view, $data)
@endforeach