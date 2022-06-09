{{-- Doctor Share Modal --}}
<div class="modal fade" id="doctor-share-modal" tabindex="-1" role="dialog" aria-labelledby="doctor-share" aria-hidden="false">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <form id="doctor-share-form" action="{{ route("billing.doctor-share") }}" method="POST">
                @csrf
                <input id="share-type" name="type" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Doctor Share - <span id="doc-modal-title"></span></h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="false">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pat_billing_id">
                    <input type="hidden" name="encounter_id">
                    <div id="doc-share-category-block">

                    </div>
                    {{-- <div><span class="error">Please select at least one field.</span></div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <button type="button" name="submit" id="js-dr-share-submit-btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End of doctor share modal --}}
