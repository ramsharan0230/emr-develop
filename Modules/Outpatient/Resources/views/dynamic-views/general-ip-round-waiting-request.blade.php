@if(count($patBillingPunched))
    @foreach($patBillingPunched as $bill)
        <tr>
            <td>
                <input type="checkbox" name="services-request-check[]" value="{{ $bill->fldid }}">
            </td>
            <td>
                <input type="hidden" name="fldid-request[]" value="{{ $bill->fldid }}">
                {{ $bill->fldordtime }}
            </td>
            <td>
                {{ $bill->flditemname }}
            </td>
            <td>
                <input type="hidden" name="flditemno-request[]" value="{{ $bill->flditemno }}">
                <input type="number" style="width: 55px;" class="service_quantity" name="service_quantity[]" min="1" value="{{ isset($bill->flditemqty) ? $bill->flditemqty : 1 }}">
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
<script>
    $(function() {
        $('.select2').select2();
        $('.select-doctors').on('select2:select', function(e) {
            e.preventDefault();
            let fldid = $(this).data('id');
            // save to patbilling share table.
            let doc_id = e.params.data.id;
            let url = '{{ route("patient.ip-round.form.save.doc-share") }}';
            $.ajax({
                url: url,
                type: 'POST',
                async: true,
                data: { user_id: doc_id, bill_id: fldid},
                success: function(res) {
                    if (res.success) {
                        showAlert(res.message);
                    }
                }
            });
        });

        $('.select-doctors').on('select2:unselect', function(e) {
            // remove doctor from pat billing share
            let doc_id = e.params.data.id;
            let fldid = $(this).data('id');
            let url = '{{ route("patient.ip-round.form.remove.doc-share") }}';
            $.ajax({
                url: url,
                type: 'POST',
                async: true,
                data: { user_id: doc_id, bill_id: fldid},
                success: function(res) {
                    if (res.success) {
                        showAlert(res.message);
                    }
                }
            });
        });
    });
</script>
