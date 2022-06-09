<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Hospital Reporting Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Martel:wght@400;700&family=Roboto:wght@400;700&display=swap"
          rel="stylesheet">

    <style>
        @page {
            margin: 20px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            /* background-color: #525659; */
        }

        .wrapper {
            /* max-width: 1000px; */
            /* margin: 20px auto;
            padding: 20px; */
            background: #fff;
        }

        .nepali-font {
            font-family: 'Martel', serif;
        }

        table td {
            border: 1px solid #000;
            border-right: none;
            border-bottom: none;
            padding: 1.5px;
            font-size: 11px;
            line-height: 10px;
        }

        @font-face {
            font-family: preeti;
            font-weight: normal;
            src: url("{{asset('assets/fonts/preeti.TTF')}}") format('truetype');
        }

        .nepali-font {
            font-family: preeti;
            font-size: 13px;
        }

        .nepali-bold {
            font-family: preeti;
            font-weight: bold;
        }


    </style>
</head>
<body>
<!-- First page-->
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000; font-size: 10px;">
                Government of Nepal
            </td>
        </tr>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE"
                style="border-right: 1px solid #000; font-size: 10px; border-top: none;">Ministry of Health &amp;
                Population
            </td>
        </tr>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE"
                style="border-top: none; border-right: 1px solid #000; font-weight: bold;">Department of Health Services
            </td>
        </tr>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE"
                style="border-top: none; border-right: 1px solid #000; font-weight: bold;">Health Management Information
                System
            </td>
        </tr>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE"
                style="border-top: none; border-right: 1px solid #000; font-weight: bold;">Hospital Level Monthly
                Reporting
                Form
            </td>
        </tr>
        <tr>
            <td colspan="14" align="center" bgcolor="#EEEEEE"
                style="border-top: none; border-right: 1px solid #000; font-weight: bold;">{{ (isset(Options::get('siteconfig')['system_name'])) ? Options::get('siteconfig')['system_name'] : null }}
            </td>
        </tr>
        <tr>
            <td colspan="2">Fiscal Year:</td>
            <td>20{{ $fiscal_one ?? null }} / 20{{ $fiscal_two ?? null }}</td>
            <td rowspan="2" style="border: none; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
            <td colspan="3">Health Facility Code:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #000;">Reference No:</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td>Palika:</td>
            <td colspan="6">&nbsp;</td>
            <td colspan="2">Ward</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="3" style="border: none;">To … … … … … … … … … … … … … … … … … … … … … … … … … … … …
                … … … …<br>
                … … … … … … … … … … … …
            </td>
            @php
                $today = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
                $today = explode('-',$today);
            @endphp
            <td colspan="3">Dispatched Date:</td>
            <td colspan="7" style="border-right: 1px solid #000;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $today[1] ?? null }}  &nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;{{ $today[2] ?? null }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/
                {{  $today[0] ?? null }}
            </td>
        </tr>
        <tr>
            <td colspan="3">Received Date:</td>
            <td colspan="7" style="border-right: 1px solid #000;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/
                207......
            </td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" align="center" style="border-bottom: 1px solid #000;">Number of Beds</td>
            <td colspan="2" align="center">Sanctioned</td>
            <td colspan="6" style="border-right: 1px solid #000;" align="center">
                &nbsp; {{(isset( Options::get('siteconfig')['hospital_sanctioned_bed'])) ?  Options::get('siteconfig')['hospital_sanctioned_bed'] : null  }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border: none;">Subject: Submission of Monthly Report on Hospital Services
                : {{ $today[1] ?? null }}
                Month,
                {{  $today[0] ?? null }}
                Year.
            </td>
            <td colspan="2" align="center" style="border-bottom: 1px solid #000;">Operational</td>
            <td colspan="6" style="border-bottom : 1px solid #000; border-right: 1px solid #000;" align="center">
                &nbsp; {{(isset( Options::get('siteconfig')['hospital_operational_bed'])) ?  Options::get('siteconfig')['hospital_operational_bed'] : null  }} </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
        <tbody>
        <tr>
            <td colspan="6" align="center" bgcolor="#EEEEEE">Hospital Services</td>
            <td rowspan="7" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Emergency Services</td>
            <td rowspan="7" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Total Patients Admitted</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ $total_patients_admitted ?? null }}</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Age Group</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">New Clients Served</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Total Clients Served</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Total Clients Served</td>
            <td colspan="2" bgcolor="#EEEEEE">Total Inpatient Days</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ $total_inpatients ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td colspan="3" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>


        {{--        {{ dd($all_hospital_services['new_male_female']->where('age_group','0_9_female')->first()) }}--}}
        <tr>
            <td align="center" bgcolor="#EEEEEE">0 - 9 Years</td>
            <td align="center">&nbsp;{{ $all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','0_9_female')->first() ? $all_hospital_services['new_male_female']->where('age_group','0_9_female')->first()->total: null) : null }}</td>
            <td align="center">&nbsp;{{$all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','0_9_male')->first() ? $all_hospital_services['new_male_female']->where('age_group','0_9_male')->first()->total : null) : null }}</td>
            <td colspan="2" align="center">&nbsp;{{$all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','0_9_female')->first() ? $all_hospital_services['total_male_female']->where('age_group','0_9_female')->first()->total : null) : null }}</td>
            <td align="center">&nbsp;{{ $all_hospital_services['total_male_female'] ? ($all_hospital_services['total_male_female']->where('age_group','0_9_male')->first() ? $all_hospital_services['total_male_female']->where('age_group','0_9_male')->first()->total :null) : null }}</td>

            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','0_9_female')->first() ? $emergency_service['male_female_emergency']->where('age_group','0_9_female')->first()->total: null) : null }}</td>
            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','0_9_male')->first() ? $emergency_service['male_female_emergency']->where('age_group','0_9_male')->first()->total: null) : null }}</td>
            <td width="200" align="center" bgcolor="#EEEEEE">Diagnostic/Other Services</td>
            <td align="center" bgcolor="#EEEEEE">Unit</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Number</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">10 - 19 Years</td>
            <td align="center">&nbsp;{{ $all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','10_19_female')->first() ? $all_hospital_services['new_male_female']->where('age_group','10_19_female')->first()->total : null) : null }}</td>
            <td align="center">&nbsp;{{ $all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','10_19_male')->first() ?$all_hospital_services['new_male_female']->where('age_group','10_19_male')->first()->total : null) : null }}</td>
            <td colspan="2" align="center">&nbsp;{{ $all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','10_19_female')->first() ? $all_hospital_services['total_male_female']->where('age_group','10_19_female')->first()->total : null) : null }}</td>
            <td align="center">&nbsp;{{ $all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','10_19_male')->first() ? $all_hospital_services['total_male_female']->where('age_group','10_19_male')->first()->total :null) : null }}</td>


            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','10_19_female')->first() ? $emergency_service['male_female_emergency']->where('age_group','10_19_female')->first()->total: null) : null }}</td>
            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','10_19_male')->first() ? $emergency_service['male_female_emergency']->where('age_group','10_19_male')->first()->total: null) : null }}</td>
            <td bgcolor="#EEEEEE">X-ray</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>

            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','X-ray')->sum('flditemqty') : null }}&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">20 - 59 Years</td>

            <td align="center">&nbsp;{{$all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','20_59_female')->first() ? $all_hospital_services['new_male_female']->where('age_group','20_59_female')->first()->total :null) : null }}</td>
            <td align="center">&nbsp;{{$all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','20_59_male')->first() ? $all_hospital_services['new_male_female']->where('age_group','20_59_male')->first()->total : null) : null }}</td>
            <td colspan="2" align="center">&nbsp;{{$all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','20_59_female')->first() ? $all_hospital_services['total_male_female']->where('age_group','20_59_female')->first()->total : null) : null }}</td>
            <td align="center">&nbsp;{{$all_hospital_services['total_male_female'] ? ( $all_hospital_services['total_male_female']->where('age_group','20_59_male')->first() ?  $all_hospital_services['total_male_female']->where('age_group','20_59_male')->first()->total : null) : null }}</td>

            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','20_59_female')->first() ? $emergency_service['male_female_emergency']->where('age_group','20_59_female')->first()->total: null) : null }}</td>
            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','20_59_male')->first() ? $emergency_service['male_female_emergency']->where('age_group','20_59_male')->first()->total: null) : null }}</td>
            <td bgcolor="#EEEEEE">Ultrasonogram (USG)</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Ultrasonogram (USG)')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">≥ 60 Years</td>

            <td align="center">&nbsp;{{  $all_hospital_services['new_male_female'] ? ($all_hospital_services['new_male_female']->where('age_group','60_above_female')->first() ? $all_hospital_services['new_male_female']->where('age_group','60_above_female')->first()->total:null) :  null }}</td>
            <td align="center">&nbsp;{{ $all_hospital_services['new_male_female'] ?  ($all_hospital_services['new_male_female']->where('age_group','60_above_male')->first() ? $all_hospital_services['new_male_female']->where('age_group','60_above_male')->first()->total:null) :  null }}</td>
            <td colspan="2" align="center">&nbsp;{{ $all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','60_above_female')->first() ? $all_hospital_services['total_male_female']->where('age_group','60_above_female')->first()->total:null) :  null }}</td>
            <td align="center">&nbsp;{{ $all_hospital_services['total_male_female'] ?  ($all_hospital_services['total_male_female']->where('age_group','60_above_male')->first() ? $all_hospital_services['total_male_female']->where('age_group','60_above_male')->first()->total : null):  null }}</td>

            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','60_above_female')->first() ? $emergency_service['male_female_emergency']->where('age_group','60_above_female')->first()->total: null) : null }}</td>
            <td align="center">&nbsp;{{ $emergency_service['male_female_emergency'] ?  ($emergency_service['male_female_emergency']->where('age_group','60_above_male')->first() ? $emergency_service['male_female_emergency']->where('age_group','60_above_male')->first()->total: null) : null }}</td>
            <td bgcolor="#EEEEEE">Echocardiogram (Echo)</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Echocardiogram (Echo)')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Electro Encephalo Gram (EEG)</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Electro Encephalo Gram (EEG)')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" width="80">Free Service Received by Impoverished Citizen</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td bgcolor="#EEEEEE">Male</td>
            <td rowspan="9" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">ORC Clinics/FCHVORC Clinics/FCHV</td>
            <td align="center" bgcolor="#EEEEEE">Planned / Total No.</td>
            <td align="center" bgcolor="#EEEEEE">Conducted/ Report Received</td>
            <td align="center" bgcolor="#EEEEEE">No. of Clients Served</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Electrocardiogram (ECG)</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Electrocardiogram (ECG)')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Heart</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">PHC Outreach Clinic</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE">Treadmill</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Trademill')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Kidney</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Immunization Clinic</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="2">&nbsp;</td>
            <td bgcolor="#EEEEEE">Computed Tomographic (CT) Scan</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Computed Tomographic (CT) Scan')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Cancer</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Immunization Session</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE">Magnetic Resonance Imaging (MRI)</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_number']) ? $diagnostic['dignostic_number']->where('sub_category','=','Magnetic Resonance Imaging (MRI)')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Head Injury</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">FCHV</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td bgcolor="#EEEEEE">Endoscopy</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_person']) ? $diagnostic['dignostic_person']->where('sub_category','=','Endoscopy')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Spinal Injury</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Colonoscopy</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_person']) ? $diagnostic['dignostic_person']->where('sub_category','=','Colonscopy')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Alzheimer</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Referrals</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Referral In</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Referred Out</td>
            <td rowspan="4" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Nuclear Medicine</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;" align="center"> {{ isset($diagnostic['dignostic_person']) ? $diagnostic['dignostic_person']->where('sub_category','=','Neuclear Medicine')->sum('flditemqty') : null}}&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Parkinson</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">Outpatient</td>
            <td align="center" bgcolor="#EEEEEE">In-patient</td>
            <td align="center" bgcolor="#EEEEEE">Emergency</td>
            <td bgcolor="#EEEEEE">Total Preventive service Provided</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Sickle Cell Anaemia</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE">Female</td>
            <td align="center">{{ $refer['refer_in_female'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_outpatient_female'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_inpatient_female'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_emergency_female'] ?? null }}</td>
            <td bgcolor="#EEEEEE">Total Laboratory service Provided</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($total_lab_service) ? $total_lab_service : null }}</td>
        </tr>
        <tr>
            <td colspan="3" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Male</td>
            <td align="center">{{ $refer['refer_in_male'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_outpatient_male'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_inpatient_male'] ?? null }}</td>
            <td align="center">{{ $refer['refer_out_emergency_male'] ?? null }}</td>
            <td bgcolor="#EEEEEE">Other Service Provided (if any)</td>
            <td align="center" bgcolor="#EEEEEE">Persons</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="border: none;"><strong>Prepared By</strong></td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" style="border: none; border-top: 1px solid #000;"><strong>Approved by</strong></td>
        </tr>
        <tr>
            <td colspan="10" style="border: none;">&nbsp;</td>
            <td colspan="3" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10" style="border: none;">Signature</td>
            <td colspan="3" style="border: none;">Signature</td>
        </tr>
        <tr>
            <td colspan="10" style="border: none;">Name of Medical Recorder</td>
            <td colspan="3" style="border: none;">Name of Hospital Superintendent/ Director</td>
        </tr>
        <tr>
            <td colspan="10" style="border: none;">&nbsp;</td>
            <td colspan="3" style="border: none;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>
<!-- End First page-->

<!-- Second page-->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
        <tbody>
        <tr>
            <td colspan="15" align="center" bgcolor="#EEEEEE" style="border-left: none;"><strong>1. Summary of Indoor
                    Services</strong></td>
        </tr>
        <tr>
            <td colspan="15" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="15" align="center" bgcolor="#EEEEEE" style="border-left: none;">A. Inpatient Outcome</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Age Group</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Recovered/Cured</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Not Improved</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Referred Out</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">DOR/LAMA/DAMA</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Absconded</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Death &lt; 48 Hours</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Death ≥ 48 Hours</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">≤ 28 Days</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_less_twenty_eight['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_less_twenty_eight['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_less_twenty_eight['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_less_twenty_eight['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">29 Days - 1 Year</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_twenty_nine_to_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_nine_to_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_nine_to_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_nine_to_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_twenty_nine_to_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">01 - 04 Years</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_one_to_four_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_one_to_four_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_one_to_four_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_one_to_four_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">05 - 14 years</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_five_to_fourteen_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_five_to_fourteen_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_five_to_fourteen_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_five_to_fourteen_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_five_to_fourteen_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_five_to_fourteen_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_five_to_fourteen_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">15 - 19 Years</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifteen_to_nineteen_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifteen_to_nineteen_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifteen_to_nineteen_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifteen_to_nineteen_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_fifteen_to_nineteen_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifteen_to_nineteen_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_fifteen_to_nineteen_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">20 - 29 Years</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_to_twentynine_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_to_twentynine_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_to_twentynine_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_twenty_to_twentynine_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_twenty_to_twentynine_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_twenty_to_twentynine_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">30 - 39 Years</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_thirty_to_thirtynine_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_thirty_to_thirtynine_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_thirty_to_thirtynine_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_thirty_to_thirtynine_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_thirty_to_thirtynine_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_thirty_to_thirtynine_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">40 - 49 Years</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fourty_to_fourtynine_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fourty_to_fourtynine_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fourty_to_fourtynine_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fourty_to_fourtynine_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_fourty_to_fourtynine_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_fourty_to_fourtynine_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">50 - 59 Years</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center"> {{ $inpatient_fifty_to_fiftynine_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center">&nbsp; {{ $inpatient_fifty_to_fiftynine_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp; {{ $inpatient_fifty_to_fiftynine_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">≥ 60 Years</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp; {{ $inpatient_greater_than_sixty_years['improved_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['improved_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['not_improved_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['not_improved_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['refer_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['refer_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['lama_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['lama_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['absconder_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['absconder_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['death_less_two_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['death_less_two_male'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['death_greater_two_female'] [0]->cnt ?? null }}</td>
            <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">
                &nbsp;{{ $inpatient_greater_than_sixty_years['death_greater_two_male'] [0]->cnt ?? null }}</td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
        <tbody>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Neonate Form</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">Gastational Weeks</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Type of Surgeries</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Number of Surgeries</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Post Operative Infection</td>
            <td rowspan="8" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Death Information</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">22 - 27</td>
            <td align="center" bgcolor="#EEEEEE">28 - 36</td>
            <td align="center" bgcolor="#EEEEEE">37 - 41</td>
            <td align="center" bgcolor="#EEEEEE">≥ 42</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td rowspan="5" align="center" bgcolor="#EEEEEE">Hospital Death</td>
            <td bgcolor="#EEEEEE">Early Neonatal</td>
            <td align="center">&nbsp;{{ $death_information['early_neonatal_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['early_neonatal_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Primi</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Major</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Female')->where('flditemtype','=','Procedures')->where('fldtarget','=','Major')->count() : null  }}</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Male')->where('flditemtype','=','Procedures')->where('fldtarget','=','Major')->count() : null  }}</td>
            <td align="center">&nbsp;</td>
            <td bgcolor="#EEEEEE">Late Neonatal</td>
            <td align="center">&nbsp;{{ $death_information['late_neonatal_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['late_neonatal_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Multi</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Intermediate</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Female')->where('flditemtype','=','Procedures')->where('fldtarget','=','Intermediate')->count() : null  }}</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Male')->where('flditemtype','=','Procedures')->where('fldtarget','=','Intermediate')->count() : null  }}</td>
            <td align="center"> &nbsp;</td>
            <td bgcolor="#EEEEEE">Maternal (All)</td>
            <td align="center">&nbsp;{{ $death_information['maternal_all'][0]->col ?? null }}</td>
            <td bgcolor="#333333" style="border-right: 1px solid #000;" align="center">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Grand Multi</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE">Minor</td>
            <td bgcolor="#EEEEEE">Outpatients</td>
            <td align="center">&nbsp;{{ isset($typeofSurgeries['department']) ? $typeofSurgeries['department']->where('fldptsex','=','Female')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                        ->where('fldcateg','=','Consultation')
                        ->count() : null  }}</td>
            <td align="center">&nbsp; {{ isset($typeofSurgeries['department']) ? $typeofSurgeries['department']->where('fldptsex','=','Male')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                         ->where('fldcateg','=','Consultation')
                        ->count() : null  }}</td>
            <td align="center">&nbsp;</td>
            <td bgcolor="#EEEEEE">Post-operative*</td>
            <td align="center">&nbsp;{{ $death_information['post_operative_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['post_operative_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td colspan="6" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Inpatients</td>
            <td align="center">&nbsp;{{ isset($typeofSurgeries['department']) ? $typeofSurgeries['department']->where('fldptsex','=','Female')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                        ->where('fldcateg','=','Patient ward')
                        ->count() : null  }}</td>
            <td align="center">&nbsp;{{ isset($typeofSurgeries['department']) ? $typeofSurgeries['department']->where('fldptsex','=','Male')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                         ->where('fldcateg','=','Patient ward')
                        ->count() : null  }}</td>
            <td align="center"></td>
            <td bgcolor="#EEEEEE">Other</td>
            <td align="center">&nbsp;{{ $death_information['others_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['others_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td rowspan="3" align="center" bgcolor="#EEEEEE">Maternal Age (Yrs)</td>
            <td align="center" bgcolor="#EEEEEE">&lt; 20</td>
            <td align="center">&nbsp;{{ isset($greatertwentydata) ? $greatertwentydata[0] : null }}</td>
            <td align="center">&nbsp;{{ isset($greatertwentydata) ? $greatertwentydata[1] : null }}</td>
            <td align="center">&nbsp;{{ isset($greatertwentydata) ? $greatertwentydata[2] : null }}</td>
            <td align="center">&nbsp;{{ isset($greatertwentydata) ? $greatertwentydata[3] : null }}</td>
            <td rowspan="3" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td bgcolor="#EEEEEE">Emergency</td>
            <td align="center">&nbsp;&nbsp;{{ isset($typeofSurgeries['emergency']) ? $typeofSurgeries['emergency']->where('fldptsex','=','Female')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                        ->count() : null  }}</td>
            <td align="center">&nbsp;&nbsp;{{ isset($typeofSurgeries['emergency']) ? $typeofSurgeries['emergency']->where('fldptsex','=','Male')
                        ->where('flditemtype','=','Procedures')
                        ->where('fldtarget','=','Minor')
                        ->count() : null  }}</td>
            <td align="center">&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Brought Dead</td>
            <td align="center">&nbsp; {{ $death_information['brought_dead_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['brought_dead_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">20 - 34</td>
            <td align="center">&nbsp;{{ isset($mid) ? $mid[0] : null }}</td>
            <td align="center">&nbsp;{{ isset($mid) ? $mid[1] : null }}</td>
            <td align="center">&nbsp;{{ isset($mid) ? $mid[2] : null }}</td>
            <td align="center">&nbsp;{{ isset($mid) ? $mid[3] : null }}</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Plaster</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Female')->where('flditemtype','=','Procedures')->where('fldtarget','=','Plaster')->count() : null  }}</td>
            <td align="center">
                &nbsp;{{ isset($typeofSurgeries['surgeries']) ? $typeofSurgeries['surgeries']->where('fldptsex','=','Male')->where('flditemtype','=','Procedures')->where('fldtarget','=','Plaster')->count() : null  }}</td>
            <td bgcolor="#333333">&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Postmortem Done</td>
            <td align="center">&nbsp;{{ $death_information['postmortem_female'][0]->col ?? null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ $death_information['postmortem_male'][0]->col ?? null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">≥ 35</td>
            <td align="center">&nbsp;{{ isset($greaterthrityfive) ? $greaterthrityfive[0] : null }}</td>
            <td align="center">&nbsp;{{ isset($greaterthrityfive) ? $greaterthrityfive[1] : null }}</td>
            <td align="center">&nbsp;{{ isset($greaterthrityfive) ? $greaterthrityfive[2] : null }}</td>
            <td align="center">&nbsp;{{ isset($greaterthrityfive) ? $greaterthrityfive[3] : null }}</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;"><strong>Free Health Services and Social
                    Security Programme</strong></td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="4" style="border: none; border-top: 1px solid #000;"><em>* Excluding Neonatal &amp; Maternal
                    Death</em></td>
        </tr>
        <tr>
            <td colspan="6" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Patients at</td>
            <td align="center" bgcolor="#EEEEEE">Ultra Poor/ Poor</td>
            <td align="center" bgcolor="#EEEEEE">Helpless/ Destitute</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Disabled</td>
            <td align="center" bgcolor="#EEEEEE">Sr. Citizens&gt; 60 Years</td>
            <td align="center" bgcolor="#EEEEEE">FCHV</td>
            <td align="center" bgcolor="#EEEEEE">Gender Based Voilence</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Others</td>
        </tr>
        <tr>
            <td colspan="6" align="center" bgcolor="#EEEEEE">Free health service summary</td>
            <td rowspan="4" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Outpatients</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Cost Exemtion</td>
            <td align="center" bgcolor="#EEEEEE">No. of Patients</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Total Exempted cost (NRS)</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Inpatients</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Partially</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Emergency</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Completely</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Referred Out</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="2" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>
<!-- End Second page-->

<!-- Third page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="24" align="center" bgcolor="#EEEEEE" style="font-weight: bold; border-left: none;">B. Inpatient
                Morbidity (No. of Patients Discharged)
            </td>
        </tr>
        <tr>
            <td colspan="24" style="border:none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Diseases</td>
            @foreach($inpatientMorbidity['age_group'] as $agegroup)
                <td colspan="2" align="center" bgcolor="#EEEEEE">{{ $agegroup }}</td>
            @endforeach
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Total Deaths</td>
        </tr>
        <tr>
            @foreach($inpatientMorbidity['age_group'] as $agegroup)
                <td align="center" bgcolor="#EEEEEE">F</td>
                <td align="center" bgcolor="#EEEEEE">M</td>
            @endforeach
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">M</td>
        </tr>
        {{--        <tr>--}}
        {{--            @php $i = 1 @endphp--}}
        {{--            <td align="center" bgcolor="#EEEEEE">ghfghf</td>--}}
        {{--            <td align="center" bgcolor="#EEEEEE">{{ $i++ }}</td>--}}
        {{--            @foreach($inpatientMorbidity['age_group'] as $agegroup)--}}
        {{--                <td align="center" bgcolor="#EEEEEE">{{ $i++ }}</td>--}}
        {{--                <td align="center" bgcolor="#EEEEEE">{{ $i++  }}</td>--}}
        {{--            @endforeach--}}
        {{--            <td align="center" bgcolor="#EEEEEE">{{ $i++ }}</td>--}}
        {{--            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">{{ $i++ }}</td>--}}
        {{--        </tr>--}}
        @forelse($inpatientMorbidity['ret_data'] as $disease)
            <tr>
                <td style="border-bottom: 1px solid #000;" align="center">&nbsp;{{ $disease['code'] }}</td>
                <td style="border-bottom: 1px solid #000;" align="center">&nbsp;{{ $disease['disease'] }}</td>

                @foreach($inpatientMorbidity['age_group'] as $agegroup)
                    @if(isset($disease[$agegroup]))
                        <td style="border-bottom: 1px solid #000;" align="center">
                            &nbsp;{{ isset($disease[$agegroup]['Female']) ? $disease[$agegroup]['Female'] : null }}</td>
                        <td style="border-bottom: 1px solid #000;" align="center">
                            &nbsp;{{ isset($disease[$agegroup]['Male']) ? $disease[$agegroup]['Male'] : null }}</td>
                    @else
                        <td style="border-bottom: 1px solid #000;" align="center">&nbsp;0</td>
                        <td style="border-bottom: 1px solid #000;" align="center">&nbsp;0</td>
                    @endif
                @endforeach

                <td style="border-bottom: 1px solid #000;" align="center">
                    &nbsp;{{ isset($disease[$agegroup]['Female']) ? $disease[$agegroup]['Female'] : null }}</td>
                <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;" align="center">
                    &nbsp; {{ isset($disease[$agegroup]['Male']) ? $disease[$agegroup]['Male'] : null }} </td>
            </tr>
        @empty
            <tr>
                <td colspan="30"></td>
                <td colspan="30"></td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>

<br>
<!-- End Third Page -->


{{--<!-- Fourth page -->--}}
<div class="wrapper" style="page-break-inside:avoid;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="23" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-left: none;">!= vf]k sfos{|d</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">vf]ksf] k|sf/</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">la=;L=hL=</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">8L=kL= 6L–x]k lj=–lxa=</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">kf]lnof] <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(OPV)</span></td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">kL= ;L= eL=</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">/f]6f</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font"> Pkm=cfO=kL=eL </td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">bfb'/f–?a]nf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">h]=O{ !@ dlxgf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">Ps aif{ pd]/kl5 8L=kL= 6L–x]k lj=lxa= / kf]lnof]sf] t]>f] dfqf k'/f</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">l6=8L=-uej{tL dlxnf_</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">t];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">t];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">t];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf] <small>-( d_</small></td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f] <small>-!% d_</small></td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">bf];|f]±</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td align="center" bgcolor="#EEEEEE">16</td>
            <td align="center" bgcolor="#EEEEEE">17</td>
            <td align="center" bgcolor="#EEEEEE">18</td>
            <td align="center" bgcolor="#EEEEEE">19</td>
            <td align="center" bgcolor="#EEEEEE">20</td>
            <td align="center" bgcolor="#EEEEEE">21</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">22</td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="nepali-font">vf]k kfPsf aRrfx?sf] ;+Vof</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['bcgCount'] : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['dptFirstCount']  : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['dptSecondCount']  : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['dptThirdCount']  : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['polioFirstCount']  : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['polioSecondCount']  : null }}</td>
            <td align="center">&nbsp;{{ isset($vaccination) ? $vaccination['polioThirdCount']  : null }}</td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['pcvFirstCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['pcvSecondCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['pcvThirdCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['rotaFirstCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['rotaSecondCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['fipvFirstCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['fipvSecondCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['daduraFirstCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['daduraSecondCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['jeCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['dptAndPolio']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['tdFirstCount']  : null }} </td>
            <td align="center">&nbsp; {{ isset($vaccination )? $vaccination['tdSecondCount']  : null }} </td>
            <td style="border-right: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['tdThirdCount']  : null }} </td>
        </tr>
        <tr>
            <td rowspan="2" align="center" class="nepali-font">vf]k-8f]h_</td>
            <td align="center" class="nepali-font">k|fKt ePsf]</td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['bcgDoseTaken'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['dptDoseTaken'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['polioDoseTaken'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['pcvDoseTaken'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['rotaDoseTaken'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['fipvDoseTaken'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['daduraDoseTaken'] : null}}</td>
            <td align="center" >&nbsp; {{ isset($vaccination) ? $vaccination['jeDoseTaken'] : null}}</td>
            <td align="center" colspan="1" bgcolor="#333333" ></td>
            <td align="center" style="border-right: 1px solid #000;" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['tdDoseTaken'] : null}}</td>
        </tr>
        <tr>
            <td align="center" class="nepali-font">vr{ ePsf]</td>
            <td align="center">&nbsp; {{ isset($vaccination) ? $vaccination['bcgDoseGiven'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['dptDoseGiven'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['polioDoseGiven'] : null}}</td>
            <td align="center" colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['pcvDoseGiven'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['rotaDoseGiven'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['fipvDoseGiven'] : null}}</td>
            <td align="center" colspan="2">&nbsp; {{ isset($vaccination) ? $vaccination['daduraDoseGiven'] : null}}</td>
            <td align="center" >&nbsp; {{ isset($vaccination) ? $vaccination['jeDoseGiven'] : null}}</td>
            <td align="center" colspan="1" bgcolor="#333333" ></td>
            <td align="center" style="border-right: 1px solid #000;"  colspan="3">&nbsp; {{ isset($vaccination) ? $vaccination['tdDoseGiven'] : null}}</td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="border-bottom: 1px solid #000;">AEFI cases</td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['bcgAefi'] : null}}</td>
            <td align="center"  colspan="3" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['dptAefi'] : null}}</td>
            <td align="center"  colspan="3" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['polioAefi'] : null}}</td>
            <td align="center"  colspan="3" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['pcvAefi'] : null}}</td>
            <td align="center"  colspan="2" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['rotaAefi'] : null}}</td>
            <td align="center"  colspan="2" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['fipvAefi'] : null}}</td>
            <td align="center"  colspan="2" style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['daduraAefi'] : null}}</td>
            <td align="center"   style="border-bottom: 1px solid #000;">&nbsp; {{ isset($vaccination) ? $vaccination['jeAefi'] : null}}</td>
            <td align="center"  bgcolor="#333333"  style="border-bottom: 1px solid #000;"></td>
            <td align="center"  colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000; ">&nbsp; {{ isset($vaccination) ? $vaccination['tdAefi'] : null}}</td>
        </tr>
        </tbody>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
        <tbody>
        <tr>
            <td colspan="25" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-left: none;">@= ;d'bfodf cfwfl/t gjlzz' tyf jfn/f]usf] PsLs[t Joj:yfkg sfoqm{d <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(CB-IMNCI)</span></td>
        </tr>
        <tr>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">@ dlxgf eGbf sd pd]/sf aRrf</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf la/fdL</td>
            <td colspan="8" align="center" bgcolor="#EEEEEE" class="nepali-font">alus{/0f</td>
            <td colspan="9" align="center" bgcolor="#EEEEEE" class="nepali-font">pkrf/</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">/]km/</td>
            <td rowspan="3" bgcolor="#EEEEEE" align="center" class="nepali-font">kmnf]ck</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font"
                style="border-right: 1px solid #000;">d[To'</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">uDeL/ ;+qmd0f</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">:yflgo ;+qmd0f</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">s8f sdn lkQ -lh08;_</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">sd tf}n÷:tgkfg ;lDaGw ;d:of</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">Pdf]lS;l;lng</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">PlDkl;lng</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">h]G6fdfOl;g <small>-luDe/ ;+qmd0f ePsf dfq_</small></td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">sf]l6«d lk=</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo PG6Lafof]l6s</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf] 8f]h</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'/f 8f]h</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&#8804;</span> @* lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">@(–%( lbg</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td align="center" bgcolor="#EEEEEE">16</td>
            <td align="center" bgcolor="#EEEEEE">17</td>
            <td align="center" bgcolor="#EEEEEE">18</td>
            <td align="center" bgcolor="#EEEEEE">19</td>
            <td align="center" bgcolor="#EEEEEE">20</td>
            <td align="center" bgcolor="#EEEEEE">21</td>
            <td align="center" bgcolor="#EEEEEE">22</td>
            <td align="center" bgcolor="#EEEEEE">23</td>
            <td align="center" bgcolor="#EEEEEE">24</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">25</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;" class="nepali-font">ufpF3/ lSnlgs</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td bgcolor="#333333" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
        <tbody>
        <tr>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font" style="width: 5.5%;">@ b]lv %( dlxgf ;Ddsf aRrf</td>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf la/fdL</td>
            <td colspan="17" align="center" bgcolor="#EEEEEE" class="nepali-font">alu{s/0f</td>
            <td colspan="7" align="center" bgcolor="#EEEEEE" class="nepali-font">pkrf/</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">/]km/</td>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">kmnf] ck</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">d[To'</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">d[To':jf;k|:jf;</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" class="nepali-font">emf8fkvfnf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cf}nf]</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">w]/} s8f Hj/f]hGo /f]u/s8f hl6n cf}nf]</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">bfb'/f</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">sfgsf] ;d:of</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">Hj/f]</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">s8f s'kf]if0f</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">/Qm– cNktf</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">s8f÷lgdf]lgof ePsf dWo]</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cf]= cf/=P;= / lh+s rSsL</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cfO= eL= ˆn'O8</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">h'sfsf] cf}ifwL</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">le6fldg P</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">:jf; k|:jf;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">emf8f kvfnf</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">:jf; k|:jf;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">emf8f kvfnf</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lgdf]lgof gePsf] ?3fvf]sL</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lgdf]lgof</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">s8f lgdf]lgof÷ w]/} s8f /f]u</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">hnljof]hgsf] alu{s/0f</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">bL3{ emf8f kvfnf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cf‘pm/ /ut</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">kmflN ;k]/d cf}nf]</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">kmflN ;k]/d gePsf] cf}nf]</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">Pdf]lS ;l;lng</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">sf]l6«d lk=</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo PG6La fof]l6s</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hn ljof]hg gePsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">s]lx hn ljof]hg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">s8f hn ljof]hg</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td align="center" bgcolor="#EEEEEE">16</td>
            <td align="center" bgcolor="#EEEEEE">17</td>
            <td align="center" bgcolor="#EEEEEE">18</td>
            <td align="center" bgcolor="#EEEEEE">19</td>
            <td align="center" bgcolor="#EEEEEE">20</td>
            <td align="center" bgcolor="#EEEEEE">21</td>
            <td align="center" bgcolor="#EEEEEE">22</td>
            <td align="center" bgcolor="#EEEEEE">23</td>
            <td align="center" bgcolor="#EEEEEE">24</td>
            <td align="center" bgcolor="#EEEEEE">25</td>
            <td align="center" bgcolor="#EEEEEE">26</td>
            <td align="center" bgcolor="#EEEEEE">27</td>
            <td align="center" bgcolor="#EEEEEE">28</td>
            <td align="center" bgcolor="#EEEEEE">29</td>
            <td align="center" bgcolor="#EEEEEE">30</td>
            <td align="center" bgcolor="#EEEEEE">31</td>
            <td align="center" bgcolor="#EEEEEE">32</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">33</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['totalPatientHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['noPnemoniaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['pnemoniaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['hardPnemoniaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['dehydrationHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['some_dehydrationHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['extreme_dehydrationHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['long_diarrheaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['bloodHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['malariaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['no_malariaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['auloHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['daduraHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['ear_problemHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['feverHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['malnutritionHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['anemiaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['otherHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['amoxicillinHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['kotrimHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['antibioticHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['orsHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['iv_fluidHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['juka_medicineHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['vitamin_aHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['breathingHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['diarrheaHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['otherReferHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['followUpHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['deathbreathingHealth'] : null }}</td>
            <td align="center" >&nbsp;{{ isset($imnc) ? $imnc['deathdiarrheaHealth'] : null }}</td>
            <td  align="center" style="border-right: 1px solid #000;">&nbsp;{{ isset($imnc) ? $imnc['deathotherHealth'] : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">ufpF3/ lSnlgs</td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['totalPatientClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['noPnemoniaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['pnemoniaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['hardPnemoniaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['dehydrationClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['some_dehydrationClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['extreme_dehydrationClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['long_diarrheaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['bloodClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['malariaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['no_malariaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['auloClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['daduraClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['ear_problemClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['feverClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['malnutritionClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['anemiaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['otherClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['amoxicillinClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['kotrimClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['antibioticClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['orsClinic'] : null  }} </td>
            <td bgcolor="#333333" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['juka_medicineClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['vitamin_aClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['breathingClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['diarrheaClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['otherReferClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['followUpClinic'] : null  }} </td>
            <td  align="center" style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['deathbreathingClinic'] : null  }} </td>
            <td align="center"  style="border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['deathdiarrheaClinic'] : null  }} </td>
            <td  align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp; {{isset($imnc) ? $imnc['deathotherClinic'] : null  }} </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
        <tbody>
        <tr>
            <td colspan="20" align="center" bgcolor="#EEEEEE" style="border-left: none;" class="nepali-font">#= kf]if0f sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">o; dlxgfdf a[lWb cg'udgsf nflu bt{f ePsf gofF aRrf</td>
            <td colspan="6" align="center" bgcolor="#EEEEEE" class="nepali-font">a[lWb cg'udg ul/Psf afnaflnsfx?sf] kf]if0f l:ylt -gofF tyf bf]xf]o{fO cfPsf_</td>
            <td rowspan="6" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cfO/g÷h'sfsf] cf}ifwL kfPsf uej{tL dlxnf</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cfO/g÷le6fldg P kfPsf ;'Ts]/L dlxnf</td>
            <td rowspan="6" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">le6fldg P÷h'sfsf] cf}ifwL kfPsf % aif{eGbf sd pd]/sf afnaflnsf -cw{ aflif{s_</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font"style="border-right: 1px solid #000;">h'sfsf] cf}ifwL kfPsf 5fqf/5fqx? -cw{ aflif{s_</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">)–!! dlxgf</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">!@–@# dlxgf</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;fdfGo</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hf]lvd</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">clt hf]lvd</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;fdfGo</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hf]lvd</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">clt hf]lvd</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf] k6s cfO/g rSsL</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">!*) cfO/g rSsL</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">h'sfsf] cf}ifwL</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">$% cfO/g rSsL</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">le6fldg P</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">le6fldg P</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">h'sfsf] cf}ifwL</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">1</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">2</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">3</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">4</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">5</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">6</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">7</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">8</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">^–!! d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">!@–%( d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">5fqf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">5fq</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" class="nepali-font" colspan="" align="center">klxnf] k6s e]6</td>
            <td></td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstNormalZero'] : null }}</td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstRiskZero'] : null}}</td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstExtremeRiskZero'] : null}}</td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstNormalTweleve'] : null}}</td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstRiskTwelve'] : null}}</td>
            <td align="center">&nbsp;{{ isset($nutrition) ? $nutrition['firstExtremeRiskTwelve'] : null}}</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>

            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">5</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;" colspan="1" align="center">bf]xf]o{fO cfPsf]</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondNormalZero'] : null  }}</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondRiskZero'] : null }}</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondExtremeRiskZero'] : null }}</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondNormalTweleve'] : null }}</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondRiskTwelve'] : null }}</td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['secondExtremeRiskTwelve'] : null }}</td>

            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['iron_first'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['onSevenTimeIron'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['jukaMedicine'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['jukaMedicineFive'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['fourtyFiveIron'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['vitaminA'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['vitaminASixToEleven'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['vitaminATweleve'] : null }} </td>
            <td style="border-bottom: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['male'] : null }} </td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;"  align="center">&nbsp;{{ isset($nutrition) ? $nutrition['female'] : null }} </td>
        </tr>
        </tbody>
    </table>
</div>
<!--End Fourth Page-->


<!-- Fifth page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="13" align="center" bgcolor="#EEEEEE" class="nepali-font">lz3| s'kf]if0fsf] PsLs[t Joj:yfkg <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(IMAM)</span> sfoqm{d</td>
            <td rowspan="8" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="7" align="center" bgcolor="#EEEEEE" class="nepali-font">lzz' tyf afn\osflng kf]if0f / afn eL6f k|awg{ sfoqm{d</td>
            <td rowspan="7" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">lzz' tyf afNosflng kf]if0f</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">pd]/ ;d"x</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ln+u</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ut dlxgfsf] cGTo ;Ddsf aRrf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">eg{f ul/Psf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">/]km/ eO{ cfPsf</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" class="nepali-font">l8:rfh{ ePsf]</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">:yfgfGt/0f eO cGoq uPsf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">dlxgfsf] cGTod hDdf aRrf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">pd]/ ;d"x -dlxgfdf_</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">klxnf] k6s</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">bf];|f] k6s</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">t]>f] k6s</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">:tgkfg dfq} u/fPsf</td>
            <td bgcolor="#EEEEEE" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">gofF egf{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'gM egf{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">lgsf] ePsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d[To" ePsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">l8kmN6/ ePsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">lgsf] gePsf]</td>
            <td align="center" bgcolor="#EEEEEE" class= "nepali-font">c:ktfndf k7fPsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=:jf=:j=;]=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=:jf=:j=;]=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=:jf=:j=;]=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">;dod} yk cfxf/ v'jfpg z'? u/]sf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Fortified Flour
                Distribution</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> ^ dlxgf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" class="nepali-font">^–!!</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" bgcolor="#333333">&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">aRrf ;+Vof</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" class="nepali-font">!@–!&</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#333333">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">^–%( dlxgf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" class="nepali-font">!*–@#</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ue{jlt dlxnf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">k'¿if</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="7" align="center" style="border: none; border-top: 1px solid #000;">&nbsp; </td>
            <td style="border: none; width: 1%;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">
                ;'Ts]/L dlxnf</td>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
        <tbody>
        <tr>
            <td colspan="15" align="center" bgcolor="#EEEEEE" style="border-left: none;" class="nepali-font">$= dlxnf :jf:Yo :jo+ ;]ljsf sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">;'/lIft dft[Tj/ kl/jf/ lgof]hg sfoqm{d</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">OsfO{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;+Vof</td>
            <td rowspan="20" style="border: none; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">;d'bfodf cfwfl/t gjlzz' tyf afn/f]usf] PsLs[t Joj:yfkg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;+Vof</td>
            <td rowspan="16" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">lz3| s'kf]if0fsf] PsLs[t Joj:yfkg <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(IMAM)</span> sfoqm{d</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">;+Vof</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">2</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">cfdf ;d'xsf] a}7s a;]sf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k6s</td>
            <td>&nbsp;</td>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">@ dlxgf eGbf sd</td>
            <td rowspan="2" bgcolor="#EEEEEE" class="nepali-font">la/fdL aRrf</td>
            <td bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≤</span> @* lbg</td>
            <td>&nbsp;</td>
            <td rowspan="4" bgcolor="#EEEEEE" class="nepali-font">Pd=o'=P=;L= 5gf}6</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">xl/of] -x|i6k'i6_</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">uej{tL dlxnfnfO{ e]6 u/]sf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">kx]nf] -dWod lz3| s'kf]if0f_</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">uej{tL dlxnfnfO{ cfO{/g rSsL ljt/0f</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td rowspan="2" bgcolor="#EEEEEE" class="nepali-font">sf]l6«daf6 pkrf/ u/L :jf:Yo ;+:yfdf k|]if0f</td>
            <td bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≤</span> @* lbg</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">/ftf]M -s8f lz3| s'kf]if0f_</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="12" bgcolor="#EEEEEE" class="nepali-font">3/df k|;'lt</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">dft[ ;'/If rSsL vfPsf] ;'lglZrt</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">km's]gf;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">hLljt hGd ePsf lzz'</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td rowspan="7" align="center" bgcolor="#EEEEEE" class="nepali-font">@–%( dlxgf</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">:jf;k|:jf; /f]usf hDdf la/fdL</td>
            <td>&nbsp;</td>
            <td rowspan="3" bgcolor="#EEEEEE" class="nepali-font">3/w'/L e]6 / cfg'udg</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">/ftf]M s8f lz3| s'kf]lift aRrf pkrf/ kl5 lgsf] ePsf]</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">d[t hGd ePsf lzz'</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">lgdf]lgof gePsf la/fdL</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">/ftf]M s8f lz3| s'kf]lift aRrf pkrf/ ul//xFbf klg tf}n j[lWb gePsf]</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">lg;f:;LPsf] gjhft lzz'sf] Joj:yfkg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">sf]l6«daf6 pkrf/ u/LPsf la/fdL</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">/ftf]M s8f lz3| s'kf]lift aRrf pkrf/ ub{f ub{} :jf:Yo ;+:yf hfg 5f8]sf]</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">hGdg] lalQs} cfdfsf] 5ftL;+u 6f;]/ /fv]sf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">emf8fkvfnf ePsf la/fdL</td>
            <td>&nbsp;</td>
            <td colspan="4" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">gfeLdf gfeLdnd nufOPsf lzz'</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">cf]=cf/=P;= / lh+s rSsLaf6 pkrf/</td>
            <td>&nbsp;</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">%= hg;+Vof Joj:yfkg sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">hGd]sf] ! 306fleq :tgkfg u/fPsf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">cf]=cf/=P;= vr{ -Kofs]6_</td>
            <td>&nbsp;</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">ljBfno :t/df lszf]/lszf]/L nlIft ;fyL lzIff sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">sd hGd tf}n ePsf lzz' -!=% – <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> @.% s]=hL=_</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">lh+s rSsL vr{ -rSsL_</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">sfoqm{d nfu' ePsf] ljBfno ;+Vof</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($population) ? $population['applied'] : null }}</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">w]/} sd hGd tf}n ePsf lzz' -<span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> !=% s]=hL=_</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">d[To'</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≤</span> @* lbg</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">k|ltj]bg ug{] ljBfno ;+Vof</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($population) ? $population['report'] : null }}</td>
        </tr>
        <tr>
            <td rowspan="3" bgcolor="#EEEEEE" class="nepali-font">gjhft lzz' / ;'Ts]/L dlxnfnfO{ hfFr e]6 u/]sf]</td>
            <td bgcolor="#EEEEEE" class="nepali-font">hGd]sf] @$ 306f leq</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">@(–%( lbg</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="2" bgcolor="#EEEEEE" class="nepali-font">k/fdz{ kfPsf hDdf ;+Vof</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">5fqf</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($population) ? $population['male'] : null }}</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" class="nepali-font">hGd]sf] t];|f] lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">@–%( lbg</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">5fq</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($population) ? $population['female'] : null }}</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" class="nepali-font">hGd]sf] ;ftf} lbg</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="4" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">;'kl/j]If0f ul/Psf ljBfno ;+Vof</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($population) ? $population['supervised'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">le6fldg ljt/0f ul/Psf] ;'Ts]/L dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;" class="nepali-font">dft[ d[To' -:jf:Yo ;+:yf afx]s_</td>
            <td bgcolor="#EEEEEE" class="nepali-font">uefj{:yf</td>
            <td>&nbsp;</td>
            <td rowspan="3" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">;+rfngdf /x]sf ;"rgf s]G› ;+Vof</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($population) ? $population['operation_school'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">s08d ljt/0f u/]sf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">uf]6f</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">k|z'tL cj:yf</td>
            <td>&nbsp;</td>
            <td colspan="4" rowspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">lkN; ljt/0f u/]sf]</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;" class="nepali-font">;fO{sn</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">;'Ts]/L cj:yf</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
{{--<!--End Fifth Page-->--}}

<!-- Sixth page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" class="nepali-font">^= ;'/lIft dft[Tj sfoqm{d</td>
            <td rowspan="16" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="9" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">&= kl/jf/ lgof]hg sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">uej{tL ;]jf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> @) aif{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> @) aif{</td>
            <td rowspan="4" style="border: none; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Obstetric Complications</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Number</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">c:yfoL ;fwg</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">gofF k|of]ustf{</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">xfn cfkgfO{ /x]sf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">;]jfdf lgoldt gePsf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">;fwg ljt/0f</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">klxnf] k6s uej{tL hfFr u/]sf dlxnf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Ectopic pregnancy</td>
            <td align="center" bgcolor="#EEEEEE">O00</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','ectopic_regnancy')->count() : null  }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> @) aif{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> @) aif{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">OsfO{</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">kl/df0f</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">rf}yf] dlxgfdf uej{tL hfFr u/]sf dlxnf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Abortion complication</td>
            <td align="center" bgcolor="#EEEEEE">O08</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','abortion_complication')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">s08d</td>
            <td colspan="4" bgcolor="#333333">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">uf]6f</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($familyPlanning['service_count']) ? $familyPlanning['service_count']['condom'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">k|f]6f]sn cg';f/ $ k6s uej{tL hfFr</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Preg.-induced hypertension</td>
            <td align="center" bgcolor="#EEEEEE">O13</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','hypertension')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">lkN;</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['less_than_twenty']) ? $familyPlanning['less_than_twenty']['pills'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['greater_than_twenty']) ? $familyPlanning['greater_than_twenty']['pills'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_using']) ? $familyPlanning['service_using']['pills'] : null}}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_continue']) ? $familyPlanning['service_continue']['pills'] : null }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;fOsn</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($familyPlanning['service_count']) ? $familyPlanning['service_count']['pills'] : null }}</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Severe/Pre-eclampsia</td>
            <td align="center" bgcolor="#EEEEEE">O14</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','severe-pre-eclampsia')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">l8kf]</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['less_than_twenty']) ? $familyPlanning['less_than_twenty']['depo'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['greater_than_twenty']) ? $familyPlanning['greater_than_twenty']['depo'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_using']) ? $familyPlanning['service_using']['depo'] : null}}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_continue']) ? $familyPlanning['service_continue']['depo'] : null }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">l8kf]</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($familyPlanning['service_count']) ? $familyPlanning['service_count']['depo'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">k|;'lt ;]jf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf= ;+=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">3/</td>
            <td rowspan="3" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Eclampsia</td>
            <td align="center" bgcolor="#EEEEEE">O15</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','eclampsia')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">cfO{= o'= ;L= 8L=</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['less_than_twenty']) ? $familyPlanning['less_than_twenty']['iucd'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['greater_than_twenty']) ? $familyPlanning['greater_than_twenty']['iucd'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_using']) ? $familyPlanning['service_using']['iucd'] : null}}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_continue']) ? $familyPlanning['service_continue']['iucd'] : null }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;]6</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($familyPlanning['service_count']) ? $familyPlanning['service_count']['iucd'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">bIf k|;'ltsdL{af6</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Hyperemesis grivadarum</td>
            <td align="center" bgcolor="#EEEEEE">O21.0</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','hyp-gravidarum')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">ODKnfG6</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['less_than_twenty']) ? $familyPlanning['less_than_twenty']['Implant'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['greater_than_twenty']) ? $familyPlanning['greater_than_twenty']['Implant'] : null }}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_using']) ? $familyPlanning['service_using']['Implant'] : null}}</td>
            <td align="center">&nbsp;{{ isset($familyPlanning['service_continue']) ? $familyPlanning['service_continue']['Implant'] : null }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;]6</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp;{{ isset($familyPlanning['service_count']) ? $familyPlanning['service_count']['Implant'] : null }}</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">cGo :jf:sdL{af6</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Antepartum haemorrhage</td>
            <td align="center" bgcolor="#EEEEEE">O46</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','aph')->count() : null  }}</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Prolonged labour</td>
            <td align="center" bgcolor="#EEEEEE">O63</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','prolonged_labour')->count() : null  }}</td>
            <td colspan="3" rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">aGWofs/0f</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">gofF k|of]ustf{</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font"
                style="border-right: 1px solid #000;">xfn cfkgfO{/x]sf</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">k|;'ltsf] lsl;d</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">lk|h]G6]zg</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Obstructed Labor</td>
            <td align="center" bgcolor="#EEEEEE">O64-O66</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','obstructed_labour')->count() : null  }}</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lzlj/</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">Cephalic</td>
            <td align="center" bgcolor="#EEEEEE">Shoulder</td>
            <td align="center" bgcolor="#EEEEEE">Breech</td>
            <td colspan="3" bgcolor="#EEEEEE">Ruptured uterus</td>
            <td align="center" bgcolor="#EEEEEE">S37.6</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','ruptured_uterus')->count() : null  }}</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">k'¿if</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">;fdfGo <small style="font-family: 'Roboto', sans-serif; font-size: 8px;">(Spontaneous)</small></td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Cephalic')->where('delivery_type','=','NVD')->count() : null }}</td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Shoulder')->where('delivery_type','=','NVD')->count() : null }}</td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Breech')->where('delivery_type','=','NVD')->count() : null }}</td>
            <td colspan="3" bgcolor="#EEEEEE">Postpartum haemorrhage</td>
            <td align="center" bgcolor="#EEEEEE">O72</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','pph')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">;/sf/L</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">Eofs'd/kmf]/;]k</td>
            <td align="center">&nbsp;{{ (isset($mch['delivery_info']) && $mch['force_cephalic']) ? $mch['force_cephalic'] : null }}</td>
            <td align="center">&nbsp;{{ (isset($mch['delivery_info']) && $mch['force_shoulder'] ) ? $mch['force_shoulder'] : null }}</td>
            <td align="center">&nbsp;{{ (isset($mch['delivery_info']) && $mch['force_breeche'] ) ? $mch['force_breeche']: null }}</td>
            <td colspan="3" bgcolor="#EEEEEE">Retained placenta</td>
            <td align="center" bgcolor="#EEEEEE">O73</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','retained_placenta')->count() : null  }}</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">u}/ ;/sf/L</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">;Nolqmof</td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Cephalic')->where('delivery_type','=','surgery')->count() : null }}</td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Shoulder')->where('delivery_type','=','surgery')->count() : null }}</td>
            <td align="center">&nbsp;{{ isset($mch['delivery_info']) ? $mch['delivery_info']->where('presentation','=','Breech')->where('delivery_type','=','surgery')->count() : null }}</td>
            <td colspan="3" bgcolor="#EEEEEE">Pueperal sepsis</td>
            <td align="center" bgcolor="#EEEEEE">O85</td>
            <td align="center">&nbsp;{{ isset($mch['complications']) ? $mch['complications']->where('obstetric_complication','=','puerperal_sepsis')->count() : null  }}</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Other complications</td>
            <td align="center" bgcolor="#EEEEEE">O75</td>
            <td align="center">&nbsp;{{ isset($mch['other_complication']) ? $mch['other_complication'] : null  }}</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">;'Ts]/L kZrft k= lg= ;]jf ckgfPsf -;'Ts]/L ePsf] $* 306f leq/_</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cfO{= o'= ;L= 8L=</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ODKnfG6</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">
                6\o'a]S6f]dL</td>
        </tr>
        <tr>
            <td colspan="2" align="center" rowspan="2" bgcolor="#EEEEEE" class="nepali-font">k|;'ltsf] kl/0ffd</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">Psn aRrf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ax' aRrf</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">h'DNofxf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> ltDNofxf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yfdf ePsf] dft[ d[To'</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">uefj{:yf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k|;"lt&nbsp;cj:yf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;'Ts]/L&nbsp;cj:yf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">:jf:Yo ;+:yfdf ePqmf] gjlzz' d[To'</td>
            <td rowspan="2" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font" height="22">cfdfx?sf] ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">*= ufpF3/ lSnlgs / ;d'bfo :t/ :jf:Yo sfoqm{d</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf hLljt hGd</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">k|fylds pkrf/ u/]sf</td>
            <td>&nbsp;</td>
            <td rowspan="12" style="border: none; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">cfO/g rSsL ljt/0f</td>
            <td bgcolor="#EEEEEE" class="nepali-font">gofF uej{tL</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cfdf ;'/Iff sfoqm{d</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf ;+Vof</td>
            <td rowspan="12">&nbsp;</td>
            <td rowspan="6" align="center" bgcolor="#EEEEEE" class="nepali-font">tf}n cg'udg u/]sf</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">)–!! dlxgf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;fdfGo</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">bf]xf]ofO{ cfPsf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">kfpg'kg{]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">kfPsf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hf]lvd</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">;'Ts]/L dlxnf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">hGd tf}n</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">lhljt hGd</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">k|f]T;fxg</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">oftfoft vr{</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">clt hf]lvd</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">le6fldg P kfPsf ;'Ts]/L dlxnf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf ;+Vof</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">lg;fl;Psf]</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">lasnf+u</td>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">uej{tL pTk|]/0ff</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE" class="nepali-font">!@–@# dlxgf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;fdfGo</td>
            <td>&nbsp;</td>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">k= lg= ;fwg ljt/0f</td>
            <td bgcolor="#EEEEEE" class="nepali-font">sG8d</td>
            <td bgcolor="#EEEEEE" class="nepali-font">uf]6f</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">;fdfGo -<span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> @=% s]=hL=_</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hf]lvd</td>
            <td>&nbsp;</td>
            <td rowspan="2" bgcolor="#EEEEEE" class="nepali-font">lkN;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">sd -!=% – <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> @=% s]=hL=_</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">;'/lIft uek{tg ;]jf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d]l8sn</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">;lh{sn</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">clt hf]lvd</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">;fOsn</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE" class="nepali-font">w]/} sd -<span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> !=% s]=hL=_</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">uek{tg ;]jf kfPsf hDdf dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> @) aif{</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">ue{ hfFr u/]sf dlxnf</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">l8kf]</td>
            <td bgcolor="#EEEEEE" class="nepali-font">hgf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> @) aif{</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">;'Ts]/L hfFr u/]sf dlxnf</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">pkrf/df lgoldt gePsf la/fdLsf] vf]h u/]sf] ;+Vof -Ifo/f]u_</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">d[t hGd ;+Vof</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">gfeL dnd nufPsf]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">/Qm ;+rf/ ul/Psf</td>
            <td rowspan="3" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">uek{tg kZrft k= lg= ;fwg ckgfPsf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">5f]6f] cjlwsf]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">h'sfsf] cf}ifwL kfPsf uj{jlt</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">/Qm gd'gf ;+sng u/]sf] :nfO8 ;+Vof</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Fresh</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">nfdf] cjlwsf]</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">hGd]sf] ^ dlxgf;Dd :tgkfg dfq u/fPsf]</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">cfdf ;d'xsf] j}7sdf efu lnPsf]</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE">Macerated</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE" class="nepali-font">lkG6</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">uek{tg kZrft hl6ntf ePsf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">^ dlxgffkl5 :tgkfgsf ;fy} 7f];, cw7{f]; / g/d vfgf ;'? u/]sf</td>
            <td>&nbsp;</td>
            <td colspan="4" rowspan="4" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">PAC</span> ;]jf kfPsf</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" rowspan="3" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td rowspan="3" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">;'Ts]/L hfFr</td>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">@$ 306f leq</td>
            <td>&nbsp;</td>
            <td rowspan="2" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="5" rowspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td rowspan="2" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font" style="border-bottom: 1px solid #000;">k|f]6f]sn cg';f/ # k6s</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
