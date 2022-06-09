<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-block">
                    <div class="form-row form-group ml-2">
                    <!--                            <div class="col-sm-2 text-center">
                                <div class="history-img mt-2">
                                    <img src="{{ asset('assets/images/dummy-img.jpg')}}" alt="">
                                </div>
                            </div>-->
                        <div class="col-sm-10">
                            <div class="profile-form form-group mt-2">
                                <h5>{{ $encounterData->patientInfo->fullname }}({{ $encounterData->patientInfo->fullname }})- <small>{{ $encounterData->patientInfo->fldagestyle }} / {{ $encounterData->patientInfo->fldptsex }}</small></h5>
                                {{-- <h5>{{ $encounterData->patientInfo->fullname }}({{ $encounterData->patientInfo->fullname }})- <small>{{ \Carbon\Carbon::parse($encounterData->patientInfo->fldptbirday)->age }} Years / {{ $encounterData->patientInfo->fldptsex }}</small></h5> --}}
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">@if(isset($encounterData->patientInfo)){{ $encounterData->patientInfo->fldptaddvill }} , {{ $encounterData->patientInfo->fldptadddist }}@endif</label>
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">Date Of Birth:</label>
                                <label class="col-sm-10">{{ date('Y-m-d', strtotime($encounterData->patientInfo->fldptbirday)) }}</label>
                            </div>
                            <div class="profile-form form-group form-row">
                                <label class="col-sm-2">Encounter:</label>
                                <label class="col-sm-10">{{ $encounterData->fldencounterval }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('patienthistory::content-main.transition')
        @include('patienthistory::content-main.symptoms')
        @include('patienthistory::content-main.po_inputs')
        @include('patienthistory::content-main.exam')
        @include('patienthistory::content-main.laboratory')
        @include('patienthistory::content-main.radiology')
        {{--            @include('patienthistory::content-main.diagnosis')--}}
        @include('patienthistory::content-main.notes')
        @include('patienthistory::content-main.med_dosing')
        @include('patienthistory::content-main.progress')
        @include('patienthistory::content-main.nur_activity')
        @include('patienthistory::content-main.bladder_irrigation')
    </div>
</div>
<style>
    #file-modal .table {
        width: 100%;
        /* display: block; */
        max-height: 500px;
        overflow-y: scroll;
    }
</style>
<script>
    var patientHistory = {
        transition: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.transition') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Transition');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        symptoms: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.symptoms') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Symptoms');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        foods: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.foods') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Foods');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        exam: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.exam') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Exams');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        laboratory: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.laboratory') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Laboratory');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        radiology: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.radiology') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Radiology');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        notes: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.notes') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Notes');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        medDosing: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.medDosing') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Med Dosing');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        progress: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.progress') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Progress');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        nursActivity: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.nursActivity') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Nurse Activity');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        },
        bladder: function (encounter) {
            $.ajax({
                url: "{{ route('patient.history.bladder') }}",
                type: "POST",
                dataType: "json",
                data: {encounter: encounter},
                success: function (data) {
                    $(".file-modal-title").empty().text('Foods');
                    $(".file-form-data").empty();
                    if (data.count > 0) {
                        $(".file-modal-title").empty().text('Bladder');
                        $(".file-form-data").html(data.html);
                        $('#file-modal').modal('show');
                    } else {
                        showAlert("No data.", 'error');
                    }
                }
            });
        }
    }
</script>
