@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">SSF (Social Security Fund) API Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-lg-8">
                            <form action="{{ route('ssf.setting.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">SSF URL</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('ssf_settings')['ssf_url'] ?? '' }}" name="ssf_url">
                                </div>
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('ssf_settings')['ssf_username'] ?? '' }}" name="ssf_username">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('ssf_settings')['ssf_password'] ?? '' }}" name="ssf_password">
                                </div>
                                <div class="form-group">

                                    <label for="">Remote User</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('ssf_settings')['ssf_remote_user'] ?? '' }}" name="ssf_remote_user">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    
                     <div class="col-sm-6">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Claim Upload API Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-lg-8">
                            <form action="{{ route('claim.setting.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">HI URL</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('claim_settings')['claim_url'] ?? '' }}" name="claim_url">
                                </div>
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('claim_settings')['claim_username'] ?? '' }}" name="claim_username">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('claim_settings')['claim_password'] ?? '' }}" name="claim_password">
                                </div>
                                <div class="form-group">

                                    <label for="">Access Code</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('claim_settings')['claim_access_code'] ?? '' }}" name="claim_access_code">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="col-sm-6">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Health Insurance API Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-lg-8">
                            <form action="{{ route('hi.setting.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">HI URL</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_url'] ?? '' }}" name="hi_url">
                                </div>
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_username'] ?? '' }}" name="hi_username">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_password'] ?? '' }}" name="hi_password">
                                </div>
                                <div class="form-group">

                                    <label for="">Remote User</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_remote_user'] ?? '' }}" name="hi_remote_user">
                                </div>
                                <div class="form-group">

                                    <label for="">Location UUID</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_location'] ?? '' }}" name="hi_location">
                                </div>
                                <div class="form-group">

                                    <label for="">Practitioner UUID</label>
                                    <input required type="text" class="form-control" value="{{ Options::get('hi_settings')['hi_practitioner'] ?? '' }}" name="hi_practitioner">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@push('after-script')
    <script>


    </script>
@endpush

