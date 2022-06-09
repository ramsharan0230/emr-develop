<div id="chief-complaint" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">Chief Complaints</h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="form-group form-row align-items-center">
                <div class="col-sm-4">
                    <select name="flditem" class="form-control flditem">
                        <option value="">--Select--</option>
                        @if(isset($complaint))
                        @foreach($complaint as $com)
                        <option value="{{ $com->fldsymptom }}">{{ $com->fldsymptom }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-1">
                    <input name="duration" type="numeric" value="0" min="0" class="form-control duration">
                </div>
                <div class="col-sm-2">
                    <select name="duration_type" class="form-control duration_type">
                        <option value="">--Select--</option>
                        <option value="Hours">Hours</option>
                        <option value="Days">Days</option>
                        <option value="Weeks">Weeks</option>
                        <option value="Months">Months</option>
                        <option value="Years">Years</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="fldreportquali" class="form-control fldreportquali">
                        <option value="">--Select--</option>
                        <option value="Left Side">Left Side</option>
                        <option value="Right Side">Right Side</option>
                        <option value="Both Side">Both Side</option>
                        <option value="Episodes">Episodes</option>
                        <option value="On/Off">On/Off</option>
                        <option value="Present">Present</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary" id="insert_complaints" url="{{ route('insert_complaint')}}"><i class="ri-add-fill"></i> Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="res-table">
        <table class="table table-bordered table-hovered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>&nbsp;</th>
                    <th>Symptoms</th>
                    <th>Dura</th>
                    <th>Side</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Time</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody id="js-chif-conplain-tbody">
                @if(isset($examgeneral))
                @foreach($examgeneral as $general)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $general->flditem }}</td>
                    <td>{{ $general->fldreportquanti }}</td>
                    <td>{{ $general->fldreportquali }}</td>
                    <td>
                        <a href="javascript:;" permit_user="{{ $general->flduserid }}" class="delete_complaints {{ $disableClass }}" url="{{ route('delete_complaint',$general->fldid) }}"><i class="ri-delete-bin-5-fill"></i></a>
                    </td>
                    <td>
                        <a href="javascript:;" permit_user="{{ $general->flduserid }}" data-toggle="modal" data-target="#edit_complaint" old_complaint_detail="{{$general->flddetail}}" class="clicked_edit_complaint {{ $disableClass }}" clicked_flag_val="{{ $general->fldid }}"><i class="ri-edit-2-fill"></i></a>
                    </td>
                    <td>{{ $general->fldtime }}</td>
                    <td>{{ $general->flduserid}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="edit_complaint" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="js-complain-detail-form" action="{{ route('insert_complaint_detail') }}">
                @csrf
                <input type="hidden" id="complaintfldid" name="fldid" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_complaintLabel" style="text-align: center;">Edit Complaint</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <textarea name="flddetail" class="flddetail_complaint" id="editor"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input name="submit" type="button" id="submitflag" class="btn btn-primary" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    var chiefComplaints = {
        displayModal: function (encId) {
            $('#js-chief-complaints-modal').modal('show');
        },
    }
</script>
<script type="text/javascript">
    CKEDITOR.replace('flddetail',
        {
        height: '300px',
        } );
    $("#insert_complaints").click(function () {
        var flditem = $(".flditem option:selected").val();
        var duration = $(".duration").val();
        var duration_type = $(".duration_type option:selected").val();
        var fldreportquali = $(".fldreportquali option:selected").val();

        var fldencounterval = $("#fldencounterval").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();
        var url = $(this).attr("url");
        var formData = {
            fldencounterval: fldencounterval,
            flduserid: flduserid,
            fldcomp: fldcomp,
            flditem: flditem,
            duration: duration,
            duration_type: duration_type,
            fldreportquali: fldreportquali
        };
        if($.isNumeric(duration) === true){
            if (flditem == '') {
                alert('Fill all the data');
            } else {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        console.log(data);
                        if ($.isEmptyObject(data.error)) {
                            var complain = data.success.data;
                            var deleteurl = baseUrl + '/present/delete-complaint-inpatient/' + complain.id;
                            $('#js-chif-conplain-tbody').append('<tr><td>' + ($('#js-chif-conplain-tbody tr').length+1) + '</td><td>' + complain.flditem + '</td><td>' + complain.fldreportquanti + '</td><td>' + complain.fldreportquali + '</td><td><a href="javascript:;" permit_user="' + complain.flduserid + '" class="delete_complaints " url="' + deleteurl + '"><i class="ri-delete-bin-5-fill"></i></a></td><td><a href="javascript:;" permit_user="' + complain.flduserid + '" data-toggle="modal" data-target="#edit_complaint" old_complaint_detail="" class="clicked_edit_complaint " clicked_flag_val="' + complain.id + '"><i class="ri-edit-2-fill"></i></a></td><td>' + complain.fldtime + '</td><td>'+ complain.flduserid + '</td></tr>')
                            showAlert("Complaint Inserted Successfully.");
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    }
                });
            }
        }else{
            alert('Duration only numeric value allowed!!');
        }
    });

    $(document).on('click', ".clicked_edit_complaint", function () {
        current_user = $('.current_user').val();
        permit_user = $(this).attr('permit_user');
        if(current_user == permit_user){
            var id = $(this).attr("clicked_flag_val");
            var old_complaint_detail = $(this).attr("old_complaint_detail");
            $("#complaintfldid").val(id);

            CKEDITOR.instances.editor.setData( old_complaint_detail );
        }else
        showAlert('Authorization with  '+permit_user);
    });

    $(document).on('click', ".delete_complaints", function () {
        current_user = $('.current_user').val();
        permit_user = $(this).attr('permit_user');
        if(current_user == permit_user) {
            var cur = $(this);
            var url = $(this).attr("url");
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            cur.closest("tr").remove();
                            showAlert("Complaint deleted Successfully.");
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    }
                });
            }
        } else
        showAlert('Authorization with  '+permit_user);
    });

    $('#submitflag').click(function(e) {
        e.preventDefault();
        var cur = $(this);
        var fldid = $('#complaintfldid').val();
        var flddetail = CKEDITOR.instances['editor'].getData();
        $.ajax({
            url: baseUrl + '/insert_complaint_detail',
            type: "POST",
            dataType: "json",
            data: {
                fldid: fldid,
                flddetail: flddetail
            },
            success: function (data) {
                console.log(data);
                showAlert("Complaint report updated Successfully.");
                $('#edit_complaint').modal('hide');
                var editIconTd = $('td a[clicked_flag_val="' + fldid + '"]');
                // $(editIconTd).closest('tr').find('td:nth-child(8)').html(flddetail);
                $(editIconTd).attr('old_complaint_detail', flddetail);
                CKEDITOR.instances['editor'].setData('');
            },
            error: function (data) {
                showAlert("Failed to update Complaint report.");
            }
        });
    });
</script>
@endpush
