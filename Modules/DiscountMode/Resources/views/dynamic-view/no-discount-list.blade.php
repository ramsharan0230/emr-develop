@if($discountList)
    @forelse($discountList as $list)
        <tr>
            <td>   <input type="checkbox" name="no_discount_remove[]" value="{{$list->flditemname}}"> </td>
            <td class="checkboxtext" style="cursor: pointer" > {{$list->flditemname}}</td>
            {{-- <td><a href="javascript:;" onclick="discountDelete.deleteNoDiscount('{{$list->flditemname}}')"><i class="fa fa-trash text-danger"></i></a></td> --}}
            {{-- <td><a href="javascript:;" onclick="discountDelete.deleteNoDiscount('{{$list->flditemname}}')"><i class="fa fa-trash text-danger"></i></a></td> --}}
        </tr>
    @empty
    @endforelse
@endif
<script>
    discountDelete={
        deleteNoDiscount:function (itemToDelete) {
            confirmTest = confirm("Delete?");
            if(confirmTest === false){
                return false;
            }
            $.ajax({
                url: '{{ route('patient.discount.mode.delete.items') }}',
                type: "POST",
                data: {itemToDelete: itemToDelete, '_token': '{{ csrf_token() }}'},
                success: function (response) {
                    // console.log(response);
                    $('#after-add-list').empty();
                    $('#after-add-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }
</script>
