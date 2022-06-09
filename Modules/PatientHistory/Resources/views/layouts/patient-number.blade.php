<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group row mb-0 align-items-center">
                            <label for="" class="control-label col-sm-3 mb-0">Patient ID</label>
                            <div class="col-sm-6">
                                <input type="text" name="patient_id" id="patient_id_submit" class="form-control" placeholder="Enter patient ID" value="{{ $patientDetails->fldpatientval??'' }}"/>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

