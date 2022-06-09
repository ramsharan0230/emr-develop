<div class="modal fade" id="allergic_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="inpatient-allergy">
            <!-- @csrf -->
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" name="encounter_id" value="@if(isset($enpatient) and $enpatient !='') {{ $enpatient->fldencounterval }} @endif">
                    <h5 class="inpatient__modal_title" id="encounter_listLabel" style="text-align: center;">Select Drugs</h5>
                    <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group margin-top-inpatient margin-bottom-inpatient only-search">
                                <div class="search__text form-row">
                                    <input type="search" class="form-control col-10" name="searchdrugs" id="searchdrugs" class="search__field search__f_md" placeholder="Search Drugs....">
                                    <input type="checkbox" id="abc" class="col-2" name="allergydrugs[]" value="abc" class="fldcodename"/>
                                    <label class="remove_some_css" for="abc"></label>
                                </div>
                            </div>
                            <ul class="related__group" id="allergicdrugss" style="height: 400px; overflow-y: scroll;">
                                @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
                                @foreach($allergicdrugs as $ad)
                                <li class="list-group-item">
                                    <input type="checkbox" id="{{$ad->fldcodename}}" name="allergydrugs[]" value="{{$ad->fldcodename}}" class="fldcodename"/>
                                    <label class="remove_some_css" for="{{$ad->fldcodename}}"></label>{{$ad->fldcodename}}
                                </li>
                                @endforeach
                                @else
                                <li class="list-group-item">No Drugs Available</li>
                                @endif
                            </ul>
                        </div>
                        <!-- <div class="col-md-3" style="overflow-y: scroll; height:400px;">
                            <ul class="list-unstyled side_list" style="width:45px;">
                                <li><input type="checkbox" name="alpha" value="A" class="alphabet fldcodename" id="a"/><label class="remove_some_css" for="a"></label>&nbsp;A</li>
                                <li><input type="checkbox" name="alpha" value="B" class="alphabet fldcodename" id="b"/><label class="remove_some_css" for="b"></label>&nbsp;B</li>
                                <li><input type="checkbox" name="alpha" value="C" class="alphabet fldcodename" id="c"/><label class="remove_some_css" for="c"></label>&nbsp;C</li>
                                <li><input type="checkbox" name="alpha" value="D" class="alphabet fldcodename" id="d"/><label class="remove_some_css" for="d"></label>&nbsp;D</li>
                                <li><input type="checkbox" name="alpha" value="E" class="alphabet fldcodename" id="e"/><label class="remove_some_css" for="e"></label>&nbsp;E</li>
                                <li><input type="checkbox" name="alpha" value="F" class="alphabet fldcodename" id="f"/><label class="remove_some_css" for="f"></label>&nbsp;F</li>
                                <li><input type="checkbox" name="alpha" value="G" class="alphabet fldcodename" id="g"/><label class="remove_some_css" for="g"></label>&nbsp;G</li>
                                <li><input type="checkbox" name="alpha" value="H" class="alphabet fldcodename" id="h"/><label class="remove_some_css" for="h"></label>&nbsp;H</li>
                                <li><input type="checkbox" name="alpha" value="I" class="alphabet fldcodename" id="i"/><label class="remove_some_css" for="i"></label>&nbsp;I</li>
                                <li><input type="checkbox" name="alpha" value="J" class="alphabet fldcodename" id="j"/><label class="remove_some_css" for="j"></label>&nbsp;J</li>
                                <li><input type="checkbox" name="alpha" value="K" class="alphabet fldcodename" id="k"/><label class="remove_some_css" for="k"></label>&nbsp;K</li>
                                <li><input type="checkbox" name="alpha" value="L" class="alphabet fldcodename" id="l"/><label class="remove_some_css" for="l"></label>&nbsp;L</li>
                                <li><input type="checkbox" name="alpha" value="M" class="alphabet fldcodename" id="m"/><label class="remove_some_css" for="m"></label>&nbsp;M</li>
                                <li><input type="checkbox" name="alpha" value="N" class="alphabet fldcodename" id="n"/><label class="remove_some_css" for="n"></label>&nbsp;N</li>
                                <li><input type="checkbox" name="alpha" value="O" class="alphabet fldcodename" id="o"/><label class="remove_some_css" for="o"></label>&nbsp;O</li>
                                <li><input type="checkbox" name="alpha" value="P" class="alphabet fldcodename" id="p"/><label class="remove_some_css" for="p"></label>&nbsp;P</li>
                                <li><input type="checkbox" name="alpha" value="Q" class="alphabet fldcodename" id="q"/><label class="remove_some_css" for="q"></label>&nbsp;Q</li>
                                <li><input type="checkbox" name="alpha" value="R" class="alphabet fldcodename" id="r"/><label class="remove_some_css" for="r"></label>&nbsp;R</li>
                                <li><input type="checkbox" name="alpha" value="S" class="alphabet fldcodename" id="s"/><label class="remove_some_css" for="s"></label>&nbsp;S</li>
                                <li><input type="checkbox" name="alpha" value="T" class="alphabet fldcodename" id="t"/><label class="remove_some_css" for="t"></label>&nbsp;T</li>
                                <li><input type="checkbox" name="alpha" value="U" class="alphabet fldcodename" id="u"/><label class="remove_some_css" for="u"></label>&nbsp;U</li>
                                <li><input type="checkbox" name="alpha" value="V" class="alphabet fldcodename" id="v"/><label class="remove_some_css" for="v"></label>&nbsp;V</li>
                                <li><input type="checkbox" name="alpha" value="W" class="alphabet fldcodename" id="w"/><label class="remove_some_css" for="w"></label>&nbsp;W</li>
                                <li><input type="checkbox" name="alpha" value="X" class="alphabet fldcodename" id="x"/><label class="remove_some_css" for="z"></label>&nbsp;X</li>
                                <li><input type="checkbox" name="alpha" value="Y" class="alphabet fldcodename" id="y"/><label class="remove_some_css" for="y"></label>&nbsp;Y</li>
                                <li><input type="checkbox" name="alpha" value="Z" class="alphabet fldcodename" id="z"/><label class="remove_some_css" for="z"></label>&nbsp;Z</li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary adonclose" data-dismiss="modal">Close</button>
                    <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Save changes"> -->
                    <button type="button" class="btn btn-primary" onclick="saveInpatientAllergyDrugs()">Save</button>
                </div>
            </div>
        </form>
    </div>
</div> 
<script type="text/javascript">
    function saveInpatientAllergyDrugs(){
        // alert('add allergy drugs');
        
        var url = "{{route('allergydrugstoreInpatient')}}";
        $.ajax({
            url: url,
            type: "POST",
            data:  $("#inpatient-allergy").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-aldrug').empty().append(response);
                $('#allergic_modal').modal('hide');
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