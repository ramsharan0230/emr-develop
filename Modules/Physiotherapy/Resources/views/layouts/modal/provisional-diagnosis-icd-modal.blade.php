<div class="modal fade" id="provisionalicddiagnosis">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
            <div class="modal-header">
                <h5 class="modal-title" id="allergicdrugsLabel">ICD10 Database</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" id="pro-diagnosis">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="diagnosis_type" id="diagnosis_type" value="Provisional">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-2">Group</label>
                                <div class="col-sm-8">
                                    <select name="" id="diagnogroup" class="form-control">
                                        <option value="">--Select Group--</option>
                                        @if(isset($digno_group) and count($digno_group) > 0)
                                            @foreach($digno_group as $dg)
                                                <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                            @endforeach
                                        @else
                                            <option value="">Groups Not Available</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:void(0);" class=" button btn btn-primary" id="searchbygroup"><i
                                                class="ri-refresh-line"></i></a>
                                </div>
                                <div class="col-sm-1">
                                    <a href="#" class="button btn btn-danger" id="closesearchgroup"><i
                                                class="ri-close-fill"></i></a>
                                </div>
                            </div>
                            <div id="diagnosiss">
                                <div class="form-group form-row align-items-center">
                                    <!-- <label for="" class="col-sm-2">Search</label> -->
                                    <!-- <div class="col-sm-10">
                                        <input type="text" name="" palceholder="Search" class="form-control">
                                    </div> -->
                                </div>
                                <div class="icd-datatable">
                                    <table class="datatable table table-bordered table-striped table-hover dataTable no-footer"
                                           id="top-req datatable ">
                                        <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody id="diagnosiscat">
                                            @forelse($digno_group_list as $dc)
                                                <tr>
                                                    <td><input type="checkbox" class="dccat" name="dccat" value="{{$dc['code']}}"></td>
                                                    <td>{{$dc['code']}}</td>
                                                    <td>{{$dc['name']}}</td>
                                                </tr>
                                            @empty
                                                {{--<tr>--}}
                                                    {{--<td colspan="3" class="text-center">--}}
                                                        {{--<em>No data available in table ...</em>--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-2">Search</label>
                                <div class="col-sm-10">
                                    <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist"
                                           placeholder="Search" class="form-control">
                                </div>
                            </div>
                            <div class="table-responsive table-scroll-icd">
                                <table class=" table table-bordered table-striped table-hover" id=" top-req">
                                    <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                    </tr>
                                    </thead>
                                    <tbody id="sublist">

                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group form-row align-items-center mt-2">
                                <label for="" class="col-sm-2">Code</label>
                                <div class="col-sm-10">
                                    <input type="text" name="code" id="code" class="form-control" readonly="">
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-2">Text</label>
                                <div class="col-sm-10">
                                    <input type="text" name="diagnosissubname" id="diagnosissubname"
                                           class="form-control">
                                    <input type="hidden" name="encounter_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateProDiagnosis()">
                        Submit
                    </button>
                    <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Submit"> -->
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function updateProDiagnosis(){
        // alert('diagn')
        if ($('#encounter_id').val() == '') {
            alert('Please select encounter id.');
            return false;
        }
        var url = "{{route('physiotherapy.diagnosis.diagnosisStoreProvisional')}}";

        $.ajax({
            url: url,
            type: "POST",
            data:  $("#pro-diagnosis").serialize(), _token: "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);

                showAlert('Data Added !!');

                if ($.isEmptyObject(response.error)) {
                    if(response.diagnosistype == 'Provisional') {
                        $('#provisional_delete').append(response.html);
                    } else if(response.diagnosistype == 'Final') {
                        $('#final_delete').append(response.html);
                    }
                    $('#diagnosissubname').val(null);
                    // $('#diagnogroup').empty();
                    // $('#diagnosiscat').html('');
                    $('#search_diagnosis_sublist').val(null);
                    $('#code').val(null);
                    $('#sublist').html('');

                    $('#provisionalicddiagnosis').modal('hide');
                    showAlert('Data Added !!');

                } else {
                    showAlert('Something went wrong!!');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
    $(document).ready(function() {


        var table = $('table.datatable').DataTable({
            "paging": false
        });

        var table = $('table.sdatatable').DataTable({
            "paging": false
        });

        $(document).on('click', '.dccat', function() {
            $('input[name="dccat"]').bind('click', function() {
                $('input[name="dccat"]').not(this).prop("checked", false);
            });
            var diagnocode = $("input[name='dccat']");
            $('#code').val($(this).val());
            if (diagnocode.is(':checked')) {
                diagnocode = $(this).val() + ",";
                diagnocode = diagnocode.slice(0, -1);

                $("input[name='dccat']").attr('checked', false);
                if (diagnocode.length > 0) {
                    // alert(diagnocode);
                    $.get("emergency/diagnosis/getDiagnosisByCode", {
                        term: diagnocode
                    }).done(function(data) {
                        // Display the returned data in browser
                        $("#sublist").html(data);
                        var table = $('table.datatable-ajax').DataTable({
                            "paging": false
                        });
                    });
                }
            } else {
                $("#sublist").html('');
            }
        });

        $('.onclose').on('click', function() {
            $('input[name="dccat"]').prop("checked", false);
            $('#code').val('');
            $("#diagnosissubname").val('');
            $("#sublist").val('');
        });

        $(document).on('click', '.diagnosissub', function() {
            // alert('click sub bhayo');

            $('input[name="diagnosissub"]').bind('click', function() {
                $('input[name="diagnosissub"]').not(this).prop("checked", false);
            });
            var diagnosub = $("input[name='diagnosissub']");

            if (diagnosub.is(':checked')) {
                var value = $(this).val();
                $('#diagnosissubname').val(value);
            } else {
                $("#diagnosissubname").val('');
            }
        });
    });

</script>
