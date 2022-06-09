@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        .cursor tr {
            cursor: pointer;
        }

        .rowSelected {
            background: #e0dbdb;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="col-md-6 col-md-offset-1 text-right">कृपया सेटिंगका लागि सेवा छनौट गर्नुहोस्!</h4>

                <section class="form-wrapper">
                    <div class="container">
                        <div class="col-md-6  col-md-offset-2">
                            <br>
                            @if(Session::has('error_message'))
                                <div class="alert alert-danger col">
                                    <strong> {{ Session::get('error_message') }}</strong>
                                </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success">
                                    <strong>{{ Session::get('success_message') }} </strong>
                                </div>
                                <br>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="form-wrapper">
                    <div class="container">
                        <div class="panel-body">
                            <div class="row col-md-offset-4">
                                <div class="form-group col-md-4">
                                    <label>Please Choose Service</label>
                                    <div class="input-group">
                                        <select name="service" id="service" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="emergency">Emergency Department</option>
                                            <option value="consultant">Consultation Types</option>
                                            <option value="inpatient">InPatient Service</option>
                                            <option value="anc">ANC Visit</option>
                                            <option value="diagnostic">Diagnostic Service</option>
                                            <option value="delivery">Delivery Types</option>
                                            {{-- <option value="laboratory">Laboratory List</option> --}}
                                            <option value="culture">Culture/Sensitivity</option>
                                            <option value="culture_specimens">Culture Specimens</option>
                                            <option value="free_service">Free Service Type</option>
                                            <option value="laboratory_services">Test Mapping</option>
                                        </select>
                                    </div>
                                    <strong><small
                                            class="help-block text-danger">{{$errors->first('service')}}</small>
                                    </strong>
                                </div>

                            </div>
                        </div>

                        <div class="form-group col-md-offset-5">
                            <div class="input-group">
                                <a href="{{ route('admin.dashboard') }}" name="Cancel"
                                   class=" btn btn-danger">Cancel </a>
                            </div>
                        </div>
                    </div>
                </section>

                @include('hmismapping::modals.modals')
            </div>
        </div>
    </div>
@stop
@push('after-script')

    <script src="{{asset('js/hmis_mapping.js')}}"></script>
@endpush
