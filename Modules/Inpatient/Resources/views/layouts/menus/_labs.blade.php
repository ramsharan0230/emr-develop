<div id="labs" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="dietarytable">
                        <select class="form-control labs-select" multiple id="js-labs-select-options"></select>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="res-table">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Specimen</th>
                                    <th>Test Name</th>
                                    <th>Status</th>
                                    <th>&nbsp;</th>
                                    <th>Observation</th>
                                    <th>Sample Time</th>
                                    <th>Report Time</th>
                                </tr>
                            </thead>
                            <tbody id="js-labs-tests-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-labs-description-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <textarea style="width: 100%;margin: 10px;height: 100px;" disabled id="js-labs-description-textarea"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('dblclick', '#js-labs-tests-tbody tr', function() {
            if ($('input[name="radio-lab-top"]:checked').val() === 'Qualitative') {
                $('#js-labs-description-textarea').val($(this).find('td:nth-child(5)').text());
                $('#js-labs-description-modal').modal('show');
            }
        });
    });
</script>
@endpush
