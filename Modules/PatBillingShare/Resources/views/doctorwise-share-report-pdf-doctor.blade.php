@extends('inpatient::pdf.layout.main')

@section('title')
Doctor Wise Fraction Summary
@endsection

@section('content')

<style>
@page{
    margin: 20px !important;
}
.leftalign {
    text-align: left;
}
.rightalign {
    text-align: right;
}
</style>

<header>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" style="max-width:100%;" /></td>
                <td style="width:60%;">
                    <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                    <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                    <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                    <h4 style="text-align: center;margin-top:2px;"> Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</h4>
                    @if(isset($certificate))
                    <h4 style="text-align: center;margin-top:2px;">{{ucfirst($certificate)}} REPORT</h4>
                    @endif

                </td>
                <td style="width: 20%;"></td>
            </tr>
        </tbody>
    </table>
</header>
<table style="width: 100%;">
<tr>
    <td>
        <div >
            <p> <b>Date:</b> {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($from_date)->format('Y-m-d'))->full_date }}
            <b>To&nbsp;</b>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($to_date)->format('Y-m-d'))->full_date }}</p>
        </div>
    </td>
    <td>
        <div>
            <p style="text-align:right;"><b>Print Date:</b> {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse(\Carbon\Carbon::now())->format('Y-m-d'))->full_date }}</p>
        </div>
    </td>
</tr>
<tr>
    <td></td>
    <td>
        <div>
            <p style="text-align:right;"><b>Printed By:</b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</p>
        </div>
    </td>
</tr>
</table>


<div style="width: 100%;">

    <p style="text-align:center"><b>Doctor Wise Fraction Summary(Cash+Bank)</b></p>



<table class="table content-body">
    <thead>
        <tr>
            <th>Sn</th>
            <th>Doctor name</th>
            <th style="text-align: right;">Outdoor </th>
            <th style="text-align: right;">Indoor</th>
            <th>Consultation Fee</th>
            <th>TDS 15%</th>
            <th>Net Total</th>
        </tr>
    </thead>
    <tbody>
                @if($results)
@php
$netoutdoor = 0;
$netindoor = 0;
$netconsultate = 0;
$nettds = 0;
$netnettotal = 0;
@endphp
                @foreach($results as $k => $r)
                @php
                $outdoor = \App\Utils\Helpers::getSumOutdoor($r->user_id,$from_date,$to_date);
               // dd($outdoor);
                $outdoortax = \App\Utils\Helpers::getSumOutdoortax($r->user_id,$from_date,$to_date);
                $indoor = \App\Utils\Helpers::getSumIndoor($r->user_id,$from_date,$to_date);
                $indoortax = \App\Utils\Helpers::getSumIndoortax($r->user_id,$from_date,$to_date);
                $consultationfee = $outdoor+$indoor;
                $total = $consultationfee;
                $tds = $indoortax+$outdoortax;
                $nettotal = $total - $tds;
                @endphp
                @if($nettotal > 0)
                <tr>
                    @php
                        $doctorname = \App\Utils\Helpers::getNameByUsernameID($r->user_id)
                    @endphp
                    @if(strpos( $doctorname, 'Dr.' ) !== false)
                        @php
                            $doctorname = $doctorname;
                        @endphp
                    @else
                        @php
                            $doctorname = 'Dr. '.$doctorname;
                        @endphp
                    @endif
                    <td>{{$k+1}}</td>
                    <td>{{ $doctorname }}</td>
                    <td style="text-align: right;">Rs. {{  \App\Utils\Helpers::numberFormat($outdoor) }} </td>
                    <td style="text-align: right;">Rs. {{  \App\Utils\Helpers::numberFormat($indoor) }} </td>
                    <td>Rs. {{  \App\Utils\Helpers::numberFormat($consultationfee) }} </td>
                    <td>Rs. {{  \App\Utils\Helpers::numberFormat($tds) }}</td>
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($nettotal)}}</td>
                </tr>
                @php
                $netoutdoor += $outdoor;
$netindoor += $indoor;
$netconsultate += $consultationfee;
$nettds += $tds;
$netnettotal += $nettotal;

                @endphp
                @endif

                @endforeach
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td style="text-align: right;"><b>Rs. {{\App\Utils\Helpers::numberFormat($netoutdoor)}}</b></td>
                    <td style="text-align: right;"><b>Rs. {{\App\Utils\Helpers::numberFormat($netindoor)}}</b></td>
                    <td><b>Rs. {{\App\Utils\Helpers::numberFormat($netconsultate)}}</b></td>
                    <td><b>Rs. {{\App\Utils\Helpers::numberFormat($nettds)}}</b></td>
                    <td><b>Rs. {{\App\Utils\Helpers::numberFormat($netnettotal)}}</b></td>
                </tr>
                @endif

    </tbody>

</table>
<br>