<!--End Sixth Page-->

<!-- seventh page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" class="nepali-font">(= s'i7/f]u lgoGq0f sfoqm{d</td>
            <td rowspan="19" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">qm=;+=</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">gofF la/fdLsf] gfd</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">hft</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">pd]/</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lhNnf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">uf=lj=;=÷gu/kflnsf</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">jf8{ g+=</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">k|sf/</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font" style="border-right: 1px solid #000;">>]0fL</td>
        </tr>
        <tr>
            <td colspan="7" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">laj/0f</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">Pd= aL=</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lk=la=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">dlxnf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'¿if</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="5" align="center" bgcolor="#EEEEEE" class="nepali-font">s'n hDdf la/fdL ;+Vof</td>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">ut dlxgfsf] cGTodf hDdf la/fdL ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="10" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">slxn] klg klxn] bt{f gu/]sf gofF la/fdL <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(New case)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="10" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;" class="nepali-font">!)= cf}nf] lgoGq0f sfoqm{d</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">k'gM /f]u lan\emPsf la/fdL <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Relapsed cases)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">/Qm gd"gf ;+sng</td>
            <td rowspan="4" style="border: none; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">kl/If0f tyf glthf</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">Micros-copy</span> af6 dfq</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">RDT</span> af6 dfq</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">Micros-copy</span> / <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">RDT</span> b'a}af6</td>
            <td rowspan="4" style="border: none; border-top: 1px solid #000; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;" class="nepali-font">cf}nf]sf] pkrf/ kfPsf la/fdL ;+Vof</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">km]/L pkrf/ z'? u/]sf la/fdL <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Retreatment cases)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">2</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">:yfgfGt/0f eO{ cfPsf la/fdL <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Transferred in cases)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">ACD</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">kl/If0f</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">o; dlxgfdf pkrf/ lnPsf la/fdLx?</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">PCD</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">kf]h]l6e</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">uej{tL</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="4" align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf 36fO{Psf</td>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">lgb{]zg cg';f/ pkrf/ k'/f u/]sf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(RFT)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="4" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">:yfgfGt/0f eO{ cGoq uPsf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Transfer Out)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" class="nepali-font">cf}nf]sf] k|sf/</td>
            <td colspan="3" rowspan="2" align="center" class="nepali-font">alu{s/0f</td>
            <td colspan="2" align="center" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">&lt;</span> % aif{</td>
            <td colspan="2" align="center" style="border-right: 1px solid #000;" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">≥</span> % aif{</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">l8kmN6/ ePsf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Defaulter)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" class="nepali-font">dlxnf</td>
            <td align="center" class="nepali-font">k'¿if</td>
            <td align="center" class="nepali-font">dlxnf</td>
            <td align="center" class="nepali-font" style="border-right: 1px solid #000;">k'¿if</td>
        </tr>
        <tr>
            <td colspan="6" bgcolor="#EEEEEE" class="nepali-font">cGo 36fO{Psf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Other Deduction)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" align="center">1</td>
            <td colspan="3" align="center">2</td>
            <td align="center">3</td>
            <td align="center">4</td>
            <td align="center">5</td>
            <td align="center" style="border-right: 1px solid #000;">6</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">o; dlxgsf] cGTodf hDdf la/fdL ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">Plasmodium Vivax</span> -lk= eL=_</td>
            <td colspan="3" align="center" class="nepali-font">:yflgo</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">o; dlxgsf] cGTodf !$ aif{ d'lgsf hDdf la/fdL ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" align="center" class="nepali-font">cfofltt</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">gofF la/fdL dWo] :d]o/ hfFr u/]sf] ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">PlasmodiumFalciparum</span> -lk= eL=_</td>
            <td colspan="3" align="center" class="nepali-font">:yflgo</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">gofF la/fdLx?df :d]o/ hfFr]sf] dWo] ls6f0f' b]lvPsf] ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" align="center" class="nepali-font">cfofltt</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" bgcolor="#EEEEEE" class="nepali-font">gofF la/fdLx?dWo] !$ aif{ d'lgsf hDdf la/fdLsf] ;+Vof</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="2" align="center" class="nepali-font"><span style="font-family: 'Roboto', sans-serif; font-size: 11px;">Plasmodium Mixed</span> -lk=ldS;_</td>
            <td colspan="3" align="center" class="nepali-font">:yflgo</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="3" align="center" class="nepali-font">cfofltt</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">la/fdLsf] c;dytfs{f] >]0fL:la/fdLsf] k|sf/</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">>]0fL )</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">>]0fL !</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">>]0fL @</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">ghfFr]sf]</td>
            <td rowspan="6" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="2" rowspan="4" align="center" class="nepali-font">cf}nf] /f]uLsf] hDdf ;+Vof</td>
            <td colspan="4" class="nepali-font">;Defljt cf}nf] /f]uL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td colspan="4" class="nepali-font">lglZrt ul/Psf] ;fdfGo la/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td colspan="4" class="nepali-font">;Defljt l;ls:t lj/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">gofF la/fdLx¿</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="nepali-font">lglZrt ul/Psf] l;ls:t lj/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">!$ aif{d'lgsf gofF la/fdLx?</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="4" align="center" class="nepali-font">pkrf/ ul/Psf cf}nf] /f]uLsf] hDdf ;+Vof</td>
            <td colspan="4" class="nepali-font">;Defljt cf}nf] /f]uL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE" class="nepali-font">lgb{]zg cg';f/ pkrf/ k'/f u/]sf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(RFT)</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="nepali-font">lglZrt ul/Psf] ;fdfGo la/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="4" class="nepali-font">;Defljt l;ls:t lj/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">sf]x6{ k|ltj]bg -gofF bt{f ul/Psf la/fdL_</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">hDdf btf{ ePsf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">lgb{]zg cg';f/ pkrf/ k'/f u/]sf <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(RFT)</span></td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">l8kmn\6/ ePsf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">cGo 36fO{Psf</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" class="nepali-font">xfn pkrf/df /x]sf</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="4" class="nepali-font">lglZrt ul/Psf] l;ls:t lj/fdL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font" height="18">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">d=</td>
            <td align="center" bgcolor="#EEEEEE" class="nepali-font">k'=</td>
            <td colspan="2" rowspan="3" class="nepali-font">d[To' ;+Vof</td>
            <td colspan="4" class="nepali-font">;Defljt cf}nf] /f]uL</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td colspan="3" rowspan="2" class="nepali-font">lglZrt ul/Psf cf}nf] /f]uL</td>
            <td class="nepali-font">lk= eL=</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" class="nepali-font">Pd= aL= la/fdL -!* dlxgfkl5_</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="nepali-font">lk= Pkm=</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;" class="nepali-font">kL= aL= la/fdL -( dlxgfkl5_</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="10" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
<!--End seventh Page-->

<!-- Eight page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="27" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;" class="nepali-font">!!= Ifo/f]u lgoGq0f sfoqm{d <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Tuberculosis Control Program)</span></td>
        </tr>
        <tr>
            <td colspan="27" align="center" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE">Case Registration (1)</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">New</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Relapse</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Treatment After Failure</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Treatment After Loss to Follow-up</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Other Previously Treated</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Previous Treatment History Unknown</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Transfer In</td>
            <td rowspan="6" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="4" rowspan="2" align="center" bgcolor="#EEEEEE">Registration by Treatment Category [3]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Adult</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Child (0-14 Years)</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">Cat I</td>
            <td align="center" bgcolor="#EEEEEE">Cat II</td>
            <td align="center" bgcolor="#EEEEEE">Cat I</td>
            <td align="center" bgcolor="#EEEEEE">Cat II</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Cat III</td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">6</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE">Pulmonary (BC)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Sex of Patient</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Female</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE">Pulmonary (CD)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="2" bgcolor="#333333">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Male</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" bgcolor="#EEEEEE">Extra Pulmonay (BC or CD)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="17" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE">At the Time of TB Diagnosis [41]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Patients Tested for HIV</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">With Known HIV Status
            </td>
        </tr>
        <tr>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Registration(BC or CD) [2]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">0-4 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">5-14 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">15-24 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">25-34 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">35-44 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">45-54 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">55-64 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">≥ 65 Years</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE">1</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">2</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">3</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" height="15">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE">Sex of Patient</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Female</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td align="center" bgcolor="#EEEEEE">16</td>
            <td align="center" bgcolor="#EEEEEE">17</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Male</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">All New</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">All Relapse</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" rowspan="3" align="center" bgcolor="#EEEEEE">TB-HIV Activities:All TB Cases [42]</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">HIV +ve TB Patients</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">HIV +ve TB Patients on
            </td>
        </tr>
        <tr>
            <td colspan="17" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">ART</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">CPT</td>
        </tr>
        <tr>
            <td colspan="7" rowspan="2" align="center" bgcolor="#EEEEEE">Private Sector and Community Involvement in
                Referral/Diagnosis [5]</td>
            <td colspan="2" bgcolor="#EEEEEE">PBC (New)</td>
            <td colspan="2" bgcolor="#EEEEEE">PBC (Relapse)</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">PBC <small>(Excl New &amp; Relapse)</small></td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">PCD (All)</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">EP (All)</td>
            <td rowspan="5" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">M</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" height="15">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">7</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="3" align="center" bgcolor="#EEEEEE">No of TB Patients</td>
            <td colspan="4" bgcolor="#EEEEEE">Reffered by Community</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">During this Month</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#EEEEEE">Referred/Diagnosed by Private HF</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">Till Date (Cumulative)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#EEEEEE">Diagnosed by Contact Tracing</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="17" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="9" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Sputum Smare
                Examination Result by Microscopy [6]</td>
        </tr>
        <tr>
            <td colspan="5" rowspan="2" align="center" bgcolor="#EEEEEE">Sputum Conversion (Bacteriologically Confirmed
                Cases) [8]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">No of Cases Registered</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Negative</td>
            <td colspan="2" bgcolor="#EEEEEE">Positive</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Died</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Lost to Follow Up</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Not Evaluated</td>
            <td rowspan="9" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td rowspan="3" align="center" bgcolor="#EEEEEE">Sex</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Presumptive TB Case Examined (Persons)</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">Smear Examination</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Follow -Up
                Case (Slides)</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Slides A</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Slides B</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE">-ve</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE">-ve</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE">-ve</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">-ve</td>
        </tr>
        <tr>
            <td colspan="5">New</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">9</td>
        </tr>
        <tr>
            <td colspan="5">Relapse</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">Treatment After Failure</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">Treatment After Lost to Follow-up</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">Others Previously Treated</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="9" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Gene-Xpert Examination
                Result [7]</td>
        </tr>
        <tr>
            <td colspan="5">Previous Treatment History Unknown</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Sex</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">MTB Detected</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">MTB Not Detected</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Invalid /Error/No result</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Test In-determinate
            </td>
        </tr>
        <tr>
            <td colspan="17" rowspan="3" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td rowspan="3" style="border: none;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">RIF Sensitive</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">RIF Resistant</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">M</td>
            <td colspan="2" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="2" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="2" style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
<!--End Eight Page-->

<!-- Nine page -->
<div class="wrapper"  style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="17" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">11. Tubercluosis Control Program</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="2" align="center" bgcolor="#EEEEEE">Treatment Outcome [9]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">No of Cases Registered</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Cured</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Completed</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Failure</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Died</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Lost to Follow Up</td>
            <td colspan="2" bgcolor="#EEEEEE" style="border-right: 1px solid #000">Not Evaluated*</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">M</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">16</td>
        </tr>
        <tr>
            <td rowspan="7" align="center">PBC</td>
            <td colspan="2">New</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Relapse</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Treatment After Failure</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Treatment After Lost to Follow-up</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Others Previously Treated</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Previous Treatment History Unknown</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">HIV +ve, All Types</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="5" align="center">PCD &amp; EP</td>
            <td rowspan="2">New</td>
            <td>PCD</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td>EP (BC or CD)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2">Others</td>
            <td>PCD</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td>EP (BC or CD)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">HIV +ve, All Types</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="17" style="border: none; border-top: 1px solid #000;"><em>* Due to Transfer Out &amp; moved to
                    second line treatment register</em></td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Practical Approach to Lung Health (PAL) [5]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Sex</td>
            <td rowspan="13" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="14" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000" class="nepali-font">!@= sfnfhf/ lgoGq0f sfoqm{d <span style="font-family: 'Roboto', sans-serif; font-size: 11px;">(Kala-azar Control
            Programme)</span></td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td colspan="14" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE">Patient Type</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">Age/Sex</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">Method of Diagnosis</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Treated With</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">No of Deaths</td>
        </tr>
        <tr>
            <td>A</td>
            <td>Total no of OPD Visits in the Month</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">&lt; 5 Years</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">≥ 5 Years</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">RK-39</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">BM</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">SP</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Other</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">L, A/ M*</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Other</td>
        </tr>
        <tr>
            <td>B</td>
            <td>Total Respiratory cases (TRC) Among Total ODP Visits</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">M</td>
        </tr>
        <tr>
            <td>B1</td>
            <td>Received Antibiotics Among TRC</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">13</td>
        </tr>
        <tr>
            <td>B2</td>
            <td>TB Suspects Among TRC</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Indi-genous</td>
            <td bgcolor="#EEEEEE">Within District</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td>B21</td>
            <td>Sputum Examination (S/C/X) Among TB Suspected</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td bgcolor="#EEEEEE">Outside District</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td>B211</td>
            <td>Bacteriologically +ve PTB Among Sputum Examined</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#EEEEEE">Foreigner</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td>C</td>
            <td>Smokers Identified Among CRD (ACT) Cases*</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="14" style="border: none; border-top: 1px solid #000;"><em>* Liposomal Amphotericin B/
                    Miltefosine</em></td>
        </tr>
        <tr>
            <td>C1</td>
            <td>Tobacco Cessation Counseled Among Smokers Identified</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="14" rowspan="4" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td>C11</td>
            <td>Smokers Who Commit to Quit After Counseling</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>C12</td>
            <td>Smokers Who Quitted Smoking After Counseling</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="border: none; border-top: 1px solid #000;"><em>CRD = Chronic Respiratory Disease, ACT =
                    Asthma, COPD, TB</em></td>
            <td style="border: none;">&nbsp;</td>
        </tr>
        </tbody>
    </table>


