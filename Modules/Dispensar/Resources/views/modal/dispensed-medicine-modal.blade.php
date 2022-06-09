<style type="text/css">
    .dispensed-medicine-list-encounter{
        height: 70vh;
        overflow-y: auto;
    }
</style>
<div class="modal fade" id="dispensed-medicine-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Dispensed Medicine List</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-user-list dispensed-medicine-list-encounter">
                <table class="table table-bordered table-hover table-striped dispensed-list">
                    <thead class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Route</th>
                            <th>Particulars</th>
                            <th>Dose</th>
                            <th>Freq</th>
                            <th>Day</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>User</th>
                            <th>Disc%</th>
                            <th>Tax%</th>
                            <th>Total</th>
                            
                        </tr>
                    </thead>
                    <tbody id="js-dispensed-medicine-tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="printReport" onclick="reorder()">Re-Order</button> -->
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $(document).on('click', '.dispensed-medicine', function () {
        var closeCheckbox = $(this).find('input[type=checkbox]'); 
        if (closeCheckbox.prop('checked') == false) {
          closeCheckbox.prop('checked', true);
        } else {
            closeCheckbox.prop('checked', false);
        }
        
        
    });
    function reorder(){
        // alert('reorder');
        var medicines = [];
        $.each($("input[name='med']:checked"), function(){
            medicines.push($(this).val());
        });
        $.ajax({
            url: baseUrl + '/dispensingForm/saveMedicines',
            type: "POST",
            dataType: "json",
            data: {ids: medicines, length:$('#js-dispensing-medicine-tbody tr').length, subtotal:$('#js-dispensing-subtotal-input').text(), total:$('#js-dispensing-nettotal-input').text()},
            success: function (response) {
                // console.log(response);
                if ($.isEmptyObject(response.error)) {
                    $('#dispensed-medicine-modal').modal('hide');
                    $("#ordered-radio").prop("checked", true);
                    $('#js-dispensing-medicine-tbody').append(response.data);
                    $('#js-dispensing-subtotal-input').text(response.finalsubtotal.toFixed(2));
                    $('#js-dispensing-nettotal-input').text(response.finaltotal.toFixed(2));
                    $('#js-dispensing-discounttotal-input').text((response.finalsubtotal.toFixed(2)-response.finaltotal.toFixed(2)).toFixed(2));
                    if(response.outofstockitem !=''){
                        alert(response.outofstockitem + ' are out of stock');
                    }
                    $.each(response.patdata, function(index, value) {
                      
                      var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + value.fldstockno + '"]').text().split(' | ');
                      var stock =  $('#js-dispensing-medicine-input option[data-fldstockno="' + value.fldstockno + '"]').attr('fldqty') || '';
                        // alert(stock);
                      var remainingStock = parseInt(stock)-parseInt(value.fldqtydisp);

                      $('#js-dispensing-medicine-input option[value="' + value.flditem + '"]').attr('fldqty', (remainingStock));
                    optionAll[4] = "QTY " + remainingStock;
                      $('#js-dispensing-medicine-input option[value="' + value.flditem + '"]').text(optionAll.join(' | '));
                      $('#js-dispensing-medicine-input').select2("destroy").select2();
                      
                      // return false;
                      // Will stop running after "three"
                      // return (value !== 'three');
                    });
                    showAlert(response.message, status);
                } else {
                    showAlert('Something went wrong!!');
                }
            }
        });
    }
</script>