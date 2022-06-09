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
        td img{
            width: 11px;
            height:11px;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    <ul>
        <li>Diet Sheet Report</li>
        <li>{{$from}} To {{$to}}</li>
        <li>Ward: {{$ward}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">Unit</th>
            <th class="tittle-th">Patient No.</th>
            <th class="tittle-th">Rank</th>
            <th class="tittle-th">Name, Surname</th>
            <th class="tittle-th">Date Of Admission</th>
            <th class="tittle-th">Bed No</th>

            @if(isset($diets) and !empty($diets))
            <th class="tittle-th" colspan="{{(isset($diets) and count($diets)>0) ? count($diets) : '1'}}">Diet Type</th>
            @endif
            @if(isset($dietsc) and !empty($dietsc))
            <th class="tittle-th" colspan="{{(isset($dietsc) and count($dietsc)>0) ? count($dietsc) : '1'}}">Diet Type</th>
            @endif
            <th class="tittle-th" colspan="{{(isset($extraitems) and count($extraitems)>0) ? count($extraitems) : '1'}}">Extra Diet</th>
            
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <!-- <td>
                <table>
                    <tr>
                    @if(isset($diets) and count($diets) > 0)
                        @foreach($diets as $ft)
                            <td>{{$ft->fldfoodtype}}</td>
                        @endforeach
                    @endif
                    </tr>
                </table>
            </td> -->
            @if(isset($diets) and count($diets) > 0)
                @foreach($diets as $ft)
                    <td>{{$ft->fldfoodtype}}</td>
                @endforeach
            @endif
            @if(isset($dietsc) and count($dietsc) > 0)
                @foreach($dietsc as $ftc)
                    <td>{{$ftc}}</td>
                @endforeach
            @endif
            @if(isset($extraitems) and count($extraitems) > 0)
                @foreach($extraitems as $et)
                    <td>{{$et->fldfoodid}}</td>
                @endforeach
            @else
             <td></td>
            @endif
            <!-- <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td>
            <td>asdfghj</td> -->
            
        </tr>
        </thead>
        <tbody>
            @if(isset($patientinfo) and count($patientinfo) > 0)
                @foreach($patientinfo as $k=>$p)
                   
                    @php
                    $sn = $k+1;
                    
                    @endphp
                     @php
                        $category = Helpers::getEncounterDietDetail($p['fldencounterval']); 
                        $extraitemsdetail = Helpers::getEncounterDietDetailExtra($p['fldencounterval']); 
                    @endphp

                    <tr>
                        <td>{{$sn}}</td>
                        <td>{{$ward}}</td>
                        <td>{{$p['patientnumber']}}</td>
                        <td>{{$p['rank']}}</td>
                        <td>{{$p['fullname']}}</td>
                        <td>{{$p['doa']}}</td>
                        <td>{{$p['bed_number']}}</td>
                         @if(isset($diets) and count($diets) > 0)
                            @foreach($diets as $d)
                                
                                <td>
                                    @if(in_array($d->fldfoodtype,$category))
                                        <img src="{{asset('images/tick.png')}}">
                                    @else
                                        <img src="{{asset('images/cancel.png')}}">
                                    @endif
                                </td>
                            @endforeach
                        @endif
                        @if(isset($dietsc) and count($dietsc) > 0)
                            @foreach($dietsc as $dc)
                                
                                <td>
                                    @if(in_array($dc,$category))
                                        <img src="{{asset('images/tick.png')}}">
                                    @else
                                        <img src="{{asset('images/cancel.png')}}">
                                    @endif
                                </td>
                            @endforeach
                        @endif
                        @if(isset($extraitems) and count($extraitems) > 0)
                            @foreach($extraitems as $ext)
                                
                                <td>
                                    @if(in_array($ext->fldfoodid,$extraitemsdetail))
                                        <img src="{{asset('images/tick.png')}}">
                                    @else
                                        <img src="{{asset('images/cancel.png')}}">
                                    @endif
                                </td>
                            @endforeach
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('dietitian-report'); 
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
