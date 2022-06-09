<script type="text/javascript">
    var procNames = <?php echo json_encode($procNames);?>;

    $(document).on('keyup','.search-input',function() {
        var $rows = $('#list-table tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $('#from_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,
           
       });

    $('#to_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,
           
       });

    function loadData(){
        if($('#category').val() != "%"){
            $('#selectedItem').val("");
            $('#method').prop('disabled', 'disabled');
            var url = "{{route('medical.report.loaddata')}}";
            $.ajax({
                url: url,
                type: "GET",
                data:  {
                            category: $('#category').val()
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#listing-table').html(response.data.html);
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    $(document).on('click','.item-td',function(){
        var selectedItem = $(this).attr('data-item');
        $('#selectedItem').val(selectedItem);
        $('#listing-table').find('.select_td').removeClass("select_td");
        $(this).addClass('select_td');
        if($('#category').val() == "Examination" || $('#category').val() == "Diagnostic Tests" || $('#category').val() == "Radio Diagnostics" || $('#category').val() == "Clinical Demographics" || $('#category').val() == "Patient Demographics"){
            $('#method').prop('disabled', false);
            var url = "{{route('medical.report.selectitem')}}";
            $.ajax({
                url: url,
                type: "GET",
                data:  {
                            selectedItem: selectedItem,
                            category: $('#category').val()
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#method').html(response.data.options);
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }else{
            $('#method').prop('disabled', 'disabled');
        }
    });

    $(document).on('change','#time',function(){
        if($(this).val() == "AnyTime"){
            $('#proctype option[value="Delivery"]').prop("disabled","disabled");
            $('#proctype option[value=""]').prop("selected","selected");
        }else{
            $('#proctype option[value="Delivery"]').prop("disabled",false);
        }
    });

    $(document).on('change','#proctype',function(){
        var selectedProcType = $(this).val();
        $('#procname').html("");
        $('#procname').html(new Option("",""))
        if(selectedProcType == "Procedure"){
            $.each( procNames, function( key, data ) {
                $('#procname').append(new Option(data.flditem,data.flditem))
            });
        }
    });

    function getRefreshData(page){
        if($('#selectedItem').val() != ""){
            var url = "{{route('medical.report.refreshdata')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "POST",
                data:  {
                            category: $('#category').val(), 
                            from_date: $('#from_date').val(),
                            to_date: $('#to_date').val(),
                            selectedItem: $('#selectedItem').val(),
                            gender: $('#gender').val(),
                            minAge: $('#minAge').val(),
                            maxAge: $('#maxAge').val(),
                            diagnosis: $('#diagnosis').val(),
                            time: $('#time').val(),
                            proctype: $('#proctype').val(),
                            procname: $('#procname').val(),
                            method: $('#method').val(),
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#table_result').html(response.data.html);
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    function pdfExport(){
        var urlReport = baseUrl + "/mainmenu/medical-report/refresh-data?category=" + $('#category').val() + "&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&selectedItem=" + $('#selectedItem').val() + "&gender=" + $('#gender').val() + "&minAge=" + $('#minAge').val() + "&maxAge=" + $('#maxAge').val() + "&diagnosis=" + $('#diagnosis').val() + "&time=" + $('#time').val() + "&proctype=" + $('#proctype').val() + "&procname=" + $('#procname').val() + "&method=" + $('#method').val() + "&isExport=true";
        window.open(urlReport, '_blank');
    }

    function excelExport(){
        var urlReport = baseUrl + "/mainmenu/medical-report/export-report?category=" + $('#category').val() + "&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&selectedItem=" + $('#selectedItem').val() + "&gender=" + $('#gender').val() + "&minAge=" + $('#minAge').val() + "&maxAge=" + $('#maxAge').val() + "&diagnosis=" + $('#diagnosis').val() + "&time=" + $('#time').val() + "&proctype=" + $('#proctype').val() + "&procname=" + $('#procname').val() + "&method=" + $('#method').val();
        window.open(urlReport);
    }

    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault(); 
          var page = $(this).attr('href').split('page=')[1];
          getRefreshData(page);
         });
    });
</script>