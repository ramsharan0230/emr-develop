
    <div class="modal fade" id="inventory-expiry-modal">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="inventory-expiry-title">Select Expiry Date</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;</button>
                </div>
                <form>
                <!-- Modal body -->
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div id="expirydatepicker"></div>
                            </div>
                            <input type="text" name="expiry_date" id="expiry_date" class="form-control">
                        </div>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary"  onclick="exportExpiryDatepdf()">OK</button>
                  <button type="button" class="btn btn-danger removecurrent" data-dismiss="modal" data-target="#inventory-expiry-modal" >Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript">
    var inventoryMainMenu = {
        chooseExpiryDate: function () {
            $('#inventory-expiry-modal').modal('show');
        },
    }
    $('#expirydatepicker').datepicker({
       // useCurrent: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd', 
        inline: true,
        sideBySide: true
    });
    $('#expirydatepicker').data('#expiry_date');

    function exportExpiryDatepdf(){
        var dateval = $('#expirydatepicker').val();

        var urlReport = baseUrl + "/inventory/expirydate-report?date=" + dateval + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');
    }
</script>

