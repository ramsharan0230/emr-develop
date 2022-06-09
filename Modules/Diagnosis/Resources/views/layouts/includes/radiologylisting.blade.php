@php $radios = \App\Utils\Diagnosishelpers::getAlltheRadio(); @endphp
<table class="table-striped table-hover table datatable-radiology">

    <thead>
        <tr>
            <th>Name</th>
            <th></th>

        </tr>
    </thead>
    <tbody id="radiologylistingbody">

        @forelse($radios as $radio)
        <tr>
            <td class="dietary-td border-none" width="80%">{{ $radio->fldexamid }}</td>
            <td class="dietary-td border-none" width="15%">
                <a type="button" href="{{ route('radiodiagnostic.edit', encrypt($radio->fldexamid)) }}"  title="edit {{ $radio->fldexamid }}">
                    <i class="fa fa-edit"></i>
                </a>
                <a type="button" title="delete {{ $radio->fldexamid }}" class="deleteradiodiagnostictest" data-href="{{ route('radiodiagnostic.delete', encrypt($radio->fldexamid)) }}"><i class="far fa-trash-alt"></i></a >
            </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>
<div class="form-group padding-none">
    <div class="form-inner">
        {{ $radios->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<script>
    var table = $('table.datatable-radiology').DataTable({
        "paging": false
    });
    $(function() {
        $('#searchradiology').keyup(function() {
            var searchkeyword = $(this).val();

            $.ajax({
                url: '{{ route('radiodiagnostic.listing.search') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'searchkeyword' : searchkeyword,
                },
                success: function(res) {
                    if(res.message == 'error'){
                        alert(res.errormessage);
                    } else if(res.message == 'success') {
                        $('#radiologylistingbody').html(res.html);
                    }
                }
            });
        });
    });
</script>
