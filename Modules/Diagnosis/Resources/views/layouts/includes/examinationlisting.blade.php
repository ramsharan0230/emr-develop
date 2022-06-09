<div class="dietarytable res-table">

    @php $exams = \App\Utils\Diagnosishelpers::getAllExams(); @endphp
    
    <table class="table table-hover table-bordered table-striped mt-1 table-sm datatable-examination">
        
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        <tbody id="examinationlistingbody">
            @forelse($exams as $exam)
            <tr>
                <td>{{ $exam->fldexamid  }}</td>
                <td>
                    <a type="button" href="{{ route('examination.editexamination', encrypt($exam->fldexamid)) }}"  title="edit {{ $exam->fldexamid  }}" class="text-primary h5">
                        <i class="ri-edit-2-fill"></i>
                    </a>
                </td>
                <td><a type="button" title="delete {{ $exam->fldexamid  }}" class="text-danger h5" href="{{ route('examination.deleteexamination', encrypt($exam->fldexamid)) }}"><i class="ri-delete-bin-5-fill"></i></a></td>
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>

<div class="form-group padding-none">
    <div class="form-inner">
        {{ $exams->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<script>
    var table = $('table.datatable-examination').DataTable({
        "paging": false
    });
    $(function() {
        $('#searchexamination').keyup(function() {
            var searchkeyword = $(this).val();
           
            $.ajax({
                url: '{{ route('examination.examination.listing.search') }}',
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
                        $('#examinationlistingbody').html(res.html);
                    }
                }
            });
        });
    });
</script>
