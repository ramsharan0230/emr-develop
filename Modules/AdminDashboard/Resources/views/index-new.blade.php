@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        .iq-card {
            /*background-color: rgba(255, 255, 255, 0.1);*/
        }

    </style>

    <style>
        #chartdiv {
            width: 100%;
            height: 200px;
        }

    </style>

    <link rel="stylesheet" href="{{ mix('new/css/dashboard.css') }}" />
    <script src="{{ mix('js/app.js') }}" ></script>
@endpush

@section('content')
    <div class="container-fluid" id="app">
        <div class="row">
            <div class="col-lg-12 ">
                <fiscal-year-dashboard />
            </div>

            @if ($patient_info_permission)
                <div class="col-md-6 col-lg-4">
                    <in-out-emergency />
                </div>

                <div class="col-md-6 col-lg-4 ">
                    <div class="top-cards">
                        <new-old-follow-up />
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 ">
                    <div class="top-cards">
                        <online-walking />
                    </div>
                </div>
            @endif

            @if ($operation_theater_permission)
                <div class="col-md-6 col-lg-4 mt-4">

                    <div class="current-wrapper">
                        <div class='current-box'>

                            <p class="card-title">Operation Theatre Count</p>
                            <ot-count />

                        </div>
                    </div>
                </div>
            @endif

            @if ($delivery_permission)
                <div class="col-md-6 col-lg-4 mt-4">

                    <div class="current-wrapper">
                        <div class='current-box'>

                            <p class="card-title">Delivery Count</p>
                            <delivery-count />

                        </div>
                    </div>
                </div>
            @endif

            @if ($pharmacy_permission)
                <div class="col-md-6 col-lg-4 mt-4">

                    <div class="current-wrapper">

                        <div class='current-box'>

                            <p class="card-title"> Pharmacy Patient Count</p>
                            <pharmacy-count />
                        </div>
                    </div>
                </div>
            @endif

            @if ($current_inpatient_permission)
                <div class="col-md-6 col-lg-4 mt-4">

                    <div class="current-wrapper">
                        <div class='current-box'>
                            <p class="card-title"> Current Inpatient Details</p>
                            <current-inpatient />
                        </div>

                        <div class='current-box mt-3'>
                            <div class="death-detail ">
                                <div class="mt-2 text-center">
                                    <death />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($lab_permission)
                <div class="col-md-6 col-lg-4  mt-4">
                    <div class=" top-cards">
                        <div class="card-title">Lab Details</div>
                        <lab-details />
                    </div>
                </div>
            @endif

            @if ($radio_permission)
                <div class="col-md-6 col-lg-4 mt-4">
                    <div class=" top-cards">
                        <div class="card-title">Radio Details</div>
                        <radio-details />
                    </div>
                </div>
            @endif

            @if ($province_wise_permission)
                @include("admindashboard::map")

                <div class="col-md-4 col-lg-4 mt-4">
                    <div class="top-cards">
                        <p class="card-title">Age Wise Details</p>
                        <age-wise-details />
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="top-cards">
                        <revenue-details :billings="{{ json_encode($billingSet) }}"
                            :departments="{{ json_encode($departments) }}" />
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 mt-4">
                    <div class="top-cards">
                        <p class="card-title">Doctor Revenue</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Revenue Type</label>
                                    <div>
                                        <select id="select-doc-revenue-type" class="form-control">
                                            <option value="" selected disabled>All</option>
                                            @if ($categories)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category }}">{{ $category }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Doctor</label>
                                    <div>
                                        <select id="select-doc-revenue-doc" class="form-control">
                                            <option value="" selected disabled>All</option>
                                            @if ($doctors)
                                                @if (Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0)
                                                    @foreach ($doctors as $doctor)
                                                        <option value="{{ $doctor->fldtitlefullname }}">
                                                            {{ $doctor->fldtitlefullname }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($doctors as $doctor)
                                                        @if (Auth::guard('admin_frontend')->user()->id == $doctor->id)
                                                            <option value="{{ $doctor->fldtitlefullname }}">
                                                                {{ $doctor->fldtitlefullname }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>From</label>
                                <input type="date" id="doc-revenue-from-date" name="from"
                                    class="form-control form-control-sm float-right">
                                <br /><br />
                                <label class="mt-2">To </label>
                                <input type="date" id="doc-revenue-to-date" name="to"
                                    class="form-control  form-control-sm float-right">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table mt-4">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Doctor</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-doc-revenue">
                                        @forelse ($doctor_shares['billing_share_reports'] as $share)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($share->firstname) . ' ' . ucfirst($share->middlename) . ' ' . ucfirst($share->lastname) }}
                                                </td>
                                                <td>{{ ucfirst($share->type) }}</td>
                                                <td>Rs. {{ $share->total_sum }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            @if ($radio_permission)
                <div class="col-md-6 col-lg-6 mt-4">
                    <div class="top-cards">
                        <p class="card-title">Radiology Reports</p>
                        <radiology-reports />
                    </div>
                </div>
            @endif

            @if ($lab_permission)
                <div class="col-md-12 mt-4">
                    <div class="top-cards">
                        <p class="card-title">Lab Reports</p>
                        <lab-report />
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        var maphovercolor = "#2675bf";
        var mapcolor = "#a3a3a0";
        
        @if ($province_wise_permission)
        
            function clearProvinceData() {
            $('#totalPatients').html("0");
            $('#totalMale').html("0");
            $('#totalFemale').html("0");
            $('#totalOther').html("0");
            }
        
            $(document).on('mouseenter', '#path27', function () {
            sudurpaschim();
            $('#path25').css("fill", maphovercolor);
            $('#path27').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#path25', function () {
            sudurpaschim();
            $('#path27').css("fill", maphovercolor);
            $('#path25').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text76', function () {
            sudurpaschim();
            $('#path25').css("fill", maphovercolor);
            $('#path27').css("fill", maphovercolor);
            });
        
            function sudurpaschim() {
            $('.svg-details').show();
            $('#provinceName').html("Sudurpaschim");
            @if (array_key_exists('Sudurpashchim', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Sudurpashchim']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Sudurpashchim']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Sudurpashchim']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Sudurpashchim']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path25', function () {
            $('.svg-details').hide();
            $('#path25').css("fill", "#a3a3a0");
            $('#path27').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseleave', '#path27', function () {
            $('.svg-details').hide();
            $('#path25').css("fill", "#a3a3a0");
            $('#path27').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseenter', '#path31', function () {
            karnali();
            $('#path31').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text114', function () {
            karnali();
            $('#path31').css("fill", maphovercolor);
            });
        
            function karnali() {
            $('.svg-details').show();
            $('#provinceName').html("Karnali");
            @if (array_key_exists('Karnali', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Karnali']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Karnali']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Karnali']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Karnali']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path31', function () {
            $('.svg-details').hide();
            $('#path31').css("fill", "#a3a3a0");
            });
        
        
            $(document).on('mouseenter', '#path26', function () {
            lumbini();
            $('#path26').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text108', function () {
            lumbini();
            $('#path26').css("fill", maphovercolor);
            });
        
            function lumbini() {
            $('.svg-details').show();
            $('#provinceName').html("Lumbini");
            @if (array_key_exists('Province No. 5', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Province No. 5']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Province No. 5']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Province No. 5']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Province No. 5']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path26', function () {
            $('.svg-details').hide();
            $('#path26').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseenter', '#path28', function () {
            gandaki();
            $('#path28').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text100', function () {
            gandaki();
            $('#path28').css("fill", maphovercolor);
            });
        
            function gandaki() {
            $('.svg-details').show();
            $('#provinceName').html("Gandaki");
            @if (array_key_exists('Gandaki', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Gandaki']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Gandaki']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Gandaki']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Gandaki']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path28', function () {
            $('.svg-details').hide();
            $('#path28').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseenter', '#path7', function () {
            bagmati();
            $('#path7').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text94', function () {
            bagmati();
            $('#path7').css("fill", maphovercolor);
            });
        
            function bagmati() {
            $('.svg-details').show();
            $('#provinceName').html("Bagmati");
            @if (array_key_exists('Bagmati', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Bagmati']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Bagmati']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Bagmati']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Bagmati']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path7', function () {
            $('.svg-details').hide();
            $('#path7').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseenter', '#path10', function () {
            province2();
            $('#path10').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text88', function () {
            province2();
            $('#path10').css("fill", maphovercolor);
            });
        
            function province2() {
            $('.svg-details').show();
            $('#provinceName').html("Province No. 2");
            @if (array_key_exists('Province No. 2', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Province No. 2']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Province No. 2']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Province No. 2']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Province No. 2']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path10', function () {
            $('.svg-details').hide();
            $('#path10').css("fill", "#a3a3a0");
            });
        
            $(document).on('mouseenter', '#path15', function () {
            province1();
            $('#path15').css("fill", maphovercolor);
            });
        
            $(document).on('mouseenter', '#text82', function () {
            province1();
            $('#path15').css("fill", maphovercolor);
            });
        
            function province1() {
            $('.svg-details').show();
            $('#provinceName').html("Province No. 1");
            @if (array_key_exists('Province No. 1', $ProvincesPatient))
                $('#totalPatients').html("{{ $ProvincesPatient['Province No. 1']['Total'] }}");
                $('#totalMale').html("{{ $ProvincesPatient['Province No. 1']['Male'] }}");
                $('#totalFemale').html("{{ $ProvincesPatient['Province No. 1']['Female'] }}");
                $('#totalOther').html("{{ $ProvincesPatient['Province No. 1']['Other'] }}");
            @else
                clearProvinceData();
            @endif
            }
        
            $(document).on('mouseleave', '#path15', function () {
            $('.svg-details').hide();
            $('#path15').css("fill", "#a3a3a0");
            });
        
        @endif

        $(document).ready(function() {
            
            $("#select-doc-revenue-doc").change(function(e) {
                filterDocRevenue();
            });

            $("#select-doc-revenue-type").change(function(e) {
                filterDocRevenue();
            });

            $("#doc-revenue-from-date").change(function(e) {
                filterDocRevenue();
            });

            $("#doc-revenue-to-date").change(function(e) {
                filterDocRevenue();
            });
        });

        function filterDocRevenue() {
            let doc = $("#select-doc-revenue-doc").val();
            let type = $("#select-doc-revenue-type").val();
            let from_date = $("#doc-revenue-from-date").val();
            let to_date = $("#doc-revenue-to-date").val();

            let url = "{{ route('admin.dashboard.doctorshare-filter') }}";

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                data: {
                    docname: doc,
                    type: type,
                    from_date: from_date,
                    to_date: to_date
                },
                async: true,
                success: function(response) {
                    let tr = "<tr><td>No item to show</td></tr>";
                    if ((response.billing_share_reports).length > 0) {
                        tr = "";
                        $.each(response.billing_share_reports, function(i, v) {
                            let middlename = (v.middlename != null) ? v.middlename : '';
                            tr += '<tr>\
                                    <td>' + (++i) + '</td>\
                                    <td>' + v.firstname + ' ' + middlename + ' ' + v.lastname + '</td>\
                                    <td>' + v.type + '</td>\
                                    <td>' + (v.total_sum).toFixed(3) + '</td>\
                                    </tr>';
                        });
                    }
                    $("#tbody-doc-revenue").html(tr);
                }
            });
        }
    </script>

    <!-- For Firbese Notification Added by Anish-->
    <script src="{{ asset('js/firebase-app.js') }}"></script>
    <script src="{{ asset('js/firebase-messaging.js') }} "></script>
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyB82pY_36o79od2rjQrW0ZU_260QFXRbVI",
            authDomain: "laravelfcm-3a3e1.firebaseapp.com",
            projectId: "laravelfcm-3a3e1",
            storageBucket: "laravelfcm-3a3e1.appspot.com",
            messagingSenderId: "47776561861",
            appId: "1:47776561861:web:f6c565f7134f981e0eceac",
            measurementId: "G-L3VS8PC3PH"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        initFirebaseMessagingRegistration();
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional


        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    $.ajax({
                        url: '{{ route('notification.save.token') }}',
                        type: 'GET',
                        data: {
                            device_token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            if (response) {
                                alert(response);
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        },
                    });

                }).catch(function(err) {
                    console.log(err);
                });
        }

        messaging.onMessage(function(payload) {
            // console.log(payload)
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            var test = new Notification(noteTitle, noteOptions);
            test.onclick = function() {
                window.open(payload.notification.click_action);
                window.focus();
            };
        });
    </script>

@endpush
