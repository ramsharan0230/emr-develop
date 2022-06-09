@extends('frontend.layouts.master')
@section('content')

    {{--navbar--}}
    {{--@include('menu::common.nav-bar')--}}
    {{--end navbar--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Patient Report/IP Events
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="ip-events-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="from_date" autocomplete="off">
                                            <input type="hidden" name="from_date" id="from_date_eng">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="to_date" autocomplete="off">
                                            <input type="hidden" name="to_date" id="to_date_eng">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Depart:</label>
                                        <div class="col-sm-8">
                                            <select name="department" id="department" class="form-control form-control-sm">
                                                <option value="">%</option>
                                                @if(isset($department) and count($department) > 0)
                                                    @foreach($department as $d)
                                                        <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Status:</label>
                                        <div class="col-sm-8">
                                            <select name="last_status" id="last_status" class="form-control form-control-sm">
                                                {{-- <option value="%"></option> --}}
                                                <option value="Exits(All)">Exits(All)</option>
                                                <option value="Absconder">Absconder</option>
                                                <option value="Death">Death</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="LAMA">LAMA</option>
                                                <option value="Refer">Refer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Gender:</label>
                                        <div class="col-sm-8">
                                            <select name="gender" id="gender" class="form-control form-control-sm">
                                                <option value="">--Gender--</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input" id="agerange">
                                        <label for="" class="col-sm-4">Age:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="age_from" id="age_from" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="age_to" id="age_to" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 p-0">
                                    <div class="form-group form-row align-items-center mt-3">
                                    </div>
                                    <a href="#" class="btn btn-info rounded-pill" type="button" onclick="searchData()"> <i class="fa fa-search"></i>&nbsp;Search</a>
                                    <a href="#" class="btn btn-warning rounded-pill" type="button" onclick="ipEventsMenu.pdfGenerate()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
                                    <a href="{{route('month.wise.adminssion.discharge.report')}}" type="button" class="btn btn-light btn-action" target="_blank" >&nbsp;Month Wise Patient Report</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                <tr>
                                    <th>Index</th>
                                    <th>EncID</th>
                                    <th width="300">Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>DOA</th>
                                    <th>LastLocation</th>
                                    <th>AdmitLocation</th>
                                    <th>LastStatus</th>
                                    <th>Consultant</th>
                                </tr>
                                </thead>
                                <tbody id="ip-events-data"></tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    @include('frontend.common.pagination-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];
                searchData(page);
            });
        });

        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
            searchData();
        })

        function searchData(page) {
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            let pageData = page === 1 ?$('#ip-events-form').serialize():$('#ip-events-form').serialize() + '&page=' + page;
            $.ajax({
                url: '{{ route('display.consultation.ip.events.search.list') }}',
                type: "POST",
                data: pageData,
                success: function (response) {
                    // console.log(response);
                    $('#ip-events-data').empty();
                    $('#ip-events-data').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $('#type').on('change', function () {
            var type = $(this).val();
            if (type == 'Age') {
                $('#agerange').show();
                $('#normalinput').hide();

            } else {
                $('#agerange').hide();
                $('#normalinput').show();
            }
        });

        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });

        var ipEventsMenu = {
            pdfGenerate: function (typePdf) {
                $('#to_date_eng').val(BS2AD($('#to_date').val()));
                $('#from_date_eng').val(BS2AD($('#from_date').val()));
                var fromdate = $('#from_date_eng').val();
                var todate = $('#to_date_eng').val();
                var last_status = $('#last_status').val();
                var department = $('#department').val();
                var gender = $('#gender').val();
                var age_from = $('#age_from').val();
                var age_to = $('#age_to').val();
                var dataGet = "typePdf=pdf&from_date=" + fromdate + "&to_date=" + todate + "&last_status=" + last_status + "&department=" + department + "&gender=" + gender + "&age_from=" + age_from + "&age_to=" + age_to + "&_token=" + "{{ csrf_token() }}";
                window.open("{{ route('display.consultation.ip.events.search.list') }}?" + dataGet, '_blank');
                // window.open("{{ route('consultation.ip.events.report.visit.pdf') }}?" + dataGet, '_blank');
                /*$.ajax({
                    url: '{{ route('consultation.ip.events.report.visit.pdf') }}',
                    type: "POST",
                    data: {typePdf: typePdf, from_date: fromdate, to_date: todate},
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response, status, xhr) {
                        // console.log(response);
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        var linkelem = document.createElement('a');
                        try {
                            var blob = new Blob([response], {type: 'application/octet-stream'});

                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);

                                if (filename) {
                                    // use HTML5 a[download] attribute to specify filename
                                    var a = document.createElement("a");

                                    // safari doesn't support this yet
                                    if (typeof a.download === 'undefined') {
                                        window.location = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.target = "_blank";
                                        a.click();
                                    }
                                } else {
                                    window.location = downloadUrl;
                                }
                            }

                        } catch (ex) {
                            console.log(ex);
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });*/
            }
        }
    </script>

@endpush
