<div class="dietarytable overflow-auto container">
    <table class="dietary-table">
        <tbody>
        @forelse($brands as $brand)
            <tr>
                <td class="dietary-td" width="85%">{{ $brand->fldbrandid  }}</td>
                <td class="dietary-td" width="15%">
                    <a type="button" href="{{ route('medicines.medicineinfo.editbrandinfo',  encrypt($brand->fldbrandid) ) }}"  title="edit label {{ $brand->fldbrandid }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button title="delete label {{ $brand->fldbrandid  }}" class="deletebrand" data-href="{{ route('medicines.medicineinfo.deletebrandinfo', ['fldbrandid ' => encrypt($brand->fldbrandid) ]) }}"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>

@if(count($brands) > 0)
    <div class="form-group padding-none">
        <div class="form-inner">
            {{ $brands->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endif
