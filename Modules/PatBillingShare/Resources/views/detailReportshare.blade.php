@extends('inpatient::pdf.layout.main')

@section('title')
Doctor Share Report
@endsection
<style>
    .content-body tbody td:nth-child(3) {
        text-align: left;
    }
</style>

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
                    <th style="text-align: center;">Patient Name</th>
                    <th style="text-align: center;">Item name </th>
                    <th style="text-align: center;">Item Rate </th>
                    <th style="text-align: center;">Item QTY</th>
                    <th style="text-align: center;">Discount Amount</th>
                    <th style="text-align: center;">Amount</th>
                    <th style="text-align: center;">Share</th>
                    <th style="text-align: center;">TDS</th>
                    <th style="text-align: center;">Dr. Share Percentage</th>

                    <th style="text-align: center;">Time</th>
                    <th style="text-align: center;">Share Time</th>
                    <th style="text-align: center;">Returned(Returned Date)</th>
                    <th style="text-align: center;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @if($drshare)
                @foreach($drshare as $k => $drshare)
                <tr class="table-body" @if($drshare->is_returned == '1') style="background-color:#f14c4c;color:white;" @endif>
                    <td>{{$k+1}}</td>
                    <td>{{ucwords($drshare->patientname)}}</td>
                    <td>{{$drshare->flditemname}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($drshare->flditemrate)}}</td>
                    <td>{{$drshare->flditemqty}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->flddiscamt)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->fldditemamt)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->share)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($drshare->tax_amt)}}</td>
                    <td>{{$drshare->usersharepercent}}</td>

                    <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($drshare->fldtime)->format('Y-m-d'))->full_date }}</td>
                    <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($drshare->created_at)->format('Y-m-d'))->full_date }} </td>
                    <td>@if($drshare->is_returned == '1') Yes({{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($drshare->returned_at)->format('Y-m-d'))->full_date }}) @endif</td>
                    <td>{{ \App\Utils\Helpers::remarkofbillreturn($drshare->fldbillno) }} </td>

                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>



@endsection
