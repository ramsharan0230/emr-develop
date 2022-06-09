@extends('patient.layouts.master')

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="topspce">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="lab-bar">
                                <div class="card-header border-bottom">
                                    <h6 class="lab-titile">Pharmacy PatDosing</h6>
                                    <div class="block-handle"></div>
                                </div>

                                <div class="table-responsive">
                                    <form action="{{ route('patient.portal.pharmacy') }}" method="post" class="m-2">
                                        @csrf
                                        <div class="row">
                                            <div class="col-1">
                                                <label for="encounters">Encounter</label>
                                            </div>
                                            <div class="col-2">
                                                <select name="encounter" id="encounters" class="form-control">
                                                    <option value=""></option>
                                                    @if($encounters)
                                                        @forelse($encounters as $encounter)
                                                            <option value="{{ $encounter }}" {{ $selectedEncounter == $encounter ? "selected":"" }}>{{ $encounter }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>

                                    </form>
                                    <table class="table mb-0 table_styles">
                                        <thead class="bg-light">
                                        <tr>
                                            <th>SNo.</th>
                                            <th scope="col" class="border-0">Encounter</th>
                                            <th scope="col" class="border-0" style="width: 400px;">Dose</th>
                                            <th scope="col" class="border-0">Type</th>
                                            <th scope="col" class="border-0">Dose</th>
                                            <th scope="col" class="border-0">Freq</th>
                                            <th scope="col" class="border-0">Days</th>
                                            <th scope="col" class="border-0">Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($patDosings)
                                            @foreach($patDosings as $dose)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $dose->fldencounterval }}</td>
                                                    <td>{{ $dose->flditem }}</td>
                                                    <td>{{ $dose->flditemtype }}</td>
                                                    <td>{{ $dose->flddose }}</td>
                                                    <td>{{ $dose->fldfreq }}</td>
                                                    <td>{{ $dose->flddays }}</td>
                                                    <td>{{ $dose->fldqtydisp }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="form-group padding-none">
                                        <div class="form-inner">
                                            @if($patDosings)
                                                {{ $patDosings->links('vendor.pagination.bootstrap-4') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

