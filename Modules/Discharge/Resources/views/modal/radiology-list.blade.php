<div class="modal fade" id="radiology-list-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Radiology Test</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-radiology-list">
                <table class="table table-striped table-hover table-bordered ">
                    <thead class="thead-light">
                        <tr>
                            <th>Test Name</th>

                            <th>Reported Time</th>
                        </tr>
                    </thead>
                    <tbody id="form-data-radiology-table-list">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReport" onclick="addRadio()">Add Test</button>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function addRadio(){
        $('form').submit(false);
        var selectedradiotest = new Array();
        $("input[name='radiotests[]']:checked").each(function() {
               selectedradiotest.push($(this).val());
          });
        if(selectedradiotest.length === 0){
            alert('Please choose radiology test to add');
            return false;
        }else{
           $.ajax({
                url: '{{ route('discharge.radio.details') }}',
                type: "POST",
                data: {
                    tests: selectedradiotest
                },
                success: function (response) {
                    $('#radiology-test').val(response);

                    $('#radiology-list-modal').modal('hide');

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

    }
</script>
