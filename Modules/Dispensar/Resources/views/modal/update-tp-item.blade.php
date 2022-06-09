<div class="modal fade" id="update-tp-item-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Quantity - "<i><span id="medicine_name"></span></i>"</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>


            <div class="form-update-tp-details">
                <form id="tp-detail">
                    <input type="number" name="new_qty" id="new_qty" class="form-control" value="" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                    <input type="hidden" name="existing_qty" id="existing_qty" class="form-control" value="">
                    <input type="hidden" name="fldid" id="patbill_fldid" value="">
                    <input type="hidden" name="encounter_id" id="tp_encounterId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReport" onclick="updateTPItemQuantity()">Update</button>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function updateTPItemQuantity(){
        var newqty = $('#new_qty').val();
        var oldqty = $('#existing_qty').val();
        if(parseInt(newqty) > parseInt(oldqty)){
            alert('Quantity cannot be greater than '+oldqty);
            return false;
        }
        if(parseInt(newqty) <= 0){
            alert('Quantity cannot be less than or equal to 0');
            return false;
        }
        $.ajax({
            url: baseUrl + '/dispensingForm/updateTPItemQuantity',
            type: "POST",
            data: $('#tp-detail').serialize(), 
            success: function (response) {
                console.log(response);
                $('#js-tp-bill-list-tbody').empty().html(response.mainhtml);
                $('.depAmount').text(response.data.totalDepositAmountReceived);
                $('.tpAmount').text(response.data.totalTPAmountReceived);
                $('.remainingAmount').text(response.data.remaining_deposit);
                $('#update-tp-item-modal').modal('hide');
                  getMedicineList();
                showAlert('Data Updated');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>