</div>
<!--End Nine Page-->

<!-- Tenth page -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="21" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">3. DR Tuberculosis
                Control Program</td>
        </tr>
        <tr>
            <td colspan="21" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Drug Resistant (DR) TB Case Registration [1]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Sex</td>
            <td rowspan="13" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td colspan="5" rowspan="2" align="center" bgcolor="#EEEEEE">Sputum Conversion of DR TB Cases: No of months
                completed treatment [3]</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Regd. Cases</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Lost to Follow-up</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Died</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE">Not Evaluated</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Smear Result</td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Culture Result</td>
            <td rowspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">Ctmn &lt;10 C*</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td align="center" bgcolor="#EEEEEE">-ve</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE">NA</td>
            <td align="center" bgcolor="#EEEEEE">-ve</td>
            <td align="center" bgcolor="#EEEEEE">+ve</td>
            <td align="center" bgcolor="#EEEEEE">NA</td>
        </tr>
        <tr>
            <td colspan="2" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">12</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">New</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE">6 months(Regd. 12 months ago)</td>
            <td colspan="3" bgcolor="#EEEEEE">All confirmed RR/MDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Relapse</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">HIV-positive RR/MDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Treatment After Loss to Follow-up</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">All confirmed XDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Treatment After Failure (Cat I)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE">12 months (Regd. 16 months ago)</td>
            <td colspan="3" bgcolor="#EEEEEE">All confirmed RR/MDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Treatment After Failure (Cat II)</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">HIV-positive RR/MDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Transfer In</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3" bgcolor="#EEEEEE">All confirmed XDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="16" align="right" style="border: none; border-top: 1px solid #000;"><em>* Contamination &lt;10
                    colonies</em></td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">HIV +ve RR-TB/DR-TB cases</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="16" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">All confirmed XDR-TB cases</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="2" align="center" bgcolor="#EEEEEE">Treatment Outcome:DR TB Patient Type* [4]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">No of Cases Registered</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Cured</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Completed</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Failure</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Died</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Lost to Follow Up</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">Not Evaluated</td>
        </tr>
        <tr>
            <td colspan="2" bgcolor="#EEEEEE">DR TB Pa. Under Current Treatment</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE">M</td>
            <td align="center" bgcolor="#EEEEEE">F</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">M</td>
        </tr>
        <tr>
            <td colspan="4" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
            <td style="border: none;">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">1</td>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000">15</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="3" align="center" bgcolor="#EEEEEE">New Registered DR TB Cases [2]</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Sex</td>
            <td rowspan="11" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td rowspan="7" align="center">All confirmed RR/MDR-TB</td>
            <td>New</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td>Relapse</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td>Treatment after Loss to Follow-up</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="8" align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Age Group</td>
            <td align="center" bgcolor="#EEEEEE">0-4 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Treatment After Failure Cat I</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">5-14 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Treatment After Failure Cat II</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">15-24 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Transfer In</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">25-34 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Others</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">35-44 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">HIV-positive RR/MDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">45-54 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2">All confirmed XDR-TB</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">55-64 Years</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="16" style="border: none; border-top: 1px solid #000;"><em>* TB cases started on a second-line TB
                    drug regimen in calendar year: ___________________</em></td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">≥ 65 Years</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td colspan="16" style="border: none;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
