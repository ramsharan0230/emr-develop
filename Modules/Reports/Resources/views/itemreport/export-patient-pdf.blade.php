@extends('inpatient::pdf.layout.main')

@section('title', 'PATIENTS REPORT')

@section('content')
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td>
                <p>From Date: {{ $finalfrom}} {{ isset($finalfrom) ? "(" .\App\Utils\Helpers::dateToNepali($finalfrom) .")" :'' }}</p>
            </td>
            <td>
                <p>To Date: {{ $finalto }} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateToNepali($finalto) .")" :'' }}</p>
            </td>
            <td>
                <p>Category: {{ $category }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Billing Mode: {{ $billingmode }}</p>
            </td>
            <td>
                <p>Comp: {{ $comp }}</p>
            </td>
            <td>
                <p>CutOff Amt: Rs. {{ $cutOffAmount }}</p>
            </td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Category</th>
                <th>Mark Count</th>
                <th>Mark Amount</th>
                <th>Total Count</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dateRange as $date)
                @php
                    $tempDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                    $totalMarkCount = 0;
                    $totalMarkAmount = 0;
                    $totalCount = 0;
                    $totalAmount = 0;
                @endphp
                <tr>
                    <td colspan="5" style="text-align: center;"><b>{{ $tempDate }}</b></td>
                </tr>
                @if(array_key_exists($tempDate, $markDatas) || array_key_exists($tempDate, $totalDatas))
                    @foreach ($allCategories as $cat)
                        <tr>
                            <td>{{ $cat }}</td>
                            @if(array_key_exists($tempDate, $markDatas) && array_key_exists($cat, $markDatas[$tempDate]))
                                @php
                                    $totalMarkCount += $markDatas[$tempDate][$cat][0]['ptcount'];
                                    $totalMarkAmount += $markDatas[$tempDate][$cat][0]['patsum'];
                                @endphp
                                <td>{{ $markDatas[$tempDate][$cat][0]['ptcount'] }}</td>
                                <td>Rs. {{ $markDatas[$tempDate][$cat][0]['patsum'] }}</td>
                            @else
                                <td>0</td>
                                <td>Rs 0.00</td>
                            @endif
                            @if(array_key_exists($tempDate, $totalDatas) && array_key_exists($cat, $totalDatas[$tempDate]))
                                @php
                                    $totalCount += $totalDatas[$tempDate][$cat][0]['ptcount'];
                                    $totalAmount += $totalDatas[$tempDate][$cat][0]['patsum'];
                                @endphp
                                <td>{{ $totalDatas[$tempDate][$cat][0]['ptcount'] }}</td>
                                <td>Rs. {{ $totalDatas[$tempDate][$cat][0]['patsum'] }}</td>
                            @else
                                <td>0</td>
                                <td>Rs 0.00</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    @foreach ($allCategories as $cat)
                        <tr>
                            <td>{{ $cat }}</td>
                            <td>0</td>
                            <td>Rs 0.00</td>
                            <td>0</td>
                            <td>Rs 0.00</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td><b>{{ $tempDate }}</b></td>
                    <td><b>{{ $totalMarkCount }}</b></td>
                    <td><b>Rs. {{ $totalMarkAmount }}</b></td>
                    <td><b>{{ $totalCount }}</b></td>
                    <td><b>Rs. {{ $totalAmount }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
