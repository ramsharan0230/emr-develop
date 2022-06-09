<script src="{{asset('js/webcam.min.js')}}"></script>
<div class="modal" id="js-patient-image-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Patient Image</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-2">Name</label>
                                <input readonly="readonly" type="text" class="form-control col-md-10" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }} {{ $patient->fldptnamelast }}@endif">
                                <input type="hidden" id="js-patient-image-fldcategory-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-row align-items-center">
                                <label class="col-md-2">Gender</label>
                                <input readonly="readonly" type="text"  class="form-control col-md-10" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row ">
                        <div class="col-md-4">
                            <div class="res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <tbody id="js-patient-image-tbody" class="general_image_inpatient"></tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" id="js-patient-image-title-input" class="form-control">&nbsp;
                                        <!-- <button type="button" class="btn btn-deafult btn-sm pull-right"><img src="{{asset('assets/images/play.png')}}" alt="" width="15px;"></button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="res-table" id="js-patient-image-preview-div"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">

                                    <div class="form-group form-row align-items-center">
                                        <label class="col-md-1">Keys</label>
                                        <div class="col-md-11">
                                            <input type="text" id="js-patient-image-key-input" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea id="js-patient-image-detail-textarea" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="image-upload d-flex">
                                            <button type="button" class="btn btn-primary btn-action btn-sm-in btn-patient" id="patient_image_file">
                                                <i class="ri-upload-cloud-fill h6"></i>
                                            </button>&nbsp;
                                            <input id="js-patient-image-file" type="file" style="display: none;" />
                                            <button type="button" class="btn btn-primary btn-action btn-sm-in btn-patient" id="capturediacom"><i class="ri-camera-fill h6"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary  btn-action btn-sm-in" id="js-patient-image-add-btn">
                                        <i class="ri-add-line"></i> Add
                                    </button>
                                    <button type="button" class="btn btn-primary  btn-action btn-sm-in" id="js-patient-image-edit-btn">
                                        <i class="ri-edit-2-fill"></i> Edit
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
<div id="uploadwebcamdiacomimageModal" class="modal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="my_camera"></div>
                        <br/>
                        <input type="button" value="Take Snapshot" onclick="take_dicomsnapshot()">
                        <input type="hidden" name="image" class="image-tag">
                    </div>
                    <div class="col-md-6">
                        <div id="results">Your captured image will appear here...</div>
                    </div>
                    <!-- <div class="col-md-12 text-center">
                        <br/>
                        <button class="btn btn-success" id="webcam_submit">Submit</button>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="diacomcam">Close</button>
            </div>
        </div>
    </div>
