$(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      }
    });
  });

//essentail

$("#save_essential").click(function() {


    var pulse_rate = $('#pulse_rate').attr('pulse_rate') + ':' + $('#pulse_rate').val();
    var sys_bp = $('#sys_bp').attr('sys_bp') + ':' + $('#sys_bp').val();
    var dia_bp = $('#dia_bp').attr('dia_bp') + ':' + $('#dia_bp').val();
    var respi = $('#respi').attr('respi') + ':' + $('#respi').val();
    var saturation = $('#saturation').attr('saturation') + ':' + $('#saturation').val();
    var pulse_rate_rate = $('#pulse_rate_rate').attr('pulse_rate_rate') + ':' + $('#pulse_rate_rate').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
  
    var url = $(this).attr('url');
    var formData = {
        "_token": "{{ csrf_token() }}",
        "fldencounterval": fldencounterval,
        "flduserid": flduserid,
        "fldcomp": fldcomp,

        'essential[]': [pulse_rate, sys_bp, dia_bp, respi, saturation, pulse_rate_rate]
    }

   

    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                location.reload();
            } else {
                alert('error');
            }
        }
    });


});


//complaint

 $("#insert_complaints").click(function() {

    var flditem = $('.flditem option:selected').text();
    var duration = $('.duration').val();
    var duration_type = $('.duration_type option:selected').text();
    var fldreportquali = $('.fldreportquali option:selected').text();

    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var url = $(this).attr('url');
    var formData = {
        
        "fldencounterval": fldencounterval,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "flditem": flditem,
        "duration": duration,
        "duration_type": duration_type,
        "fldreportquali": fldreportquali


    }
console.log(formData);
   
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                location.reload();
            } else {
                alert('error');
            }
        }
    });


});
 $('.clickededitcomplaint').click(function() {
     var id = $(this).attr('clickedflagval');
     $('#complaintfldid').val(id);
    
 });


$("#update_complaint").click(function() {
    var current = $(this);
    var url = $(this).attr('url');



});
$('.delete_complaints').click(function() {
    var cur = $(this);
    var url = $(this).attr('url');
   
    $.ajax({
        url: url,
        type: 'GET',
        dataType: "json",
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                cur.closest("tr").remove();
            } else {
                alert('error');
            }
        }
    });

});


//finding

 $('#insert_finding').click(function() {

    var find_fldhead = $('#find_fldhead option:selected').text();
    var find_fldtype = $('#find_fldtype').val();
    var fldrepquali = $('#find_fldrepquali').val();
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var url = $(this).attr('url');
    var formData = {
        "fldencounterval": fldencounterval,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldrepquali": fldrepquali,
        "fldtype": find_fldtype,
        "fldhead": find_fldhead


    }

    console.log(formData);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                location.reload();
            } else {
                alert('error');
            }
        }
    });


});

 $('.delete_finding').click(function() {
     var cur = $(this);
     var url = $(this).attr('url');
   
     $.ajax({
         url: url,
         type: 'GET',
         dataType: "json",
         success: function(data) {
             if ($.isEmptyObject(data.error)) {
                 cur.closest("tr").remove();
             } else {
                 alert('error');
             }
         }
     });

 });

 $('.clickedflag').click(function() {
    var id = $(this).attr('clickedflagval');
    $('#findingfldid').val(id);
  
});

 //tabs
 $('.save_history').click(function() {
    
    
    var history = CKEDITOR.instances.history.getData();
    var url = $('.note_tabs').val();
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
   
    var formData = {
        "content": history,
        "fldinput": 'History',
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldencounterval": fldencounterval
    }

    console.log(formData);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                alert('save information');
                //location.reload();
            } else {
                alert('error');
            }
        }
    });


});


$('.save_advice').click(function() {

    var advice = CKEDITOR.instances.advice.getData();
    var url = $('.note_tabs').val();
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
   
    var formData = {
        "content": advice,
        "fldinput": 'Notes',
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldencounterval": fldencounterval
    }


    console.log(formData);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                alert('save information');
                //location.reload();
            } else {
                alert('error');
            }
        }
    });


});


$('.save_fluid').click(function() {

    var fluid = CKEDITOR.instances.fluid.getData();
    var url = $('.note_tabs').val();
    var fldencounterval = $('#fldencounterval').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
   
    var formData = {
        "content": fluid,
        "fldinput": 'fluid',
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldencounterval": fldencounterval
    }

    // console.log(formData);
    
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                alert('save information');
                //location.reload();
            } else {
                alert('error');
            }
        }
    });


});

 



 /**
 * Start Function for Allergy
 */
       
    var ckbox = $("input[name='alpha']");
    var chkId = '';
    $('input').on('click', function() {
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
    $('.onclose').on('click', function(){
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
     var searchtext = $(this).val();
     // if()
     var patientid = $('#patientID').val();
     var resultDropdown = $(this).siblings("#allergicdrugss");
     // $('#allergicdrugss').hide();
     if(searchtext.length > 0){
          $.get("searchDrugs", {term: searchtext,patient_id: patientid}).done(function(data){
              // Display the returned data in browser
              resultDropdown.html(data);
          });
      } else{
          $.get("getAllDrugs", {term: searchtext,patient_id: patientid}).done(function(data){
              // Display the returned data in browser
              resultDropdown.html(data);
          });
      }
  });

  $('#deletealdrug').on('click', function(){
            
            $('#select-multiple-aldrug').each(function() {
                // alval = [];
                 var finalval = $(this).val().toString();
                // alert(finalval);
                var url = $('.delete_pat_findings').val();
                    
                      $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: {ids:finalval},
                        success: function(data) {
                            // console.log(data);
                            if ($.isEmptyObject(data.error)) {

                                alert('Delete Drug ?');
                                location.reload();
                            } else {
                                alert('error');
                            }
                        }
                    });
            });
           
            
        });

        
/**
 * End Function for Allergy
 */
  
 /**
 * Start Function for Diagnosis
 */
             
                      
//   var table = $('table.datatable').DataTable({
//       "paging":   false
      
//   });
  
  $(document).on('click','.dccat', function(){
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

$('#deletealdiagno').on('click', function(){
            
    $('#select-multiple-diagno').each(function() {
        // alval = [];
         var finalval = $(this).val().toString();
        // alert(finalval);
        var url = $('.delete_pat_findings').val();
            // alert(finalval);
              $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {ids:finalval},
                success: function(data) {
                    // console.log(data);
                    if ($.isEmptyObject(data.error)) {

                        alert('Delete Diagnosis ?');
                        location.reload();
                    } else {
                        alert('error');
                    }
                }
            });
    });
   
    
});

/**
* Start Function for Diagnosis
*/