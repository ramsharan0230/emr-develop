<div class="modal fade show" id="js-laboratory-update-specimen-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align: center;">Update Specimen</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Test <strong id="js-laboratory-update-specimen-test-modal"></strong></label>
                    </div>
                    <div class="col-md-12">
                        <label>SampleId <strong id="js-laboratory-update-specimen-sampleid-modal"></strong></label>
                    </div>
                </div>
                <div class="row" style="padding: 0px 15px;">
                    <input type="hidden" id="js-laboratory-update-specimen-testid-hidden-input">
                    <select id="js-laboratory-update-specimen-specimen-select" class="form-control">
                        <option value="">--Select--</option>
                        @foreach (\App\Utils\Helpers::getSampleTypes() as $specimen)
                        <option value="{{ $specimen->fldsampletype }}">{{ $specimen->fldsampletype }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="specimen.save()">Save</button>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    var segment = "{{ Request::segment(3) }}";
    var specimen = {
        displayModal: function (currentElem) {
            $('#js-laboratory-update-specimen-modal').modal('show');

            var sampleidTdCount = (segment == 'reporting') ? 2 : 5;

            var currentTr = $(currentElem).closest('tr');
            var text = $(currentElem).text().trim();
            $('#js-laboratory-update-specimen-test-modal').text($(currentTr).find('td:nth-child(3)').text().trim());
            $('#js-laboratory-update-specimen-sampleid-modal').text($(currentTr).find('td:nth-child(' + sampleidTdCount + ')').text().trim());
            $('#js-laboratory-update-specimen-testid-hidden-input').val($(currentTr).data('fldid'));
            $('#js-laboratory-update-specimen-specimen-select option').attr('selected', false);
            $('#js-laboratory-update-specimen-specimen-select option[value="' + text + '"]').attr('selected', true);
        },
        save: function() {
            var fldsampletype = $('#js-laboratory-update-specimen-specimen-select').val() || '';
            if (fldsampletype != '') {
                $.ajax({
                    url: baseUrl + "/admin/laboratory/reporting/updateSpecimen",
                    type: "POST",
                    data: {
                        fldid: $('#js-laboratory-update-specimen-testid-hidden-input').val(),
                        fldsampletype: $('#js-laboratory-update-specimen-specimen-select').val()
                    },
                    dataType: "json",
                    success: function (response) {
                        var specimenTdCount = (segment == 'reporting') ? 8 : 4;
                        var specimenTbodyId = (segment == 'reporting') ? 'js-reporting-samples-tbody' : 'js-printing-samples-tbody';

                        var status = response.status ? 'success' : 'fail';
                        showAlert(response.message, status);
                        if (response.status) {
                            $('#js-laboratory-update-specimen-modal').modal('hide');
                            $('#' + specimenTbodyId + ' tr[is_selected="yes"] td:nth-child(' + specimenTdCount + ')').text(fldsampletype);
                        }
                    }
                });
            } else
                showAlert('Please select specimen.', 'fail');
        }
    };
</script>
@endpush
