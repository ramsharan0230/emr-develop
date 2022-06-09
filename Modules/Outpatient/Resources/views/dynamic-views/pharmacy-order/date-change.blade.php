<form class="pharmacy-add-order-change-fields">
    @csrf
    {!! $html !!}
    <button type="button" class="btn btn-primary" onclick="formSubmitPharmacyNewOrder()">Save</button>
</form>

<script type="text/javascript">
    $('#datepicker_pharmacy_start_date').datetimepicker({
        // maxDate: dateToday,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        // yearRange: "-100",
    }).datetimepicker('setDate', '{{ $Pathdosing->fldstarttime }}');

    function formSubmitPharmacyNewOrder(){
        $.ajax({
            url: "{{ $routeForForm }}",
            type: "POST",
            data: $('.pharmacy-add-order-change-fields').serialize(),
            success: function (data) {
                // console.log(data);
                $('#new_orders_list').empty();
                $('#new_orders_list').html(data);
                $('#general-modal').modal('hide');
            },
            error: function (xhr, err) {
                console.log(xhr);
            }
        });
    }

</script>
