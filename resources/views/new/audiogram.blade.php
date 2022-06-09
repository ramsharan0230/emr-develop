@extends('frontend.layouts.master')
<style>
    .width-long {
        width: 18%;
    }
    .width-long2 {
        width: 10%;
    }
    .custom-col-audiogram{
      flex: 0 0 25%;
    max-width: 25%;
    position: relative;
    width: 100%;
    padding-right: 10px;
    padding-left: 10px;
    }
.canvas__img {
    border: 2px solid;
    position: absolute;
}
.img-audiogram-ear img {
    position: absolute;
    width: 246px;
    left: 16px;
    top: 54px;
}
.img-audiogram-nose img {
    position: absolute;
    width: 246px;
     top: 43px;
}
.img-audiogram-throat img {
    position: absolute;
  width: 211px;
    left: 35px;
}
.img-audiogram-tongue img {
    position: absolute;
  width: 211px;
    left: 24px;
     top: 46px;
}

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div id="accordion">
                        <div class="accordion-nav">
                            <ul>
                                <li><a href="#" data-toggle="collapse" data-target="#chief-complaint" aria-expanded="false" aria-controls="collapseOne">Chief Complaints</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#systemic-illness" aria-expanded="false" aria-controls="collapseOne">Systemic Illness</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#allergy" aria-expanded="false" aria-controls="collapseOne">Allergy</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#current-medication" aria-expanded="false" aria-controls="collapseOne">Current Medication</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#history" aria-expanded="false" aria-controls="collapseOne">History</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#on-examination" aria-expanded="false" aria-controls="collapseOne">On Examination</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#procedure" aria-expanded="false" aria-controls="collapseOne">Procedure</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#audiogram" aria-expanded="true" aria-controls="collapseOne">Audiogram</a></li>
                            </ul>
                        </div>
                        <div id="chief-complaint" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="systemic-illness" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="allergy" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="current-medication" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="history" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="on-examination" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="procedure" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"></div>
                        <div id="audiogram" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="mb-2 mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th colspan="2">Frequency</th>
                                                <th>125</th>
                                                <th>250</th>
                                                <th>500</th>
                                                <th>750</th>
                                                <th>1000</th>
                                                <th>1500</th>
                                                <th>2000</th>
                                                <th>3000</th>
                                                <th>4000</th>
                                                <th>6000</th>
                                                <th>8000</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="width-long" rowspan="2">AC, masked if necessary</td>
                                                <td class="width-long2">Right Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long2">Left Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long" rowspan="2">AC, not masked(shadow)</td>
                                                <td class="width-long2">Right Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long2">Left Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long" rowspan="2">BC, not masked</td>
                                                <td class="width-long2">Right Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long2">Left Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long" rowspan="2">BC, masked</td>
                                                <td class="width-long2">Right Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long2">Left Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long" rowspan="2">ULL</td>
                                                <td class="width-long2">Right Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                            <tr>
                                                <td class="width-long2">Left Ear</td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                                <td><input type="text" name="" value="" class="td-input" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-lg-2 col-sm-4 text-right">Audiometer:</label>
                                        <div class="col-lg-6 col-sm-6">
                                            <input type="text" class="form-control" name="flditemcost">
                                        </div>
                                    </div>
                                     <div class="form-group form-row align-items-center">
                                        <label class="col-lg-2 col-sm-4 text-right">Tester:</label>
                                        <div class="col-lg-6 col-sm-6">
                                            <input type="text" class="form-control" name="flditemcost">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-lg-2 col-sm-4 text-right">Remarks:</label>
                                        <div class="col-lg-6 col-sm-6">
                                            <input type="text" class="form-control" name="flditemcost">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2 mb-3 text-center">
                                        <button class="btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        <button class="btn btn-primary"><i class="fas fa-code"></i>&nbsp;&nbsp;Report</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                       <div class="custom-col-audiogram">
                    <h4>Ear</h4>
                    <div class="eye-img img-audiogram-ear">
                        <img src="{{asset('assets/images/ear.png')}}">
                        <canvas id="canvasRight-draw" height="210" width="244" class="canvas__img"></canvas>
                    </div>
                </div>
                         <div class="custom-col-audiogram">
                            <h4>Nose</h4>
                            <div class="eye-img img-audiogram-nose">
                                <img src="{{asset('assets/images/nose.jpeg')}}" />
                                <canvas id="canvasRight-draw" height="210" width="244" class="canvas__img"></canvas>
                            </div>
                        </div>
                        <div class="custom-col-audiogram">
                            <h4>Throat</h4>
                            <div class="eye-img img-audiogram-throat">
                                <img src="{{asset('assets/images/throat.jpeg')}}" />
                                <canvas id="canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                            </div>
                        </div>
                        <div class="custom-col-audiogram">
                            <h4>Tongue</h4>
                            <div class="eye-img img-audiogram-tongue">
                                <img src="{{asset('assets/images/tongue.jpeg')}}" />
                                <canvas id="canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Note</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group">
                                <textarea id="js-note-ck-textarea" name="examgeneral[Note]" class="form-control ck-eye">{{ isset($exam['otherData']['note']) ? $exam['otherData']['note'] : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Advice</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group">
                                <textarea id="js-advice-ck-textarea" name="examgeneral[Advice]" class="form-control ck-eye">{{ isset($exam['otherData']['advice']) ? $exam['otherData']['advice'] : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="d-flex justify-content-around">
                        <button type="button" onclick="laboratory.displayModal()" class="btn btn-primary">Laboratory</button>
                        <button type="button" onclick="radiology.displayModal()" class="btn btn-primary">Radiology</button>
                        <button type="button" onclick="pharmacy.displayModal()" class="btn btn-primary">Pharmacy</button>
                        <a href="{{ route('eye.histry.pdf', $patient->fldpatientval ?? 0) }}?eye" target="_blank">
                            <button type="button" class="btn btn-primary">History</button>
                        </a>
                        <a href="{{ Session::has('eye_encounter_id')?route('eye.opd.sheet.pdf', Session::get('eye_encounter_id')??0 ): '' }}?eye" type="button" class="btn-custom-opd" target="_blank">
                            <button type="button" class="btn btn-primary">OPD Sheet</button>
                        </a>
                        <button class="btn btn-primary">Save</button>
                        <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish">
                            <button class="btn btn-primary">Finish</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
