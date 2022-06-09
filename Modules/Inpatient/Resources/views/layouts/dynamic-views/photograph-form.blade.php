<script src="{{asset('scripts/croppie.js')}}"></script>

<link rel="stylesheet" href="{{asset('styles/croppie.css')}}" />
<form action=""  class="laboratory-form container" method="post" enctype="multipart/form-data">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="fldinput" value="Photograph">

    <div class="modal-body">

        <div class="row">
            <div class="col-md-12" >
                <div class="form-group justify-content-center">
                    <label for="" class="form-label"> Photograph</label>
                </div>
                <div class="photo-box" style="text-align: center;">

                    <div id="uploaded_image"></div>

                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12" >
                <div class="photo-upload">
                    <button type="button" class="choose_file"><i class="fas fa-folder-open"></i> Open</button>
                    <button type="button"><i class="fas fa-camera"></i> Capture</button><!-- <span class="name">No file selected</span> -->
                    <button type="button"><i class="fas fa-eye"></i> Preview</button>

                    <input type="file" name="upload_image" id="upload_image_inpatient" style="display: none;"/>
                </div>
            </div>
        </div>



    </div>
    <div class="modal-footer">

       <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
       <input type="submit" name="submit" id="submitfreetextdiagnosis" class="btn btn-primary" value="Save">

    </div>
</form>
<div id="uploadimageModalInpatient" class="modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                          <div id="image_demo_inpatient"></div>
                    </div>
                    <div class="col-md-12 text-center" style="padding-top:30px;">
                          <button class="btn btn-success crop_image">Crop & Upload Image</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".choose_file").click(function () {
      $("#upload_image_inpatient").trigger('click');
    });

</script>
<script>
$(document).ready(function(){

    $image_crop = $('#image_demo_inpatient').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#upload_image_inpatient').on('change', function(){
    // alert('hdd');
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModalInpatient').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url:"{{route('inpatient.image.form.save.waiting')}}",
        type: "POST",
        data:{"image": response},
        success:function(data)
        {
          $('#uploadimageModalInpatient').modal('hide');
          $('#uploaded_image').html(data);
        }
      });
    })
  });

});
</script>



