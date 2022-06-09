@if($data)
    <div>
        <input type="checkbox" id="checkall" /> <label for="checkall">Check All</label>
    </div>
    <ul class="res-table" id="map-search">
        @foreach ($data as $key => $service)
            <li>
                <input type='checkbox' name='itemMap[]' class="check-all-items" id='map-{{$key}}' value='{{ $service->flditemname }}'>
                &nbsp;<label for='map-{{$key}}'> {{ $service->flditemname }}</label>
            </li>
        @endforeach
    </ul>
@endif
<script>
    $('#checkall').on('click', function(e) {
        $('.check-all-items').prop('checked', $(e.target).prop('checked'));
    });
</script>
