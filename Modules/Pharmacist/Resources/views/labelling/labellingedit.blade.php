@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Labelling
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="form-group form-row align-items-center er-input">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadio6" name="customRadio-1" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio6">Essential   </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadio7" name="customRadio-1" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio7"> Frequency  </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadio8" name="customRadio-1" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio8">Dosage Form</label>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-2">Word:</label>
                                <div class="col-sm-10">
                                    <select name="" class="form-control">
                                        <option value="%">%</option>
                                        <option >1</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-3">English:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $locallabel->fldengdire }}"/>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-3">local:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $locallabel->fldlocaldire }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group form-row align-items-center mt-3">
                            </div>
                            <a href="#" class="btn btn-info rounded-pill" type="button"> <i class="fa fa-search"></i>&nbsp;Search</a>&nbsp;
                            <a href="#" class="btn btn-warning rounded-pill" type="button"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
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
            <div class="table-responsive table-scroll-consult">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr><th class="tittle-th">Code</th>
                            <th class="tittle-th">English</th>
                            <th class="tittle-th">Local</th>
                            <th class="tittle-th">Action</th>
                        </tr></thead>
                        <tbody></tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
