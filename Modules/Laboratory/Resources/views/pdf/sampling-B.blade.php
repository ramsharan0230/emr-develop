<!DOCTYPE html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Summarize Report A</title>

	<style type="text/css">
        p{
            margin:5px;
        }
		.a4 {
			padding: 0 5px;
			width: 210mm;
			height: 297mm;
			/* border: 1px solid green; */
		}
		.text-center {
			text-align: center;
		}
		.header-bottom {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
		}
		.hb p {
			margin: 0;
		}
		.hb .bar-code {
			width: 200px;
			height: 50px;
			background-color: #ccc;
		}
		.hb-right {
			text-align: right
		}
		.hb-right .bar-code {
			float: right;
		}
		table {
			width: 100%;
			margin-top: 8px;
			border-collapse: collapse;
		}
		#detail-table td {
			border: 1px solid #ccc;
		}

        .table-head {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
		td {
			padding: 1px;
			padding-left: 4px;
			line-height: 16px;
			font-size: 12px;
		}
		.td-header {

			display: flex;
			flex-direction: row;
			justify-content: space-between;
		}
		.td-100 {
			width: 100%;
		}
		.td-30 {
			width: 30%;
		}
		.td-40 {
			width: 40%;
		}
		.td-50 {
			width: 50%;
		}
		.td-75 {
			width: 75%;
		}
		.td-25 {
			width: 25%;
		}
		.td-border {
			border: 1px solid #000;
		}
	</style>
</head>
@php
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
    $fldtime_sample=$fldtime_sample->fldtime_sample??'';
@endphp
<body>
	<div class="">
		<div class="row">
            <table style="width: 100%;" >
                <tr>
                    <td style="width: 10%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
                    <td style="width:80%;">
                        <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                        <h4 style="text-align: center;margin-top:4px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                        <h4 style="text-align: center;margin-top:4px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                        <h4 style="text-align: center;">worksheet</h4>
                    </td>
                    <td style="width: 10%;"></td>
                </tr>
            </table>
    	</div>
		<header>
			<p class="text-center">
				Printed Time : {{ \Carbon\Carbon::now() }} <br>
				Printed By:{{ \Auth::guard('admin_frontend')->user()->flduserid ?? ''}}
			</p>
			<div class="header-bottom">
				<div class="hb hb-left">
					<p> Sample No: {{ $fldsampleid }}</p>
					<div class="">
						@php
                        echo DNS1D::getBarcodeSVG($fldsampleid, \App\Utils\Options::get('barcode_format'))
						@endphp
					</div>
				</div>
				<div class="hb hb-right">
					<p> Collected At : <span>{{ $fldtime_sample }} </span></p>
					<div class="">
						@php
                        echo DNS1D::getBarcodeSVG($encounter_data->fldencounterval, \App\Utils\Options::get('barcode_format')) 
						@endphp
					</div>
				</div>
			</div>
		</header>

		<section>
			<table id="detail-table">
				<tr>
					<td colspan="4">
                        <div class="table-head">
                            <div>
                                <p><strong>Name:</strong>  {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
                                {{-- <p><strong>Department:</strong> % </p> --}}
                                <p><strong>Specimen:</strong> {{ $specimen }}</p>
                            </div>
                            <div>
                                <p><strong>EncID:</strong> {{ $encounter_data->fldencounterval }}</p>
                                <p><strong>DOReg:</strong> {{ $encounter_data->fldregdate ? \Carbon\Carbon::parse($encounter_data->fldregdate)->format('d/m/Y'):'' }}</p>
                                <p><strong>Phone: </strong>{{$patientInfo->fldptcontact??''}}</p>
                                <p><strong>Refer By: </strong>{{$refer_by->fldfullname??''}}</p>
                            </div>
                            <div>
                            </div>
                        </div>
					</td>
				</tr>
				<tr>
					<td class="td-30">Test</td>
					<td colspan="3">&nbsp;</td>
				</tr>
                @foreach ($testData as $groupKey => $test)
                    <tr>
                    @if (is_array($test))
                        @php
                            $rowcount = count($test);
                        @endphp
						@if($rowcount==1)
						<td colspan="2">{{ $groupKey }}</td>
						@else
						<td rowspan="{{ $rowcount }}">{{ $groupKey }}</td>
                        <td colspan="2">{{ $test[0]->fldtestid }}</td>
                        {{-- <td class="td-30"></td> --}}
						@endif
                    </tr>
                        @for ($i = 1; $i < $rowcount; $i++)
                            <tr>
                                <td colspan="2">{{ $test[$i]->fldtestid }}</td>
                                {{-- <td></td> --}}
                            </tr>
                        @endfor
                    @else
                        <td colspan="1">{{ $test->fldtestid }}</td>
                        <td colspan="3"></td>
                    @endif
                    </tr>
                @endforeach
			</table>
		</section>
	</div>
</body>
</html>