

<table>
    <tr>
        <td colspan="8" style="text-align: center; font-size: 14px; font-weight: 700;">Trade {{$selected_page}} Ageing Report for NPD as at {{$startTime}}</td>
    </tr>
    @php
    $interval = 25;//Options::get('ageing_interval');
    $in = $interval;
         $x = $in + $in;
         $y = $x + $in;
         $z = $y+ $in;
         $datelimitin = date('Y-m-d',strtotime('+'.$in.' day', strtotime($startTime)));
         $datelimitx = date('Y-m-d',strtotime('+'.$x.' day', strtotime($startTime)));
         $datelimity = date('Y-m-d',strtotime('+'.$y.' day', strtotime($startTime)));
         $datelimitz = date('Y-m-d',strtotime('+'.$z.' day', strtotime($startTime)));
         $datelimitzmore =  $datelimitz;
    @endphp
    <tr>
        <td>Business Unit</td>
        <td>NDP</td>
        <td></td>
        <td></td>
        <td></td>
        <td>Not Due</td>
        <td>{{$startTime}}</td>
        <td>{{$startTime}}</td>
    </tr>
    <tr>
        <td>Ageing as at</td>
        <td>{{$startTime}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$in}} Days</td>
        <td>{{$startTime}}</td>
        <td>{{$datelimitin}} </td>
    </tr>
    <tr>
        <td>Period to</td>
        <td>{{$datelimitzmore}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$x}} Days</td>
        <td>{{$startTime}}</td>
        <td>{{$datelimitx}} </td>
    </tr>
    <tr>
        <td>Account from</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$y}} days</td>
        <td>{{$startTime}}</td>
        <td>{{$datelimity}} </td>
    </tr>
    <tr>
        <td>Account to</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$z}} Days</td>
        <td>{{$startTime}}</td>
        <td>{{$datelimitz}} </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$z}}+</td>
        <td>{{$datelimitzmore}}</td>
        <td> </td>
    </tr>

    <tr>
        <td colspan="8"></td>
    </tr>


<tr>
    <td></td>
    <td></td>
    <td>0 Days</td>
    <td>{{$in}} Days</td>
    <td>{{$x}} Days</td>
    <td>{{$y}} Days</td>
    <td>{{$z}} Days</td>
</tr>
    @if(isset($results))
   @php
   $c=0;
   @endphp
    @foreach($results as $keys => $aa)

        <tr>
        <td>{{$keys}}</td>
        <td>{{\App\Utils\Helpers::getAccountName($keys)}}</td>

                @foreach($aa as $kk => $a)
                    <td>{{$a}}</td>

                @endforeach


        </tr>
        @endforeach


                    @endif

</table>

