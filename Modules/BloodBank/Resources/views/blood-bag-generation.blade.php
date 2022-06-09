@extends('frontend.layouts.master')

@section('content')
    @include('frontend.common.alert_message')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Blood Bag Generation</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST" id="js-bag-generation-form" >
                            @csrf
                            <div class="row form-group">
                                <div class="col-sm-3">
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
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Bag Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="bag_date" value="{{ request()->get('bag_date') ? request()->get('bag_date') :$dates }}" id="bag_date" class="form-control nepaliDatePicker col-sm-10" >
                                            @if(isset($form_errors['bag_date']))<div class="text-danger">{{ $form_errors['bag_date'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Bag No</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="bag_no" value="{{ request()->get('bag_no') }}" id="bag_no" class="form-control col-sm-10" >
                                            @if(isset($form_errors['bag_no']))<div class="text-danger">{{ $form_errors['bag_no'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Consent Date</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar nepaliDatePicker" name="consent_date" id="consent_date" placeholder="" style="background:none;" value="{{ request()->get('consent_date') ? request()->get('consent_date') :$dates }}" readonly>
                                                    @if(isset($form_errors['consent_date']))<div class="text-danger">{{ $form_errors['consent_date'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Consent No</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" name="consent_no" id="consent_no" placeholder="" style="background:none;">
                                                    @if(isset($form_errors['consent_no']))<div class="text-danger">{{ $form_errors['consent_no'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Donar</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="search-patient" name="donor" placeholder="" style="background:none;">
{{--                                                    <a class="search-link" id="search-patient-btn" href="javascript:;"><i class="ri-search-line"></i></a>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Mobile No</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar " name="mobile" id="mobile" placeholder="" style="background:none;" readonly>
                                                    @if(isset($form_errors['mobile']))<div class="text-danger">{{ $form_errors['mobile'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Blood Group</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <div class="form-group form-row align-items-center">
                                                        <div class="col-sm-9">
                                                            <select name="blood_group" id="blood_group" class="form-control">
                                                                <option value="">--- Select ---</option>
                                                                <option value="A" @if (request()->get('blood_group') == "A") selected @endif>A</option>
                                                                <option value="B" @if (request()->get('blood_group') == "B") selected @endif>B</option>
                                                                <option value="AB" @if (request()->get('blood_group') == "AB") selected @endif>AB</option>
                                                                <option value="O" @if (request()->get('blood_group') == "O") selected @endif>O</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if(isset($form_errors['blood_group']))<div class="text-danger">{{ $form_errors['blood_group'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Donation Type</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar " id="donation_type" name="donation_type" placeholder="" readonly>
                                                    @if(isset($form_errors['donation_type']))<div class="text-danger">{{ $form_errors['donation_type'] }} </div>@endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Rh Type</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="rh_type" name="rh_type"  placeholder="" readonly>
                                                    @if(isset($form_errors['rh_type']))<div class="text-danger">{{ $form_errors['rh_type'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Bag Type</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <select class="form-control" name="bag_type" id="bag_type">
                                                    <option value="">--select--</option>
                                                    @forelse($bag_types as $bag_type)
                                                        <option value="{{ $bag_type->id }}">{{ $bag_type->description }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            @if(isset($form_errors['bag_type']))<div class="text-danger">{{ $form_errors['bag_type'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Tube ID</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" name="tube_id" id="tube_id" placeholder="" style="background:none;">
                                                    @if(isset($form_errors['tube_id']))<div class="text-danger">{{ $form_errors['tube_id'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Collect Date</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text nepaliDatePicker search-input search-input-donar" id="collect_date" name="collect_date" placeholder="" value="{{ request()->get('collect_date') ? request()->get('collect_date') :$dates }}">
                                                    @if(isset($form_errors['collect_date']))<div class="text-danger">{{ $form_errors['collect_date'] }} </div>@endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Start Time</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="time" class="text " id="start_time" name="start_time"  placeholder="" >
                                                    @if(isset($form_errors['start_time']))<div class="text-danger">{{ $form_errors['start_time'] }} </div>@endif</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">End Time</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="time" class="text " id="end_time" name="end_time"  placeholder="" >
                                                    @if(isset($form_errors['end_time']))<div class="text-danger">{{ $form_errors['end_time'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

{{--                            <div class="form-group form-row mt-2">--}}
{{--                                <label class="col-sm-1">Remarks</label>--}}
{{--                                <div class="col-sm-4">--}}
{{--                                    <input type="text" name="remarks" value="{{ request()->get('remarks') }}" id="remarks" class="form-control">--}}
{{--                                    @if(isset($form_errors['remarks']))<div class="text-danger">{{ $form_errors['remarks'] }} </div>@endif--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <input type="radio" name="is_accepted" id="is_accepted" value="1" required>--}}
{{--                                    <label class="col-sm-1">Accepted</label>--}}

{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <input type="radio" name="is_accepted" id="is_rejected" value="0" required>--}}
{{--                                    <label class="col-sm-1">Rejected</label>--}}
{{--                                </div>--}}
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

            $('#consent_no').on('keydown', function(e) {

                if (e.which == 13) {
                    e.preventDefault();
                    var consent = $('#consent_no').val();
                    if(consent !=''){
                        searchPatient(consent);
                    }else {
                        showAlert('Enter consent no to search','error');
                        return false;
                    }

                }
            });


            // $('#search-patient').on('keydown', function(e) {
            //
            //     if (e.which == 13) {
            //         e.preventDefault();
            //
            //         var donor = $('#search-patient').val();
            //
            //         if(donor ==''){
            //             showAlert('Enter donor no to search','error');
            //             return false;
            //         }
            //
            //         searchPatient(donor,'donor');
            //     }
            // });

            // $('#search-patient-btn').on('click', function(e) {
            //     var donor = $('#search-patient').val();
            //     if(donor ==''){
            //         showAlert('Enter donor no to search','error');
            //         return false;
            //     }
            //     searchPatient(donor,'donor');
            // });


            function searchPatient(val) {
                if (val !== '') {
                    $.ajax({
                        url: baseUrl + "/bloodbank/blood-bag/searchPatient",
                        type: "GET",
                        data: {
                            search_value: val,
                        },
                        dataType: "json",
                        success: function (response) {
                            $('form#js-bag-generation-form')[0].reset();
                            // $('#search-patient').val(text);
                            if (response) {
                                $('#search-patient').val(response.donor ? response.donor.donor_no: "");
                                $('#mobile').val(response.donor ? response.donor.mobile :'');
                                $('#blood_group').val( response.donor ? response.donor.blood_group :'');
                                $('#branch').val(response.branch_id);
                                $('#rh_type').val(response.donor ? response.donor.rh_type :'');
                                $('#donation_type').val(response.donor? response.donor.type :'');
                                $('#consent_no').val(response.id);
                                $('#consent_date').val(response.created_at);
                            }

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
