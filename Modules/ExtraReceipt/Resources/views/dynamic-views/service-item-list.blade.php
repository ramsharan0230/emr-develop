<table class="table table-striped table-bordered table-hover">
    <thead class="thead-light">
    <tr>
        <th>S.N.</th>
        <th style="width: 60%;">Items</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Dis%</th>
        <th class="text-center">Tax%</th>
        <th class="text-center">Total Amount</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody id="billing-body">
    @if ($serviceData)
        @forelse ($serviceData as $service)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$service->flditemname}} </td>
                <td class="text-center">
                    <input type="text" name="quantity[]" class="quantity-change" fldid="{{$service->fldid}}" value="{{$service->flditemqty}}" style="width: 40%;">
                </td>
                <td class="text-center">{{$service->flditemrate}} </td>
                <td class="text-center"><input type="text" name="dis[]" class="discount-change" fldid="{{$service->fldid}}" value="{{$service->flddiscper}}" style="width: 40%;"></td>
                <td class="text-center"><input type="text" name="tax[]" value="0" style="width: 40%;"></td>
                <td class="text-center">{{$service->fldditemamt}} </td>
                <td class="text-center">
                    <a href="javascript:;" class="delete-billing-row" onclick="return confirm('Delete?')" rel="{{$service->fldid}}">
                        <i class='fa fa-trash text-danger'></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No Items Added</td>
            </tr>
        @endforelse
    @endif
    </tbody>
    <thead class="thead-light">
    <tr>
        <th>&nbsp;</th>
        <th>Total</th>
        <th colspan="2" class="text-right"></th>
        <th colspan="2" class="text-right"></th>
        <th class="text-right table-bill-total">{{ $total }}</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
</table>
<script>
    $(".delete-billing-row").click(function () {
        // alert($(this).attr('rel'));
        $.ajax({
            url: "{{ route('billing.delete.items.by.service') }}",
            type: "POST",
            data: {
                fldid: $(this).attr('rel')
            },
            success: function (data) {
                $("#billing-body").empty().append(data.message.tableData);
                $("#sub-total-data").empty().append(data.message.total + data.message.discount);
                $("#grand-total-data").empty().append(data.message.total);
                $("#table-bill-total").empty().append(data.message.total);
                $("#discount-total").empty().append(data.message.discount);
                showAlert('Delete successfully.');
            }
        });
    });

    $(document).ready(function () {
        $(".quantity-change").blur(function () {
            fldid = $(this).attr('fldid');
            new_quantity = $(this).val();
            $.ajax({
                url: "{{ route('billing.change.quantity.service') }}",
                type: "POST",
                data: {
                    fldid: fldid, new_quantity: new_quantity
                },
                success: function (data) {
                    $("#billing-body").empty().append(data.message.tableData);
                    $("#sub-total-data").empty().append(data.message.total + data.message.discount);
                    $("#grand-total-data").empty().append(data.message.total);
                    $("#table-bill-total").empty().append(data.message.total);
                    $("#discount-total").empty().append(data.message.discount);
                    showAlert('Quantity change successfully.');
                }
            });
        });
        $(".discount-change").blur(function () {
            fldid = $(this).attr('fldid');
            new_discount = $(this).val();
            $.ajax({
                url: "{{ route('billing.change.discount.service') }}",
                type: "POST",
                data: {
                    fldid: fldid, new_discount: new_discount
                },
                success: function (data) {
                    $("#billing-body").empty().append(data.message.tableData);
                    $("#sub-total-data").empty().append(data.message.total + data.message.discount);
                    $("#grand-total-data").empty().append(data.message.total);
                    $("#table-bill-total").empty().append(data.message.total);
                    $("#discount-total").empty().append(data.message.discount);
                    showAlert('Discount added successfully.');
                }
            });
        });
    })
</script>

