<div class="modal fade" id="file-modal">
    <div class="modal-dialog modal-lg" id="size">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="file-modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="file-form-container">
                    <div class="file-form-data"></div>
                </div>

            </div>
            <i class="glyphicon glyphicon-chevron-left"></i>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="button" id="savebutton" onclick="updateQuantity()" value="Update" class="btn btn-primary"/>
            </div>

        </div>
    </div>
</div>

<script>
    var fileMenu = {
        waitingModalDisplay: function () {
            var selected_hospital_department=$('#selected_hospital_department').children("option:selected").val();
            $('.file-form-data').empty();
            $('.file-modal-title').text('Online Request');
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('patient.file.menu.waiting') }}',
                type: "POST",
                data: {selected_hospital_department: selected_hospital_department},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        searchModalDisplay: function () {
            $('.file-modal-title').text('Search Patient');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('patient.file.menu.search') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        LastEncounter: function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('patient.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },

        LastEncounterInpatient : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('inpatient.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },

        LastEncounterDelivery : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('delivery.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        LastEncounterEmergency : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('emergency.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        LastEncounterEye : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('eye.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        patientEncounterModalDisplay: function () {
            $('.file-modal-title').text('Patient Encounters');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');
            var patientId = $('.patient_id_submit').val();
            $.ajax({
                url: '{{ route('delivery.file.menu.history') }}',
                type: "POST",
                data: {patient_id:patientId},
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        LastEncounterMajor : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('major.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        LastEncounterDental : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('dental.last.encounter.form') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        }



    }


    //Request
    var requestMenu = {
        majorProcedureModal: function () {
            $('form').submit(false);
            $('.file-form-data').empty();
            $('.file-modal-title').text('Major Procedure');
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-lg');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.request.menu.majorprocedure') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                    $('#savebutton').hide();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        monitoringModal: function () {
            $('.file-form-data').empty();
            $('.file-modal-title').text('Monitoring');
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-lg');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.request.menu.monitoring') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                    $('#savebutton').hide();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },

        extraProcedureModal: function () {
            $('.file-form-data').empty();
            $('.file-modal-title').text('Extra Procedure');
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-lg');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.request.menu.extraprocedure') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },

    }

    //ReferTO
    var outcomeMenu = {
        refertoModal: function () {
            $('.file-form-data').empty();
            $('.file-modal-title').text('Refer To');
            $('#size').removeClass();
            $('#size').addClass('modal-dialog');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.outcome.menu.referto') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
        followupModal: function () {
            $('.file-form-data').empty();
            $('.file-modal-title').text('Consult Time');
            $('#size').removeClass();
            $('#size').addClass('modal-dialog modal-lg');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.outcome.menu.followup') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },
    }


    var historyNav = {
        encounterModal: function () {
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-lg');
            $('.file-form-data').empty();
            $('.file-modal-title').text('Patient Encounter');
            /*$('#size').removeClass();
            $('#size').addClass('modal-dialog');*/

            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({

                url: '{{ route('patient.menu.history.nav.encounter') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        },

        selectionModal: function () {
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');
            $('.file-form-data').empty();
            $('.file-modal-title').text('Selection');
            $('#size').removeClass();
            $('#size').addClass('modal-dialog');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.menu.history.nav.selection') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    console.log(response);
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        }

    }

    var menuEquipment = {
        displayModal: function () {
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.minor.equipment.form') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Equipments');
                    $('.file-form-data').html(response);
                    $('#file-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var vaccination = {
        displayModal: function () {
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-lg');
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.vaccination.form') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Vaccination Form');
                    $('.file-form-data').html(response);
                    $('#file-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }



</script>
<style>
    .file-form-container {
        min-height: 100px;
        max-height: 650px;
        /*overflow: scroll;*/
    }
</style>
