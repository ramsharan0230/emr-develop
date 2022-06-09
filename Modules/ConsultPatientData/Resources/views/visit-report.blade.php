@extends('frontend.layouts.master') @section('content')

    {{--navbar--}}
    {{--@include('menu::common.nav-bar')--}}
    {{--end navbar--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 leftdiv">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Patient Report/Visit Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="visit-report-form">
                            @csrf

                            <div class="row">
                                <div class="col-sm-4 col-lg-2">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4 col-lg-3">From:</label>
                                        <div class=" col-lg-9 col-sm-8">
                                            <input type="text" class="form-control" id="from_date" autocomplete="off">
                                            <input type="hidden" name="from_date" id="from_date_eng">

                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4  col-lg-3">To:</label>
                                        <div class=" col-lg-9 col-sm-8">
                                            <input type="text" class="form-control" id="to_date" autocomplete="off">
                                            <input type="hidden" name="to_date" id="to_date_eng">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-5">Last Status:</label>
                                        <div class="col-sm-7">
                                            <select name="last_status" id="last_status" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Absconder">Absconder</option>
                                                <option value="Admitted">Admitted</option>
                                                <option value="Death">Death</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="LAMA">LAMA</option>
                                                <option value="Recorded">Recorded</option>
                                                <option value="Refer">Refer</option>
                                                <option value="Registered">Registered</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-5">Depart:</label>
                                        <div class="col-sm-7">
                                            <select name="department" id="department" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($department) and count($department) > 0)
                                                    @foreach($department as $d)
                                                        <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Mode:</label>
                                        <div class="col-sm-8">
                                            <select name="mode" id="mode" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($mode) and count($mode) > 0)
                                                    @foreach($mode as $m)
                                                        <option value="{{$m->fldsetname}}">{{$m->fldsetname}}</option>
                                                    @endforeach

                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                    @php
                                        $hospital_department = Helpers::getDepartmentAndComp();
                                    @endphp
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Comp:</label>
                                        <div class="col-sm-8">
                                            <select name="comp" id="comp" class="form-control">
                                                <option value="%">%</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-row align-items-center er-input">
                                                <div class=" col-lg-12 col-sm-12">
                                                    <select name="province" id="js-province" class="form-control select2 js-registration-province">
                                                        <option value="default">--Select Province--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center er-input">
                                                <label for="" class="col-sm-4 col-lg-4">Gender:</label>
                                                <div class=" col-lg-8 col-sm-8">
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Others">Other</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row align-items-center er-input">
                                                <div class=" col-lg-12 col-sm-12">
                                                    <select name="district" class="form-control select2" id="js-district">
                                                        <option value="default">--Select District--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group  er-input">
                                                <select name="type" class="form-control" id="checkType">
                                                    <option value=""></option>
                                                    <option value="Age">Age(in Years)</option>
                                                    <option value="Discount Type">Discount Type</option>
                                                    <option value="Ethnic Group">Ethnic Group</option>
                                                </select>
                                            </div>
                                            <div class="form-group form-row align-items-center er-input">
                                                <div class="col-sm-12" id="discountdiv" style="display: none;">
                                                    <select name="freetext" class="form-control js-discount select2">
                                                        <option value="">--Select Discount--</option>
                                                        @foreach($discounts as $discount)
                                                            <option value="{{ $discount->fldtype }}" data-fldmode="{{ $discount->fldmode }}" data-fldpercent="{{ $discount->fldpercent }}" data-fldamount="{{ $discount->fldamount }}">{{ $discount->fldtype }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-12" id="ethnicdiv" style="display: none;">
                                                    <select name="freetext" class="form-control select2 js-ethnic-group">
                                                        <option value="">--Select Enthnic Group--</option>
                                                        <option value="1 - Dalit">1 - Dalit</option>
                                                        <option value="2 - Janajati">2 - Janajati</option>
                                                        <option value="3 - Madhesi">3 - Madhesi</option>
                                                        <option value="4 - Muslim">4 - Muslim</option>
                                                        <option value="5 - Brahman/Chhetri">5 - Brahman/Chhetri</option>
                                                        <option value="6 - Others">6 - Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12" id="agerange" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="number" name="age_from" id="age_from" class="form-control" placeholder="AgeFrom">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" name="age_to" id="age_to" class="form-control" placeholder="AgeTo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-5 col-lg-4">Encounter ID:</label>
                                        <div class=" col-lg-6 col-sm-6">
                                            <input type="text" name="encounter_id" class="form-control" id="encounter_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <button class="btn btn-info rounded-pill" type="button" onclick="searchData()"><i class="fa fa-search"></i>&nbsp;Search</button>&nbsp;
                                <button class="btn btn-warning rounded-pill" type="button" onclick="visitDataMenu.pdfGenerate()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Pdf</button>&nbsp;
                                <button class="btn btn-danger rounded-pill" type="button" onclick="exportVisitReportExcel()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-bordered table-contant">
                                <thead class="thead-light">
                                <tr>
                                    <th>Index</th>
                                    <th>EncID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th width="100">DORec</th>
                                    <th width=100>DOAdmission</th>
                                    <th width="100">DODischarge</th>
                                    <th>No. of stay days</th>
                                    <th>Status</th>
                                    <th width=200>Consult</th>
                                </tr>
                                </thead>
                                <tbody id="visit_report_data"></tbody>
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
        var provinceSelector = '#js-province';
        var districtSelector = '#js-district';
        var districts = null;
        var addresses = JSON.parse('{!! $addresses !!}');
        var initdistricts = JSON.parse('{!! $districts !!}');

        getProvinces();

        function getProvinces(provinceId = null) {
            var selectOption = $('<option>', {val: '', text: '--Select Province--'});
            $(provinceSelector).empty().append(selectOption.clone());
            $(districtSelector).empty().append(selectOption.clone());

            var elems = $.map(addresses, function (d) {
                if (d.fldprovince == provinceId)
                    districts = d.districts;

                return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId)});
            });

            $(provinceSelector).empty().append(selectOption.clone()).append(elems);
            var selectOption = $('<option>', {val: '', text: '--Select District--'});
            $(districtSelector).empty().append(selectOption.clone());

            var elems = $.map(initdistricts, function (d) {
                return $("<option value='" + d.flddistrict + "' data-fldprovince='" + d.fldprovince + "'>" + d.flddistrict + "</option>")
            });
            $(districtSelector).empty().append(selectOption.clone()).append(elems);
        }

        function getDistrict(id, districtId) {
            var selectOption = $('<option>', {val: '', text: '--Select District--'});
            if (id === "" || id === null) {
                $(districtSelector).empty().append(selectOption.clone());
            } else {
                $.map(addresses, function (d) {
                    if (d.fldprovince == id) {
                        districts = d.districts;
                        return false;
                    }
                });
                districts = Object.keys(districts).sort().reduce(
                    (obj, key) => {
                        obj[key] = districts[key];
                        return obj;
                    },
                    {}
                );
                var elems = $.map(districts, function (d) {
                    return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId)});
                });

                $(districtSelector).empty().append(selectOption.clone()).append(elems);
            }
        }

        function setProvince(id, municipalityId) {
            if (id === "" || id === null) {
            } else {
                var dataFldprovince = $(districtSelector).find('option:selected').data('fldprovince');
                if (dataFldprovince) {
                    $(provinceSelector).val(dataFldprovince);
                    $('#select2-js-province-container').attr('title', dataFldprovince);
                    $('#select2-js-province-container').text(dataFldprovince);

                    $.map(addresses, function (d) {
                        if (d.fldprovince.toLowerCase() == dataFldprovince.toLowerCase()) {
                            districts = d.districts;
                            return false;
                        }
                    });
                }

                if (districts == null) {
                    districts = initdistricts;
                    var valueId = ucwords(id);
                    $(districtSelector).find('option[value="' + valueId + '"]').prop('selected', true);
                    $(districtSelector).trigger('change');
                    return false;
                }
            }
        }

        $('#js-province').change(function () {
            getDistrict($(this).val(), null);
        });

        $('#js-district').change(function () {
            setProvince($(this).val(), null);
        });

        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
            searchData();
        })

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

        function searchData(page) {
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            let pageData = page === 1 ?$('#visit-report-form').serialize():$('#visit-report-form').serialize() + '&page=' + page;
            $.ajax({
                url: '{{ route('display.consultation.view.report.search') }}',
                type: "get",
                data: pageData,
                success: function (response) {
                    $('#visit_report_data').empty().append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportData() {
            showAlert('exportdata');
        }

        $('#checkType').on('change', function () {
            var type = $(this).val();
            if (type === 'Age') {
                $('#agerange').show();
                $('#discountdiv').hide();
                $('#ethnicdiv').hide();
            } else if (type === 'Discount Type') {
                $('#agerange').hide();
                $('#discountdiv').show();
                $('#ethnicdiv').hide();
            } else if (type === 'Ethnic Group') {
                $('#agerange').hide();
                $('#discountdiv').hide();
                $('#ethnicdiv').show();
            } else {
                $('#agerange').hide();
                $('#discountdiv').hide();
                $('#ethnicdiv').hide();
            }
        });


        var visitDataMenu = {
            pdfGenerate: function (typePdf) {
                $('#to_date_eng').val(BS2AD($('#to_date').val()));
                $('#from_date_eng').val(BS2AD($('#from_date').val()));
                var from_date = $('#from_date_eng').val();
                var to_date = $('#to_date_eng').val();

                var encounter_id = $('#encounter_id').val();
                var comp = $('#comp').val();
                var department = $('#department').val();
                var province = $('#js-province').val();
                var district = $('#js-district').val();
                var freetext = $("select[name='freetext']").val();
                var gender = $('#gender').val();
                var last_status = $('#last_status').val();
                var mode = $('#mode').val();
                var type = $('#checkType').val();
                var age_from = $('#age_from').val();
                var age_to = $('#age_to').val();

                var urlReport = "{{ route('display.consultation.view.report.search') }}" + "?typePdf=pdf&from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&department=" + department + "&province=" + province + "&district=" + district + "&freetext=" + freetext + "&gender=" + gender + "&last_status=" + last_status + "&mode=" + mode + "&type=" + type + "&age_from=" + age_from + "&age_to=" + age_to + "&encounter_id=" + encounter_id + "&_token=" + "{{ csrf_token() }}";
                window.open(urlReport, '_blank');

                // var urlReport = "{{ route('consultation.gender.surname.district.report.visit.pdf') }}" + "?typePdf=" + typePdf + "&from_date=" + fromdate + "&to_date=" + todate + "&_token=" + "{{ csrf_token() }}";
                // window.open(urlReport, '_blank');


                /*$.ajax({
                    url: '{{ route('consultation.gender.surname.district.report.visit.pdf') }}',
                type: "POST",
                data: {typePdf: typePdf, from_date: fromdate, to_date: todate},
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response, status, xhr) {
                        // console.log(response);
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        console.log(xhr);
                    }
                });*/

            }
        }

        function exportVisitReportExcel(){
                $('#to_date_eng').val(BS2AD($('#to_date').val()));
                $('#from_date_eng').val(BS2AD($('#from_date').val()));
                var from_date = $('#from_date_eng').val();
                var to_date = $('#to_date_eng').val();

                var encounter_id = $('#encounter_id').val();
                var comp = $('#comp').val();
                var department = $('#department').val();
                var province = $('#js-province').val();
                var district = $('#js-district').val();
                var freetext = $("select[name='freetext']").val();
                var gender = $('#gender').val();
                var last_status = $('#last_status').val();
                var mode = $('#mode').val();
                var type = $('#checkType').val();
                var age_from = $('#age_from').val();
                var age_to = $('#age_to').val();

                var urlReport = "{{ route('display.consultation.view.report.excel') }}" + "?&from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&department=" + department + "&province=" + province + "&district=" + district + "&freetext=" + freetext + "&gender=" + gender + "&last_status=" + last_status + "&mode=" + mode + "&type=" + type + "&age_from=" + age_from + "&age_to=" + age_to + "&encounter_id=" + encounter_id + "&_token=" + "{{ csrf_token() }}";
                window.open(urlReport, '_blank');
    }

        $(window).ready(function () {
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 20 // Options | Number of years to show
            });
            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 20 // Options | Number of years to show
            });

        })

    </script>

@endpush