<!--End Tenth Page-->

<!-- 15th page -->

<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">15. New Outpatient
                Morbidity (Including Under 5yrs Children) -- 1
            </td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td rowspan="33" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">A. Communicable, Immunizable</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">D. Other Communicable
                Diseases
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">001</td>
            <td align="center" bgcolor="#EEEEEE">B05.9</td>
            <td bgcolor="#EEEEEE">Measles</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B05.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B05.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">030</td>
            <td align="center" bgcolor="#EEEEEE">A30.9</td>
            <td bgcolor="#EEEEEE">Leprosy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A30.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A30.9')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">002</td>
            <td align="center" bgcolor="#EEEEEE">A36.9</td>
            <td bgcolor="#EEEEEE">Diptheria</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A36.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A36.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">031</td>
            <td align="center" bgcolor="#EEEEEE">G03.9</td>
            <td bgcolor="#EEEEEE">Meningitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G03.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G03.9')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">003</td>
            <td align="center" bgcolor="#EEEEEE">A37.9</td>
            <td bgcolor="#EEEEEE">Whooping Cough</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A37.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A37.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">E. HIV/STI</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">004</td>
            <td align="center" bgcolor="#EEEEEE">A33</td>
            <td bgcolor="#EEEEEE">Neonatal Tetanus</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A33')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A33')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">032</td>
            <td align="center" bgcolor="#EEEEEE">B20</td>
            <td bgcolor="#EEEEEE">HIV Infection</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B20')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B20')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">005</td>
            <td align="center" bgcolor="#EEEEEE">A35</td>
            <td bgcolor="#EEEEEE">Tetanus</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A35')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A35')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">033</td>
            <td align="center" bgcolor="#EEEEEE">A54</td>
            <td bgcolor="#EEEEEE">Urethral Discharge Syndrome (UDS) Gonococal</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A54')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A54')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">006</td>
            <td align="center" bgcolor="#EEEEEE">A16.9</td>
            <td bgcolor="#EEEEEE">Tuberculosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A16.9')->where('fldptsex','=','Female')->count() : null }}  </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A16.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">034</td>
            <td align="center" bgcolor="#EEEEEE">N49</td>
            <td bgcolor="#EEEEEE">Scrotal Swelling Syndrome (SSS)</td>
            <td bgcolor="#333333">&nbsp;</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N49')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">007</td>
            <td align="center" bgcolor="#EEEEEE">G83</td>
            <td bgcolor="#EEEEEE">Acute Flaccid Paralysis (AFP)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G83')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G83')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">035</td>
            <td align="center" bgcolor="#EEEEEE">N89.8</td>
            <td bgcolor="#EEEEEE">Vaginal Discharge Syndrome (VDS)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N89.8')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N89.8')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">008</td>
            <td align="center" bgcolor="#EEEEEE">B06.9</td>
            <td bgcolor="#EEEEEE">Rubella</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B06.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B06.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">036</td>
            <td align="center" bgcolor="#EEEEEE">N74</td>
            <td bgcolor="#EEEEEE">Lower Abdominal Pain Syndrome (LAPS)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N74')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N74')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">009</td>
            <td align="center" bgcolor="#EEEEEE">B26.9</td>
            <td bgcolor="#EEEEEE">Mumps</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B26.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B26.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">037</td>
            <td align="center" bgcolor="#EEEEEE">A54.3</td>
            <td bgcolor="#EEEEEE">Neonatal Conjuctive Syndrome (NCS)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A54.3')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A54.3')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">010</td>
            <td align="center" bgcolor="#EEEEEE">B01.9</td>
            <td bgcolor="#EEEEEE">Chicken Pox</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B01.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B01.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">038</td>
            <td align="center" bgcolor="#EEEEEE">N76.6</td>
            <td bgcolor="#EEEEEE">Genital User Disease Syndrome (GUDS) - female</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N76.6')->where('fldptsex','=','Female')->count() : null }}</td>
            <td bgcolor="#333333" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">011</td>
            <td align="center" bgcolor="#EEEEEE">B16.9</td>
            <td bgcolor="#EEEEEE">Hepatitis B</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B16.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B16.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">039</td>
            <td align="center" bgcolor="#EEEEEE">N50.8</td>
            <td bgcolor="#EEEEEE">Genital User Disease Syndrome (GUDS) - male</td>
            <td bgcolor="#333333">&nbsp;</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N50.8')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">B. Communicable, Vector Borne</td>
            <td align="center" bgcolor="#EEEEEE">040</td>
            <td align="center" bgcolor="#EEEEEE">A55</td>
            <td bgcolor="#EEEEEE">Inguinal Bubo Syndrome (IBS)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A55')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A55')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">012</td>
            <td align="center" bgcolor="#EEEEEE">A86</td>
            <td bgcolor="#EEEEEE">Acute Encephalitis like Syndrome (AES)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A86')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A86')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">041</td>
            <td align="center" bgcolor="#EEEEEE">A51</td>
            <td bgcolor="#EEEEEE">Syphilis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A51')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A51')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">013</td>
            <td align="center" bgcolor="#EEEEEE">B74.9</td>
            <td bgcolor="#EEEEEE">Filariasis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B74.9')->where('fldptsex','=','Female')->count() : null }}  </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B74.9')->where('fldptsex','=','Male')->count() : null }}  </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">F. Other Infected
                Diseases
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">014</td>
            <td align="center" bgcolor="#EEEEEE">B54</td>
            <td bgcolor="#EEEEEE">Clinical Malaria</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B54')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B54')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">042</td>
            <td align="center" bgcolor="#EEEEEE">J22</td>
            <td bgcolor="#EEEEEE">ARI/Lower respiratory tract infection (LRTI)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J22')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J22')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">015</td>
            <td align="center" bgcolor="#EEEEEE">B50.9</td>
            <td bgcolor="#EEEEEE">Malaria (Plasmodium Falciparum)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B50.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B50.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">043</td>
            <td align="center" bgcolor="#EEEEEE">J06</td>
            <td bgcolor="#EEEEEE">Upper respiratory tract infection (URTI)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J06')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J06')->where('fldptsex','=','Male')->count() : null }} </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">016</td>
            <td align="center" bgcolor="#EEEEEE">B51.9</td>
            <td bgcolor="#EEEEEE">Malaria (Plasmodium Vivax)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B51.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B51.9')->where('fldptsex','=','Male')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">044</td>
            <td align="center" bgcolor="#EEEEEE">J18</td>
            <td bgcolor="#EEEEEE">Pneumonia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J18')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J18')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">017</td>
            <td align="center" bgcolor="#EEEEEE">A90</td>
            <td bgcolor="#EEEEEE">Dengue Fever</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A90')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A90')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">045</td>
            <td align="center" bgcolor="#EEEEEE">J15</td>
            <td bgcolor="#EEEEEE">Severe pneumonia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J15')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J15')->where('fldptsex','=','Male')->count() : null }} </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">018</td>
            <td align="center" bgcolor="#EEEEEE">B55.9</td>
            <td bgcolor="#EEEEEE">Kala-azar/Leshmaniasis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B55.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B55.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">046</td>
            <td align="center" bgcolor="#EEEEEE">J40</td>
            <td bgcolor="#EEEEEE">Bronchitis (Acute &amp; chronic)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J40')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J40')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">C. Communicable, Water/Food Borne</td>
            <td align="center" bgcolor="#EEEEEE">047</td>
            <td align="center" bgcolor="#EEEEEE">N39</td>
            <td bgcolor="#EEEEEE">Urinary Tract Infection (UTI)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N39')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N39')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">019</td>
            <td align="center" bgcolor="#EEEEEE">A01.0</td>
            <td bgcolor="#EEEEEE">Typhoid (Enteric Fever)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A01.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A01.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">048</td>
            <td align="center" bgcolor="#EEEEEE">J11</td>
            <td bgcolor="#EEEEEE">Viral Influenza</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J11')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J11')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">020</td>
            <td align="center" bgcolor="#EEEEEE">A09</td>
            <td bgcolor="#EEEEEE">Acute gastro-enteritis (AGE)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A09')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A09')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">049</td>
            <td align="center" bgcolor="#EEEEEE">N99</td>
            <td bgcolor="#EEEEEE">Reproductive Tract Infection (RTI) - Female</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N99')->where('fldptsex','=','Female')->count() : null }}</td>
            <td bgcolor="#333333" style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">021</td>
            <td align="center" bgcolor="#EEEEEE">A06.9</td>
            <td bgcolor="#EEEEEE">Ameobic Dysentery/Amoebiasis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A06.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A06.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">050</td>
            <td align="center" bgcolor="#EEEEEE">N51*</td>
            <td bgcolor="#EEEEEE">Reproductive Tract Infection (RTI) - Male</td>
            <td bgcolor="#333333">&nbsp;</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N51*')->where('fldptsex','=','Male')->count() : null }}
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">022</td>
            <td align="center" bgcolor="#EEEEEE">A03.9</td>
            <td bgcolor="#EEEEEE">Bacillary Dysentery/Shigellosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A03.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A03.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">G. Nutritional &amp;
                Metabolic Disorder
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">023</td>
            <td align="center" bgcolor="#EEEEEE">K52.9</td>
            <td bgcolor="#EEEEEE">Presumed non-infectious diarrhoea</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K52.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K52.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">051</td>
            <td align="center" bgcolor="#EEEEEE">E04</td>
            <td bgcolor="#EEEEEE">Goitre, Cretinism</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E04')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E04')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">024</td>
            <td align="center" bgcolor="#EEEEEE">A00.9</td>
            <td bgcolor="#EEEEEE">Cholera</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A00.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A00.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">052</td>
            <td align="center" bgcolor="#EEEEEE">E14</td>
            <td bgcolor="#EEEEEE">Diabetes Mellitus (DM)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E14')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E14')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">025</td>
            <td align="center" bgcolor="#EEEEEE">B82.9</td>
            <td bgcolor="#EEEEEE">Intestinal Worms</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B82.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B82.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">053</td>
            <td align="center" bgcolor="#EEEEEE">E46</td>
            <td bgcolor="#EEEEEE">Malnutrition</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E46')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E46')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">026</td>
            <td align="center" bgcolor="#EEEEEE">R17</td>
            <td bgcolor="#EEEEEE">Jaundice</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R17')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R17')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">054</td>
            <td align="center" bgcolor="#EEEEEE">E50</td>
            <td bgcolor="#EEEEEE">Avitaminoses &amp; other nutrient deficiency</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E50')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E50')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">027</td>
            <td align="center" bgcolor="#EEEEEE">B15.9</td>
            <td bgcolor="#EEEEEE">Hepatitis A</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B15.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B15.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">055</td>
            <td align="center" bgcolor="#EEEEEE">E66</td>
            <td bgcolor="#EEEEEE">Obesity</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E66')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E66')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">028</td>
            <td align="center" bgcolor="#EEEEEE">B17</td>
            <td bgcolor="#EEEEEE">Hepatitis E</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B17')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B17')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">056</td>
            <td align="center" bgcolor="#EEEEEE">D64</td>
            <td bgcolor="#EEEEEE">Anaemia/Polyneuropathy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','D64')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','D64')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">029</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">E86</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Volume Depletion (Dehydration)</td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E86')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E86')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">057</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">G62</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Polyneuritis</td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G62')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G62')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        </tbody>
    </table>

