<!DOCTYPE html>
<html>
<head>
    <title>Bed Occupancy</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
        .content-body {
            border-collapse: collapse;
        }
        .content-body td, .content-body th{
            border: 1px solid #ddd;
        }
        .content-body {
            font-size: 12px;
        }
    </style>

</head>
<body>

@include('pdf-header-footer.header-footer')
<main>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td>
            <p><strong>BED OCCUPANCY</strong></p>
        </td>
        <td style="width: 185px;">

            <p><strong>DOReg:</strong> {{ \Carbon\Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s') }}</p>

        </td>
        <td style="width: 130px;"></td>
    </tr>
    </tbody>
</table>
<table style="width: 100%;" border="1px" rules="all" class="content-body">
    <tbody>
    <tr>
        <th style="width: 20px; text-align: center;">S.N</th>
        <th style="width: 96px; text-align: center;">BedNo</th>
        <th style="width:96px; text-align: center;">EncID</th>
        <th style="width: 96px; text-align: center;">Name</th>
        <th style="width:96px; text-align: center;">Age/Sex</th>
        <th style="width:96px; text-align: center;">Consult</th>
        <th style="width: 96px; text-align: center;">DOA</th>
    </tr>
    @if(isset($encounters) && count($encounters))

        @foreach($encounters as $k => $en)

            @if($en['door'] === 0)
                <tr>
                    <td colspan="7" align="center">{{$en['flddept']}}</td>
                </tr>
            @endif
            <tr>
                <td>{{$k+1}}</td>
                <td>
                    {{$en['fldbed']}}
                </td>
                <td>
                    {{$en['encounter_id']}}
                </td>
                <td>
                    {{$en['name']}}
                </td>
                <td>
                    {{$en['agesex']}}
                </td>

                <td>
                    {{$en['consult']}}
                </td>

                <td>
                    {{$en['birthday']}}
                </td>

            </tr>
        @endforeach
    @endif

    </tbody>
</table>
@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
