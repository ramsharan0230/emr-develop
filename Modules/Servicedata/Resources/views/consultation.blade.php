@extends('frontend.layouts.master') @section('content')
    <!-- <div class="iq-top-navbar second-nav">
        <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-more-fill"></i></div>
                        <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
    {{--navbar--}}
    {{--@include('menu::common.nav-bar')--}}
    {{--end navbar--}}<!--
                </div>
            </nav>
        </div>
    </div> -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Consult report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-4">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">From:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="from_date" autocomplete="off">
                                        <input type="hidden" name="from_date" id="from_date_eng">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">To:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="to_date" autocomplete="off">
                                        <input type="hidden" name="to_date" id="to_date_eng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">Depart:</label>
                                    <div class="col-sm-9">
                                        <select name="department" id="department" class="form-control">
                                            <option value="%">%</option>
                                            @if(isset($department) and count($department) > 0)
                                                @foreach($department as $d)
                                                    <option data-fldid="{{$d->fldid}}" value="{{$d->flddept}}">{{$d->flddept}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">Consult:</label>
                                    <div class="col-sm-9">
                                        <select name="consult" id="consult" class="form-control select2">
                                            <option value="%">%</option>
                                            @foreach ($consultants as $consultant)
                                                <option value="{{$consultant->username}}">{{$consultant->getFldtitlefullnameAttribute()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5">Gender:</label>
                                    <div class="col-sm-7 padding-none">
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="%">%</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Others">Other</option>
                                        </select>
                                    </div>
                                </div>
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
                            </div>
                            <div class="col-sm-5">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group form-row align-items-center er-input">
                                            <div class=" col-lg-12 col-sm-12">
                                                <select name="province" id="js-province" class="form-control select2 js-registration-province">
                                                    <option value="default">--Select Province--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center er-input">
                                            <select name="type" class="form-control" id="type">
                                                <option value="">--Select--</option>
                                                <option value="Age">Age(in Years)</option>
                                                <option value="Discount Type">Discount Type</option>
                                                <option value="Ethnic Group">Ethnic Group</option>
                                                {{-- <option value="Surname">Surname</option>
                                                <option value="Visit Type">Visit Type</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group form-row align-items-center er-input">
                                            <select name="district" class="form-control select2" id="js-district">
                                                <option value="default">--Select District--</option>
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
                                        {{--since we dont need comp; hidden field created so the serch will not break--}}
                                        <input type="hidden" name="comp" id="comp" value="%">
                                        <div class="form-group form-row align-items-center er-input">
                                            {{--<label for="" class="col-sm-4">Comp:</label>
                                            <div class="col-sm-7">
                                                <select name="comp" id="comp" class="form-control">
                                                    <option value="%">%</option>
                                                    <option value="{{$comp}}">{{$comp}}</option>
                                                </select>
                                            </div>--}}
                                            <button class="btn btn-primary rounded-pill" type="button" onclick="searchData()"><i class="fa fa-search"></i>&nbsp;Search</button>&nbsp;
                                            <button class="btn btn-warning rounded-pill" type="button" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">

                        </div>
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
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>PatientNo</th>
                                    <th>ConsultDate</th>
                                    <th>Department</th>
                                    <th>Consultant</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="consultation_data"></tbody>
                            </table>
<!--                            <div id="bottom_anchor"></div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="image-modal">
        <div class="modal-dialog" id="size">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="image-modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="image-form-container">
                        <div class="image-form-data"></div>
                    </div>

                </div>
                <i class="glyphicon glyphicon-chevron-left"></i>
                <!-- Modal footer -->
                {{--<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>--}}

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
            var selectOption = $('<option>',{val:'',text:'--Select Province--'});
            $(provinceSelector).empty().append(selectOption.clone());
            $(districtSelector).empty().append(selectOption.clone());

            var elems = $.map(addresses, function(d) {
                if (d.fldprovince == provinceId)
                    districts = d.districts;

                return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId) });
            });

            $(provinceSelector).empty().append(selectOption.clone()).append(elems);
            var selectOption = $('<option>',{val:'',text:'--Select District--'});
            $(districtSelector).empty().append(selectOption.clone());

            var elems = $.map(initdistricts, function(d) {
                return $("<option value='" + d.flddistrict + "' data-fldprovince='" + d.fldprovince + "'>" + d.flddistrict + "</option>")
            });
            $(districtSelector).empty().append(selectOption.clone()).append(elems);
        }

        function getDistrict(id, districtId) {
            var selectOption = $('<option>',{val:'',text:'--Select District--'});
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
            //searchData();
        })

        $(document).ready(function()
        {
            $(document).on('click', '.pagination a',function(event)
            {
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page=$(this).attr('href').split('page=')[1];

                searchData(page);
            });

        });

        function searchData(page) {
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            var fromdate = $('#from_date_eng').val();
            var todate = $('#to_date_eng').val();
            var department = $('#department').val();
            var mode = $('#mode').val();
            var comp = $('#comp').val();
            var gender = $('#gender').val();
            var province = $('#js-province').val();
            var dist = $('#js-district').val();
            var type = $('#type').val();
            var freetext = $("select[name='freetext']").val();
            var age_from = $('#age_from').val();
            var age_to = $('#age_to').val();
            var consult = $('#consult').val();
            $.ajax({
                url: '{{ route('listconsultation') }}',
                type: "POST",
                data: {
                    date_from: fromdate,
                    date_to: todate,
                    department: department,
                    mode: mode,
                    gender: gender,
                    province: province,
                    district: dist,
                    type: type,
                    freetext: freetext,
                    age_from: age_from,
                    comp: comp,
                    age_to: age_to,
                    consult: consult,
                    page: page,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#consultation_data').html(response);
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
            var fromdate = $('#from_date_eng').val();
            var todate = $('#to_date_eng').val();
            var department = $('#department').val();
            var mode = $('#mode').val();
            var comp = $('#comp').val();
            var gender = $('#gender').val();
            var province = $('#js-province').val();
            var dist = $('#js-district').val();
            var type = $('#type').val();
            var freetext = $("select[name='freetext']").val();
            var age_from = $('#age_from').val();
            var age_to = $('#age_to').val();
            var consult = $('#consult').val();
            var dataGet = "typePdf=pdf&date_from=" + fromdate + "&date_to=" + todate + "&department=" + department + "&mode=" + mode + "&gender=" + gender + "&province=" + province + "&district=" + dist + "&type=" + type + "&freetext=" + freetext + "&age_from=" + age_from + "&comp=" + comp + "&age_to=" + age_to + "&consult=" + consult + "&_token=" + "{{ csrf_token() }}";
            window.open("{{ route('listconsultation') }}?" + dataGet, '_blank');
            // window.open("{{ route('consultation.generatepdf') }}?" + dataGet, '_blank');
        }

        $('#type').on('change', function () {
            var type = $(this).val();
            if (type === 'Age') {
                $('#agerange').show();
                $('#discountdiv').hide();
                $('#ethnicdiv').hide();
            } else if(type === 'Discount Type'){
                $('#agerange').hide();
                $('#discountdiv').show();
                $('#ethnicdiv').hide();
            } else if(type === 'Ethnic Group'){
                $('#agerange').hide();
                $('#discountdiv').hide();
                $('#ethnicdiv').show();
            } else {
                $('#agerange').hide();
                $('#discountdiv').hide();
                $('#ethnicdiv').hide();
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

        function displayPatientImage(val) {
            // alert(val);
            // $('#file-modal').modal('hide');
            $.ajax({
                url: '{{ route('display.patient.image') }}',
                type: "POST",
                data: {fldpatientval: val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.image-form-data').empty();
                    $('.image-modal-title').text('Patient Image');
                    $('#image-modal').modal('show');
                    $('.image-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        }


        function lastEncounter(val) {
            $.ajax({
                url: '{{ route('display.last.encounter') }}',
                type: "POST",
                data: {fldpatientval: val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.image-form-data').empty();
                    $('.image-modal-title').text('Last Encounter');
                    $('#image-modal').modal('show');
                    $('.image-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function lastAllEncounter(val) {
            $.ajax({
                url: '{{ route('display.all.encounter') }}',
                type: "POST",
                data: {fldpatientval: val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.image-form-data').empty();
                    $('.image-modal-title').text('All Encounter');
                    $('#image-modal').modal('show');
                    $('.image-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $('#submitconsultant_list').on('click', function () {
            var value = $("input[name=consultant]:checked").val()
            $('#consult').val(value);
            $('#consultant_list').modal('hide');
        });

        $(document).on('change','#department',function(){
            var deptid = $('option:selected', this).attr('data-fldid');
            $.ajax({
                url: '{{ route("consultation.getDeptWiseConsultant")}}',
                type: "GET",
                data: {
                    deptid: deptid
                },
                success: function (response) {
                    $('#consult').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });

    </script>

@endpush
