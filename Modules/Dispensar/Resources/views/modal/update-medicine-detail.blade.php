<div class="modal fade" id="update-medicine-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            @php
                $frequencies = Helpers::getFrequencies();
            @endphp
            <div class="modal-body">
                <div class="form-update-medicine-details">
                    <form id="med-detail">
                        <div id="dosehtml">

                        </div>
                        <label>Frequencies : </label>
                        <select name="freq" class="form-control" {{ (Options::get('dispensing_freq_dose') == 'Auto') ? 'disabled' : '' }}>
                            <option value="">--Select--</option>
                            @foreach($frequencies as $frequency)
                            <option value="{{ $frequency }}" {{ (Options::get('dispensing_freq_dose') == 'Auto' && $frequency == '1') ? 'selected' : '' }}>{{ $frequency }}</option>
                            @endforeach
                        </select>
                        <div id="med-field">

                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReport" onclick="updateEntity()">Update</button>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function onlyNumberKey(evt) {

        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
    function updateEntity(){
        var qty = $('#updateqty').val();
        if(qty <= 0){
            alert('Quantity cannnot be less than  or equal to 0');
            return false;
        }
        var stocknumber = $('#stocknumber').val();
        var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text().split(' | ');
        var stock =  $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').attr('fldqty') || '';

        if(parseInt(qty) > parseInt(stock)){
            alert('Quantity cannnot be greater than '+stock);
            return false;
        }
        var orderType = $('input[name="radio1"][type="radio"]:checked').val();
        $.ajax({
            url: baseUrl + '/dispensingForm/updateEntity',
            type: "POST",
            data: $('#med-detail').serialize() + "&orderType=" + orderType,
            //data: $('#med-detail').serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);

                $('#js-dispensing-medicine-tbody').html(response.html);
                $('#js-dispensing-subtotal-input').val(numberFormatDisplay(response.subtotal));
                $('#js-dispensing-totalvat-input').val(numberFormatDisplay(response.taxtotal));
                $('#js-dispensing-nettotal-input').val(numberFormatDisplay(response.total));
                $('#js-dispensing-discounttotal-input').val(numberFormatDisplay(response.dsicountetotal));
                $('#js-dispensing-discount-input').text(response.discountpercent);
                $('#update-medicine-modal').modal('hide');
                $('#discount_type_change').val('');
                // $('#js-dispensing-discounttotal-input').val('');
                // $('#js-dispensing-discount-input').val('');
                var medicine = $('#update_medicine').val();


                var remainingStock = parseInt(stock)-parseInt(qty);
                if(remainingStock > stock){
                    var finalstock = stock;
                }else{
                    var finalstock = remainingStock;
                }

                  // $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').attr('fldqty', (remainingStock));
                optionAll[4] = "QTY " + finalstock;
                  $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text(optionAll.join(' | '));
                  $('#js-dispensing-medicine-input').select2("destroy").select2();

                showAlert('Data Updated');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>