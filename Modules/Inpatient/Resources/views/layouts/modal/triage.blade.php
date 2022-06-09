<div class="modal" id="js-menu-triage-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Triage Examination</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-row align-items-center">
                            <label class="col-md-2 mr-0 border-none">Name</label>
                            <div class="col-sm-10">
                                <input readonly="readonly" type="text" id="js-triage-name-input" class="form-control full-width" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{ $patient->fldptnamelast }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-row align-items-center">
                            <label class="col-md-2 mr-0 border-none">Gender</label>
                            <div class="col-sm-10">
                                <input readonly="readonly" type="text" id="js-triage-gender-input" class="form-control full-width" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row top-req">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select id="js-triage-examination-option" multiple class="form-control " style="height: 495px; width: 100%;"></select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-md-2 mr-0 border-none">Name</label>
                                    <div class="col-sm-10">
                                        <input readonly="readonly" type="text" id="js-triage-name-input" class="form-control full-width" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{ $patient->fldptnamelast }}@endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-md-2 mr-0 border-none">Gender</label>
                                    <div class="col-sm-10">
                                        <input readonly="readonly" type="text" id="js-triage-gender-input" class="form-control full-width" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="d-flex">
                                    <div class="red modal-color-box" data-color="red"></div>
                                    <div class="yellow modal-color-box" data-color="yellow"></div>
                                    <div class="green modal-color-box" data-color="green"></div>
                                    <div class="blue modal-color-box" data-color="blue"></div>
                                    <div class="black modal-color-box" data-color="black"></div>
                                </div>
                            </div>
                            <div class="col-md-1 p-0 col-sm-2">
                                <button type="button" id="js-triage-exam-change-color-btn" class="btn btn-primary"><i class="ri-check-line"></i> OK</button>
                            </div>
                            <div class="col-md-6">
                                <span class="modal-blue-box table-patient-img"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group form-group-trainge">
                                    <div class="form-row align-items-center">
                                        <div class="col-sm-5">
                                            <input type="text" name="" id="js-triage-examid-input" class="form-control full-width">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="" id="js-triage-input-element" class="form-control full-width">
                                            <input type="hidden" id="js-triage-examination-qualitative-input">
                                            <input type="hidden" id="js-triage-examination-quantative-input">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-primary" id="js-triage-examination-add"><i class="ri-add-line"></i> Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row top-req">
                            <div class="col-md-12">
                                <div class="res-table">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">DateTime</th>
                                                <th class="tittle-th">Examination</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">Observation</th>
                                                <th class="tittle-th">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-triage-examinations-list"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-menu-triage-exam-modal"></div>

