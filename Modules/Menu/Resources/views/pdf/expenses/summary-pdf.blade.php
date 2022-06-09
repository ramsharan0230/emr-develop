<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Expenses Summary PDF</title>
    <style>
        .table-design {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-design td, .table-design th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-design tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-design tr:hover {
            background-color: #ddd;
        }

        .table-design th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #999;
            color: white;
        }
    </style>
</head>
<body>
@php
    $patientInfo = $encounterData->patientInfo;
@endphp
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} / {{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }} Years / {{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
            <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>
        </tr>
        <tr>
            <td style="width: 200px;"><p><strong>REPORT:</strong> Expenses</p></td>
        </tr>
        </tbody>
    </table>

    <table class="table-design">
        <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Rate</th>
            <th>Tax%</th>
            <th>Disc%</th>
            <th>QTY</th>
            <th>Total</th>
            <th>Invoice</th>
        </tr>
        </thead>
        @if(count($summary))
            @foreach($summary as $flditemtype => $details)
                @php $grand_total = 0; @endphp

                @if($flditemtype == 'Diagnostic Tests')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $diagnostic_total_amount = 0; @endphp
                    @foreach($details as $diagnostic)
                        <tr>
                            <td>{{ $diagnostic->fldtime }}</td>
                            <td>{{ $diagnostic->flditemtype }}</td>
                            <td>{{ $diagnostic->flditemrate }}</td>
                            <td>{{ $diagnostic->fldtaxper }}</td>
                            <td>{{ $diagnostic->flddiscper }}</td>
                            <td>{{ $diagnostic->flditemqty }}</td>
                            <td>{{ $diagnostic->tot }}</td>
                            <td>{{ $diagnostic->fldbillno }}</td>
                        </tr>
                        @php $diagnostic_total_amount += $diagnostic->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $diagnostic_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

                @if($flditemtype == 'Radio Diagnostics')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $radio_total_amount = 0; @endphp
                    @foreach($details as $radio)
                        <tr>
                            <td>{{ $radio->fldtime }}</td>
                            <td>{{ $radio->flditemtype }}</td>
                            <td>{{ $radio->flditemrate }}</td>
                            <td>{{ $radio->fldtaxper }}</td>
                            <td>{{ $radio->flddiscper }}</td>
                            <td>{{ $radio->flditemqty }}</td>
                            <td>{{ $radio->tot }}</td>
                            <td>{{ $radio->fldbillno }}</td>
                        </tr>
                        @php $radio_total_amount += $radio->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $radio_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

                @if($flditemtype == 'Procedures')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $procedures_total_amount = 0; @endphp
                    @foreach($details as $procedures)
                        <tr>
                            <td>{{ $procedures->fldtime }}</td>
                            <td>{{ $procedures->flditemtype }}</td>
                            <td>{{ $procedures->flditemrate }}</td>
                            <td>{{ $procedures->fldtaxper }}</td>
                            <td>{{ $procedures->flddiscper }}</td>
                            <td>{{ $procedures->flditemqty }}</td>
                            <td>{{ $procedures->tot }}</td>
                            <td>{{ $procedures->fldbillno }}</td>
                        </tr>
                        @php $procedures_total_amount += $procedures->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $procedures_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

                @if($flditemtype == 'General Services')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $general_total_amount = 0; @endphp
                    @foreach($details as $general)
                        <tr>
                            <td>{{ $general->fldtime }}</td>
                            <td>{{ $general->flditemtype }}</td>
                            <td>{{ $general->flditemrate }}</td>
                            <td>{{ $general->fldtaxper }}</td>
                            <td>{{ $general->flddiscper }}</td>
                            <td>{{ $general->flditemqty }}</td>
                            <td>{{ $general->tot }}</td>
                            <td>{{ $general->fldbillno }}</td>
                        </tr>
                        @php $general_total_amount += $general->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $general_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

                @if($flditemtype == 'Equipment')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $equipment_total_amount = 0; @endphp
                    @foreach($details as $equipment)
                        <tr>
                            <td>{{ $equipment->fldtime }}</td>
                            <td>{{ $equipment->flditemtype }}</td>
                            <td>{{ $equipment->flditemrate }}</td>
                            <td>{{ $equipment->fldtaxper }}</td>
                            <td>{{ $equipment->flddiscper }}</td>
                            <td>{{ $equipment->flditemqty }}</td>
                            <td>{{ $equipment->tot }}</td>
                            <td>{{ $equipment->fldbillno }}</td>
                        </tr>
                        @php $equipment_total_amount += $equipment->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $equipment_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

                @if($flditemtype == 'Other Items')

                    <tbody>
                    <tr>
                        <td colspan="8" style="text-align: center;">{{$flditemtype}}</td>
                    </tr>
                    @php $items_total_amount = 0; @endphp
                    @foreach($details as $items)
                        <tr>
                            <td>{{ $items->fldtime }}</td>
                            <td>{{ $items->flditemtype }}</td>
                            <td>{{ $items->flditemrate }}</td>
                            <td>{{ $items->fldtaxper }}</td>
                            <td>{{ $items->flddiscper }}</td>
                            <td>{{ $items->flditemqty }}</td>
                            <td>{{ $items->tot }}</td>
                            <td>{{ $items->fldbillno }}</td>
                        </tr>
                        @php $items_total_amount += $items->tot; @endphp
                    @endforeach
                    </tbody>

                    <tbody>
                    <tr>
                        <td>{{$flditemtype}}</td>
                        <td>Sub Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $items_total_amount }}</td>
                        <td></td>
                    </tr>
                    </tbody>

                @endif

            @endforeach
            <tfoot style="background-color: #a0a000;color: #fff;">
            <tr>
                <td>All Types</td>
                <td>Grand Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @if(isset($diagnostic_total_amount))
                    @php $grand_total += $diagnostic_total_amount @endphp
                @endif
                @if(isset($radio_total_amount))
                    @php $grand_total += $radio_total_amount @endphp
                @endif
                @if(isset($procedures_total_amount))
                    @php $grand_total += $procedures_total_amount @endphp
                @endif
                @if(isset($general_total_amount))
                    @php $grand_total += $general_total_amount @endphp
                @endif
                @if(isset($equipment_total_amount))
                    @php $grand_total += $equipment_total_amount @endphp
                @endif
                @if(isset($items_total_amount))
                    @php $grand_total += $items_total_amount @endphp
                @endif
                <td>{{ $grand_total }}</td>
                <td></td>
            </tr>
            </tfoot>
        @endif
    </table>

    <p>Admin, {{ date('M j, Y') }}</p>
</main>
</body>
</html>
