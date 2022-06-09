@extends('frontend.layouts.master')

@section('content')
    @include('frontend.common.alert_message')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Cross Matching Result</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST" id="js-result-authorization-form" >
                            @csrf
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Branch</label>
                                        <div class="col-sm-8">
                                            <select name="branch" id="branch" class="form-control">
                                                <option value="">--- Select ---</option>
                                                @foreach ($hospitalbranches as $hospitalbranch)
                                                    <option value="{{ $hospitalbranch->id }}" @if (request()->get('branch') == $hospitalbranch->id) selected @endif>{{ $hospitalbranch->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($form_errors['branch']))<div class="text-danger">{{ $form_errors['branch'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Request Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="request_date" value="{{ request()->get('request_date') ? request()->get('request_date') :$dates }}" id="request_date" class="form-control nepaliDatePicker col-sm-10"  required>
                                            @if(isset($form_errors['request_date']))<div class="text-danger">{{ $form_errors['request_date'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Request No</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="request_no" value="{{ request()->get('request_no') ? request()->get('request_no') :$dates }}" id="request_no" class="form-control nepaliDatePicker col-sm-10" required>
                                            @if(isset($form_errors['request_no']))<div class="text-danger">{{ $form_errors['request_no'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group form-row align-items-center">
                                        <div class="col-sm-9">
                                            <a class="search-link" id="searchBtn" name="searchBtn" href="javascript:;"><i class="ri-search-line"></i></a>
                                            {{--                                            <button type="button" name="searchBtn"  id="searchBtn" class="form-control col-sm-10"><i class="ri-search-line"></i> </button>--}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>

                            <hr>
                            <div class="col-sm-2">
                                <button  class="btn btn-primary"><i class="ri-save-3-line"></i></button>
                                <button  class="btn btn-primary"><i class="fa fa-check"></i></button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('after-script')
    <script>
        $(document).ready(function () {
            var nepaliDateConverter = new NepaliDateConverter();

            $('#searchBtn').on('click', function(e) {
                searchPatient();
            });


            function searchPatient() {

                var branch = $('#branch').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var from_bag_no = $('#from_bag_no').val();
                var to_bag_no = $('#to_bag_no').val();
                if (from_date !== '' || to_date!= '') {
                    $.ajax({
                        url: baseUrl + "/bloodbank/result-authorization/search",
                        type: "GET",
                        data: {
                            branch : branch,
                            from_date : from_date,
                            to_date : to_date,
                            from_bag_no : from_bag_no,
                            to_bag_no : to_bag_no,
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            $('form#js-result-authorization-form')[0].reset();

                            if(response.error){
                                showAlert(response.error,'error');
                            }
                        }
                    });
                }
            }
        });
    </script>
@endpush
