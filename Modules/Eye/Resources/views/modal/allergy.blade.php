<div id="allergy" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card-header d-flex ">
        <div class="iq-header-title d-flex align-items-center">
            <h4 class="card-title">Allergy</h4>
            <div class="custom-control custom-checkbox ml-4">
                <input id="enc" type="checkbox" class="custom-control-input">
                <label for="enc" class="custom-control-label">All Enc</label>
            </div>
        </div>
        <div class="allergy-add ml-4">

            @if(isset($enable_freetext) and $enable_freetext == 1)
                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary" data-toggle="modal" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()"><i class="ri-add-fill"></i></a>
            @else
                <a href="javascript:void(0);"><i class="ri-add-fill iq-bg-primary"></i></a>
            @endif
            <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary" data-toggle="modal" data-target="#allergicdrugs"><i class="ri-add-fill"></i></a>
            <a href="javascript:void(0);" id="deletealdrug" class="{{ $disableClass }} iq-bg-danger"><i class="ri-delete-bin-5-fill"></i></a>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
        <select name="" id="select-multiple-aldrug" multiple class="form-control">
            @if(isset($patdrug) && count($patdrug) >0)
                @foreach($patdrug as $pd)
                    <option value="{{$pd->fldid}}">{{$pd->fldcode}} <a class="right_del" href="{{route('deletepatfinding',$pd->fldid)}}" onclick="return confirm('Are you sure you want to delete this Allergic Drug?');"><i class="fas fa-trash-alt"></i></a></option>
                @endforeach
            @else
                <option value="">No Allergic Drugs Found</option>
            @endif
        </select>
    </div>
</div>

<div class="modal fade" id="allergicdrugs" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="" id="allergyform">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                <div class="modal-header">
                    <h5 class="modal-title" id="allergicdrugsLabel" style="text-align: center;">Select Drugs</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow-y: scroll; height:400px;">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <input type="text" name="searchdrugs" class="form-control" id="searchdrugs"><br/>
                                <!-- <div id="searchresult"></div> -->
                                <div id="allergicdrugss" class="res-table">
                                    @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
                                        @foreach($allergicdrugs as $ad)
                                            <li class="list-group-item"><input type="checkbox" value="{{$ad->fldcodename}}" class="fldcodename" name="allergydrugs[]"/>&nbsp; {{$ad->fldcodename}}</li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item">No Drugs Available</li>
                                    @endif
                                </div>
                            </ul>
                        </div>
                        <!-- <div class="col-md-2 modal_container">
                            <p>Filter</p>
                            <ul class="list-unstyled side_list" style="width:45px;">
                                <li><input type="checkbox" name="alpha" value="A" class="alphabet"/>&nbsp;A</li>
                                <li><input type="checkbox" name="alpha" value="B" class="alphabet"/>&nbsp;B</li>
                                <li><input type="checkbox" name="alpha" value="C" class="alphabet"/>&nbsp;C</li>
                                <li><input type="checkbox" name="alpha" value="D" class="alphabet"/>&nbsp;D</li>
                                <li><input type="checkbox" name="alpha" value="E" class="alphabet"/>&nbsp;E</li>
                                <li><input type="checkbox" name="alpha" value="F" class="alphabet"/>&nbsp;F</li>
                                <li><input type="checkbox" name="alpha" value="G" class="alphabet"/>&nbsp;G</li>
                                <li><input type="checkbox" name="alpha" value="H" class="alphabet"/>&nbsp;H</li>
                                <li><input type="checkbox" name="alpha" value="I" class="alphabet"/>&nbsp;I</li>
                                <li><input type="checkbox" name="alpha" value="J" class="alphabet"/>&nbsp;J</li>
                                <li><input type="checkbox" name="alpha" value="K" class="alphabet"/>&nbsp;K</li>
                                <li><input type="checkbox" name="alpha" value="L" class="alphabet"/>&nbsp;L</li>
                                <li><input type="checkbox" name="alpha" value="M" class="alphabet"/>&nbsp;M</li>
                                <li><input type="checkbox" name="alpha" value="N" class="alphabet"/>&nbsp;N</li>
                                <li><input type="checkbox" name="alpha" value="O" class="alphabet"/>&nbsp;O</li>
                                <li><input type="checkbox" name="alpha" value="P" class="alphabet"/>&nbsp;P</li>
                                <li><input type="checkbox" name="alpha" value="Q" class="alphabet"/>&nbsp;Q</li>
                                <li><input type="checkbox" name="alpha" value="R" class="alphabet"/>&nbsp;R</li>
                                <li><input type="checkbox" name="alpha" value="S" class="alphabet"/>&nbsp;S</li>
                                <li><input type="checkbox" name="alpha" value="T" class="alphabet"/>&nbsp;T</li>
                                <li><input type="checkbox" name="alpha" value="U" class="alphabet"/>&nbsp;U</li>
                                <li><input type="checkbox" name="alpha" value="V" class="alphabet"/>&nbsp;V</li>
                                <li><input type="checkbox" name="alpha" value="W" class="alphabet"/>&nbsp;W</li>
                                <li><input type="checkbox" name="alpha" value="X" class="alphabet"/>&nbsp;X</li>
                                <li><input type="checkbox" name="alpha" value="Y" class="alphabet"/>&nbsp;Y</li>
                                <li><input type="checkbox" name="alpha" value="Z" class="alphabet"/>&nbsp;Z</li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveAllergyDrugs()">Save</button>
                    <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Save changes"> -->
                </div>
            </form>
        </div>
    </div>
</div>

@include('outpatient::modal.allergy-freetext-modal')

@push('after-script')
    <script type="text/javascript">
        var allergy = {
            displayModal: function (encId) {
                $('#js-allergy-modal').modal('show');
            },
        }
    </script>
    <script type="text/javascript">
        $('#deletealdrug').on('click', function () {
            // e.preventDefault();
            $('#select-multiple-aldrug').each(function () {
                if (confirm('Are you sure you want to delete?')) {
                    var finalval = $(this).val().toString();
                    var url = $('.delete_pat_findings').val();
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: {ids: finalval},
                        success: function (data) {
                            if ($.isEmptyObject(data.error))
                                $('#select-multiple-aldrug option:selected').remove();
                            else
                                showAlert('Something went wrong!!');
                        }
                    });
                }
            });
        });

        $(document).on('click', '#submitallergydrugs', function (e) {
            e.preventDefault();

            var data = $(this).closest('form').serialize();
            var allData = $(this).closest('form').serializeArray();
            var url = $(this).closest('form').attr('action');

            $.ajax({
                url: url,
                type: "POST",
                // dataType: "json",
                data: data,
                success: function (data) {
                    $.each(allData, function (i, val) {
                        if (val.name == 'allergydrugs[]')
                            $('#select-multiple-aldrug').append('<option value="0">' + val.value + '</option>');
                    });
                    $('#allergicdrugs').modal('hide');
                    showAlert("Data updated Successfully.");
                },
                error: function (data) {
                    showAlert("Failed to update data.");
                }
            });
        });
    </script>
@endpush
