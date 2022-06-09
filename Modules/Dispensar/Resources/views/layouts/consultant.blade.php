<div class="modal fade" id="consultant_list" tabindex="-1" role="dialog" aria-labelledby="consultant_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultant_listLabel" style="text-align: center;">Choose Consultants</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body res-table">
                
                <div class="row mb-2">
                    <div class="col-4">
                        <input type="checkbox" id="enterConsultant" value="1"> Enter Consultant
                    </div>
                    <div class="col-4 newconsultdiv" style="display: none;">
                        <input type="text" class="form-control" name="newconsultname" id="newconsultname" placeholder="Consultant name">
                    </div>
                    <div class="col-4 newconsultdiv" style="display: none;">
                        <input type="text" class="form-control" name="newconsultnmc" id="newconsultnmc" placeholder="NMC No.">
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12 mb-2">
                        <input type="text" class="form-control" id="searchConsultant" onkeyup="myFunctionSearchconsultant()" placeholder="Search..">
                    </div>
                </div>
                <table id="consultantSearchtable" class="table table-bordered table-hover table-striped text-center">
                @php
                    $consultantList = Helpers::getConsultantList();
                @endphp
                @if(count($consultantList))
                    @foreach($consultantList as $con)
                    <tr>
                        <td style="text-align: left;">
                            <div class="custom-control custom-radio">
                            <input type="radio" name="consultant" value="{{ $con->username }}" class="custom-control-input" {{(isset($enpatient) && $enpatient->flduserid == $con->username)? "checked":"" }}>
                            <label class="custom-control-label">{{ $con->username }}</label>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitconsultant_list">Submit</button>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    function myFunctionSearchconsultant() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchConsultant");
        filter = input.value.toUpperCase();
        table = document.getElementById("consultantSearchtable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1)
                    tr[i].style.display = "";
                else
                    tr[i].style.display = "none";
            }
        }
    }

    $("#enterConsultant").change(function() {
        if(this.checked) {
            $('.newconsultdiv').show();
            $('input[name="consultant"]').prop('checked', false);
        }else{
            $('#newconsultname').val('');
            $('#newconsultnmc').val('');
            $('.newconsultdiv').hide();
        }
    });
</script>
@endpush