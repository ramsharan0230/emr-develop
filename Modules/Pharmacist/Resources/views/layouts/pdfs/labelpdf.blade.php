<!DOCTYPE html>
<html>
<head>
    <title>MEDICINE LABELLING REPORT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        @font-face {
            font-family: "Preeti";
            font-style: normal;
            font-weight: normal;
            src: url({{asset("assets/fonts/PREETI.TTF")}}) format('truetype');
        }
        @font-face {
            font-family: "Preeti Bold";
            font-style: normal;
            font-weight: bold;
            src: url({{asset("assets/fonts/PREETI.TTF")}}) format('truetype');
        }

        .unicode{
            font-family: Preeti, "Preeti Bold";
            font-size: 18px;
            line-height: 1.8;
        }

        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p> MEDICINE LABELLING </p>
            @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
            <p>{{ $currentDatetime }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>

        <th class="tittle-th">SNo</th>
        <th class="tittle-th">Code</th>
        <th class="tittle-th">English</th>
        <th class="tittle-th">Local</th>
    </tr>
    </thead>
    <tbody>
    @forelse($labels as $label)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $label->fldengcode }}</td>
            <td>{{ $label->fldengdire }}</td>
            @php

                $unicode_char_array = preg_split('//u', ("". $label->fldlocaldire), -1);
                $preeti = '';
                if(count($unicode_char_array) > 0) {
                    foreach($unicode_char_array as $k=>$v) {
                        $preeti .= \App\Utils\Pharmacisthelpers::convertunicodecharatertopreeti($v);
                    }
                }
               $preeti =  mb_convert_encoding($preeti, 'UTF-8', 'UTF-8');
            @endphp
            <td class="unicode">{{ $preeti }}</td>
        </tr>
    @empty
    @endforelse
    </tbody>
</table>
<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>admin, {{ Carbon\Carbon::now()->format('m/d/Y') }} </p>
        </td>
    </tr>
    </tbody>
</table>

@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>

