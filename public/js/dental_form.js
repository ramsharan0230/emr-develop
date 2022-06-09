$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});


$('#dentalmedicalhistory').on('click', function(){
	  
   if($('#medicalhistory').is(':hidden'))
    {
      $('#medicalhistory').show('slow');
      $('#medical_gray').show();
      $('#medical_blue').hide();
    }else{
      $('#medicalhistory').hide('slow');
      $('#medical_gray').hide();
      $('#medical_blue').show();
    }
    $('#dentalhistory').hide();
   
    $('#allergy').hide();
    // $('#notes').hide();
    $('#diagnosis').hide();
    $('#extralaboratory').hide();
    $('#dentalAdvice').hide();
     $('#dentalNotes').hide();
     $('#Procedures').hide();
     
     $('#dental_gray').hide();
     $('#diagnosis_gray').hide();
     $('#allergy_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();
    // $('#advice').hide();
      $('#dental_blue').show();
     $('#diagnosis_blue').show();
     $('#allergy_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();

});
$('#dental_history').on('click', function(){
	       
   if($('#dentalhistory').is(':hidden'))
    {
      $('#dentalhistory').show('slow');
      $('#dental_gray').show();
      $('#dental_blue').hide();
    }else{
      $('#dentalhistory').hide('slow');
      $('#dental_gray').hide();
      $('#dental_blue').show();
    }
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    $('#diagnosis').hide();
    $('#extralaboratory').hide();
    $('#dentalAdvice').hide();
     $('#dentalNotes').hide();
     $('#Procedures').hide();

     $('#medical_gray').hide();
     $('#diagnosis_gray').hide();
     $('#allergy_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#diagnosis_blue').show();
     $('#allergy_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();
    // $('#advice').hide();

});
$('#dentalallergy').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    if($('#allergy').is(':hidden'))
    {
      $('#allergy').show('slow');
      $('#allergy_gray').show();
      $('#allergy_blue').hide();
    }else{
      $('#allergy').hide('slow');
      $('#allergy_gray').hide();
      $('#allergy_blue').show();
    }
    // $('#notes').hide();
    $('#diagnosis').hide();
    $('#extralaboratory').hide();
    // $('#advice').hide();
    $('#dentalAdvice').hide();
     $('#dentalNotes').hide();
     $('#Procedures').hide();

     $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#diagnosis_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#diagnosis_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();
});

$('#dentaldigonosis').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    if($('#diagnosis').is(':hidden'))
    {
      $('#diagnosis').show('slow');
      $('#diagnosis_gray').show();
      $('#diagnosis_blue').hide();
    }else{
      $('#diagnosis').hide('slow');
      $('#diagnosis_gray').hide();
      $('#diagnosis_blue').show();
    }
    $('#extralaboratory').hide();
    // $('#advice').hide();
    $('#dentalAdvice').hide();
     $('#dentalNotes').hide();
     $('#Procedures').hide();

     $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#allergy_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#allergy_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();
});
$('#dentalextralaboratory').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    
    if($('#extralaboratory').is(':hidden'))
    {
      $('#extralaboratory').show('slow');

      $('#laboratory_gray').show();
      $('#laboratory_blue').hide();
    }else{
      $('#extralaboratory').hide('slow');
      $('#laboratory_gray').hide();
      $('#laboratory_blue').show();
    }
    // $('#advice').hide();
    $('#diagnosis').hide();
     $('#dentalAdvice').hide();
     $('#dentalNotes').hide();
    $('#Procedures').hide();

    $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#allergy_gray').hide();
     $('#diagnosis_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#allergy_blue').show();
     $('#diagnosis_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();

});
$('#dentaladvice').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    
    if($('#dentalAdvice').is(':hidden'))
    {
      $('#dentalAdvice').show('slow');
      $('#advice_gray').show();
      $('#advice_blue').hide();
    }else{
      $('#dentalAdvice').hide('slow');
      $('#advice_gray').hide();
      $('#advice_blue').show();
    }
    $('#extralaboratory').hide();
    // $('#advice').hide();
    $('#dentalNotes').hide();
    $('#diagnosis').hide();
    $('#Procedures').hide();

    $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#allergy_gray').hide();
     $('#diagnosis_gray').hide();
     $('#laboratory_gray').hide();
     $('#notes_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#allergy_blue').show();
     $('#diagnosis_blue').show();
     $('#laboratory_blue').show();
     $('#notes_blue').show();
     $('#procedures_blue').show();

});
$('#dentalnotes').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    $('#extralaboratory').hide();
    
    if($('#dentalNotes').is(':hidden'))
    {
      $('#dentalNotes').show('slow');
      $('#notes_gray').show();
      $('#notes_blue').hide();
    }else{
      $('#dentalNotes').hide('slow');
      $('#notes_gray').hide();
      $('#notes_blue').show();
    }
    // $('#advice').hide();
    $('#diagnosis').hide();
    $('#dentalAdvice').hide();
    $('#Procedures').hide();

    $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#allergy_gray').hide();
     $('#diagnosis_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#procedures_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#allergy_blue').show();
     $('#diagnosis_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#procedures_blue').show();
});
$('#procedures').on('click', function(){
    $('#dentalhistory').hide();
    $('#medicalhistory').hide();
    $('#allergy').hide();
    // $('#notes').hide();
    $('#extralaboratory').hide();
    
    if($('#Procedures').is(':hidden'))
    {
      $('#Procedures').show('slow');
      $('#procedures_gray').show();
      $('#procedures_blue').hide();
    }else{
      $('#Procedures').hide('slow');
      $('#procedures_gray').hide();
      $('#procedures_blue').show();
    }
    // $('#advice').hide();
    $('#diagnosis').hide();
    $('#dentalAdvice').hide();
    $('#dentalNotes').hide();

    $('#medical_gray').hide();
     $('#dental_gray').hide();
     $('#allergy_gray').hide();
     $('#diagnosis_gray').hide();
     $('#laboratory_gray').hide();
     $('#advice_gray').hide();
     $('#notes_gray').hide();

     $('#medical_blue').show();
     $('#dental_blue').show();
     $('#allergy_blue').show();
     $('#diagnosis_blue').show();
     $('#laboratory_blue').show();
     $('#advice_blue').show();
     $('#notes_blue').hide();

});


