@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Account Ledger
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-6">Account Group Code:<span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <select name="GroupId" id="GroupId" class="form-control select2">
                                        <option value="">-- Select Account Group --</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->GroupId }}">{{ $group->GroupName }} @if(isset($group->GroupNameNep)) ( {{ $group->GroupNameNep }} ) @endif</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Account No. :<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="AccountNo" id="AccountNo" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Account Name:<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="AccountName" id="AccountName">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-6">Account Name (Native):<br></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="In Native Language" name="AccountNameNep" id="AccountNameNep">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Report Code:<br></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="ReportCode" id="ReportCode">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Is Active:</label>
                                <div class="col-sm-7">
                                    <select name="fldstatus" id="fldstatus" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary btn-action" id="addLedger"><i class="fa fa-plus"></i>&nbsp;Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered " id="accountLedgerTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Account group Code</th>
                                        <th class="text-center">Account No.</th>
                                        <th class="text-center"> Account Name</th>
                                        <th class="text-center">Account Name (in native language)</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ledger-lists">
                                    {!! $ledgerLists !!}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editledgerModal" tabindex="-1" role="dialog" aria-labelledby="editledgerModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editledgerModalLabel">Edit Account Ledger</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group form-row">
                        <input type="hidden" name="AccountId" id="editAccountId">
                        <label for="" class="col-sm-5">Account Group Code:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select name="GroupId" id="editGroupId" class="form-control select2">
                                <option value="">-- Select Account Group --</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->GroupId }}">{{ $group->GroupName }} @if(isset($group->GroupNameNep)) ( {{ $group->GroupNameNep }} ) @endif</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="" class="col-sm-5">Account No.:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="AccountNo" id="editAccountNo">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="" class="col-sm-5">Account Name:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="AccountName" id="editAccountName">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="" class="col-sm-5">Account Name (Native):</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" placeholder="In Native Language" name="AccountNameNep" id="editAccountNameNep">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="" class="col-sm-5">Report Code:<br></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="ReportCode" id="editReportCode">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label for="" class="col-sm-5">Is Active:</label>
                        <div class="col-sm-7">
                            <select name="editfldstatus" id="editfldstatus" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateLedger">Edit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('after-script')
    <script>
        $( document ).ready(function() {
            $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadLedgers(page);
            });

            $('#accountLedgerTable').DataTable();
        });

        function loadLedgers(page){
            var url = "{{route('accounts.ledger.lists')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                success: function(response) {
                    $('#ledger-lists').html(response)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $(document).on('click','#addLedger',function(){
            if($('#GroupId').val() != "" && $('#AccountNo').val() != "" && $('#AccountName').val() != ""){
                $.ajax({
                    type : 'post',
                    url  : '{{ route("accounts.ledger.create") }}',
                    dataType : 'json',
                    data : {
                    '_token': '{{ csrf_token() }}',
                    'GroupId': $('#GroupId').val(),
                    'AccountNo': $('#AccountNo').val(),
                    'AccountName': $('#AccountName').val(),
                    'AccountNameNep': $('#AccountNameNep').val(),
                    'ReportCode': $('#ReportCode').val(),
                    'fldstatus': $('#fldstatus').val(),
                    },
                    success: function (res) {
                        showAlert(res.message);
                        if(res.status){
                            var newaccount = parseInt($('#AccountNo').val()) + 1;
                            $('#AccountNo').val(newaccount);
                            $('#ledger-lists').html(res.html);
                        }
                    }
                });
            }
        });

        $(document).on('click','#updateLedger',function(){
            if($('#editGroupId').val() != "" && $('#editAccountNo').val() != "" && $('#editAccountName').val() != ""){
                $.ajax({
                    type : 'post',
                    url  : '{{ route("accounts.ledger.create") }}',
                    dataType : 'json',
                    data : {
                    '_token': '{{ csrf_token() }}',
                    'AccountId': $('#editAccountId').val(),
                    'GroupId': $('#editGroupId').val(),
                    'AccountNo': $('#editAccountNo').val(),
                    'AccountName': $('#editAccountName').val(),
                    'AccountNameNep': $('#editAccountNameNep').val(),
                    'ReportCode': $('#editReportCode').val(),
                    'fldstatus': $('#editfldstatus').val(),
                    },
                    success: function (res) {
                        showAlert(res.message);
                        if(res.status){
                            $('#editledgerModal').modal('hide');
                            $('#ledger-lists').html(res.html);
                        }
                    }
                });
            }
        });

        $(document).on('click','.editLedger',function(){
            var accountId = $(this).attr('data-accountid');
            $.ajax({
                type : 'get',
                url  : '{{ route("accounts.ledger.edit") }}',
                dataType : 'json',
                data : {
                '_token': '{{ csrf_token() }}',
                'AccountId': accountId
                },
                success: function (res) {
                    if(res.status){
                        $('#editGroupId option[value='+res.ledgerData.GroupId+']').attr('selected','selected');
                        $('#editAccountNo').val(res.ledgerData.AccountNo);
                        $('#editAccountName').val(res.ledgerData.AccountName);
                        $('#editAccountNameNep').val(res.ledgerData.AccountNameNep);
                        $('#editReportCode').val(res.ledgerData.ReportCode);
                        $('#editfldstatus option[value='+res.ledgerData.fldstatus+']').attr('selected','selected');
                        $('#editAccountId').val(accountId);
                        $('#editledgerModal').modal('show');
                    }
                }
            });
        });

        $(document).on('click','.deleteLedger',function(){
            if(!confirm("Delete?")){
               return false;
            }
            var accountId = $(this).attr('data-accountid');
            $.ajax({
                type : 'get',
                url  : '{{ route("accounts.ledger.delete") }}',
                dataType : 'json',
                data : {
                '_token': '{{ csrf_token() }}',
                'AccountId': accountId
                },
                success: function (res) {
                    if(res.status){
                        showAlert(res.message);
                        $('#ledger-lists').html(res.html);
                    }
                }
            });
        });

        $(document).on('click','.changeStatus',function(){
            var accountId = $(this).attr('data-accountid');
            var current = $(this);
            if($(this).html() == 'Active'){
                var fldstatus = 0;
                var updateText = "Inactive";
                var updateClass = "btn-outline-danger";
            }else{
                var fldstatus = 1;
                var updateText = "Active";
                var updateClass = "btn-outline-success";
            }
            $.ajax({
                type : 'get',
                url  : '{{ route("accounts.ledger.changeStatus") }}',
                dataType : 'json',
                data : {
                '_token': '{{ csrf_token() }}',
                'fldstatus': fldstatus,
                'AccountId': accountId
                },
                success: function (res) {
                    if(res.status){
                        current.html(updateText);
                        if(updateClass == "btn-outline-danger"){
                            current.removeClass('btn-outline-success');
                            current.addClass('btn-outline-danger');
                        }else{
                            current.removeClass('btn-outline-danger');
                            current.addClass('btn-outline-success');
                        }
                    }
                }
            });
        });

        $('#GroupId').on('change', function(){
            var accountcode = $(this).val();
            $.ajax({
                url: '{{ route("accounts.ledger.getAccountNumber") }}',
                type: "POST",
                data: {accountcode:accountcode},
                success: function (response) {
                    $('#AccountNo').val(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        })
    </script>
    @endpush
