@extends('frontend.layouts.master')

@section('content')
    <style>
        ul.timeline {
            list-style-type: none;
            height: 100%;
            overflow: auto;
            position: relative;
            width: 100%;
            max-height: calc(100vh - 350px);
        }

        /* ul.timeline:after {
            content: " ";
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            overflow: auto;
        } */

        ul.timeline > li {
            padding: 5px 0;
            position: relative;
        }

        ul.timeline > li button:before {
            content: " ";
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            margin: 7px 0;
            z-index: 1;
        }

        ul.timeline > li:after {
            content: " ";
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            top: 0;
            width: 2px;
            height: 100%;
            overflow: auto;
            z-index: 0;
        }

        .titles {
            font-weight: 600;
            cursor: pointer;
        }

        .heading {
            font-weight: 400;
            padding-top: 3px;
            border-top: 1px solid #e3e3e3;
        }
        table {
            width: 100%;
        }

        .detail-table tr td {
            width: 50%;
        }

        .detail-table tr td:nth-child(2) {
            text-align: right;
        }

        .acc-button {
            background: unset;
            border: unset;
            padding: 5px;
            width: 100%;
            transition: 0.2s ease-out;
        }

        .acc-button:hover {
            background: #fafafa;
        }

        .acc-button a {
            margin-left: 50px;
        }

        .detail {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-left: 50px
        }

        .patient-ledger-detail-modal .modal-dialog{
            display: flex;
            align-items: center;
            height: calc(100vh - 200px);
        }

        table.table {
            table-layout: auto;
        }


        /* .acc-button:hover + .detail { */
        /* .acc-button.active + .detail {
            display: block;
            max-height: 0;
            transition: max-height 0.5s ease-out;
        } */
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Patient Ledger Report
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex flex-row">
                                    <div class="d-flex flex-column col-sm-3">
                                        <label>Patient no.</label>
                                        <div class="input-group">
                                            <input type="text" name="" id="patient-ledger-search-id" placeholder="Patient no." class="form-control">
                                            <div class="input-group-append">
                                                <button type="button" onclick="searchLedgerPatients()" class="btn btn-secondary" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-wrap">
                                        <div class="d-flex flex-column col-sm-6 col-md-6 mb-2">
                                            <label>Patient Name</label>
                                            <span><b id="patient-name">N/A</b></span>
                                        </div>
                                        <div class="d-flex flex-column col-sm-6 col-md-6 mb-2">
                                            <label>Age / Gender</label>
                                            <span><b id="patient-age">N/A</b></span>
                                        </div>
                                        <div class="d-flex flex-column col-sm-6 col-md-6 mb-2">
                                            <label>Address</label>
                                            <span><b id="patient-address">N/A</b></span>
                                        </div>
                                        <div class="d-flex flex-column col-sm-6 col-md-6 mb-2">
                                            <label>Phone Number</label>
                                            <span><b id="patient-phone-number">N/A</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="container">
                                    <div class="row">
                                        <h6 style="margin-left: 30px; margin-bottom: 10px;">Patient Detail Timeline</h6>
                                        <ul class="timeline" id="timeline-div">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table res-table" id="ajaxresult">
                                    <table id="patient-ledger-table"
                                    >
                                        <thead class="thead-light">
                                            <tr>
                                                <th>SN.</th>
                                                <th>Bill no.</th>
                                                <th>Pay Item Name</th>
                                                <th>Prev Deposit</th>
                                                <th>ItemAmt</th>
                                                <th>DiscAmt</th>
                                                <th>TaxAmt</th>
                                                <th>RecvAmt</th>
                                                <th>CurrDeposit</th>
                                                <th>Payment Mode</th>
                                                <th>User</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.common.patient-ledger-detail')

