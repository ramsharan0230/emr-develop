<div class="modal fade show" id="js-laboratory-update-method-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align: center;">Update Method</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Test <strong id="js-laboratory-update-method-test-modal"></strong></label>
                    </div>
                    <div class="col-md-12">
                        <label>SampleId <strong id="js-laboratory-update-method-sampleid-modal"></strong></label>
                    </div>
                </div>
                <div class="row" style="padding: 0px 15px;">
                    <input type="hidden" id="js-laboratory-update-method-testid-hidden-input">
                    <select id="js-laboratory-update-method-method-select" class="form-control">
                        <option value="">--Select--</option>
                        @foreach (\App\Utils\Helpers::getMethodsByCategory() as $method)
                        <option value="{{ $method->fldmethod }}">{{ $method->fldmethod }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="method.save()">Save</button>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    var segment = "{{ Request::segment(3) }}";
    var method = {
        displayModal: function (currentElem) {
            $('#js-laboratory-update-method-modal').modal('show');

            var sampleidTdCount = (segment == 'reporting') ? 2 : 5;

            var currentTr = $(currentElem).closest('tr');
            var text = $(currentElem).text().trim();
            $('#js-laboratory-update-method-test-modal').text($(currentTr).find('td:nth-child(3)').text().trim());
            $('#js-laboratory-update-method-sampleid-modal').text($(currentTr).find('td:nth-child(' + sampleidTdCount + ')').text().trim());
            $('#js-laboratory-update-method-testid-hidden-input').val($(currentTr).data('fldid'));
            $('#js-laboratory-update-method-method-select option').attr('selected', false);
            $('#js-laboratory-update-method-method-select option[value="' + text + '"]').attr('selected', true);
        },
        save: function() {
            var fldmethod = $('#js-laboratory-update-method-method-select').val() || '';
            if (fldmethod != '') {
                $.ajax({
                    url: baseUrl + "/admin/laboratory/reporting/updateMethod",
                    type: "POST",
                    data: {
                        fldid: $('#js-laboratory-update-method-testid-hidden-input').val(),
                        fldmethod: fldmethod
                    },
                    dataType: "json",
                    success: function (response) {
                        var methodTdCount = (segment == 'reporting') ? 9 : 4;
                        var methodTbodyId = (segment == 'reporting') ? 'js-reporting-samples-tbody' : 'js-printing-samples-tbody';

                        var status = response.status ? 'success' : 'fail';
                        showAlert(response.message, status);
                        if (response.status) {
                            $('#js-laboratory-update-method-modal').modal('hide');
                            $('#' + methodTbodyId + ' tr[is_selected="yes"] td:nth-child(' + methodTdCount + ')').text(fldmethod);
                        }
                    }
                });
            } else
                showAlert('Please select method.', 'fail');
        }
    };
</script>
@endpush
