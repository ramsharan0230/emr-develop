<head>
    <title>Patient Credit Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @media print {
            .page {
                margin: 5px;
            }           
        }
        .table {
            border-collapse: collapse;
            width: 100%;      
        }
        .table td, .table th {
            border: 1px solid #a79c9c;
            padding: 4px;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        p, h3 {
            margin-bottom: 0; margin-top: 2px;
        }
        main{
            width: 90%;
            margin: 0 auto;;
        }
        .content-body table { page-break-inside:auto; }
        .content-body tr    { page-break-inside:avoid; page-break-after:auto }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>
    <div class="page">
        <div class="row">
            <table style="width: 100%;" >
                <tr>
                    <td style="width: 10%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
                    <td style="width:80%;">
                        <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                        <h4 style="text-align: center;margin-top:4px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                        <h4 style="text-align: center;margin-top:4px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                            <h4 style="text-align: center;">Patient Credit Report</h4>
                    </td>
                    <td style="width: 10%;"></td>
                </tr>
            </table>
    </div>

    <table style="width:100%">
        <tr>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right">Printed Time: {{ \Carbon\Carbon::now() }}</td>
        </tr>
    </table>
    
    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead class="thead-light">
                <tr>
                    <th>SN</th>
                    <th>Date</th>
                    <th>Days</th>
                    <th>Patient ID/Encounter ID</th>
                    <th>Patient Details</th>
                    @if($amount_type==0)
                    <th>Credit Amount</th>
                    @elseif($amount_type==1)
                    <th>Deposite Amount</th>
                    @else
                    <th>Credit Amount</th>
                    @endif
                </tr>
                </thead>
                <tbody id="billing_result">
                    <?php 
                        $count = 1; 
                    ?>
                    @if(!$patient_credit_report->isEmpty())
                    @foreach ($patient_credit_report as $pcr)
                    @php
                        $days=new \Carbon\Carbon($pcr->fldtime);
                        $days_exceed=\Carbon\Carbon::now()->diffInDays($days);
                    @endphp
                        @if(isset($patient_credit_color))
                            @if($days_exceed<=$patient_credit_color->green_day)
                                <tr>
                                    <td class="green_day">{{$count++}}</td>
                                    <td class="green_day">{{$pcr->fldtime ?? ''}}</td>
                                    <td class="green_day">{{$days_exceed ?? ''}}</td>
                                    <td class="green_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                    <td class="green_day">
                                        {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                        {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}}/{{$pcr->fldptsex ?? ''}} Y <br> 
                                        {{$pcr->fldptcontact ?? ''}}
                                    </td>
                                    <td class="green_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                </tr>
                            @elseif($days_exceed >= $patient_credit_color->green_day && $days_exceed <= $patient_credit_color->yellow_day)
                                <tr>
                                    <td class="yellow_day">{{$count++}}</td>
                                    <td class="yellow_day">{{$pcr->fldtime ?? ''}}</td>
                                    <td class="yellow_day">{{$days_exceed ?? ''}}</td>
                                    <td class="yellow_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                    <td class="yellow_day">
                                        {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                        {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                        {{$pcr->fldptcontact ?? ''}}
                                    </td>
                                    <td class="yellow_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                </tr>
                            @elseif($days_exceed >= $patient_credit_color->yellow_day && $days_exceed <= $patient_credit_color->red_day)
                                <tr>
                                    <td class="red_day">{{$count++}}</td>
                                    <td class="red_day">{{$pcr->fldtime ?? ''}}</td>
                                    <td class="red_day">{{$days_exceed ?? ''}}</td>
                                    <td class="red_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                    <td class="red_day">{{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                        {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                        {{$pcr->fldptcontact ?? ''}}
                                    </td>
                                    <td class="red_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                </tr>
                            @else
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$pcr->fldtime ?? ''}}</td>
                                <td>{{$days_exceed ?? ''}}</td>
                                <td>{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                <td>
                                    {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                    {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                    {{$pcr->fldptcontact ?? ''}} 
                                </td>
                                <td>{{$pcr->fldcurdeposit ?? ''}}</td>
                            </tr>
                            @endif
                        @else
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$pcr->fldtime ?? ''}}</td>
                            <td>{{$days_exceed ?? ''}}</td>
                            <td>{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                            <td>
                                {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                {{$pcr->fldptcontact ?? ''}}    
                            </td>
                            <td>{{$pcr->fldcurdeposit ?? ''}}</td>
                        </tr>
                        @endif
                    @endforeach
                    @endif                        
                </tbody>
        </table>     
    </div>    
</div>
