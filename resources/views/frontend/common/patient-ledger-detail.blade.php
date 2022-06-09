<div class="modal fade patient-ledger-detail-modal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table" id="ajaxresultBillData">
                <table id="patient-ledger-bill-table">
                    <thead class="thead-light">
                    <tr>
                        <th>SN.</th>
                        <th>Encounter</th>
                        <th>Bill No.</th>
                        <th>Item Name</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Total amount</th>
                        <th>Received amount</th>
                        <th>User</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-light">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th class="ledger-total"></th>
                    <th></th>
                </tr>
                </thead>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).on("click", ".list-bill-detail", function (e) {
        var url = "{{ route('patient.ledger.getBillData') }}";
        var value =$(this).data('value');
        var billno =$(this).html();
        $.ajax({
            url: url ,
            type: "GET",
            data: {
                encounterval: value,
                billno:billno
            },
            success:  function (response) {
                if(response.data){
                    $('#ajaxresultBillData').html(response.data.html);
                    $('.patient-ledger-detail-modal').modal('show');
                setTimeout(function(){
                    $('.patient-ledger-detail-modal #patient-ledger-bill-table').bootstrapTable();
                },50)

                    $('.ledger-total').html(response.data.total);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert("{{ __('messages.error') }}", 'error')
            }
        });
    });
    function getItemValue() {
        return new Promise((resolve) => setTimeout(resolve, 0));
    }
    $(function() {
        $('#patient-ledger-bill-table').bootstrapTable();
    })
</script>
