<div class="modal" id="js-prog-essential-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Essential Examination</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-row align-items-center">
                            <label class="col-md-2">Name</label>
                            <div class="col-md-10">
                                <input readonly="readonly" type="text" id="js-prog-name-input" class="form-control" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{(isset($patient->fldptnamefir) ? $patient->fldptnamefir :'')  }} {{ isset($patient->fldmidname) ? $patient->fldmidname :'' }}  {{ isset($patient->fldptnamelast ) ? $patient->fldptnamelast :'' }}@endif">
                            </div>
                        </div>
                    </div>
                <!-- <div class="col-md-3 form-checkbox">
                        <div class="form-group">
                            <input type="checkbox" class="checkbox">
                            <span class="checkbox-align">Display Keypad</span>
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="form-group form-row align-items-center">
                            <label class="col-md-3">Gender:</label>
                            <div class="col-md-9">
                                <input readonly="readonly" type="text" id="js-prog-gender-input" class="form-control" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="essen-table mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody id="js-prog-essential-tbody">
                                    {{-- <tr data-unit="mmHg">
                                            <td class="table2-td">Systolic BP</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="mmHg">
                                            <td class="table2-td">Diastolic BP</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="%">
                                            <td class="table2-td">O2 Saturation</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="/min">
                                            <td class="table2-td">Respiratory Rate</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="°C">
                                            <td class="table2-td-">Temperature(F)</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="bpm">
                                            <td class="table2-td">Fatal Heart Rate</td>
                                            <td class="table2-td"></td>
                                        </tr>
                                        <tr data-unit="bpm">
                                            <td class="table2-td">Pulse Rate</td>
                                            <td class="table2-td"></td>
                                        </tr> --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-sm-3">Chart:</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" value="7" min="1" max="100" />
                            </div>
                            <label for="" class="col-sm-2">Day</label>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="generate-line-chart-essence-exam"><i class="ri-refresh-line"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 ">
                        <div class="row">
                            <div class="col-md-9">
                                <span id="js-prog-main-color bg-primary" class="js-prog-main-color"></span>
                            </div>
                            <div class="col-md-3 pl-0">
                                <button type="button" class="btn btn-primary" data-target="#js-progs-change-color" data-toggle="modal"><img src="{{ asset('assets/images/color.png')}}" alt="" style="width:15px;">&nbsp; Select</button>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center mt-2">
                            <div class="col-md-3">
                                <input type="text" id="js-prog-essential-fldhead-input" class="form-control" value="" readonly="readonly">
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="js-prog-essential-fldrepquali-input" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="js-prog-essential-unit-input" class="form-control" value="/min" readonly="readonly">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary" id="js-prog-essential-add-btn"><i class="ri-add-line"></i> Save</button>
                            </div>
                        </div>
                        <div class="essen2-table">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="tittle-th">DateTime</th>
                                        <th class="tittle-th">Examination</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Observation</th>
                                        <th class="tittle-th">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="js-prog-essential-data-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="essential-table3 res-table">
                        <table></table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="essential-exam-chart">
                        <div id="curve_chart" style="width: 766px; max-height: 500px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="js-progs-change-color">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Change Color</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 btn btn-lg btn-color-change p-2 select_color" style="background-color: red;margin-right: 10px;margin-left: 25px;" data-color="red"></div>
                    <div class="col-md-2 btn btn-lg btn-color-change p-2 select_color" style="background-color: yellow;margin-right: 10px;" data-color="yellow"></div>
                    <div class="col-md-2 btn btn-lg btn-color-change p-2 select_color" style="background-color: green;margin-right: 10px;" data-color="green"></div>
                    <div class="col-md-2 btn btn-lg btn-color-change p-2 select_color" style="background-color: blue;margin-right: 10px;" data-color="blue"></div>
                    <div class="col-md-2 btn btn-lg btn-color-change p-2 select_color" style="background-color: black;margin-right: 10px;" data-color="black"></div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" id="js-progs-change-color-btn" class="btn btn-primary btn-sm">Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>

<script type="text/javascript">
    $('#js-prog-essential-modal').on('hidden.bs.modal', function() {
        getPatientProfileColor();
    });

    $(document).on('click', '#js-prog-essential-tbody tr', function() {
        selected_td('#js-prog-essential-tbody tr', this);
        var fldhead = $('#js-prog-essential-tbody tr[is_selected="yes"] td:first-child').text();
        $('#js-prog-essential-fldrepquali-input').val('0');
        $('#js-prog-essential-fldhead-input').val(fldhead);
        $('#js-prog-essential-unit-input').val($('#js-prog-essential-tbody tr[is_selected="yes"]').data('unit'));

        var encounterLocal = '';
        if (globalEncounter)
            encounterLocal = globalEncounter;

        $.ajax({
            url: baseUrl + '/inpatient/prog/getEssentialList',
            type: "GET",
            data: {
                fldhead: fldhead,
                encounterId: encounterLocal
            },
            dataType: "json",
            success: function(data) {
                var trData = '';
                $.each(data, function(i, val) {
                    var abnormalVal = get_abnoraml_btn(val.fldabnormal);
                    trData += '<tr data-fldid="' + val.fldid + '"><td>' + val.fldtime + '</td>';
                    trData += '<td>' + fldhead + '</td>';
                    trData += '<td onclick="changeAbnormalStatus.displayModal(\'#js-prog-essential-data-tbody\', 3, ' + val.fldid + ')">' + abnormalVal + '</td>';
                    trData += '<td>' + val.fldrepquanti + '</td>';
                    trData += '<td class="js-delete-exam-btn"><i class="far fa-trash-alt"></i></td></tr>';
                });
                $('#js-prog-essential-data-tbody').empty().html(trData);
            }
        });
    });

    $('#js-prog-essential-add-btn').click(function() {
        var encounterLocal = '';
        if (globalEncounter)
            encounterLocal = globalEncounter;

        $.ajax({
            url: baseUrl + '/inpatient/prog/saveEssential',
            type: "POST",
            data: {
                fldhead: $('#js-prog-essential-fldhead-input').val(),
                fldrepquali: $('#js-prog-essential-fldrepquali-input').val(),
                encounterId: encounterLocal,
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    var val = response.data;
                    var abnormalVal = get_abnoraml_btn(val.fldabnormal);
                    var trData = '<tr data-fldid="' + val.fldid + '"><td>' + val.fldtime + '</td>';
                    trData += '<td>' + val.fldhead + '</td>';
                    trData += '<td onclick="changeAbnormalStatus.displayModal(\'#js-prog-essential-data-tbody\', 3, ' + val.fldid + ')">' + abnormalVal + '</td>';
                    trData += '<td>' + val.fldrepquali + '</td>';
                    trData += '<td class="js-delete-exam-btn"><i class="far fa-trash-alt"></i></td></tr>';

                    $('#js-prog-essential-data-tbody').append(trData);
                }
                showAlert(response.message);
            }
        });
    });

    $('#js-progs-change-color-btn').click(function() {
        var color = $('.btn-color-change[is_selected="yes"]').data('color') || '';
        if (color !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/prog/changeColor',
                type: "POST",
                data: {
                    color: color,
                    encounterId: globalEncounter
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $('#js-prog-main-color').css('background-color', color);
                        $('#js-progs-change-color').modal('hide');
                    }
                    showAlert(response.message);
                }
            });
        } else
            alert('Please choose color to change.');
    });

    $('.btn-color-change').click(function() {
        $('.btn-color-change').attr('is_selected', 'no');
        $(this).attr('is_selected', 'yes');
    });

    $(document).on('click', '#js-prog-essential-data-tbody tr td:nth-child(4)', function() {
        updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
    });

    $(document).on('click', '#js-prog-essential-data-tbody tr', function() {
        selected_td('#js-prog-essential-data-tbody tr', this);
    });

    $(document).on('click', '#generate-line-chart-essence-exam', function() {
        var fldhead = $('#js-prog-essential-tbody tr[is_selected="yes"] td:first-child').text();
        var encounterLocal = '';
        if (globalEncounter)
            encounterLocal = globalEncounter;

        $.ajax({
            url: baseUrl + '/inpatient/prog/essence-line-chart',
            type: "get",
            data: {
                fldhead: fldhead,
                encounterId: encounterLocal
            },
            success: function(response) {
                document.getElementById('curve_chart').innerHTML = "";
                if (response.dataCount > 1) {
                    google.charts.load('current', {
                        'packages': ['corechart']
                    });
                    google.charts.setOnLoadCallback(drawChart);
                }

                function drawChart() {
                    var data = google.visualization.arrayToDataTable(response.data);

                    var options = {
                        title: 'Essential Exam',
                        curveType: 'function',
                        legend: {
                            position: 'bottom'
                        }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);
                }
            }
        });
    });

    $(document).on('click', '.js-delete-exam-btn', function() {
        var currentElem = $(this).closest('tr');
        var fldid = $(currentElem).data('fldid');

        if (confirm('Are you sure you want to delete??')) {
            $.ajax({
                url: baseUrl + '/inpatient/onexamination/deleteExamination',
                type: "POST",
                data: {
                    fldid: fldid
                },
                success: function(response) {
                    if (response.status)
                        $(currentElem).remove();

                    showAlert(response.message);
                }
            });
        }
    });

    $('.select_color').click( function () {
        var current = $(this);
        $('.selected').removeClass('selected');
        current.addClass("selected");

    })
</script>
@endpush