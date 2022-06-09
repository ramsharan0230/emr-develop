@extends('frontend.layouts.master') @section('content')
<!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<i class="ri-menu-3-line"></i>
</button> -->
<!-- <div class="iq-menu-bt align-self-center">
<div class="wrapper-menu">
    <div class="main-circle"><i class="ri-more-fill"></i></div>
    <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
</div>
</div> -->
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
                            Confinement Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form action="{{ route('display.consultation.confinement.export') }}" id="confinement-form" method="post" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
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
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-6">Del Type:</label>
                                    <div class="col-sm-6">
                                        <select name="delevery" id="delevery" class="form-control form-control-sm">
                                            <option value="%">%</option>
                                            @if(isset($delivery) and count($delivery) > 0)
                                            @foreach($delivery as $de)
                                            <option value="{{$de->flditem}}">{{$de->flditem}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-6">Complications:</label>
                                    <div class="col-sm-6">
                                        <select name="complication" id="complication" class="form-control form-control-sm">
                                            <option value="">--Select--</option>
                                            @if(isset($complications) and count($complications) > 0)
                                            @foreach($complications as $com)
                                            <option value="{{$com->flditem}}">{{$com->flditem}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4">Province:</label>
                                    <div class="col-sm-8">
                                        <select name="province" id="js-province" class="form-control select2 js-registration-province">
                                            <option value="default">--Select Province--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4">District:</label>
                                    <div class="col-sm-8">
                                        <select name="district" class="form-control select2" id="js-district">
                                            <option value="default">--Select District--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5">Age (yr):</label>
                                    <div class="col-sm-4 padding-none">
                                        <input type="text" name="age_from" id="age_from" class="form-control form-control-sm" placeholder="from">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="age_to" id="age_to" class="form-control form-control-sm" placeholder="to">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5">Weight(g):</label>
                                    <div class="col-sm-4 padding-none">
                                        <input type="text" name="weight_from" id="weight_from" class="form-control form-control-sm" placeholder="from">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="weight_to" id="weight_to" class="form-control form-control-sm" placeholder="to">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4">Result:</label>
                                    <div class="col-sm-8">
                                        <select name="result" id="result" class="form-control form-control-sm">
                                            <option value="%">%</option>
                                            <option value="Fresh Still Birth">Fresh Still Birth</option>
                                            <option value="Live Baby">Live Baby</option>
                                            <option value="Macerated Still Birth">Macerated Still Birth</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="#" class="btn btn-info rounded-pill" type="button" onclick="searchData()"> <i class="fa fa-search"></i>&nbsp;Search</a>&nbsp;
                            <a href="#" class="btn btn-warning rounded-pill" type="button" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="res-table table-container">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">Index</th>
                                    <th class="tittle-th">EncID</th>
                                    <th class="tittle-th">DOReg</th>
                                    <th class="tittle-th">Mother</th>
                                    <th class="tittle-th">Address</th>
                                    <th class="tittle-th">Age</th>
                                    <th class="tittle-th">PatientNo</th>
                                    <th class="tittle-th">Guardian</th>
                                    <th class="tittle-th">DateTime</th>
                                    <th class="tittle-th">DelMode</th>
                                    <th class="tittle-th">Result</th>
                                    <th class="tittle-th">BloodLoss(ml)</th>
                                    <th class="tittle-th">Weight(g)</th>
                                    <th class="tittle-th">BabyNo</th>
                                    <th class="tittle-th">BabySex</th>
                                    <th class="tittle-th">Consultant</th>
                                    <th class="tittle-th">Nurse</th>
                                    <th class="tittle-th">Complication</th>
                                </tr>
                            </thead>
                            <tbody id="confinement_data"></tbody>
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
            $.map(addresses, function(d) {
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
            var elems = $.map(districts, function(d) {
                return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId) });
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

                $.map(addresses, function(d) {
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

    $('#js-province').change(function() {
        getDistrict($(this).val(), null);
    });

    $('#js-district').change(function() {
        setProvince($(this).val(), null);
    });

    $(window).ready(function () {
        $('#to_date').val(AD2BS('{{date('Y-m-d')}}'));
        $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
        searchData();
    })
    function searchData(page) {
        $('#to_date_eng').val(BS2AD($('#to_date').val()));
        $('#from_date_eng').val(BS2AD($('#from_date').val()));
        let pageData = page === 1 ?$('#confinement-form').serialize():$('#confinement-form').serialize() + '&page=' + page;
        $.ajax({
            url: '{{ route('display.consultation.confinement.report.search.list') }}',
            type: "POST",
            data: pageData,
            success: function (response) {
                console.log(response);
                $('#confinement_data').html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function exportData() {
        $('#to_date_eng').val(BS2AD($('#to_date').val()));
        $('#from_date_eng').val(BS2AD($('#from_date').val()));
        var delevery = $('#delevery').val();
        var district = $('#js-district').val();
        var province = $('#js-province').val();
        var to_date = $('#to_date_eng').val();
        var from_date = $('#from_date_eng').val();
        var complication = $('#complication').val();
        var result = $('#result').val();
        var age_from = $('#age_from').val();
        var age_to = $('#age_to').val();
        var weight_from = $('#weight_from').val();
        var weight_to = $('#weight_to').val();
        var dataGet = "typePdf=pdf&from_date=" + from_date + "&to_date=" + to_date + "&delevery=" + delevery + "&district=" + district + "&complication=" + complication + "&province=" + province + "&result=" + result + "&age_from=" + age_from + "&age_to=" + age_to + "&weight_from=" + weight_from + "&weight_to=" + weight_to + "&_token=" + "{{ csrf_token() }}";
        window.open("{{ route('display.consultation.confinement.report.search.list') }}?" + dataGet, '_blank');
        showAlert('exportdata');
    }

    $('#checkType').on('change', function () {
        var type = $(this).val();
        if (type === 'Age') {
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

    </script>

    @endpush
