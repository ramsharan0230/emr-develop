@extends('frontend.layouts.master')
@section('content')
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
<div>
    @include('menu::common.inpatient-nav-bar')
    
    <div class="container-fluid">
        <div class="row">
            @include('frontend.common.patientProfile')

            @include('inpatient::layouts._inpatient')
        </div>
    </div>
</div>
    @include('inpatient::menumodal')
@endsection

@push('after-script')
    <script src="{{ asset('js/inpatient_form.js')}}"></script>
    <script src="{{ asset('js/inpatient_ns.js')}}"></script>
    {{-- Apex Charts --}}
    <script src="{{ asset('js/apex-chart.min.js') }}"></script>
    <script>
        CKEDITOR.replace('history_detail',
            {
                height: '300px',
            });
        CKEDITOR.replace('editor_present',
            {
                height: '300px',
            });
        CKEDITOR.replace('prog_problem',
            {
                height: '300px',
            });
        CKEDITOR.replace('prog_treatment',
            {
                height: '300px',
            });
        CKEDITOR.replace('prog_input',
            {
                height: '300px',
            });
        CKEDITOR.replace('prog_plan',
            {
                height: '300px',
            });
        CKEDITOR.replace('plan_subject',
            {
                height: '300px',
            });
        CKEDITOR.replace('plan_object',
            {
                height: '300px',
            });
        CKEDITOR.replace('plan_assess',
            {
                height: '300px',
            });
        CKEDITOR.replace('plan_planning',
            {
                height: '300px',
            });
        CKEDITOR.replace('details_of_patient',
            {
                height: '200px',
            });

        $('#absconderModal').click(function () {
            $('#insert-dynamic-title').empty();
            $('#insert-dynamic-title').append('Do you want to Absconder?');
            $('#confirm-yes-button').attr('data-target', '');
            $('#confirm-yes-button').attr('url', '{{route("outcome.absconder.save")}}');
        });

        // Mark LAMA And Mark Death Dynamic modal value
        $('#markDeathModal').click(function () {
            $('#markLamaDeathTitle').empty();
            $('#markLamaDeathTitle').append('Reason For Death');
            $('#save-lama-death-modal').attr('url', '{{route("outcome.death.save")}}');
            // $('#get_related_fldcurrlocat').empty();
        });

        $('#markLamaModal').click(function () {
            $('#markLamaDeathTitle').empty();
            $('#markLamaDeathTitle').append('Reason For LAMA');
            $('#save-lama-death-modal').attr('url', '{{route("outcome.lama.save")}}');
        });

        // change form action diagnosis
        $('#final_diagnosis').click(function () {
            $('#change_action_value').attr('action', '{{route("finalDiagnosisStoreInpatient")}}');
        });
        $('#pro_diagnosis').click(function () {
            $('#change_action_value').attr('action', '{{route("diagnosisStoreInpatient")}}');
        });

        $('#final_obstetric').click(function () {
            $('.change_obstetric_action').val('data-src', '{{route("inpatient.final.obstetric.form.save.waiting")}}');
        });
        $('#pro_obstetric').click(function () {
            $('.change_obstetric_action').val('data-src', '{{route("inpatient.obstetric.form.save.waiting")}}');
        });

        // To Delete Provisional Dliagonisi
        $("#delete__provisional_item").click(function (e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                // alert($("#pat_findings_delete").val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $('#provisional_delete').each(function () {
                    // alval = [];
                    var finalval = $(this).val().toString();
                    // alert(finalval);

                    // alert(finalval);
                    $.ajax({
                        url: '{{ route("delete.provisional") }}',
                        type: "POST",
                        dataType: "json",
                        data: {ids: finalval},
                        success: function (data) {
                            // console.log(data);
                            if ($.isEmptyObject(data.error)) {
                                showAlert('Data Deleted !!');
                                $('#provisional_delete option:selected').remove();

                            } else {
                                showAlert('Something went wrong!!', 'error');
                            }
                        }
                    });
                });

            }
        });

        // To Delete Allergic
        $("#delete__final_item").click(function (e) {
            e.preventDefault();
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            if (confirm('Are you sure?') == true) {
                $('#final_delete').each(function () {
                    // alval = [];
                    var finalval = $(this).val().toString();
                    // alert(finalval);

                    // alert(finalval);
                    $.ajax({
                        url: '{{ route("delete.final") }}',
                        type: "POST",
                        dataType: "json",
                        data: {ids: finalval},
                        success: function (data) {
                            // console.log(data);
                            if ($.isEmptyObject(data.error)) {

                                showAlert('Data Deleted !!');
                                $('#final_delete option:selected').remove();
                            } else {
                                showAlert('Something went wrong!!', 'error');
                            }
                        }
                    });
                });
            }
        });

        // To Delete Allergic
        $("#delete__allergic_item").click(function (e) {
            e.preventDefault();
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            if (confirm('Are you sure?') == true) {
                $('#select-multiple-aldrug').each(function () {
                    // alval = [];
                    var finalval = $(this).val().toString();
                    // alert(finalval);

                    // alert(finalval);
                    $.ajax({
                        url: '{{ route("delete.allergic") }}',
                        type: "POST",
                        dataType: "json",
                        data: {ids: finalval},
                        success: function (data) {
                            // console.log(data);
                            if ($.isEmptyObject(data.error)) {

                                showAlert('Data Deleted !!');
                                $('#select-multiple-aldrug option:selected').remove();
                            } else {
                                showAlert('Something went wrong!!', 'error');
                            }
                        }
                    });
                });
            }
        });

        var inpatientdiagnosisfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("inpatient.diagnosis.freetext") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext').html(response);
                        setTimeout(function () {
                            $('#custom_diagnosis').focus();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#provisional-diagnosis-freetext-modal-final').modal('show');
            },
        }

        var finaldiagnosisfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    // url: "{{ route('inpatient.diagnosis.freetext.final') }}",
                    url: '{{route("inpatient.diagnosis.freetext.final")}}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext-final').html(response);
                        setTimeout(function () {
                            $('#final_custom_diagnosis').focus();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-freetext-modal-final').modal('show');
            },
        }

        var proobstetric = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("inpatient.diagnosis.obstetric") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-obstetric').html(response);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-obstetric-modal').modal('show');
            },
        }

        var finalobstetric = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("inpatient.diagnosis.final.obstetric") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-obstetric').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-obstetric-modal').modal('show');
            },
        }
        // inpatient profile image
        var imagePop = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("inpatient.image.form") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-inpatient-image').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#inpatient-image-modal').modal('show');
            },
        }
    </script>
@endpush
