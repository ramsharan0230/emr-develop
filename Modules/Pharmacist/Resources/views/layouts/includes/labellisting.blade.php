<!-- <div class="row">
    <div class="col-md-12">
        <div class="table-scroll-md table-responsive">
            <table class="table table-sm">
                <thead>
                <th class="tittle-th">Code</th>
                <th class="tittle-th">English</th>
                <th class="tittle-th">Local</th>
                <th class="tittle-th">Action</th>
                </thead>
                <tbody>
                    @php $locallabels = \App\Utils\Pharmacisthelpers::getLocalLabel(); @endphp

                    @forelse($locallabels as $locallabel)
                        <tr>
                            <td>{{ $locallabel->fldengcode }}</td>
                            <td>{{ $locallabel->fldengdire }}</td>
                            <td>{{ $locallabel->fldlocaldire }}</td>
                            <td class="text-center">
                                <a type="button" href="{{ route('pharmacist.labelling.editlocallabels', $locallabel->fldid) }}" style="margin-left: 15px;" title="edit {{ $locallabel->fldengcode }}">
                                    <i class="ri-edit-fill"></i>
                                </a>
                                <button title="delete {{ $locallabel->fldengcode }}" class="deletelabel" data-href="{{ route('pharmacist.labelling.deletelocallabels', $locallabel->fldid) }}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div> -->
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="table-responsive table-container">
                <table class="table table-striped table-bordered table-hover ">
                    <thead class="thead-light">
                        <tr><th class="tittle-th">Code</th>
                            <th class="tittle-th">English</th>
                            <th class="tittle-th">Local</th>
                            <th class="tittle-th">Action</th>
                        </tr></thead>
                        <tbody id="labelListing">
                            @php $locallabels = \App\Utils\Pharmacisthelpers::getLocalLabel()->where('fldlabeltype',"essential"); @endphp

                            @forelse($locallabels as $locallabel)
                            <tr>
                                <td>{{ $locallabel->fldengcode }}</td>
                                <td>{{ $locallabel->fldengdire }}</td>
                                <td>{{ $locallabel->fldlocaldire }}</td>
                                <td class="text-center">
                                    {{-- href="{{ route('pharmacist.labelling.editlocallabels', $locallabel->fldid) }}" --}}
                                    <a type="button" href="#" data-fldid="{{ $locallabel->fldid }}" style="margin-left: 15px;" title="edit {{ $locallabel->fldengcode }}" class="btn btn-warning btn-sm-in editLabel">
                                        <i class="ri-edit-fill"></i>
                                    </a>
                                    <button title="delete {{ $locallabel->fldengcode }}"  data-href="{{ route('pharmacist.labelling.deletelocallabels', $locallabel->fldid) }}" class="btn btn-danger btn-sm-in deletelabel"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    <div id="bottom_anchor"></div>
                </div>
            </div>
        </div>
    </div>
