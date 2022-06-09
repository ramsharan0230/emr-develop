<div class="iq-card iq-card-block iq-card-stretch">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">Diagnosis:</h4>
        </div>
        <div class="allergy-add">
            @if(isset($enable_freetext) and $enable_freetext  == '1')
            <a href="javascript:void(0);" class="iq-bg-primary" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()">
                <i class="ri-add-fill"></i>
            </a>&nbsp;
            @else
            <a href="javascript:void(0);" class="iq-bg-primary" disabled>
                <i class="ri-add-fill"></i>
            </a>&nbsp;
            @endif
            @if(isset($patient) and $patient->fldptsex == 'Female')
            <a href="javascript:void(0);" class="iq-bg-primary {{ $disableClass }}" id="pro_obstetric" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="obstetric.displayModal()">
                <i class="ri-add-fill"></i>
            </a>&nbsp;
            @endif
            <a href="javascript:void(0);" class="iq-bg-primary {{ $disableClass }}" data-toggle="modal" data-target="#diagnosis">
                <i class="ri-add-fill"></i>
            </a>&nbsp;
            <a href="javascript:void(0);" class="iq-bg-warning"><i class="ri-information-fill"></i></a>&nbsp;
            <a href="javascript:void(0);" class="iq-bg-danger {{ $disableClass }}" id="deletealdiagno"><i class="ri-delete-bin-5-fill"></i></a>
        </div>
    </div>
    <div class="collapse-body">
        <div class="form-group mb-0">
            <select id="select-multiple-diagno" class="form-control" multiple>
                @if(isset($patdiago) and count($patdiago) > 0)
                    @foreach($patdiago as $patdiag)
                        <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                    @endforeach
                @else
                    <option value="">No Diagnosis Found</option>
                @endif
            </select>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    // $('#deletealdiagno').on('click', function() {
    //     if (confirm('Delete Diagnosis??')) {
    //         $('#select-multiple-diagno').each(function() {
    //             var finalval = $(this).val().toString();
    //             var url = $('.delete_pat_findings').val();

    //             $.ajax({
    //                 url: url,
    //                 type: "POST",
    //                 dataType: "json",
    //                 data: { ids: finalval },
    //                 success: function(data) {
    //                     if ($.isEmptyObject(data.error)) {
    //                         showAlert('Deleted Data!!');
    //                         $('#select-multiple-diagno option:selected').remove();
    //                     } else
    //                         showAlert('Something went wrong!!');
    //                 }
    //             });
    //         });
    //     }
    // });

    var table = $('table.datatable').DataTable({
        "paging":   false
    });

    $('#searchbygroup').on('click', function(){
        var groupname = $('#diagnogroup').val();
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

    $(document).on('click', '.clickable-row', function () {
    // alert('click bhayo');
    var diagnocode = $(this).data('code');
    $('#code').val(diagnocode);
    $('#diagno-code').val(diagnocode);
    if (diagnocode.length > 0) {
        // alert(diagnocode);
        $.get("getDiagnosisByCode", {term: diagnocode}, {dataType: 'json'}).done(function (data) {
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
});

$('.onclose').on('click', function () {

    $('input[name="dccat"]').prop("checked", false);
    $('#code').val('');
    $("#diagnosissubname").val('');
    $("#sublist").val('');
});


$(document).on('click', '.clickable-subrow', function () {
    // alert('click sub bhayo');
// clickable-subrow
var diagnosubcode = $(this).data('subcode');
// alert(diagnosubcode)
if (diagnosubcode.length > 0) {
    $('#diagnosissubname').val(diagnosubcode);
}else{
    $("#diagnosissubname").val('');
}
    // $('input[name="diagnosissub"]').bind('click', function () {
    //     $('input[name="diagnosissub"]').not(this).prop("checked", false);
    // });
    // var diagnosub = $("input[name='diagnosissub']");

    // if (diagnosub.is(':checked')) {
    //     var value = $(this).val();

    //     $('#diagnosissubname').val(value);
    // } else {
    //     $("#diagnosissubname").val('');
    // }
});

    // $(document).on('click', '#submitfreetextdiagnosis', function(e) {
    //     e.preventDefault();
    //     var data = $(this).closest('form').serialize();
    //     var url = $(this).closest('form').attr('action');

    //     var value = $('#custom_diagnosis').val();
    //     if (value != '') {
    //         $.ajax({
    //             url: url,
    //             type: "POST",
    //             // dataType: "json",
    //             data: data,
    //             success: function (data) {
    //                 $('#select-multiple-diagno').append('<option value="0">' + value + '</option>');
    //                 $('#diagnosis-freetext-modal').modal('hide');
    //                 showAlert("Data updated Successfully.");
    //             },
    //             error: function (data) {
    //                 showAlert("Failed to update data.");
    //             }
    //         });
    //     } else
    //         showAlert("Please enter value to save.");
    // });

    // $(document).on('click', '#submitdiagnosis', function(e) {
    //     e.preventDefault();
    //     var formElem = $(this).closest('form');
    //     $.ajax({
    //         url: $(formElem).attr('action'),
    //         type: "POST",
    //         // dataType: "json",
    //         data: $(formElem).serialize(),
    //         success: function (data) {
    //             $('#select-multiple-diagno').append('<option value="' + data.fldid + '">' + data.fldvalue + '</option>');
    //             $('#diagnosis').modal('hide');
    //             showAlert("Data updated Successfully.");
    //         },
    //         error: function (data) {
    //             showAlert("Failed to update data.");
    //         }
    //     });
    // });
</script>
@endpush
