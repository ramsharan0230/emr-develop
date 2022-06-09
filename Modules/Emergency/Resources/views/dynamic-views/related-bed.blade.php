<div class="bed-list" id="transfer-bed-list">

    @php
    $departmentBed = $get_related_data->groupBy('fldfloor');
    $floorkeyArray = [];
    $departmentArray = [];
    @endphp

    @if(count($departmentBed))
    @forelse($departmentBed as $floorKey => $floor)
    <div class="row m-0">
        @forelse($floor as $bed)
        @if(!in_array($floorKey, $floorkeyArray))
        @php
        array_push($floorkeyArray,$floorKey)
        @endphp
        <div class="col-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb iq-bg-primary mb-0">
                    <li class="breadcrumb-item"><a href="#">
                        <i class="fas fa-home"></i>
                        @if(!in_array($bed->flddept, $departmentArray))
                        @php
                        array_push($departmentArray,$bed->flddept)
                        @endphp
                        {{ $bed->flddept }}
                        @endif</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">
                            {{ $floorKey }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-8"></div>
        @endif

        <div class="col-sm-2 text-center" data-bedid="{{ $bed->fldbed }}">
            @if($bed->fldencounterval == "")
            <div class="empty-bed">
                <input type="radio" name="department_bed" id="{{ $bed->fldbed }}" value="{{ $bed->fldbed }}" style="display:none" />
                <label for="{{ $bed->fldbed }}"> <img style="cursor: pointer;" src="{{ asset('new/images/bed-1.jpg')}}" class="img-bed" alt=""/>
                </label>
            </div>
            @else

            <label for="{{ $bed->fldbed }}">
                <img src="{{ asset('new/images/bed-occupied.png')}}" class="img-bed" alt="{{ $bed->fldencounterval }}" title="{{ $bed->fldencounterval }}" />
            </label>
            @endif
            <p>{{ $bed->fldbed }}</p>
        </div>
        @empty
        @endforelse
    </div>
    @empty
    @endforelse
    @endif
    <!-- first foor ends -->

</div>
