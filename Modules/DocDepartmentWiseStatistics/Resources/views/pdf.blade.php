@extends('inpatient::pdf.layout.main')

@section('title')
Department Wise Statistics
@endsection

@section('content')

<style>
.content-body th,.content-body td{
    text-align: left;
}

.heading {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
</style>
<div style="width: 100%;"><h4 style="text-align:center;">Department Wise Statistics</h4></div>
    <div style="width: 100%;">
        <div class="heading" style="width: 100%; margin-bottom: 10px;">
            <div>Date: {{\Carbon\Carbon::parse($from_date)->format('Y-m-d')}} to {{\Carbon\Carbon::parse($to_date)->format('Y-m-d')}}</div>
            <div style="display: flex; flex-direction: column;">
                <div>Printed Date: 2029-31-54 02"12"23</div>
                <div>Printed By: Cogent Hospital Nepal</div>
            </div>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th rowspan="2">Department</th>
                <th colspan="15" style="text-align: center;">Chaitra</th>
                <th colspan="15" style="text-align: center;">Baisakh</th>
            </tr>
            <tr>
            @php
                $begin = new \DateTime( $from_date );
                $end   = new \DateTime( $to_date );
                @endphp
                @for($i = $begin; $i <= $end; $i->modify('+1 day'))
                    @php
                    $nepmonth = Helpers::dateEngToNepdash($i->format('Y-m-d'))->nmonth;
                    $nepdate = Helpers::dateEngToNepdash($i->format('Y-m-d'))->date;
                    @endphp
                    <!-- <th>{{$nepmonth}}-{{$nepdate}}</th> -->
                    <th>20</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @if($patients)
                @foreach($patients as $key=>$patient)
                <tr>
                    <td>{{ $patient['name'] }}</td>
                    @php
                    $begin = new \DateTime( $from_date );
                    $end   = new \DateTime( $to_date );
                    @endphp
                    @for($j = $begin; $j <= $end; $j->modify('+1 day'))
                        @php 
                        $date = $j->format("Y-m-d");
                        $dates = array_keys($patient['dates']);
                        if(in_array($date,$dates)){
                            $count = $patient['dates'][$date];
                            echo "<td>" . $count ."</td>";
                        }else{
                            echo "<td></td>";
                        }
                        @endphp
                    @endfor
                </tr>
                @endforeach
            @endif
            @if($others)
                <tr>
                    <td>Others</td>
                    @php
                        $begin = new \DateTime( $from_date );
                        $end   = new \DateTime( $to_date );
                    @endphp
                    @for($k = $begin; $k <= $end; $k->modify('+1 day'))
                    @php 
                    $date = $k->format("Y-m-d");
                    if(in_array($date,array_keys($others))){
                        echo "<td>" . $others[$date] ."</td>";
                    }else{
                        echo "<td></td>";
                    }
                    @endphp
                    @endfor
                </tr>
            @endif
        </tbody>
    </table>
@endsection