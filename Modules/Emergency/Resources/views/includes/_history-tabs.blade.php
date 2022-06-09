<div class="col-sm-6">
    <input type="hidden" name="note_tabs" class="note_tabs" value="{{ route('save_note_tabs_emergency') }}"/>
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="history-tab">
                <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#advice" role="tab" aria-controls="advice" aria-selected="false">Advice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fluid" role="tab" aria-controls="fluid" aria-selected="false">Fluid</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent-3">
                    <div class="tab-pane fade show active" id="history" role="tabpanel" aria-labelledby="history">
                        <div class="iq-card-header d-flex justify-content-between">
                            <h5 class="card-title">History Of Illness</h5>
                            <button type="button" class="btn btn-sm btn-primary mb-3 save_history {{ $disableClass }}" old_id="@if(isset($history)) {{ $history->fldid }} @endif"><i class="fas fa-check pr-0"></i></button>

                        </div>
                        <div class="form-group mb-0">
                            <!-- @if(isset($past_history))
                            <textarea class="form-control" cols="60" readonly>

                                @foreach($past_history as $previous)
                                {{ $previous->flddetail }}
                                @endforeach

                            </textarea>
                            @endif -->
                            <textarea class="form-control textarea-md" name="history" id="history_emergency">
                              @if(isset($history)) {{ $history->flddetail }} @endif
                          </textarea>
                      </div>
                  </div>
                  <div class="tab-pane fade" id="advice" role="tabpanel" aria-labelledby="advice">
                    <div class="iq-card-header d-flex justify-content-between">
                        <h5 class="card-title">Advice</h5>
                        <button type="button" class="btn btn-sm btn-primary mb-3 save_advice {{ $disableClass }}" old_id="@if(isset($advice)) {{ $advice->fldid }} @endif"><i class="fas fa-check pr-0"></i></button>
                        {{-- <a href="#" class="btn btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></a> --}}
                    </div>
                    <div class="form-group mb-0">
                        <textarea class="form-control textarea-md" name="advice" id="advice_emergency">
                            @if(isset($advice)) {{ $advice->flddetail }} @endif
                        </textarea>
                    </div>
                </div>
                <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes">
                    <div class="iq-card-header d-flex justify-content-between">
                        <label class="card-title col-10">Notes</label>
                        <a href="javascript:;" class="btn btn-sm btn-primary mb-3 update_note_emergency {{ $disableClass }}"  url="{{ route('update.note.emergency') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></a>
                        <button type="button" data-toggle="modal" data-target="#note-showall-emergency" class="btn btn-sm btn-warning mb-3" title="Show All"><i class="fas fa-list pr-0"></i></button>

                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-12">
                            <select id="dropdown_note_emergency" class="form-control">
                                <option value="Progress Note" selected=selected>Progress Note</option>
                                <option value="Clinicians Note">Clinicians Note</option>
                                <option value="Nurses Note">Nurses Note</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <input type="hidden" id="note_emergency_fldid" value="">
                        <textarea class="form-control textarea-md" name="note" id="note_emergency">
                            @if(isset($note)) {{ $note->flddetail }} @endif
                        </textarea>
                    </div>
                    <div class="modal fade" id="note-showall-emergency">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">All Notes</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>




                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="row append-all-notes">
                                        @if(isset($progress_note))
                                        <div class="col-md-12">
                                            <h3 class="all-note-title">Progress Note</h3>
                                            @foreach($progress_note as $p)


                                            <p class="all-note-paragraph">{{ strip_tags($p->flddetail) }}</p>

                                            @endforeach
                                        </div>
                                        @endif
                                        @if(isset($clinic_note))
                                        <div class="col-md-12">
                                            <h3 class="all-note-title">Clinicians Note</h3>
                                            @foreach($clinic_note as $c)


                                            <p class="all-note-paragraph">{{ strip_tags($c->flddetail) }}</p>

                                            @endforeach
                                        </div>
                                        @endif
                                        @if(isset($nurse_note))
                                        <div class="col-md-12">
                                            <h3 class="all-note-title">Nurses Note</h3>
                                            @foreach($nurse_note as $n)


                                            <p class="all-note-paragraph">{{ strip_tags($n->flddetail) }}</p>

                                            @endforeach
                                        </div>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="fluid" role="tabpanel" aria-labelledby="fluid">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h5 class="card-title">IV Fluid</h5>
                        </div>
                    </div>
                    <div class="history-tab-content">
                        <table class="table table-hovered table-bordered table-striped mb-3">
                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>Medicine</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($fluid_list) && $fluid_list)

                                @forelse($fluid_list as $fluid)
                                <tr>
                                    <td>{{ $fluid->fldstarttime ?? null }}</td>
                                    <td>{{ $fluid->flditem ?? null }}</td>
                                    <td class="text-center"><a type="button " title="Start" class="btn check_btn prevent fluid_button {{ $disableClass }}" data-toggle="modal" data-id="{{ $fluid->fldid  }}" data-target="#fluidModal" id="fluid_start_btn" data-medicine="{{ $fluid->flditem }}" data-dose="{{ $fluid->flddose  }}" data-frequency=" {{ $fluid->fldfreq }}" data-days=" {{ $fluid->flddays }} " data-status=" {{ $fluid->fldstatus }} " data-start_time=" {{ $fluid->fldstarttime }}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <a type="button " class="btn check_btn prevent" style="display: none;" id="fluid_pause_btn" title="Pause">
                                        <i class="fas fa-pause"></i>
                                    </a>
                                    <a type="button " class="btn check_btn prevent" style="display: none;" id="fluid_stop_btn" title="Stop">
                                        <i class="fas fa-stop"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3"> There is no fluid dispensed!!</td>
                            </tr>
                            @endforelse
                            @else
                            <tr>
                                <td colspan="3"> There is no fluid dispensed!!</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="table table-hovered table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Particulars</th>
                                <th>Rate</th>
                                <th>Unit</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="fluid_particulars_body">
                            @if(isset($fluid_particulars))
                            @forelse( $fluid_particulars as $particulars)
                            <tr>
                                <td>{{ $particulars->getName->flditem ?? null }}</td>
                                <td>{{ $particulars->fldvalue ?? null }}</td>
                                <td>{{ $particulars->fldunit ?? null }}</td>
                                <td>{{ $particulars->fldfromtime ?? null }}</td>
                                <td>{{ $particulars->fldtotime ?? null }}</td>
                                <td>
                                    @if( $particulars->fldstatus =='ongoing')
                                    <button type="button" class="fluid_stop_btn" data-stop_id="{{ $particulars->fldid ?? null }}" data-dose_no="{{ $particulars->flddose ?? null }}"><i class="fas fa-stop"></i></button>
                                    @elseif( $particulars->fldstatus =='stopped')
                                    <button type="button"><i class="fas fa-lock"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty

                            @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="fluidModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="fluid_title"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;
                                </button>

                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Start Date</th>
                                        <th>Medicine</th>
                                        <th>Dose</th>
                                        <th>Frequency</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th>--}}
                                    </thead>
                                    <tbody id="fluid_table_body"></tbody>
                                    {{-- <tr>--}}
                                        {{-- <td>--}}
                                            {{-- <input type="text" class="form-control"--}}
                                            {{-- placeholder="">--}}
                                        {{-- </td>--}}
                                        {{-- <td>--}}
                                            {{-- <label for="">ml/Hr</label>--}}
                                        {{-- </td>--}}
                                    {{-- </tr>--}}
                                </table>
                                <table>
                                    <tr>
                                        <td><label>Enter rate of Administration in ML/Hour: </label>
                                        </td>
                                        <td><input type="text" class="form-control" id="fluid_dose">
                                        </td>
                                        <td><label id="empty_dose_alert" style="color: red;"></label></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="fluid_modal_save_btn">Save
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>
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