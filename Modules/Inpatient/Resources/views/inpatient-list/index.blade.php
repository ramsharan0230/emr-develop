@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Inpatient List
                            </h3>
                        </div>
                    </div>
                    <form action="{{ route('inpatient.inpatient.search') }}" id="search_form">
                        @csrf
                        <div class="iq-card-body">
                            <div class="form-row">
                                <div class="col-sm-3">
                                    <label for="from_date">From date </label>
                                    <input type="text" id="from_date" name="from_date" value="{{ $date }}" class="form-control nepaliDatePicker" placeholder="From Date"/>
                                </div>
                                <div class="col-sm-2">
                                    <label for="to_date">To date </label>
                                    <input type="text" id="to_date" name="to_date" value="{{ $date }}" class="form-control nepaliDatePicker" placeholder="To Date"/>
                                </div>
                                <div class="col-sm-5 mt-4 mb-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" id="admitted_only"
                                               value="admitted" checked class="custom-control-input">
                                        <label class="custom-control-label" for="admitted_only"> Admitted Only </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" id="discharged_only" class="custom-control-input" value="discharge">
                                        <label class="custom-control-label" for="discharged_only"> Discharge Only</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" id="both" class="custom-control-input" value="both">
                                        <label class="custom-control-label" for="both"> Both </label>
                                    </div>
                                </div>
                                <div class="col-sm-7  mt-3">
                                    <button type="button" class="btn btn-primary btn-action" name="refresh" id="refresh"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <input type="hidden" name="encounter" id="encounter">
        <input type="hidden" name="patient" id="patient">
        <div class="">
            <div class="iq-card iq-card-block">
                {{-- <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Search</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="search" id="search" class="form-control">
                            </div>

                        </div>
                    </div>
                </div> --}}
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive" id="search-data">
                            <table id="myTable1" data-show-columns="true"
                                    data-search="true"
                                    data-show-toggle="true"
                                    data-pagination="true"
                                    data-resizable="true">
                                <thead>
                                <tr>
                                    {{--                                    <th class="text-center"></th>--}}
                                    <th class="text-center">S.N</th>
                                    <th class="text-center">IP Date</th>
                                    <th class="text-center">Dis Date</th>
                                    <th class="text-center">Hosp No</th>
                                    <th class="text-center">IP No</th>
                                    <th class="text-center">Ward/BNo/Room.</th>
                                    <th class="text-center">Fname</th>
                                    <th class="text-center">Lname</th>
                                    <th class="text-center">Doctor</th>
                                    <th class="text-center">Gender</th>
                                    <th class="text-center">Age</th>
                                    <th class="text-center">Billno</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Guardian</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody id="patientListBody">
{{--                                {{ dd($encounters) }}--}}
                                @forelse($encounters as $encounter)
                                    <tr class="list_tr" data-encounter="{{ $encounter->fldencounterval }}"
                                        data-patient=" {{ $encounter->fldpatientval }} " id="list_tr{{ $loop->iteration }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $encounter -> flddoa ? \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($encounter -> flddoa)->format('Y-m-d'))->full_date : '' }}</td>
                                        <td>{{ $encounter -> flddod ?\App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($encounter -> flddod)->format('Y-m-d'))->full_date : '' }}</td>
                                        <td>{{ $encounter->fldpatientval  ?? null }}</td>
                                        <td>{{ $encounter->fldencounterval ?? null }}</td>
                                        <td>  <span class="bed_number">{{ ((isset($encounter->departmentBed) && $encounter->departmentBed->flddept) ? $encounter->departmentBed->flddept : '') }}
{{--                                                {{ ($encounter->fldcurrlocat) ? ($encounter->fldcurrlocat =='Null' ? '' : $encounter->fldcurrlocat) : '' }}--}}
                                                {{ (isset($encounter) && $encounter->departmentBed && $encounter->departmentBed->fldbed ) ?  '/'.$encounter->departmentBed->fldbed :'' }} </span>
                                            {{ (isset($encounter) && $encounter->room) ? '/'.$encounter->room->fldroom :''  }}</td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fldptnamefir : '')) }}</td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fldptnamelast : '')) }}</td>
                                        <td> {{ (((isset($encounter) && $encounter->user) ? $encounter->user->fldtitlefullname : '')) }}</td>
{{--                                        <td> {{ ((isset($encounter->consultant) ? $encounter->consultant->user->fldtitlefullname : '')) }}</td>--}}
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fldptsex : '')) }}</td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->age() : '')) }}</td>
                                        @php
                                            $bill ='';
                                            if((isset($encounter) && $encounter->fldadmission=='Discharged')){
                                            $bill = \App\Utils\Helpers::getDischargeBill($encounter->fldencounterval);
                                            }
                                        @endphp
{{--                                        $bills = (isset($encounter) && $encounter->patBillDetails) ? $encounter->patBillDetails->where('fldpayitemname','Discharge Clearance')->pluck('fldbillno')->toArray() :'';--}}
                                        {{--                                            $bill = implode(',',array_filter($bills,'strlen'));--}}
                                        <td>{{ isset($bill) ? $bill :'' }} </td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fldptcontact : '')) }}</td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fulladdress : '')) }}</td>
                                        <td> {{ ((isset($encounter->patientInfo) ? $encounter->patientInfo->fldptguardian : '')) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin('bed-occupancy'))
                                                    <a class="dropdown-item bed_exchange" data-encounter="{{$encounter->fldencounterval}}" data-patient="{{$encounter->fldpatientval}}" data-bedid="{{$encounter->fldcurrlocat}}">Bed Exchange</a>
                                                    @endif
                                                    @if (\App\Utils\Permission::checkPermissionFrontendAdmin('discharge-clearance'))
                                                        <a class="dropdown-item " id="discharge_billing_btn" href="{{ route('billing.dischargeClearance',['encounter_id' =>$encounter->fldencounterval ])  }}" target="_blank">Discharge Billing</a>
                                                    @endif
                                                    @if (\App\Utils\Permission::checkPermissionFrontendAdmin('credit-clearence'))
                                                        <a class="dropdown-item " id="creadit_btn" href="{{ route('billing.display.form',['encounter_id' =>$encounter->fldencounterval ]) }}" target="_blank">Credit Billing</a>
                                                    @endif
                                                    @if (\App\Utils\Permission::checkPermissionFrontendAdmin('deposit-form'))
                                                        <a class="dropdown-item " id="deposit_billing_btn" href="{{ route('depositForm',['encounter_id' =>$encounter->fldencounterval ]) }}" target="_blank">Deposit Billing </a>
                                                    @endif
                                                    @if (\App\Utils\Permission::checkPermissionFrontendAdmin('pharmacy-sales-report'))
                                                        <a class="dropdown-item " id="pharmacy_sale_btn" href="{{ route('dispensingForm',['encounter_id' =>$encounter->fldencounterval ]) }}" target="_blank">Pharmacy Sale</a>
                                                    @endif
                                                    @if (\App\Utils\Permission::checkPermissionFrontendAdmin('dataview-transition-report'))
                                                        <a class="dropdown-item " id="transiition_btn" href="{{ route('dataview.transitions',['encounter_id' =>$encounter->fldencounterval ]) }}" target="_blank">Transitions</a>
                                                    @endif







                                                    @if((isset($encounter) && $encounter->fldadmission=='Discharged'))
                                                    <a class="dropdown-item undo_discharge" data-encounter=" {{ (isset($encounter) ? $encounter->fldencounterval : '') }} " data-patient=" {{ (isset($encounter) ? $encounter->fldpatientval : '') }} "  id="undo_discharge_btn" href="{{ route('inpatientlist.undo.discharge',['encounter_id' =>$encounter->fldencounterval ]) }}" target="_blank">Undo Discharge</a>
                                                    @endif
                                                </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" align="center"> No data available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div>
                        {{ $encounters->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>



    <!--Assign bed-->

    <div class="modal fade" id="assign-bed-emergency">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Assign Bed</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" name="current-patient-bed" id="current-patient-bed">
                    <div class="form-group form-row">
                        <select id="select-department-emergency" class="col-6 form-control"
                                name="select-department-emergency">
                            <option value="">---Select Department---</option>
                            @if(isset($departments))
                                @foreach($departments as $department)
                                    <option value="{{ $department->flddept }}"
                                            bed1="{{asset('assets/images/bed-2.png')}}"
                                            bed2="{{asset('assets/images/bed-1.png')}}">{{ $department->flddept }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="container-fluid">
                            <div class="departments-bed-list row">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    {{-- <a href="javascript:;" id="save-department-bed" url="{{ route('save.department.bed') }}" class="btn btn-primary">Save changes</a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- END Assign Bed-->


    {{-- occupied bed modal --}}
    <div data-backdrop="static" class="modal fade" id="occupied-bed-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <input type="hidden" id="selected-bed">
            <form method="POST" id="assign-bed-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeDeptModalLabel">Do you want to hold current bed?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group" id="occupied-bed-list">

                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="assign-bed-submit-btn" url="{{ route('save.department.bed') }}" type="button"
                                class="btn btn-primary" data-dismiss="modal">No
                        </button>
                        <button id="append-assign-bed-submit-btn" url="{{ route('update.department.bed') }}"
                                type="button" class="btn btn-success">Yes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end of occupied bed modal --}}

@endsection
@push('after-script')
    <script>

        $('#refresh').click(function (e) {
            e.preventDefault()
            getPatientDetailBySearch();
        });

        function getPatientDetailBySearch() {
            var admitted = $('#admitted_only').val();
            var discharge = $('#discharged_only').val();
            var both = $('#both').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var data = $('#search_form').serialize();
            var url ="{{ route('inpatient.inpatient.search') }}";
            $.ajax({
                method: "GET",
                url: url,
                data:
                    {
                        admitted: admitted,
                        discharge: discharge,
                        both: both,
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                    },
            }).done(function (html) {
                if (html) {
                    $('#search-data').empty().html(html);
                    $('#myTable2').bootstrapTable();
                }
            });
        }

        $(document).on('click','.bed_exchange',function () {
            var encountnerr = $(this).data("encounter");
            var patientt = $(this).data("patient");
            $('#encounter').val(encountnerr);
            $('#patient').val(patientt);
            //Testing no refresh
                selected_td('#patientListBody tr', this);
            // alert($(this).closest('tr').find('.bed_number').text());
            // alert($(document).find('.bed_number').text());
            // return  false;
            if (encountnerr == '') {
                showAlert('Something went wrong with encounter', 'error');
                return false;
            }
            $('#current-patient-bed').val($(this).data('bedid'));
            $('#assign-bed-emergency').modal('show');
        });

        function selected_td(elemId, currentElem) {
            $.each($(elemId), function (i, e) {
                $(e).removeClass('selected_bed');
            });
            $(currentElem).closest('tr').find('.bed_number').addClass('selected_bed');
        }



        $(document).on('change', '#select-department-emergency', function (e) {
            var encounter_id = $('#encounter').val();
            var flddept = e.target.value;
            if (flddept === "") {
                showAlert('Select Department.', 'error');
                return false;
            }

            if ($("#encountner").val() === "") {
                showAlert('Select patient.', 'error');
                return false;
            }
            var num = 1;
            $.get('inpatient-list/get-related-bed?flddept=' + flddept, function (data) {
                $('.departments-bed-list').empty().html(data.html);
            });
        });

        $(document).on('click', '.empty-bed', function (event) {
            event.preventDefault();
            let enc_id = $('#encounter').val();
            let bedSelected = $(this).closest('.text-center').data('bedid');
            // get occupied beds by the encounter.
            let url = '{{ route("encounter.department-beds", ":ENCOUNTER_VAL") }}';
            url = url.replace(':ENCOUNTER_VAL', enc_id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                async: true,
                success: function (res) {
                    if (res.data.length >=1) {
                        let li = "";
                        let bed_img = "{{ asset('new/images/bed-occupied.png')}}";    
                        $.each(res.data, function (i, v) {
                            li += '<li class="list-group-item" style="display: flex; align-items: center;">\
                                        <img style="width: 39px; height: 100%; margin-right: 7px;" src="' + bed_img + '" class="img-bed"/>\
                                        <div style="line-height: 15px;">\
                                            <div style="">' + v.fldbed + '</div>\
                                            <small>' + v.flddept + '</small>\
                                        </div>\
                                    </li>';
                        });

                        $("#occupied-bed-list").html(li);
                    } else {
                        $("#changeDeptModalLabel").html('Do you want to proceed?');
                        $("#assign-bed-submit-btn").hide();
                    }

                    $("#assign-bed-emergency").modal('hide');
                    $('#selected-bed').val(bedSelected);
                    $("#occupied-bed-modal").modal('show');
                }
            });
        });

        $(document).on('change', '#select-department-emergency', function (e) {
            var flddept = e.target.value;
            if (flddept === "") {
                showAlert('Select Department.', 'error');
                return false;
            }

            if ($('#encounter').val() === "") {
                showAlert('Select patient.', 'error');
                return false;
            }
            var num = 1;
            $.get('inpatient-list/get-related-bed?flddept=' + flddept, function (data) {
                $('.departments-bed-list').empty().html(data.html);
            });
        });

        function saveDepartmentBed(event) {
            var fldcurrlocat = $('#select-department-emergency option:selected').val();
            var fldbed = $("input[name='department_bed']:checked").val();
            var fldencounterval = "";
            console.log('insiide');
            if ($("#fldencounterval").length > 0) {
                fldencounterval = $("#fldencounterval").val();
            }

            if ($("#encounter").length > 0) {
                fldencounterval = $('#encounter').val();
            }

            var url = $(event.target).attr("url");
            var holdbed = confirm('Do you want to hold current bed?');

            if (holdbed == true) {

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                    holdbed: holdbed,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $('#assign-bed-emergency').modal('hide');
                            $(document).find('.selected_bed').text('');
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('inpatient-list/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $(document).find('.selected_bed').text(data.fldcurrlocat + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    }
                });
            }
        }

        $('#save-department-bed').click(function (event) {
            saveDepartmentBed(event);
        });

        $("#assign-bed-submit-btn").click(function (event) {
            let btn = $(this);
            btn.html('Updating...').prop('disabled', true);
            setTimeout(() => {

                var fldcurrlocat = $('#select-department-emergency option:selected').val();
                var fldbed = $("input[name='department_bed']:checked").val();
                var fldencounterval = "";

                if ($("#fldencounterval").length > 0) {
                    fldencounterval = $('#encounter').val();
                }

                if ($("#encounter").length > 0) {
                    fldencounterval = $('#encounter').val();
                }

                var url = $(event.target).attr("url");

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $("#show-btn").click();

                            $('#occupied-bed-modal').modal('hide');
                            $(document).find('.selected_bed').text('');
                            // $("#get_related_fldcurrlocat").html(null);
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('inpatient-list/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $(document).find('.selected_bed').text(data.current_bed_department.flddept + ' / ' + fldbed);
                                // $(document).find('.selected_bed').text(data.fldcurrlocat + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    },
                    complete: function () {
                        btn.html('No').prop('disabled', false);
                        var selectedBed = $('#selected-bed').val();
                        var span = $('#transfer-bed-list').find('.text-center').filter('[data-bedid='+selectedBed+']');
                        var html = '<label for="'+selectedBed+'">'+
                                        '<img src="{{ asset('new/images/bed-occupied.png')}}" class="img-bed" alt="'+fldencounterval+'" title="'+fldencounterval+'" />'+
                                    '</label>'+
                                    '<p>'+selectedBed+'</p>'; 
                        span.html(html);

                        var oldBed = $('#current-patient-bed').val();
                        var oldspan = $('#transfer-bed-list').find('.text-center').filter('[data-bedid='+oldBed+']');
                        var oldhtml = '<div class="empty-bed">'+
                                        '<input type="radio" name="department_bed" id="'+oldBed+'" value="'+oldBed+'" style="display:none" />'+
                                        '<label for="'+oldBed+'"> <img style="cursor: pointer;" src="{{ asset('new/images/bed-1.jpg')}}" class="img-bed" alt=""/>'+
                                        '</label>'+
                                    '</div>'+
                                    '<p>'+oldBed+'</p>'; 
                        oldspan.html(oldhtml);
                    }
                });
            }, 500);
        });

        // append new bed
        $("#append-assign-bed-submit-btn").click(function (event) {
            let btn = $(this);
            btn.html('Updating...').prop('disabled', true);
            setTimeout(() => {

                var fldcurrlocat = $('#select-department-emergency option:selected').val();
                var fldbed = $("input[name='department_bed']:checked").val();
                var fldencounterval = "";

                if ($("#encounter").length > 0) {
                    fldencounterval = $("#encounter").val();
                }

                if ($("#encounter").length > 0) {
                    fldencounterval = $('#encounter').val();
                }

                var url = $(event.target).attr("url");

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $("#show-btn").click();
                            $('#occupied-bed-modal').modal('hide');
                            $(document).find('.selected_bed').text('');
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('inpatient-list/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $(document).find('.selected_bed').text(data.current_bed_department.flddept + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    },
                    complete: function () {
                        btn.html('Yes').prop('disabled', false);
                    }
                });
            }, 500);
        });
        $( document ).ready(function() {
            $('#discharged_only').val('');
            $('#both').val('')
        });

        $('#admitted_only').click(function () {
            $(this).val('admitted');
            $('#discharged_only').val('');
            $('#both').val('')
        });
        $('#discharged_only').click(function () {
            $(this).val('discharge');
            $('#admitted_only').val('');
            $('#both').val('')
        });
        $('#both').click(function () {
            $(this).val('both');
            $('#discharged_only').val('');
            $('#admitted_only').val('')
        });

        //undo discharge
        $(document).on('click','.undo_discharge',function () {
            var encounter = $(this).data("encounter");
            var patient = $(this).data("patient");
            var url = $(this).attr('url');

            if (encounter == '') {
                showAlert('Something went wrong with encounter', 'error');
                return false;
            }
            $.ajax({
                method: "GET",
                url: url,
                data:
                    {
                        encounter: encounter,
                        patient: patient,
                    },
            }).done(function (data) {
                if (data.message) {
                    showAlert(data.message);
                    location.reload();
                }
                if(data.error){
                    showAlert(data.error,'error');
                }
            });
        });


        // for search in table
        $("#search").on("keyup", function() {
            var value = $(this).val();

            $("table tr").each(function (index) {
                if (!index) return;
                $(this).find("td").each(function () {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });

        $(function() {
            $('#myTable1').bootstrapTable()
        })

    </script>
@endpush