@endsection
@push('after-script')
    <script>
        enableAccButton();
        function enableAccButton(){
            var acc = document.getElementsByClassName("acc-button");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    for (j = 0; j < acc.length; j++) {
                            acc[j].classList.toggle("active");
                            var details = acc[j].nextElementSibling;
                        details.style.maxHeight = null;
                    }
                    this.classList.toggle("active");
                    var detail = this.nextElementSibling;
                    if (detail.style.maxHeight) {
                        detail.style.maxHeight = null;
                    } else {
                        detail.style.maxHeight = detail.scrollHeight + "px";
                    }
                });
            }
        }

        $(document).on("click", ".encounter-list", function (e) {
            var url = "{{ route('patient.ledger.getPatientData') }}";
            var value =$(this).data('value');
            $.ajax({
                url: url ,
                type: "GET",
                data: {
                    encounterval: value
                },
                success: function (response) {
                    if(response.data) {
                        $('#ajaxresult').html(response.data.html);
                        $('#patient-ledger-table').bootstrapTable();
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("{{ __('messages.error') }}", 'error')
                }
            });
        });
        $(document).on("keyup", "#patient-ledger-search-id", function (e) {
            e.preventDefault();
            if(e.keyCode === 13) {
                searchLedgerPatients();
            }

        });
        $(function() {
            $('#patient-ledger-table').bootstrapTable();
        })

        function searchLedgerPatients(){
            if($("#patient-ledger-search-id").val() == ''){
                showAlert("patient id cannot be empty.", 'error')
                return;
            }
            var url = "{{ route('patient.ledger.search.encounter') }}";
            var value = $("#patient-ledger-search-id").val();
            $.ajax({
                url: url ,
                type: "GET",
                data: {
                    key: value
                },
                success: function (response) {
                    if(response){
                        var responseHtml="";
                        if(response.patient_data){
                            $('#patient-name').html(response.patient_data.fldfullname);
                            $('#patient-age').html(response.patient_data.fldagestyle + '/'+response.patient_data.fldptsex);
                            $('#patient-address').html(response.patient_data.fulladdress);
                            $('#patient-phone-number').html(response.patient_data.fldptcontact);
                        }

                        $.each(response.encounter_data, function(i, val) {
                            responseHtml+=
                                "<li data-value='"+ val.fldencounterval+"' class='encounter-list'> <button class='acc-button'> <div class='d-flex justify-content-between'> <a class='titles'>Enc No:"+ val.fldencounterval+"</a> <a>"+val.nepali_date+"</a> </div></button>"
                                +"<div class='detail'>"
                                +"<h6 class='heading'>Total Expenses</h6>"
                                +"<table class='detail-table'>"
                                +"<tr><td>Pharmacy</td><td>Rs. "+val.totalPharmacy+"</td></tr>"
                                +"<tr><td>Service</td><td>Rs. "+val.totalService+"</td></tr>"
                                +"<tr><td>Credit</td><td>Rs. "+val.totalCredit+"</td></tr>"
                                +"<tr><td>Credit Clearance</td><td>Rs. "+val.totalCreditClearance+"</td></tr>"
                                +"<tr><td>To Refund</td><td>Rs. "+val.totalToRefund+"</td></tr>"
                                +"<tr><td>Deposit</td><td>Rs. "+val.totalDeposit+"</td></tr>"
                                +"<tr><td>Deposit Refund</td><td>Rs. "+val.totalDepositRefund+"</td></tr>"
                                +"</table></div></li>";
                        });
                        $('#timeline-div').html(responseHtml);
                        $('#ajaxresult').html("<table id='patient-ledger-table' data-show-columns='true'"+
                            "data-search='true' data-show-toggle='true' data-pagination='true' data-resizable='true' >"+
                            "<thead class='thead-light'> <th>SN.</th> <th>Bill no.</th><th>Pay Item Name</th>"+
                            " <th>Prev Deposit</th><th>ItemAmt</th> <th>DiscAmt</th><th>TaxAmt</th> <th>RecvAmt</th>"+
                            "<th>CurrDeposit</th><th>Department</th><th>Payment Mode</th></thead></table>");
                        enableAccButton();
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("{{ __('messages.error') }}", 'error')
                }
            });
        }
    </script>
@endpush
