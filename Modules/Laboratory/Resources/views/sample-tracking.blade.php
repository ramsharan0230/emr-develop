@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Sample Tracking
                            </h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Encounter/ SampleId</th>
                                <th>Name</th>
                                @if(count($testNames))
                                    @foreach($testNames as $test)
                                        <th>{{ $test->fldcategory }}</th>
                                    @endforeach
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @if($sampleId)
                                @foreach($sampleId as $sample)
                                    <tr>
                                        <td>{{ $sample->fldencounterval }} / {{ $sample->fldsampleid }}</td>
                                        <td>{{ $sample->patientEncounter && $sample->patientEncounter->patientInfo ? $sample->patientEncounter->patientInfo->fullname:"" }}</td>
                                        @if(count($testNames))
                                            @foreach($testNames as $test)
                                                <?php //dd($sample->tracking); ?>
                                                <td>
                                                    @if(count($sample->tracking))
                                                        @if($sample->tracking->where('test_category', $test->fldcategory)->where('sample_id', $sample->fldsampleid)->where('sample_in', 1)->first() &&$sample->tracking->where('test_category', $test->fldcategory)->where('sample_id', $sample->fldsampleid)->where('sample_out', 1)->first())
                                                            <label>
                                                                <input type="checkbox" name="check_in" checked disabled> Check In
                                                            </label>
                                                            <br>
                                                            <label>
                                                                <input type="hidden" id="id-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                                <input type="checkbox" name="check_out" checked disabled> Check Out
                                                            </label>
                                                            @elseif($sample->tracking->where('test_category', $test->fldcategory)->where('sample_id', $sample->fldsampleid)->where('sample_in', 1)->first())
                                                            <label>
                                                                <input type="checkbox" name="check_in" checked disabled> Check In
                                                            </label>
                                                            <br>
                                                            <label>
                                                                <input type="hidden" id="id-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                                <input type="checkbox" name="check_out" id="out-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}" onchange="updateTracking('{{ $sample->fldsampleid }}-{{ $test->fldcategory }}','{{ $test->fldcategory }}', '{{ $sample->fldsampleid }}', 'out')"> Check Out
                                                            </label>
                                                        @else
                                                            <label>
                                                                <input type="checkbox" name="check_in" id="in-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}" onchange="insertTracking('{{ $sample->fldsampleid }}-{{ $test->fldcategory }}', '{{ $test->fldcategory }}', '{{ $sample->fldsampleid }}', 'in')"> Check In
                                                            </label>
                                                            <br>
                                                            <label class="d-none" id="checkout-container-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                                <input type="hidden" id="id-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                                <input type="checkbox" name="check_out" id="out-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}" onchange="updateTracking('{{ $sample->fldsampleid }}-{{ $test->fldcategory }}','{{ $test->fldcategory }}', '{{ $sample->fldsampleid }}', 'out')"> Check Out
                                                            </label>
                                                        @endif
                                                    @else
                                                        <label>
                                                            <input type="checkbox" name="check_in" id="in-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}" onchange="insertTracking('{{ $sample->fldsampleid }}-{{ $test->fldcategory }}', '{{ $test->fldcategory }}', '{{ $sample->fldsampleid }}', 'in')"> Check In
                                                        </label>
                                                        <br>
                                                        <label class="d-none" id="checkout-container-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                            <input type="hidden" id="id-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}">
                                                            <input type="checkbox" name="check_out" id="out-{{ $sample->fldsampleid }}-{{ $test->fldcategory }}" onchange="updateTracking('{{ $sample->fldsampleid }}-{{ $test->fldcategory }}','{{ $test->fldcategory }}', '{{ $sample->fldsampleid }}', 'out')"> Check Out
                                                        </label>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
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
@endsection
@push('after-script')
    <script>
        function insertTracking(checkboxID, testCategory, sampleId, inout) {
            if ($('#in-' + checkboxID).is(":checked")) {
                $.ajax({
                    url: "{{ route('laboratory.tracking.create.update.sample.track.in') }}",
                    type: "POST",
                    data: {testCategory: testCategory, sampleId: sampleId, inout: inout},
                    success: function (data) {
                        $('#in-' + checkboxID).attr("disabled", true);
                        $("#id-" + checkboxID).val(data.tracking_id);
                        $("#checkout-container-" + checkboxID).removeClass('d-none');
                        $("#checkout-container-" + checkboxID).show();
                    }
                });
            }
        }

        function updateTracking(checkboxID, testCategory, sampleId, inout) {
            if ($('#out-' + checkboxID).is(":checked")) {
                $.ajax({
                    url: "{{ route('laboratory.tracking.create.update.sample.track.out') }}",
                    type: "POST",
                    data: {testCategory: testCategory, sampleId: sampleId, inout: inout, trackingId: $('#id-' + checkboxID).val()},
                    success: function (data) {
                        $('#out-' + checkboxID).attr("disabled", true);
                        $("#id-" + checkboxID).val(data.tracking_id);
                        $("#checkout-container-" + checkboxID).removeClass('d-none');
                        $("#checkout-container-" + checkboxID).show();
                    }
                });
            }
        }
    </script>
@endpush

