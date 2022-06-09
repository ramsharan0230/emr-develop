@if($data)
    <div class="row ul-style">
        <div class="col-md-6">
            @if($type === 'service')
                <div class="group-row">
                    <select name='service-medicine' class='form-control col-md-6' id="select-service-list" onclick='mapAccount.listData()'>
                        <option>Select</option>
                        @foreach ($data as $service)
                            <option value='{{ $service->flditemtype }}'>{{ $service->flditemtype }}</option>
                        @endforeach
                    </select>
                </div>
                <div id='service-append'></div>
            @else
                <div>
                    <input type="checkbox" id="checkall" /> <label for="checkall">Check All</label>
                </div>

                <ul class="res-table" id="map-search">
                    @foreach ($data as $key => $service)
                        <li>
                            <input type='checkbox' class="check-all-items" name='itemMap[]' id='map-{{$key}}' value='{{ $service->fldstockid }}'>
                            &nbsp;<label for='map-{{$key}}'> {{ $service->fldstockid }}</label>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-md-6">
            <div id="existing-list"></div>
        </div>
    </div>
@endif
<script>
    $('#checkall').on('click', function(e) {
        $('.check-all-items').prop('checked', $(e.target).prop('checked'));
    });
</script>
