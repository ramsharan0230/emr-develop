@forelse ($discountList as $list)
    <tr>
        <td>
            <input type="checkbox" name="no_discount[]" value="{{$list->flditemname}}">
        </td>
        <td class="checkboxtext" style="cursor: pointer"> {{$list->flditemname }} </td>
    </tr>
@empty
    <tr> No item found </tr>
@endforelse
