<div class="dietarytable overflow-auto container">
    <table class="dietary-table">
        <tbody>
        @forelse($labels as $label)
            <tr>
                <td class="dietary-td" width="85%">{{ $label->fldlabel }}</td>
                <td class="dietary-td" width="15%">
                    <a type="button" href="{{ route('medicines.medicineinfo.editlabel', encrypt($label->fldlabel)) }}"  title="edit label {{ $label->fldlabel}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button title="delete label {$label->fldlabel }}" class="deletelabel" data-href="{{ route('medicines.medicineinfo.deletelabel', ['flddrug' => encrypt($drug->flddrug), 'fldlabel' =>encrypt($label->fldlabel)]) }}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>

@if(count($labels) > 0)
    <div class="form-group padding-none">
        <div class="form-inner">
            {{ $labels->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endif
