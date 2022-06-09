<div class="modal fade" id="visual-input-box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Visual Input Box</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3 table-scroll">
                                <table class="table table-bordered cg_table">
                                    <thead>
                                    <tr>
                                        <th class="tittle-th">DateTime</th>
                                        <th class="tittle-th">Consultation</th>
                                        <th class="tittle-th">Consultant</th>
                                        <th class="tittle-th">&nbsp;&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list_of_complaint">

                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('diagnosis::examinations.dynamic-modal.common-get-function')
