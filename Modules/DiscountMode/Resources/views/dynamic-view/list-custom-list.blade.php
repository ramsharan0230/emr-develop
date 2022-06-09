@if ($discountList)
    @foreach ($discountList as $list)
        <tr>
            <td> {{$list->flditemtype}}</td>
            <td> {{$list->flditemname}}</td>
            <td> {{$list->fldpercent}}</td>
            <td><a href="javascript:;" onclick="deleteCustomDiscountByType('{{ $list->fldid }}','{{ $list->fldtype }}')"><i class="fa fa-trash text-danger"></i></a></td>
        </tr>
    @endforeach
@endif
<script>
    function deleteCustomDiscountByType(fldid, fldtype) {
        confirmTest = confirm("Delete?");
        if (confirmTest === false) {
            return false;
        }
        $.ajax({
            url: '{{ route('patient.discount.mode.delete.custom.discount.by.type') }}',
            type: "POST",
            data: {fldid: fldid, fldtype: fldtype, '_token': '{{ csrf_token() }}'},
            success: function (response) {
                // console.log(response);
                $('#custom-discount-list').empty().append(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>
