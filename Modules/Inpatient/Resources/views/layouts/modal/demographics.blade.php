<div class="modal" id="js-clinical-demographics-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Clinical Demographics</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-2">Name</label>
                                <div class="col-md-10">
                                    <input readonly="readonly" type="text" id="js-clinical-demographics-name-input" class="form-control" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{ $patient->fldptnamelast }}@endif">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-2">Gender</label>
                                <div class="col-md-10">
                                    <input readonly="readonly" type="text" id="js-clinical-demographics-gender-input" class="form-control" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row top-req">
                        <div class="col-md-4">
                            <div class="res-table">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="form">
                                <!-- <div class="radio-1" style="margin-left: 50px;">
                                    <input type="radio" class="radio-custom">
                                    <label for="" style="border:none;">Manual</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="radio-custom-demographic">
                                    <label for="" style="border:none;">Alphabet</label>
                                </div> -->
                            </div>
                            <div class="form">
                                @if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif
                                @if(isset($enpatient))
                                <a href="{{ route('dataentry.reportClinicalDemographics') }}?encounterId={{ $enpatient->fldencounterval }}" target="_blank">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-code"></i>&nbsp; Report
                                    </button>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 ">
                            <div class="form-gourp form-row align-items-center">
                                <label for="" style="border:none;" class="col-md-2">Value</label>
                                <div class="col-sm-8">
                                    <input type="text" id="js-clinical-demographics-fldreportquali" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary" id="js-clinical-demographics-add"><i class="ri-add-line"></i> Save</button>
                                </div>
                            </div>
                            <div class="res-table mt-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="tittle-th">&nbsp;</th>
                                            <th class="tittle-th">Variable</th>
                                            <th class="tittle-th">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-clinical-demographics-tbody"></tbody>
                                </table>
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
    $('#js-clinical-demographics-add').click(function() {
        $.ajax({
            url: baseUrl + '/inpatient/dataEntryMenu/saveClinicalDemographics',
            type: "POST",
            data: {
                fldreportquali: $('#js-clinical-demographics-fldreportquali').val(),
                encounterId: globalEncounter,
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var val = response.data;

                    var trData = '<tr>';
                    trData += '<td>' + ($('#js-clinical-demographics-tbody tr').length+1) + '</td>';
                    trData += '<td>' + (val.flditem == null ? '' : val.flditem)+ '</td>';
                    trData += '<td>' + val.fldreportquali + '</td></tr>';
                    $('#js-clinical-demographics-tbody').append(trData);
                }
                showAlert(response.message);
            }
        });
    });
</script>
@endpush
