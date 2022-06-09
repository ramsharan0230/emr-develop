@extends('frontend.layouts.master')

@section('content')
    <style type="text/css">
        .fa-arrow-circle-right {
            font-size: 25px;
        }
        .modal-body p {
            margin-bottom: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Dispensing Remark Report</h4>
                            <input type="hidden" id="js-sampling-current-userid" value="{{ \Auth::guard('admin_frontend')->user()->username }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="js-xray-form">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="from_date" value="{{ $from_date }}" class="form-control form-control-sm nepaliDatePicker" id="from_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="to_date" value="{{ $to_date }}" class="form-control form-control-sm nepaliDatePicker" id="to_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-4">Encounter</label>
                                        <div class="col-lg-8 col-sm-8">
                                            <input type="text" value="{{ request()->get('encounter_id') }}" name="encounter_id" class="form-control" id="js-sampling-encounterid-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-3 col-sm-5">Name</label>
                                        <div class="col-lg-9 col-sm-7">
                                            <input type="text" value="{{ request()->get('name') }}" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-5">Phone</label>
                                        <div class="col-lg-8 col-sm-7">
                                            <input type="text" class="form-control" value="{{ request()->get('phone') }}" name="phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-5">Remark</label>
                                        <div class="col-lg-8 col-sm-7">
                                            <input type="text" class="form-control" value="{{ request()->get('remark') }}" name="remark">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-lg-3 col-sm-4">
                                            <button class="btn btn-primary" id="js-sampling-encounter-show-btn"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;View</button>&nbsp;
                                        </div>
                                        <div class="col-lg-3 col-sm-4">
                                            <button class="btn btn-primary" type="button" id="js-sampling-encounter-export-btn"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Export</button>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table table-sticky-th">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">SN</th>
                                    <th class="tittle-th">Encounter ID</th>
                                    <th class="tittle-th" width="250px">Patient Detail</th>
                                    <th class="tittle-th">Phone No.</th>
                                    <th class="tittle-th">Date</th>
                                    <th class="tittle-th">Remark</th>
                                </tr>
                                </thead>
                                <tbody id="js-sampling-labtest-tbody">
                                    @if(isset($remarks) && $remarks)
                                        @foreach($remarks as $remark)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $remark->fldencounterval }}</td>
                                                <td>
                                                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldfullname : '' }} <br>
                                                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldagestyle . ' years' : '' }}/{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptsex : '' }}
                                                </td>
                                                <td>
                                                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptcontact : '' }}
                                                </td>
                                                <td>{{ explode(' ', $remark->fldtime)[0] }}</td>
                                                <td>{!! $remark->fldremark !!}</td>
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
@endsection

@push('after-script')
<script>
    $(document).ready(function() {
        $('#js-sampling-encounter-export-btn').click(function() {
            var url = baseUrl + '/remarkreport/remarkreportCsv?' + $('#js-xray-form').serialize();
            window.open(url, '_blank');
        });
    });
</script>
@endpush
