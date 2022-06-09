<div class="modal fade" id="doctors-list-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Doctors List</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-doctors-list">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReport" onclick="addDoctors()">Add Doctors</button>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function addDoctors(){
        // alert('add doctors')
        $('form').submit(false);
        var selecteddoc = new Array();
        $("input[name='doctors[]']:checked").each(function() {
               selecteddoc.push($(this).val());
          });
        if(selecteddoc.length === 0){
            alert('Please choose lab test to add');
            return false;
        }else{
           var doctors =  selecteddoc.join();
           $('#doctors').html(doctors);
           $('#doctors-list-modal').modal('hide');
        }
        
    }
</script>
