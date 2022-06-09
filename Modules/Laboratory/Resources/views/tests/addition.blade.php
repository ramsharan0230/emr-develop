@extends('frontend.layouts.master')

@section('content')

@include('menu::common.laboratory-nav-bar')
<div class="container-fluid">
    <div class="row">

        <!--Here patient profile -->
        @include('frontend.common.patientProfile')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Test-Addition
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="dietarytable">
                                <select class="form-control" style="width: 100%;height: 342px;" id="js-addition-bill-tbody" multiple>
                                    @if(isset($billings))
                                    @foreach($billings as $bill)
                                    <option value="{{ $bill->fldid }}">{{ $bill->flditemname }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="dietarytable">
                                <button class="btn btn-primary" id="js-addition-bill-add-btn"><i class="fa fa-plus"></i>&nbsp;Add</button>&nbsp;
                                <button class="btn btn-danger" style="float: right;" id="js-addition-bill-delete-btn"><i class="fa fa-trash-alt"></i>&nbsp;Delete</button>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="res-table mt-2 table-sticky-th">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th><input type="checkbox" id="js-addition-select-all-checkbox"></th>
                                            <th>Test Name</th>
                                            <th>Method</th>
                                            <th>Sample Date</th>
                                            <th>Sample ID</th>
                                            <th>Specimen</th>
                                            <th>Vial</th>
                                            <th>Referal</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-addition-labtest-tbody">
                                        @if(isset($labtests))
                                        @foreach($labtests as $key => $test)
                                        <tr fldcomment="{{ $test->fldcomment }}" fldbillno="{{ $test->fldbillno }}" fldcondition="{{ $test->fldcondition }}">
                                            <td>{{ ($key+1) }}</td>
                                            <td><input type="checkbox" class="js-addition-labtest-checkbox" value="{{ $test->fldid }}"></td>
                                            <td>{{ $test->fldtestid }}</td>
                                            <td>{{ $test->fldmethod }}</td>
                                            <td>{{ ($test->fldtime_sample) ? Helpers::dateEngToNepdash(explode(' ', $test->fldtime_sample)[0])->full_date . ' ' . explode(' ', $test->fldtime_sample)[1] : '' }}</td>
                                            <td>{{ $test->fldsampleid }}</td>
                                            <td>{{ $test->fldsampletype }}</td>
                                            <td>{{ ($test->test) ? $test->test->fldvial : '' }}</td>
                                            <td>{{ $test->fldrefername }}</td>
                                            <td><button class="btn btn-danger btn-sm js-addition-test-delete-btn"><i class="fa fa-trash-alt"></i></button></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group er-input">
                                <label>Specimen:</label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="js-addition-specimen-input">
                                        <option value="">-- Select --</option>
                                        @foreach($specimens as $specimen)
                                        <option value="{{ $specimen->fldsampletype }}">{{ $specimen->fldsampletype }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 padding-none">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm-in js-addition-add-item"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:void(0);" id="js-addition-update-specimen" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i></a>
                                </div>
                            </div>
                            <div class="form-group er-input">
                                <label>Condition:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="js-addition-condition-input">
                                        <option value="">-- Select --</option>
                                        @foreach($conditions as $condition)
                                        <option value="{{ $condition->fldtestcondition }}">{{ $condition->fldtestcondition }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 padding-none">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm-in js-addition-add-item" data-variable="condition"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="form-group er-input">
                                <label>Date Time:</label>
                                <div class="col-sm-9">
                                    <input type="text" id="js-addition-date-input" readonly value="{{ $date }}" class="form-control nepaliDatePicker" style="width: 58%;float: left;">
                                    <input type="text" id="js-addition-time-input" readonly value="{{ $time }}" placeholder="HH:MM" class="form-control" style="width: 38%;">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group">
                                <textarea style="height: 70px;" id="js-addition-comment-textarea" class="form-control textarea-lab" placeholder="Comment"></textarea>
                                <div class="form-group er-input mt-2">
                                    <label class="col-3">Invoice:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="js-addition-invoice-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group er-input">
                                <label class="col-3">SampleID:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="js-addition-sampleid-input" value="{{ (isset($patient) && isset($next_sample_id)) ? $next_sample_id : '' }}">
                                </div>
                                @if((!empty(Options::get('sample_no_increment')) && Options::get('sample_no_increment') == 'Yes') || empty(Options::get('sample_no_increment')))
                                <div class="col-sm-2 padding-none">
                                    <a href="javascript:viod(0);" id="js-sampling-nextid" class="btn btn-primary btn-sm-in"><i class="ri-edit-fill"></i></a>
                                </div>
                                @endif
                            </div>
                            <div class="form-group er-input">
                                <label class="col-3">Referred:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="js-addition-referred-input">
                                        <option value="">-- Select user --</option>
                                        @foreach($refer_by as $user)
                                        <option value="{{ $user->flduserid }}">{{ $user->fldfullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="id-generate-worksheet" checked="">
                                    <label class="custom-control-label">W</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="id-generate-barcode">
                                    <label class="custom-control-label">B</label>
                                </div>
                                <button class="btn btn-primary btn-sm-in" id="js-addition-test-update-btn"><i class="ri-edit-fill"></i>&nbsp;Update</button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group er-input">
                                <label class="col-3">Diagnosis:</label>
                                <select class="form-control" multiple>
                                    @if(isset($enpatient) && $enpatient->PatFindings)
                                    @foreach($enpatient->PatFindings as $patfinding)
                                    <option>{{ $patfinding->fldcode }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('laboratory::layouts.variableModal')
@include('radiology::layouts.modal.test-group-modal')

@endsection
@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
<script type="text/javascript">
    var laboratoryTab = {
        LastEncounterLab : function () {
            $('.file-modal-title').text('Last Encounter Ids');
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-xl');
            $('#size').addClass('modal-dialog modal-sm');
            $.ajax({
                url: '{{ route('laboratory.addition.last.laboratory.encounter') }}',
                type: "POST",
                success: function (response) {
                    // console.log(response);

                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#file-modal').modal('show');
        }
    }
</script>

@endpush
