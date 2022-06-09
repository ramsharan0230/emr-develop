
<div class="modal fade" id="tpbill-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">TP Bill Item List</h4>
                <button type="button" class="close uncheck" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-user-list dispensed-medicine-list-encounter">
                <table class="table table-bordered table-hover table-striped dispensed-list">
                    <thead class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Type</th>
                            <th>Particulars</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>User</th>
                            <th>SubTotal</th>
                            <th>Discount</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Ordered Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="js-tp-bill-list-tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary uncheck" data-dismiss="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.uncheck').on('click', function(){
         $("#tpbill").prop("checked", false);
    })
</script>