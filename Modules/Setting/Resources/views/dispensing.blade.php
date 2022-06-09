@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Dispensing Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <form method="POST" class="form-horizontal">
                                    @csrf
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Frequency/Day*</label>
                                        <div class="col-sm-9">
                                            <select class="form-control col-9" name="dispensing_freq_dose" id="dispensing_freq_dose">
                                                <option value="">---Select---</option>
                                                <option value="Manual" {{ Options::get('dispensing_freq_dose') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                                <option value="Auto" {{ Options::get('dispensing_freq_dose') == 'Auto' ? 'selected' : '' }}>Auto</option>
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('dispensing_freq_dose')}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Direct Purchase Entry*</label>
                                        <div class="col-sm-9">
                                            <select class="form-control col-9" name="direct_purchase_entry" id="direct_purchase_entry">
                                                <option value="Yes" {{ Options::get('direct_purchase_entry') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ Options::get('direct_purchase_entry') != 'Yes' ? 'selected' : '' }}>No</option>
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('direct_purchase_entry')}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Medicine by Category*</label>
                                        <div class="col-sm-9">
                                            <select class="form-control col-9" name="medicine_by_category" id="medicine_by_category">
                                                <option value="Yes" {{ Options::get('medicine_by_category') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ Options::get('medicine_by_category') != 'Yes' ? 'selected' : '' }}>No</option>
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('medicine_by_category')}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Medicine Stock*</label>
                                        <div class="col-sm-9">
                                            <select class="form-control col-9" name="dispensing_medicine_stock" id="dispensing_medicine_stock">
                                                <option value="">---Select---</option>
                                                <option value="FIFO" {{ Options::get('dispensing_medicine_stock') == 'FIFO' ? 'selected' : '' }}>FIFO</option>
                                                <option value="LIFO" {{ Options::get('dispensing_medicine_stock') == 'LIFO' ? 'selected' : '' }}>LIFO</option>
                                                <option value="Expiry" {{ Options::get('dispensing_medicine_stock') == 'Expiry' ? 'selected' : '' }}>Expiry</option>
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('dispensing_medicine_stock')}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center" id="js-expiry-date-div">
                                        <label for="" class="col-sm-3">Expiry limit (In Days)*</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="dispensing_expiry_limit" id="dispensing_expiry_limit" class="form-control col-9" value="{{ Options::get('dispensing_expiry_limit') }}">
                                            <small class="help-block text-danger">{{$errors->first('dispensing_expiry_limit')}}</small>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3"></label>
                                        <div class="col-sm-9">
                                            <button class="btn btn-primary btn-action">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@push('after-script')
    <script type="text/javascript">
        $(document).ready(function() {
            toggleExpiryInput();
            function toggleExpiryInput() {
                var dispensing_medicine_stock = $('#dispensing_medicine_stock').val() || '';
                if (dispensing_medicine_stock == 'Expiry')
                    $('#js-expiry-date-div').show();
                else
                    $('#js-expiry-date-div').hide();
            }
            $('#dispensing_medicine_stock').change(function() {
                toggleExpiryInput();
            });
        });
    </script>
@endpush
