@extends('inpatient::pdf.layout.main')

@section('title')
    Doctor Share Report
@endsection

@section('content')
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
    <div style="width: 100%;">
    <div style="width: 50%;float: left;">
            <p>From Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($eng_from_date)->format('Y-m-d'))->full_date }}</p>
            <p>To Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($eng_to_date)->format('Y-m-d'))->full_date }}</p>
        </div>
        <div style="width: 50%;float: left; text-align: right;">
            <p>Print Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse(\Carbon\Carbon::now())->format('Y-m-d'))->full_date }}</p>
        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 50%;">
            <h3>{{ $drshare ? \App\Utils\Helpers::getNameByUsernameID($drshare[0]->druserid) :''}}</h3>
        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 20%;">
            <p><b>Doctor Share Summary</b></p>
        </div>
        <div style="width: 100%;">
            <table class="table content-body">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th style="text-align: center;">Bill number</th>
                    <th style="text-align: center;">Encounter </th>
                    <th style="text-align: center;">Patient Name </th>
                    <th style="text-align: center;">Type</th>
                    <th style="text-align: center;">Share</th>
                    <th style="text-align: center;">TDS</th>
                    <th style="text-align: center;">Time</th>
                    <th style="text-align: center;">Returned at</th>
                </tr>
                </thead>
                <tbody>
@if($drshare)
@foreach($drshare as $k => $drshare)
<tr>
    <td>{{$k+1}}</td>
                    <td>{{$drshare->fldbillno}}</td>
                    <td>{{$drshare->fldencounterval}}</td>
                    <td>{{\App\Utils\Helpers::getPatientName($drshare->fldencounterval)}}</td>
                    <td>{{$drshare->type}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->share)}}</td>
                    <td>{{$drshare->shareqty}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->tax_amt)}}</td>
                    <td>{{$drshare->fldtime}}</td>
                    <td>{{$drshare->returned_at}}</td>
</tr>
                    @endforeach
                    @endif
                </tbody>

            </table>
        </div>
    </div>



@endsection
