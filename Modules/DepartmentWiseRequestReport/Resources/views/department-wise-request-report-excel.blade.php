@php
    $malePharmacy = 0;
    $femalePharmacy = 0;
    $maleLaboratory = 0;
    $femaleLaboratory = 0;
    $maleRadiology = 0;
    $femaleRadiology = 0;
    $malePlannedConsult = 0;
    $femalePlannedConsult = 0;
    $maleDoneConsult = 0;
    $femaleDoneConsult = 0;
@endphp
<table>
    <tbody>
        <tr><td></td></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <td></td>
            @endfor
            <td colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></td>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <td></td>
            @endfor
            <td colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></td>
        </tr>
        <tr><td></td></tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <td></td>
            @endfor
            <td colspan="2"><b>Patient Type:</b></td>
            <td colspan="2">{{ ucfirst($patientType) }}</td>
        </tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <td></td>
            @endfor
            <td colspan="2"><b>Department:</b></td>
            <td colspan="2">{{ $request_dept }}</td>
        </tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <td></td>
            @endfor
            <td colspan="2"><b>Billing Mode:</b></td>
            <td colspan="2">{{ isset($billing) ? $billing : "" }}</td>
        </tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <td></td>
            @endfor
            <td colspan="2"><b>Request date:</b></td>
            <td colspan="2">{{ $request_date }}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td  rowspan="2">Department</td>
            <td  rowspan="2">User</td>
            <td  colspan="2">Pharmacy Order</td>
            <td  colspan="2">Laboratory Order</td>
            <td  colspan="2">Radiology Order</td>
            <td  colspan="2">Consult Planned</td>
            <td  colspan="2">Consult Done</td>
        </tr>
        <tr>
            <td >Male</td>
            <td >Female</td>
            <td >Male</td>
            <td >Female</td>
            <td >Male</td>
            <td >Female</td>
            <td >Male</td>
            <td >Female</td>
            <td >Male</td>
            <td >Female</td>
        </tr>
        @if($filter)
            @foreach ($dept as $dept)
                @php
                    $hasUserData = false;
                @endphp
                @if(count($dept->users) > 0)
                    @php
                        $isFirstUser = true;
                        $chkDeptNoData = 0;
                        $usersArray = [];
                        foreach ($dept->users as $user){
                            array_push($usersArray,$user->flduserid);
                        }
                    @endphp
                    @foreach ($dept->users as $key => $user)
                        @php
                            $hasUserData = false;
                            if(array_key_exists($dept->flddept, $departmentWisePharmacyData)){
                                if(array_key_exists($user->flduserid, $departmentWisePharmacyData[$dept->flddept])){
                                    $hasUserData = true;
                                }
                            }
                            if(array_key_exists($dept->flddept, $departmentWiseData)){
                                if(array_key_exists($user->flduserid, $departmentWiseData[$dept->flddept])){
                                    $hasUserData = true;
                                }
                            }
                            if(array_key_exists($dept->flddept, $departmentWiseConsultData)){
                                if(array_key_exists($user->flduserid, $departmentWiseConsultData[$dept->flddept])){
                                    $hasUserData = true;
                                }
                            }
                        @endphp
                        @if($hasUserData)
                        @php
                            $chkDeptNoData = $chkDeptNoData + 1;
                        @endphp
                        <tr>
                            @php
                                $maxrowspan = 0;
                                if(array_key_exists($dept->flddept, $departmentWisePharmacyData)){
                                    $rowspan = count($departmentWisePharmacyData[$dept->flddept]);
                                    $arrayDiff = count(array_diff(array_keys($departmentWisePharmacyData[$dept->flddept]), $usersArray));
                                    $maxrowspan += ($rowspan - $arrayDiff);
                                }
                                if(array_key_exists($dept->flddept, $departmentWiseData)){
                                    $rowspan = count($departmentWiseData[$dept->flddept]);
                                    $arrayDiff = count(array_diff(array_keys($departmentWiseData[$dept->flddept]), $usersArray));
                                    $maxrowspan += ($rowspan - $arrayDiff);
                                }
                                if(array_key_exists($dept->flddept, $departmentWiseConsultData)){
                                    $rowspan = count($departmentWiseConsultData[$dept->flddept]);
                                    $arrayDiff = count(array_diff(array_keys($departmentWiseConsultData[$dept->flddept]), $usersArray));
                                    $maxrowspan += ($rowspan - $arrayDiff);
                                }
                                if($maxrowspan < 1){
                                    $maxrowspan = 1;
                                }
                            @endphp
                            @if($isFirstUser)
                                <td  rowspan="{{ $maxrowspan }}">{{ $dept->flddept }}</td>
                                @php
                                    if($hasUserData){
                                        $isFirstUser = false;
                                    }
                                @endphp
                                @if($isFirstUser)
                                    <td  rowspan="{{ $maxrowspan }}">{{ $dept->flddept }}</td>
                                    @php
                                        if($hasUserData){
                                            $isFirstUser = false;
                                        }
                                    @endphp
                                @endif
                                @if($hasUserData)
                                    <td >{{ $user->fldfullname }}</td>
                                @endif
                                @if(count($departmentWisePharmacyData) > 0)
                                    @if(array_key_exists($dept->flddept, $departmentWisePharmacyData))
                                        @if(array_key_exists($user->flduserid, $departmentWisePharmacyData[$dept->flddept]))
                                            @if(array_key_exists('Male', $departmentWisePharmacyData[$dept->flddept][$user->flduserid]))
                                                <td >{{ count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Male']) }}</td>
                                                @php
                                                    $malePharmacy += count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Male']);
                                                @endphp
                                            @else
                                                <td >0</td>
                                            @endif
                                            @if(array_key_exists('Female', $departmentWisePharmacyData[$dept->flddept][$user->flduserid]))
                                                <td >{{ count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Female']) }}</td>
                                                @php
                                                    $femalePharmacy += count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Female']);
                                                @endphp
                                            @else
                                                <td >0</td>
                                            @endif
                                        @else
                                            @if($hasUserData)
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                        @endif
                                    @else
                                        <td >0</td>
                                        <td >0</td>
                                    @endif
                                @else
                                    <td >0</td>
                                    <td >0</td>
                                @endif
                                @if(count($departmentWiseData) > 0)
                                    @if(array_key_exists($dept->flddept, $departmentWiseData))
                                        @if(array_key_exists($user->flduserid, $departmentWiseData[$dept->flddept]))
                                            @if(array_key_exists('Diagnostic Tests', $departmentWiseData[$dept->flddept][$user->flduserid]))
                                                @if(count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']) > 0)
                                                    @if(array_key_exists('Male', $departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']))
                                                        <td >{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Male']) }}</td>
                                                        @php
                                                            $maleLaboratory += count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Male']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                    @if(array_key_exists('Female', $departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']))
                                                        <td >{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Female']) }}</td>
                                                        @php
                                                            $femaleLaboratory += count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Female']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                @else
                                                    <td >0</td>
                                                    <td >0</td>
                                                @endif
                                            @else
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                            @if(array_key_exists('Radio Diagnostics', $departmentWiseData[$dept->flddept][$user->flduserid]))
                                                @if(count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']) > 0)
                                                    @if(array_key_exists('Male', $departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']))
                                                        <td >{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Male']) }}</td>
                                                        @php
                                                            $maleRadiology += count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Male']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                    @if(array_key_exists('Female', $departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']))
                                                        <td >{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Female']) }}</td>
                                                        @php
                                                            $femaleRadiology += count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Female']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                @else
                                                    <td >0</td>
                                                    <td >0</td>
                                                @endif
                                            @else
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                        @else
                                            @if($hasUserData)
                                                <td >0</td>
                                                <td >0</td>
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                        @endif
                                    @else
                                        <td >0</td>
                                        <td >0</td>
                                        <td >0</td>
                                        <td >0</td>
                                    @endif
                                @else
                                    <td >0</td>
                                    <td >0</td>
                                    <td >0</td>
                                    <td >0</td>
                                @endif

                                @if(count($departmentWiseConsultData) > 0)
                                    @if(array_key_exists($dept->flddept, $departmentWiseConsultData))
                                        @if(array_key_exists($user->flduserid, $departmentWiseConsultData[$dept->flddept]))
                                            @if(array_key_exists('Planned', $departmentWiseConsultData[$dept->flddept][$user->flduserid]))
                                                @if(count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']) > 0)
                                                    @if(array_key_exists('Male', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']))
                                                        <td >{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Male']) }}</td>
                                                        @php
                                                            $malePlannedConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Male']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                    @if(array_key_exists('Female', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']))
                                                        <td >{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Female']) }}</td>
                                                        @php
                                                            $femalePlannedConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Female']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                @else
                                                    <td >0</td>
                                                    <td >0</td>
                                                @endif
                                            @else
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                            @if(array_key_exists('Done', $departmentWiseConsultData[$dept->flddept][$user->flduserid]))
                                                @if(count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']) > 0)
                                                    @if(array_key_exists('Male', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']))
                                                        <td >{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Male']) }}</td>
                                                        @php
                                                            $maleDoneConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Male']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                    @if(array_key_exists('Female', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']))
                                                        <td >{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Female']) }}</td>
                                                        @php
                                                            $femaleDoneConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Female']);
                                                        @endphp
                                                    @else
                                                        <td >0</td>
                                                    @endif
                                                @else
                                                    <td >0</td>
                                                    <td >0</td>
                                                @endif
                                            @else
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                        @else
                                            @if($hasUserData)
                                                <td >0</td>
                                                <td >0</td>
                                                <td >0</td>
                                                <td >0</td>
                                            @endif
                                        @endif
                                    @else
                                        <td >0</td>
                                        <td >0</td>
                                        <td >0</td>
                                        <td >0</td>
                                    @endif
                                @else
                                    <td >0</td>
                                    <td >0</td>
                                    <td >0</td>
                                    <td >0</td>
                                @endif
                            @endif
                        </tr>
                        @endif
                    @endforeach
                    @if($chkDeptNoData < 1)
                        <tr>
                            <td >{{ $dept->flddept }}</td>
                            <td >N/A</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                            <td >0</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td >{{ $dept->flddept }}</td>
                        <td >N/A</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                        <td >0</td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td  rowspan="2" colspan="2">Total</td>
            <td >{{ $malePharmacy }}</td>
            <td >{{ $femalePharmacy }}</td>
            <td >{{ $maleLaboratory }}</td>
            <td >{{ $femaleLaboratory }}</td>
            <td >{{ $maleRadiology }}</td>
            <td >{{ $femaleRadiology }}</td>
            <td >{{ $malePlannedConsult }}</td>
            <td >{{ $femalePlannedConsult }}</td>
            <td >{{ $maleDoneConsult }}</td>
            <td >{{ $femaleDoneConsult }}</td>
        </tr>
        <tr>
            <td  colspan="2">{{ $malePharmacy + $femalePharmacy }}</td>
            <td  colspan="2">{{ $maleLaboratory + $femaleLaboratory }}</td>
            <td  colspan="2">{{ $maleRadiology + $femaleRadiology }}</td>
            <td  colspan="2">{{ $malePlannedConsult + $femalePlannedConsult }}</td>
            <td  colspan="2">{{ $maleDoneConsult + $femaleDoneConsult }}</td>
        </tr>
    </tbody>
</table>