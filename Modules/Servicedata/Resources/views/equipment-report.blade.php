@extends('frontend.layouts.master') @section('content')
{{--<div class="iq-top-navbar second-nav">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button> -->
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-more-fill"></i></div>
                        <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                    </div>
                </div>
                <div class="navbar-collapse">
                    --}}{{--navbar--}}{{--
                    @include('menu::common.nav-bar')
                    --}}{{--end navbar--}}{{--
                </div>
            </nav>
        </div>
    </div>--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Equipment
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="equipment-form" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
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
                                <div class="col-lg-5 col-md-6">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Equipment:</label>
                                        <div class="col-sm-9">
                                            <select name="equipments" id="equipments" class="form-control form-control-sm">
                                                <option value="%">%</option>
                                                @if(isset($equipments) and count($equipments) > 0)
                                                @foreach($equipments as $d)
                                                <option value="{{$d->flditemname}}">{{$d->flditemname}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="start_stop_date" class="custom-control-input" id="start_date" value="start_date" checked>
                                            <label class="custom-control-label" for="customRadio6">Start Date </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="start_stop_date" value="stop_date" class="custom-control-input" id="stop_date">
                                            <label class="custom-control-label" for="customRadio7">Stop Date</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group form-row align-items-center mt-3">
                                    </div>
                                    <a href="#" class="btn btn-primary rounded-pill" type="button" onclick="searchData()"> <i class="fa fa-search"></i>&nbsp;Search</a>&nbsp;
                                    <a href="#" class="btn btn-warning rounded-pill" type="button" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Gridview
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="tittle-th">Index</th>
                                        <th class="tittle-th">EncID</th>
                                        <th class="tittle-th">Name</th>
                                        <th class="tittle-th">Gender</th>
                                        <th class="tittle-th">Start</th>
                                        <th class="tittle-th">Stop</th>
                                    </tr>
                                </thead>
                                <tbody id="equipment_data"></tbody>
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
        });

        function searchData(page) {
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            let pageData = page === 1 ?$('#equipment_data').serialize():$('#equipment_data').serialize() + '&page=' + page;
            $.ajax({
                url: '{{ route('display.consultation.equipment.report.search.list') }}',
                type: "post", // showAlert('exportdata');
                data: pageData,
                success: function (response) {
                    // console.log(response);
                    $('#equipment_data').html(response);
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
            window.open("{{ route('equipment.generatepdf') }}?" + $('#equipment-form').serialize(), '_blank');
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

    </script>

    @endpush