</div>
<!-- End 15th page-->

<!-- 16th Page-->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">15. New Outpatient
                Morbidity (Including Under 5yrs Children) -- 2
            </td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td rowspan="33" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">H. Skin Diseases</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">I. Ear, Nose and
                Throat
                Infection …
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">058</td>
            <td align="center" bgcolor="#EEEEEE">L70</td>
            <td bgcolor="#EEEEEE">Acne</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L70')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L70')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">088</td>
            <td align="center" bgcolor="#EEEEEE">J34.2</td>
            <td bgcolor="#EEEEEE">Deviated nasal septum (DNS)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J34.2')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J34.2')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">059</td>
            <td align="center" bgcolor="#EEEEEE">B07</td>
            <td bgcolor="#EEEEEE">Warts</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B07')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B07')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">089</td>
            <td align="center" bgcolor="#EEEEEE">J31</td>
            <td bgcolor="#EEEEEE">Rhinitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J31')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J31')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">060</td>
            <td align="center" bgcolor="#EEEEEE">L81.1</td>
            <td bgcolor="#EEEEEE">Chloasma/ melasma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L81.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L81.1')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">090</td>
            <td align="center" bgcolor="#EEEEEE">H60</td>
            <td bgcolor="#EEEEEE">Otitis externa</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H60')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H60')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">061</td>
            <td align="center" bgcolor="#EEEEEE">L50</td>
            <td bgcolor="#EEEEEE">Urticaria</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L50')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L50')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">091</td>
            <td align="center" bgcolor="#EEEEEE">K21.0</td>
            <td bgcolor="#EEEEEE">Reflux laryngitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K21.0')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K21.0')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">062</td>
            <td align="center" bgcolor="#EEEEEE">L30.9</td>
            <td bgcolor="#EEEEEE">Dermatitis/Eczema</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L30.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L30.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">J. Oral Health
                Related
                Problems
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">063</td>
            <td align="center" bgcolor="#EEEEEE">L65</td>
            <td bgcolor="#EEEEEE">Alopecia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L65')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L65')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">092</td>
            <td align="center" bgcolor="#EEEEEE">K02</td>
            <td bgcolor="#EEEEEE">Dental caries</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K02')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K02')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">064</td>
            <td align="center" bgcolor="#EEEEEE">L80</td>
            <td bgcolor="#EEEEEE">Vitiligo</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L80')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L80')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">093</td>
            <td align="center" bgcolor="#EEEEEE">K08.8</td>
            <td bgcolor="#EEEEEE">Toothache</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K08.8')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K08.8')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">065</td>
            <td align="center" bgcolor="#EEEEEE">E70.3</td>
            <td bgcolor="#EEEEEE">Albinism</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E70.3')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E70.3')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">094</td>
            <td align="center" bgcolor="#EEEEEE">K05</td>
            <td bgcolor="#EEEEEE">Periodontal disease (gum disease)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K05')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K05')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">066</td>
            <td align="center" bgcolor="#EEEEEE">B00</td>
            <td bgcolor="#EEEEEE">Herpes simplex</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B00')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B00')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">095</td>
            <td align="center" bgcolor="#EEEEEE">K08.9</td>
            <td bgcolor="#EEEEEE">Other disorder of teeth</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K08.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K08.9')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">067</td>
            <td align="center" bgcolor="#EEEEEE">B02</td>
            <td bgcolor="#EEEEEE">Herpes zoster</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B02')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B02')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">096</td>
            <td align="center" bgcolor="#EEEEEE">K12</td>
            <td bgcolor="#EEEEEE">Oral ulcer (Aphthous &amp; herpetic)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K12')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K12')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">068</td>
            <td align="center" bgcolor="#EEEEEE">L53.9</td>
            <td bgcolor="#EEEEEE">Erythroderma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L53.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L53.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">097</td>
            <td align="center" bgcolor="#EEEEEE">K01.1</td>
            <td bgcolor="#EEEEEE">Tooth impaction</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K01.1')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K01.1')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">069</td>
            <td align="center" bgcolor="#EEEEEE">L01.0</td>
            <td bgcolor="#EEEEEE">Impetigo</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L01.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L01.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">098</td>
            <td align="center" bgcolor="#EEEEEE">K00.4</td>
            <td bgcolor="#EEEEEE">Hypoplasia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K00.4')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K00.4')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">070</td>
            <td align="center" bgcolor="#EEEEEE">L02</td>
            <td bgcolor="#EEEEEE">Boils</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">099</td>
            <td align="center" bgcolor="#EEEEEE">K13.2</td>
            <td bgcolor="#EEEEEE">Leukoplakia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K13.2')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K13.2')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">071</td>
            <td align="center" bgcolor="#EEEEEE">L02.0</td>
            <td bgcolor="#EEEEEE">Abscess</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">100</td>
            <td align="center" bgcolor="#EEEEEE">B37</td>
            <td bgcolor="#EEEEEE">Fungal infection (candidiasis)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B37')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B37')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">072</td>
            <td align="center" bgcolor="#EEEEEE">L02.9</td>
            <td bgcolor="#EEEEEE">Furunculosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02.9')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L02.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">101</td>
            <td align="center" bgcolor="#EEEEEE">K04</td>
            <td bgcolor="#EEEEEE">Oral space infection &amp; abscess</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K04')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K04')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">073</td>
            <td align="center" bgcolor="#EEEEEE">L43</td>
            <td bgcolor="#EEEEEE">Fungal infection (Lichen planus)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L43')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L43')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">K. Eye Problems</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">074</td>
            <td align="center" bgcolor="#EEEEEE">B86</td>
            <td bgcolor="#EEEEEE">Scabies</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B86')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','B86')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>102</td>
            <td>H10</td>
            <td>Conjunctivitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H10')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H10')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">075</td>
            <td align="center" bgcolor="#EEEEEE">L81.5</td>
            <td bgcolor="#EEEEEE">Leukoderma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L81.5')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L81.5')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>103</td>
            <td>A71</td>
            <td>Trachoma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A71')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A71')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">076</td>
            <td align="center" bgcolor="#EEEEEE">L40</td>
            <td bgcolor="#EEEEEE">Psoriasis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L40')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L40')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>104</td>
            <td>H26</td>
            <td>Cataract</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H26')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H26')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">077</td>
            <td align="center" bgcolor="#EEEEEE">L04</td>
            <td bgcolor="#EEEEEE">Acute Lymphadenitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L04')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L04')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>105</td>
            <td>H54</td>
            <td>Blindness</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H54')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H54')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">I. Ear, Nose and Throat Infection</td>
            <td>106</td>
            <td>H52</td>
            <td>Refractive error</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H52')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H52')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>078</td>
            <td>H66.0</td>
            <td>Acute Suppurative Otitis Media</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H66.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H66.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>107</td>
            <td>H40</td>
            <td>Glaucoma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H40')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H40')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>079</td>
            <td>H66.1</td>
            <td>Chronic Suppurative Otitis Media</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H66.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H66.1')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>108</td>
            <td>H53.5</td>
            <td>Colour blindness</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.5')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.5')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>080</td>
            <td>J32</td>
            <td>Sinusitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J32')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J32')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>109</td>
            <td>H05.2</td>
            <td>Exophthalmos</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H05.2')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H05.2')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>081</td>
            <td>J03</td>
            <td>Acute Tonsilitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J03')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J03')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>110</td>
            <td>H00.0</td>
            <td>Sty</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H00.0')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H00.0')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>082</td>
            <td>J02</td>
            <td>Pharyngitis/Sore throat</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J02')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J02')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>111</td>
            <td>H00.1</td>
            <td>Chalazion</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H00.1')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H00.1')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>083</td>
            <td>T16</td>
            <td>Foreign body in ear</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T16')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T16')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>112</td>
            <td>H11.0</td>
            <td>Pterygium</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H11.0')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H11.0')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>084</td>
            <td>T17.1</td>
            <td>Foreign body in nose</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T17.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T17.1')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>113</td>
            <td>E14.3†</td>
            <td>Diabetic retinopathy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E14.3†')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','E14.3†')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>085</td>
            <td>T17.2</td>
            <td>Foreign body in throat</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T17.2')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T17.2')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>114</td>
            <td>H35</td>
            <td>Hypertensive retinopathy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td>086</td>
            <td>H61.2</td>
            <td>Wax</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H61.2')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H61.2')->where('fldptsex','=','Male')->count() : null }} </td>
            <td>115</td>
            <td>H02</td>
            <td>Entropion</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H02')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H02')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000;">087</td>
            <td style="border-bottom: 1px solid #000;">J33</td>
            <td style="border-bottom: 1px solid #000;">Nasal Polyps</td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J33')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J33')->where('fldptsex','=','Male')->count() : null }} </td>
            <td style="border-bottom: 1px solid #000;">116</td>
            <td style="border-bottom: 1px solid #000;">H02.1</td>
            <td style="border-bottom: 1px solid #000;">Ectropion</td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H02.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H02.1')->where('fldptsex','=','Male')->count() : null }} </td>
        </tr>
        </tbody>
    </table>

