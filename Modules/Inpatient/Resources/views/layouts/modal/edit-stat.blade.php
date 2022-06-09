<style type="text/css">

    .m-route_stat {
        width: 100%;
        padding: 14px !important;
        border-radius: 4px;
    }
    .group__box label{
        margin-bottom: 0px ! important;
    }
    .btn-stat{
        width: 49%;
        margin-top: 12px;
    }
</style>
<div class="modal fade bd-example-modal-lg" id="js-statprn-dosing-record-modal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                <h5 class="inpatient__modal_title" style="text-align: center;">Dosing Record</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- <div class="row">

                        <div class="col-md-3 form-checkbox">
                            <div class="form-group form-row align-items-center">
                                <input type="checkbox" style="display: inline-block;">&nbsp;
                                <span class="checkbox-align">Display Keypad</span>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="res-table border mb-3" id="js-statprn-dosing-record-ul" style="height:435px;"></ul>
                            <button type="button" id="js-statprn-modal-export-report-btn" class="btn btn-primary float-right"><i class="ri-code-s-slash-line"></i> Export</button>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-2">Name</label>
                                        <div class="col-md-10">
                                            <input type="text" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ isset($patient->fldptnamefir) ?  $patient->fldptnamefir :'' }} {{ $patient->fldmidname }} {{ isset($patient->fldptnamelast) ? $patient->fldptnamelast :'' }}@endif"  class="form-control" readonly >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-3">Gender</label>
                                        <div class="col-md-9">
                                            <input type="text" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="js-statprn-modal-qty-input">
                                        <input type="hidden" id="js-statprn-flddrug-hidden-input">
                                        <input type="hidden" id="js-statprn-fldvolunit-hidden-input">
                                        <input type="hidden" id="js-statprn-fldroute-hidden-input">
                                        <input type="hidden" id="js-statprn-flddose-hidden-input">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-grou form-row align-items-center">
                                        <label id="js-statprn-modal-unit-label" class="col-md-3">Tab</label>&nbsp;
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-sm btn-primary btn-action" id="js-statprn-dosing-add-btn"><i class="ri-add-line"></i> Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-2">Regimen</label>
                                        <div class="col-md-10">
                                            <input class="form-control" id="js-statprn-modal-regimen-label" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-4">TotDose</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="js-statprn-modal-total-dose-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="res-table border" style="height:315px;">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>---</th>
                                                    <th>DateTime</th>
                                                    <th>Particulars</th>
                                                    <th>Qty</th>
                                                    <th>Unit</th>
                                                    <th>---</th>
                                                    <th>Dose</th>
                                                </tr>
                                            </thead>
                                            <tbody id="js-statprn-dosing-record-list-tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-md-4">Count(Today)</label>
                                        <div class="col-md-8">
                                            <input type="" name="" id="js-statprn-modal-count-today-input" class="dosing-record form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-md-4">Count(Total)</label>
                                        <div class="col-md-8">
                                            <input type="" name="" id="js-statprn-modal-count-total-input" class="dosing-record form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    $(document).on('click', '#js-statprn-dosing-record-ul li', function() {
        $('#js-statprn-dosing-record-ul li').css('background-color', '#ffffff');
        $(this).css('background-color', '#c8dfff');

        $.each($('#js-statprn-dosing-record-ul li'), function(i,e) {
            $(e).attr('is_selected', 'no');
        });
        $(this).attr('is_selected', 'yes');

        $.ajax({
            url: baseUrl + '/inpatient/stat/getDosingDetail',
            type: 'GET',
            data: {
                fldid: $(this).data('fldid'),
                flditem: $(this).data('flditem'),
                encounterId: globalEncounter,
            },
            success: function(data) {
                var regimen = data.detail.fldroute + ' ' + data.detail.flddose + ' X ' + data.detail.fldfreq;
                $('#js-statprn-modal-regimen-label').text(regimen);
                $('#js-statprn-modal-medicine-label').text(data.detail.flditem);
                $('#js-statprn-modal-unit-label').text(data.med_detail.fldvolunit);

                var trData = '';
                var totalDose = 0;
                $.each(data.lists, function(i, list) {
                    var dose = data.detail.flddose * list.fldvalue;
                    totalDose += dose;

                    var csscolor = '70d470';
                    var lockItem = '<td style="color: #' + csscolor + ';"><i class="fas fa-lock"></i></td>';
                    if (data.detail.fldroute == 'injection') {
                        if (list.fldfromtime === null && list.fldtotime === null) {
                            lockItem = '<td style="color: #' + csscolor + ';"><i class="fas fa-play"></i></td>';
                        } else if (list.fldfromtime !== null && list.fldtotime === null) {
                            csscolor = '000';
                            lockItem = '<td style="color: #' + csscolor + ';"><i class="fas fa-stop"></i></td>';
                        }
                    }

                    trData += '<tr data-fldid="' + list.fldid + '">';
                    trData += '<td>' + (i+1) + '</td>';
                    trData += '<td>' + list.fldtime + '</td>';
                    trData += '<td>' + data.med_detail.flddrug + '</td>';
                    trData += '<td>' + list.fldvalue + '</td>';
                    trData += '<td>' + list.fldunit + '</td>';
                    trData += lockItem;
                    trData += '<td>' + dose + '</td>';
                    trData += '</tr>';
                });
                $('#js-statprn-modal-total-dose-input').val(totalDose);
                $('#js-statprn-dosing-record-list-tbody').empty().html(trData);
                $('#js-statprn-modal-count-total-input').val(data.lists.length);
                $('#js-statprn-flddrug-hidden-input').val(data.med_detail.flddrug);
                $('#js-statprn-fldroute-hidden-input').val(data.detail.fldroute);
                $('#js-statprn-fldvolunit-hidden-input').val(data.med_detail.fldvolunit);
                $('#js-statprn-flddose-hidden-input').val(data.detail.flddose);
                $('#js-statprn-modal-count-today-input').val(data.detail.dayCount);

            }
        });
    });

    $('#js-statprn-dosing-add-btn').click(function() {
        var quantity = $('#js-statprn-modal-qty-input').val() || '0';
        var flddoseno = $('#js-statprn-dosing-record-ul li[is_selected="yes"]').data('fldid') || '0';
        if (quantity !== '0' || flddoseno !== '0') {
            $.ajax({
                url: baseUrl + '/inpatient/stat/addDosingDetail',
                type: 'POST',
                data: {
                    flddoseno: flddoseno,
                    fldvalue: quantity,
                    fldunit: $('#js-statprn-fldvolunit-hidden-input').val(),
                    encounterId: globalEncounter,
                },
                success: function(response) {
                    if (response.status) {
                        var list = response.data;
                        var dose = list.fldvalue * Number($('#js-statprn-flddose-hidden-input').val());
                        var totalDose = dose + Number($('#js-statprn-modal-total-dose-input').val());
                        var lenght = $('#js-statprn-dosing-record-list-tbody tr').length+1

                        var csscolor = '70d470';
                        var lockItem = '<td style="color: #' + csscolor + ';"><i class="fas fa-lock"></i></td>';
                        if ($('#js-statprn-fldroute-hidden-input').val() === 'injection')
                            lockItem = '<td style="color: #' + csscolor + ';"><i class="fas fa-play"></i></td>';

                        var trData = '<tr data-fldid="' + list.fldid + '">';
                        trData += '<td>' + lenght + '</td>';
                        trData += '<td>' + list.fldtime + '</td>';
                        trData += '<td>' + $('#js-statprn-flddrug-hidden-input').val() + '</td>';
                        trData += '<td>' + list.fldvalue + '</td>';
                        trData += '<td>' + list.fldunit + '</td>';
                        trData += lockItem;
                        trData += '<td>' + dose + '</td>';
                        trData += '</tr>';
                        $('#js-statprn-dosing-record-list-tbody').append(trData);

                        $('#js-statprn-modal-count-total-input').val(lenght);
                        $('#js-statprn-modal-total-dose-input').val(totalDose);
                        $('#js-statprn-modal-count-today-input').val(list.dayCount);
                    }
                    showAlert(response.message);
                }
            });
        } else
        alert('Dose or quantity is invalid.');
    });

    $(document).on('click', '#js-statprn-dosing-record-list-tbody tr td:nth-child(6)', function() {
        var iconElem = $(this).find('i.fas');
        var fldid = $(this).closest('tr').data('fldid');
        var column = '';
        if ($(iconElem).hasClass('fa-play'))
            column = 'fldfromtime';
        else if ($(iconElem).hasClass('fa-stop'))
            column = 'fldtotime';

        if (column !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/stat/updateDosingDetail',
                type: 'POST',
                data: {
                    column: column,
                    fldid: fldid
                },
                success: function(response) {
                    if (column === 'fldfromtime') {
                        $(iconElem).removeClass('fa-play');
                        $(iconElem).addClass('fa-stop');
                        $(iconElem).closest('td').css('color', '#000');
                    } else if (column === 'fldtotime') {
                        $(iconElem).removeClass('fa-stop');
                        $(iconElem).addClass('fa-lock');
                        $(iconElem).closest('td').css('color', '#70d470');
                    }
                    showAlert(response.message);
                }
            });
        }
    });

    $('#js-statprn-modal-export-report-btn').click(function() {
        var fldid = $('#js-statprn-dosing-record-ul li[is_selected="yes"]').data('fldid') || '';
        var flditem = $('#js-statprn-dosing-record-ul li[is_selected="yes"]').data('flditem') || '';
        if (fldid !== '' || flditem !== '') {
            var url = baseUrl + '/inpatient/stat/generateExportMedicineDetailPDF?fldid=' + fldid + '&flditem=' + flditem+ '&encounterId=' + globalEncounter;
            window.location.href = url;
        }
    });
</script>
@endpush
