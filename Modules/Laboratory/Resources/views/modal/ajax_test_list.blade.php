<table 
    id="myTable12" 
    data-show-columns="true"
    data-search="true"
    data-search-align="left"
    data-show-toggle="true"
    data-pagination="true"
    data-resizable="true"
>
    <thead class="thead-light">
        <tr>
            <th>S.N.</th>
            <th>Test Id</th>
        </tr>
    </thead>
    <tbody id="">
        @if(!$unsampled_test->isEmpty())
            @php
                $count=1;
            @endphp
            @foreach ($unsampled_test as $list)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$list->testid}}</td>
            @endforeach
        @endif
    </tbody>
</table>