</div>
<!-- End 16th page -->

<!-- 17th Page-->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">15. New Outpatient
                Morbidity (Including Under 5yrs Children) -- 3
            </td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td rowspan="34" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">K. Eye Problems …</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">N. Mental Health
                related problems
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">117</td>
            <td align="center" bgcolor="#EEEEEE">H26.1</td>
            <td bgcolor="#EEEEEE">Traumatic eye disease</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H26.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H26.1')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">147</td>
            <td align="center" bgcolor="#EEEEEE">F03</td>
            <td bgcolor="#EEEEEE">Dementia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F03')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F03')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">118</td>
            <td align="center" bgcolor="#EEEEEE">H20</td>
            <td bgcolor="#EEEEEE">Uveitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H20')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H20')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">148</td>
            <td align="center" bgcolor="#EEEEEE">F10</td>
            <td bgcolor="#EEEEEE">Addiction (ch. acoholisim, Dipsomania, drug)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F10')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F10')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">119</td>
            <td align="center" bgcolor="#EEEEEE">H35.3</td>
            <td bgcolor="#EEEEEE">Macular degeneration (age related)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35.3')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35.3')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">149</td>
            <td align="center" bgcolor="#EEEEEE">F20</td>
            <td bgcolor="#EEEEEE">Schizophrenia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F20')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F20')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">120</td>
            <td align="center" bgcolor="#EEEEEE">H53.0</td>
            <td bgcolor="#EEEEEE">Amblyopia (Lazy eye)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">150</td>
            <td align="center" bgcolor="#EEEEEE">F23</td>
            <td bgcolor="#EEEEEE">Acute psychotic disorder</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F23')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F23')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">121</td>
            <td align="center" bgcolor="#EEEEEE">H50</td>
            <td bgcolor="#EEEEEE">Squint</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H50')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H50')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">151</td>
            <td align="center" bgcolor="#EEEEEE">F31</td>
            <td bgcolor="#EEEEEE">Bipolar affective disorder</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F31')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F31')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">122</td>
            <td align="center" bgcolor="#EEEEEE">H35.5</td>
            <td bgcolor="#EEEEEE">Retinitis pigmentosa</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35.5')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H35.5')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">152</td>
            <td align="center" bgcolor="#EEEEEE">F32</td>
            <td bgcolor="#EEEEEE">Depression</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F32')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F32')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">123</td>
            <td align="center" bgcolor="#EEEEEE">H53.6</td>
            <td bgcolor="#EEEEEE">Nightblindness/visual disturbance</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.6')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','H53.6')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">153</td>
            <td align="center" bgcolor="#EEEEEE">F40</td>
            <td bgcolor="#EEEEEE">Phobic Anxiety</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F40')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F40')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">124</td>
            <td align="center" bgcolor="#EEEEEE">C69.2</td>
            <td bgcolor="#EEEEEE">Retinoblastoma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C69.2')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C69.2')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">154</td>
            <td align="center" bgcolor="#EEEEEE">F41</td>
            <td bgcolor="#EEEEEE">Other Anxiety</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F41')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F41')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">L. Obstetrics Complications</td>
            <td align="center" bgcolor="#EEEEEE">155</td>
            <td align="center" bgcolor="#EEEEEE">F42</td>
            <td bgcolor="#EEEEEE">Obsessive - compulsive disorder</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F42')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F42')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">125</td>
            <td align="center" bgcolor="#EEEEEE">O00</td>
            <td bgcolor="#EEEEEE">Ectopic Pregnancy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O00')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O00')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">156</td>
            <td align="center" bgcolor="#EEEEEE">F44</td>
            <td bgcolor="#EEEEEE">Conversive disorder (Hysteria)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F44')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F44')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">126</td>
            <td align="center" bgcolor="#EEEEEE">O08</td>
            <td bgcolor="#EEEEEE">Abortion Complication</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O08')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O08')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">157</td>
            <td align="center" bgcolor="#EEEEEE">F48</td>
            <td bgcolor="#EEEEEE">Neurosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F48')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F48')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">127</td>
            <td align="center" bgcolor="#EEEEEE">O13</td>
            <td bgcolor="#EEEEEE">Pregnancy Induced Hypertension (PIH)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O13')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O13')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">158</td>
            <td align="center" bgcolor="#EEEEEE">F79</td>
            <td bgcolor="#EEEEEE">Mental retardation</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F79')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F79')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">128</td>
            <td align="center" bgcolor="#EEEEEE">O14</td>
            <td bgcolor="#EEEEEE">Severe/ Pre-eclampsia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O14')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O14')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">159</td>
            <td align="center" bgcolor="#EEEEEE">G40</td>
            <td bgcolor="#EEEEEE">Epilepsy</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G40')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G40')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">129</td>
            <td align="center" bgcolor="#EEEEEE">O15.0</td>
            <td bgcolor="#EEEEEE">Antepartum Eclampsia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.0')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">160</td>
            <td align="center" bgcolor="#EEEEEE">G43</td>
            <td bgcolor="#EEEEEE">Migraine</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G43')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','G43')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">130</td>
            <td align="center" bgcolor="#EEEEEE">O15.1</td>
            <td bgcolor="#EEEEEE">Intrapartum Eclampsia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.1')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.1')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">161</td>
            <td align="center" bgcolor="#EEEEEE">F99</td>
            <td bgcolor="#EEEEEE">Mental illness (unspecified)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F99')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','F99')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">131</td>
            <td align="center" bgcolor="#EEEEEE">O15.2</td>
            <td bgcolor="#EEEEEE">Postpartum Eclampsia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.2')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O15.2')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">O. Malignancy</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">132</td>
            <td align="center" bgcolor="#EEEEEE">O21</td>
            <td bgcolor="#EEEEEE">Hyperemesis Grivadarum</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O21')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O21')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">162</td>
            <td align="center" bgcolor="#EEEEEE">C50</td>
            <td bgcolor="#EEEEEE">Breast cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C50')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C50')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">133</td>
            <td align="center" bgcolor="#EEEEEE">O46</td>
            <td bgcolor="#EEEEEE">Antepartum Haemorrhage</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O46')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O46')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">163</td>
            <td align="center" bgcolor="#EEEEEE">C53</td>
            <td bgcolor="#EEEEEE">Cervical/ uteri cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C53')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C53')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">134</td>
            <td align="center" bgcolor="#EEEEEE">O63</td>
            <td bgcolor="#EEEEEE">Prolonged labour</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O63')->where('fldptsex','=','Female')->count() : null }} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O63')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">164</td>
            <td align="center" bgcolor="#EEEEEE">C34</td>
            <td bgcolor="#EEEEEE">Lung/ brunchial Cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C34')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C34')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">135</td>
            <td align="center" bgcolor="#EEEEEE">O64-O66</td>
            <td bgcolor="#EEEEEE">Obstructed Labor</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O64-O66')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O64-O66')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">165</td>
            <td align="center" bgcolor="#EEEEEE">C15</td>
            <td bgcolor="#EEEEEE">Oesophagus cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C15')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C15')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">136</td>
            <td align="center" bgcolor="#EEEEEE">S37</td>
            <td bgcolor="#EEEEEE">Ruptured Uterus</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','S37')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','S37')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">166</td>
            <td align="center" bgcolor="#EEEEEE">C16</td>
            <td bgcolor="#EEEEEE">Stomach cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C16')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C16')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">137</td>
            <td align="center" bgcolor="#EEEEEE">O72</td>
            <td bgcolor="#EEEEEE">Postpartum Haemorrhage</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O72')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O72')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">167</td>
            <td align="center" bgcolor="#EEEEEE">C73</td>
            <td bgcolor="#EEEEEE">Thyroid cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C73')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C73')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">138</td>
            <td align="center" bgcolor="#EEEEEE">O73</td>
            <td bgcolor="#EEEEEE">Retained Placenta</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O73')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O73')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">168</td>
            <td align="center" bgcolor="#EEEEEE">C22</td>
            <td bgcolor="#EEEEEE">Liver cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C22')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C22')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">139</td>
            <td align="center" bgcolor="#EEEEEE">O75</td>
            <td bgcolor="#EEEEEE">Other Complications of labor and delivery</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O75')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O75')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">169</td>
            <td align="center" bgcolor="#EEEEEE">C25</td>
            <td bgcolor="#EEEEEE">Pancreatic cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C25')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C25')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">140</td>
            <td align="center" bgcolor="#EEEEEE">O85</td>
            <td bgcolor="#EEEEEE">Pueperal Sepsis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O85')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','O85')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">170</td>
            <td align="center" bgcolor="#EEEEEE">C79.5</td>
            <td bgcolor="#EEEEEE">Bone/ bone marrow cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C79.5')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C79.5')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">M. Gynae Problems</td>
            <td align="center" bgcolor="#EEEEEE">171</td>
            <td align="center" bgcolor="#EEEEEE">C23</td>
            <td bgcolor="#EEEEEE">Gall bladder cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C23')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C23')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">141</td>
            <td align="center" bgcolor="#EEEEEE">N73</td>
            <td bgcolor="#EEEEEE">Pelvic Inflammatory Disease (PID)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N73')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N73')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">172</td>
            <td align="center" bgcolor="#EEEEEE">C19</td>
            <td bgcolor="#EEEEEE">Colorectal (colon with rectum) cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C19')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C19')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">142</td>
            <td align="center" bgcolor="#EEEEEE">N81.4</td>
            <td bgcolor="#EEEEEE">Prolapsed uterus</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N81.4')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N81.4')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">173</td>
            <td align="center" bgcolor="#EEEEEE">C06</td>
            <td bgcolor="#EEEEEE">Oral cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C06')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C06')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">143</td>
            <td align="center" bgcolor="#EEEEEE">N92</td>
            <td bgcolor="#EEEEEE">Menstrual disorder</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N92')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N92')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">174</td>
            <td align="center" bgcolor="#EEEEEE">C85</td>
            <td bgcolor="#EEEEEE">Lymphoma cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C85')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C85')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">144</td>
            <td align="center" bgcolor="#EEEEEE">N93</td>
            <td bgcolor="#EEEEEE">Disfunctional Uterine Bleeding (DUB)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N93')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N93')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">175</td>
            <td align="center" bgcolor="#EEEEEE">C56</td>
            <td bgcolor="#EEEEEE">Ovary cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C56')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C56')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">145</td>
            <td align="center" bgcolor="#EEEEEE">N97</td>
            <td bgcolor="#EEEEEE">Sub- fertility (Female)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N97')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N97')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">176</td>
            <td align="center" bgcolor="#EEEEEE">C67</td>
            <td bgcolor="#EEEEEE">Urinary bladder cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C67')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C67')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">146</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">N46</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Sub- fertility/ infertility (Male)</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N46')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N46')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">177</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">C11</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Nasopharyngeal cancer</td>
            <td align="center" style="border-bottom: 1px solid #000;">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C11')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C11')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        </tbody>
    </table>

