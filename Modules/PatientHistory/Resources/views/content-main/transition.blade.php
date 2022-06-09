<div class="col-sm-6">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height border">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h3 class="card-title">
                    Transition
                </h3>
            </div>
            <a href="javascript:;" class="btn btn-primary float-right" onclick="patientHistory.transition('{{ $encounterData->fldencounterval??'' }}')"><i class="fa fa-eye"></i> View</a>
        </div>
        <div class="iq-card-body">
            @include('patienthistory::dynamic-data.transition')
        </div>
    </div>
</div>
