<style type="text/css">
body{
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    font-size: 13px;
}
    @page {
        margin-top: 35mm;
        margin-bottom:30mm;
    }

    .heading table th{
        font-weight: normal;
    }

    .div-table {
        margin-top: 20px;
        margin: 0 auto;
        width: 95%;
    }

    .td-borderbottom {
        border-bottom: 1px solid #000;
        text-align: center;
        padding: 10px;
        font-size: 14px;
    }

    .th-style {
        padding: 10px;
        border: 1px solid;
    }

    .td-style {
        padding: 10px;
        border: 1px solid;
    }

    .th-bak {
        background-color:white;
        border-top:1px solid #ccc;
        padding:0;
        padding-top:10px;
        text-align: left;
        font-weight: bold;
    }

    .th-head{
        border: 1px solid #d8d7d7;
        text-align: center;
        background-color:#d8d7d7;
        text-align: left;
    }



    .div-table {
        margin-top: 20px;
        margin: 0 auto;
        width: 95%;
    }

    .table-first {
        width: 95%;
    }


    .table-detail{
        border: 1px solid #d8d7d7;
        border-collapse:collapse;
        font-size: 13px;
    }

.table-detail td, .table-detail th{
        border: 1px solid #c3c3c3;
        padding: 5px;
    }

    .table-second {
        width: 95%;
    }

    /* .th-head {
        width: 13%;
    } */

    h3 h2 {
        margin: 15px 0px 2px 0px;
    }

    .table-break2 {
        width: 100%;
    }

    @media print {
        .table-break {
            page-break-before: always;
        }

        .table-break2 {
            page-break-before: always;
        }

        .th-bak {
            -webkit-print-color-adjust: exact;
        }

        .table-bak {
            -webkit-print-color-adjust: exact;
        }
    }

    .row {
        display: flex;
        width: 95%;
        margin: 0 auto;
        justify-content: space-between;
    }

    .left-table {
        width: 50%;
    }

    .right-table {
        width: 50%;
        text-align: right;
    }

    .text-right {
        text-align: right;
    }

    ul{
        margin:0;
        padding:0;
        /* padding-left:10px; */
    }

    ul{
        list-style: none;
    }