</div>
<!-- End 17th page -->

<!-- 18th Page-->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="11" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">15. New Outpatient
                Morbidity (Including Under 5yrs Children) -- 4
            </td>
        </tr>
        <tr>
            <td colspan="11" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE">Male</td>
            <td rowspan="32" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">SN</td>
            <td align="center" bgcolor="#EEEEEE">ICD Code</td>
            <td align="center" bgcolor="#EEEEEE">Name of Disease</td>
            <td align="center" bgcolor="#EEEEEE">Female</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">Male</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">O. Malignancy …</td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">R. Orthopaedic
                Problems
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">178</td>
            <td align="center" bgcolor="#EEEEEE">C49.0</td>
            <td bgcolor="#EEEEEE">Head &amp; neck cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C49.0')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C49.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">206</td>
            <td align="center" bgcolor="#EEEEEE">T14</td>
            <td bgcolor="#EEEEEE">Falls/ injuries/ fractures</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T14')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T14')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">179</td>
            <td align="center" bgcolor="#EEEEEE">C80</td>
            <td bgcolor="#EEEEEE">Other Cancer</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C80')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','C80')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">207</td>
            <td align="center" bgcolor="#EEEEEE">V89</td>
            <td bgcolor="#EEEEEE">Road Traffic Accident (RTA)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','V89')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','V89')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">P. Cardiovascular &amp; Respiratory Related Problems</td>
            <td align="center" bgcolor="#EEEEEE">208</td>
            <td align="center" bgcolor="#EEEEEE">M06</td>
            <td bgcolor="#EEEEEE">Rheumatoid arthritis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M06')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M06')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">180</td>
            <td align="center" bgcolor="#EEEEEE">I10</td>
            <td bgcolor="#EEEEEE">Hypertension</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I10')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I10')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">209</td>
            <td align="center" bgcolor="#EEEEEE">M13</td>
            <td bgcolor="#EEEEEE">Arthritis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M13')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M13')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">181</td>
            <td align="center" bgcolor="#EEEEEE">I50.0</td>
            <td bgcolor="#EEEEEE">Congestive heart failure</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I50.0')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I50.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">210</td>
            <td align="center" bgcolor="#EEEEEE">M19</td>
            <td bgcolor="#EEEEEE">Osteo arthrosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M19')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M19')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">182</td>
            <td align="center" bgcolor="#EEEEEE">I50.9</td>
            <td bgcolor="#EEEEEE">Cardiac heart failure</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I50.9')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I50.9')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">211</td>
            <td align="center" bgcolor="#EEEEEE">M54.9</td>
            <td bgcolor="#EEEEEE">Back ache (musculo- skeletal pain)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M54.9')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','M54.9')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">183</td>
            <td align="center" bgcolor="#EEEEEE">J44</td>
            <td bgcolor="#EEEEEE">Chronic Obstructive Pulmonary Disease (COPD)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J44')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J44')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="5" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">S. Surgical Problems
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">184</td>
            <td align="center" bgcolor="#EEEEEE">I01</td>
            <td bgcolor="#EEEEEE">Acute rheumatic fever</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I01')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I01')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">212</td>
            <td align="center" bgcolor="#EEEEEE">K27</td>
            <td bgcolor="#EEEEEE">Acid peptic disorders</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K27')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K27')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">185</td>
            <td align="center" bgcolor="#EEEEEE">I09</td>
            <td bgcolor="#EEEEEE">Rheumatic heart disease (RHD)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I09')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I09')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">213</td>
            <td align="center" bgcolor="#EEEEEE">K60.2</td>
            <td bgcolor="#EEEEEE">Anal Fissure</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K60.2')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K60.2')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">186</td>
            <td align="center" bgcolor="#EEEEEE">I24</td>
            <td bgcolor="#EEEEEE">Ischemic heart disease</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I24')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I24')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">214</td>
            <td align="center" bgcolor="#EEEEEE">K60.3</td>
            <td bgcolor="#EEEEEE">Anal fistula</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K60.3')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K60.3')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">187</td>
            <td align="center" bgcolor="#EEEEEE">I52*</td>
            <td bgcolor="#EEEEEE">Other cardiovascular problems</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I52*')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I52*')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">215</td>
            <td align="center" bgcolor="#EEEEEE">K63.2</td>
            <td bgcolor="#EEEEEE">Fistula of Intestine</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K63.2')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K63.2')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">188</td>
            <td align="center" bgcolor="#EEEEEE">J45</td>
            <td bgcolor="#EEEEEE">Bronchial asthma</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J45')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','J45')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">216</td>
            <td align="center" bgcolor="#EEEEEE">N20.0</td>
            <td bgcolor="#EEEEEE">Renal stones</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N20.0')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N20.0')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td colspan="5" align="center" bgcolor="#EEEEEE">Q. Other Diseases &amp; Injuries</td>
            <td align="center" bgcolor="#EEEEEE">217</td>
            <td align="center" bgcolor="#EEEEEE">N63</td>
            <td bgcolor="#EEEEEE">Breast lumps (adenoma)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N63')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N63')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">189</td>
            <td align="center" bgcolor="#EEEEEE">N17</td>
            <td bgcolor="#EEEEEE">Acute Renal failure</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N17')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N17')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">218</td>
            <td align="center" bgcolor="#EEEEEE">N61</td>
            <td bgcolor="#EEEEEE">Mastitis (Ignored breast)/breast abscess</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N61')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N61')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">190</td>
            <td align="center" bgcolor="#EEEEEE">N18</td>
            <td bgcolor="#EEEEEE">Chronic Renal failure</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N18')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N18')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">219</td>
            <td align="center" bgcolor="#EEEEEE">N64.4</td>
            <td bgcolor="#EEEEEE">Mastalgia breast</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N64.4')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N64.4')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">191</td>
            <td align="center" bgcolor="#EEEEEE">N05</td>
            <td bgcolor="#EEEEEE">Nephritis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N05')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N05')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">220</td>
            <td align="center" bgcolor="#EEEEEE">D17</td>
            <td bgcolor="#EEEEEE">Lumps (lipoma)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','D17')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','D17')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">192</td>
            <td align="center" bgcolor="#EEEEEE">N04</td>
            <td bgcolor="#EEEEEE">Nephrotic syndrome</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N04')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N04')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">221</td>
            <td align="center" bgcolor="#EEEEEE">L72.1</td>
            <td bgcolor="#EEEEEE">Sebaceous cyst</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L72.1')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L72.1')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">193</td>
            <td align="center" bgcolor="#EEEEEE">R51</td>
            <td bgcolor="#EEEEEE">Headache</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R51')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R51')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">222</td>
            <td align="center" bgcolor="#EEEEEE">L05</td>
            <td bgcolor="#EEEEEE">Pilonidal sinus</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L05')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','L05')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">194</td>
            <td align="center" bgcolor="#EEEEEE">R50</td>
            <td bgcolor="#EEEEEE">Pyrexia of Unknown Origin (PUO)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R50')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R50')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">223</td>
            <td align="center" bgcolor="#EEEEEE">K37</td>
            <td bgcolor="#EEEEEE">Appendicitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K37')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K37')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">195</td>
            <td align="center" bgcolor="#EEEEEE">K29</td>
            <td bgcolor="#EEEEEE">Gastritis (APD)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K29')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K29')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">224</td>
            <td align="center" bgcolor="#EEEEEE">K81</td>
            <td bgcolor="#EEEEEE">Cholecystitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K81')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K81')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">196</td>
            <td align="center" bgcolor="#EEEEEE">W57</td>
            <td bgcolor="#EEEEEE">Insect/Wasp bite</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W57')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W57')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">225</td>
            <td align="center" bgcolor="#EEEEEE">K80</td>
            <td bgcolor="#EEEEEE">Cholelithiasis (gall stone)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K80')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K80')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">197</td>
            <td align="center" bgcolor="#EEEEEE">R10</td>
            <td bgcolor="#EEEEEE">Abdominal Pain</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R10')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R10')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">226</td>
            <td align="center" bgcolor="#EEEEEE">K46</td>
            <td bgcolor="#EEEEEE">Hernia</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K46')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K46')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">198</td>
            <td align="center" bgcolor="#EEEEEE">K74</td>
            <td bgcolor="#EEEEEE">Cirrhosis of liver</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K74')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','K74')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">227</td>
            <td align="center" bgcolor="#EEEEEE">N43</td>
            <td bgcolor="#EEEEEE">Hydrocoele</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N43')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N43')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">199</td>
            <td align="center" bgcolor="#EEEEEE">T30</td>
            <td bgcolor="#EEEEEE">Burns and Scalds</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T30')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T30')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">228</td>
            <td align="center" bgcolor="#EEEEEE">N47</td>
            <td bgcolor="#EEEEEE">Phimosis/para-phimosis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N47')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N47')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">200</td>
            <td align="center" bgcolor="#EEEEEE">T65</td>
            <td bgcolor="#EEEEEE">Toxic Effect</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T65')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T65')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">229</td>
            <td align="center" bgcolor="#EEEEEE">I84</td>
            <td bgcolor="#EEEEEE">Haemorrhoids/Piles</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I84')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','I84')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">201</td>
            <td align="center" bgcolor="#EEEEEE">W54</td>
            <td bgcolor="#EEEEEE">Dog Bite</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W54')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W54')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">230</td>
            <td align="center" bgcolor="#EEEEEE">N45</td>
            <td bgcolor="#EEEEEE">Epididymitis/ Orchitis</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N45')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N45')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">202</td>
            <td align="center" bgcolor="#EEEEEE">A82</td>
            <td bgcolor="#EEEEEE">Other rabies susceptible animal bite</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A82')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','A82')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">231</td>
            <td align="center" bgcolor="#EEEEEE">N41</td>
            <td bgcolor="#EEEEEE">Prostatism (BEP/BPH)</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N41')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','N41')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">203</td>
            <td align="center" bgcolor="#EEEEEE">T63.0</td>
            <td bgcolor="#EEEEEE">Snake bite: Poisonous</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T63.0')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','T63.0')->where('fldptsex','=','Male')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE">232</td>
            <td align="center" bgcolor="#EEEEEE">R69</td>
            <td bgcolor="#EEEEEE">Not mentioned above and other</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R69')->where('fldptsex','=','Female')->count() : null }}</td>
            <td style="border-right: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','R69')->where('fldptsex','=','Male')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">204</td>
            <td align="center" bgcolor="#EEEEEE">W59</td>
            <td bgcolor="#EEEEEE">Snake bite: Non-poisonous</td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W59')->where('fldptsex','=','Female')->count() : null}} </td>
            <td align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','W59')->where('fldptsex','=','Male')->count() : null }} </td>
            <td colspan="3" align="center" bgcolor="#EEEEEE">Total New OPD Visits</td>
            <td>&nbsp;</td>
            <td style="border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">205</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Z73</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Physical Disability (Disabled Person)</td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','Z73')->where('fldptsex','=','Female')->count() : null}} </td>
            <td style="border-bottom: 1px solid #000;" align="center">
                &nbsp;{{ isset($outpatient) ? $outpatient->where('fldcodeid','=','Z73')->where('fldptsex','=','Male')->count() : null}} </td>
            <td colspan="3" align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Total Old (repeated)
                OPD Visits
            </td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</div>
<!-- End 18th page -->

