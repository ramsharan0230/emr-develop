<option value="">--Select--</option>
@if(count($newOrderData))
    @foreach($newOrderData as $new)
        <option value="{{ $new->fldbrandid }}" data-type="{{ $new->drug->fldroute }}">
            {{ $new->drug->fldroute }} | {{ $new->fldbrand }} | QTY {{ $new->entry ? $new->entry->sum('fldqty') : '' }} | {{ $new->flddeflabel }}
        </option>
    @endforeach
@endif
@if(count($newOrderDataSurgical))
    @foreach($newOrderDataSurgical as $new)
        <option value="{{ $new->fldsurgid }}" data-type="{{ $new->fldsurgcateg }}">
            {{ $new->fldsurgid }} | ({{ $new->Surgical ? $new->Surgical->fldsurgcateg : "" }})
        </option>
    @endforeach
@endif


{{--<input id="search-pharmacy-new-order-list" class="form-control mb-2" type="text" placeholder="Search..">
@php
    $count_lable_pharmacy = 199;
@endphp
<div style="height: 300px; overflow-y: scroll;">
    <table class="table table-sm" id="search-pharmacy-new-order-list-table">
        <tbody>

        @if(count($newOrderData))
            @foreach($newOrderData as $new)
                <tr>
                    <td>
                        <input type="radio" name="neworder_add" id="pharmacy-new-order{{$count_lable_pharmacy}}" value="{{ $new->fldbrandid }}">
                    </td>
                    <td>
                        <label for="pharmacy-new-order{{$count_lable_pharmacy}}">{{ $new->fldbrandid }}</label>
                    </td>
                    <td class="text-center">
                        @if($new->entry)
                            @if(strtotime($new->entry[0]->fldexpiry) > strtotime('1 day') && strtotime($new->entry[0]->fldexpiry) < strtotime('-3 months ago'))
                                <a href="javascript:;" style="padding: 5px; background-color: #238de7;" title="Less than 3 months"></a>
                            @elseif(strtotime($new->entry[0]->fldexpiry) > strtotime('-6 months ago'))
                                <a href="javascript:;" style="padding: 5px; background-color: #08e794;" title="Greater than 6 months"></a>
                            @else
                                <a href="javascript:;" style="padding: 5px; background-color: #ff1700;" title="Expired"></a>
                            @endif
                        @endif
                    </td>
                    <td>{{ isset($new->Drug)?$new->Drug->fldroute:'' }}</td>
                    <td>{{ isset($new->Drug)?$new->Drug->fldstrength:'' }}</td>
                    <td>{{ isset($new->Drug)?$new->Drug->fldstrunit:'' }}</td>
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
--}}
