$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content") 
        }
    });
});

// $('a[data-toggle="collapse"]').bind('click',function() {
//     $('.btn-eye-form').css('background-color', '#007bff');
//     $(this).css('background-color', '#ea2e2e');
//     var el_id = $(this).attr('href');
//     $('.collapse').not($(el_id)).collapse('hide');
// });

var systematicIllness = {
    displayModal: function (modalTitle) {
        $('#js-eye-ck-modal-title').html(modalTitle);
        $('#js-eye-ck-modal').modal('show');
    },
}

var currentMedication = {
    displayModal: function (modalTitle) {
        $('#js-eye-ck-modal-title').html(modalTitle);
        $('#js-eye-ck-modal').modal('show');
    },
}

CKEDITOR.replace('js-systematic-illness-ck-textarea',
{
height: '300px',
} );
CKEDITOR.replace('js-current-medication-ck-textarea',
{
height: '300px',
} );
CKEDITOR.replace('js-note-ck-textarea',
{
height: '300px',
} );
CKEDITOR.replace('js-advice-ck-textarea',
{
height: '300px',
} );
CKEDITOR.replace('js-history-past',
{
height: '300px',
} );
CKEDITOR.replace('js-history-family',
{
height: '300px',
} );
CKEDITOR.replace('js-procedure-ck-textarea',
{
height: '300px',
} );

CKEDITOR.replace('js-onexam-right',
{
height: '300px',
} );
CKEDITOR.replace('js-onexam-left',
{
height: '300px',
} );

$('.js-eye-ajax-save-btn').click(function(e) {
    e.preventDefault();
    CKEDITOR.instances['js-systematic-illness-ck-textarea'].updateElement();
    CKEDITOR.instances['js-current-medication-ck-textarea'].updateElement();
    CKEDITOR.instances['js-note-ck-textarea'].updateElement();
    CKEDITOR.instances['js-advice-ck-textarea'].updateElement();
    CKEDITOR.instances['js-history-past'].updateElement();
    CKEDITOR.instances['js-history-family'].updateElement();
    CKEDITOR.instances['js-procedure-ck-textarea'].updateElement();
    CKEDITOR.instances['js-onexam-right'].updateElement();
    CKEDITOR.instances['js-onexam-left'].updateElement();
    var data = $(this).closest('form').serialize();
    var url = $(this).closest('form').attr('action');

    $.ajax({
        url: url,
        type: "POST",
        // dataType: "json",
        data: data,
        success: function (data) {
            showAlert("Data updated Successfully.");
        },
        error: function (data) {
            showAlert("Failed to update data.");
        }
    });
});

$("#searchdrugs").keyup(function(){
    // alert('sdfsd');
     var searchtext = $(this).val();
     // if()
     var patientid = $('#patientID').val();
     var resultDropdown = $(this).siblings("#allergicdrugss");
     // $('#allergicdrugss').hide();
     if(searchtext.length > 0){
          $.get("searchDrugs", {term: searchtext,patient_id: patientid}).done(function(data){
              // Display the returned data in browser
              $('#allergicdrugss').empty().html(data);
          });
      } else{
          $.get("getAllDrugs", {term: searchtext,patient_id: patientid}).done(function(data){
              // Display the returned data in browser
              $('#allergicdrugss').empty().html(data);
          });
      }
  });