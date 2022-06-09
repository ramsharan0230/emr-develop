@extends('frontend.layouts.master') @section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>Anaesthology:</label>
                                <select class="form-control form-control-sm mb-3">
                                 <option selected="">select</option>
                                 <option value="1">One</option>
                                 <option value="2">Two</option>
                                 <option value="3">Three</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>Performer consultan list:</label>
                                <select class="form-control form-control-sm mb-3">
                                 <option selected="">select</option>
                                 <option value="1">One</option>
                                 <option value="2">Two</option>
                                 <option value="3">Three</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <label>Types of Anaesthesia:</label>
                            <div class="form-group">
                                <select class="form-control form-control-sm mb-3">
                                 <option selected="">TIVA</option>
                                 <option value="1">Blocks</option>
                                 <option value="2">Macs</option>
                              </select>
                           </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Diagnosis</h4>
                                </div>
                                <div class="">

                                    @if((isset($enable_freetext) and $enable_freetext == 'Yes') || (isset($enable_freetext) and $enable_freetext == '1'))
                                        <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                                    @else
                                        <a href="javascript:void(0);" class="btn btn-primary">Free</a>
                                    @endif

                                    @if(isset($patient) and $patient->fldptsex == 'Female')
                                        <a href="javascript:void(0);" class="btn btn-primary" id="pro_obstetric" data-toggle="modal" data-target="#diagnosis-obstetric-modal" onclick="obstetric.displayModal()">OBS</a>
                                    @endif

                                    <a href="javascript:void(0);" class="btn btn-warning" data-toggle="modal" data-target="#diagnosis">ICD</a>


                                    <a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <form action="" class="form-horizontal">
                                    <div class="form-group mb-0">
                                        <select name="" id="select-multiple-diagno" class="form-control" multiple>
                                            @if(isset($patdiago) and count($patdiago) > 0)
                                                @foreach($patdiago as $patdiag)
                                                    <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Diagnosis Found</option>
                                            @endif
                                        </select>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>Procedure form:</label>
                            <div class="form-group">
                                <textarea name="" id="" cols="30" rows="8" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <label>Co morbidity:</label>
                            <div class="form-group">
                                <textarea name="" id="" cols="30" rows="8" class="form-control" ></textarea>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>Medication:</label>
                                <textarea name="" id="" cols="30" rows="8" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>monitoring:</label>
                                <textarea name="" id="" cols="30" rows="8" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>Others:</label>
                                <textarea name="" id="" cols="30" rows="8" class="form-control"></textarea>
                           </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <label>Recovery:</label>
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="customRadio-1" name="customRadio-10" class="custom-control-input bg-primary">
                                    <label class="custom-control-label" for="customRadio-1"> Complete</label>
                                </div>
                                <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                    <input type="radio" id="customRadio-1" name="customRadio-10" class="custom-control-input bg-primary">
                                    <label class="custom-control-label" for="customRadio-1"> Incomplete</label>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
