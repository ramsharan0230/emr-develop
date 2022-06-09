@if (isset($result) && count($result) > 0)
    @foreach ($result as $k => $data)
        <tr>
            <td>{{ ++$k }}</td>
            <td>{{ $data->flditemtype }}</td>
            <td>{{ $data->flditemname }}</td>
            <td>{{ $data->serviceCost->flditemcost}}</td>
            <td>{{ $data->flditemqty }}</td>
            <td>{{ $data->discount_per }}</td>
            <td>{{ ($data->serviceCost->flditemcost * $data->flditemqty) - ( $data->discount_per/100 * ($data->serviceCost->flditemcost * $data->flditemqty) ) }}</td>
            <td>
                {{-- @dump($data->price_editable) --}}
                @if ($data->price_editable == 1 )
                    <a href="javascript:void(0);" onclick="editpackage({{ $data->fldid }}, '{{ $data->flditemname }}')" data-qty="{{ $data->flditemqty }}" data-discount="{{ $data->discount_per }}" id="edit-{{ $data->fldid }}"><i class="fas fa-edit text-primary-2"></i></a> |
                    <a href="javascript:void(0);" onclick="deletepackage({{ $data->fldid }})"><i class="fa fa-trash text-danger"></i></a>
                @endif
            </td>
        </tr>
    @endforeach
@endif

