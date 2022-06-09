<table class="table" id="table">
    <thead>
    <tr>
        <th>Rank</th>
        <th>Test Name</th>
        <th>Numbers of Test Done</th>
    </tr>
    </thead>
    <tbody>
    @if(!$top_lab_test->isEmpty())
    <?php 
        $count = 1; 
    ?>
    @foreach ($top_lab_test as $list)
    <tr>
        <td>{{$count++}}</td>
        <td>{{$list->fldtestid}} </td>
        <td>{{$list->test_count}} </td>
    </tr> 
    @endforeach
    @endif                                   
    </tbody>
</table>     