<table id="myTable1" data-show-columns="true"
                                    data-search="true"
                                    data-search-align="left"
                                    data-show-toggle="true"
                                    data-pagination="true"
                                    data-resizable="true">
<thead>
    <tr>
        <th>Department</th>
        @php
        $begin = new \DateTime( $from_date );
        $end   = new \DateTime( $to_date );
        @endphp
        @for($i = $begin; $i <= $end; $i->modify('+1 day'))
            @php
                $nepmonth = Helpers::dateEngToNepdash($i->format('Y-m-d'))->nmonth;
                $nepdate = Helpers::dateEngToNepdash($i->format('Y-m-d'))->date;
            @endphp
            <th>{{$nepmonth}}-{{$nepdate}}</th>
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