</div>
@push('after-script')
    <script type="text/javascript">
        function resetForm() {

            $('#js-patient-image-title-input').val('');
            $('#js-patient-image-key-input').val('');
            $('#js-patient-image-detail-textarea').val('');
            $('#js-patient-image-name-input').val('');
            $('#js-patient-image-gender-input').val('');
            $('#js-patient-image-preview-div').html('');
        }

        $('#js-patient-image-modal').on('hidden.bs.modal', function () {
            resetForm();
            $('#js-patient-image-fldcategory-input').val('');
            $('#js-patient-image-tbody').html('');
        });

        function get_images(fldcateg) {
            $('#js-patient-image-name-input').val($('#js-inpatient-name-input').val());
            $('#js-patient-image-gender-input').val($('#js-inpatient-gender-input').val());

            $('#js-patient-image-modal').modal('show');
            $.ajax({
                url: baseUrl + '/inpatient/dataEntryMenu/getPatientImage',
                type: "GET",
                data: {fldcateg: fldcateg, encounterId: globalEncounter},
                // dataType: "json",
                success: function (data) {
                    var trData = '';
                    $.each(data, function(i, val) {

                        // console.log(val);

                        trData += '<tr title="' + val.fldtitle + '" fldid="' + val.fldid + '" imagepath="' + val.fldpic + '" key="' + val.fldkeyword + '" detail="' + val.flddetail + '"><td>' + val.fldtitle + '</td></tr>';
                    });
                    $('#js-patient-image-tbody').empty().html(trData);
                }
            });
        }

        $('#menu-general-image').click(function() {
            var category = 'IMAGE';
            get_images(category);
            $('#js-patient-image-fldcategory-input').val(category);
            $('input#js-patient-image-file').attr('accept', 'image/*');
        });

        $('#menu-dicom-image').click(function() {
            var category = 'DICOM';
            get_images(category);
            $('#js-patient-image-fldcategory-input').val(category);
            $('input#js-patient-image-file').attr('accept', '.dicom');
        });

        $('#patient_image_file').on('click', function(){
            $('#js-patient-image-file').trigger('click');
            e.stopPropagation();
        });

        $(document).on('click', '#js-patient-image-tbody tr', function() {
            $('#js-patient-image-tbody tr').css('background-color', '#ffffff');
            $(this).css('background-color', '#c8dfff');

            $.each($('#js-patient-image-tbody tr'), function(i,e) {
                $(e).attr('is_selected', 'no');
            });
            $(this).attr('is_selected', 'yes');

            $('#js-patient-image-title-input').val($(this).attr('title'));
            $('#js-patient-image-key-input').val($(this).attr('key'));
            $('#js-patient-image-detail-textarea').val($(this).attr('detail'));
            $('#js-patient-image-preview-div').html('<img src="' + $(this).attr('imagepath') + '">');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#js-patient-image-preview-div').html('<img src="' + e.target.result + '">');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#js-patient-image-file").change(function(){
            readURL(this);
        });

        function getPatientImageFormData() {
            var data = new FormData();
            data.append('fldtitle', $('#js-patient-image-title-input').val());
            data.append('fldkey', $('#js-patient-image-key-input').val());
            data.append('flddetail', $('#js-patient-image-detail-textarea').val());
            data.append('fldcateg', $('#js-patient-image-fldcategory-input').val());
            data.append('encounterId', globalEncounter);

            if ($('#js-patient-image-file')[0].files !== undefined && $('#js-patient-image-file')[0].files.length > 0) {
                var image = $('#js-patient-image-file')[0].files[0];
                data.append('fldimage', image);
            }else{
                if($('#webcam_image').length){
                    data.append('fldimage', document.getElementById("webcam_image").src);
                    data.append('webcam', 1);
                }
            }
            return data;
        }

        $('#js-patient-image-add-btn').click(function() {
            var data = getPatientImageFormData();
            $.ajax({
                url: baseUrl + '/inpatient/dataEntryMenu/savePatientImage',
                type: "POST",
                enctype: 'multipart/form-data',
                data: data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        var trData = '<tr title="' + val.fldtitle + '" fldidid="' + val.fldid + '" imagepath="' + val.fldpic + '" key="' + val.fldkeyword + '" detail="' + val.flddetail + '"><td>' + val.fldtitle + '</td></tr>';
                        $('#js-patient-image-tbody').append(trData);

                        $("#js-patient-image-file").val(null);
                        resetForm();
                    }
                    showAlert(response.message);

                }
            });

        });

        $('#js-patient-image-edit-btn').click(function() {
            var selectedTr = $('#js-patient-image-tbody tr[is_selected="yes"]');
            var data = getPatientImageFormData();
            data.append('fldid', $(selectedTr).attr('fldid'));
            $.ajax({
                url: baseUrl + '/inpatient/dataEntryMenu/updatePatientImage',
                type: "POST",
                enctype: 'multipart/form-data',
                data: data,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        var val = response.data;
                        $(selectedTr).find('td:first-child').text(val.fldtitle);

                        $(selectedTr).attr('title', val.fldtitle);
                        $(selectedTr).attr('key', val.fldkeyword);
                        $(selectedTr).attr('detail', val.flddetail);
                        $(selectedTr).attr('imagepath', val.fldpic);

                        $("#js-patient-image-file").val(null);
                        resetForm();
                    }
                    showAlert(response.message);
                    Webcam.reset();
                }
            });
        });

        //Web Cam Script
        Webcam.set({
            width: 490,
            height: 390,
            image_format: 'jpeg',
            jpeg_quality: 90
        });



        var imageData = '';

        function take_dicomsnapshot() {
            // alert('dfgffdg');
            Webcam.snap(function (data_uri) {
                $('#uploadwebcamdiacomimageModal').modal('hide');
                imageData = data_uri;
                // $("#js-patient-image-file").val(data_uri);
                $('#js-patient-image-preview-div').html('<img src="' + data_uri + '" id="webcam_image">');
                // document.getElementById('uploaded_image').innerHTML = '<img src="'+data_uri+'"/>';
            });

        }

        // $('#webcam_submit').on('click', function () {
        //     // var response = $('.image_demo').val();
        //     // console.log(response);
        //     $.ajax({
        //         url: "{{route('patient.image.form.save.waiting')}}",
        //         type: "POST",
        //         data: {"image": imageData, "encounterID": $('#common_encounter_id').val(), "fldinput": $('#common_fldinput').val()},
        //         success: function (data) {
        //             console.log(data)
        //             $('#uploaded_image').html(data);
        //             location.reload(true);
        //         }
        //     });
        // });

        $('#capturediacom').on('click', function () {
            Webcam.attach('#my_camera');
            $('#uploadwebcamdiacomimageModal').modal('show');
        })

        $('#diacomcam').on('click', function(){
            $('#uploadwebcamdiacomimageModal').modal('hide');
        });
    </script>
@endpush
