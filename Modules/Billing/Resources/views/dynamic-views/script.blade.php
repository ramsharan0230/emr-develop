<script>
    $(".delete-billing-row").click(function () {
        // alert($(this).attr('rel'));
        $.ajax({
            url: "{{ route('billing.delete.items.by.service') }}",
            type: "POST",
            data: {
                fldid: $(".delete-billing-row").attr('rel')
            },
            success: function (data) {
                $("#billing-body").empty().append(data.message.tableData);
                $("#sub-total-data").empty().append(data.message.total);
                $("#grand-total-data").empty().append(data.message.total);
                $("#table-bill-total").empty().append(data.message.total);
                $(".dynamic-script").empty().append(data.message.script);
                alert('Added successfully.');
            }
        });
    });
</script>
