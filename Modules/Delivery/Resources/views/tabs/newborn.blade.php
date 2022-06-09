<div class="tab-pane fade" id="newborn" role="tabpanel" aria-labelledby="newborn-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="newborn-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4">Enc ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="js-newborn-children-select">
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4">Pat No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="js-newborn-patno-input" value="" readonly="readonly" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-3">Refer:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="js-newborn-refer-input">
                                        <option value="">-- Select --</option>
                                        @foreach($consultants as $consultant)
                                        <option value="{{ $consultant->fldusername }}">{{ $consultant->fldusername }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary"><i class="ri-user-fill"></i></button>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-3">DOB:</label>
                                <div class="col-sm-7">
                                    <input type="text" id="js-newborn-dob-input" class="form-control nepaliDatePicker">
                                </div>
                                <div class="col-sm-2" id="js-newborn-dob-save-btn">
                                    <button class="btn btn-primary"><i class="ri-check-fill"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4">Sex:</label>
                                <div class="col-sm-7">
                                    <select name="department" class="form-control" id="js-newborn-sex-select">
                                        <option value="">--Select--</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4">Age:</label>
                                <div class="col-sm-7">
                                    <input type="text" id="js-newborn-age-input" value="" readonly="readonly" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-row align-items-center">
                                <buttin id="js-newborn-birth-btn" class="btn btn-info rounded-pill" type="button"> <i class="fa fa-code"></i>&nbsp;Birth</buttin>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <buttin id="js-newborn-examreport-export-btn" class="btn btn-info rounded-pill" type="button"><i class="fas fa-code"></i>&nbsp;Baby</buttin>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="res-table">
                    <table class="table table-hovered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="3">Examination</th>
                            </tr>
                        </thead>
                        <tbody id="js-newborn-examination-tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="res-table mt-2">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Examination</th>
                                <th>&nbsp;</th>
                                <th>Observation</th>
                                <th>Report Time</th>
                            </tr>
                        </thead>
                        <tbody id="js-newborn-baby-examination-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
