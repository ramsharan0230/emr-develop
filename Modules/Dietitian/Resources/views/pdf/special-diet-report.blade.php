<!DOCTYPE html>
<html>
<head>
    <title>Special Diet REPORT</title>
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

        .content-body td, .content-body th {
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

    <ul>
        <li>Special Diet Report</li>
        <li>{{$from}} To {{$to}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">Type Of Diet</th>
            <th class="tittle-th">Ward/Bed No.</th>
            <th class="tittle-th">Total</th>
            
        </tr>
        </thead>
        <tbody>
            @foreach($diet_type as $k=>$dt)
                @php
                    $sn = $k+1;
                @endphp
                @if(isset($from) and isset($to) and $from !='' and $to !='')
                    @php
                        $result= \App\ExtraDosing::select('fldencounterval')->where('fldcategory',$dt->fldfoodtype)->distinct('fldencounterval')->whereBetween('fldtime', [$from, $to])->get();
                    @endphp
                @else
                    @php
                        $result= \App\ExtraDosing::select('fldencounterval')->where('fldcategory',$dt->fldfoodtype)->distinct('fldencounterval')->get();
                    @endphp
                @endif
                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$dt->fldfoodtype}}</td>
                    <td>
                        <ul>
                        @foreach($result as $rd)
                            @php
                                $encdata = \App\Encounter::select('fldadmitlocat', 'fldcurrlocat')->where('fldencounterval',$rd->fldencounterval)->first();
                            @endphp
                            <li>{{$encdata->fldadmitlocat}} / {{$encdata->fldcurrlocat}}</li>
                        @endforeach
                        <ul>
                    </td>
                    <td>{{count($result)}}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('dietitian-report'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
