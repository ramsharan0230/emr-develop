<form class="pharmacy-add-order-change-fields form-inline">
    @csrf
    <div class="form-group">
    {!! $html !!}
    </div>
    <div class="form-group mx-sm-3">
    <select name="pharmacy_data_value" class="form-control col-sm-12">
        <option value="1" {{ $Pathdosing->fldfreq == '1'?'selected':'' }}>1</option>
        <option value="2" {{ $Pathdosing->fldfreq == '2'?'selected':'' }}>2</option>
        <option value="3" {{ $Pathdosing->fldfreq == '3'?'selected':'' }}>3</option>
        <option value="4" {{ $Pathdosing->fldfreq == '4'?'selected':'' }}>4</option>
        <option value="6" {{ $Pathdosing->fldfreq == '6'?'selected':'' }}>6</option>
        <option value="PRN" {{ $Pathdosing->fldfreq == 'PRN'?'selected':'' }}>PRN</option>
        <option value="SOS" {{ $Pathdosing->fldfreq == 'SOS'?'selected':'' }}>SOS</option>
        <option value="stat" {{ $Pathdosing->fldfreq == 'stat'?'selected':'' }}>stat</option>
        <option value="AM" {{ $Pathdosing->fldfreq == 'AM'?'selected':'' }}>AM</option>
        <option value="HS" {{ $Pathdosing->fldfreq == 'HS'?'selected':'' }}>HS</option>
        <option value="Pre" {{ $Pathdosing->fldfreq == 'Pre'?'selected':'' }}>Pre</option>
        <option value="Post" {{ $Pathdosing->fldfreq == 'Post'?'selected':'' }}>Post</option>
        <option value="Hourly" {{ $Pathdosing->fldfreq == 'Hourly'?'selected':'' }}>Hourly</option>
        <option value="Alt day" {{ $Pathdosing->fldfreq == 'Alt '?'selected':'' }}>Alt day</option>
        <option value="Weekly" {{ $Pathdosing->fldfreq == 'Weekly'?'selected':'' }}>Weekly</option>
        <option value="Biweekly" {{ $Pathdosing->fldfreq == 'asdf'?'selected':'' }}>asdf</option>
        <option value="Tryweekly" {{ $Pathdosing->fldfreq == 'Tryweekly'?'selected':'' }}>Tryweekly</option>
        <option value="Monthly" {{ $Pathdosing->fldfreq == 'Monthly'?'selected':'' }}>Monthly</option>
        <option value="Yearly" {{ $Pathdosing->fldfreq == 'Yearly'?'selected':'' }}>Yearly</option>
        <option value="Tapering" {{ $Pathdosing->fldfreq == 'Tapering'?'selected':'' }}>Tapering</option>
    </select>
    </div>
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

    function formSubmitPharmacyNewOrder() {
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