@push('after-script')
<script type="text/javascript">
    $(document).on('click', '#js-triage-examinations-list tr td:nth-child(4)', function() {
        $('#js-onexam-status-save-modal').attr('location', 'triage');
        $('#js-onexam-fldid-input').val($(this).closest('tr').data('fldid'));
        var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
        $('#js-onexam-status-select option').attr('selected', false);
        $('#js-onexam-status-select option[value="' + status + '"]').attr('selected', true);

        $('#js-onexam-status-modal').modal('show');
    });

    $('div.modal-color-box').click(function() {
        $.each($('div.modal-color-box'), function(i, ele) {
            $(ele).attr('is_selected', 'no');
        });
        $(this).attr('is_selected', 'yes');

        $('div.modal-color-box').css('border', 'none');
        $(this).css('border', '6px solid gray');
    });
    $('#js-triage-exam-change-color-btn').click(function() {
        var color = $('div.modal-color-box[is_selected="yes"]').data('color') || '';
        if (color !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/prog/changeColor',
                type: "POST",
                data: {
                    color: color,
                    encounterId: $('#encounter_id').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status)
                        $('.table-patient-img').css('background-color', color);

                    showAlert(response.message);
                }
            });
        } else
            alert('Please choose color to change.');
    });

    $('#js-menu-triage-modal').on('hidden.bs.modal', function() {
        $('#js-triage-examid-input').val('');
        $('#js-triage-examination-qualitative-input').val('');
        $('#js-triage-input-element').val('');
        $('#js-triage-examinations-list').html('');

        getPatientProfileColor();
    });

    $('#js-triage-examination-option').change(function() {
        var elem = $('#js-triage-examination-option option:selected');
        var type = $(elem).data('opt');
        var examid = $(elem).attr('value');
        $('#js-triage-examid-input').val(examid);

        $.ajax({
            url: baseUrl + '/inpatient/dataEntryMenu/getModalContent',
            type: "GET",
            data: {
                type: type,
                examid: examid
            },
            dataType: "json",
            success: function(response) {
                if (response.hasOwnProperty('modal') && response.modal) {
                    $('#js-menu-triage-exam-modal').html(response.view_data);
                    $('#js-menu-triage-exam-modal').modal('show');

                    $('#js-menu-triage-exam-modal').on('change', '.js-modal-select', function() {
                        $(this)
                            .closest('td')
                            .next('td')
                            .find('input.js-modal-scale-text')
                            .val($(this).find('option:selected').data('val'));
                    });
                }
            }
        });
    });

    $(document).on('click', '#js-triage-examination-add', function() {
        var qualitative = '';
        var quantative = '0';
        var examOption = $('#js-triage-examination-option option:selected').data('opt');

        if (examOption == 'No Selection' && isNaN($('#js-input-no-selection').val())) {
            showAlert('Enter valid number.', 'fail');
            return;
        }

        if (examOption === 'Clinical Scale') {
            var quantative = 0;
            qualitative = "{";
            $.each($('.js-modal-scale-text'), function(i, e) {
                var valueee = $(e).val();
                quantative += Number(valueee);

                qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
            });
            qualitative = qualitative.substring(0, qualitative.length - 2);
            qualitative += "}";
            $('#js-triage-input-element').val(quantative)
        } else if (examOption === 'Left and Right') {
            qualitative = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
            $('#js-triage-input-element').val(qualitative)
        } else if (examOption == 'No Selection') {
            qualitative = $('#js-input-no-selection').val();
            quantative = qualitative;
            $('#js-triage-input-element').val(qualitative)
        } else {
            qualitative = $('#js-input-element').val();
            $('#js-triage-input-element').val(qualitative)
        }
        $('#js-triage-examination-qualitative-input').val(qualitative);
        $('#js-triage-examination-quantative-input').val(quantative);

        $('#js-menu-triage-exam-modal').modal('hide');
    });

    $(document).on('click', '#js-triage-examinations-list tr', function() {
        selected_td('#js-triage-examinations-list tr', this);
    });

    $('#js-triage-examination-add').click(function() {
        var data = {
            examinationid: $('#js-triage-examination-option option:selected').val(),
            examinationtype: $('#js-triage-examination-option option:selected').data('opt'),
            qualitative: $('#js-triage-examination-qualitative-input').val(),
            quantitative: $('#js-triage-examination-quantative-input').val(),
            encounterId: globalEncounter,
        };
        $.ajax({
            url: baseUrl + '/inpatient/dataEntryMenu/saveTriageExam',
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    var val = response.data;
                    var abnormalVal = get_abnoraml_btn(val.abnormal);

                    var trData = '<tr data-fldid="' + val.fldid + '">';
                    trData += '<td>' + ($('#js-triage-examinations-list tr').length + 1) + '</td>';
                    trData += '<td>' + val.time + '</td>';
                    trData += '<td>' + val.examination + '</td>';
                    trData += '<td onclick="changeAbnormalStatus.displayModal(\'#js-triage-examinations-list\', 4, ' + val.fldid + ')">' + abnormalVal + '</td>';
                    trData += '<td>' + val.quantative + '</td>';
                    trData += '<td onclick="triageExam.deleteExam(this, ' + val.fldid + ')"><i class="far fa-trash-alt"></i></td></tr>';

                    $('#js-triage-examinations-list').append(trData);
                }
                showAlert(response.message);
            }
        });
    });
</script>
@endpush