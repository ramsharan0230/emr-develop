@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Doctor Fraction Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('pat-billing-share.index') }}" id="billing_filter_data" method="GET">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">From:</label>
                                        <div class="col-sm-9">
                                            <input type="text" autocomplete="off" class="form-control" name="from_date"
                                                   id="from_date" value=""/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" autocomplete="off" class="form-control" name="to_date"
                                                   id="to_date" value=""/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <select name="doctor_id" class="form-control select2" id="doctor_id">
                                            <option value="">-- Select Doctor --</option>
                                            @foreach ($consultants as $consultant)
                                                <option
                                                    {{ $consultant->id == request()->get('doctor_id') ? "selected" : '' }} value="{{$consultant->id}}">{{$consultant->fldtitlefullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="form-group form-row">
                                        <input type="text" name="doctor_name" class="form-control" placeholder="Doctor Name" id="" value="{{ request()->get('doctor_name') }}" />
                                    </div> --}}
                                    <div class="form-group form-row">
                                        <input type="text" name="bill_no" class="form-control" id=""
                                               value="{{ request()->get('bill_no') }}" placeholder="Bill Number"/>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4">
                                    {{-- <div class="form-group form-row">
                                        <input type="text" name="doctor_username" class="form-control" placeholder="Doctor Username" id="" value="{{ request()->get('doctor_username') }}" />
                                    </div> --}}
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="itemname" id="" class="select2 form-control">
                                                <option value="">--All Item Name--</option>
                                                @forelse ($itemnames as $itemname)
                                                    <option
                                                        value="{{ $itemname->flditemname }}" {{ (request()->get('itemname') == $itemname->flditemname)?'selected':'' }}>{{ $itemname->flditemname }}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <!--                                    <div class="form-group form-row">
                                                                            <div class="col-sm-12">
                                                                                <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Patient Name">
                                                                            </div>
                                                                        </div>-->
                                    {{-- <div class="form-group form-row">
                                        <input type="text" name="seach_name" class="form-control" id="" value="" />
                                    </div> --}}
                                    <div class="form-group ">
                                        <div class="dropdown" style="float: left; margin-left:2%">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <!-- <a class="dropdown-item" onclick="generatePdf()"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Export</a>
                                                <a class="dropdown-item" onclick="generateDoctorwisePdf()"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Doctor-wise Export With
                                                    Referral PDF</a>
                                                <a class="dropdown-item" onclick="generateDoctorwiseExcel()"><i
                                                        class="fas fa-file-excel"></i>&nbsp;Doctor-wise Patient Export
                                                    With Referral Excel </a>
                                                <a class="dropdown-item" onclick="generateDoctorwisePdfPatient()"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Doctor-wise Patient Export
                                                    With Referral </a> -->
                                                <a class="dropdown-item" onclick="generateDoctorwisePatientExcel()"><i class="fas fa-file-excel"></i>&nbsp;Doctor-wise
                                                    Patient Export Excel </a>
                                                <a class="dropdown-item"
                                                   onclick="generateDoctorwisePdfWithoutReferal()"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Doctor-wise Without Referral
                                                    PDF</a>
                                                <a class="dropdown-item" onclick="generateDoctorwiseExcelWithoutReferal()"><i class="fas fa-file-excel"></i>&nbsp;Doctor-wise
                                                    Without Referral Excel</a>
                                                <a class="dropdown-item"
                                                   onclick="generateDoctorwisePdfPatientWithoutReferal()"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Doctor Patient Without
                                                    Referral PDF</a>
                                                <a class="dropdown-item" onclick="generateDoctorwiseExcelPatientWithoutReferal()"><i class="fas fa-file-excel"></i>&nbsp;Doctor
                                                    Patient Without Referral Excel</a>
                                                <a class="dropdown-item" onclick="generateReferalDoctorListExcell()"><i
                                                        class="fas fa-file-excel"></i>&nbsp;All Referral | Doctor Excel</a>
                                                <a class="dropdown-item" onclick="generateDoctorwiseshareReport()"><i
                                                        class="fas fa-file-excel"></i>&nbsp;DoctorwiseshareReport</a>


                                                        <a class="dropdown-item" onclick="generateDoctorshareoneReport()"><i
                                                        class="fas fa-file-excel"></i>&nbsp;Doctor Report</a>
                                            </div>
                                        </div>
                                        <div class="dropdown" style="float: left;  margin-left:2%">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false" style="float: left; margin-left:-5%">
                                                Report
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" onclick="getReportDetail('IPD')"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;IPD Round Details</a>
                                                <a class="dropdown-item" onclick="getReportDetail('OPD')"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;OPD Consultation</a>
                                                <a class="dropdown-item" onclick="getReportDetail('payable')"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Payable</a>
                                                <a class="dropdown-item" onclick="getReportDetail('referable')"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;Referrable</a>
                                                <a class="dropdown-item" onclick="getReportDetail('OT')"><i
                                                        class="fas fa-file-pdf"></i>&nbsp;OT</a>

                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill" onclick=""><i
                                                class="fa fa-check"></i>&nbsp;
                                            Search
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group ">

                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab"
                                   aria-controls="home" aria-selected="true">Report</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel"
                                 aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table-long">
                                    <table class="table table-striped table-hover table-bordered doctor-fraction">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>S.N</th>
                                            <th>Doctor</th>
                                            <th>Bill Number</th>
                                            <th>Patient</th>
                                            <th>Item Name</th>
                                            <!-- <th>Entry User</th> -->
                                            <th>Billing Date</th>
                                            <th>Total Amount (Rs.)</th>
                                            <th>Hospital Share %</th>
                                            <th>Hospital Share Amount (Rs.)</th>
                                            <th>Share Type</th>
                                            <th>Doctor Share Rs.(%)</th>
                                            <th>Tax %</th>

                                            <th>Is Return</th>
                                        </tr>
                                        </thead>
                                        <tbody id="billing_result">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @if($billing_share_reports)
                                            @forelse($billing_share_reports as $k => $report)
                                                <tr>
                                                    <td>{{ $k + 1 }}</td>
                                                    <td>{{ $report->user ? ucfirst($report->user->firstname) . " " . ucfirst($report->user->middlename) . " " . ucfirst($report->user->lastname):"" }}</td>
                                                    <td>{{ $report->fldbillno }}</td>
                                                    <td>{{\App\Utils\Helpers::getPatientName($report->fldencounterval)}}</td>
                                                    <td>{{ $report->flditemname }}</td>

                                                    <td>{{ $report->created_at }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->fldditemamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->hospitalshare) }} %</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->hospitalshare * $report->fldditemamt/100) }}</td>
                                                    <td>{{ ucfirst($report->type) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->share) }}
                                                        ({{ \App\Utils\Helpers::numberFormat($report->usersharepercent) }}%)
                                                    </td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($report->user_tax_percent) }}%
                                                    </td>
                                                    @php
                                                        $tax_amt = ($report->tax_amt) ? $report->tax_amt : 0;
                                                        $payment = $report->share - $tax_amt;
                                                    @endphp

                                                    @php
                                                        $total += $payment;
                                                    @endphp
                                                    <td>{{($report->is_returned == 0) ? "No" : "Yes"}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="20" class="text-center">
                                                        <em>No data available in table ...</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="20">No data to show.</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div
                                style="display: flex; align-items: center; justify-content: space-between;padding: 12px;">
                                @if(is_countable($billing_share_reports) && count($billing_share_reports))
                                    <div id="bottom_anchor" style="display:inline-flex;">
                                        {{ $billing_share_reports->appends($_GET)->render() }}
                                    </div>
                                @endif
                                <!-- <div style="display:inline-flex;">
                                    <strong>Total: {{  \App\Utils\Helpers::numberFormat($total??0) }} NRS</strong>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('billing::modal.user-list')
@endsection
@push('after-script')
    <script src="{{ asset('js/search-ajax.js')}}"></script>
    <script>
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{request()->get('eng_to_date')??date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{request()->get('eng_from_date')??date('Y-m-d')}}'));
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
            $('#eng_from_date').val(BS2AD($('#from_date').val()));
        });
        $(function () {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });

            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_from_date').val(BS2AD($('#from_date').val()));
                }
            });
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_to_date').val(BS2AD($('#to_date').val()));
                }
            });

            $("#customSearch").searchAjax({
                url: '{!! route("usershare.filter") !!}',
                paginate: true,
                paginateId: "bottom_anchor", // anchor tag encapsulated div
                onResult: function (res) {
                    let tbody = $("#js-user-share-item-tbody");
                    let tr_data = "";
                    let sn = res.data.current_page * (res.data.per_page - 1);
                    $.each(res.data.data, function (i, v) {
                        tr_data += '<tr>\
                        <td>' + sn++ + '</td>\
                        <td>' + v.user.fldfullname + '</td>\
                        <td>' + v.flditemname + '</td>\
                        <td>' + numberFormatDisplay(v.flditemshare) + '</td>\
                        <td>' + numberFormatDisplay(v.flditemtax) + '</td>\
                        <td>' + v.category + '</td>\
                    </tr>';
                    });

                    tbody.html(tr_data);
                    $("#bottom_anchor").html(res.paginate_view);
                }
            });
        });

        function generatePdf() {
            window.open("{{ route('pat-billing-share.pdf') }}?" + $('#billing_filter_data').serialize(), '_blank');
        }

        function generateDoctorwisePdf() {
            var url = "{{route('pat-billing-share.checkUsername')}}";
            $.ajax({
                url: url,
                type: "GET",
                data: $("#billing_filter_data").serialize(),
                success: function (response) {
                    if (response.data.status) {
                        window.open("{{ route('doctorwiseShareReport') }}?" + $('#billing_filter_data').serialize(), '_blank');
                    } else {
                        showAlert("Invalid Doctor Username", 'Error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert("Invalid Doctor Username", 'Error');
                }
            });
        }

        function generateDoctorwisePdfPatient() {
            var url = "{{route('pat-billing-share.checkUsername')}}";
            $.ajax({
                url: url,
                type: "GET",
                data: $("#billing_filter_data").serialize(),
                success: function (response) {
                    if (response.data.status) {
                        window.open("{{ route('doctorwiseShareReportPatient') }}?" + $('#billing_filter_data').serialize(), '_blank');
                    } else {
                        showAlert("Invalid Doctor Username", 'Error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert("Invalid Doctor Username", 'Error');
                }
            });
        }

        function generateDoctorshareoneReport() {
            window.open("{{ route('pat-doctorreportshare') }}?" + $('#billing_filter_data').serialize(), '_blank');
        }

        function getReportDetail(options) {

            window.open("{{ route('pat-getReportDetail') }}?" +'type='+options +'&'+ $('#billing_filter_data').serialize(), '_blank');
        }





        function generateDoctorwisePdfWithoutReferal() {
            var url = "{{route('pat-billing-share.checkUsername')}}";
            $.ajax({
                url: url,
                type: "GET",
                data: $("#billing_filter_data").serialize(),
                success: function (response) {
                    if (response.data.status) {
                        window.open("{{ route('doctorwiseShareReport') }}?" + $('#billing_filter_data').serialize() + "&withoutReferral=true", '_blank');
                    } else {
                        showAlert("Invalid Doctor Username", 'Error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert("Invalid Doctor Username", 'Error');
                }
            });
        }

        function generateDoctorwisePdfPatientWithoutReferal() {
            var url = "{{route('pat-billing-share.checkUsername')}}";
            $.ajax({
                url: url,
                type: "GET",
                data: $("#billing_filter_data").serialize(),
                success: function (response) {
                    if (response.data.status) {
                        window.open("{{ route('doctorwiseShareReportPatient') }}?" + $('#billing_filter_data').serialize() + "&withoutReferral=true", '_blank');
                    } else {
                        showAlert("Invalid Doctor Username", 'Error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert("Invalid Doctor Username", 'Error');
                }
            });
        }

        function generateReferalDoctorListExcell() {

            window.open("{{ route('pat-billing-share.export.referral.doctor.list') }}?" + $('#billing_filter_data').serialize(), '_blank');
        }

        function generateDoctorwiseshareReport() {

            window.open("{{ route('pat-billing-share.doctorsummary') }}?" + $('#billing_filter_data').serialize(), '_blank');
            }

        function generateDoctorwiseExcel() {

            if ($('#doctor_id').val() == '' || $('#doctor_id').val() == null) {
                showAlert('Please select doctor', 'error');
                return false;
            }
            window.open("{{ route('pat-billing-share.export.referral.doctor.wise') }}?" + $('#billing_filter_data').serialize(), '_blank');
        }
        function generateDoctorwisePatientExcel() {

            if ($('#doctor_id').val() == '' || $('#doctor_id').val() == null) {
                showAlert('Please select doctor', 'error');
                return false;
            }
            window.open("{{ route('pat-billing-share.export.doctor.wise.patient') }}?" + $('#billing_filter_data').serialize(), '_blank');
        }

        function generateDoctorwiseExcelWithoutReferal() {

            if ($('#doctor_id').val() == '' || $('#doctor_id').val() == null) {
                showAlert('Please select doctor', 'error');
                return false;
            }
            window.open("{{ route('pat-billing-share.export.doctor.wise.without.referal') }}?" + $('#billing_filter_data').serialize()+ "&withoutReferral=true", '_blank');
        }

        function generateDoctorwiseExcelPatientWithoutReferal() {

            if ($('#doctor_id').val() == '' || $('#doctor_id').val() == null) {
                showAlert('Please select doctor', 'error');
                return false;
            }
            window.open("{{ route('pat-billing-share.export.doctor.wise.patient.without.referal') }}?" + $('#billing_filter_data').serialize()+ "&withoutReferral=true", '_blank');
        }


        function generatePatshareExcell() {
            window.open("{{ route('pat-billing-share.export.excel') }}", '_blank');

        }

        $('.doctor-fraction').DataTable({
            "pageLength": 25
        });
    </script>
@endpush
