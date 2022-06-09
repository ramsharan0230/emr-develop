<div class="modal fade bd-example-modal-lg" id="show-lama-death-modal">
   <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title" id="markLamaDeathTitle">Reason For LAMA</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <textarea id="lamaDeathFormDetail" class="form-control"></textarea>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <a href="#" class="btn btn-secondary" onclick="document.getElementById('lamaDeathFormDetail').value =''">Clear</a>
                <button type="button" class="btn btn-primary" id="save-lama-death-modal" url="{{ route('outcome.lama.save') }}" data-dismiss="modal">save</button>
            </div>

        </div>
    </div>
</div>
