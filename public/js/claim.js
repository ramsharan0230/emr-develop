$('#searchbygroup').on('click', function () {
    // alert('searchbygroup');
    var groupname = $('#diagnogroup').val();
    // alert(groupname);
    if (groupname.length > 0) {
        $.get("getDiagnosisByGroup", {term: groupname}).done(function (data) {
            // Display the returned data in browser
            $("#diagnosiscat").empty().html(data);
        });
    }
});


$('#closesearchgroup').on('click', function () {
    $('#diagnogroup').val('');
    $.get("getInitialDiagnosisCategoryAjaxclaim", {term: ''}).done(function (data) {
        // Display the returned data in browser
        $("#diagnosiscat").html(data);
    });

});

$(document).on('click', '.diagnosissub', function () {
    // alert('click sub bhayo');

    $('input[name="diagnosissub"]').bind('click', function () {
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




$(document).on('click', 'input[name="dccat"]', function () {
    // alert('click bhayo');

    $('input[name="dccat"]').bind('click', function () {
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
            $.get("getDiagnosisByCodeclaim", {term: diagnocode}, {dataType: 'json'}).done(function (data) {
                // Display the returned data in browser
                $("#sublist").html(data);
            });

            // $.ajax({
            //           url: '{{route("getDiagnosisByCode")}}',
            //           type: "POST",
            //           dataType: "json",
            //           data: {term:diagnocode},
            //           success: function(data) {
            //               $("#sublist").html(data);
            //           }
            //       });
        }
    } else {
        $("#sublist").html('');
    }
});


$('.btnclaim').on('click',function(){

    var enc = $('#enc').val();

    $.ajax({
        url:  baseUrl + '/healthinsurance/claim/claim-bills',
        type: "GET",
        data: {
            enc: enc,
           },
        success: function(response) {

            if(response.data.status == false){
                showAlert(response.data.msg,'error');


            }else{
                showAlert('Items Claimed');

            }
            
            
        },

        error: function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });

});

$("#modal_result").on('click', '.btnupload', function() {

    var currentRow = $(this).closest("tr");
    var bill = currentRow.find(".fldbill").html();
    var upload = currentRow.find(".btnupload");

    var enc = $('#enc').val();

    upload.prop('disabled', true);

    $.ajax({
        url:  baseUrl + '/healthinsurance/claim/bill-upload-status',
        type: "GET",
        data: {
            enc: enc,
            bill: bill,
           },
        success: function(response) {

            if(response.data.status == false){
                showAlert(response.data.message,'error');


            }else{
                showAlert(response.data.message);

            }
            
            
        },

        error: function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });

});