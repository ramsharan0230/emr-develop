<div class="modal fade" id="encounter_list" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('patient') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Choose Encounter ID</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="ajax_response_encounter_list">

                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" id="submitencounter_list" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
