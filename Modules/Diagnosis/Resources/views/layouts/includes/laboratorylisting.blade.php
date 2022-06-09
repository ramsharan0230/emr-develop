<div class="res-table">
    <table class="table table-bordered table-hover table-striped">
        <tbody>
        @php $tests = \App\Utils\Diagnosishelpers::getAlltheTests(); @endphp
        @forelse($tests as $test)
            <tr>
                <td class="dietary-td" width="80%">{{ $test->fldtestid }}</td>
                <td class="dietary-td" width="15%">
                    <a type="button" href="{{ route('diagnostictest.edit', encrypt($test->fldtestid)) }}"  title="edit {{ $test->fldtestid }}">
                        <i class="fa fa-edit"></i>
                    </a>&nbsp;&nbsp;
                    <a type="button" title="delete {{ $test->fldtestid }}" class="deletediagnostictest" data-href="{{ route('diagnostictest.delete', encrypt($test->fldtestid)) }}"><i class="far fa-trash-alt"></i></a>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>

<div class="form-group padding-none">
    <div class="form-inner">
        {{ $tests->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
