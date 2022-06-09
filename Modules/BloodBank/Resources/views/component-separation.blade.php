@extends('frontend.layouts.master')

@section('content')
    @include('frontend.common.alert_message')

    <template id="component-template">
        <div class="row new-component">
            <div class="col-sm-3">
                <div class="form-group form-row align-items-center">
                    <label class="col-sm-4">Component</label>
                    <div class="col-sm-8">

                        <select name="component[]" id="component" class="form-control">
                            <option value="">--Select--</option>
                            @forelse( $tests as $test)
                                <option value="{{ $test->fldtestid  }}"> {{ $test->fldtestid  }}</option>
                            @empty
                            @endforelse
                        </select>

{{--                        <div class="iq-search-bar p-0">--}}
{{--                            <div class="searchbox full-width">--}}
{{--                                <input type="text" class="text search-input search-input-donar"--}}
{{--                                       id="component" name="component[]" placeholder=""--}}
{{--                                       style="background:none;">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group form-row align-items-center">
                    <label class="col-sm-4">Volume</label>
                    <div class="col-sm-8">
                        <div class="iq-search-bar p-0">
                            <div class="searchbox full-width">
                                <input type="text" class="text search-input search-input-donar"
                                       name="volume[]" id="volume" placeholder=""
                                       style="background:none;" >
                                @if(isset($form_errors['volume']))
                                    <div class="text-danger">{{ $form_errors['volume'] }} </div>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group form-row align-items-center">
                    <label class="col-sm-4">Date</label>
                    <div class="col-sm-8">
                        <div class="iq-search-bar p-0">
                            <div class="searchbox full-width">
                                <input type="text"
                                       class="text nepaliDatePicker search-input search-input-donar"
                                       id="date" name="date[]" placeholder=""
                                       value="{{ $dates }}" >
                                @if(isset($form_errors['date']))
                                    <div class="text-danger">{{ $form_errors['date'] }} </div>@endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group form-row align-items-center">
                    <label class="col-sm-4">Time</label>
                    <div class="col-sm-8">
                        <div class="iq-search-bar p-0">
                            <div class="searchbox full-width">
                                <input type="time" class="text " id="time" name="time[]"
                                       placeholder="" >
                                @if(isset($form_errors['time']))
                                    <div class="text-danger">{{ $form_errors['time'] }} </div>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-danger btn-sm-in mt-4 component-remove-btn"><i class="fa fa-times"></i></button>
        </div>
    </template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Component Separation</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form method="POST" id="js-bag-generation-form">
                            @csrf
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Branch</label>
                                        <div class="col-sm-8">
                                            <select name="branch" id="branch" class="form-control">
                                                <option value="">--- Select ---</option>
                                                @foreach ($hospitalbranches as $hospitalbranch)
                                                    <option value="{{ $hospitalbranch->id }}"
                                                            @if (request()->get('branch') == $hospitalbranch->id) selected @endif>{{ $hospitalbranch->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($form_errors['branch']))
                                                <div class="text-danger">{{ $form_errors['branch'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Bag Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="bag_date"
                                                   id="bag_date" class="form-control nepaliDatePicker col-sm-10">
                                            @if(isset($form_errors['bag_date']))
                                                <div class="text-danger">{{ $form_errors['bag_date'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Bag No</label>
                                        <div class="col-sm-9">
                                            <div class="col-sm-8">
                                                <div class="iq-search-bar p-0">
                                                    <div class="searchbox full-width">
                                                        <input type="text" name="bag_no" value=" "
                                                               id="bag_no" class="form-control col-sm-10">
                                                        <a class="search-link" id="search-bag-btn"
                                                           href="javascript:;"><i class="ri-search-line"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(isset($form_errors['bag_no']))
                                                <div class="text-danger">{{ $form_errors['bag_no'] }} </div>@endif

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
                                                    <input type="text"
                                                           class="text search-input search-input-donar nepaliDatePicker"
                                                           name="consent_date" id="consent_date" placeholder=""
                                                           style="background:none;"
                                                           value="{{ $dates }}" readonly>
                                                    @if(isset($form_errors['consent_date']))
                                                        <div class="text-danger">{{ $form_errors['consent_date'] }} </div>@endif
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
                                                    <input type="text" class="text search-input search-input-donar"
                                                           name="consent_no" id="consent_no" placeholder=""
                                                           style="background:none;" readonly>
                                                    @if(isset($form_errors['consent_no']))
                                                        <div class="text-danger">{{ $form_errors['consent_no'] }} </div>@endif
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
                                                    <input type="text" class="text search-input search-input-donar"
                                                           id="donor" name="donor" placeholder=""
                                                           style="background:none;" readonly>
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
                                                    <input type="text" class="text search-input search-input-donar "
                                                           name="mobile" id="mobile" placeholder=""
                                                           style="background:none;" readonly>
                                                    @if(isset($form_errors['mobile']))
                                                        <div class="text-danger">{{ $form_errors['mobile'] }} </div>@endif
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
                                                            <select name="blood_group" id="blood_group"
                                                                    class="form-control">
                                                                <option value="">--- Select ---</option>
                                                                <option value="A"
                                                                        @if (request()->get('blood_group') == "A") selected @endif>
                                                                    A
                                                                </option>
                                                                <option value="B"
                                                                        @if (request()->get('blood_group') == "B") selected @endif>
                                                                    B
                                                                </option>
                                                                <option value="AB"
                                                                        @if (request()->get('blood_group') == "AB") selected @endif>
                                                                    AB
                                                                </option>
                                                                <option value="O"
                                                                        @if (request()->get('blood_group') == "O") selected @endif>
                                                                    O
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if(isset($form_errors['blood_group']))
                                                        <div class="text-danger">{{ $form_errors['blood_group'] }} </div>@endif
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
                                                    <input type="text" class="text search-input search-input-donar "
                                                           id="donation_type" name="donation_type" placeholder="" readonly>
                                                    @if(isset($form_errors['donation_type']))
                                                        <div class="text-danger">{{ $form_errors['donation_type'] }} </div>@endif

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
                                                    <input type="text" class="text search-input search-input-donar"
                                                           id="rh_type" name="rh_type" placeholder="" readonly>
                                                    @if(isset($form_errors['rh_type']))
                                                        <div class="text-danger">{{ $form_errors['rh_type'] }} </div>@endif
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
                                            @if(isset($form_errors['bag_type']))
                                                <div class="text-danger">{{ $form_errors['bag_type'] }} </div>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Tube ID</label>
                                        <div class="col-sm-8">
                                            <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar"
                                                           name="tube_id" id="tube_id" placeholder=""
                                                           style="background:none;" readonly>
                                                    @if(isset($form_errors['tube_id']))
                                                        <div class="text-danger">{{ $form_errors['tube_id'] }} </div>@endif
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
                                                    <input type="text"
                                                           class="text nepaliDatePicker search-input search-input-donar"
                                                           id="collect_date" name="collect_date" placeholder=""
                                                           value="{{ $dates }}">
                                                    @if(isset($form_errors['collect_date']))
                                                        <div class="text-danger">{{ $form_errors['collect_date'] }} </div>@endif

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
                                                    <input type="time" class="text " id="start_time" name="start_time"
                                                           placeholder="" readonly>
                                                    @if(isset($form_errors['start_time']))
                                                        <div class="text-danger">{{ $form_errors['start_time'] }} </div>@endif
                                                </div>
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
                                                    <input type="time" class="text " id="end_time" name="end_time"
                                                           placeholder="" readonly>
                                                    @if(isset($form_errors['end_time']))
                                                        <div class="text-danger">{{ $form_errors['end_time'] }} </div>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5>Separation</h5>
                          <div class="component-div">
                              <div class="row">
                                  <div class="col-sm-3">
                                      <div class="form-group form-row align-items-center">
                                          <label class="col-sm-4">Component</label>
                                          <div class="col-sm-8">

                                              <select name="component[]" id="component" class="form-control">
                                                  <option value="">--Select--</option>
                                                  @forelse( $tests as $test)
                                                    <option value="{{ $test->fldtestid  }}"> {{ $test->fldtestid  }}</option>
                                                  @empty
                                                  @endforelse
                                              </select>
{{--                                              <div class="iq-search-bar p-0">--}}
{{--                                                  <div class="searchbox full-width">--}}
{{--                                                      <input type="text" class="text search-input search-input-donar"--}}
{{--                                                             id="component" name="component[]" placeholder=""--}}
{{--                                                             style="background:none;">--}}
{{--                                                  </div>--}}
{{--                                              </div>--}}
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-2">
                                      <div class="form-group form-row align-items-center">
                                          <label class="col-sm-4">Volume</label>
                                          <div class="col-sm-8">
                                              <div class="iq-search-bar p-0">
                                                  <div class="searchbox full-width">
                                                      <input type="text" class="text search-input search-input-donar"
                                                             name="volume[]" id="volume" placeholder=""
                                                             style="background:none;" >
                                                      @if(isset($form_errors['volume']))
                                                          <div class="text-danger">{{ $form_errors['volume'] }} </div>@endif
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="form-group form-row align-items-center">
                                          <label class="col-sm-4">Date</label>
                                          <div class="col-sm-8">
                                              <div class="iq-search-bar p-0">
                                                  <div class="searchbox full-width">
                                                      <input type="text"
                                                             class="text nepaliDatePicker search-input search-input-donar"
                                                             id="date" name="date[]" placeholder=""
                                                             value="{{ $dates }}" >
                                                      @if(isset($form_errors['date']))
                                                          <div class="text-danger">{{ $form_errors['date'] }} </div>@endif

                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="form-group form-row align-items-center">
                                          <label class="col-sm-4">Time</label>
                                          <div class="col-sm-8">
                                              <div class="iq-search-bar p-0">
                                                  <div class="searchbox full-width">
                                                      <input type="time" class="text " id="time" name="time[]"
                                                             placeholder="" >
                                                      @if(isset($form_errors['time']))
                                                          <div class="text-danger">{{ $form_errors['time'] }} </div>@endif
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-1">
                                      <div class="form-group form-row align-items-center">
                                          <div class="col-sm-8">
                                              <button type="button" class="btn btn-primary btn-sm-in mt-4 component-add-button"><i class="fa fa-plus"></i></button>
                                          </div>
                                      </div>
                                  </div>


                              </div>
                          </div>

                            <hr>
                            <div class="form-group form-row mt-2">
                                <label class="col-sm-2">Print component instructions</label>
                                <div class="col-sm-4">
                                    <input type="checkbox" name="is_accepted" id="is_accepted" value="1" required>
                                    @if(isset($form_errors['remarks']))
                                        <div class="text-danger">{{ $form_errors['remarks'] }} </div>@endif
                                </div>
                                {{--                                                            <div class="col-sm-4">--}}
                                {{--                                                                <label class="col-sm-1">Print component instructions</label>--}}
                                {{--                                                                <input type="checkbox" name="is_accepted" id="is_accepted" value="1" required>--}}
                                {{--                                                            </div>--}}
                                {{--                                                            <div class="col-sm-2">--}}
                                {{--                                                                <input type="radio" name="is_accepted" id="is_rejected" value="0" required>--}}
                                {{--                                                                <label class="col-sm-1">Rejected</label>--}}
                                {{--                                                            </div>--}}

                                <div class="col-sm-2">
                                    <button class="btn btn-primary"><i class="ri-save-3-line"></i></button>
                                    <button class="btn btn-primary"><i class="fa fa-check"></i></button>
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


            $('#bag_no').on('keydown', function (e) {

                if (e.which == 13) {
                    e.preventDefault();

                    var bag_no = $('#bag_no').val();

                    if (bag_no == '') {
                        showAlert('Enter donor no to search', 'error');
                        return false;
                    }

                    searchPatient(bag_no);
                }
            });

            $('#search-bag-btn').on('click', function (e) {
                var bag_no = $('#bag_no').val();
                if (bag_no == '') {
                    showAlert('Enter donor no to search', 'error');
                    return false;
                }
                searchPatient(bag_no);
            });


            function searchPatient(val) {
                var text = $('#bag_no').val() || '';
                if (text !== '') {
                    $.ajax({
                        url: baseUrl + "/bloodbank/component-separation/search",
                        type: "GET",
                        data: {
                            search_value: val,
                        },
                        dataType: "json",
                        success: function (response) {
                            var branch = $('#branch').val();
                            $('form#js-bag-generation-form')[0].reset();
                            if (response) {
                                // $('#search-patient').val(response.donor ? response.donor.donor_no)
                                $('#mobile').val(response.mobile);
                                $('#bag_no').val(response.bag_no);
                                $('#blood_group').val(response.blood_group);
                                $('#branch').val(response.branch_id);
                                $('#rh_type').val(response.rh_type);
                                $('#bag_date').val(response.bag_date)
                                $('#donation_type').val(response.donation_type);
                                $('#donor').val(response.donor.donor_no);
                                $('#bag_type').val(response.bag_id);
                                $('#tube_id').val(response.tube_id);
                                $('#collect_date').val(response.collect_date);
                                $('#start_time').val(response.start_time);
                                $('#end_time').val(response.end_time);
                                $('#consent_no').val(response.donor.consent.id);
                                $('#consent_date').val(response.donor.consent.form_date);
                            }
                        }
                    });
                }
            }
        });

        //multiple component

        $('.component-add-button').click(function() {
            var trTemplateData = $('#component-template').html();
            // var activeForm = $('div.tab-pane.fade.active.show');
            //
            $('.component-div').append(trTemplateData);
            // $.each($(activeForm).find('.js-multi-consultation-tbody tr select'), function(i, elem) {
            //     if (!$(elem).hasClass('select2-hidden-accessible'))
            //         $(elem).select2();
            // });
        });

        $(document).on('click', '.component-remove-btn', function() {
            console.log('working');
            var trCount = $(this).closest('.new-component').find('div').length;
            if (trCount > 1) {
                $(this).closest('div').remove();
            }
        });

    </script>
@endpush
