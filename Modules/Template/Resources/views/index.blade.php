@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Complete Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#birth_certificate" role="tab" aria-controls="contact"
                                   aria-selected="false">Birth Certificate</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-3">
                            <div class="tab-pane fade" id="birth_certificate" role="tabpanel" aria-labelledby="birth_certificate">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Birth Certificate</h4>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('template.update') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-8 col-md-12">
                                            <div class="form-group form-row">
                                                <textarea class="form-control" name="birth_template" id="birth_template" placeholder="Please enter template content here">{{ Options::get('birth_certificate_template') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <h4>List of variables</h4>
                                            <ul>
                                                <li>{$gender}</li>
                                                <li>{$wife}</li>
                                                <li>{$guardian}</li>
                                                <li>{$address}</li>
                                                <li>{$date}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    </script>

@stop

@push('after-script')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('birth_template');
            });
    </script>
@endpush

