@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Storage Coding</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="fldcategory" value="Medicines" checked class="custom-control-input">
                                    <label class="custom-control-label">Medicines</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="fldcategory" value="Surgicals" class="custom-control-input">
                                    <label class="custom-control-label">Surgicals</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="fldcategory" value="Extra Items" class="custom-control-input">
                                    <label class="custom-control-label">Extra Items</label>
                                </div>
                                <button class="btn btn-primary" id="js-storecodding-refresh-btn"><i class="ri-refresh-line"></i> Refresh</button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row align-items-center">
                                <div class="col-sm-7">
                                    <input type="text" readonly id="js-storecodding-fldstockid-input" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" id="js-storecodding-fldcode-input" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-warning" id="js-storecodding-update-btn"><i class="ri-edit-2-fill"></i> Edit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive table-container">
                                <table class="table table-hover table-bordered table-striped ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>StockID</th>
                                            <th>Particulars</th>
                                            <th>Batch</th>
                                            <th>Code</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-storecodding-medicine-tbody">
                                        @foreach($medicines as $medicine)
                                        <tr data-fldstockno="{{ $medicine->fldstockno }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $medicine->fldstockno }}</td>
                                            <td>{{ $medicine->fldstockid }}</td>
                                            <td>{{ $medicine->fldbatch }}</td>
                                            <td>{{ $medicine->fldcode }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $(document).on('click', '#js-storecodding-medicine-tbody tr', function() {
        selected_td('#js-storecodding-medicine-tbody tr', this);

        $('#js-storecodding-fldstockid-input').val($(this).find('td:nth-child(3)').text().trim());
        $('#js-storecodding-fldcode-input').val($(this).find('td:nth-child(5)').text().trim());
    });

    $('#js-storecodding-refresh-btn').click(function() {
        var fldcategory = $('input[type="radio"][name="fldcategory"]:checked').val();
        $('#js-storecodding-fldstockid-input').val('');
        $('#js-storecodding-fldcode-input').val('');
        if (fldcategory != '') {
            $.ajax({
                url: baseUrl + "/store/storeCoding/getMedicines",
                type: "GET",
                data: {
                    fldcategory: fldcategory
                },
                dataType: "json",
                success: function (response) {
                    var trData = '';
                    $.each(response, function(i, elem) {
                        trData += '<tr data-fldstockno="' + elem.fldstockno + '">';
                        trData += '<td>' + (i+1) + '</td>';
                        trData += '<td>' + elem.fldstockno + '</td>';
                        trData += '<td>' + elem.fldstockid + '</td>';
                        trData += '<td>' + elem.fldbatch + '</td>';
                        trData += '<td>' + elem.fldcode + '</td>';
                        trData += '</tr>';
                    });
                    $('#js-storecodding-medicine-tbody').empty().html(trData);
                }
            });
        }
    });

    $('#js-storecodding-update-btn').click(function() {
        var selectedTr = $('#js-storecodding-medicine-tbody tr[is_selected="yes"]');
        var fldcode = $('#js-storecodding-fldcode-input').val() || '';
        var fldstockno = $(selectedTr).data('fldstockno') || '';

        if (fldcode != '' && fldstockno != '') {
            $.ajax({
                url: baseUrl + "/store/storeCoding/update",
                type: "POST",
                data: {
                    fldcode: fldcode,
                    fldstockno: fldstockno
                },
                dataType: "json",
                success: function (response) {
                    var status = (response.status) ? 'success' : 'fail';
                    if (response.status)
                        $(selectedTr).find('td:nth-child(5)').text(fldcode);
                    showAlert(response.message, status);
                }
            });
        }
    });
</script>
@endpush