<!-- Page 19th -->
<div class="wrapper" style="page-break-inside:avoid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td colspan="29" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">16. Laboratory
                Services</td>
        </tr>
        <tr>
            <td colspan="29" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE">No.</td>
            <td rowspan="37" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE">No.</td>
            <td rowspan="37" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE">No.</td>
            <td rowspan="37" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE">No.</td>
            <td rowspan="37" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE">No.</td>
            <td rowspan="37" style="border: none; border-left: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">DE</td>
            <td colspan="2" align="center" bgcolor="#EEEEEE">Test</td>
            <td align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">No.</td>
        </tr>
        <tr>
            <td colspan="4" align="center" bgcolor="#EEEEEE">HAEMATOLOGY</td>
            <td align="center" bgcolor="#EEEEEE">36</td>
            <td colspan="2" bgcolor="#EEEEEE">ALC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','ALC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">71</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti-CCP</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Anti-CCP')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">106</td>
            <td colspan="2" bgcolor="#EEEEEE">SGOT</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','SGOT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">140</td>
            <td rowspan="2" bgcolor="#EEEEEE">HAV</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">174</td>
            <td colspan="2" bgcolor="#EEEEEE">Cortisol</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Cortisol')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">1</td>
            <td colspan="2" bgcolor="#EEEEEE">Hb</td>

            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Hb')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">37</td>
            <td colspan="2" bgcolor="#EEEEEE">AEC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','AEC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">72</td>
            <td rowspan="2" bgcolor="#EEEEEE">RK-39</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">107</td>
            <td colspan="2" bgcolor="#EEEEEE">Total Protein</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Total Protein')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">141</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">175</td>
            <td colspan="2" bgcolor="#EEEEEE">AFP</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','AFP')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">2</td>
            <td colspan="2" bgcolor="#EEEEEE">RBC Count</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','RBC Count')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">38</td>
            <td colspan="2" bgcolor="#EEEEEE">FDP</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','FDP')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">73</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">108</td>
            <td colspan="2" bgcolor="#EEEEEE">Albumin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Albumin')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">142</td>
            <td rowspan="2" bgcolor="#EEEEEE">HBsAg</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">176</td>
            <td colspan="2" bgcolor="#EEEEEE">β-HCG</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','β-HCG')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">3</td>
            <td colspan="2" bgcolor="#EEEEEE">TLC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','TLC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">39</td>
            <td colspan="2" bgcolor="#EEEEEE">D-dimer</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','D-dimer')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">74</td>
            <td rowspan="2" bgcolor="#EEEEEE">JE</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">109</td>
            <td colspan="2" bgcolor="#EEEEEE">Gamma GT</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Gamma GT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">143</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">177</td>
            <td colspan="2" bgcolor="#EEEEEE">LH</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','LH')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">4</td>
            <td colspan="2" bgcolor="#EEEEEE">Platelets Count</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Platelets Count')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">40</td>
            <td colspan="2" bgcolor="#EEEEEE">Fac VIII</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Fac VIII')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">75</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">110</td>
            <td colspan="2" bgcolor="#EEEEEE">24hr urine protein</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','24hr urine protein')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">144</td>
            <td rowspan="2" bgcolor="#EEEEEE">HCV</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','HCV Total')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">178</td>
            <td colspan="2" bgcolor="#EEEEEE">FSH</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','FSH')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">5</td>
            <td colspan="2" bgcolor="#EEEEEE">DLC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','DLC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">41</td>
            <td colspan="2" bgcolor="#EEEEEE">Fac IX</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Fac')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">76</td>
            <td rowspan="2" bgcolor="#EEEEEE">Dengue</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">111</td>
            <td colspan="2" bgcolor="#EEEEEE">24hr urine U/A</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','24hr urine U/A')->count() : null}} </td>
            <td align="center" bgcolor="#EEEEEE">145</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">179</td>
            <td colspan="2" bgcolor="#EEEEEE">Prolactin</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Prolactin')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">6</td>
            <td colspan="2" bgcolor="#EEEEEE">ESR</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','ESR')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">42</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">77</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">112</td>
            <td colspan="2" bgcolor="#EEEEEE">Creatinine Clearance</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Creatinine Clearance')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">146</td>
            <td rowspan="2" bgcolor="#EEEEEE">HEV</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">180</td>
            <td colspan="2" bgcolor="#EEEEEE">Oestrogen</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Oestrogen')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">7</td>
            <td colspan="2" bgcolor="#EEEEEE">PCV/Hct</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PCV/Hct')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">IMMUNOLOGY</td>
            <td align="center" bgcolor="#EEEEEE">78</td>
            <td rowspan="3" bgcolor="#EEEEEE">Rapid MP test</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">113</td>
            <td colspan="2" bgcolor="#EEEEEE">Iron</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Iron')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">147</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">181</td>
            <td colspan="2" bgcolor="#EEEEEE">Progesterone</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Progesterone')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">8</td>
            <td colspan="2" bgcolor="#EEEEEE">MCV</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','MCV')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">43</td>
            <td colspan="2" bgcolor="#EEEEEE">Pregnancy Test (UPT)</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Pregnancy Test (UPT)')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">79</td>
            <td bgcolor="#EEEEEE">+Ve PV</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">114</td>
            <td colspan="2" bgcolor="#EEEEEE">TIBC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','TIBC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">148</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti-HBs</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','Anti-HBs')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">182</td>
            <td colspan="2" bgcolor="#EEEEEE">Testosterone</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Testosterone')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">9</td>
            <td colspan="2" bgcolor="#EEEEEE">MCH</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','MCH')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">44</td>
            <td colspan="2" bgcolor="#EEEEEE">ASO</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','ASO')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">80</td>
            <td bgcolor="#EEEEEE">+Ve PF</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">115</td>
            <td colspan="2" bgcolor="#EEEEEE">CPK-MB</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','CPK-MB')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">149</td>
            <td colspan="2" bgcolor="#EEEEEE">HBeAg</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','HBeAg')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">183</td>
            <td colspan="2" bgcolor="#EEEEEE">Vit.D</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Vit.D')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">10</td>
            <td colspan="2" bgcolor="#EEEEEE">MCHC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','MCHC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">45</td>
            <td colspan="2" bgcolor="#EEEEEE">CRP</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CRP')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">81</td>
            <td colspan="2" bgcolor="#EEEEEE">Mantoux test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Mantoux Test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">116</td>
            <td colspan="2" bgcolor="#EEEEEE">CPK-NAC</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','CPK-NAC')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">150</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti-HBe</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','Anti-HBe')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">184</td>
            <td colspan="2" bgcolor="#EEEEEE">Vit.B12</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Vitamin B12')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">11</td>
            <td colspan="2" bgcolor="#EEEEEE">RDW</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','RDW')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">46</td>
            <td colspan="2" bgcolor="#EEEEEE">RA Factor</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','RA Factor')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">82</td>
            <td rowspan="2" bgcolor="#EEEEEE">Chikungunya</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">117</td>
            <td colspan="2" bgcolor="#EEEEEE">LDH</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','LDH')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">151</td>
            <td colspan="2" bgcolor="#EEEEEE">HBcAg</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','HBcAg')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">185</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','Others')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">12</td>
            <td colspan="2" bgcolor="#EEEEEE">Blood Group &amp; Rh Type</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','like','Blood Group & Rh Type')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">47</td>
            <td rowspan="2" bgcolor="#EEEEEE">TPHA</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">83</td>
            <td bgcolor="#EEEEEE">P+ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">118</td>
            <td colspan="2" bgcolor="#EEEEEE">Iso-Trop-I</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','like','Iso-Trop-I')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">152</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti-HBcAg</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','like','Anti-HBcAg')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">DRUG ANALYSIS</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">13</td>
            <td colspan="2" bgcolor="#EEEEEE">Coombs test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','like','Coombs test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">48</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">84</td>
            <td bgcolor="#EEEEEE">Scrub Typhus</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">119</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">153</td>
            <td colspan="2" bgcolor="#EEEEEE">Western blot</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','Western blot')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">186</td>
            <td colspan="2" bgcolor="#EEEEEE">Carbamazepine</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Carbamazepine')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">14</td>
            <td colspan="2" bgcolor="#EEEEEE">Retics</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Retics')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">49</td>
            <td colspan="2" bgcolor="#EEEEEE">ANA</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','ANA')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">85</td>
            <td colspan="2" bgcolor="#EEEEEE">H. Pylori</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','H. Pylori')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">BACTERIOLOGY</td>
            <td align="center" bgcolor="#EEEEEE">154</td>
            <td colspan="2" bgcolor="#EEEEEE">CD4 count</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','CD4 count')->count() : null}}</td>
            <td align="center" bgcolor="#EEEEEE">187</td>
            <td colspan="2" bgcolor="#EEEEEE">Cyclosporine</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Cyclosporine')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">15</td>
            <td colspan="2" bgcolor="#EEEEEE">PBS/PBF</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PBS/PBF')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">50</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti-dsDNA</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Anti-dsDNA')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">86</td>
            <td colspan="2" bgcolor="#EEEEEE">Leptospira</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Leptospira')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">120</td>
            <td colspan="2" bgcolor="#EEEEEE">Gram stain</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Gram stain')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">155</td>
            <td colspan="2" bgcolor="#EEEEEE">Viral load</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','Viral load')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">188</td>
            <td colspan="2" bgcolor="#EEEEEE">Valporic acid</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Valporic acid')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">16</td>
            <td colspan="2" bgcolor="#EEEEEE">HbA1c</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','HbA1c')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">51</td>
            <td rowspan="2" bgcolor="#EEEEEE">RPR/VDRL</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">87</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">121</td>
            <td rowspan="10" align="center" bgcolor="#EEEEEE">Culture</td>
            <td bgcolor="#EEEEEE">Blood</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Blood')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">156</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','VIROLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">189</td>
            <td colspan="2" bgcolor="#EEEEEE">Phenytoin</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Phenytoin')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">17</td>
            <td rowspan="2" bgcolor="#EEEEEE">Special Stain</td>
            <td bgcolor="#EEEEEE">MPO</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','MPO')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">52</td>
            <td bgcolor="#EEEEEE">+Ve</td>
            <td>&nbsp;</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">BIOCHEMISTRY</td>
            <td align="center" bgcolor="#EEEEEE">122</td>
            <td bgcolor="#EEEEEE">Urine</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Urine')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">PARASITOLOGY</td>
            <td align="center" bgcolor="#EEEEEE">190</td>
            <td colspan="2" bgcolor="#EEEEEE">Digoxine</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Digoxine')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">18</td>
            <td bgcolor="#EEEEEE">PAS</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PAS')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">53</td>
            <td colspan="2" bgcolor="#EEEEEE">CEA</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CEA')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">88</td>
            <td colspan="2" bgcolor="#EEEEEE">Sugar</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Sugar')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">123</td>
            <td bgcolor="#EEEEEE">Body Fluid</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Body Fluid')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">157</td>
            <td colspan="2" bgcolor="#EEEEEE">Stool R/E</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Stool R/E')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">191</td>
            <td colspan="2" bgcolor="#EEEEEE">Tacrolimus</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Tacrolimus')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">19</td>
            <td colspan="2" bgcolor="#EEEEEE">Sickling Test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Sickling Test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">54</td>
            <td colspan="2" bgcolor="#EEEEEE">CA-125</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CA-125')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">89</td>
            <td colspan="2" bgcolor="#EEEEEE">Blood Urea</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Blood Urea')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">124</td>
            <td bgcolor="#EEEEEE">Swab</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Swab')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">158</td>
            <td colspan="2" bgcolor="#EEEEEE">Occult blood</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Occult blood')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">192</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','DRUG-ANALYSIS')->where('service_name','=','Others')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">20</td>
            <td colspan="2" bgcolor="#EEEEEE">Urine for Hemosiderin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','like','Urine for Hemosiderin')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">55</td>
            <td colspan="2" bgcolor="#EEEEEE">CA-19.9</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CA-19.9')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">90</td>
            <td colspan="2" bgcolor="#EEEEEE">Creatinine</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Creatinine')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">125</td>
            <td bgcolor="#EEEEEE">Stool</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Stool')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">159</td>
            <td colspan="2" bgcolor="#EEEEEE">Reducing sugar</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Reducing sugar')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">HISTOPATHOLOGY/
                CYTOLOGY</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">21</td>
            <td colspan="2" bgcolor="#EEEEEE">BT</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','BT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">56</td>
            <td colspan="2" bgcolor="#EEEEEE">CA-15.3</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CA-15.3')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">91</td>
            <td colspan="2" bgcolor="#EEEEEE">Sodium (Na)</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Sodium (Na)')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">126</td>
            <td bgcolor="#EEEEEE">Water</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Water')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">160</td>
            <td colspan="2" bgcolor="#EEEEEE">Urine R/E</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Urine R/E')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">193</td>
            <td rowspan="2" bgcolor="#EEEEEE">Biopsy</td>
            <td bgcolor="#EEEEEE">H &amp; E</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','CYTOLOGY')->where('service_name','=','H & E')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">22</td>
            <td colspan="2" bgcolor="#EEEEEE">CT</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','CT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">57</td>
            <td colspan="2" bgcolor="#EEEEEE">Toxo</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Toxo')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">92</td>
            <td colspan="2" bgcolor="#EEEEEE">Potassium (K)</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Potassium(K)')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">127</td>
            <td bgcolor="#EEEEEE">Pus</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Pus')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">161</td>
            <td colspan="2" bgcolor="#EEEEEE">Bile salts</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Bile salts')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">194</td>
            <td bgcolor="#EEEEEE">Other</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','CYTOLOGY')->where('service_name','=','Other')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">23</td>
            <td colspan="2" bgcolor="#EEEEEE">PT-INR</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PT-INR')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">58</td>
            <td colspan="2" bgcolor="#EEEEEE">Rubella</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Rubella')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">93</td>
            <td colspan="2" bgcolor="#EEEEEE">Calcium</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Calcium')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">128</td>
            <td bgcolor="#EEEEEE">Sputum</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Sputum')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">162</td>
            <td colspan="2" bgcolor="#EEEEEE">Bile pigments</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Bile pigments')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">195</td>
            <td rowspan="3" bgcolor="#EEEEEE">Cytology</td>
            <td bgcolor="#EEEEEE">Pap</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','CYTOLOGY')->where('service_name','=','Pap')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">24</td>
            <td colspan="2" bgcolor="#EEEEEE">APTT</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','APTT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">59</td>
            <td colspan="2" bgcolor="#EEEEEE">CMV</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','CMV')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">94</td>
            <td colspan="2" bgcolor="#EEEEEE">Phosphorus</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Phosphorus')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">129</td>
            <td bgcolor="#EEEEEE">CSF</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','CSF')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">163</td>
            <td colspan="2" bgcolor="#EEEEEE">Urobilinogen</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Urobilinogen')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">196</td>
            <td bgcolor="#EEEEEE">Giemsa</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','CYTOLOGY')->where('service_name','=','Giemsa')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">25</td>
            <td colspan="2" bgcolor="#EEEEEE">Bone Marrow Analysis</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','like','Bone Marrow Analysis')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">60</td>
            <td colspan="2" bgcolor="#EEEEEE">HSV</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','HSV')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">95</td>
            <td colspan="2" bgcolor="#EEEEEE">Magnesium</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Magnesium')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">130</td>
            <td bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">164</td>
            <td colspan="2" bgcolor="#EEEEEE">Porphobilinogen</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Porphobilinogen')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">197</td>
            <td bgcolor="#EEEEEE">Others</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','CYTOLOGY')->where('service_name','=','Others')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">26</td>
            <td colspan="2" bgcolor="#EEEEEE">Aldehyde test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','like','Aldehyde test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">61</td>
            <td colspan="2" bgcolor="#EEEEEE">Measles</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Measles')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">96</td>
            <td colspan="2" bgcolor="#EEEEEE">Uric acid</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Uric acid')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">131</td>
            <td colspan="2" bgcolor="#EEEEEE">Sputum AFB</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Sputum AFB')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">165</td>
            <td colspan="2" bgcolor="#EEEEEE">Acetone</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Acetone')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE" style="border-right: 1px solid #000;">IMMUNO-HISTO CHEMISTRY
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">27</td>
            <td colspan="2" bgcolor="#EEEEEE">MP Total</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','MP Total')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">62</td>
            <td colspan="2" bgcolor="#EEEEEE">Echinococcus</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Echinococcus')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">97</td>
            <td colspan="2" bgcolor="#EEEEEE">Total Cholesterol</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Total Cholesterol')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">132</td>
            <td colspan="2" bgcolor="#EEEEEE">Other AFB</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Other AFB')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">166</td>
            <td colspan="2" bgcolor="#EEEEEE">Chyle</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Chyle')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">198</td>
            <td colspan="2" bgcolor="#EEEEEE">ER</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','ER')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">28</td>
            <td rowspan="3" bgcolor="#EEEEEE">Smear MP Pos</td>
            <td bgcolor="#EEEEEE">PF</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PF')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">63</td>
            <td colspan="2" bgcolor="#EEEEEE">Amoebiasis</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Amoebiasis')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">98</td>
            <td colspan="2" bgcolor="#EEEEEE">Triglycerides</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Triglycerides')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">133</td>
            <td colspan="2" bgcolor="#EEEEEE">Leprosy Smear</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Leprosy Smear')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">167</td>
            <td colspan="2" bgcolor="#EEEEEE">Specific Gravity</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Specific Gravity')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">199</td>
            <td colspan="2" bgcolor="#EEEEEE">PR</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','PR')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">29</td>
            <td bgcolor="#EEEEEE">PV</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','PV')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">64</td>
            <td colspan="2" bgcolor="#EEEEEE">PSA</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','PSA')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">99</td>
            <td colspan="2" bgcolor="#EEEEEE">HDL</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','HDL')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">134</td>
            <td colspan="2" bgcolor="#EEEEEE">India Ink Test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','like','India Ink Test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">168</td>
            <td colspan="2" bgcolor="#EEEEEE">Bence Jones protein</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','like','Bence Jones protein')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">200</td>
            <td colspan="2" bgcolor="#EEEEEE">G-FAP</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','G-FAP')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">30</td>
            <td bgcolor="#EEEEEE">P-MIX</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','P-MIX')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">65</td>
            <td colspan="2" bgcolor="#EEEEEE">Ferritin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Ferritin')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">100</td>
            <td colspan="2" bgcolor="#EEEEEE">LDL</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','LDL')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">135</td>
            <td rowspan="2" bgcolor="#EEEEEE">Fungus</td>
            <td bgcolor="#EEEEEE">KOH Test</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','KOH Test')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">169</td>
            <td colspan="2" bgcolor="#EEEEEE">Semen analysis</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Semen analysis')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">201</td>
            <td colspan="2" bgcolor="#EEEEEE">s-100</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','s-100')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">31</td>
            <td rowspan="2" bgcolor="#EEEEEE">MF</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">66</td>
            <td colspan="2" bgcolor="#EEEEEE">Cysticercosis</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Cysticercosis')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">101</td>
            <td colspan="2" bgcolor="#EEEEEE">Amylase</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Amylase')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">136</td>
            <td bgcolor="#EEEEEE">Culture</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Culture')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">170</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','PARASITOLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">202</td>
            <td colspan="2" bgcolor="#EEEEEE">Vimentin</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','s-100')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">32</td>
            <td bgcolor="#EEEEEE">Pos.</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Pos.')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">67</td>
            <td colspan="2" bgcolor="#EEEEEE">Brucella</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Brucella')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">102</td>
            <td colspan="2" bgcolor="#EEEEEE">Micro albumin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Micro albumin')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">137</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BACTERIOLOGY')->where('service_name','=','Others')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">HORMONE/ENDOCRINE</td>
            <td align="center" bgcolor="#EEEEEE">203</td>
            <td colspan="2" bgcolor="#EEEEEE">Cytokeratin</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','Cytokeratin')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">33</td>
            <td colspan="2" bgcolor="#EEEEEE">LD Bodies</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','LD Bodies')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">68</td>
            <td colspan="2" bgcolor="#EEEEEE">Thyroglobulin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Thyroglobulin')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">103</td>
            <td colspan="2" bgcolor="#EEEEEE">Bilirubin</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Bilirubin')->count() : null }}</td>
            <td colspan="4" align="center" bgcolor="#EEEEEE">VIROLOGY</td>
            <td align="center" bgcolor="#EEEEEE">171</td>
            <td colspan="2" bgcolor="#EEEEEE">T3</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','T3')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">204</td>
            <td colspan="2" bgcolor="#EEEEEE">Others</td>
            <td style="border-right: 1px solid #000;" align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNO-HISTROCHEMESTRY')->where('service_name','=','Others')->count() : null }}</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE">34</td>
            <td colspan="2" bgcolor="#EEEEEE">Hb Electrophoresis</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','Hb Electrophoresis')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">69</td>
            <td colspan="2" bgcolor="#EEEEEE">Anti TPO</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','=','Anti TPO')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">104</td>
            <td colspan="2" bgcolor="#EEEEEE">SGPT</td>
            <td align="center">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','SGPT')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE">138</td>
            <td rowspan="2" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">HIV</td>
            <td bgcolor="#EEEEEE">Total</td>
            <td>&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE">172</td>
            <td colspan="2" bgcolor="#EEEEEE">T4</td>
            <td>&nbsp;{{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','T4')->count() : null }}</td>
            <td colspan="4" rowspan="2" style="border: none; border-top: 1px solid #000;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">35</td>
            <td colspan="2" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">LE cell</td>
            <td style="border-bottom: 1px solid #000;">&nbsp; {{ isset($test) ?  $test->where('sub_category','=','HEMATOLOGY')->where('service_name','=','LE cell')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">70</td>
            <td colspan="2" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Protein Electrophoresis</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;{{ isset($test) ?  $test->where('sub_category','=','IMMUNOLOGY')->where('service_name','like','Protein Electrophoresis')->count() : null }} </td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">105</td>
            <td colspan="2" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">Alk Phos</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;{{ isset($test) ?  $test->where('sub_category','=','BIOCHEMISTRY')->where('service_name','=','Alk Phos')->count() : null }}</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">139</td>
            <td bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">+Ve</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;</td>
            <td align="center" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">173</td>
            <td colspan="2" bgcolor="#EEEEEE" style="border-bottom: 1px solid #000;">TSH</td>
            <td style="border-bottom: 1px solid #000;">&nbsp;{{ isset($test) ?  $test->where('sub_category','=','HORMONES-ENDOCRINES')->where('service_name','=','TSH')->count() : null }}</td>
        </tr>
        </tbody>
    </table>
</div>

<!-- ENd Page 19th -->

</body>
</html>
