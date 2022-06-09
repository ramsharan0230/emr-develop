{{--<option value="">--Select--</option>
@if(count($newOrderData))
    @foreach($newOrderData as $new)
        <option value="{{ $new->fldsurgid }}">{{ $new->fldsurgid }}</option>
    @endforeach
@endif--}}

<input id="search-pharmacy-new-order-list" class="form-control mb-2" type="text" placeholder="Search..">
@php
    $count_lable_pharmacy = 179;
@endphp
<div style="height: 300px; overflow-y: scroll;">
    <table class="table table-sm" id="search-pharmacy-new-order-list-table">
        <tbody>

        @if(count($newOrderData))
            @foreach($newOrderData as $new)
                <tr>
                    <td>
                        <input type="radio" name="neworder_add" id="pharmacy-new-order{{$count_lable_pharmacy}}" value="{{ $new->fldsurgid }}">
                    </td>
                    <td>
                        <label for="pharmacy-new-order{{$count_lable_pharmacy}}">{{ $new->fldsurgid }}</label>
                    </td>
                </tr>
                @php
                    $count_lable_pharmacy++;
                @endphp
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<hr>
<a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="pharmacyPopup.clickMedSelect()">Select</a>
<script>
    $(document).ready(function () {
        $("#search-pharmacy-new-order-list").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#search-pharmacy-new-order-list-table tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
