
<div class="modal fade" id="confirm-box">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">CogentEMR</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="confirm-box-title" style="line-height: 32px;">The patient information will be locked. <span id="insert-dynamic-title"></span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirm-yes-button" data-dismiss="modal" data-toggle="modal" data-target="">Yes</button>
            </div>

        </div>
    </div>
</div>
@include('inpatient::layouts.modal.outcome.show-discharge-modal')
@include('inpatient::layouts.modal.outcome.show-lama-death-modal')
@include('inpatient::layouts.modal.outcome.show-refere-modal')
@include('inpatient::layouts.modal.outcome.show-absconder-modal')


@push('after-script')
    <script type="text/javascript">
        $().ready(function() {
            // OutCome
            // change Data dynamicly
            $('#dischargeModal').click(function () {
                $('#insert-dynamic-title').empty();
                $('#insert-dynamic-title').append('Do you want to initiate discharge process?');
                $('#confirm-yes-button').attr('data-target', '#show-discharge-modal');
            });
            $('#markLamaModal').click(function () {
                $('#insert-dynamic-title').empty();
                $('#insert-dynamic-title').append('Do you want to mark as LAMA?');
                $('#confirm-yes-button').attr('data-target', '#show-lama-death-modal');
            });
            $('#markReferModal').click(function () {
                $('#insert-dynamic-title').empty();
                $('#insert-dynamic-title').append('Do you want to mark as Referred?');
                $('#confirm-yes-button').attr('data-target', '#show-refere-modal');
            });
            $('#markDeathModal').click(function () {
                $('#insert-dynamic-title').empty();
                $('#insert-dynamic-title').append('Do you want to mark as Death?');
                $('#confirm-yes-button').attr('data-target', '#show-lama-death-modal');
            });


            // Inset Discharge
            $("#save-discharge-modal").click(function () {
                var fldencounterval = $('#fldencounterval').val();
                var flduserid = $('.flduseridOutcome').val();
                var fldcomp = $('.fldcompidOutcome').val();
                var fldhead = $(".fldhead option:selected").val();
                var url = $(this).attr('url');
                $("#get_related_fldcurrlocat").val('');

                var formData = {
                    "fldencounterval": fldencounterval,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                    "fldhead": fldhead,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#admitedstatus').text('Discharged');
                            $('.removeOnclickDischarge').removeAttr('onclick');
                            $('#get_related_fldcurrlocat').html('Discharged');
                            toggleCertificateUrl('discharge');
                            showAlert('Inserted Successfully');
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            // Inset Lama and Death
            $("#save-lama-death-modal").click(function () {
                var fldencounterval = $('#fldencounterval').val();
                var flduserid = $('.flduseridOutcome').val();
                var fldcomp = $('.fldcompidOutcome').val();
                var fldcomment = $("#lamaDeathFormDetail").val();
                var url = $(this).attr('url');
                $("#get_related_fldcurrlocat").val('');

                var formData = {
                    "fldencounterval": fldencounterval,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                    "fldcomment": fldcomment,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#admitedstatus').text(data.success.type);
                            toggleCertificateUrl(data.success.type);
                            showAlert('Inserted Successfully');
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            // Inset Refere
            $("#save-refere-modal").click(function () {
                var fldencounterval = $('#fldencounterval').val();
                var flduserid = $('.flduseridOutcome').val();
                var fldcomp = $('.fldcompidOutcome').val();
                var fldcomment = $("#fldheadOutcomerefere").val();
                var url = $(this).attr('url');
                $("#get_related_fldcurrlocat").val('');

                var formData = {
                    "fldencounterval": fldencounterval,
                    "flduserid": flduserid,
                    "fldcomp": fldcomp,
                    "fldcomment": fldcomment,
                }
                // console.log(url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#admitedstatus').text('Refer');
                            toggleCertificateUrl('referral');
                            showAlert('Inserted Successfully');
                            // location.reload();
                        } else {
                            showAlert('error');
                        }
                    }
                });
            });

            function toggleCertificateUrl(type) {
                $('.certificates').attr('href', 'javascript:void(0);');

                var typeElem = $('.' + type)
                $(typeElem).attr('href', $(typeElem).data('url'));
            }
        });
    </script>
@endpush
