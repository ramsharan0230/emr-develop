<form action="{{ route('patient.ip-round.form.save.waiting') }}" class="services-form" id="services-request-submit" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="flditemtype" value="General Services">
    <input type="hidden" name="patientLocation" value="{{ $encounterData->fldcurrlocat }}">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Refer By:</label>
                <div class="col-sm-10">
                    <select name="referer_by" id="refer_by_change_lab" class="form-control">
                        @if(count($refer_by))
                            @foreach($refer_by as $refer)
                                <option value="{{ $refer->username }}">{{ $refer->firstname.' '. $refer->lastname }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <ul class="nav nav-tabs" id="yourTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="services-request-tab" data-toggle="tab" href="#services-request" role="tab" aria-controls="services-request" aria-selected="false">Request </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="services-reported-tab" data-toggle="tab" href="#services-reported" role="tab" aria-controls="services-reported" aria-selected="false">Reported </a>
                    </li>
                </ul>
                <div class="tab-content" id="nav-tabContent">
                    {{--request--}}
                    <div class="tab-pane fade show active" id="services-request" role="tabpanel" aria-labelledby="services-request-tab">
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <input id="search-new-request-services" type="text" class="form-control" placeholder="Search.." autocomplete="off">
                                <div id="style-1 mt-2 mb-2">
                                    <ul class="res-table list-group" id="search-new-request-table" style="padding: .2rem;">
                                        @if(count($itemsForMultiselect))
                                            @foreach($itemsForMultiselect as $fldName)
                                                <li class="list-group-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="servicesreport[]" class="custom-control-input services-radio-check" value="{{ $fldName->flditemname }}">
                                                        <label class="custom-control-label">{{ $fldName->flditemname }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary btn-sm disableInsertUpdate" id="save-request-waiting" style="display: inline-block; margin-right: 79px;"><i class="fas fa-caret-left"></i> <i class="fas fa-caret-right"></i></button>
                                    <div>
                                        <a href="javascript:void(0)" class="btn btn-primary disableInsertUpdate" onclick="insertUpdateRequestServices.updateRequest();"><i class="fas fa-save"></i>&nbsp;Save</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-primary  disableInsertUpdate" onclick="insertUpdateRequestServices.cancelRequest()"><i class="fas fa-times"></i>&nbsp;Cancel</a>
                                    </div>
                                </div>
                                <div class="res-table mt-2">
                                    <table class="table table-bordered append-request-data-services table-hover table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th></th>
                                            <th>Date Time</th>
                                            <th>Service Name</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Cost</th>
                                            <th>Status</th>
                                            <th style="width: 150px;">Doctors</th>
                                            <th>Perform</th>
                                        </tr>
                                        </thead>
                                        <tbody class="services-request-table" id="patbillingData">
                                        @if(count($patBillingListPunched))
                                            @foreach($patBillingListPunched as $bill)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="services-request-check[]" value="{{ $bill->fldid }}" class="custom-control-input">
                                                            <label for="" class="custom-control-label"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="hidden" name="fldid-request[]" value="{{ $bill->fldid }}" class="custom-control-input">
                                                            <label for="" class="">{{ $bill->fldordtime }}</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        {{ $bill->flditemname }}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="flditemno-request[]" value="{{ $bill->flditemno }}">
                                                        <input style="width: 52px;" type="number" class="service_quantity" name="service_quantity[]" min="1" value="{{ isset($bill->flditemqty) ? $bill->flditemqty : 1 }}">
                                                    </td>
                                                    <td class="flditemrate" data-rate="{{ $bill->flditemrate }}" data-currency="{{ $bill->fldcurrency }}">
                                                        {{ $bill->fldcurrency }} {{ $bill->flditemrate }}
                                                    </td>
                                                    <td class="fldditemamt" data-amount="{{ $bill->flditemrate }}" data-currency="{{ $bill->fldcurrency }}">
                                                        {{ $bill->fldcurrency }} {{ $bill->fldditemamt }}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="status-request[]" value="{{ $bill->fldstatus }}">
                                                        {{ $bill->fldstatus }}
                                                    </td>
                                                    <td>
                                                        <select data-id="{{ $bill->fldid }}" class="form-control select2 select-doctors" multiple name="doctor_id">
                                                            @foreach ($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ in_array($doctor->id, collect($bill->pat_billing_shares)->pluck('user_id')->toArray())?'selected':'' }}>{{ $doctor->fldfullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;" onclick="insertUpdateRequestServices.deleteRequestedData('{{ $bill->fldid }}')">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end request--}}
                    {{--reported--}}
                    <div class="tab-pane fade" id="services-reported" role="tabpanel" aria-labelledby="services-reported-tab">
                        <div class="row">
                            {{-- <div class="col-md-4">
                                <ul class="list-group">
                                    @if(count($patlabtestRequest))
                                        @foreach($patlabtestRequest as $pltr)
                                            <li class="list-group-item">
                                                <a href="javascript:void(0)" onclick="requestedServices.reportedSelected('{{ $pltr->col }}')">{{ $pltr->col }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="res-table">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Ordered Time</th>
                                            <th>Refered By</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Cost</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody class="services-append-reported">
                                        @foreach ($patBilling as $patBill)
                                            <tr>
                                                <td>{{ $patBill->flditemname }}</td>
                                                <td>{{ $patBill->fldordtime }}</td>
                                                <td>{{ $patBill->fldrefer }}</td>
                                                <td>{{ $patBill->flditemqty }}</td>
                                                <td>{{ $patBill->fldcurrency }} {{ $patBill->flditemrate }}</td>
                                                <td>{{ $patBill->fldcurrency }} {{ $patBill->fldditemamt }}</td>
                                                <td>{{ $patBill->fldstatus }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end reported--}}
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $(".select2").select2({
            width: 'resolve'
        });
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
    });

    $('.select-doctors').on('select2:select', function (e) {
        e.preventDefault();
        let fldid = $(this).data('id');
        // save to patbilling share table.
        let doc_id = e.params.data.id;
        let url = '{{ route("patient.ip-round.form.save.doc-share") }}';
        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            data: {user_id: doc_id, bill_id: fldid},
            success: function (res) {
                if (res.success) {
                    showAlert(res.message);
                }
            }
        });
    });

    $('.select-doctors').on('select2:unselect', function (e) {
        // remove doctor from pat billing share
        let doc_id = e.params.data.id;
        let fldid = $(this).data('id');
        let url = '{{ route("patient.ip-round.form.remove.doc-share") }}';
        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            data: {user_id: doc_id, bill_id: fldid},
            success: function (res) {
                if (res.success) {
                    showAlert(res.message);
                }
            }
        });
    });

    var insertUpdateRequestServices = {
        updateRequest: function () {
            var error = false;
            $('.error').each(function () {
                $(this).remove();
            });

            $('.service_quantity').each(function () {
                if ($(this).val() < 1) {
                    $(this).closest("td").append("<span class='error'>Quantity must be greater than 0.</span>")
                    error = true;
                } else {
                    $(this).closest("td").find("span").remove();
                }
            });

            if (!error) {
                $.ajax({
                    url: "{{ route('patient.services.form.save.done') }}",
                    type: "POST",
                    data: $('#services-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                    success: function (data) {
                        $('#patbillingData').empty();
                        $('#patbillingData').append(data.punched);
                        $('.services-append-reported').empty();
                        $('.services-append-reported').append(data.reported_html);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
                return false;
            }
        },
        insertRequest: function () {
            $.ajax({
                url: $('#services-request-submit').attr('action'),
                type: $('#services-request-submit').attr('method'),
                data: $('#services-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                success: function (data) {
                    $('#patbillingData').empty();
                    $('.services-radio-check').prop("checked", false);
                    $('#patbillingData').append(data);
                    $(".select2").select2();
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        cancelRequest: function () {
            $.ajax({
                url: "{{ route('patient.services.cancel.services.reported') }}",
                type: "POST",
                data: $('#services-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                success: function (data) {
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        deleteRequestedData: function (fldid) {
            confirmDelete = confirm("Delete?");

            if (confirmDelete === false) {
                return false;
            }
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.services.delete.ip-round.requested') }}",
                type: "POST",
                data: {fldid: fldid, encounterId: encounterId},
                success: function (data) {
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        }
    };

    var requestedServices = {
        reportedSelected: function (fldtestid) {
            unitMedecine = '';
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.services.list.services.reported') }}",
                type: "POST",
                data: {encounterId: encounterId, fldtestid: fldtestid, MedUnit: unitMedecine},
                success: function (data) {
                    $('.services-append-reported').empty();
                    $('.services-append-reported').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    }

    $(document).ready(function () {
        $('#save-request-waiting').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequestServices.insertRequest();
        });

        $('#save-request').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequestServices.updateRequest();
        });
        $("#search-new-request-services").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#search-new-request-table li").filter(function () {
                let item = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(item);
            });
        });

    });

    $(document).on("keyup", ".service_quantity", function (e) {
        var current = $(this);
        keyPressFunction(e, current);
    });

    $(document).on("keydown", ".service_quantity", function (e) {
        var current = $(this);
        keyPressFunction(e, current);
    });

    function keyPressFunction(e, current) {
        var qty = current.val();
        var rate = parseFloat(current.closest("tr").find(".flditemrate").attr("data-rate"));
        var currency = current.closest("tr").find(".flditemrate").attr("data-currency");
        var amount = rate * qty;
        current.closest("tr").find(".fldditemamt").html(currency + " " + amount);
    }

</script>