function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

/**
 * Start Function for Allergy
 */
       
    var ckbox = $("input[name='alpha']");
    var chkId = '';
  
  
  $('#deletealdrug').on('click', function() {
        if (confirm('Delete Allergy??')) {
            $('#select-multiple-aldrug').each(function() {
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
                            $('#select-multiple-aldrug option:selected').remove();
                        } else
                            showAlert('Something went wrong!!');
                    }
                });
            });
        }
    });

        
/**
 * End Function for Allergy
 */
  
 /**
 * Start Function for Diagnosis
 */
             
                      
  var table = $('table.datatable').DataTable({
      "paging":   false
      
  });
  var table = $('table.sdatatable').DataTable({
      "paging":   false
      
  });
  
  $(document).on('click','input[name="dccat"]', function(){
      // alert('click bhayo');

       $('input[name="dccat"]').bind('click',function() {
          $('input[name="dccat"]').not(this).prop("checked", false);
        });
      var diagnocode = $("input[name='dccat']");
      $('#code').val($(this).val());
      if (diagnocode.is(':checked')) {
          
              diagnocode = $(this).val() + ",";
              diagnocode = diagnocode.slice(0, -1);
                                
        $("input[name='dccat']").attr('checked', false);
          
         if(diagnocode.length > 0){
              // alert(diagnocode);
              $.get("getDiagnosisByCode", {term: diagnocode}).done(function(data){
                  // Display the returned data in browser
                  $("#sublist").html(data);
              });
         }
      }else{
          $("#sublist").html('');
      }
  });

  $('.onclose').on('click', function(){
      
    $('input[name="dccat"]').prop("checked", false);
    $('#code').val('');
    $("#diagnosissubname").val('');
    $("#sublist").val('');
});



$(document).on('click','.diagnosissub', function(){
  // alert('click sub bhayo');

   $('input[name="diagnosissub"]').bind('click',function() {
      $('input[name="diagnosissub"]').not(this).prop("checked", false);
    });
  var diagnosub = $("input[name='diagnosissub']");
  
  if (diagnosub.is(':checked')) {
      var value = $(this).val();
      
      $('#diagnosissubname').val(value);
  }else{
      $("#diagnosissubname").val('');
  }
});

