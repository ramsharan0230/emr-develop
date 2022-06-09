<div class="col-sm-4">
  <div class="iq-card iq-card-block  iq-card-stretch iq-card-height">
    <div class="iq-card-header p-1 d-flex justify-content-between">
      <div class="iq-header-title d-flex align-items-center">
        <h4 class="card-title">Allergy</h4>
{{--        <div class="profile-form custom-control custom-checkbox custom-control-inline ml-2">--}}
{{--          <input type="checkbox" class="custom-control-input" id="customCheck5">--}}
{{--          <label class="custom-control-label" for="customCheck5">Enc--}}
{{--          </label>--}}
{{--      </div>--}}
  </div>
  <div class="allergy-add">
    @if(isset($enable_freetext) and $enable_freetext == 1)
    <a href="javascript:void(0);" class="iq-bg-primary mr-1" onclick="allergyfreetextEmergency.displayModal()"><i class="ri-add-fill"></i></a>
    @else
    <a href="javascript:void(0);" class="iq-bg-secondary mr-1"><i class="ri-add-fill"></i></a>
    @endif
    <a href="javascript:void(0);" class="iq-bg-primary mr-1" data-toggle="modal" data-target="#allergicdrugs-emergency"><i class="ri-add-fill"></i></a>
    <a href="javascript:void(0);" id="deletealdrug-emergency" class="iq-bg-danger mr-1"><i class="ri-delete-bin-5-fill"></i></a>
            <!-- <a href="#" class="iq-bg-primary"><i class="ri-add-fill"></i></a>
            <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
            <a href="#" class="iq-bg-danger"><i class="ri-delete-bin-5-fill"></i></a> -->
        </div>
    </div>
    <div class="iq-card-body">

        <div class="form-group mb-0">
          <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('emergency.deletepatfinding') }}"/>
          <select name="" id="select-multiple-aldrug-emergency" class="form-control" multiple>

            @if(isset($patdrug) && count($patdrug) > 0)
            @foreach($patdrug as $pd)
            <option value="{{$pd->fldid}}">{{$pd->fldcode}} </option>
            @endforeach
            @else
            <option value="">No Allergic Drugs Found</option>
            @endif
        </select>
    </div>
    
</div>
</div>
</div>
<div class="modal fade" id="allergicdrugs-emergency" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="emergencyallergyform">
                @csrf
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
                                <input type="text" name="searchdrugs" class="form-control" id="searchdrugs-emergency"><br/>
                                <!-- <div id="searchresult"></div> -->
                                <div id="allergicdrugss-emergency">
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
                        <button type="button" class="btn btn-primary" onclick="saveEmergencyAllergyDrugs()">Save</button>
                        <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Save changes"> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="allergy-freetext-modal-emergency">
      <div class="modal-dialog ">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <h4 class="modal-title">Allergic Drugs</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <!-- Modal body -->
              <div class="form-data-allergy-freetext"></div>
          </div>
      </div>
  </div>

  <script type="text/javascript">
    $('#deletealdrug-emergency').on('click', function() {
        if (confirm('Delete Allergy??')) {
            $('#select-multiple-aldrug-emergency').each(function() {
                var finalval = $(this).val().toString();
                var url = $('.delete_pat_findings').val();

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: { ids: finalval },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Data Deleted!!');
                            $('#select-multiple-aldrug-emergency option:selected').remove();
                        } else
                        showAlert('Something went wrong!!');
                    }
                });
            });
        }
    });
    function saveEmergencyAllergyDrugs(){
        // alert('add allergy drugs');
        
        var url = "{{route('emergency.allergydrugstore')}}";
        $.ajax({
            url: url,
            type: "POST",
            data:  $("#emergencyallergyform").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-aldrug-emergency').empty().append(response);
                $('#allergicdrugs-emergency').modal('hide');
                showAlert('Data Added !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>