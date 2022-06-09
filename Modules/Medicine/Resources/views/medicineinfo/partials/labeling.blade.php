<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form action="{{ route('medicines.medicineinfo.addlabel') }}" id="labellingForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="flddrug" id="labelling_flddrug">
                <ul class="nav nav-tabs justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#opLabel" role="tab" aria-controls="opLabel" aria-selected="false">OP Label</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#ipLabel" role="tab" aria-controls="ipLabel" aria-selected="false">IP Label</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#udLabel" role="tab" aria-controls="udLabel" aria-selected="false">UD Label</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="opLabel" role="tabpanel" aria-labelledby="opLabel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <textarea name="fldopinfo" id="fldopinfo" class="form-control">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ipLabel" role="tabpanel" aria-labelledby="ipLabel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <textarea name="fldipinfo" id="fldipinfo" class="form-control">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="udLabel" role="tabpanel" aria-labelledby="udLabel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <textarea name="fldasepinfo" id="fldasepinfo" class="form-control">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-6">Med Counseling:</label>
                    <div class="col-sm-12">
                        <textarea name="fldmedinfo" id="fldmedinfo" class="form-control">
                        </textarea>
                    </div>
                </div>
                <button id="labelingSave" class="btn btn-primary btn-action float-right">Save</button>
            </form>
        </div>
    </div>
</div>
