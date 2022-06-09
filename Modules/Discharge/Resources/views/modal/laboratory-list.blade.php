<div class="modal fade" id="laboratory-list-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Laboratory Tests</h4>
               
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-laboratory-list">
                <table class="table table-striped table-hover table-bordered ">
                    <thead class="thead-light">
                        <tr>
                            <th><input type="checkbox" name="" id="lab-list"></th>
                            <th>Test Name</th>
                            <th>Unit</th>
                            <th>Result</th>
                            <th>Reported Time</th>
                        </tr>
                    </thead>
                    <tbody id="form-data-laboratory-table-list">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReport" onclick="addLab()">Add Test</button>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function addLab(){
        // alert('add lab');
        $('form').submit(false);
        var selectedtest = new Array();
        $("input[name='tests[]']:checked").each(function() {
               selectedtest.push($(this).val());
          });
        if(selectedtest.length === 0){
            alert('Please choose lab test to add');
            return false;
        }else{
           $.ajax({
                url: '{{ route('discharge.lab.details') }}',
                type: "POST",
                data: {
                    tests: selectedtest
                },
                success: function (response) {
                    $('#laboratory-test').val(response);

                    $('#laboratory-list-modal').modal('hide');

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

    }

    $(document).ready(function(){
        $("#lab-list").change(function() {
            if (this.checked) {
                $(".test-list").each(function() {
                    this.checked=true;
                });
            } else {
                $(".test-list").each(function() {
                    this.checked=false;
                });
            }
        });
    });
</script>