</style>
<div class="pdf-container">
    <div class="heading" style="margin: 0 auto; width: 95%;">

         <!-- <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="width: 57%;">
                     <h3 style="text-align: center;">Department Of Surgery</h3></td>
                </tr>
                <tr>
                    <td style="width: 57%;">
                        <h3 style="text-align: center;">Discharge Summary</h3>
                    </td>
                </tr>
            </tbody>
        </table> -->


        @php
            $enpatient = \App\Encounter::where('fldencounterval',$encounter_id)->with('patientInfo')->first();
            $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';

             $age = $enpatient->patientInfo->fldagestyle;
            //  $age = date_diff(date_create($enpatient->patientInfo->fldptbirday), date_create('now'))->y;
        @endphp
        <table style="width: 50%; float: left; text-align:left;">
        <!-- <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-bak" style="margin-top: 20px; margin: 0 auto; background-color: #d8d7d7;"> -->
            <tbody>
                <tr>
                    <th>Name: {{$fullname}}</th>
                </tr>
                <tr>
                    <th>Age/Sex: {{$age}}/{{$enpatient->patientInfo->fldptsex}}</th>
                </tr>
                <tr>
                    <th>Address :{{$enpatient->patientInfo->fldptaddvill}}/{{$enpatient->patientInfo->fldptadddist}}</th>
                </tr>


                @if(isset($result['othergeneralData']['department']) and $result['othergeneralData']['department'] !='')
                @php
                $depdata = \DB::table('tbldepartmentbed')
                ->select('flddept')
                ->where('fldbed',$result['othergeneralData']['department'])
                ->first();
                $beddepartment = $depdata->flddept;
                @endphp
                @else
                @php
                $beddepartment = "";
                @endphp
                @endif
                <tr>
                    <th>Guardian: {{$enpatient->patientInfo->fldptguardian}}</th>
                </tr>
                <tr>
                    <th>Relation: {{$enpatient->patientInfo->fldrelation}}</th>
                </tr>

            </tbody>
        </table>

        <table style="width: 50%;float:right;text-align:right;">
            <tr>
                <th>ID no.: {{$encounter_id}}</th>
            </tr>
            <tr>
                <th>Admission Date: {{$enpatient->flddoa}}</th>
            </tr>
            <tr>
                <th>Discharge Date: {{$enpatient->flddod}}</th>
            </tr>
            <tr>
                <th>
                    Bed No: {{(isset($result['othergeneralData']['bed_number']) and $result['othergeneralData']['bed_number'] !='') ? $result['othergeneralData']['bed_number'] : ""}}</th>
            </tr>
            <tr>
                <th>Department: {{(isset($result['othergeneralData']['bed_number']) and $result['othergeneralData']['bed_number'] !='') ? Helpers::getDepartmentFromBED($result['othergeneralData']['bed_number']) : ""}}</th>
            </tr>
            <tr>
                <th>Patient Status: <b>{{$enpatient->fldadmission}}</b></th>
            </tr>
        </table>
    </div>
    <div class="table" style="margin-bottom: 20px;margin-top:20px;">
        @if(isset($diagnosis) and count($diagnosis) > 0)
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th class="th-bak">DIAGNOSIS</th>
                </tr>
                </thead>
                <tbody>
                <tr>

                    <td style="border: none; font-size: 14px; padding: 10px;">
                        <ul>
                            @foreach($diagnosis as $d)
                                <li>{{$d->fldcode}}</li>
                            @endforeach
                        </ul>

                    </td>

                </tr>
                </tbody>

        </table>
        @endif
         @if(isset($result['othergeneralData']['complaints']) and $result['othergeneralData']['complaints'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">MEDICAL HISTORY AND PRESENTING COMPLAINTS</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['complaints'] !!}
                    </td>
                </tr>
                </tbody>

        </table>
        @endif
        @if(isset($result['othergeneralData']['past_history']) and $result['othergeneralData']['past_history'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                    <th class="th-bak">CASE SUMMARY</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['past_history'] !!}
                    </td>
                </tr>
                </tbody>

        </table>
        @endif
        @if(isset($result['othergeneralData']['physical_examination']) and $result['othergeneralData']['physical_examination'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">PHYSICAL AND SYSTEMIC EXAMINATION</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['physical_examination'] !!}
                    </td>
                </tr>
                </tbody>

        </table>
        @endif
        @if((isset($result['othergeneralData']['laboratory']) and $result['othergeneralData']['laboratory'] !='') or (isset($result['othergeneralData']['radiology']) and $result['othergeneralData']['radiology'] !=''))
        <div class="div-table">

            <p style="padding-bottom:0;font-size:16px;" class="th-bak" >INVESTIGATION</p>
            @if(isset($result['othergeneralData']['laboratory']) and $result['othergeneralData']['laboratory'] !='')
            <p style="margin-bottom:0;padding-bottom:10px;font-size:14px;font-weight:bold;" >Laboratory</p>
            <table class="table-detail" style="margin: 0 auto; width:100%;">
                <thead>

                    <tr>
                        <th class="th-head">TEST</th>
                        <th class="th-head">RESULT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($result['othergeneralData']['laboratory']))
                        @php
                            $labdata = explode('|',$result['othergeneralData']['laboratory']);
                        @endphp
                        @if(isset($labdata) and !empty($labdata))

                            @foreach($labdata as $ldata)
                                @if($ldata != '')
                                <tr>
                                    @php
                                        $bldata = explode(':',$ldata);
                                    @endphp
                                    <td style="border: 1px solid #c3c3c3; ">
                                       {{isset($bldata[0]) ? trim($bldata[0]) : ""}}
                                    </td>
                                    <td style="border:1px solid #c3c3c3;  ">
                                       {{isset($bldata[1]) ? trim($bldata[1]) : ""}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    </tbody>
            </table>
            @endif
            @if(isset($result['othergeneralData']['radiology']) and $result['othergeneralData']['radiology'] !='')
            <p style="margin-bottom:0;padding-bottom:10px;font-size:14px;font-weight:bold;" >Radiology</p>
            <table class="table-detail" style="margin:0px auto 0; width:100%;">
                <thead>




                    @if(isset($result['othergeneralData']['radiology']))
                        @php
                            $radiodata = explode('|',$result['othergeneralData']['radiology']);
                        @endphp
                        @if(isset($radiodata) and !empty($radiodata))
                            @foreach($radiodata as $rdata)

                                @if($rdata != '')
                                <tr>
                                    @php
                                        $brdata = explode(':',$rdata);
                                    @endphp
                                    <td style="border:1px solid #c3c3c3; font-size: 14px; padding: 10px;">
                                       {{isset($brdata[0]) ? trim($brdata[0]) : ""}} : &nbsp;

                                       {{isset($brdata[1]) ? str_replace("&nbsp;", "", trim($brdata[1])) : ""}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </thead>
            </table>
            @endif
        </div>
        @endif
        @if(isset($result['othergeneralData']['course_in_hospital']) and $result['othergeneralData']['course_in_hospital'] !='')
        <table border="0" width="95%" cellspacing="0" cellpadding="0" style="margin: 10px auto 0; ">
            <thead>
                <tr>
                   <th class="th-bak">TREATMENT DURING HOSPITAL STAY</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['course_in_hospital'] !!}
                    </td>
                </tr>

                </tbody>
        </table>
        @endif
        @if(isset($result['othergeneralData']['operation_date']) || isset($result['othergeneralData']['operative_procedures']) || isset($result['othergeneralData']['operative_findings'] ))
                @if(isset($result['othergeneralData']['operation_date']))
                    @php
                        $operationdate = json_decode($result['othergeneralData']['operation_date']);
                    @endphp
                @endif

                <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
{{--            {{ dd(empty($operationdate[0])) }}--}}
            @if(isset($operationdate) and !empty($operationdate[0]))
                <tr>
                    <th class="th-bak">OPERATION</th>
                </tr>
            @endif
            </thead>
            <tbody>
            @if(isset($operationdate) and !empty($operationdate))
{{--                @if(isset($result['othergeneralData']['operation_date']))--}}
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
{{--                        @php--}}
{{--                            $operationdate = json_decode($result['othergeneralData']['operation_date']);--}}
{{--                        @endphp--}}
                        @if(isset($operationdate) and !empty($operationdate[0]))
                        <b>Operation Date (AD/BS)</b><br/>
                            <ul>
                                @foreach($operationdate as $od)
                                    @if(!is_null($od))
                                        @php
                                            $npdate = Helpers::dateEngToNepdash($od);

                                        @endphp
                                        <li>{{$od}} / {{$npdate->year}}-{{$npdate->month}}-{{$npdate->date}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                    </td>
                </tr>
                @endif
                @if(isset($result['othergeneralData']['operative_procedures']))
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        @php
                            $operativeprocedures = json_decode($result['othergeneralData']['operative_procedures']);
                        @endphp
                        @if(isset($operativeprocedures) and !empty($operativeprocedures[0]))
                        <b>Operative Procedure:</b><br/>
                            <ul>
                                @foreach($operativeprocedures as $op)
                                    @if($op !='null')
                                        <li>{{$op}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                    </td>
                </tr>
                @endif
                @if(isset($result['othergeneralData']['operative_findings']))
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       <b>Operative Findings :</b>&nbsp;<br/>{{(isset($result['othergeneralData']['operative_findings']) and $result['othergeneralData']['operative_findings'] !='') ? $result['othergeneralData']['operative_findings'] : ""}}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif
        @if(isset($result['othergeneralData']['patient_condition']) and $result['othergeneralData']['patient_condition'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">CONDITION AT THE TIME OF DISCHARGE</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {{$result['othergeneralData']['patient_condition']}}
                    </td>
                </tr>
                </tbody>

        </table>
        @endif

        @if(isset($result['othergeneralData']['medication']) and $result['othergeneralData']['medication'] !='')
            @php
                $medication = explode(',',$result['othergeneralData']['medication']);
            @endphp
            <div class="div-table">
                <p class="th-bak" style="font-size:16px;">TREATMENT AT DISCHARGE</p>
               <table width="100%"  class="table-detail">

                <thead>

                    <tr>
                        <th class="th-head">Sr No:</th>
                        <th class="th-head">Medicine:</th>
                        <!-- <th class="th-head">Label:</th>
                        <th class="th-head">Remarks:</th>
                        <th class="th-head">Days:</th> -->
                    </tr>
                </thead>

                <tbody>
                    @foreach(array_map('trim',$medication) as $med)

                        @if($med !=' ')
                            @php
                                $label = $med
                            @endphp
                            @php
                                $medecine = explode('-Dose:',$med);
                            @endphp
                            @if(isset($medecine[1]))
                                @php
                                    $dose = explode('-Freq:',$medecine[1]);
                                @endphp
                            @endif
                            @if(isset($dose[1]))
                                @php
                                    $frequency = explode('-Days:',$dose[1]);
                                @endphp
                            @endif
                            @if(isset($medecine) and $medecine[0] !='')
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ isset($medecine) ? $medecine[0] : $med}}</td>
                               <!--  <td>
                                    {{$label}}
                                </td>
                                <td>{{ isset($frequency) ? 'दिनमा '.$frequency[0].' पटक' : ''}}</td>
                                <td>{{ isset($frequency) ? $frequency[1].' दिन' : ''}}</td> -->
                            </tr>
                            @endif
                        @endif

                    @endforeach


                </tbody>
            </table>
            </div>

        @endif
         @if(isset($result['othergeneralData']['diet']) and $result['othergeneralData']['diet'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">DIET</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['diet'] !!}
                    </td>
                </tr>

            </tbody>
        </table>
        @endif
         @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">ALLERGIES</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        <ul>
                            @foreach($allergicdrugs as $drug)
                            <li>{{$drug->fldcode}}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                </tbody>

        </table>
        @endif
        @if(isset($result['othergeneralData']['special_instruction']) and $result['othergeneralData']['special_instruction'] !='')
        <table border="0" width="95%" cellspacing="0" cellpadding="0" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">SPECIAL INSTRUCTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['special_instruction'] !!}
                    </td>
                </tr>
            </tbody>
        </table>
        @endif
        @if(isset($result['othergeneralData']['advice']) and $result['othergeneralData']['advice'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 10px auto 0; ">
            <thead>
                <tr>
                    <th class="th-bak">ADVICE ON DISCHARGE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['advice'] !!}
                    </td>
                </tr>

            </tbody>
        </table>
        @endif







        @php
            $currentdate = date('Y-m-d');
        @endphp
        @if(isset($enpatient->fldfollowdate) and $enpatient->fldfollowdate!='' and $enpatient->fldfollowdate > $currentdate)

        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">FOLLOW UP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {{$enpatient->fldfollowdate}}
                    </td>
                </tr>

            </tbody>
        </table>
        @endif





        @if(isset($result['othergeneralData']['operation_performed']) and $result['othergeneralData']['operation_performed'] !='')
         <table border="0" cellspacing="0" cellpadding="0" class="table-break" style="margin: 0 auto; ">
            <thead>
                <tr>
                   <th class="th-bak">OPERATION PERFORMED: EMERGENCY OPEN APPENDECTOMY UNDER SAB ON 2077/09/21</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {!! $result['othergeneralData']['operation_performed'] !!}
                    </td>
                </tr>
                </tbody>

        </table>
         @endif
         @if(isset($result['othergeneralData']['consultant']) and $result['othergeneralData']['consultant'] !='')

             @php
                $consultantdata = \DB::table('users')
                                ->where('username',$result['othergeneralData']['consultant'])

                                ->first();
                $consultantfullname = $consultantdata->fldcategory.' '.$consultantdata->firstname.' '.$consultantdata->middlename.' '.$consultantdata->lastname;
                $consultantnmc = (isset($consultantdata->nmc) and $consultantdata->nmc !='') ? $consultantdata->nmc : $consultantdata->nhbc;
             @endphp
        @else
            @php
                $consultantfullname = '';
                $consultantnmc = '';
            @endphp
        @endif

        @if(isset($result['othergeneralData']['medical_officer']) and $result['othergeneralData']['medical_officer'] !='')
             @php
                $medicalofficerdata = \DB::table('users')
                                ->where('username',$result['othergeneralData']['medical_officer'])
                                ->first();
                $medicalofficerfullname = $medicalofficerdata->fldcategory.' '.$medicalofficerdata->firstname.' '.$medicalofficerdata->middlename.' '.$medicalofficerdata->lastname;
                $medicalofficernmc = (isset($medicalofficerdata->nmc) and $medicalofficerdata->nmc !='') ? $medicalofficerdata->nmc : $medicalofficerdata->nhbc;
             @endphp
        @else
            @php
                $medicalofficerfullname = '';
                $medicalofficernmc = '';
            @endphp
        @endif
        <div class="row" style="margin-top: 40px;">
            <div class="left-table">
                <h4>DISCHARGE SUMMARY CHECKED BY: </h4>
                <div style="margin-top:30px;">
                    ----------------------------------------<br/>
                    Signature of Consultant/Incharge Of unit<br/>
                    <b>{{ $consultantfullname}}</b><br/>
                    NMC Reg No: <b>{{ $consultantnmc}}</b>
                </div>
            </div>
            <div class="right-table">
                <h4>DISCHARGE SUMMARY PREPARED BY:</h4>
                <div style="margin-top:30px;">
                ----------------------------------------<br/>
                    Signature of Medical Officer<br/>
                    <b>{{ $medicalofficerfullname }}</b><br/>
                    NMC Reg No: <b>{{ $medicalofficernmc }}</b>
                </div>
            </div>
        </div>

    </div>
</div>

