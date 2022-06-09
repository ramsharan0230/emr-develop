<div class="modal fade" id="bed-list-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bed List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bed-list" id="zxc">
                    <!-- <div class="floor mt-2">
                        <label for=""class="breadcrumb iq-bg-primary mb-0 p-2"><b>Ground floor mt-2</b></label>
                        <div class="row">
                            <div class="col-12 form-group mt-2">
                                <h6 class="p-1">Neuro-icu</h6>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-12 form-group mt-2">
                                <h6 class="p-1">Pathology</h6>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="floor mt-2">
                        <label for=""class="breadcrumb iq-bg-primary mb-0 p-2"><b>First floor</b></label>
                        <div class="row">
                            <div class="col-12 form-group mt-2">
                                <h6 class="p-1">Dermetologist</h6>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-12 form-group mt-2">
                                <h6 class="p-1">Dental</h6>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                            </div>
                        </div>
                    </div> -->
                    {{-- @php
                        $bedData = Helpers::getDepartmentBed();
                        $departmentBed = $bedData['departmentBedList'];
                        $departmentBedOrder = $bedData['departmentFloor'];
                        $floorkeyArray = [];
                        $departmentArray = [];
                    @endphp

                    @if($departmentBed)
                        <div class="row m-0">
                            @forelse($departmentBedOrder as $order)
                                @forelse($departmentBed->where('fldfloor', $order->name)->all() as $floorKey)
                                    @if(!in_array($floorKey->fldfloor, $floorkeyArray) || !in_array($floorKey->flddept, $departmentArray))
                                        @php
                                            array_push($floorkeyArray, $floorKey->fldfloor);
                                            array_push($departmentArray, $floorKey->flddept);
                                        @endphp
                                        <div class="col-12 p-0">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb iq-bg-primary mb-0 p-2">
                                                    <li class="breadcrumb-item">
                                                        {{ $floorKey->fldfloor }}
                                                    </li>
                                                    <li class="breadcrumb-item">
                                                        {{ $floorKey->flddept }}
                                                    </li>
                                                </ol>
                                            </nav>
                                        </div>
                                    @endif
                                    <div class="col-sm-1 text-center">
                                        @if($floorKey['fldencounterval'] == "")
                                            <img src="{{ asset('new/images/bed-1.png')}}" class="img-bed" alt=""/>
                                        @else
                                            <img data-toggle="popover" data-trigger="hover" data-html="true" src="{{ asset('new/images/bed-occupied.png')}}" class="img-bed bedDesc" data-bed="{{ $floorKey->fldbed }}" data-encounter-id="{{ $floorKey->fldencounterval }}" alt="{{ $floorKey['fldencounterval'] }}" onMouseOver="this.style.cursor='pointer'"/>
                                        @endif
                                        <p>{{ $floorKey['fldbed'] }}</p>
                                    </div>
                                @empty
                                @endforelse
                            @empty
                            @endforelse
                        </div>
                @endif --}}
                <!-- first foor ends -->

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>
        </div>
    </div>
</div>
