<div class="modal" id="js-patient-image-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Patient Image</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="form-label col-md-2" id="js-patient-image-testid"></label>
                                <input readonly="readonly" type="text" class="intake-input col-md-10" id="js-patient-image-testname">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row  top-req">
                        <div class="col-md-4">
                            <div class="table-patient table-scroll">
                                <table class="table table-sm">
                                    <tbody id="js-patient-image-tbody" class="general_image_inpatient"></tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-trainge">
                                        <input type="text" id="js-patient-image-title-input" class="input-patient-img col-md-10">&nbsp;
                                        <!-- <button type="button" class="btn btn-deafult btn-sm pull-right"><img src="{{asset('assets/images/play.png')}}" alt="" width="15px;"></button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 top-req">
                                    <div class="patient-img-table table-scroll" id="js-patient-image-preview-div"></div>
                                </div>
                            </div>
                            <div class="row top-req">
                                <div class="col-md-8">

                                    <div class="form-group top-req">
                                        <label class="form-label col-md-1">Keys</label>
                                        <input type="text" id="js-patient-image-key-input" class="input2-patient-img col-md-10">
                                    </div>
                                    <div class="form-group top-req">
                                        <textarea class="modal-textarea" id="js-patient-image-detail-textarea" class="patient-image-textarea" style="height: 100px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="image-upload">
                                            <button type="button" class="btn defalut-btn btn-sm btn-patient" id="patient_image_file">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <input id="js-patient-image-file" type="file" style="display: none;" />
                                        </div>
                                        <button type="button" class="btn defalut-btn btn-sm btn-patient"><i class="fas fa-camera"></i></button>&nbsp;
                                    </div>
                                    <button type="button" class="default-btn f-btn-icon-b btn-patient-width" id="js-patient-image-add-btn">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Add
                                    </button>
                                    <button type="button" class="defalut-btn f-btn-icon btn-patient-width" id="js-patient-image-edit-btn">
                                        <img src="{{ asset('assets/images/edit.png') }}" alt="" width="15px;">&nbsp;&nbsp;Edit
                                    </button>
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
        $('#js-reporting-image-btn').click(function () {
            var trElem = '';
            var encounterId = '';
            if ($('#js-reporting-samples-tbody tr[is_selected="yes"]').length > 0) {
                trElem = $('#js-reporting-samples-tbody tr[is_selected="yes"]');
                encounterId = $('#js-reporting-encounter-input').val();
            } else if ($('#js-sampling-labtest-tbody tr[is_selected="yes"]').length > 0) {
                trElem = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
                encounterId = $('#js-sampling-encounterid-input').val();
            }
            // var trElem = $('#js-reporting-samples-tbody tr[is_selected="yes"]') || $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
            // var encounterId = $('#js-reporting-encounter-input').val() || $('#js-sampling-encounterid-input').val() || '';
            var labtestid = $(trElem).data('fldid') || '';
            if (encounterId != '' && labtestid != '') {
                $('#js-patient-image-testid').text(labtestid);
                $('#js-patient-image-testname').val($(trElem).find('td:nth-child(3)').text().trim());
                $.ajax({
                    url: baseUrl + '/admin/laboratory/reporting/getPatientImage',
                    type: "GET",
                    data: {encounterId: encounterId, labtestid: labtestid},
                    success: function (data) {
                        var trData = '';
                        $.each(data, function (i, val) {
                            trData += '<tr title="' + val.fldtitle + '" fldid="' + val.fldid + '" imagepath="' + val.fldpic + '" key="' + val.fldkeyword + '" detail="' + val.flddetail + '"><td>' + val.fldtitle + '</td></tr>';
                        });
                        $('#js-patient-image-tbody').empty().html(trData);
                    }
                });
                $('#js-patient-image-modal').modal('show');
            } else
                alert('Please select test to insert image.');
        });

        function resetForm() {
            $('#js-patient-image-title-input').val('');
            $('#js-patient-image-key-input').val('');
            $('#js-patient-image-detail-textarea').val('');
            $('#js-patient-image-name-input').val('');
            $('#js-patient-image-gender-input').val('');
            $('#js-patient-image-preview-div').html('');
        }

        $('#js-patient-image-modal').on('hidden.bs.modal', function () {
            resetForm();
            $('#js-patient-image-testid').html('');
            $('#js-patient-image-testname').val('');
        });

        $('#patient_image_file').on('click', function(){
            $('input#js-patient-image-file').trigger('click');
        });

        $(document).on('click', '#js-patient-image-tbody tr', function() {
            selected_td('#js-printing-samples-tbody tr', this);

            $('#js-patient-image-title-input').val($(this).attr('title'));
            $('#js-patient-image-key-input').val($(this).attr('key'));
            $('#js-patient-image-detail-textarea').val($(this).attr('detail'));
            $('#js-patient-image-preview-div').html('<img src="' + $(this).attr('imagepath') + '" style="width:100%;height: 100%;">');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#js-patient-image-preview-div').html('<img src="' + e.target.result + '" style="width:100%;height: 100%;">');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#js-patient-image-file").change(function(){
            readURL(this);
        });

        function getPatientImageFormData() {
            var encounterId = $('#js-reporting-encounter-input').val() || $('#js-sampling-encounterid-input').val() || '';
            var data = new FormData();
            data.append('fldtitle', $('#js-patient-image-title-input').val());
            data.append('fldkey', $('#js-patient-image-key-input').val());
            data.append('flddetail', $('#js-patient-image-detail-textarea').val());
            data.append('fldencounterval', encounterId);
            data.append('fldtestid', $('#js-patient-image-testid').text().trim());

            if ($('#js-patient-image-file')[0].files !== undefined && $('#js-patient-image-file')[0].files.length > 0) {
                var image = $('#js-patient-image-file')[0].files[0];
                data.append('fldimage', image);
            }
            return data;
        }

        $('#js-patient-image-add-btn').click(function() {
            var data = getPatientImageFormData();
            $.ajax({
                url: baseUrl + '/admin/laboratory/reporting/savePatientImage',
                type: "POST",
                enctype: 'multipart/form-data',
                data: data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        var trData = '<tr title="' + val.fldtitle + '" fldidid="' + val.fldid + '" imagepath="' + val.fldpic + '" key="' + val.fldkeyword + '" detail="' + val.flddetail + '"><td>' + val.fldtitle + '</td></tr>';
                        $('#js-patient-image-tbody').append(trData);

                        $("#js-patient-image-file").val(null);
                        resetForm();
                    }
                    showAlert(response.message);
                }
            });
        });

        $('#js-patient-image-edit-btn').click(function() {
            var selectedTr = $('#js-patient-image-tbody tr[is_selected="yes"]');
            var data = getPatientImageFormData();
            data.append('fldid', $(selectedTr).data('fldid'));
            $.ajax({
                url: baseUrl + '/admin/laboratory/reporting/updatePatientImage',
                type: "POST",
                enctype: 'multipart/form-data',
                data: data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        $(selectedTr).find('td:first-child').text(val.fldtitle);

                        $(selectedTr).attr('title', val.fldtitle);
                        $(selectedTr).attr('key', val.fldkeyword);
                        $(selectedTr).attr('detail', val.flddetail);
                        $(selectedTr).attr('imagepath', val.fldpic);

                        $("#js-patient-image-file").val(null);
                        resetForm();
                    }
                    showAlert(response.message);
                }
            });
        });
    </script>
@endpush
