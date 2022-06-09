<script src="{{asset('scripts/croppie.js')}}"></script>
<script src="{{asset('js/webcam.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('styles/croppie.css')}}"/>
@php
    $encounterData = $encounter[0];
    $encounterDataPatientInfo = $encounter[0]->patientInfo;
@endphp
<input type="hidden" name="encounter" value="{{ $encounterId }}" id="common_encounter_id">
<input type="hidden" name="fldinput" value="Patient Image" id="common_fldinput">

<div class="modal-body" id="frame">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group justify-content-center">
                <label for="" class="form-label"> Photographs</label>
            </div>
            <div class="form-group">

                <div id="uploaded_image"></div>

            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="photo-upload">
                <input type="file" name="upload_image" id="upload_image"/>
                {{--                <button type="button" class="choose_file"><i class="fas fa-folder-open"></i> Open</button>--}}
                <button type="button" id="capture"><i class="fas fa-camera"></i> Capture</button><!-- <span class="name">No file selected</span> -->
                <button type="button"><i class="fas fa-eye"></i> Preview</button>

            </div>
        </div>
    </div>


</div>
<div class="modal-footer">

    <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
    <input type="submit" name="submit" id="webcam_submit" class="btn btn-primary" value="Save">

</div>




<script type="text/javascript">

    $(document).ready(function () {

        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square' //circle
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $('#upload_image').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function (event) {
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (response) {
                $.ajax({
                    url: "{{route('patient.image.form.save.waiting')}}",
                    type: "POST",
                    data: {"image": response, "encounterID": $('#common_encounter_id').val(), "fldinput": $('#common_fldinput').val()},
                    success: function (data) {
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });


    });


    var imageData = '';

    function take_snapshot() {
        // alert('dfgffdg');
        Webcam.snap(function (data_uri) {
            $('#uploadwebcamimageModal').modal('hide');
            imageData = data_uri;
            $(".image-tag").val(data_uri);
            document.getElementById('uploaded_image').innerHTML = '<img src="'+data_uri+'"/>';
        });
    }

    $('#webcam_submit').on('click', function () {
        // var response = $('.image_demo').val();
        // console.log(response);
        $.ajax({
            url: "{{route('patient.image.form.save.waiting')}}",
            type: "POST",
            data: {"image": imageData, "encounterID": $('#common_encounter_id').val(), "fldinput": $('#common_fldinput').val()},
            success: function (data) {
                // console.log(data)
                $('#uploaded_image').html(data);
                location.reload(true);
            }
        });
    });

    $('#capture').on('click', function () {
        Webcam.set({
            width: 460,
            height: 380,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#display_webcam_video');
        $('#uploadwebcamimageModal').modal('show');
    })
</script>

