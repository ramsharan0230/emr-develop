<form action="javascript:;" id="billing-item-list-form">
    <div class="modal-body">

        <div class="row">
            <div class="col-md-12">

                <h8>Total Deposit: <b>{{$previousDeposit}}</b></h8>
                <br/>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Bill Number</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Rate</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($billitems) and !empty($billitems))
                        @foreach($billitems as $items)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$items->fldbillno}}</td>
                                <td>{{$items->flditemname}}</td>
                                <td>{{$items->flditemqty}}</td>
                                <td>{{$items->flditemrate}}</td>
                                <td>{{$items->fldditemamt}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No Item Available</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>Total Amt</b></td>
                        <td colspan="2">{{ $billitems->sum('fldditemamt')+$billitems->sum('flddiscamt') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>Discount Amt</b></td>
                        <td colspan="2">{{ $billitems->sum('flddiscamt') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>Paid Amt</b></td>
                        <td colspan="2">{{ $patbilldata->fldreceivedamt }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>Billable Amt</b></td>
                        <td colspan="2">{{ $patbilldata->fldcurdeposit }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>Receivable Amt</b></td>
                        <td colspan="2"><input type="text" name="received_amount" id="received_amount" class="form-control" value="{{ abs($patbilldata->fldcurdeposit) }}" style="width: 100px"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="encounter_id" value="{{ $encounter_id }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="button" id="submit-credit-clearance" class="btn btn-primary" value="Save">
    </div>
</form>

<script type="text/javascript">
    /*$(document).on('click', '#submitfreetextdiagnosis', function () {
        // alert('free allergic test');
        var postval = $('#custom_diagnosis').val();
        var encounter = $('#in_diag_enc').val();
        var fldinput = $('#in_diag_fldinput').val();
        if (postval != '') {
            var url = "";

            $.ajax({
                url: url,
                type: "POST",
                // dataType: "json",
                data: {custom_diagnosis: postval, encounter: encounter, fldinput: fldinput},
                success: function (data) {
                    // response.log()
                    // console.log(data)
                    $('#provisional_delete').empty().append(data);
                    $('#diagnosis-freetext-modal').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!');
                }
            });
        } else {
            showAlert('Data Not Found');
        }

    });*/

    $(document).ready(function (){
        $('#submit-credit-clearance').on('click', function () {
            $.ajax({
                url: '{{ route("billing.item.save") }}',
                type: "POST",
                // dataType: "json",
                data: $('#billing-item-list-form').serialize(),
                success: function (data) {
                    // response.log()
                    // showAlert('Data Added !!');
                    if ($.isEmptyObject(data.success)) {
                        showAlert('Data Added !!');
                        // location.reload(true);
                    } else
                        showAlert('Something went wrong!!');
                }
            });
        });
    })
</script>