$('#searchbygroup').on('click', function(){
  // alert('searchbygroup');
  var groupname = $('#diagnogroup').val();
  // alert(groupname);
  if(groupname.length > 0){
    $.get("getDiagnosisByGroup", {term: groupname}).done(function(data){
          // Display the returned data in browser
          $("#diagnosiscat").html(data);
      });
  }
});
$('#closesearchgroup').on('click', function(){
    $('#diagnogroup').val('');
    $.get("getInitialDiagnosisCategoryAjax", {term:'' }).done(function(data){
          // Display the returned data in browser
          $("#diagnosiscat").html(data);
      });
  
});



$('#deletealdiagno').on('click', function() {
        if (confirm('Delete Diagnosis ??')) {
            $('#select-multiple-diagno').each(function() {
                var finalval = $(this).val().toString();
                var url = $('.delete_pat_findings').val();

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: { ids: finalval },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Data Deleted !!');
                            $('#select-multiple-diagno option:selected').remove();
                        } else
                            showAlert('Something went wrong!!');
                    }
                });
            });
        }
    });

/**
* Start Function for Diagnosis
*/



  $('.alphabet').on('click', function() {
       $('input[name="alpha"]').bind('click',function() {
          $('input[name="alpha"]').not(this).prop("checked", false);
        });
      if (ckbox.is(':checked')) {
          
          $('#searchdrugs').val($('.alphabet').val());
          chkId = $(this).val() + ",";
          chkId = chkId.slice(0, -1);
       

        // alert(chkId);
        $("input[name='alpha']").attr('checked', false);
        $('#searchdrugs').val(chkId);
         
         var patientid = $('#patientID').val();
         
         if(chkId.length > 0){
              $.get("searchDrugs", {term: chkId,patient_id: patientid}).done(function(data){
                  // Display the returned data in browser
                  $("#allergicdrugss").html(data);
              });
          } else{
              $.get("getAllDrugs", {term: chkId,patient_id: patientid}).done(function(data){
                  // Display the returned data in browser
                  $("#allergicdrugss").html(data);
              });
          }
      }else{
          $('#searchdrugs').val('');
          $.get("getAllDrugs", {term: chkId,patient_id: patientid}).done(function(data){
              // Display the returned data in browser
              $("#allergicdrugss").html(data);
          });
      }     
    });
    $('.adonclose').on('click', function(){
      $('input[name="alpha"]').prop("checked", false);
      $('#searchdrugs').val('');
      var chkId = '';
      var patientid = $('#patientID').val();
      $.get("getAllDrugs", {term: chkId,patient_id: patientid}).done(function(data){
          // Display the returned data in browser
          $("#allergicdrugss").html(data);
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


// if($('#js-medical-history-ck-textarea').length > 0){
//   CKEDITOR.replace('js-medical-history-ck-textarea');
// CKEDITOR.replace('js-dental-history-ck-textarea');
// CKEDITOR.replace('js-extralaboratory-ck-textarea');
// CKEDITOR.replace('js-advice-ck-textarea');
// CKEDITOR.replace('js-notes-ck-textarea');
// CKEDITOR.replace('js-procedures-ck-textarea');
// }

if($('#js-medical-history-ck-textarea').length > 0){
    CKEDITOR.replace('js-medical-history-ck-textarea',
    {
    height: '300px',
    } );
}

if($('#js-dental-history-ck-textarea').length > 0){
    CKEDITOR.replace('js-dental-history-ck-textarea',
    {
    height: '300px',
    } );
}

if($('#js-extralaboratory-ck-textarea').length > 0){
    CKEDITOR.replace('js-extralaboratory-ck-textarea',
    {
    height: '300px',
    } );
}

if($('#js-advice-ck-textarea').length > 0){
    CKEDITOR.replace('js-advice-ck-textarea',
    {
    height: '300px',
    } );
}

if($('#js-notes-ck-textarea').length > 0){
    CKEDITOR.replace('js-notes-ck-textarea',
    {
    height: '300px',
    } );
}

if($('#js-procedures-ck-textarea').length > 0){
    CKEDITOR.replace('js-procedures-ck-textarea',
    {
    height: '300px',
    } );
}

// CKEDITOR.replace('js-diagnosis-ck-textarea');
// CKEDITOR.replace('hidden_one');


