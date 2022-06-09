<style type="text/css">
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
        background-color: #d8d7d7;
    }
    .div-table {
        margin-top: 20px;
        margin: 0 auto;
        width: 95%;
    }
    .table-first {
        width: 95%;
    }
    .table-second {
        width: 95%;
    }
    .th-head {
        width: 13%;
    }
     h3 h2 {
        margin: 15px 0px 2px 0px;
    }
    .table-break2{width: 100%;}
    @media print {
       .table-break{page-break-before: always;}
       .table-break2{page-break-before: always;}
       .th-bak {-webkit-print-color-adjust: exact;}
       .table-bak{-webkit-print-color-adjust: exact;}
    }
    .row{
        display:flex;
        width: 95%;
        margin: 0 auto;
    }
    .left-table{
        width: 50%;
    }
    .right-table{
        width: 50%;
    }
    .text-right{
        text-align:right;
    }
</style>
<div class="pdf-container">
    <div class="heading">
        <table style="width: 95%; margin: 0 auto;">
           <tbody>
        <tr>
            <td style="width: 20%;">
                @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                    <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"/>
                @endif
            </td>
            <td style="width:60%;">
                <h3 style="margin-bottom: 0; text-align: center;">Government Of Nepal</h3>
                    <h3 style="margin-bottom: 0; margin-top: 2px; text-align: center;">Ministry of Health and Population</h3>
                    <h3 style="margin-bottom: 0; margin-top: 2px; text-align: center;">National Academy of medical Science</h3>
                    <h3 style="margin-bottom: 0; margin-top: 2px; text-align: center;">{{ isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name']:'' }}</h3>
                    <h3 style="margin-bottom: 0; margin-top: 2px; text-align: center;">{{ isset(Options::get('siteconfig')['system_address']) ? Options::get('siteconfig')['system_address']:'' }}</h3>
                    <h3 style="margin-bottom: 0; margin-top: 2px; text-align: center;">Phone No: {{  Options::get('system_telephone_no') ? Options::get('system_telephone_no') :'' }} </h3>
            </td>
            <td></td>
        </tr>
        </tbody>
        </table>
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
    </div>

    @php
        $enpatient = \App\Encounter::where('fldencounterval',$encounter_id)->with('patientInfo')->first();
        $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';

         $age = $enpatient->patientInfo->fldagestyle;
        //  $age = date_diff(date_create($enpatient->patientInfo->fldptbirday), date_create('now'))->y;
    @endphp
    <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-bak" style="margin-top: 20px; margin: 0 auto; background-color: #d8d7d7;">
        <thead>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px; width: 53%;">Name: {{$fullname}}</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">ID no.: {{$encounter_id}}</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Age/Sex: {{$age}}/{{$enpatient->patientInfo->fldptsex}}</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Address :{{$enpatient->patientInfo->fldptaddvill}}/{{$enpatient->patientInfo->fldptadddist}}</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Admission Date: {{$enpatient->flddoa}}</th>
                <th colspan="2" style="padding: 6px; border: none; text-align: left; font-size: 14px;">Discharge Date: {{$enpatient->flddod}}</th>
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
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Relation: {{$enpatient->patientInfo->fldrelation}}</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Bed No: {{(isset($result['othergeneralData']['bed_number']) and $result['othergeneralData']['bed_number'] !='') ? $result['othergeneralData']['bed_number'] : ""}}</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Department: {{$beddepartment}}</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Guardian: {{$enpatient->patientInfo->fldptguardian}}</th>
                
            </tr>
        </thead>
    </table>

    <div class="table" style="margin-bottom: 20px;">
        @if(isset($diagnosis) and count($diagnosis) > 0)
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">DIAGNOSIS</th>
                </tr>

                <tr>
                    
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        <ul>
                            @foreach($diagnosis as $d)
                                <li>{{$d->fldcode}}</li>
                            @endforeach
                        </ul>
                       
                    </td>
                    
                </tr>

            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['past_history']) and $result['othergeneralData']['past_history'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">CASE SUMMARY</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['past_history'] !!}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['operation_date']) || isset($result['othergeneralData']['operative_procedures']) || isset($result['othergeneralData']['operative_findings'] ))
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">OPERATION</th>
                </tr>
                @if(isset($result['othergeneralData']['operation_date']))
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       <b>Operation Date(BS/AD):</b>{{(isset($result['othergeneralData']['operation_date']) and $result['othergeneralData']['operation_date'] !='') ? $result['othergeneralData']['operation_date'].'/'. $result['othergeneralData']['eng_operation_date'] : ""}} 
                    </td>
                </tr>
                @endif
                @if(isset($result['othergeneralData']['operative_procedures']))
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       <b>Operative Procedure:</b>{{(isset($result['othergeneralData']['operative_procedures']) and $result['othergeneralData']['operative_procedures'] !='') ? $result['othergeneralData']['operative_procedures'] : ""}}
                    </td>
                </tr>
                @endif
                @if(isset($result['othergeneralData']['operative_findings']))
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       <b>Operative Findings :</b>{{(isset($result['othergeneralData']['operative_findings']) and $result['othergeneralData']['operative_findings'] !='') ? $result['othergeneralData']['operative_findings'] : ""}}
                    </td>
                </tr>
                @endif
            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['laboratory']) and $result['othergeneralData']['laboratory'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak" colspan="2">INVESTIGATION</th>
                </tr>
                <tr>
                    <td style="border: 1px solid; text-align: center;">TEST</td>
                    <td style="border: 1px solid; text-align: center">RESULT</td>
                </tr>
                @if(isset($result['othergeneralData']['laboratory']))
                    @php
                        $labdata = explode('|',$result['othergeneralData']['laboratory']);
                    @endphp
                    @if(isset($labdata) and !empty($labdata))
                        <tr><td colspan="2">Laboratory</td></tr>
                        @foreach($labdata as $ldata)
                            @if($ldata != '')
                            <tr>
                                @php
                                    $bldata = explode(':',$ldata);
                                @endphp
                                <td style="border: 1px solid; font-size: 14px; padding: 10px;">
                                   {{isset($bldata[0]) ? trim($bldata[0]) : ""}}
                                </td>
                                <td style="border: 1px solid; font-size: 14px; padding: 10px;">
                                   {{isset($bldata[1]) ? trim($bldata[1]) : ""}}
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                @endif
                @if(isset($result['othergeneralData']['radiology']))
                    @php
                        $radiodata = explode('|',$result['othergeneralData']['radiology']);
                    @endphp
                    @if(isset($radiodata) and !empty($radiodata))
                        @foreach($radiodata as $rdata)
                        <tr><td colspan="2">Radiology</td></tr>
                            @if($rdata != '')
                            <tr>
                                @php
                                    $brdata = explode(':',$rdata);
                                @endphp
                                <td style="border: 1px solid; font-size: 14px; padding: 10px;">
                                   {{isset($brdata[0]) ? trim($brdata[0]) : ""}}
                                </td>
                                <td style="border: 1px solid; font-size: 14px; padding: 10px;">
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
        @if(isset($result['othergeneralData']['course_in_hospital']) and $result['othergeneralData']['course_in_hospital'] !='')
        <table border="0" width="95%" cellspacing="0" cellpadding="0"  style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">TREATMENT DURING HOSPITAL STAY</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['course_in_hospital'] !!}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['medication']) and $result['othergeneralData']['medication'] !='')
            @php
                $medication = explode(',',$result['othergeneralData']['medication']);
            @endphp
            <div class="div-table">
               <table width="100%"  border="0" cellspacing="0" cellpadding="0" style="border: 3px solid; background-color: #d8d7d7;">

                <thead>
                    <tr>
                        <th colspan="4" style="padding: 10px; border: 1px solid;" class="th-bak">TREATMENT AT DISCHARGE</th>
                    </tr>
                    <tr>
                        <th class="th-borderbottom">Sr No:</th>
                        <th class="th-borderbottom">Medecine:</th>
                        <th class="th-borderbottom">Label:</th>
                        <th class="th-borderbottom">Remarks:</th>
                        <th class="th-borderbottom">Days:</th>
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
                                <td class="td-borderbottom">{{$loop->iteration}}</td>
                                <td class="td-borderbottom">{{ isset($medecine) ? $medecine[0] : $med}}</td>
                                <td class="td-borderbottom">
                                    {{$label}}
                                </td>
                                <td class="td-borderbottom">{{ isset($frequency) ? 'दिनमा '.$frequency[0].' पटक' : ''}}</td>
                                <td class="td-borderbottom">{{ isset($frequency) ? $frequency[1].' दिन' : ''}}</td>
                            </tr>
                            @endif
                        @endif

                    @endforeach


                </tbody>
            </table>
            </div>

        @endif
        @if(isset($result['othergeneralData']['advice']) and $result['othergeneralData']['advice'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">ADVICE ON DISCHARGE</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['advice'] !!}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['patient_condition']) and $result['othergeneralData']['patient_condition'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">CONDITION AT THE TIME OF DISCHARGE</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {{$result['othergeneralData']['patient_condition']}}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        
        @if(isset($result['othergeneralData']['special_instruction']) and $result['othergeneralData']['special_instruction'] !='')
        <table border="0" width="95%" cellspacing="0" cellpadding="0"  style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">SPECIAL INSTRUCTION</th>
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
        
        
        @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">ALLERGIES</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       <ul>
                           @foreach($allergicdrugs as $drug)
                                <li>{{$drug->fldcode}}</li>
                           @endforeach
                       </ul>
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        @if(isset($result['othergeneralData']['diet']) and $result['othergeneralData']['diet'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">DIET</th>
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
        
        @if(isset($enpatient->fldfollowdate) and $enpatient->fldfollowdate!='')
        @php
            $currentdate = date('Y-m-d');
        @endphp
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">FOLLOW UP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        {{($enpatient->fldfollowdate > $currentdate) ? $enpatient->fldfollowdate : ""}}
                    </td>
                </tr>

            </tbody>
        </table>
        @endif
        
        @if(isset($result['othergeneralData']['complaints']) and $result['othergeneralData']['complaints'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">MEDICAL HISTORY AND PERSISTING COMPLAINTS</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['complaints'] !!}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        
        @if(isset($result['othergeneralData']['physical_examination']) and $result['othergeneralData']['physical_examination'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">PHYSICAL AND SYSTEMIC EXAMINATION</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['physical_examination'] !!}
                    </td>
                </tr>

            </thead>
        </table>
        @endif
        
        @if(isset($result['othergeneralData']['operation_performed']) and $result['othergeneralData']['operation_performed'] !='')
        <table border="0" cellspacing="0" cellpadding="0" class="table-break" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">OPERATION PERFORMED: EMERGENCY OPEN APPENDECTOMY UNDER SAB ON 2077/09/21</th>
                </tr>

                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                       {!! $result['othergeneralData']['operation_performed'] !!}
                    </td>
                </tr>

            </thead>
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
                <div class="">
                    ----------------------------------------<br/>
                    Signature of Consultant/Incharge Of unit<br/>
                    Full Name: <b>{{ $consultantfullname}}</b><br/>
                    NMC Reg No: <b>{{ $consultantnmc}}</b>
                </div>
            </div>
            <div class="right-table" style="margin-left: 650px;">
                <h4>DISCHARGE SUMMARY PREPARED BY:</h4>
                <div class="">
                ----------------------------------------<br/>
                    Signature of Medical Officer<br/>
                    Full Name: <b>{{ $medicalofficerfullname }}</b><br/>
                    NMC Reg No: <b>{{ $medicalofficernmc }}</b>
                </div>
            </div>
        </div>
        
    </div>
</div>

