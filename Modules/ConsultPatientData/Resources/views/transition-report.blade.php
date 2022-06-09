@extends('frontend.layouts.master') @section('content')

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
                                Patient Report/Transition
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="transition-form">
                            <div class="row">
                                <div class="col-sm-4">
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
                                <div class="col-sm-5">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Depart:</label>
                                        <div class="col-sm-9">
                                            <select name="department" id="department" class="form-control form-control-sm">
                                                {{--<option value="%">%</option>--}}
                                                @if(isset($department) and count($department) > 0)
                                                    @foreach($department as $d)
                                                        <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="entry_exit_date" class="custom-control-input" id="entry_date" value="entry_date" checked>
                                            <label class="custom-control-label" for="customRadio6">Entry Date </label>
                                        </div>&nbsp;
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="entry_exit_date" value="exit_date" class="custom-control-input" id="exit_date">
                                            <label class="custom-control-label" for="customRadio7">Exit Date</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 p-0">
                                    <div class="form-group form-row align-items-center mt-3">
                                    </div>
                                    <a href="#" class="btn btn-info rounded-pill" type="button" onclick="searchData()"> <i class="fa fa-search"></i>&nbsp;Search</a>
                                    <a href="#" class="btn btn-warning rounded-pill" type="button" onclick="exportData()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
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
                                    <th width="200">Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>DOA</th>
                                    <th>BedNo</th>
                                    <th>Trans IN</th>
                                    <th>Trans Out</th>
                                </tr>
                                </thead>
                                <tbody id="transition_data"></tbody>
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
    {{--    <script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>--}}
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
            let pageData = page === 1 ?$('#transition-form').serialize():$('#transition-form').serialize() + '&page=' + page;
            $.ajax({
                url: '{{ route('display.consultation.transition.search.list') }}',
                type: "post",
                data: pageData,
                success: function (response) {
                    // console.log(response);
                    // dispChart(response.chartData);

                    $('#transition_data').html(response.html);
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
            var entry_exit_date = $("radio[name='entry_exit_date']").val();
            var from_date = $('#from_date_eng').val();
            var to_date = $('#to_date_eng').val();
            var department = $('#department').val();

            var urlReport = "{{ route('display.consultation.transition.search.list') }}" + "?typePdf=pdf&from_date=" + from_date + "&to_date=" + to_date + "&entry_exit_date=" + entry_exit_date + "&department=" + department + "&_token=" + "{{ csrf_token() }}";
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
