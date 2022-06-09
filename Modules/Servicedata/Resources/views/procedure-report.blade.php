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
                    @include('menu::common.nav-bar')
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
                                Procedure report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="procedure-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Form:</label>
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
                                        <label for="" class="col-sm-4">Procedure:</label>
                                        <div class="col-sm-8">
                                            <select name="procedure" id="procedure" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($procedure) and count($procedure) > 0)
                                                    @foreach($procedure as $d)
                                                        <option value="{{$d->flditem}}">{{$d->flditem}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Status:</label>
                                        <div class="col-sm-8">
                                            <select name="last_status" id="last_status" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Done">Done</option>
                                                <option value="Minor">Minor</option>
                                                <option value="On Hold">On Hold</option>
                                                <option value="Planned">Planned</option>
                                                <option value="Referred">Referred</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Gender:</label>
                                        <div class="col-sm-8">
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="">--Gender--</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Age:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="age_from" id="age_from" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="age_to" id="age_to" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group form-row align-items-center mt-3">
                                    </div>
                                    <a href="javascript:;" class="btn btn-primary rounded-pill" type="button" onclick="searchData()"> <i class="fa fa-search"></i>&nbsp;Search</a>&nbsp;
                                    <a href="javascript:;" class="btn btn-warning rounded-pill" type="button" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
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
                                    <th class="tittle-th">Index</th>
                                    <th class="tittle-th">EncID</th>
                                    <th class="tittle-th">Name</th>
                                    <th class="tittle-th">Age</th>
                                    <th class="tittle-th">Gender</th>
                                    <th class="tittle-th">DateTime</th>
                                    <th class="tittle-th">Procedure</th>
                                    <th class="tittle-th">Status</th>
                                    <th class="tittle-th">Summary</th>
                                </tr>
                                </thead>
                                <tbody id="procedure-data"></tbody>
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
            let pageData = page === 1 ?$('#procedure-form').serialize():$('#procedure-form').serialize() + '&page=' + page;
            $.ajax({
                url: '{{ route('display.consultation.procedure.report.search.list') }}',
                type: "POST",
                data: pageData,
                success: function (response) {
                    // console.log(response);
                    $('#procedure-data').empty();
                    $('#procedure-data').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportData() {

            var urlReport = "{{ route('display.consultation.procedure.search.generate.print') }}" + "?" + $('#procedure-form').serialize() + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport, '_blank');
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
