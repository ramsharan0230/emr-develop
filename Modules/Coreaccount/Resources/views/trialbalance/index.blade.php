@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Trial Balance
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form id="trial-balance-filter">
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">From Date:<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}">
                                    <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}">
                                    <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
<!--                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Search For:</label>
                                <div class="col-sm-7">
                                    <select name="groupby" id="groupby" class="form-control">
                                        <option value="GroupId">GL Code</option>
                                        <option value="AccountNo">Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>-->

                        <div class="col-sm-2">
                            <div class="form-group form-row">
                                <button type="button" class="btn btn-primary btn-action" onclick="searchTrialBalance()"><i class="fa fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header justify-content-between mt-4">
{{--                    <button type="button" class="btn btn-primary btn-action float-right ml-1" ><i class="fa fa-print"></i>--}}
{{--                        Print--}}
{{--                    </button>--}}
                    <button type="button" class="btn btn-primary btn-action float-right ml-1" onclick="exportTrialBalance()"><i class="fa fa-print"></i>
                        Print
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary btn-action float-right" onclick="exportTrialBalanceExcel()"><i class="fa fa-arrow-circle-down"></i>
                        Export Excel
                    </button>&nbsp;
                </div>
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" rowspan="2">S/N</th>
                                        <th class="text-center" rowspan="2">Group</th>
                                        <th class="text-center" rowspan="2">SubGroup</th>
                                        <th class="text-center" rowspan="2">Account</th>
                                        <th class="text-center" colspan="2">Opening</th>
                                        <th class="text-center" colspan="2">Turnover</th>
                                        <th class="text-center" colspan="2">Closing</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Dr</th>
                                        <th class="text-center">Cr</th>
                                        <th class="text-center">Dr</th>
                                        <th class="text-center">Cr</th>
                                        <th class="text-center">Dr</th>
                                        <th class="text-center">Cr</th>
                                    </tr>
                                </thead>
                                <tbody id="trial-balance-list-data">
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // hide/show
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "none";
        } else {
            x.style.display = "none";
        }
    }

    $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
        }
    });

    function searchTrialBalance(){
        // alert('Trial Balance');
        $.ajax({
            url: baseUrl + '/account/trialbalance/searchTrialBalance',
            type: "POST",
            data: $('#trial-balance-filter').serialize(),
            success: function (response) {
                $('#trial-balance-list-data').html(response.html);
                /*$('#heading1').text(response.heading1);
                $('#heading2').text(response.heading2);*/
                // $('#groupname').append().html(response.grouphtml);
                // $('#accountModal').modal('hide');
                // showAlert('Data Added');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function exportTrialBalance(){
        // alert('Export Trial Balance');
        var data = $('#trial-balance-filter').serialize();
        // alert(data);
        var urlReport = baseUrl + "/account/trialbalance/exportTrialBalance?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');
    }

    function exportTrialBalanceExcel(){
        // alert('Export Trial Balance');
        var data = $('#trial-balance-filter').serialize();
        // alert(data);
        var urlReport = baseUrl + "/account/trialbalance/exportTrialBalanceExcel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');
    }

</script>
@endsection
